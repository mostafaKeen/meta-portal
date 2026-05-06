<?php

namespace Modules\WhatsAppQR\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\WhatsAppQR\Database\Factories\WhatsAppMessageFactory;

class WhatsAppMessage extends Model
{
    protected $table = 'whatsapp_messages';

    protected $fillable = [
        'whatsapp_chat_id',
        'message_id',
        'reaction_to',
        'direction',
        'text',
        'media_type',
        'media_path',
    ];


    public function chat()
    {
        return $this->belongsTo(WhatsAppChat::class, 'whatsapp_chat_id');
    }
}
