<?php

namespace Modules\Telegram\Observers;

use Modules\Company\Models\TelegramBot;
use Modules\Telegram\Services\TelegramService;
use Illuminate\Support\Facades\Log;

class TelegramBotObserver
{
    public function __construct(
        protected TelegramService $telegramService
    ) {}

    /**
     * Handle the TelegramBot "saved" event.
     */
    public function saved(TelegramBot $bot): void
    {
        if ($bot->isDirty('token') || $bot->wasRecentlyCreated) {
            $this->registerWebhook($bot);
        }
    }

    /**
     * Handle the TelegramBot "deleted" event — remove webhook.
     */
    public function deleted(TelegramBot $bot): void
    {
        try {
            $this->telegramService->forBot($bot)->deleteWebhook(dropPendingUpdates: true);
            Log::info("Telegram Webhook removed for deleted bot: {$bot->name}");
        } catch (\Exception $e) {
            Log::error("Error removing Telegram Webhook on delete: " . $e->getMessage());
        }
    }

    /**
     * Register the webhook with Telegram.
     */
    protected function registerWebhook(TelegramBot $bot): void
    {
        try {
            $service = $this->telegramService->forBot($bot);

            // Step 1: Delete old webhook first
            $service->deleteWebhook(dropPendingUpdates: true);

            // Step 2: Build the webhook URL manually using APP_URL
            // Route name is 'api.telegram.webhook' (RouteServiceProvider prefixes with 'api.')
            $webhookUrl = rtrim(config('app.url'), '/') . '/api/telegram/webhook/' . $bot->token;

            Log::info("Registering Telegram Webhook", [
                'bot' => $bot->name,
                'bot_id' => $bot->id,
                'webhook_url' => $webhookUrl,
            ]);

            // Step 3: Set the new webhook
            $success = $service->setWebhook($webhookUrl);

            if ($success) {
                Log::info("Telegram Webhook registered successfully for bot: {$bot->name}");
                
                // Step 4: Verify
                $info = $service->getWebhookInfo();
                Log::info("Telegram Webhook verification", ['info' => $info]);
            } else {
                Log::error("Failed to register Telegram Webhook for bot: {$bot->name}");
            }
        } catch (\Exception $e) {
            Log::error("Error registering Telegram Webhook: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
