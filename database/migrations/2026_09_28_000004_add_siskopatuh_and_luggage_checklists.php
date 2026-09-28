<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('registration_items')->where('type', 'equipment_goods')->delete();

        DB::table('registrations')->pluck('id')->each(function ($registrationId) {
            foreach (['siskopatuh', 'luggage'] as $type) {
                DB::table('registration_items')->insertOrIgnore([
                    'registration_id' => $registrationId,
                    'type' => $type,
                    'status' => 'missing',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        DB::table('registration_items')->whereIn('type', ['siskopatuh', 'luggage'])->delete();
    }
};