<?php

namespace Modules\WhatsAppQR\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WhatsAppQRReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $number;
    public $qr;

    public function __construct($number, $qr)
    {
        $this->number = $number;
        $this->qr = $qr;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('company.' . $this->number->company_id),
        ];
    }

    public function broadcastAs()
    {
        return 'whatsapp.qr';
    }
}
