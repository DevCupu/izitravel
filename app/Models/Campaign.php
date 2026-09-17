<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    protected $fillable = [
        'name',
        'utm_campaign',
        'wa_message_template',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function chatLogs(): HasMany
    {
        return $this->hasMany(ChatLog::class, 'campaign_id');
    }

    public function ads(): HasMany
    {
        return $this->hasMany(CampaignAd::class)->orderByDesc('is_active')->orderBy('id');
    }
}
