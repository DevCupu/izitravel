<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'H. Muhammad Ridwan',
                'location' => 'Jakarta',
                'message' => 'Sangat puas dengan pelayanan IZI Travel. Hotel di Makkah dan Madinah sangat dekat dengan Masjidil Haram dan Nabawi. Pembimbing umrah sangat sabar dan menguasai manasik dengan baik.',
                'rating' => 5,
                'order' => 1,
            ],
            [
                'name' => 'Hj. Siti Aminah',
                'location' => 'Bandung',
                'message' => 'Proses pendaftaran, pembuatan paspor dan visa semuanya dibantu sampai selesai. Jadwal keberangkatan tepat waktu dan fasilitas bus AC selama di Arab Saudi sangat nyaman. Terima kasih!',
                'rating' => 5,
                'order' => 2,
            ],
            [
                'name' => 'H. Achmad Fauzi',
                'location' => 'Surabaya',
                'message' => 'Pelayanan prima sejak di tanah air hingga kembali ke Indonesia. Fasilitas hotel bintang 5 sesuai dengan yang dijanjikan, makanan prasmanan selalu cocok dengan lidah Indonesia.',
                'rating' => 5,
                'order' => 3,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(
                ['name' => $testimonial['name']],
                $testimonial
            );
        }
    }
}
