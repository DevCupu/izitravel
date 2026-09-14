<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatLog extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'campaign_id',
        'cs_id',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'ip_address',
        'user_agent',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function cs(): BelongsTo
    {
        return $this->belongsTo(WhatsAppCs::class, 'cs_id');
    }
}
