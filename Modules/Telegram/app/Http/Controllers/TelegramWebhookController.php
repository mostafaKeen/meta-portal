<?php

namespace Modules\Telegram\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Telegram\Models\TelegramChat;
use Modules\Telegram\Models\TelegramMessage;
use Modules\Telegram\Jobs\DownloadTelegramMedia;

class TelegramWebhookController extends Controller
{
    /**
     * Handle incoming Telegram webhook updates.
     */
    public function handle(Request $request, string $token)
    {
        $requestId = uniqid('tg_');
        $update = $request->all();

        Log::info("Telegram Webhook Request [{$requestId}]", [
            'token_suffix' => substr($token, -5),
            'payload' => $update
        ]);

        // 1. Find the bot by token
        $bot = \Modules\Company\Models\TelegramBot::where('token', $token)->where('status', 'active')->first();
        
        if (!$bot) {
            Log::warning("Telegram Webhook [{$requestId}] - Unknown or inactive token.");
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Log::info("Telegram Webhook [{$requestId}] - Processing for bot: {$bot->name}");

        // We only handle 'message' for core chatting
        if (!isset($update['message'])) {
            Log::debug("Telegram Webhook [{$requestId}] - No message field in update.");
            return response('OK', 200);
        }

        $message = $update['message'];
        $telegramMessageId = (string)($message['message_id'] ?? '');
        $chat = $message['chat'];
        $chatId = (string)$chat['id'];

        // 2. Idempotency Check (scoped by bot_id — message IDs are only unique per bot)
        if ($telegramMessageId && TelegramMessage::where('bot_id', $bot->id)->where('telegram_message_id', $telegramMessageId)->exists()) {
            return response('Duplicate', 200);
        }

        // 3. Create/Update Chat
        $telegramChat = TelegramChat::updateOrCreate(
            ['chat_id' => $chatId, 'bot_id' => $bot->id],
            [
                'first_name' => $chat['first_name'] ?? null,
                'last_name' => $chat['last_name'] ?? null,
                'username' => $chat['username'] ?? null,
                'last_message_at' => now(),
            ]
        );

        // 4. Determine Media Type & File ID
        $mediaType = 'text';
        $fileId = null;
        $prefix = 'file_';

        if (isset($message['photo'])) {
            $mediaType = 'photo';
            $bestPhoto = end($message['photo']); // Largest size
            $fileId = $bestPhoto['file_id'];
            $prefix = 'img_';
        } elseif (isset($message['voice'])) {
            $mediaType = 'voice';
            $fileId = $message['voice']['file_id'];
            $prefix = 'voice_';
        } elseif (isset($message['document'])) {
            $mediaType = 'document';
            $fileId = $message['document']['file_id'];
            $prefix = 'doc_';
        } elseif (isset($message['video'])) {
            $mediaType = 'video';
            $fileId = $message['video']['file_id'];
            $prefix = 'vid_';
        }

        // 5. Store Message
        $telegramMessage = TelegramMessage::create([
            'bot_id' => $bot->id,
            'telegram_chat_id' => $telegramChat->id,
            'direction' => 'in',
            'telegram_message_id' => $telegramMessageId,
            'text' => $message['text'] ?? ($message['caption'] ?? null),
            'media_type' => $mediaType,
            'metadata' => $message,
        ]);

        // 6. Queue Media Download if applicable
        if ($fileId) {
            DownloadTelegramMedia::dispatch($telegramMessage, $fileId, $prefix);
        }

        return response('OK', 200);
    }
}
