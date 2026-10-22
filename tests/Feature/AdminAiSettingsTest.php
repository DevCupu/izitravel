<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use App\Support\AiRecommendationSettings;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminAiSettingsTest extends TestCase
{
    public function test_only_admin_can_open_ai_settings_and_key_is_never_displayed(): void
    {
        $this->get(route('admin.ai-settings.index'))->assertRedirect(route('login'));

        $cs = User::factory()->create(['role' => 'cs', 'email_verified_at' => now()]);
        $this->actingAs($cs)->get(route('admin.ai-settings.index'))->assertForbidden();

        config()->set('services.openai.key', 'test-secret-key');
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $this->actingAs($admin)->get(route('admin.ai-settings.index'))
            ->assertOk()
            ->assertSee('API key terpasang, belum terverifikasi')
            ->assertDontSee('test-secret-key');
    }

    public function test_admin_can_change_ai_recommendation_controls(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        config()->set('services.openai.key', 'server-fallback-key');
        Http::fake(['api.openai.com/v1/models/*' => Http::response(['id' => 'gpt-5.4-mini'])]);

        $this->actingAs($admin)->post(route('admin.ai-settings.update'), [
            'enabled' => '1',
            'api_key' => 'panel-secret-key',
            'model' => 'gpt-5.4-mini',
            'guidance' => 'Utamakan hotel dekat masjid.',
            'candidate_limit' => 18,
            'result_limit' => 2,
            'daily_limit' => 25,
            'timeout_seconds' => 7,
        ])->assertRedirect(route('admin.ai-settings.index'));

        $ai = AiRecommendationSettings::current();
        $this->assertTrue($ai['enabled']);
        $this->assertSame('Utamakan hotel dekat masjid.', $ai['guidance']);
        $this->assertSame(18, $ai['candidate_limit']);
        $this->assertSame(2, $ai['result_limit']);
        $this->assertSame(25, $ai['daily_limit']);
        $this->assertSame(7, $ai['timeout_seconds']);
        $this->assertSame('panel', $ai['key_source']);
        $this->assertTrue($ai['key_verified']);
        $this->assertSame('panel-secret-key', AiRecommendationSettings::apiKey());
        $this->assertNotSame('panel-secret-key', Setting::getValue('ai_recommendation_api_key'));
        $this->actingAs($admin)->get(route('admin.ai-settings.index'))->assertDontSee('panel-secret-key');

        $this->actingAs($admin)->post(route('admin.ai-settings.update'), [
            'model' => 'gpt-5.4-mini',
            'candidate_limit' => 12,
            'result_limit' => 3,
            'daily_limit' => 100,
            'timeout_seconds' => 10,
        ])->assertRedirect();

        $this->assertSame('0', Setting::getValue('ai_recommendation_enabled'));
        $this->assertSame('panel-secret-key', AiRecommendationSettings::apiKey());

        $this->actingAs($admin)->post(route('admin.ai-settings.update'), [
            'model' => 'gpt-5.4-mini',
            'candidate_limit' => 12,
            'result_limit' => 3,
            'daily_limit' => 100,
            'timeout_seconds' => 10,
            'clear_api_key' => 1,
        ])->assertRedirect();

        $this->assertSame('server-fallback-key', AiRecommendationSettings::apiKey());
        $this->assertSame('server', AiRecommendationSettings::current()['key_source']);
        $this->assertTrue(AiRecommendationSettings::current()['key_verified']);
    }

    public function test_unverified_key_can_be_rechecked_from_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $this->actingAs($admin);
        Http::fakeSequence()
            ->push(['error' => ['message' => 'Invalid key']], 401)
            ->push(['id' => 'gpt-5.4-mini'], 200);

        $this->post(route('admin.ai-settings.update'), [
            'enabled' => '1',
            'api_key' => 'first-key',
            'model' => 'gpt-5.4-mini',
            'candidate_limit' => 12,
            'result_limit' => 3,
            'daily_limit' => 100,
            'timeout_seconds' => 10,
        ])->assertRedirect(route('admin.ai-settings.index'));
        $this->assertFalse(AiRecommendationSettings::current()['key_verified']);

        $this->post(route('admin.ai-settings.verify'))->assertRedirect(route('admin.ai-settings.index'));
        $this->assertTrue(AiRecommendationSettings::current()['key_verified']);
    }

    public function test_invalid_controls_are_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);

        $this->actingAs($admin)->post(route('admin.ai-settings.update'), [
            'enabled' => '1',
            'api_key' => 'should-not-be-flashed',
            'model' => 'bad model/id',
            'candidate_limit' => 2,
            'result_limit' => 9,
            'daily_limit' => -1,
            'timeout_seconds' => 99,
        ])->assertSessionHasErrors(['model', 'candidate_limit', 'result_limit', 'daily_limit', 'timeout_seconds'])
            ->assertSessionMissing('_old_input.api_key');

        $this->assertNull(Setting::getValue('ai_recommendation_model'));
    }
}
