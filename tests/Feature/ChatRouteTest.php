<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignAd;
use App\Models\ChatLog;
use App\Models\Setting;
use App\Models\WhatsAppCs;
use App\Services\ChatRouterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
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
        $response->assertSee('https://wa.me/');
        $response->assertSee('FB_IAB');
        $response->assertSee('pageshow');
        $response->assertSee('visibilitychange');
        $response->assertSee('isAdsInAppBrowser ? 3 : 1');
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

    public function test_chat_matches_registered_ad(): void
    {
        $campaign = Campaign::create(['name' => 'Umrah Oktober', 'utm_campaign' => 'umrah_oktober', 'is_active' => true]);
        $ad = CampaignAd::create(['campaign_id' => $campaign->id, 'name' => 'Poster Harga', 'utm_content' => 'poster_harga', 'is_active' => true]);
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $this->get('/chat?utm_source=meta&utm_campaign=umrah_oktober&utm_content=poster_harga')->assertStatus(200);

        $this->assertDatabaseHas('chat_logs', [
            'campaign_id' => $campaign->id,
            'campaign_ad_id' => $ad->id,
            'utm_content' => 'poster_harga',
        ]);
    }

    public function test_chat_keeps_unregistered_campaign_but_routes_anyway(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $response = $this->get('/chat?utm_campaign=iklan_baru_tanpa_daftar');

        $response->assertStatus(200);
        $response->assertSee('https://wa.me/');

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
        $response->assertSee('https://wa.me/');
        $this->assertDatabaseCount('chat_logs', 1);
    }

    public function test_chat_falls_back_to_wa_link_to_main_number(): void
    {
        Setting::setValue('contact_whatsapp', '6281199999999');

        $response = $this->get('/chat');

        $response->assertStatus(200);
        $response->assertSee('https://wa.me/6281199999999');
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

    public function test_chat_sets_visitor_cookie_for_repeat_access_dedup(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $response = $this->get('/chat?utm_campaign=visa_umrah');

        $response->assertStatus(200);
        $response->assertCookieNotExpired(ChatRouterService::VISITOR_COOKIE);
    }

    public function test_chat_reuses_log_for_same_visitor_cookie(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $uid = (string) Str::uuid();

        $this->withCookie(ChatRouterService::VISITOR_COOKIE, $uid)
            ->get('/chat?utm_campaign=visa_umrah')
            ->assertStatus(200);
        $this->withCookie(ChatRouterService::VISITOR_COOKIE, $uid)
            ->get('/chat?utm_campaign=visa_umrah')
            ->assertStatus(200);

        $this->assertDatabaseCount('chat_logs', 1);
    }

    public function test_chat_creates_separate_log_for_distinct_visitor_cookies(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $this->withCookie(ChatRouterService::VISITOR_COOKIE, (string) Str::uuid())
            ->get('/chat?utm_campaign=visa_umrah')
            ->assertStatus(200);
        $this->withCookie(ChatRouterService::VISITOR_COOKIE, (string) Str::uuid())
            ->get('/chat?utm_campaign=visa_umrah')
            ->assertStatus(200);

        $this->assertDatabaseCount('chat_logs', 2);
    }
}
