<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'registration_id',
    'type',
    'status',
    'note',
    'file_path',
])]
class RegistrationItem extends Model
{
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
