<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('manifest_group')->nullable()->after('pic_name');
        });

        Schema::create('departure_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->string('city');
            $table->string('room_number');
            $table->string('room_type');
            $table->unsignedTinyInteger('capacity');
            $table->timestamps();
            $table->unique(['package_id', 'city', 'room_number']);
        });

        Schema::create('room_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departure_room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique('registration_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_assignments');
        Schema::dropIfExists('departure_rooms');
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('manifest_group');
        });
    }
};
