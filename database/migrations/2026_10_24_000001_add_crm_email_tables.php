<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lead_followups', function (Blueprint $table) {
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamp('reminder_claimed_at')->nullable();
        });

        Schema::create('inbound_mailboxes', function (Blueprint $table) {
            $table->id();
            $table->string('account')->unique();
            $table->unsignedBigInteger('uid_validity')->nullable();
            $table->unsignedBigInteger('last_uid')->default(0);
            $table->timestamp('last_synced_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();
        });

        Schema::create('inbound_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mailbox_id')->constrained('inbound_mailboxes')->cascadeOnDelete();
            $table->unsignedBigInteger('uid_validity');
            $table->unsignedBigInteger('uid');
            $table->string('message_id')->nullable();
            $table->string('from_email');
            $table->string('from_name')->nullable();
            $table->string('subject')->nullable();
            $table->longText('body_text')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->string('status', 20)->default('new');
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('linked_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('linked_at')->nullable();
            $table->timestamps();
            $table->unique(['mailbox_id', 'uid_validity', 'uid']);
            $table->index(['status', 'received_at']);
            $table->index(['lead_id', 'received_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inbound_emails');
        Schema::dropIfExists('inbound_mailboxes');
        Schema::table('lead_followups', fn (Blueprint $table) => $table->dropColumn(['reminder_sent_at', 'reminder_claimed_at']));
    }
};
