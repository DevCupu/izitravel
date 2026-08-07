<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'content',
        'author',
        'author_role',
        'image',
        'read_time',
        'published_at',
        'is_active',
        'order',
        'tags',
    ];

    protected $casts = [
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
