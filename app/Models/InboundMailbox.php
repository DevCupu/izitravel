<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InboundMailbox extends Model
{
    protected $fillable = ['account', 'uid_validity', 'last_uid', 'last_synced_at', 'last_error'];

    protected function casts(): array
    {
        return ['uid_validity' => 'integer', 'last_uid' => 'integer', 'last_synced_at' => 'datetime'];
    }

    public function emails(): HasMany
    {
        return $this->hasMany(InboundEmail::class, 'mailbox_id');
    }
}
