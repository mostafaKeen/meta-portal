<?php

namespace Modules\Telegram\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Company\Models\TelegramBot;
use Modules\Telegram\Models\TelegramChat;
use Modules\Telegram\Models\TelegramMessage;
use Modules\Telegram\Services\TelegramService;

class TelegramController extends Controller
{
    public function __construct(
        protected TelegramService $telegramService
    ) {}

    /**
     * Display a listing of bots for the company.
     */
    public function index()
    {
        $bots = TelegramBot::where('company_id', auth()->user()->company_id)
            ->withCount(['chats'])
            ->get();

        return view('telegram::index', compact('bots'));
    }

    /**
     * Display chats for a specific bot.
     */
    public function chats(TelegramBot $bot)
    {
        $this->authorizeBot($bot);

        $chats = $this->getChatsForBot($bot);

        return view('telegram::chats', compact('bot', 'chats'));
    }

    /**
     * Display a specific chat.
     */
    public function showChat(TelegramBot $bot, TelegramChat $chat)
    {
        $this->authorizeBot($bot);
        
        if ($chat->bot_id !== $bot->id) {
            abort(404);
        }

        $chats = $this->getChatsForBot($bot);
        $messages = $chat->messages()->orderBy('created_at', 'asc')->get();

        return view('telegram::chats', compact('bot', 'chats', 'chat', 'messages'));
    }

    protected function getChatsForBot(TelegramBot $bot)
    {
        return TelegramChat::where('bot_id', $bot->id)
            ->with(['messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->orderByDesc('last_message_at')
            ->get();
    }

    /**
     * Send a message to a chat.
     */
    public function sendMessage(Request $request, TelegramBot $bot, TelegramChat $chat)
    {
        $this->authorizeBot($bot);
        $service = $this->telegramService->forBot($bot);

        $request->validate([
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:20480', // 20MB limit
        ]);

        $result = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $mime = $file->getMimeType();

            if (str_contains($mime, 'image')) {
                $result = $service->sendPhoto($chat->chat_id, $file, $request->message);
            } elseif (str_contains($mime, 'audio') || str_contains($mime, 'ogg')) {
                $result = $service->sendVoice($chat->chat_id, $file, $request->message);
            } else {
                $result = $service->sendDocument($chat->chat_id, $file, $request->message);
            }
        } elseif ($request->message) {
            $result = $service->sendMessage($chat->chat_id, $request->message);
        }

        if ($result) {
            return back()->with('success', 'Message sent successfully!');
        }

        return back()->with('error', 'Failed to send message.');
    }

    protected function authorizeBot(TelegramBot $bot)
    {
        if ($bot->company_id !== auth()->user()->company_id && !auth()->user()->isSuperAdmin()) {
            abort(403);
        }
    }
}
