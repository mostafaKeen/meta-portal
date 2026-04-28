<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramBot extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'token',
        'status',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function chats(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Telegram\Models\TelegramChat::class, 'bot_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
