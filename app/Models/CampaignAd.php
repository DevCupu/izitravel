<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampaignAd extends Model
{
    protected $fillable = [
        'campaign_id',
        'name',
        'utm_content',
        'wa_message_template',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function chatLogs(): HasMany
    {
        return $this->hasMany(ChatLog::class, 'campaign_ad_id');
    }
}
