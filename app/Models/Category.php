<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'slug',
    'order',
    'is_active',
])]
class Category extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function packages()
    {
        return $this->hasMany(Package::class, 'category', 'name');
    }
}
