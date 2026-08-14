<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'gender',
    'passport_number',
    'birth_date',
    'address',
])]
class Jemaah extends Model
{
    public const GENDERS = [
        'male' => 'Laki-laki',
        'female' => 'Perempuan',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
