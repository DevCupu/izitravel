<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'departure_room_id',
    'registration_id',
])]
class RoomAssignment extends Model
{
    public function room()
    {
        return $this->belongsTo(DepartureRoom::class, 'departure_room_id');
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
