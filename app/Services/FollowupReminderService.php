<?php

namespace App\Services;

use App\Mail\FollowupDueMail;
use App\Models\LeadFollowup;
use App\Models\LeadStage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FollowupReminderService
{
    public function sendDue(): array
    {
        $counts = ['sent' => 0, 'failed' => 0];
        $now = now();
        $tasks = LeadFollowup::query()
            ->where('status', 'open')
            ->where('due_at', '<=', $now)
            ->whereNull('reminder_sent_at')
            ->where(fn ($query) => $query->whereNull('reminder_claimed_at')
                ->orWhere('reminder_claimed_at', '<', $now->copy()->subMinutes(10)))
            ->whereHas('lead', fn ($query) => $query->whereIn('stage', LeadStage::query()->where('kind', 'open')->select('key')))
            ->with(['lead.cs', 'assignee'])
            ->orderBy('due_at')->limit(10)->get();

        foreach ($tasks as $task) {
            $claimed = LeadFollowup::query()->whereKey($task->id)->where('status', 'open')
                ->whereNull('reminder_sent_at')->where('due_at', '<=', $now)
                ->where(fn ($query) => $query->whereNull('reminder_claimed_at')
                    ->orWhere('reminder_claimed_at', '<', $now->copy()->subMinutes(10)))
                ->update(['reminder_claimed_at' => $now]);
            if (! $claimed) continue;

            try {
                $task->refresh()->load(['lead.cs', 'assignee']);
                if ($task->status !== 'open' || $task->lead?->trashed()
                    || ! LeadStage::whereKey($task->lead->stage)->where('kind', 'open')->exists()
                    || ! $task->assignee?->is_active || ! $task->lead->cs?->is_active
                    || $task->lead->cs->user_id !== $task->assigned_user_id) {
                    continue;
                }

                Mail::to($task->assignee->email)->send(new FollowupDueMail($task));
                LeadFollowup::query()->whereKey($task->id)
                    ->where('assigned_user_id', $task->assigned_user_id)
                    ->where('due_at', $task->due_at)->where('status', 'open')
                    ->update(['reminder_sent_at' => now(), 'reminder_claimed_at' => null]);
                $counts['sent']++;
            } catch (\Throwable $exception) {
                $counts['failed']++;
                Log::error('Gagal mengirim pengingat follow-up CRM.', ['followup_id' => $task->id, 'exception' => $exception]);
            } finally {
                LeadFollowup::query()->whereKey($task->id)->where('reminder_claimed_at', $now)
                    ->update(['reminder_claimed_at' => null]);
            }
        }

        return $counts;
    }
}
