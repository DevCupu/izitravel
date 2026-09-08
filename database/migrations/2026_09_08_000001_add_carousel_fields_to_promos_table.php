<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add carousel fields to the existing promos table.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('promos', 'label')) {
            Schema::table('promos', function (Blueprint $table) {
                $table->string('label')->nullable()->after('title');
            });
        }

        if (! Schema::hasColumn('promos', 'cta_text')) {
            Schema::table('promos', function (Blueprint $table) {
                $table->string('cta_text')->nullable()->after('action_url');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('promos', 'label')) {
            Schema::table('promos', function (Blueprint $table) {
                $table->dropColumn('label');
            });
        }

        if (Schema::hasColumn('promos', 'cta_text')) {
            Schema::table('promos', function (Blueprint $table) {
                $table->dropColumn('cta_text');
            });
        }
    }
};