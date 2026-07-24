<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add nullable slug column (no unique constraint yet)
        Schema::table('packages', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Step 2: Generate slugs for existing packages
        $packages = \DB::table('packages')->get();
        foreach ($packages as $package) {
            \DB::table('packages')
                ->where('id', $package->id)
                ->update(['slug' => Str::slug($package->name)]);
        }

        // Step 3: Now make it unique & not-null
        Schema::table('packages', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
