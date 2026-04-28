<?php

namespace Modules\Telegram\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Telegram\Models\TelegramMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DownloadTelegramMedia
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected TelegramMessage $message,
        protected string $fileId,
        protected string $prefix = 'file_'
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $token = $this->message->bot?->token;
        if (!$token) {
            Log::error("DownloadTelegramMedia failed: Bot token not found for message {$this->message->id}");
            return;
        }

        // 1. Get File Path from Telegram
        $response = Http::get("https://api.telegram.org/bot{$token}/getFile", [
            'file_id' => $this->fileId,
        ]);

        if (!$response->successful() || !isset($response['result']['file_path'])) {
            return;
        }

        $filePath = $response['result']['file_path'];
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $fileName = "{$this->prefix}" . Str::random(20) . ".{$extension}";
        $localPath = "telegram/media/{$fileName}";

        // 2. Download and Store File
        $fileContent = Http::get("https://api.telegram.org/file/bot{$token}/{$filePath}")->body();
        
        Storage::disk('public')->put($localPath, $fileContent);

        // 3. Update Message Record
        $this->message->update([
            'media_path' => $localPath,
        ]);
    }
}
