<?php

namespace Modules\WhatsAppQR\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WhatsAppStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $number;
    public $status;

    public function __construct($number, $status)
    {
        $this->number = $number;
        $this->status = $status;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('company.' . $this->number->company_id),
        ];
    }

    public function broadcastAs()
    {
        return 'whatsapp.status';
    }
}
