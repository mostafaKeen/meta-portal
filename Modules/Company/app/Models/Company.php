<?php

namespace Modules\Company\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'domain_slug',
        'logo',
        'email',
        'phone',
        'address',
        'website',
        // Bitrix24
        'b24_domain',
        'b24_client_id',
        'b24_client_secret',
        'b24_access_token',
        'b24_refresh_token',
        // Status
        'status',
        'plan_id',
        'trial_ends_at',
    ];

    protected function casts(): array
    {
        return [
            'trial_ends_at' => 'datetime',
        ];
    }

    /**
     * Get all users belonging to this company.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all WhatsApp numbers belonging to this company.
     */
    public function whatsappNumbers()
    {
        return $this->hasMany(WhatsappNumber::class);
    }

    /**
     * Get the route key name for implicit model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    /**
     * Scope: only active companies.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get user count as attribute.
     */
    public function getUsersCountAttribute(): int
    {
        return $this->users()->count();
    }
}
