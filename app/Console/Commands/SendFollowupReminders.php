<?php

namespace App\Console\Commands;

use App\Services\FollowupReminderService;
use Illuminate\Console\Command;

class SendFollowupReminders extends Command
{
    protected $signature = 'crm:send-followup-reminders';
    protected $description = 'Kirim satu pengingat email untuk tugas CRM yang jatuh tempo';

    public function handle(FollowupReminderService $service): int
    {
        $counts = $service->sendDue();
        $this->info("Terkirim: {$counts['sent']}; gagal: {$counts['failed']}.");

        return $counts['failed'] ? self::FAILURE : self::SUCCESS;
    }
}
