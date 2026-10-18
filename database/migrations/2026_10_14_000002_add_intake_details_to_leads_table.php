<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('city', 120)->nullable();
            $table->string('planned_departure_month', 7)->nullable();
            $table->string('need_type', 40)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('leads', fn (Blueprint $table) => $table->dropColumn([
            'city', 'planned_departure_month', 'need_type',
        ]));
    }
};
