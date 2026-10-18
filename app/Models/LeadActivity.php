<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadActivity extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['lead_id', 'actor_user_id', 'type', 'note', 'changes'];
    protected $casts = ['changes' => 'array'];

    public function actor(): BelongsTo { return $this->belongsTo(User::class, 'actor_user_id'); }
}
