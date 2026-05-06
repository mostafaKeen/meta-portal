import 'dotenv/config';
import makeWASocket, { 
    useMultiFileAuthState, 
    DisconnectReason, 
    makeCacheableSignalKeyStore,
    fetchLatestBaileysVersion,
    downloadMediaMessage
} from '@whiskeysockets/baileys';

import express from 'express';
import cors from 'cors';
import pino from 'pino';
import axios from 'axios';
import path from 'path';
import fs from 'fs';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();
app.use(cors());
app.use(express.json());

const logger = pino({ level: 'info' });
const sessions = new Map();
const webhookUrl = process.env.LARAVEL_WEBHOOK_URL;

// ─── Webhook helper ───────────────────────────────────────────────────────────
async function notifyLaravel(data, retries = 2) {
    for (let attempt = 0; attempt <= retries; attempt++) {
        try {
            const response = await axios.post(webhookUrl, data, { timeout: 10000 });
            return response.data;
        } catch (error) {
            const status = error.response?.status;
            const message = error.response?.data?.error || error.message;
            logger.error(`Webhook Error [${status}] (attempt ${attempt + 1}): ${message}`);

            // Don't retry on 404 — session not found in Laravel
            if (status === 404) return false;

            // Don't retry on 4xx client errors
            if (status && status >= 400 && status < 500) return false;

            // Wait before retrying server errors / network issues
            if (attempt < retries) {
                await new Promise(r => setTimeout(r, 1000 * (attempt + 1)));
            }
        }
    }
    return false;
}

// ─── Validate session exists in Laravel before starting ───────────────────────
async function validateSessionInLaravel(sessionId) {
    try {
        const response = await axios.post(webhookUrl, {
            event: 'validate_session',
            session_id: sessionId
        }, { timeout: 5000 });
        return response.status === 200;
    } catch (error) {
        const status = error.response?.status;
        if (status === 404) {
            logger.warn(`Session '${sessionId}' does not exist in Laravel DB. Skipping.`);
            return false;
        }
        // Network error — assume valid (don't delete on network failure)
        logger.warn(`Could not validate session '${sessionId}' (network error). Starting anyway.`);
        return true;
    }
}

// ─── Clean up orphaned session folder ─────────────────────────────────────────
function cleanupSessionFolder(sessionId) {
    const sessionDir = path.join(__dirname, 'sessions', sessionId);
    if (fs.existsSync(sessionDir)) {
        try {
            fs.rmSync(sessionDir, { recursive: true, force: true });
            logger.info(`Cleaned up orphaned session folder: ${sessionId}`);
        } catch (e) {
            logger.warn(`Failed to clean session folder ${sessionId}: ${e.message}`);
        }
    }
}

