<?php

namespace Modules\Telegram\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Telegram\Models\TelegramMessage;
use Modules\Telegram\Models\TelegramChat;

class TelegramService
{
    protected ?string $token = null;
    protected ?string $baseUrl = null;
    protected ?\Modules\Company\Models\TelegramBot $bot = null;

    public function __construct()
    {
        // Default to config if available (for single bot setups)
        $this->token = config('telegram.token');
        if ($this->token) {
            $this->baseUrl = "https://api.telegram.org/bot{$this->token}";
        }
    }

    /**
     * Set the bot to use for subsequent requests.
     */
    public function forBot(\Modules\Company\Models\TelegramBot $bot): self
    {
        $this->bot = $bot;
        $this->token = $bot->token;
        $this->baseUrl = "https://api.telegram.org/bot{$this->token}";
        
        return $this;
    }

    public function sendMessage(string $telegramChatId, string $text, array $options = []): ?TelegramMessage
    {
        return $this->sendRequest('sendMessage', array_merge([
            'chat_id' => $telegramChatId,
            'text' => $text,
        ], $options), 'text', $text);
    }

    public function sendPhoto(string $telegramChatId, $photo, ?string $caption = null): ?TelegramMessage
    {
        return $this->sendRequest('sendPhoto', [
            'chat_id' => $telegramChatId,
            'photo' => $photo,
            'caption' => $caption,
        ], 'photo', $caption);
    }

    public function sendVoice(string $telegramChatId, $voice, ?string $caption = null): ?TelegramMessage
    {
        return $this->sendRequest('sendVoice', [
            'chat_id' => $telegramChatId,
            'voice' => $voice,
            'caption' => $caption,
        ], 'voice', $caption);
    }

    public function sendDocument(string $telegramChatId, $document, ?string $caption = null): ?TelegramMessage
    {
        return $this->sendRequest('sendDocument', [
            'chat_id' => $telegramChatId,
            'document' => $document,
            'caption' => $caption,
        ], 'document', $caption);
    }

    protected function sendRequest(string $method, array $params, string $type, ?string $text): ?TelegramMessage
    {
        if (!$this->baseUrl) {
            throw new \Exception("Telegram Bot Token not set.");
        }

        $request = Http::asMultipart();
        
        // Convert params to multipart
        foreach ($params as $key => $value) {
            if ($value === null) continue;
            
            if (is_resource($value) || $value instanceof \Illuminate\Http\UploadedFile) {
                $request->attach($key, $value instanceof \Illuminate\Http\UploadedFile ? fopen($value->getRealPath(), 'r') : $value, $value instanceof \Illuminate\Http\UploadedFile ? $value->getClientOriginalName() : null);
            } else {
                $request->attach($key, (string)$value);
            }
        }

        $response = $request->post("{$this->baseUrl}/{$method}");

        if ($response->successful()) {
            $data = $response['result'];
            $telegramChatId = $params['chat_id'];

            // Find the database chat record
            $chat = TelegramChat::where('bot_id', $this->bot?->id)
                ->where('chat_id', $telegramChatId)
                ->first();

            // Store local copy if it's a file
            $mediaPath = null;
            if (isset($params['photo']) || isset($params['voice']) || isset($params['document'])) {
                $file = $params['photo'] ?? ($params['voice'] ?? $params['document']);
                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    $mediaPath = $file->store("telegram/media", 'public');
                }
            }

            // Save outgoing message
            return TelegramMessage::create([
                'bot_id' => $this->bot?->id,
                'telegram_chat_id' => $chat?->id,
                'direction' => 'out',
                'telegram_message_id' => $data['message_id'],
                'text' => $text,
                'media_type' => $type,
                'media_path' => $mediaPath,
                'metadata' => $data,
            ]);
        }

        Log::error("Telegram {$method} failed", [
            'bot' => $this->bot?->name,
            'chat_id' => $params['chat_id'] ?? 'unknown',
            'response' => $response->json(),
            'status' => $response->status(),
        ]);

        return null;
    }

    /**
     * Remove the current webhook (must be called before setWebhook).
     * https://core.telegram.org/bots/api#deletewebhook
     */
    public function deleteWebhook(bool $dropPendingUpdates = false): bool
    {
        $response = Http::post("{$this->baseUrl}/deleteWebhook", [
            'drop_pending_updates' => $dropPendingUpdates,
        ]);

        $json = $response->json();
        Log::info("Telegram deleteWebhook response", ['bot' => $this->bot?->name, 'response' => $json]);

        return $response->successful() && ($json['result'] ?? false);
    }

    /**
     * Set the webhook for the bot.
     * https://core.telegram.org/bots/api#setwebhook
     */
    public function setWebhook(string $url, array $options = []): bool
    {
        $params = array_merge(['url' => $url], $options);

        $response = Http::post("{$this->baseUrl}/setWebhook", $params);

        $json = $response->json();
        Log::info("Telegram setWebhook response", [
            'bot' => $this->bot?->name,
            'url' => $url,
            'response' => $json,
        ]);

        return $response->successful() && ($json['result'] ?? false);
    }

    /**
     * Get current webhook status.
     * https://core.telegram.org/bots/api#getwebhookinfo
     */
    public function getWebhookInfo(): array
    {
        return Http::get("{$this->baseUrl}/getWebhookInfo")->json();
    }

    /**
     * Get basic info about the bot.
     * https://core.telegram.org/bots/api#getme
     */
    public function getMe(): array
    {
        return Http::get("{$this->baseUrl}/getMe")->json();
    }
}
