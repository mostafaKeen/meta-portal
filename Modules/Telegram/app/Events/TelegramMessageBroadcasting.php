<?php

namespace Modules\Telegram\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Telegram\Models\TelegramMessage;

class TelegramMessageBroadcasting implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public TelegramMessage $message)
    {
        // Eager load relationships needed for the UI
        $this->message->load(['chat', 'bot']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('company.' . $this->message->bot->company_id . '.bot.' . $this->message->bot_id),
        ];
    }

    /**
     * Data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'chat_id' => $this->message->telegram_chat_id,
            'direction' => $this->message->direction,
            'text' => $this->message->text,
            'media_type' => $this->message->media_type,
            'media_path' => $this->message->media_path ? \Storage::url($this->message->media_path) : null,
            'time' => $this->message->created_at->format('H:i'),
            'chat_name' => $this->message->chat->first_name . ' ' . $this->message->chat->last_name,
            'chat_initial' => mb_substr($this->message->chat->first_name ?? 'U', 0, 1, 'UTF-8'),
        ];
    }
}
