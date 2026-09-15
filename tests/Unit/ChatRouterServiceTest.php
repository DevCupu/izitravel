<?php

namespace Tests\Unit;

use App\Models\Campaign;
use App\Models\ChatLog;
use App\Models\Setting;
use App\Models\WhatsAppCs;
use App\Services\ChatRouterService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ChatRouterServiceTest extends TestCase
{
    private ChatRouterService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ChatRouterService;
    }

    public function test_select_weighted_respects_bucket_boundaries(): void
    {
        $andi = WhatsAppCs::create(['name' => 'Andi', 'phone' => '6281200000001', 'weight' => 50]);
        $budi = WhatsAppCs::create(['name' => 'Budi', 'phone' => '6281300000002', 'weight' => 30]);
        $citra = WhatsAppCs::create(['name' => 'Citra', 'phone' => '6281400000003', 'weight' => 20]);

        // Cumulative buckets: Andi [1-50], Budi [51-80], Citra [81-100]
        $cases = [
            1 => 'Andi',
            50 => 'Andi',
            51 => 'Budi',
            80 => 'Budi',
            81 => 'Citra',
            100 => 'Citra',
        ];

        $csList = new Collection([$andi, $budi, $citra]);

        foreach ($cases as $roll => $expected) {
            $this->assertSame($expected, $this->service->selectWeighted($csList, $roll)['name'], "roll={$roll}");
        }
    }

    public function test_weighted_distribution_is_roughly_proportional(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '6281200000001', 'weight' => 50, 'is_active' => true]);
        WhatsAppCs::create(['name' => 'Budi', 'phone' => '6281300000002', 'weight' => 30, 'is_active' => true]);
        WhatsAppCs::create(['name' => 'Citra', 'phone' => '6281400000003', 'weight' => 20, 'is_active' => true]);

        $counts = ['Andi' => 0, 'Budi' => 0, 'Citra' => 0];
        $trials = 3000;

        for ($i = 0; $i < $trials; $i++) {
            $counts[$this->service->pickCs()['name']]++;
        }

        $this->assertGreaterThan(0.40, $counts['Andi'] / $trials, 'Andi ≈ 50%');
        $this->assertLessThan(0.60, $counts['Andi'] / $trials, 'Andi ≈ 50%');
        $this->assertGreaterThan(0.20, $counts['Budi'] / $trials, 'Budi ≈ 30%');
        $this->assertLessThan(0.40, $counts['Budi'] / $trials, 'Budi ≈ 30%');
        $this->assertGreaterThan(0.10, $counts['Citra'] / $trials, 'Citra ≈ 20%');
        $this->assertLessThan(0.30, $counts['Citra'] / $trials, 'Citra ≈ 20%');
    }

    public function test_pick_cs_never_selects_inactive_cs(): void
    {
        $active = WhatsAppCs::create(['name' => 'Andi', 'phone' => '6281200000001', 'weight' => 1, 'is_active' => true]);
        WhatsAppCs::create(['name' => 'Liburan', 'phone' => '6281500000009', 'weight' => 100, 'is_active' => false]);

        for ($i = 0; $i < 100; $i++) {
            $picked = $this->service->pickCs();
            $this->assertFalse($picked['fallback']);
            $this->assertSame($active->id, $picked['id'], 'inactive CS must never be picked');
        }
    }

    public function test_phone_numbers_are_normalized(): void
    {
        $cs = WhatsAppCs::create(['name' => 'Format', 'phone' => '+62 812-3456 78', 'weight' => 1]);

        $picked = $this->service->selectWeighted(new Collection([$cs]));

        $this->assertSame('62812345678', $picked['phone']);
    }

    public function test_leading_zero_phone_becomes_country_code(): void
    {
        $cs = WhatsAppCs::create(['name' => 'Lokal', 'phone' => '08123456789', 'weight' => 1]);

        $picked = $this->service->selectWeighted(new Collection([$cs]));

        $this->assertSame('628123456789', $picked['phone']);
    }

    public function test_falls_back_to_setting_number_when_no_active_cs(): void
    {
        Setting::setValue(ChatRouterService::FALLBACK_PHONE_SETTING, '6281199999999');

        $picked = $this->service->pickCs();

        $this->assertTrue($picked['fallback']);
        $this->assertNull($picked['id']);
        $this->assertSame('6281199999999', $picked['phone']);
    }

    public function test_fallback_returns_null_phone_when_no_setting(): void
    {
        Setting::setValue(ChatRouterService::FALLBACK_PHONE_SETTING, '');

        $picked = $this->service->pickCs();

        $this->assertTrue($picked['fallback']);
        $this->assertNull($picked['phone']);
    }

    public function test_route_logs_utm_and_known_campaign(): void
    {
        $campaign = Campaign::create(['name' => 'Visa Umrah', 'utm_campaign' => 'visa_umrah', 'is_active' => true]);
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '081300000001', 'weight' => 1]);

        $request = Request::create('/chat', 'GET', [
            'utm_source' => 'meta',
            'utm_medium' => 'paid_social',
            'utm_campaign' => 'visa_umrah',
        ]);

        $result = $this->service->route($request);

        $this->assertSame($campaign->id, $result['campaign']->id);
        $this->assertStringStartsWith('https://wa.me/6281300000001?text=', $result['wa_url']);
        $this->assertStringStartsWith('whatsapp://send?phone=6281300000001&text=', $result['wa_app_url']);

        $log = ChatLog::where('utm_campaign', 'visa_umrah')->first();
        $this->assertNotNull($log);
        $this->assertSame($campaign->id, $log->campaign_id);
        $this->assertSame('meta', $log->utm_source);
        $this->assertSame('paid_social', $log->utm_medium);
    }

    public function test_route_message_mentions_campaign_name(): void
    {
        Campaign::create(['name' => 'Paket Umrah Oktober', 'utm_campaign' => 'paket_umrah', 'is_active' => true]);
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '6281300000001', 'weight' => 1]);

        $request = Request::create('/chat', 'GET', ['utm_campaign' => 'paket_umrah']);

        $result = $this->service->route($request);

        $this->assertStringContainsString(rawurlencode('Paket Umrah Oktober'), $result['wa_url']);
    }

    public function test_route_uses_campaign_whatsapp_message_template_when_available(): void
    {
        Campaign::create([
            'name' => 'IKLAN UMROH 10 FREE 1',
            'utm_campaign' => 'umroh_10_free_1',
            'wa_message_template' => "Assalamu'alaikum Admin IZI Travel, saya mau tanya dulu program {campaign}.",
            'is_active' => true,
        ]);
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '6281300000001', 'weight' => 1]);

        $request = Request::create('/chat', 'GET', ['utm_campaign' => 'umroh_10_free_1']);

        $result = $this->service->route($request);

        $this->assertStringContainsString(
            rawurlencode("Assalamu'alaikum Admin IZI Travel, saya mau tanya dulu program IKLAN UMROH 10 FREE 1."),
            $result['wa_url']
        );
    }

    public function test_route_reuses_log_for_same_visitor_within_dedupe_window(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '6281300000001', 'weight' => 1]);

        $params = [
            'utm_source' => 'meta',
            'utm_medium' => 'paid_social',
            'utm_campaign' => 'visa_umrah',
        ];

        $first = $this->service->route($this->chatRequest($params, 'visitor-a'));
        $second = $this->service->route($this->chatRequest($params, 'visitor-a'));

        $this->assertSame(1, ChatLog::count(), 'repeated access from the same visitor must not create a duplicate log');
        $this->assertSame($first['token'], $second['token'], 'repeated access must reuse the same token');
        $this->assertSame($first['cs']['id'], $second['cs']['id'], 'repeated access must reuse the same CS');
    }

    public function test_route_rotates_cs_for_distinct_visitors_sharing_the_same_ip(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '6281300000001', 'weight' => 1]);
        WhatsAppCs::create(['name' => 'Budi', 'phone' => '6281300000002', 'weight' => 1]);

        $params = ['utm_campaign' => 'visa_umrah'];

        $picked = [];
        for ($i = 0; $i < 20; $i++) {
            $uid = 'visitor-'.$i;
            $csId = $this->service->route($this->chatRequest($params, $uid))['cs']['id'];

            // Same visitor revisiting within the window reuses the same CS.
            $repeatCsId = $this->service->route($this->chatRequest($params, $uid))['cs']['id'];
            $this->assertSame($csId, $repeatCsId);

            $picked[] = $csId;
        }

        // 20 distinct visitors (sharing one proxy IP) => must be spread across CS.
        $this->assertSame(20, ChatLog::count());
        $this->assertGreaterThan(
            1,
            count(array_unique($picked)),
            'visitors behind the same proxy IP must still be distributed across CS'
        );
    }

    public function test_route_creates_new_log_when_dedupe_window_passed(): void
    {
        WhatsAppCs::create(['name' => 'Andi', 'phone' => '6281300000001', 'weight' => 1]);

        $params = ['utm_campaign' => 'visa_umrah'];

        ChatLog::query()->where('utm_campaign', 'visa_umrah')->delete();

        $this->service->route($this->chatRequest($params, 'visitor-a'));

        ChatLog::query()->first()?->forceFill([
            'created_at' => now()->subSeconds(ChatRouterService::DEDUPE_WINDOW_SECONDS + 1),
        ])->save();

        $this->service->route($this->chatRequest($params, 'visitor-a'));

        $this->assertSame(2, ChatLog::count(), 'a new log is created once the dedupe window passes');
    }

    private function chatRequest(array $params, ?string $visitorUid = null): Request
    {
        $request = Request::create('/chat', 'GET', $params);

        if ($visitorUid !== null) {
            $request->cookies->set(ChatRouterService::VISITOR_COOKIE, $visitorUid);
        }

        return $request;
    }
}
