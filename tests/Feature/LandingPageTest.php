<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\Setting;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('IZI Travel');
        $response->assertSee('Siaran Langsung Masjidil Haram');
    }

    public function test_landing_page_displays_active_packages(): void
    {
        Package::create([
            'name' => 'Paket Umrah Plus Turki Platinum',
            'slug' => 'paket-umrah-plus-turki-platinum',
            'price' => 35000000,
            'duration_days' => 12,
            'departure_date' => '2026-10-15',
            'airline' => 'Saudia Airlines',
            'hotel_makkah' => 'Pullman Zamzam',
            'hotel_madinah' => 'Frontel Al Harithia',
            'is_active' => true,
            'order' => 1,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Paket Umrah Plus Turki Platinum');
    }

    public function test_landing_page_uses_youtube_live_stream_settings(): void
    {
        Setting::setValue('haramain_youtube_makkah', 'https://www.youtube.com/live/nwllJOmz3sI?si=-zjT6toqyA7olIau');

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('haramain-iframe');
        $response->assertSee('youtube-direct-link');
    }
}
