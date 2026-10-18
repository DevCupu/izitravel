<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppCs extends Model
{
    protected $table = 'whatsapp_cs';

    protected $fillable = [
        'name',
        'phone',
        'weight',
        'is_active',
        'user_id',
    ];

    protected $casts = [
        'weight' => 'integer',
        'is_active' => 'boolean',
    ];

    public function chatLogs(): HasMany
    {
        return $this->hasMany(ChatLog::class, 'cs_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'assigned_cs_id');
    }
}
