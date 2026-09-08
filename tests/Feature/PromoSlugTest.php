<?php

namespace Tests\Feature;

use App\Models\Promo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromoSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_slug_is_generated_and_kept_unique(): void
    {
        $a = Promo::create(['title' => 'Promo Umrah Ramadhan', 'description' => 'x']);
        $b = Promo::create(['title' => 'Promo Umrah Ramadhan', 'description' => 'y']);

        $this->assertSame('promo-umrah-ramadhan', $a->slug);
        $this->assertSame('promo-umrah-ramadhan-1', $b->slug);
    }

    public function test_carousel_is_hidden_without_promos_and_shows_admin_data(): void
    {
        $this->get('/')->assertOk()->assertDontSee('data-purpose="promo-carousel"', false);

        Promo::create([
            'title' => 'Promo Umrah Ramadhan',
            'description' => 'Keberangkatan Ramadhan 1448H',
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertSee('data-purpose="promo-carousel"', false)
            ->assertSee('Promo Umrah Ramadhan');
    }
}
