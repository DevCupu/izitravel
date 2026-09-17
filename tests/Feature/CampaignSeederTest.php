<?php

namespace Tests\Feature;

use App\Models\Campaign;
use Database\Seeders\CampaignSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_campaign_seeder_is_idempotent_and_creates_ads(): void
    {
        $this->seed(CampaignSeeder::class);
        $this->seed(CampaignSeeder::class);

        $campaign = Campaign::where('utm_campaign', 'umrah_oktober_2026')->firstOrFail();

        $this->assertSame(1, Campaign::where('utm_campaign', 'umrah_oktober_2026')->count());
        $this->assertSame(3, $campaign->ads()->count());
        $this->assertDatabaseHas('campaign_ads', [
            'campaign_id' => $campaign->id,
            'utm_content' => 'video_testimoni_jemaah',
            'is_active' => true,
        ]);
        $this->assertDatabaseHas('campaign_ads', [
            'campaign_id' => $campaign->id,
            'utm_content' => 'poster_harga_promo',
            'is_active' => true,
        ]);
        $this->assertDatabaseHas('campaign_ads', [
            'campaign_id' => $campaign->id,
            'utm_content' => 'carousel_fasilitas',
            'is_active' => true,
        ]);
    }
}
