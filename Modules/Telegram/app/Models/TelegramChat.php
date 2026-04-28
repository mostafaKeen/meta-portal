<?php

namespace Modules\Telegram\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TelegramChat extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'chat_id',
        'bot_id',
        'first_name',
        'last_name',
        'username',
        'photo_url',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(TelegramMessage::class, 'telegram_chat_id', 'id');
    }
}
