<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->index(['stage', 'updated_at'], 'leads_pipeline_stage_recent_index');
            $table->index(['assigned_cs_id', 'stage', 'updated_at'], 'leads_pipeline_cs_stage_recent_index');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex('leads_pipeline_stage_recent_index');
            $table->dropIndex('leads_pipeline_cs_stage_recent_index');
        });
    }
};
