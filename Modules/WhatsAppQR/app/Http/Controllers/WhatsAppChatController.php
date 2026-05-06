<?php

namespace Modules\WhatsAppQR\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\WhatsAppQR\Models\WhatsAppChat;
use Modules\WhatsAppQR\Models\WhatsAppMessage;
use Modules\WhatsAppQR\Services\WhatsAppService;
use Modules\WhatsAppQR\Events\WhatsAppMessageReceived;
use Illuminate\Support\Facades\Storage;

class WhatsAppChatController extends Controller
{
    public function startChat(Request $request)
    {
        $request->validate([
            'whatsapp_number_id' => 'required|exists:whatsapp_numbers,id',
            'phone' => 'required|string',
        ]);

        $number = \Modules\Company\Models\WhatsappNumber::findOrFail($request->whatsapp_number_id);

        if ($number->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        // Clean phone number
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        
        $chat = WhatsAppChat::firstOrCreate(
            ['whatsapp_number_id' => $number->id, 'chat_id' => $phone],
            ['name' => $phone]
        );

        return response()->json(['success' => true, 'chat' => $chat->load('messages')]);
    }

    public function getMessages(Request $request, $chatId)
    {
        $chat = WhatsAppChat::with(['messages' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(50);
        }])->findOrFail($chatId);

        if ($chat->whatsappNumber->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        return response()->json([
            'success' => true, 
            'messages' => $chat->messages
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:whatsapp_chats,id',
            'text' => 'required_without:media|nullable|string',
            'media' => 'nullable|file|max:10240', // 10MB limit
        ]);

        $chat = WhatsAppChat::with('whatsappNumber')->findOrFail($request->chat_id);
        $number = $chat->whatsappNumber;

        if ($number->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $service = app(WhatsAppService::class);
        $mediaData = [];

        if ($request->hasFile('media')) {
            $path = $request->file('media')->store('whatsapp/media', 'public');
            $mediaData = [
                'media_url' => asset('storage/' . $path),
                'type' => $this->getMediaType($request->file('media')),
            ];
        }

        $response = $service->sendMessage(
            $number->session_name,
            $chat->chat_id,
            $request->text ?? '',
            $mediaData
        );

        if (isset($response['success']) && $response['success']) {
            $message = WhatsAppMessage::create([
                'whatsapp_chat_id' => $chat->id,
                'message_id' => $response['result']['key']['id'] ?? null,
                'direction' => 'out',
                'text' => $request->text ?? '',
                'media_type' => $mediaData['type'] ?? 'text',
                'media_path' => $mediaData['media_url'] ?? null,
            ]);

            $chat->update(['last_message_at' => now()]);

            // Broadcast so UI updates in real-time for all listeners
            try {
                broadcast(new \Modules\WhatsAppQR\Events\WhatsAppMessageReceived($message, $number->company_id));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning("WhatsApp Broadcast Error: " . $e->getMessage());
            }

            return response()->json([
                'success' => true, 
                'message' => [
                    'id' => $message->id,
                    'whatsapp_chat_id' => $message->whatsapp_chat_id,
                    'message_id' => $message->message_id,
                    'direction' => $message->direction,
                    'text' => $message->text,
                    'media_type' => $message->media_type,
                    'media_path' => $message->media_path,
                    'created_at' => $message->created_at->toISOString(),
                ]
            ]);
        }




        return response()->json(['success' => false, 'error' => $response['error'] ?? 'Failed to send message'], 422);
    }

    protected function getMediaType($file)
    {
        $mime = $file->getMimeType();
        if (str_contains($mime, 'image')) return 'image';
        if (str_contains($mime, 'audio') || str_contains($mime, 'video')) return 'audio';
        return 'document';
    }
}
