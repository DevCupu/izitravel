<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = [
        'name',
        'logo_type',
        'logo_path',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo_type === 'svg') {
            return null; // Return null so we can render raw SVG code instead
        }

        if (! $this->logo_path) {
            return null;
        }

        if (str_starts_with($this->logo_path, 'images/')) {
            return asset($this->logo_path);
        }

        return \Storage::disk('public')->url($this->logo_path);
    }
}
