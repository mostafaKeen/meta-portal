<?php

namespace Modules\WhatsAppQR\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\WhatsAppQR\Database\Factories\WhatsAppChatFactory;

class WhatsAppChat extends Model
{
    protected $table = 'whatsapp_chats';

    protected $fillable = [
        'whatsapp_number_id',
        'chat_id',
        'name',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function whatsappNumber()
    {
        return $this->belongsTo(\Modules\Company\Models\WhatsappNumber::class);
    }

    public function messages()
    {
        return $this->hasMany(WhatsAppMessage::class, 'whatsapp_chat_id');
    }
}
