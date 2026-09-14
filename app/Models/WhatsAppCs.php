<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsAppCs extends Model
{
    protected $table = 'whatsapp_cs';

    protected $fillable = [
        'name',
        'phone',
        'weight',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'integer',
        'is_active' => 'boolean',
    ];

    public function chatLogs(): HasMany
    {
        return $this->hasMany(ChatLog::class, 'cs_id');
    }
}
