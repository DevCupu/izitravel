<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;
    public const NEED_TYPES = [
        'package_price' => 'Info paket/harga',
        'departure_schedule' => 'Jadwal keberangkatan',
        'facilities' => 'Fasilitas',
        'documents' => 'Dokumen',
        'other' => 'Lainnya',
    ];

    protected $fillable = [
        'contact_id', 'display_name', 'assigned_cs_id', 'package_id', 'campaign_id', 'campaign_ad_id',
        'attribution_method', 'stage', 'city', 'planned_departure_month', 'need_type', 'needs_summary', 'first_contacted_at',
        'won_at', 'confirmation_note', 'booking_reference', 'lost_reason',
    ];

    protected function casts(): array
    {
        return ['first_contacted_at' => 'datetime', 'won_at' => 'datetime'];
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        return $user->isAdmin() ? $query : $query->whereHas('cs', fn (Builder $q) => $q->where('user_id', $user->id));
    }

    public function scopeNeedsFollowup(Builder $query): Builder
    {
        return $query
            ->whereIn('stage', LeadStage::query()->where('kind', 'open')->select('key'))
            ->whereDoesntHave('followups', fn (Builder $followups) => $followups->where('status', 'open'));
    }

    public function contact(): BelongsTo { return $this->belongsTo(Contact::class); }
    public function cs(): BelongsTo { return $this->belongsTo(WhatsAppCs::class, 'assigned_cs_id'); }
    public function package(): BelongsTo { return $this->belongsTo(Package::class); }
    public function campaign(): BelongsTo { return $this->belongsTo(Campaign::class); }
    public function campaignAd(): BelongsTo { return $this->belongsTo(CampaignAd::class); }
    public function activities(): HasMany { return $this->hasMany(LeadActivity::class)->latest('id'); }
    public function followups(): HasMany { return $this->hasMany(LeadFollowup::class); }
    public function inboundEmails(): HasMany { return $this->hasMany(InboundEmail::class); }
}
