<?php

namespace Modules\Telegram\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Telegram\Models\TelegramChat;
use Modules\Telegram\Models\TelegramMessage;
use Modules\Telegram\Events\TelegramMessageBroadcasting;

class ProcessTelegramUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected array $update,
        protected int $botId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $bot = \Modules\Company\Models\TelegramBot::find($this->botId);
        if (!$bot) return;

        if (!isset($this->update['message'])) return;

        $message = $this->update['message'];
        $telegramMessageId = (string)($message['message_id'] ?? '');
        $chat = $message['chat'];
        $chatId = (string)$chat['id'];

        // 1. Idempotency
        if ($telegramMessageId && TelegramMessage::where('bot_id', $bot->id)->where('telegram_message_id', $telegramMessageId)->exists()) {
            return;
        }

        // 2. Update/Create Chat
        $telegramChat = TelegramChat::updateOrCreate(
            ['chat_id' => $chatId, 'bot_id' => $bot->id],
            [
                'first_name' => $chat['first_name'] ?? null,
                'last_name' => $chat['last_name'] ?? null,
                'username' => $chat['username'] ?? null,
                'last_message_at' => now(),
            ]
        );

        // 3. Media Handling
        $mediaType = 'text';
        $fileId = null;
        $prefix = 'file_';

        if (isset($message['photo'])) {
            $mediaType = 'photo';
            $bestPhoto = end($message['photo']);
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
        }

        // 4. Save Message
        $telegramMessage = TelegramMessage::create([
            'bot_id' => $bot->id,
            'telegram_chat_id' => $telegramChat->id,
            'direction' => 'in',
            'telegram_message_id' => $telegramMessageId,
            'text' => $message['text'] ?? ($message['caption'] ?? null),
            'media_type' => $mediaType,
            'metadata' => $message,
        ]);

        // 5. Broadcast for Real-time
        broadcast(new TelegramMessageBroadcasting($telegramMessage))->toOthers();

        // 6. Media Download
        if ($fileId) {
            DownloadTelegramMedia::dispatch($telegramMessage, $fileId, $prefix);
        }
    }
}
