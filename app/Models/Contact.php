<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    protected $fillable = ['name', 'phone_normalized'];

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
