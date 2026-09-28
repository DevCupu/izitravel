<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('registration_items')
            ->where('type', 'visa')
            ->get(['registration_id', 'status'])
            ->each(function ($item) {
                $visaStatus = match ($item->status) {
                    'completed' => 'completed',
                    'in_progress' => 'kemenag_process',
                    default => 'not_started',
                };

                DB::table('registrations')
                    ->where('id', $item->registration_id)
                    ->update(['visa_status' => $visaStatus]);
            });
    }

    public function down(): void
    {
        // The original checklist status is retained in registration_items.
    }
};