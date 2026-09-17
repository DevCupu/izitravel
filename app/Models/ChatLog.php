<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatLog extends Model
{
    const UPDATED_AT = null;

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    protected $fillable = [
        'campaign_id',
        'campaign_ad_id',
        'cs_id',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'ip_address',
        'user_agent',
        'token',
        'visitor_uid',
        'clicked_at',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function campaignAd(): BelongsTo
    {
        return $this->belongsTo(CampaignAd::class);
    }

    public function cs(): BelongsTo
    {
        return $this->belongsTo(WhatsAppCs::class, 'cs_id');
    }
}
