<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing galleries to avoid duplicate seeding issues
        Gallery::truncate();

        // Standard default items
        $items = [
            [
                'type' => 'photo',
                'title' => 'Pelepasan Keberangkatan',
                'category_label' => 'Foto Kegiatan',
                'image' => 'images/gallery_departure.webp',
                'order' => 1,
            ],
            [
                'type' => 'photo',
                'title' => 'Transit & Keberangkatan',
                'category_label' => 'Foto Kegiatan',
                'image' => 'images/gallery_airport.webp',
                'order' => 2,
            ],
            [
                'type' => 'photo',
                'title' => "Thawaf & Sa'i Jamaah",
                'category_label' => 'Foto Kegiatan',
                'image' => 'images/gallery_makkah1.webp',
                'order' => 3,
            ],
            [
                'type' => 'photo',
                'title' => 'Ziarah Masjid Nabawi',
                'category_label' => 'Foto Kegiatan',
                'image' => 'images/gallery_madinah1.webp',
                'order' => 4,
            ],
            [
                'type' => 'photo',
                'title' => 'Akomodasi Hotel Bintang',
                'category_label' => 'Foto Kegiatan',
                'image' => 'images/gallery_makkah2.webp',
                'order' => 5,
            ],
            [
                'type' => 'photo',
                'title' => 'Ibadah Khusyuk di Raudhah',
                'category_label' => 'Foto Kegiatan',
                'image' => 'images/gallery_madinah2.webp',
                'order' => 6,
            ],
            [
                'type' => 'photo',
                'title' => 'Ziarah Kota Makkah',
                'category_label' => 'Foto Kegiatan',
                'image' => 'images/gallery_city_tour.webp',
                'order' => 7,
            ],
            [
                'type' => 'photo',
                'title' => 'Manasik Teori & Praktik',
                'category_label' => 'Foto Kegiatan',
                'image' => 'images/gallery_manasik.webp',
                'order' => 8,
            ],
            [
                'type' => 'video',
                'title' => "Testimoni Umrah Syar'i IZI",
                'category_label' => 'YouTube',
                'image' => 'images/package_kaaba.webp',
                'video_id' => 'L0c4f8d-DRE',
                'video_platform' => 'youtube',
                'order' => 9,
            ],
            [
                'type' => 'video',
                'title' => 'Indahnya Suasana Nabawi',
                'category_label' => 'YouTube',
                'image' => 'images/package_nabawi.webp',
                'video_id' => 'sYszx6TchM8',
                'video_platform' => 'youtube',
                'order' => 10,
            ],
            [
                'type' => 'video',
                'title' => 'Praktik Manasik Umrah',
                'category_label' => 'YouTube',
                'image' => 'images/gallery_manasik.webp',
                'video_id' => 'eUu6K9eE3tM',
                'video_platform' => 'youtube',
                'order' => 11,
            ],
            [
                'type' => 'video',
                'title' => 'Kesan Jamaah di Jabal Magnet',
                'category_label' => 'Instagram Reel',
                'image' => 'images/gallery_city_tour.webp',
                'video_id' => '563t3uP1yR0',
                'video_platform' => 'youtube',
                'order' => 12,
            ],
        ];

        // Add 13 more unique albums to simulate scale
        $extraAlbums = [
            'Umrah Ramadhan 2026',
            'Umrah Syawal 2026',
            'Ziarah Thaif 2026',
            'Ziarah Badar 2026',
            'Manasik Akbar 2026',
            'Rihlah Turki 2026',
            'Rihlah Mesir 2026',
            'Umrah Plus Aqsa 2026',
            'Umrah Reguler Jan 2026',
            'Umrah Akbar Feb 2026',
            'Umrah Syaban 2026',
            'Umrah Awal Musim 2026',
            'Milad IZI Travel 2026',
        ];

        foreach ($items as $item) {
            Gallery::create($item);
        }

        // Add 2 photos and 1 video for each extra album
        $photoImages = ['images/gallery_departure.webp', 'images/gallery_airport.webp', 'images/gallery_makkah1.webp', 'images/gallery_madinah1.webp', 'images/gallery_makkah2.webp', 'images/gallery_madinah2.webp', 'images/gallery_city_tour.webp', 'images/gallery_manasik.webp'];
        $videoIds = ['aBcD1eFgH2I', 'jKlM3nOpQ4R', 'sTuV5wXyZ6A', 'bCdE7fGhI8J', 'kLmN9oPqR0S', 'tUvW1xYzA2B', 'cDeF3gHiJ4K', 'lMnO5pQrS6T', 'uVwX7yZaB8C', 'dEfG9hIjK0L', 'mNoP1qRsT2U', 'vWxY3zAbC4D', 'eFgH5iJkL6M'];
        foreach ($extraAlbums as $index => $albumName) {
            Gallery::create([
                'type' => 'photo',
                'title' => "Dokumentasi Foto A - {$albumName}",
                'category_label' => $albumName,
                'image' => $photoImages[$index % count($photoImages)],
                'order' => 1,
            ]);

            Gallery::create([
                'type' => 'photo',
                'title' => "Dokumentasi Foto B - {$albumName}",
                'category_label' => $albumName,
                'image' => $photoImages[($index + 3) % count($photoImages)],
                'order' => 2,
            ]);

            Gallery::create([
                'type' => 'video',
                'title' => "Dokumentasi Video - {$albumName}",
                'category_label' => $albumName,
                'image' => 'images/package_kaaba.webp',
                'video_id' => $videoIds[$index],
                'video_platform' => 'youtube',
                'order' => 3,
            ]);
        }
    }
}
