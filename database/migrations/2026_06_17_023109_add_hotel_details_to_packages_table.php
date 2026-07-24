<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->string('hotel_makkah')->nullable()->after('hotel');
            $table->unsignedSmallInteger('hotel_makkah_nights')->nullable()->after('hotel_makkah');
            $table->string('hotel_madinah')->nullable()->after('hotel_makkah_nights');
            $table->unsignedSmallInteger('hotel_madinah_nights')->nullable()->after('hotel_madinah');
            $table->string('hotel')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['hotel_makkah', 'hotel_makkah_nights', 'hotel_madinah', 'hotel_madinah_nights']);
        });
    }
};
