<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\ChatLog;
use App\Models\Setting;
use App\Models\WhatsAppCs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_chat_logs_utm_and_renders_interstitial(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $response = $this->get('/chat?utm_source=meta&utm_medium=paid_social&utm_campaign=visa_umrah');

        $response->assertStatus(200);
        $response->assertSee('Menghubungkan Anda');
        $response->assertSee('chat/open');
        $response->assertSee('FB_IAB');
        $response->assertSee('pageshow');
        $response->assertSee('visibilitychange');
        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));

        $this->assertDatabaseHas('chat_logs', [
            'utm_source' => 'meta',
            'utm_medium' => 'paid_social',
            'utm_campaign' => 'visa_umrah',
            'campaign_id' => null,
        ]);
    }

    public function test_chat_matches_registered_campaign(): void
    {
        $campaign = Campaign::create([
            'name' => 'Visa Umrah September',
            'utm_campaign' => 'visa_umrah',
            'is_active' => true,
        ]);
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $this->get('/chat?utm_source=meta&utm_campaign=visa_umrah')->assertStatus(200);

        $log = ChatLog::where('utm_campaign', 'visa_umrah')->first();
        $this->assertNotNull($log);
        $this->assertSame($campaign->id, $log->campaign_id);
    }

    public function test_chat_keeps_unregistered_campaign_but_routes_anyway(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $response = $this->get('/chat?utm_campaign=iklan_baru_tanpa_daftar');

        $response->assertStatus(200);
        $response->assertSee('chat/open');

        $this->assertDatabaseHas('chat_logs', [
            'utm_campaign' => 'iklan_baru_tanpa_daftar',
            'campaign_id' => null,
        ]);
    }

    public function test_chat_works_without_any_utm(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '6281300000001', 'weight' => 1]);

        $response = $this->get('/chat');

        $response->assertStatus(200);
        $response->assertSee('chat/open');
        $this->assertDatabaseCount('chat_logs', 1);
    }

    public function test_chat_open_selects_a_fresh_cs_and_redirects_to_whatsapp(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $response = $this->get('/chat/open?utm_source=meta&utm_campaign=iklan_umroh');

        $response->assertRedirect();
        $this->assertStringStartsWith('https://wa.me/6281300000001?text=', $response->getTargetUrl());
        $log = ChatLog::first();
        $this->assertNotNull($log);
        $this->assertSame('meta', $log->utm_source);
        $this->assertSame('iklan_umroh', $log->utm_campaign);
        $this->assertNotNull($log->clicked_at);
    }

    public function test_chat_falls_back_to_wa_link_to_main_number(): void
    {
        Setting::setValue('contact_whatsapp', '6281199999999');

        $response = $this->get('/chat');

        $response->assertStatus(200);
        $response->assertSee('chat/open');
        $log = ChatLog::first();
        $this->assertNotNull($log);
        $this->assertNull($log->cs_id);
    }

    public function test_chat_route_is_throttled(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        for ($i = 0; $i < 20; $i++) {
            $this->get('/chat')->assertStatus(200);
        }

        $this->get('/chat')->assertStatus(429);
    }
}
