<?php

namespace Modules\Telegram\Console;

use Illuminate\Console\Command;
use Modules\Company\Models\TelegramBot;
use Modules\Telegram\Services\TelegramService;

class RegisterWebhookCommand extends Command
{
    protected $signature = 'telegram:webhook {token? : The bot token to register}';
    protected $description = 'Register Telegram webhook for a bot (deletes old webhook first)';

    public function handle(TelegramService $service): int
    {
        $token = $this->argument('token');

        if ($token) {
            $bot = TelegramBot::where('token', $token)->first();
            if (!$bot) {
                $this->error("Bot with this token not found in database.");
                return 1;
            }
            $this->registerForBot($service, $bot);
        } else {
            $bots = TelegramBot::where('status', 'active')->get();
            if ($bots->isEmpty()) {
                $this->warn("No active bots found.");
                return 0;
            }
            foreach ($bots as $bot) {
                $this->registerForBot($service, $bot);
            }
        }

        return 0;
    }

    protected function registerForBot(TelegramService $service, TelegramBot $bot): void
    {
        $this->info("Processing bot: {$bot->name} (ID: {$bot->id})");

        $s = $service->forBot($bot);

        // Step 1: Delete old webhook
        $this->warn("  → Deleting old webhook...");
        $s->deleteWebhook(dropPendingUpdates: true);

        // Step 2: Set new webhook
        $webhookUrl = rtrim(config('app.url'), '/') . '/api/telegram/webhook/' . $bot->token;
        $this->info("  → Setting webhook to: {$webhookUrl}");
        $result = $s->setWebhook($webhookUrl);
        
        if ($result) {
            $this->info("  ✓ Webhook registered successfully!");
        } else {
            $this->error("  ✗ Failed to register webhook.");
        }

        // Step 3: Verify
        $info = $s->getWebhookInfo();
        $this->info("  → Current webhook URL: " . ($info['result']['url'] ?? 'EMPTY'));
        $this->info("  → Pending updates: " . ($info['result']['pending_update_count'] ?? 0));
        
        if (!empty($info['result']['last_error_message'])) {
            $this->error("  → Last error: " . $info['result']['last_error_message']);
        }
    }
}
