<?php

namespace Modules\WhatsAppQR\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('whatsappqr.node_url', 'http://localhost:3000');
    }

    public function startSession(string $sessionId)
    {
        try {
            $response = Http::post("{$this->baseUrl}/sessions/create", [
                'session_id' => $sessionId
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error("WhatsApp Session Start Error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function sendMessage(string $sessionId, string $to, string $text, array $media = [])
    {
        try {
            $payload = [
                'session_id' => $sessionId,
                'to' => $to,
                'text' => $text,
            ];

            if (!empty($media)) {
                $payload['media'] = $media;
            }

            $response = Http::post("{$this->baseUrl}/messages/send", $payload);
            $data = $response->json() ?? [];
            if (!$response->successful()) {
                Log::error("WhatsApp API Error: " . ($data['error'] ?? 'Unknown error'));
                return ['success' => false, 'error' => $data['error'] ?? 'API error'];
            }
            return $data;
        } catch (\Exception $e) {
            Log::error("WhatsApp Send Message Error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function deleteSession(string $sessionId)
    {
        try {
            $response = Http::delete("{$this->baseUrl}/sessions/{$sessionId}");
            return $response->json();
        } catch (\Exception $e) {
            Log::error("WhatsApp Session Delete Error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
