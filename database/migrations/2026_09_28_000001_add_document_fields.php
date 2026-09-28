<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jemaahs', function (Blueprint $table) {
            $table->date('passport_expiry_date')->nullable()->after('passport_number');
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->string('visa_status')->default('not_started')->after('pic_name');
        });

        $registrationIds = DB::table('registrations')->pluck('id');
        foreach ($registrationIds as $registrationId) {
            foreach (['marriage_book', 'meningitis_card', 'equipment_goods'] as $type) {
                DB::table('registration_items')->insertOrIgnore([
                    'registration_id' => $registrationId,
                    'type' => $type,
                    'status' => 'missing',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('visa_status');
        });

        Schema::table('jemaahs', function (Blueprint $table) {
            $table->dropColumn('passport_expiry_date');
        });
    }
};