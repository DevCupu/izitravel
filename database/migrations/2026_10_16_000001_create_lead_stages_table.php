<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_stages', function (Blueprint $table) {
            $table->string('key', 24)->primary();
            $table->string('label', 60);
            $table->string('kind', 12)->default('open');
            $table->unsignedInteger('position');
            $table->string('color', 7);
            $table->timestamps();
        });

        $now = now();
        DB::table('lead_stages')->insert([
            ['key' => 'baru', 'label' => 'Baru', 'kind' => 'open', 'position' => 1, 'color' => '#3166b1', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'dihubungi', 'label' => 'Dihubungi', 'kind' => 'open', 'position' => 2, 'color' => '#5a7fc4', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'konsultasi', 'label' => 'Konsultasi', 'kind' => 'open', 'position' => 3, 'color' => '#8b6cb1', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'penawaran', 'label' => 'Penawaran', 'kind' => 'open', 'position' => 4, 'color' => '#bf8846', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'booking', 'label' => 'Booking', 'kind' => 'open', 'position' => 5, 'color' => '#c49c34', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'berhasil', 'label' => 'Berhasil', 'kind' => 'won', 'position' => 6, 'color' => '#27856d', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'gagal', 'label' => 'Gagal', 'kind' => 'lost', 'position' => 7, 'color' => '#8893a3', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_stages');
    }
};
