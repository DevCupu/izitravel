<?php

namespace App\Mail;

use App\Models\LeadFollowup;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FollowupDueMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public LeadFollowup $task) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Pengingat follow-up: '.$this->task->lead->display_name);
    }

    public function content(): Content
    {
        return new Content(text: 'emails.followup-due');
    }
}
