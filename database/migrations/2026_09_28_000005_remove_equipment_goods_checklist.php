<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('registration_items')->where('type', 'equipment_goods')->delete();
    }

    public function down(): void
    {
        // Retired checklist is intentionally not restored.
    }
};
