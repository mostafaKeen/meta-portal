<?php

namespace Modules\Plans\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Models\Company;

class SubscriptionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'plan_id',
        'type',
        'status',
        'user_notes',
        'admin_notes',
    ];

    /**
     * Get the company that made the request.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the plan being requested.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Scope a query to only include pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
