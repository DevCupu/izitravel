<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InboundEmail extends Model
{
    protected $fillable = [
        'mailbox_id', 'uid_validity', 'uid', 'message_id', 'from_email', 'from_name',
        'subject', 'body_text', 'received_at', 'status', 'lead_id', 'linked_by_user_id', 'linked_at',
    ];

    protected function casts(): array
    {
        return ['received_at' => 'datetime', 'linked_at' => 'datetime'];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function mailbox(): BelongsTo
    {
        return $this->belongsTo(InboundMailbox::class);
    }
}