// ─── Core session logic ───────────────────────────────────────────────────────
async function startSession(sessionId) {
    if (sessions.has(sessionId)) {
        logger.info(`Session ${sessionId} already exists, skipping.`);
        return;
    }

    logger.info(`Starting session: ${sessionId}`);
    const sessionDir = path.join(__dirname, 'sessions', sessionId);
    
    const { state, saveCreds } = await useMultiFileAuthState(sessionDir);
    const { version } = await fetchLatestBaileysVersion();

    const sock = makeWASocket({
        version,
        logger: pino({ level: 'silent' }),
        auth: {
            creds: state.creds,
            keys: makeCacheableSignalKeyStore(state.keys, pino({ level: 'silent' })),
        },
        browser: ['MetaPortal', 'Chrome', '110.0.0'],
        connectTimeoutMs: 60000,
        defaultQueryTimeoutMs: 0,
        keepAliveIntervalMs: 10000,
    });

    sock.reconnectAttempts = 0;
    sessions.set(sessionId, sock);

    sock.ev.on('creds.update', saveCreds);

    sock.ev.on('connection.update', async (update) => {
        const { connection, lastDisconnect, qr } = update;

        if (qr) {
            logger.info(`QR code generated for session ${sessionId}`);
            await notifyLaravel({
                event: 'qr',
                session_id: sessionId,
                qr: qr
            });
        }

        if (connection === 'close') {
            const statusCode = lastDisconnect?.error?.output?.statusCode;
            const shouldReconnect = statusCode !== DisconnectReason.loggedOut && statusCode !== 401;
            const isTimeout = statusCode === DisconnectReason.timedOut || statusCode === 408;
            
            const reasonMap = {
                [DisconnectReason.badSession]: 'Bad Session',
                [DisconnectReason.connectionClosed]: 'Connection Closed',
                [DisconnectReason.connectionLost]: 'Connection Lost',
                [DisconnectReason.connectionReplaced]: 'Connection Replaced',
                [DisconnectReason.loggedOut]: 'Logged Out',
                [DisconnectReason.restartRequired]: 'Restart Required',
                [DisconnectReason.timedOut]: 'Timed Out',
                401: 'Unauthorized'
            };
            const reason = reasonMap[statusCode] || 'Unknown';
            logger.info(`Connection closed for ${sessionId}. Status: ${statusCode} (${reason}). Reconnecting: ${shouldReconnect}`);
            
            const result = await notifyLaravel({
                event: 'connection_status',
                session_id: sessionId,
                status: 'disconnected'
            });

            sessions.delete(sessionId);
            
            // If Laravel returned 404, stop reconnecting and clean up
            if (result === false) {
                logger.warn(`Session ${sessionId} not found in Laravel. Cleaning up...`);
                cleanupSessionFolder(sessionId);
                return;
            }

            // Increment reconnect attempts
            const attempts = (sock.reconnectAttempts || 0) + 1;
            
            // Allow more retries for timeouts (QR waiting to be scanned)
            const maxAttempts = isTimeout ? 15 : 5;
            
            if (shouldReconnect && attempts <= maxAttempts) {
                const delay = isTimeout 
                    ? 3000  // Quick retry for QR timeouts  
                    : Math.min(5000 * attempts, 30000); // Exponential for other errors
                    
                logger.info(`Reconnecting ${sessionId} (attempt ${attempts}/${maxAttempts}) in ${delay/1000}s...`);
                setTimeout(() => {
                    startSession(sessionId).then(() => {
                        const newSock = sessions.get(sessionId);
                        if (newSock) newSock.reconnectAttempts = attempts;
                    });
                }, delay);
            } else if (shouldReconnect) {
                logger.error(`Max reconnect attempts (${maxAttempts}) reached for ${sessionId}. Giving up.`);
            }
        } else if (connection === 'open') {
            logger.info(`Session ${sessionId} connected successfully!`);
            sock.reconnectAttempts = 0;
            await notifyLaravel({
                event: 'connection_status',
                session_id: sessionId,
                status: 'connected'
            });
        }
    });

    sock.ev.on('messages.upsert', async (m) => {
        if (m.type === 'notify') {
            for (const msg of m.messages) {
                const remoteJid = msg.key.remoteJid;
                
                // Filter out status updates and group messages
                if (!remoteJid || remoteJid === 'status@broadcast' || remoteJid.includes('@g.us')) {
                    continue;
                }

                const imageMessage = msg.message?.imageMessage;
                const audioMessage = msg.message?.audioMessage;
                const videoMessage = msg.message?.videoMessage;
                const documentMessage = msg.message?.documentMessage;
                const stickerMessage = msg.message?.stickerMessage;
                const reactionMessage = msg.message?.reactionMessage;

                let mediaType = 'text';
                let text = msg.message?.conversation 
                    || msg.message?.extendedTextMessage?.text
                    || '';

                if (imageMessage) mediaType = 'image';
                else if (audioMessage) mediaType = 'audio';
                else if (videoMessage) mediaType = 'video';
                else if (documentMessage) mediaType = 'document';
                else if (stickerMessage) mediaType = 'sticker';
                else if (reactionMessage) {
                    mediaType = 'reaction';
                    text = reactionMessage.text; // The emoji
                }

                // Download media if applicable
                let mediaUrl = null;
                const mediaMsg = imageMessage || audioMessage || videoMessage || documentMessage || stickerMessage;
                if (mediaMsg && mediaType !== 'text' && mediaType !== 'reaction') {
                    try {
                        const buffer = await downloadMediaMessage(msg, 'buffer', {}, { logger });
                        const ext = mediaType === 'image' ? 'jpg' : (mediaType === 'sticker' ? 'webp' : (mediaType === 'audio' ? 'ogg' : 'bin'));
                        const fileName = `${msg.key.id}_${Date.now()}.${ext}`;
                        const relativePath = path.join('whatsapp/media', fileName);
                        const fullPath = path.join(__dirname, '../public/storage', relativePath);
                        
                        // Ensure directory exists
                        if (!fs.existsSync(path.dirname(fullPath))) {
                            fs.mkdirSync(path.dirname(fullPath), { recursive: true });
                        }
                        
                        fs.writeFileSync(fullPath, buffer);
                        mediaUrl = `/storage/${relativePath}`;
                        logger.info(`Downloaded media to ${mediaUrl}`);
                    } catch (err) {
                        logger.error(`Error downloading media for session ${sessionId}: ${err.message}`);
                    }
                }

                // Forward BOTH incoming AND outgoing messages to Laravel
                const direction = msg.key.fromMe ? 'out' : 'in';
                
                logger.info(`Message [${direction}] from ${remoteJid} in session ${sessionId}: ${text.substring(0, 50)}`);
                
                // If it's a LID, try to extract the normal JID if available
                const realJid = msg.key.remoteJidAlt || msg.key.remoteJid;
                let fromId = realJid.split('@')[0];

                // AWAIT the webhook call — don't fire-and-forget
                await notifyLaravel({
                    event: 'message',
                    session_id: sessionId,
                    data: {
                        from: fromId,
                        lid: remoteJid.includes('@lid') ? remoteJid.split('@')[0] : null,
                        name: msg.pushName || '',
                        text: text,
                        message_id: msg.key.id,
                        reaction_to: reactionMessage?.key?.id || null,
                        type: mediaType,
                        media_url: mediaUrl,
                        direction: direction,
                        timestamp: msg.messageTimestamp
                    }
                });


            }
        }
    });
}

