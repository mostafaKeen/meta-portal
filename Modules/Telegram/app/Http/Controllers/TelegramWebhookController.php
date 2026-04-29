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
        $update = $request->all();

        // 1. Quick Bot Validation
        $bot = \Modules\Company\Models\TelegramBot::where('token', $token)->where('status', 'active')->first();
        
        if (!$bot) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // 2. Offload to Queue for Scalability
        \Modules\Telegram\Jobs\ProcessTelegramUpdate::dispatch($update, $bot->id);

        return response('OK', 200);
    }
}
