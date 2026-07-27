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
            $table->string('start_city')->nullable()->after('airline');
            $table->string('pembimbing')->nullable()->after('start_city');
            $table->string('hotel_makkah_distance')->nullable()->after('hotel_makkah_nights');
            $table->string('hotel_madinah_distance')->nullable()->after('hotel_madinah_nights');
            $table->json('exclusions')->nullable()->after('inclusions');
            $table->json('features')->nullable()->after('exclusions');
            $table->unsignedBigInteger('price_triple')->nullable()->after('price');
            $table->unsignedBigInteger('price_double')->nullable()->after('price_triple');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'start_city',
                'pembimbing',
                'hotel_makkah_distance',
                'hotel_madinah_distance',
                'exclusions',
                'features',
                'price_triple',
                'price_double'
            ]);
        });
    }
};
