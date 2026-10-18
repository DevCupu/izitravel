<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
            $table->boolean('must_change_password')->default(false);
        });

        Schema::table('whatsapp_cs', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
        });

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_normalized', 25)->unique();
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained()->restrictOnDelete();
            $table->string('display_name');
            $table->foreignId('assigned_cs_id')->constrained('whatsapp_cs')->restrictOnDelete();
            $table->foreignId('package_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('campaign_ad_id')->nullable()->constrained('campaign_ads')->restrictOnDelete();
            $table->string('attribution_method', 20)->default('unknown');
            $table->string('stage', 24)->default('baru');
            $table->text('needs_summary')->nullable();
            $table->timestamp('first_contacted_at')->nullable();
            $table->timestamp('won_at')->nullable();
            $table->text('confirmation_note')->nullable();
            $table->string('booking_reference')->nullable();
            $table->text('lost_reason')->nullable();
            $table->timestamps();
            $table->index(['assigned_cs_id', 'stage']);
            $table->index(['stage', 'created_at']);
            $table->index(['campaign_id', 'campaign_ad_id']);
        });

        Schema::create('lead_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type', 40);
            $table->text('note')->nullable();
            $table->json('changes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['lead_id', 'created_at']);
        });

        Schema::create('lead_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_user_id')->constrained('users')->restrictOnDelete();
            $table->timestamp('due_at');
            $table->string('description', 255);
            $table->string('status', 20)->default('open');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->index(['assigned_user_id', 'status', 'due_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_followups');
        Schema::dropIfExists('lead_activities');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('contacts');
        Schema::table('whatsapp_cs', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
            $table->dropConstrainedForeignId('user_id');
        });
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['is_active', 'must_change_password']));
    }
};
