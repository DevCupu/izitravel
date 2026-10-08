<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignAd;
use App\Models\ChatLog;
use App\Models\Setting;
use App\Models\WhatsAppCs;
use App\Services\ChatRouterService;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ChatRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_chat_logs_utm_and_redirects_directly_to_whatsapp(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $response = $this->get('/chat?utm_source=meta&utm_medium=paid_social&utm_campaign=visa_umrah');

        $this->assertWhatsAppRedirect($response, '6281300000001');
        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));

        $this->assertDatabaseHas('chat_logs', [
            'utm_source' => 'meta',
            'utm_medium' => 'paid_social',
            'utm_campaign' => 'visa_umrah',
            'campaign_id' => null,
        ]);
        $this->assertNotNull(ChatLog::firstOrFail()->clicked_at);
    }

    public function test_chat_matches_registered_campaign(): void
    {
        $campaign = Campaign::create([
            'name' => 'Visa Umrah September',
            'utm_campaign' => 'visa_umrah',
            'is_active' => true,
        ]);
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $this->get('/chat?utm_source=meta&utm_campaign=visa_umrah')->assertRedirect();

        $log = ChatLog::where('utm_campaign', 'visa_umrah')->first();
        $this->assertNotNull($log);
        $this->assertSame($campaign->id, $log->campaign_id);
    }

    public function test_chat_matches_registered_ad(): void
    {
        $campaign = Campaign::create(['name' => 'Umrah Oktober', 'utm_campaign' => 'umrah_oktober', 'is_active' => true]);
        $ad = CampaignAd::create(['campaign_id' => $campaign->id, 'name' => 'Poster Harga', 'utm_content' => 'poster_harga', 'is_active' => true]);
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $this->get('/chat?utm_source=meta&utm_campaign=umrah_oktober&utm_content=poster_harga')->assertRedirect();

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

        $this->assertWhatsAppRedirect($response);

        $this->assertDatabaseHas('chat_logs', [
            'utm_campaign' => 'iklan_baru_tanpa_daftar',
            'campaign_id' => null,
        ]);
    }

    public function test_chat_works_without_any_utm(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '6281300000001', 'weight' => 1]);

        $response = $this->get('/chat');

        $this->assertWhatsAppRedirect($response, '6281300000001');
        $this->assertDatabaseCount('chat_logs', 1);
    }

    public function test_chat_falls_back_to_wa_link_to_main_number(): void
    {
        Setting::setValue('contact_whatsapp', '6281199999999');

        $response = $this->get('/chat');

        $this->assertWhatsAppRedirect($response, '6281199999999');
        $log = ChatLog::first();
        $this->assertNotNull($log);
        $this->assertNull($log->cs_id);
    }

    public function test_chat_renders_error_page_when_no_destination_number_exists(): void
    {
        Setting::setValue('contact_whatsapp', '');

        $this->get('/chat')
            ->assertOk()
            ->assertSee('CS Sedang Tidak Tersedia');
    }

    public function test_chat_route_is_throttled(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        for ($i = 0; $i < 20; $i++) {
            $this->get('/chat')->assertRedirect();
        }

        $this->get('/chat')->assertStatus(429);
    }

    public function test_chat_sets_visitor_cookie_for_repeat_access_dedup(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $response = $this->get('/chat?utm_campaign=visa_umrah');

        $response->assertRedirect();
        $response->assertCookieNotExpired(ChatRouterService::VISITOR_COOKIE);
    }

    public function test_chat_reuses_log_for_same_visitor_cookie(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $uid = (string) Str::uuid();

        $this->withCookie(ChatRouterService::VISITOR_COOKIE, $uid)
            ->get('/chat?utm_campaign=visa_umrah')
            ->assertRedirect();
        $this->withCookie(ChatRouterService::VISITOR_COOKIE, $uid)
            ->get('/chat?utm_campaign=visa_umrah')
            ->assertRedirect();

        $this->assertDatabaseCount('chat_logs', 1);
    }

    public function test_chat_creates_separate_log_for_distinct_visitor_cookies(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $this->withCookie(ChatRouterService::VISITOR_COOKIE, (string) Str::uuid())
            ->get('/chat?utm_campaign=visa_umrah')
            ->assertRedirect();
        $this->withCookie(ChatRouterService::VISITOR_COOKIE, (string) Str::uuid())
            ->get('/chat?utm_campaign=visa_umrah')
            ->assertRedirect();

        $this->assertDatabaseCount('chat_logs', 2);
    }

    public function test_chat_does_not_log_known_automated_traffic(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $response = $this->withHeader('User-Agent', 'facebookexternalhit/1.1')
            ->get('/chat?utm_source=meta&utm_campaign=visa_umrah');

        $this->assertWhatsAppRedirect($response);
        $this->assertDatabaseCount('chat_logs', 0);
    }

    public function test_meta_in_app_browser_is_still_logged_as_human_traffic(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $this->withHeader('User-Agent', 'Mozilla/5.0 [FBAN/EMA;FBAV/420.0.0.0]')
            ->get('/chat?utm_source=meta&utm_campaign=visa_umrah')
            ->assertRedirect();

        $this->assertDatabaseCount('chat_logs', 1);
        $this->assertNotNull(ChatLog::firstOrFail()->clicked_at);
    }

    public function test_legacy_click_endpoint_still_marks_redirect(): void
    {
        $log = ChatLog::create(['token' => (string) Str::uuid()]);
        $csrf = 'csrf-test-token';

        $this->withMiddleware(ValidateCsrfToken::class)
            ->withSession(['_token' => $csrf])
            ->post('/chat/click', [
                '_token' => $csrf,
                'token' => $log->token,
            ])->assertNoContent();

        $this->assertNotNull($log->fresh()->clicked_at);
    }

    private function assertWhatsAppRedirect(TestResponse $response, ?string $phone = null): void
    {
        $response->assertRedirect();
        $expectedPrefix = 'https://wa.me/'.($phone ?? '');

        $this->assertStringStartsWith($expectedPrefix, (string) $response->headers->get('Location'));
    }
}
