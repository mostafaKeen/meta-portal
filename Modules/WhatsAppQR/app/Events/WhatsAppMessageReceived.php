<?php

namespace Modules\WhatsAppQR\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WhatsAppMessageReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public array $messageData;
    protected int $companyId;

    public function __construct($message, int $companyId)
    {
        // Store the data we need as a plain array — avoid SerializesModels relation issues
        $this->messageData = [
            'id' => $message->id,
            'whatsapp_chat_id' => $message->whatsapp_chat_id,
            'message_id' => $message->message_id,
            'direction' => $message->direction,
            'text' => $message->text,
            'media_type' => $message->media_type,
            'media_path' => $message->media_path,
            'reaction_to' => $message->reaction_to,
            'created_at' => $message->created_at->toISOString(),

        ];
        $this->companyId = $companyId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('company.' . $this->companyId),
        ];
    }

    public function broadcastAs()
    {
        return 'whatsapp.message';
    }

    public function broadcastWith()
    {
        return ['message' => $this->messageData];
    }
}