// ─── REST API ─────────────────────────────────────────────────────────────────
app.post('/sessions/create', async (req, res) => {
    const { session_id } = req.body;
    if (!session_id) return res.status(400).json({ error: 'session_id required' });
    
    startSession(session_id);
    res.json({ status: 'initializing', session_id });
});

app.post('/messages/send', async (req, res) => {
    const { session_id, to, text, media } = req.body;
    const sock = sessions.get(session_id);

    if (!sock) return res.status(404).json({ error: 'Session not found or not connected' });

    try {
        const jid = `${to}@s.whatsapp.net`;
        
        // Send immediately — no artificial typing delay
        let payload = { text };
        if (media && media.media_url) {
            const mediaType = media.type === 'image' ? 'image' : (media.type === 'audio' ? 'audio' : 'document');
            payload = {
                [mediaType]: { url: media.media_url },
                caption: text
            };
        }

        const result = await sock.sendMessage(jid, payload);
        res.json({ success: true, result });
    } catch (error) {
        logger.error(`Send message error for session ${session_id}: ${error.message}`);
        res.status(500).json({ error: error.message });
    }
});

app.get('/sessions/status', (req, res) => {
    const statuses = {};
    for (const [id, sock] of sessions.entries()) {
        statuses[id] = {
            connected: sock?.user ? true : false,
            user: sock?.user || null
        };
    }
    res.json(statuses);
});

app.get('/sessions/:id/status', (req, res) => {
    const sessionId = req.params.id;
    const sock = sessions.get(sessionId);
    
    if (!sock) return res.status(404).json({ exists: false, connected: false });
    
    res.json({
        exists: true,
        connected: sock?.user ? true : false,
        user: sock?.user || null
    });
});

app.delete('/sessions/:id', async (req, res) => {
    const sessionId = req.params.id;
    const sock = sessions.get(sessionId);

    if (sock) {
        try {
            await sock.logout();
        } catch (e) {
            logger.warn(`Error logging out session ${sessionId}: ${e.message}`);
        }
        sessions.delete(sessionId);
    }

    cleanupSessionFolder(sessionId);
    res.json({ success: true });
});

// ─── Startup ──────────────────────────────────────────────────────────────────
const PORT = process.env.PORT || 3000;
app.listen(PORT, async () => {
    logger.info(`WhatsApp Engine running on port ${PORT}`);
    
    // Auto-restart previous sessions — but validate against Laravel first
    const sessionsFolder = path.join(__dirname, 'sessions');
    if (fs.existsSync(sessionsFolder)) {
        const folders = fs.readdirSync(sessionsFolder)
            .filter(f => fs.lstatSync(path.join(sessionsFolder, f)).isDirectory());
        
        for (const folder of folders) {
            const isValid = await validateSessionInLaravel(folder);
            if (isValid) {
                await startSession(folder);
            } else {
                cleanupSessionFolder(folder);
            }
        }
    }
});
