<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappNumber extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Modules\Company\Database\Factories\WhatsappNumberFactory::new();
    }
    protected $fillable = [
        'company_id',
        'type',
        'phone_number',
        'app_name',
        'app_id',
        'app_token',
        'session_name',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'type' => 'string',
            'status' => 'string',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeApi($query)
    {
        return $query->where('type', 'api');
    }

    public function scopeQr($query)
    {
        return $query->where('type', 'qr');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isApi(): bool
    {
        return $this->type === 'api';
    }

    public function isQr(): bool
    {
        return $this->type === 'qr';
    }

    /**
     * The users that are assigned to this WhatsApp number.
     */
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'whatsapp_number_user')
            ->withPivot('access_type')
            ->withTimestamps();
    }
}
