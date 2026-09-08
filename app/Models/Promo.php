<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Promo extends Model
{
    protected static function booted(): void
    {
        static::creating(function (Promo $promo) {
            if (empty($promo->slug)) {
                $promo->slug = static::generateUniqueSlug($promo->title);
            }
        });
    }

    protected static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title) ?: 'promo';
        $original = $slug;
        $counter = 1;

        while (static::query()->where('slug', $slug)->exists()) {
            $slug = $original.'-'.$counter++;
        }

        return $slug;
    }

    protected $fillable = [
        'label',
        'title',
        'slug',
        'description',
        'image',
        'discount_code',
        'discount_value',
        'expiry_date',
        'action_url',
        'cta_text',
        'order',
        'is_active',
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'images/')) {
            return asset($this->image);
        }

        return \Storage::disk('public')->url($this->image);
    }
}