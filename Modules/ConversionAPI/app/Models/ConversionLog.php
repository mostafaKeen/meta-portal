<?php

namespace Modules\ConversionAPI\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Models\Company;

class ConversionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'entity_type',
        'entity_id',
        'event_name',
        'bitrix_payload',
        'fb_payload',
        'fb_response',
        'status',
        'error_message',
    ];

    protected $casts = [
        'bitrix_payload' => 'array',
        'fb_payload' => 'array',
        'fb_response' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
