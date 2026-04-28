<?php

namespace Modules\Telegram\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bot_id',
        'telegram_chat_id',
        'direction',
        'telegram_message_id',
        'text',
        'media_type',
        'media_path',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(TelegramChat::class, 'telegram_chat_id', 'id');
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(\Modules\Company\Models\TelegramBot::class, 'bot_id', 'id');
    }
}
