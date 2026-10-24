<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadFollowup extends Model
{
    use SoftDeletes;
    protected $fillable = ['lead_id', 'assigned_user_id', 'due_at', 'description', 'status', 'completed_at', 'reminder_sent_at', 'reminder_claimed_at'];
    protected function casts(): array { return ['due_at' => 'datetime', 'completed_at' => 'datetime', 'reminder_sent_at' => 'datetime', 'reminder_claimed_at' => 'datetime']; }
    protected static function booted(): void
    {
        static::updated(function (self $task): void {
            if ($task->wasChanged(['due_at', 'assigned_user_id'])) {
                // A task loaded before the reminder was sent may hold stale reminder fields.
                // Reset in the database so reassignment and rescheduling always notify again.
                static::query()->whereKey($task->id)->update([
                    'reminder_sent_at' => null,
                    'reminder_claimed_at' => null,
                ]);
            }
        });
    }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function assignee(): BelongsTo { return $this->belongsTo(User::class, 'assigned_user_id'); }
}
