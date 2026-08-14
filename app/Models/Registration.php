<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'jemaah_id',
    'package_id',
    'status',
    'pic_name',
])]
class Registration extends Model
{
    /**
     * The 7 fixed checklist items tracked per registration, in display order.
     *
     * @var array<string, string>
     */
    public const ITEM_TYPES = [
        'passport' => 'Paspor',
        'vaccine' => 'Vaksin',
        'ktp' => 'KTP',
        'kk' => 'KK',
        'equipment' => 'Perlengkapan',
        'visa' => 'Visa',
        'ticket' => 'Tiket',
    ];

    /**
     * Display grouping for each item type (UI grouping only, not a status dimension).
     *
     * @var array<string, string>
     */
    public const ITEM_KINDS = [
        'passport' => 'document',
        'vaccine' => 'document',
        'ktp' => 'document',
        'kk' => 'document',
        'equipment' => 'operational',
        'visa' => 'operational',
        'ticket' => 'operational',
    ];

    /**
     * Uniform status set shared by all 7 checklist items.
     *
     * @var array<string, string>
     */
    public const STATUSES = [
        'missing' => 'Belum',
        'in_progress' => 'Proses',
        'completed' => 'Selesai',
        'problem' => 'Bermasalah',
    ];

    /**
     * Public-safe, single-value summary of the 7-item checklist (no document detail exposed).
     *
     * @var array<string, string>
     */
    public const OVERALL_STATUSES = [
        'not_started' => 'Pendaftaran Diterima',
        'in_progress' => 'Persiapan Berjalan',
        'ready' => 'Siap Berangkat',
        'attention' => 'Perlu Tindak Lanjut',
    ];

    protected static function booted(): void
    {
        static::created(function (Registration $registration) {
            $registration->items()->createMany(
                collect(array_keys(self::ITEM_TYPES))
                    ->map(fn ($type) => ['type' => $type, 'status' => 'missing'])
                    ->all()
            );
        });
    }

    public function jemaah()
    {
        return $this->belongsTo(Jemaah::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function items()
    {
        return $this->hasMany(RegistrationItem::class);
    }

    /**
     * Ordered 7-item checklist for rendering in the roster table / tracking profile.
     * Eager-load `items` before accessing this to avoid N+1.
     *
     * @return array<int, array{key: string, label: string, kind: string, status: string, model: ?RegistrationItem}>
     */
    public function getChecklistAttribute(): array
    {
        $items = $this->items->keyBy('type');

        return collect(self::ITEM_TYPES)
            ->map(fn ($label, $type) => [
                'key' => $type,
                'label' => $label,
                'kind' => self::ITEM_KINDS[$type],
                'status' => $items[$type]->status ?? 'missing',
                'model' => $items[$type] ?? null,
            ])
            ->values()
            ->all();
    }

    /**
     * Collapse the 7-item checklist into one public-safe status (one of OVERALL_STATUSES keys).
     * Any `problem` item takes priority; otherwise it's the aggregate of the other 6 states.
     */
    public function getOverallStatusAttribute(): string
    {
        $statuses = $this->items->pluck('status');

        return match (true) {
            $statuses->contains('problem') => 'attention',
            $statuses->every(fn ($status) => $status === 'completed') => 'ready',
            $statuses->every(fn ($status) => $status === 'missing') => 'not_started',
            default => 'in_progress',
        };
    }
}
