<?php

namespace Modules\Company\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory()
    {
        return \Modules\Company\Database\Factories\CompanyFactory::new();
    }

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
     * Get the company administrator.
     */
    public function admin()
    {
        return $this->hasOne(User::class)->where('role', 'company_admin');
    }

    /**
     * Get all WhatsApp numbers belonging to this company.
     */
    public function whatsappNumbers()
    {
        return $this->hasMany(WhatsappNumber::class);
    }

    /**
     * Get all Telegram bots belonging to this company.
     */
    public function telegramBots()
    {
        return $this->hasMany(TelegramBot::class);
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

    /**
     * Get the active subscription for the company.
     */
    public function subscription()
    {
        return $this->hasOne(\Modules\Plans\Models\Subscription::class)->where('status', 'active')->latestOfMany();
    }

    /**
     * Get all subscription history.
     */
    public function subscriptions()
    {
        return $this->hasMany(\Modules\Plans\Models\Subscription::class);
    }

    /**
     * Helper to get active plan details.
     */
    public function activePlan()
    {
        return $this->subscription?->plan;
    }

    /**
     * Check if the company has reached its agent limit.
     */
    public function hasReachedAgentLimit(): bool
    {
        $plan = $this->activePlan();
        if (!$plan) {
            return false;
        }

        if ($plan->max_agents === -1) {
            return false;
        }

        return $this->users()->count() >= $plan->max_agents;
    }

    /**
     * Check if the company has reached its WhatsApp QR numbers limit.
     */
    public function hasReachedWhatsappLimit(): bool
    {
        $plan = $this->activePlan();
        if (!$plan) {
            return false;
        }

        if ($plan->max_qr_numbers === -1) {
            return false;
        }

        return $this->whatsappNumbers()->where('type', 'qr')->count() >= $plan->max_qr_numbers;
    }

    /**
     * Check if the company has reached its Telegram bots limit.
     */
    public function hasReachedTelegramLimit(): bool
    {
        $plan = $this->activePlan();
        if (!$plan) {
            return false;
        }

        if ($plan->max_telegram_bots === -1) {
            return false;
        }

        return $this->telegramBots()->count() >= $plan->max_telegram_bots;
    }
}
