<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $campaign = Campaign::updateOrCreate(
            ['utm_campaign' => 'umrah_oktober_2026'],
            [
                'name' => 'Umrah Oktober 2026',
                'is_active' => true,
            ]
        );

        $ads = [
            [
                'name' => 'Video Testimoni Jemaah',
                'utm_content' => 'video_testimoni_jemaah',
                'wa_message_template' => "Assalamu'alaikum Admin IZI Travel, saya tertarik dengan {campaign} setelah melihat {ad}. Mohon informasi paket dan jadwalnya.",
                'is_active' => true,
            ],
            [
                'name' => 'Poster Harga Promo',
                'utm_content' => 'poster_harga_promo',
                'wa_message_template' => "Assalamu'alaikum Admin IZI Travel, saya melihat promo {campaign} dari {ad}. Apakah harga dan kuotanya masih tersedia?",
                'is_active' => true,
            ],
            [
                'name' => 'Carousel Fasilitas',
                'utm_content' => 'carousel_fasilitas',
                'wa_message_template' => "Assalamu'alaikum Admin IZI Travel, saya ingin mengetahui detail fasilitas {campaign} yang saya lihat di {ad}.",
                'is_active' => true,
            ],
        ];

        foreach ($ads as $ad) {
            $campaign->ads()->updateOrCreate(
                ['utm_content' => $ad['utm_content']],
                $ad
            );
        }
    }
}
