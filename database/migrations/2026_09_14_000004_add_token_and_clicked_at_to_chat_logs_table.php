<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Level-2 click tracking for the IZI /chat router.
     *
     * The redirect landing page (chat.blade.php) renders a small unique `token`
     * per lead. A fire-and-forget sendBeacon + keepalive fetch POSTs back to
     * /chat/click the moment the visitor actually taps "Lanjut ke WhatsApp" or
     * the auto-redirect fires. That lets admins see in the lead log:
     *   - clicked_at  : when the visitor actually left the page toward WhatsApp
     *   - token       : the per-lead secret used to authenticate that beacon
     *                   (never guesses ids, so the beacon can't poke arbitrary rows)
     *
     * Both columns live on chat_logs (SQLite runs fine with indexed varchar; the
     * two-number string `token` is MySQL-compatible as a normal string column).
     */
    public function up(): void
    {
        Schema::table('chat_logs', function (Blueprint $table) {
            $table->string('token', 36)->nullable()->after('user_agent');
            $table->timestamp('clicked_at')->nullable()->after('token');

            $table->index('token');
        });
    }

    /**
     * Reverse the click-tracking columns. Drops the index and both columns
     * (token first, then clicked_at, as migration order requires).
     */
    public function down(): void
    {
        Schema::table('chat_logs', function (Blueprint $table) {
            $table->dropIndex(['token']);
            $table->dropColumn(['clicked_at']);
            $table->dropColumn('token');
        });
    }
};
