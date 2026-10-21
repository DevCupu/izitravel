<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HeroImageOptimizationTest extends TestCase
{
    public function test_mobile_hero_is_generated_once_and_used_on_the_homepage(): void
    {
        Storage::fake('public');
        $source = 'settings/test-hero.webp';
        Storage::disk('public')->put($source, file_get_contents(public_path('images/hero_kaaba_4k.webp')));
        Setting::setValue('hero_image', $source);

        $this->artisan('images:generate-hero-mobile')->assertSuccessful();

        $mobile = Setting::getValue('hero_image_mobile');
        $this->assertNotEmpty($mobile);
        $this->assertSame($source, Setting::getValue('hero_image_mobile_source'));
        $this->assertTrue(Storage::disk('public')->exists($mobile));
        $this->assertLessThan(Storage::disk('public')->size($source), Storage::disk('public')->size($mobile));
        $this->assertLessThanOrEqual(800, getimagesize(Storage::disk('public')->path($mobile))[0]);

        $this->artisan('images:generate-hero-mobile')->assertSuccessful();
        $this->assertSame($mobile, Setting::getValue('hero_image_mobile'));

        $this->get('/')->assertOk()->assertSee('storage/' . $mobile, false);
    }
}
