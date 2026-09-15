<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\ChatLog;
use App\Models\User;
use App\Models\WhatsAppCs;
use Tests\TestCase;

class AdminChatRouterTest extends TestCase
{
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.whatsapp-cs.index'))->assertRedirect('/login');
        $this->get(route('admin.campaigns.index'))->assertRedirect('/login');
        $this->get(route('admin.chat-logs.index'))->assertRedirect('/login');
    }

    public function test_admin_can_manage_whatsapp_cs(): void
    {
        // Index
        $this->actingAs($this->admin)->get(route('admin.whatsapp-cs.index'))->assertStatus(200);

        // Store (phone normalized to country code)
        $this->actingAs($this->admin)->post(route('admin.whatsapp-cs.store'), [
            'name' => 'Andi',
            'phone' => '0813 1234 5678',
            'weight' => 50,
            'is_active' => 1,
        ])->assertRedirect(route('admin.whatsapp-cs.index'));
        $this->assertDatabaseHas('whatsapp_cs', [
            'name' => 'Andi',
            'phone' => '6281312345678',
            'weight' => 50,
            'is_active' => 1,
        ]);

        // Update bobot + status
        $cs = WhatsAppCs::where('name', 'Andi')->first();
        $this->actingAs($this->admin)->put(route('admin.whatsapp-cs.update', $cs->id), [
            'name' => 'Andi',
            'phone' => '6281312345678',
            'weight' => 30,
            'is_active' => 1,
        ])->assertRedirect(route('admin.whatsapp-cs.index'));
        $this->assertDatabaseHas('whatsapp_cs', ['id' => $cs->id, 'weight' => 30]);

        // Delete (another CS exists, so deletion is allowed)
        WhatsAppCs::create(['name' => 'Budi', 'phone' => '6281300000002', 'weight' => 30, 'is_active' => 1]);
        $this->actingAs($this->admin)->delete(route('admin.whatsapp-cs.destroy', $cs->id))
            ->assertRedirect(route('admin.whatsapp-cs.index'));
        $this->assertDatabaseMissing('whatsapp_cs', ['id' => $cs->id]);
    }

    public function test_cannot_deactivate_last_active_cs(): void
    {
        $cs = WhatsAppCs::create(['name' => 'Satu-satunya', 'phone' => '6281200000001', 'weight' => 1, 'is_active' => true]);

        $this->actingAs($this->admin)->put(route('admin.whatsapp-cs.update', $cs->id), [
            'name' => 'Satu-satunya',
            'phone' => '6281200000001',
            'weight' => 1,
            'is_active' => 0,
        ]);

        $this->assertDatabaseHas('whatsapp_cs', ['id' => $cs->id, 'is_active' => 1]);
    }

    public function test_cannot_delete_last_active_cs(): void
    {
        $cs = WhatsAppCs::create(['name' => 'Satu-satunya', 'phone' => '6281200000001', 'weight' => 1, 'is_active' => true]);

        $this->actingAs($this->admin)->delete(route('admin.whatsapp-cs.destroy', $cs->id));

        $this->assertDatabaseHas('whatsapp_cs', ['id' => $cs->id]);
    }

    public function test_admin_can_manage_campaigns(): void
    {
        // Index
        $this->actingAs($this->admin)->get(route('admin.campaigns.index'))->assertStatus(200);

        // Store — auto slug from name
        $this->actingAs($this->admin)->post(route('admin.campaigns.store'), [
            'name' => 'Visa Umrah September',
            'utm_campaign' => '',
            'wa_message_template' => "Assalamu'alaikum, saya mau tanya paket {campaign}.",
            'is_active' => 1,
        ])->assertRedirect(route('admin.campaigns.index'));
        $this->assertDatabaseHas('campaigns', [
            'name' => 'Visa Umrah September',
            'utm_campaign' => 'visa_umrah_september',
            'wa_message_template' => "Assalamu'alaikum, saya mau tanya paket {campaign}.",
            'is_active' => 1,
        ]);

        // Update
        $campaign = Campaign::where('utm_campaign', 'visa_umrah_september')->first();
        $this->actingAs($this->admin)->put(route('admin.campaigns.update', $campaign->id), [
            'name' => 'Visa Umrah Oktober',
            'utm_campaign' => 'visa_umrah_oktober',
            'wa_message_template' => "Halo Admin, mohon info {campaign}.",
            'is_active' => 1,
        ])->assertRedirect(route('admin.campaigns.index'));
        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'utm_campaign' => 'visa_umrah_oktober',
            'wa_message_template' => "Halo Admin, mohon info {campaign}.",
        ]);

        // Delete
        $this->actingAs($this->admin)->delete(route('admin.campaigns.destroy', $campaign->id))
            ->assertRedirect(route('admin.campaigns.index'));
        $this->assertDatabaseMissing('campaigns', ['id' => $campaign->id]);
    }

    public function test_admin_can_view_and_filter_chat_logs(): void
    {
        $campaign = Campaign::create(['name' => 'Visa Umrah', 'utm_campaign' => 'visa_umrah', 'is_active' => true]);
        $andi = WhatsAppCs::create(['name' => 'Andi', 'phone' => '6281200000001', 'weight' => 1]);
        $budi = WhatsAppCs::create(['name' => 'Budi', 'phone' => '6281300000002', 'weight' => 1]);

        ChatLog::create(['campaign_id' => $campaign->id, 'cs_id' => $andi->id, 'utm_source' => 'meta']);
        ChatLog::create(['campaign_id' => null, 'cs_id' => $budi->id, 'utm_source' => 'meta']);

        $this->actingAs($this->admin)->get(route('admin.chat-logs.index'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.chat-logs.index', ['campaign_id' => $campaign->id]))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.chat-logs.index', ['cs_id' => $budi->id]))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.chat-logs.index', ['from' => today()->toDateString(), 'to' => today()->toDateString()]))->assertStatus(200);
    }

    public function test_dashboard_renders_with_chat_stats(): void
    {
        $cs = WhatsAppCs::create(['name' => 'Andi', 'phone' => '6281200000001', 'weight' => 1]);
        ChatLog::create(['cs_id' => $cs->id]);

        $this->actingAs($this->admin)->get(route('admin.dashboard'))->assertStatus(200);
    }
}
