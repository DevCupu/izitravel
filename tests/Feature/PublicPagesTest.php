<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Package;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_package_detail_page_renders_successfully(): void
    {
        $package = Package::create([
            'slug' => 'paket-umrah-reguler-bintang-5',
            'name' => 'Paket Umrah Reguler Bintang 5',
            'price' => 28000000,
            'duration_days' => 9,
            'departure_date' => '2026-11-20',
            'airline' => 'Garuda Indonesia',
            'hotel_makkah' => 'Safwah Royal Orchid',
            'hotel_madinah' => 'Grand Mercure',
            'is_active' => true,
            'order' => 1,
        ]);

        $response = $this->get('/paket/' . $package->slug);

        $response->assertStatus(200);
        $response->assertSee('Paket Umrah Reguler Bintang 5');
    }

    public function test_article_detail_page_renders_successfully(): void
    {
        $article = Article::create([
            'slug' => 'panduan-lengkap-ibadah-umrah',
            'title' => 'Panduan Lengkap Ibadah Umrah',
            'content' => 'Berikut adalah panduan tata cara umrah sesuai sunnah.',
            'category' => 'panduan',
            'author' => 'Tim Redaksi IZI Travel',
            'is_active' => true,
        ]);

        $response = $this->get('/artikel/' . $article->slug);

        $response->assertStatus(200);
        $response->assertSee('Panduan Lengkap Ibadah Umrah');
    }

    public function test_gallery_page_renders_successfully(): void
    {
        $response = $this->get('/galeri');

        $response->assertStatus(200);
        $response->assertSee('Galeri');
    }

    public function test_terms_and_privacy_pages_render_successfully(): void
    {
        $termsResponse = $this->get('/syarat-ketentuan');
        $termsResponse->assertStatus(200);

        $privacyResponse = $this->get('/kebijakan-privasi');
        $privacyResponse->assertStatus(200);
    }
}
