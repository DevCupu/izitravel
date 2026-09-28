<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'package_id',
    'city',
    'room_number',
    'room_type',
    'capacity',
])]
class DepartureRoom extends Model
{
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function assignments()
    {
        return $this->hasMany(RoomAssignment::class);
    }

    public function registrations()
    {
        return $this->belongsToMany(Registration::class, 'room_assignments');
    }
}
