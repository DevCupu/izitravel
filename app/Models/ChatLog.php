<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatLog extends Model
{
    const UPDATED_AT = null;

    /**
     * User-agent fragments that identify previews, crawlers, and diagnostics.
     * Real Meta in-app browsers use FBAN / FBAV / FB_IAB and are intentionally
     * not included here.
     *
     * @var list<string>
     */
    public const AUTOMATED_USER_AGENT_FRAGMENTS = [
        'bot',
        'crawler',
        'spider',
        'slurp',
        'facebookexternalhit',
        'facebot',
        'meta-externalagent',
        'meta-externalfetcher',
        'curl/',
        'wget/',
        'python-requests',
        'postmanruntime',
        'headlesschrome',
        'lighthouse',
    ];

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

    public static function isAutomatedUserAgent(?string $userAgent): bool
    {
        if ($userAgent === null || trim($userAgent) === '') {
            return false;
        }

        $normalized = strtolower($userAgent);

        foreach (self::AUTOMATED_USER_AGENT_FRAGMENTS as $fragment) {
            if (str_contains($normalized, $fragment)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Exclude known automated traffic without deleting historical records.
     */
    public function scopeHumanTraffic(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query->whereNull('user_agent')
                ->orWhere(function (Builder $query) {
                    foreach (self::AUTOMATED_USER_AGENT_FRAGMENTS as $fragment) {
                        $query->whereRaw('LOWER(user_agent) NOT LIKE ?', ['%'.$fragment.'%']);
                    }
                });
        });
    }

    public function scopeOpened(Builder $query): Builder
    {
        return $query->whereNotNull('clicked_at');
    }

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
