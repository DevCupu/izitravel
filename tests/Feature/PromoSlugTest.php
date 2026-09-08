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
}
