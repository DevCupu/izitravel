<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadFollowup extends Model
{
    use SoftDeletes;
    protected $fillable = ['lead_id', 'assigned_user_id', 'due_at', 'description', 'status', 'completed_at'];
    protected function casts(): array { return ['due_at' => 'datetime', 'completed_at' => 'datetime']; }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function assignee(): BelongsTo { return $this->belongsTo(User::class, 'assigned_user_id'); }
}
