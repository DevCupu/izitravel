<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('campaign_ads')) {
            return;
        }

        DB::table('campaign_ads')
            ->where('name', 'Iklan Utama')
            ->where('utm_content', 'main')
            ->delete();
    }

    public function down(): void
    {
        // Generated placeholder ads are intentionally not restored.
    }
};
