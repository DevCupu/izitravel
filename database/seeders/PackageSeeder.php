<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Umrah VIP Al-Harmain (12 Hari)',
                'slug' => 'umrah-vip-al-harmain-12-hari',
                'duration_days' => 12,
                'departure_date' => '2026-08-28',
                'airline' => 'Garuda Indonesia (Direct)',
                'hotel' => 'Safwah Royal Orchid / Mövenpick',
                'hotel_makkah' => 'Safwah Royal Orchid',
                'hotel_makkah_nights' => 7,
                'hotel_madinah' => 'Mövenpick Hotel Madinah',
                'hotel_madinah_nights' => 4,
                'inclusions' => [
                    ['icon' => 'plane', 'label' => 'Tiket pesawat PP', 'desc' => 'Penerbangan pulang-pergi dengan maskapai terpercaya'],
                    ['icon' => 'file-check', 'label' => 'Pengurusan visa resmi', 'desc' => 'Visa umrah resmi yang diproses langsung'],
                    ['icon' => 'building-2', 'label' => 'Hotel pelataran masjid', 'desc' => 'Akomodasi dekat Masjidil Haram & Nabawi'],
                    ['icon' => 'shield-check', 'label' => 'Asuransi perjalanan', 'desc' => 'Perlindungan selama perjalanan ibadah'],
                    ['icon' => 'luggage', 'label' => 'Perlengkapan ibadah', 'desc' => 'Koper, kain ihram, buku doa, & lainnya'],
                    ['icon' => 'compass', 'label' => 'Muthawwif bersertifikat', 'desc' => 'Pembimbing ibadah profesional & berpengalaman'],
                    ['icon' => 'utensils', 'label' => 'Makan prasmanan 3x', 'desc' => 'Menu halal & beragam setiap hari'],
                    ['icon' => 'book-open', 'label' => 'Handling & manasik', 'desc' => 'Bimbingan manasik pra-keberangkatan'],
                ],
                'price' => 38500000,
                'category' => 'Premium',
                'badge_label' => 'BEST SELLER',
                'badge_color' => 'amber',
                'image' => 'images/package_kaaba.webp',
                'order' => 1,
            ],
            [
                'name' => 'Umrah Syawal Istimewa (9 Hari)',
                'slug' => 'umrah-syawal-istimewa-9-hari',
                'duration_days' => 9,
                'departure_date' => '2026-09-18',
                'airline' => 'Saudia Airlines (Direct)',
                'hotel' => 'Anjum Makkah / Aqeeq Madinah',
                'hotel_makkah' => 'Anjum Makkah Hotel',
                'hotel_makkah_nights' => 5,
                'hotel_madinah' => 'Aqeeq Madinah Hotel',
                'hotel_madinah_nights' => 3,
                'inclusions' => [
                    ['icon' => 'plane', 'label' => 'Tiket pesawat PP', 'desc' => 'Penerbangan pulang-pergi dengan maskapai terpercaya'],
                    ['icon' => 'file-check', 'label' => 'Pengurusan visa resmi', 'desc' => 'Visa umrah resmi yang diproses langsung'],
                    ['icon' => 'building-2', 'label' => 'Hotel pelataran masjid', 'desc' => 'Akomodasi dekat Masjidil Haram & Nabawi'],
                    ['icon' => 'shield-check', 'label' => 'Asuransi perjalanan', 'desc' => 'Perlindungan selama perjalanan ibadah'],
                    ['icon' => 'luggage', 'label' => 'Perlengkapan ibadah', 'desc' => 'Koper, kain ihram, buku doa, & lainnya'],
                    ['icon' => 'compass', 'label' => 'Muthawwif bersertifikat', 'desc' => 'Pembimbing ibadah profesional & berpengalaman'],
                    ['icon' => 'utensils', 'label' => 'Makan prasmanan 3x', 'desc' => 'Menu halal & beragam setiap hari'],
                    ['icon' => 'book-open', 'label' => 'Handling & manasik', 'desc' => 'Bimbingan manasik pra-keberangkatan'],
                ],
                'price' => 32900000,
                'category' => 'Ekonomi',
                'badge_label' => 'PROMO KHUSUS',
                'badge_color' => 'emerald',
                'image' => 'images/package_nabawi.webp',
                'order' => 2,
            ],
            [
                'name' => 'Umrah Akhir Tahun Deluxe (12 Hari)',
                'slug' => 'umrah-akhir-tahun-deluxe-12-hari',
                'duration_days' => 12,
                'departure_date' => '2026-10-25',
                'airline' => 'Qatar Airways',
                'hotel' => 'Fairmont Clock Tower / Pullman',
                'hotel_makkah' => 'Fairmont Clock Tower Makkah',
                'hotel_makkah_nights' => 7,
                'hotel_madinah' => 'Pullman Zamzam Madinah',
                'hotel_madinah_nights' => 4,
                'inclusions' => [
                    ['icon' => 'plane', 'label' => 'Tiket pesawat PP', 'desc' => 'Penerbangan pulang-pergi dengan maskapai terpercaya'],
                    ['icon' => 'file-check', 'label' => 'Pengurusan visa resmi', 'desc' => 'Visa umrah resmi yang diproses langsung'],
                    ['icon' => 'building-2', 'label' => 'Hotel pelataran masjid', 'desc' => 'Akomodasi dekat Masjidil Haram & Nabawi'],
                    ['icon' => 'shield-check', 'label' => 'Asuransi perjalanan', 'desc' => 'Perlindungan selama perjalanan ibadah'],
                    ['icon' => 'luggage', 'label' => 'Perlengkapan ibadah', 'desc' => 'Koper, kain ihram, buku doa, & lainnya'],
                    ['icon' => 'compass', 'label' => 'Muthawwif bersertifikat', 'desc' => 'Pembimbing ibadah profesional & berpengalaman'],
                    ['icon' => 'utensils', 'label' => 'Makan prasmanan 3x', 'desc' => 'Menu halal & beragam setiap hari'],
                    ['icon' => 'book-open', 'label' => 'Handling & manasik', 'desc' => 'Bimbingan manasik pra-keberangkatan'],
                ],
                'price' => 42500000,
                'category' => 'Premium',
                'badge_label' => 'VIP DELUXE',
                'badge_color' => 'indigo',
                'image' => 'images/package_kaaba.webp',
                'order' => 3,
            ],
            [
                'name' => 'Umrah Ramadhan Berkah (15 Hari)',
                'slug' => 'umrah-ramadhan-berkah-15-hari',
                'duration_days' => 15,
                'departure_date' => '2026-11-15',
                'airline' => 'Emirates Airlines',
                'hotel' => 'Swissôtel Makkah / Mövenpick',
                'hotel_makkah' => 'Swissôtel Makkah',
                'hotel_makkah_nights' => 9,
                'hotel_madinah' => 'Mövenpick Hotel Madinah',
                'hotel_madinah_nights' => 5,
                'inclusions' => [
                    ['icon' => 'plane', 'label' => 'Tiket pesawat PP', 'desc' => 'Penerbangan pulang-pergi dengan maskapai terpercaya'],
                    ['icon' => 'file-check', 'label' => 'Pengurusan visa resmi', 'desc' => 'Visa umrah resmi yang diproses langsung'],
                    ['icon' => 'building-2', 'label' => 'Hotel pelataran masjid', 'desc' => 'Akomodasi dekat Masjidil Haram & Nabawi'],
                    ['icon' => 'shield-check', 'label' => 'Asuransi perjalanan', 'desc' => 'Perlindungan selama perjalanan ibadah'],
                    ['icon' => 'luggage', 'label' => 'Perlengkapan ibadah', 'desc' => 'Koper, kain ihram, buku doa, & lainnya'],
                    ['icon' => 'compass', 'label' => 'Muthawwif bersertifikat', 'desc' => 'Pembimbing ibadah profesional & berpengalaman'],
                    ['icon' => 'utensils', 'label' => 'Makan prasmanan 3x', 'desc' => 'Menu halal & beragam setiap hari'],
                    ['icon' => 'book-open', 'label' => 'Handling & manasik', 'desc' => 'Bimbingan manasik pra-keberangkatan'],
                ],
                'price' => 49900000,
                'category' => 'VVIP',
                'badge_label' => 'KUOTA TERBATAS',
                'badge_color' => 'rose',
                'image' => 'images/package_nabawi.webp',
                'order' => 4,
            ],
        ];

        foreach ($packages as $package) {
            Package::updateOrCreate(
                ['name' => $package['name']],
                $package
            );
        }
    }
}
