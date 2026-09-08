<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Restore ini dibuat ulang agar environment yang belum punya tabel
     * promos (mis. production) bisa menjalankan migration turunan.
     * Environment yang sudah memiliki tabel akan dilewati via hasTable.
     */
    public function up(): void
    {
        if (Schema::hasTable('promos')) {
            return;
        }

        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('discount_code')->nullable();
            $table->string('discount_value')->nullable();
            $table->datetime('expiry_date')->nullable();
            $table->string('action_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // No-op: migration ini restore-only, tidak memutus siklus hidup
        // tabel pada environment yang tabelnya sudah ada sebelumnya.
    }
};