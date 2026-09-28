<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('registrations')->pluck('id')->each(function ($registrationId) {
            DB::table('registration_items')->insertOrIgnore([
                'registration_id' => $registrationId,
                'type' => 'equipment_goods',
                'status' => 'missing',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    public function down(): void
    {
        DB::table('registration_items')->where('type', 'equipment_goods')->delete();
    }
};
