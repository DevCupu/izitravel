<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('utm_content');
            $table->text('wa_message_template')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['campaign_id', 'utm_content']);
            $table->index(['campaign_id', 'is_active']);
        });

        Schema::table('chat_logs', function (Blueprint $table) {
            $table->foreignId('campaign_ad_id')->nullable()->after('campaign_id')
                ->constrained('campaign_ads')->nullOnDelete();
            $table->string('utm_content')->nullable()->after('utm_campaign');
            $table->index(['campaign_ad_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('chat_logs', function (Blueprint $table) {
            $table->dropForeign(['campaign_ad_id']);
            $table->dropIndex(['campaign_ad_id', 'created_at']);
            $table->dropColumn(['campaign_ad_id', 'utm_content']);
        });

        Schema::dropIfExists('campaign_ads');
    }
};
