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
    public function chats(Request $request, TelegramBot $bot)
    {
        $this->authorizeBot($bot);

        $chats = $this->getChatsForBot($bot, $request->search);

        return view('telegram::chats', compact('bot', 'chats'));
    }

    /**
     * Display a specific chat.
     */
    public function showChat(Request $request, TelegramBot $bot, TelegramChat $chat)
    {
        $this->authorizeBot($bot);
        
        if ($chat->bot_id !== $bot->id) {
            abort(404);
        }

        $chats = $this->getChatsForBot($bot, $request->search);
        $messages = $chat->messages()->orderBy('created_at', 'asc')->get();

        return view('telegram::chats', compact('bot', 'chats', 'chat', 'messages'));
    }

    protected function getChatsForBot(TelegramBot $bot, ?string $search = null)
    {
        $query = TelegramChat::where('bot_id', $bot->id);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhereHas('messages', function($mq) use ($search) {
                      $mq->where('text', 'like', "%{$search}%");
                  });
            });
        }

        $chats = $query->with(['messages' => function ($query) use ($search) {
                if ($search) {
                    $query->where('text', 'like', "%{$search}%")->latest();
                } else {
                    $query->latest()->limit(1);
                }
            }])
            ->orderByDesc('last_message_at')
            ->get();

        return $chats;
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
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => [
                        'id' => $result->id,
                        'text' => $result->text,
                        'direction' => $result->direction,
                        'media_type' => $result->media_type,
                        'media_path' => $result->media_path ? \Storage::url($result->media_path) : null,
                        'time' => $result->created_at->format('H:i'),
                    ]
                ]);
            }
            return back()->with('success', 'Message sent successfully!');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'error' => 'Failed to send message.'], 500);
        }
        return back()->with('error', 'Failed to send message.');
    }

    /**
     * Poll for new messages (AJAX fallback for real-time).
     */
    public function newMessages(Request $request, TelegramBot $bot, TelegramChat $chat)
    {
        $this->authorizeBot($bot);

        if ($chat->bot_id !== $bot->id) {
            abort(404);
        }

        $lastId = (int) $request->query('last_id', 0);

        $messages = $chat->messages()
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'text' => $msg->text,
                    'direction' => $msg->direction,
                    'media_type' => $msg->media_type,
                    'media_path' => $msg->media_path ? \Storage::url($msg->media_path) : null,
                    'time' => $msg->created_at->format('H:i'),
                ];
            });

        return response()->json(['messages' => $messages]);
    }

    protected function authorizeBot(TelegramBot $bot)
    {
        if ($bot->company_id !== auth()->user()->company_id && !auth()->user()->isSuperAdmin()) {
            abort(403);
        }
    }
}
