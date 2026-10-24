<?php

namespace App\Console\Commands;

use App\Services\InboxSyncService;
use Illuminate\Console\Command;

class SyncCrmInbox extends Command
{
    protected $signature = 'crm:sync-inbox';
    protected $description = 'Sinkronkan email baru dari inbox IMAP ke CRM';

    public function handle(InboxSyncService $service): int
    {
        try {
            $count = $service->sync();
            $this->info("Email baru: {$count}.");

            return self::SUCCESS;
        } catch (\Throwable $exception) {
            $this->error('Sinkronisasi inbox gagal. Periksa log aplikasi dan status inbox admin.');

            return self::FAILURE;
        }
    }
}
