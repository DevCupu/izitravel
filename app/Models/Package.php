<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'slug',
    'duration_days',
    'departure_date',
    'airline',
    'hotel',
    'hotel_makkah',
    'hotel_makkah_nights',
    'hotel_makkah_distance',
    'hotel_madinah',
    'hotel_madinah_nights',
    'hotel_madinah_distance',
    'inclusions',
    'exclusions',
    'features',
    'price',
    'price_triple',
    'price_double',
    'category',
    'badge_label',
    'badge_color',
    'image',
    'brochure',
    'brochure_image',
    'is_active',
    'order',
    'start_city',
    'pembimbing',
])]
class Package extends Model
{
    /**
     * Boot the model and auto-generate slug from name.
     */
    protected static function booted(): void
    {
        static::creating(function (Package $package) {
            if (empty($package->slug)) {
                $package->slug = static::generateUniqueSlug($package->name);
            }
        });

        static::updating(function (Package $package) {
            if ($package->isDirty('name') && ! $package->isDirty('slug')) {
                $package->slug = static::generateUniqueSlug($package->name, $package->id);
            }
        });
    }

    /**
     * Generate a unique slug, appending a suffix if needed.
     */
    protected static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'price' => 'integer',
            'price_triple' => 'integer',
            'price_double' => 'integer',
            'duration_days' => 'integer',
            'hotel_makkah_nights' => 'integer',
            'hotel_madinah_nights' => 'integer',
            'inclusions' => 'array',
            'exclusions' => 'array',
            'features' => 'array',
            'category' => 'string',
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

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

    public function getBrochureUrlAttribute(): ?string
    {
        if (! $this->brochure) {
            return null;
        }

        if (str_starts_with($this->brochure, 'brochures/')) {
            return asset($this->brochure);
        }

        return \Storage::disk('public')->url($this->brochure);
    }

    public function getBrochureImageUrlAttribute(): ?string
    {
        if (! $this->brochure_image) {
            return null;
        }

        if (str_starts_with($this->brochure_image, 'images/')) {
            return asset($this->brochure_image);
        }

        return \Storage::disk('public')->url($this->brochure_image);
    }
}
