<?php

namespace Modules\WhatsAppQR\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Company\Models\WhatsappNumber;
use Modules\WhatsAppQR\Models\WhatsAppChat;
use Modules\WhatsAppQR\Models\WhatsAppMessage;
use Modules\WhatsAppQR\Events\WhatsAppQRReceived;
use Modules\WhatsAppQR\Events\WhatsAppMessageReceived;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $event = $request->input('event');
        $sessionId = $request->input('session_id');

        Log::info("WhatsApp Webhook Received: {$event} for session {$sessionId}");

        $number = WhatsappNumber::where('session_name', $sessionId)->first();

        if (!$number) {
            Log::warning("WhatsApp Webhook Error: Session ID '{$sessionId}' not found in database.");
            return response()->json(['error' => "Session '{$sessionId}' not found"], 404);
        }

        switch ($event) {
            case 'qr':
                $qr = $request->input('qr');
                $number->update(['qr_code' => $qr, 'status' => 'inactive']);
                
                // Broadcast QR to frontend — non-blocking
                try {
                    broadcast(new WhatsAppQRReceived($number, $qr));
                } catch (\Exception $e) {
                    Log::warning("QR broadcast failed: " . $e->getMessage());
                }
                break;

            case 'connection_status':
                $status = $request->input('status');
                Log::info("WhatsApp Status Update: {$status} for session {$sessionId}");
                $newStatus = ($status === 'open' || $status === 'connected') ? 'active' : 'inactive';
                $number->update(['status' => $newStatus]);
                
                if ($newStatus === 'active') {
                    $number->update(['qr_code' => null]);
                }

                try {
                    broadcast(new \Modules\WhatsAppQR\Events\WhatsAppStatusUpdated($number, $newStatus));
                } catch (\Exception $e) {
                    Log::warning("Status broadcast failed: " . $e->getMessage());
                }
                break;

            case 'validate_session':
                Log::info("Session validation: {$sessionId} exists (id={$number->id})");
                break;

            case 'message':
                $this->handleMessage($number, $request->input('data'));
                break;
        }

        return response()->json(['success' => true]);
    }

    protected function handleMessage($number, $data)
    {
        try {
            $direction = $data['direction'] ?? 'in';
            $chatId = $data['from'];

            $chat = WhatsAppChat::firstOrCreate(
                ['whatsapp_number_id' => $number->id, 'chat_id' => $chatId],
                ['name' => $data['name'] ?? $chatId]
            );

            // Update name if we now have a pushName and didn't before
            if (!empty($data['name']) && $chat->name === $chatId) {
                $chat->update(['name' => $data['name']]);
            }

            // Use updateOrCreate with message_id to handle duplicates gracefully
            $messageId = $data['message_id'] ?? null;

            if ($messageId) {
                // Check if this exact message already exists (prevent duplicates)
                $existing = WhatsAppMessage::where('message_id', $messageId)->first();
                if ($existing) {
                    Log::info("Duplicate message_id {$messageId} — skipping.");
                    return;
                }
            }

            $message = WhatsAppMessage::create([
                'whatsapp_chat_id' => $chat->id,
                'message_id' => $messageId,
                'reaction_to' => $data['reaction_to'] ?? null,
                'direction' => $direction,
                'text' => $data['text'] ?? '',
                'media_type' => $data['type'] ?? 'text',
                'media_path' => $data['media_url'] ?? null,
            ]);


            $chat->update(['last_message_at' => now()]);

            // Broadcast new message instantly (ShouldBroadcastNow)
            broadcast(new WhatsAppMessageReceived($message, $number->company_id));

        } catch (\Exception $e) {
            Log::error("WhatsApp handleMessage error: " . $e->getMessage(), [
                'session' => $number->session_name,
                'data' => $data,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
