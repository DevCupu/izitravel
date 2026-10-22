<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\Setting;
use App\Support\AiRecommendationSettings;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PackageRecommendationTest extends TestCase
{
    public function test_public_quiz_lists_only_matching_active_future_packages(): void
    {
        $departure = today()->addMonths(2);
        $match = $this->package('Paket Cocok', $departure->toDateString(), 29000000, 'Makassar');
        $this->package('Paket Mahal', $departure->toDateString(), 45000000, 'Makassar');
        $this->package('Paket Kota Lain', $departure->toDateString(), 25000000, 'Jakarta');
        $this->package('Paket Lewat', today()->subDay()->toDateString(), 20000000, 'Makassar');
        $this->package('Paket Nonaktif', $departure->toDateString(), 20000000, 'Makassar', false);

        $this->get(route('public.packages.recommend'))->assertOk()->assertSee('Rencana perjalanan Anda');
        $response = $this->post(route('public.packages.recommend.submit'), [
            'budget' => 30000000,
            'month' => $departure->format('Y-m'),
            'city' => 'Makassar',
        ]);

        $response->assertOk()
            ->assertSee('Paket Cocok')
            ->assertDontSee('Paket Mahal')
            ->assertDontSee('Paket Kota Lain')
            ->assertDontSee('Paket Lewat')
            ->assertDontSee('Paket Nonaktif')
            ->assertSee(route('public.chat', [
                'recommended_package_id' => $match->id,
                'utm_source' => 'website',
                'utm_medium' => 'rekomendasi_paket',
            ]));
    }

    public function test_ai_can_rank_only_real_candidate_ids(): void
    {
        $first = $this->package('Paket Awal', today()->addMonth()->toDateString(), 27000000, 'Makassar');
        $second = $this->package('Paket Prioritas', today()->addMonths(2)->toDateString(), 30000000, 'Makassar');
        config()->set('services.openai.key', 'test-key');
        Setting::setValue('ai_recommendation_api_key', Crypt::encryptString('panel-key'));
        Setting::setValue('ai_recommendation_verified_fingerprint', AiRecommendationSettings::fingerprint('panel-key', 'gpt-5.4-mini'));
        Http::fake(['api.openai.com/*' => Http::response([
            'status' => 'completed',
            'output' => [[
                'type' => 'message',
                'content' => [[
                    'type' => 'output_text',
                    'text' => json_encode(['package_ids' => [$second->id, 999999, $first->id]]),
                ]],
            ]],
        ])]);

        $response = $this->post(route('public.packages.recommend.submit'), [
            'notes' => 'Saya lebih suka berangkat belakangan.',
            'ai_consent' => 1,
        ]);

        $response->assertOk()->assertSeeInOrder(['Paket Prioritas', 'Paket Awal']);
        Http::assertSentCount(1);
        Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Bearer panel-key'));
    }

    public function test_ai_is_not_called_without_visitor_consent(): void
    {
        $this->package('Paket Biasa', today()->addMonth()->toDateString(), 27000000, 'Makassar');
        config()->set('services.openai.key', 'test-key');
        Http::fake();

        $this->post(route('public.packages.recommend.submit'), [
            'notes' => 'Saya ingin hotel dekat masjid.',
        ])->assertOk()->assertSee('Paket Biasa');

        Http::assertNothingSent();
    }

    public function test_admin_settings_control_model_result_count_and_daily_quota(): void
    {
        $first = $this->package('Paket Pertama', today()->addMonth()->toDateString(), 27000000, 'Makassar');
        $second = $this->package('Paket Kedua', today()->addMonths(2)->toDateString(), 30000000, 'Makassar');
        config()->set('services.openai.key', 'test-key');
        Setting::setValue('ai_recommendation_model', 'gpt-5.4-mini');
        Setting::setValue('ai_recommendation_verified_fingerprint', AiRecommendationSettings::fingerprint('test-key', 'gpt-5.4-mini'));
        Setting::setValue('ai_recommendation_guidance', 'Utamakan hotel dekat masjid.');
        Setting::setValue('ai_recommendation_result_limit', '1');
        Setting::setValue('ai_recommendation_daily_limit', '1');
        Http::fake(['api.openai.com/*' => Http::response([
            'status' => 'completed',
            'output' => [['type' => 'message', 'content' => [[
                'type' => 'output_text',
                'text' => json_encode(['package_ids' => [$second->id]]),
            ]]]],
        ])]);

        $firstResponse = $this->post(route('public.packages.recommend.submit'), ['ai_consent' => 1]);
        $firstResponse->assertOk()->assertSee('1 paket untuk Anda')->assertSee('Paket Kedua')->assertDontSee('Paket Pertama');
        Http::assertSent(fn ($request) => $request['model'] === 'gpt-5.4-mini'
            && str_contains($request['instructions'], 'Utamakan hotel dekat masjid.'));

        $secondResponse = $this->post(route('public.packages.recommend.submit'), ['ai_consent' => 1]);
        $secondResponse->assertOk()->assertSee('Paket Pertama')->assertDontSee('Paket Kedua');
        Http::assertSentCount(1);
    }

    public function test_disabling_ai_hides_consent_and_skips_api_request(): void
    {
        $this->package('Paket Biasa', today()->addMonth()->toDateString(), 27000000, 'Makassar');
        config()->set('services.openai.key', 'test-key');
        Setting::setValue('ai_recommendation_enabled', '0');
        Http::fake();

        $this->get(route('public.packages.recommend'))->assertOk()->assertDontSee('name="ai_consent"', false);
        $this->post(route('public.packages.recommend.submit'), ['ai_consent' => 1])
            ->assertOk()->assertSee('Paket Biasa');

        Http::assertNothingSent();
    }

    public function test_consultation_passes_the_selected_package_and_preferences_to_chat_router(): void
    {
        $package = $this->package('Paket Konsultasi', today()->addMonths(2)->toDateString(), 28000000, 'Makassar');
        $this->post(route('public.packages.recommend.submit'), [
            'budget' => 30000000,
            'city' => 'Makassar',
            'notes' => 'Hotel dekat masjid',
        ])->assertOk();

        $response = $this->get(route('public.chat', ['recommended_package_id' => $package->id]));
        $response->assertRedirect();
        $message = rawurldecode($response->headers->get('Location'));
        $this->assertStringContainsString('Paket Konsultasi', $message);
        $this->assertStringContainsString('anggaran maksimal Rp 30.000.000', $message);
        $this->assertStringContainsString('Hotel dekat masjid', $message);
    }

    public function test_no_match_shows_a_clear_empty_state(): void
    {
        $this->package('Paket Premium', today()->addMonth()->toDateString(), 40000000, 'Makassar');

        $this->post(route('public.packages.recommend.submit'), ['budget' => 20000000])
            ->assertOk()
            ->assertSee('Belum ada paket untuk pilihan ini');
    }

    public function test_home_package_section_has_prompt_search_action(): void
    {
        $this->package('Paket Berangkat', today()->addMonth()->toDateString(), 27000000, 'Makassar');
        config()->set('services.openai.key', 'test-key');
        Setting::setValue('ai_recommendation_verified_fingerprint', AiRecommendationSettings::fingerprint('test-key', 'gpt-5.4-mini'));

        $this->get('/')->assertOk()
            ->assertSee('id="umrah-ai-search"', false)
            ->assertSee('Cari dengan AI')
            ->assertSee(route('public.packages.recommend.prompt'));
    }

    public function test_home_hides_ai_search_until_key_is_verified_and_ai_is_enabled(): void
    {
        $this->package('Paket Berangkat', today()->addMonth()->toDateString(), 27000000, 'Makassar');
        config()->set('services.openai.key', '');
        $this->get('/')->assertOk()->assertDontSee('id="umrah-ai-search"', false);

        config()->set('services.openai.key', 'unverified-key');
        $this->get('/')->assertOk()->assertDontSee('id="umrah-ai-search"', false);

        Setting::setValue('ai_recommendation_verified_fingerprint', AiRecommendationSettings::fingerprint('unverified-key', 'gpt-5.4-mini'));
        Setting::setValue('ai_recommendation_enabled', '0');
        $this->get('/')->assertOk()->assertDontSee('id="umrah-ai-search"', false);
    }

    public function test_prompt_search_returns_only_ai_selected_active_future_packages(): void
    {
        $match = $this->package('Paket Cocok', today()->addMonth()->toDateString(), 29000000, 'Makassar');
        $other = $this->package('Paket Lain', today()->addMonths(2)->toDateString(), 45000000, 'Jakarta');
        $this->package('Paket Lama', today()->subDay()->toDateString(), 20000000, 'Makassar');
        $this->package('Paket Nonaktif', today()->addMonth()->toDateString(), 20000000, 'Makassar', false);
        config()->set('services.openai.key', 'test-key');
        Setting::setValue('ai_recommendation_verified_fingerprint', AiRecommendationSettings::fingerprint('test-key', 'gpt-5.4-mini'));
        Http::fake(['api.openai.com/*' => Http::response([
            'status' => 'completed',
            'output' => [['type' => 'message', 'content' => [[
                'type' => 'output_text',
                'text' => json_encode(['package_ids' => [$match->id, 999999]]),
            ]]]],
        ])]);

        $this->postJson(route('public.packages.recommend.prompt'), [
            'prompt' => 'Dari Makassar maksimal 30 juta',
            'ai_consent' => 1,
        ])->assertOk()
            ->assertJsonPath('status', 'ok')
            ->assertJsonPath('package_ids', [$match->id]);

        Http::assertSent(fn ($request) => str_contains($request['input'], 'Dari Makassar maksimal 30 juta')
            && str_contains($request['input'], 'Paket Cocok')
            && str_contains($request['input'], 'Paket Lain')
            && ! str_contains($request['input'], 'Paket Lama')
            && ! str_contains($request['input'], 'Paket Nonaktif'));
        $this->assertSame([$match->id], session('package_recommendations.ids'));
    }

    public function test_prompt_search_requires_consent_and_reports_no_match(): void
    {
        $this->package('Paket Ada', today()->addMonth()->toDateString(), 27000000, 'Makassar');
        config()->set('services.openai.key', 'test-key');
        Setting::setValue('ai_recommendation_verified_fingerprint', AiRecommendationSettings::fingerprint('test-key', 'gpt-5.4-mini'));
        Http::fake(['api.openai.com/*' => Http::response([
            'status' => 'completed',
            'output' => [['type' => 'message', 'content' => [[
                'type' => 'output_text',
                'text' => json_encode(['package_ids' => []]),
            ]]]],
        ])]);

        $withoutConsent = $this->postJson(route('public.packages.recommend.prompt'), ['prompt' => 'Hotel dekat Masjidil Haram']);
        $this->assertSame(422, $withoutConsent->status(), $withoutConsent->getContent());
        $withoutConsent->assertJsonValidationErrors('ai_consent');
        Http::assertNothingSent();

        $this->postJson(route('public.packages.recommend.prompt'), [
            'prompt' => 'Hotel dekat Masjidil Haram',
            'ai_consent' => 1,
        ])->assertOk()->assertJsonPath('status', 'no_match')->assertJsonPath('package_ids', []);
    }

    public function test_prompt_search_reports_unavailable_when_ai_is_not_configured(): void
    {
        $this->package('Paket Ada', today()->addMonth()->toDateString(), 27000000, 'Makassar');
        config()->set('services.openai.key', '');
        Http::fake();

        $this->postJson(route('public.packages.recommend.prompt'), [
            'prompt' => 'Saya ingin berangkat dari Makassar',
            'ai_consent' => 1,
        ])->assertStatus(503)->assertJsonPath('status', 'unavailable');
        Http::assertNothingSent();
    }

    public function test_prompt_search_rejects_a_key_that_has_not_been_verified(): void
    {
        $this->package('Paket Ada', today()->addMonth()->toDateString(), 27000000, 'Makassar');
        config()->set('services.openai.key', 'unverified-key');
        Http::fake();

        $this->postJson(route('public.packages.recommend.prompt'), [
            'prompt' => 'Saya ingin berangkat dari Makassar',
            'ai_consent' => 1,
        ])->assertStatus(503)->assertJsonPath('status', 'unavailable');
        Http::assertNothingSent();
    }

    public function test_ai_form_disappears_after_openai_rejects_the_verified_key(): void
    {
        $this->package('Paket Ada', today()->addMonth()->toDateString(), 27000000, 'Makassar');
        config()->set('services.openai.key', 'revoked-key');
        Setting::setValue('ai_recommendation_verified_fingerprint', AiRecommendationSettings::fingerprint('revoked-key', 'gpt-5.4-mini'));
        Http::fake(['api.openai.com/*' => Http::response(['error' => ['message' => 'Invalid API key']], 401)]);

        $this->postJson(route('public.packages.recommend.prompt'), [
            'prompt' => 'Saya ingin berangkat dari Makassar',
            'ai_consent' => 1,
        ])->assertStatus(502)->assertJsonPath('status', 'failed');

        $this->assertFalse(AiRecommendationSettings::current()['key_verified']);
        $this->get('/')->assertOk()->assertDontSee('id="umrah-ai-search"', false);
    }

    private function package(string $name, string $date, int $price, string $city, bool $active = true): Package
    {
        return Package::create([
            'name' => $name,
            'departure_date' => $date,
            'duration_days' => 9,
            'airline' => 'Saudia',
            'hotel' => 'Hotel Contoh',
            'price' => $price,
            'start_city' => $city,
            'is_active' => $active,
        ]);
    }
}
