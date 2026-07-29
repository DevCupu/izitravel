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
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
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
            [
                'name' => 'Hj. Kartini Kartono',
                'location' => 'Semarang',
                'message' => 'Perjalanan umrah pertama yang luar biasa bersama IZI Travel. Nyaman untuk lansia, fasilitas kursi roda dibantu dengan tulus oleh mutawwif. Sangat direkomendasikan.',
                'rating' => 5,
                'order' => 4,
            ],
            [
                'name' => 'H. Yusuf Bachtiar',
                'location' => 'Medan',
                'message' => 'Sangat merekomendasikan paket VIP IZI Travel. Jarak hotel ke masjid dekat sekali, mutawwifnya berilmu tinggi, penjelasannya runtut dan menambah kekhusyukan ibadah kami.',
                'rating' => 5,
                'order' => 5,
                'video_url' => 'https://www.instagram.com/reel/C8C8C8C8C8C/',
            ],
            [
                'name' => 'Hj. Laila Sari',
                'location' => 'Makassar',
                'message' => 'Alhamdulillah, terima kasih IZI Travel atas pelayanan yang luar biasa. Anak-anak dan orang tua saya merasa sangat nyaman selama beribadah di tanah suci.',
                'rating' => 5,
                'order' => 6,
            ],
            [
                'name' => 'H. Ahmad Dahlan',
                'location' => 'Yogyakarta',
                'message' => 'Fasilitas manasik umrah sebelum berangkat sangat mendalam dan interaktif. Pas keberangkatan semua berjalan teratur dan rapi. Penanganan bagasi pun sangat cepat.',
                'rating' => 4,
                'order' => 7,
            ],
            [
                'name' => 'Hj. Fatimah Az-Zahra',
                'location' => 'Palembang',
                'message' => 'Sangat berkesan beribadah bersama IZI Travel. Setiap detail perjalanan diperhatikan dengan sangat baik oleh tim pendamping.',
                'rating' => 5,
                'order' => 8,
            ],
            [
                'name' => 'H. Budi Santoso',
                'location' => 'Solo',
                'message' => 'Layanan customer service sangat responsif dalam menjawab pertanyaan sebelum keberangkatan. Hotel bersih dan dekat masjid. Sangat memuaskan.',
                'rating' => 4,
                'order' => 9,
            ],
            [
                'name' => 'Hj. Nurul Hidayah',
                'location' => 'Banjarmasin',
                'message' => 'Terima kasih IZI Travel atas pengaturan hotel di Ring 1 yang luar biasa memudahkan kami untuk selalu shalat berjamaah di masjid tepat waktu.',
                'rating' => 5,
                'order' => 10,
            ],
            [
                'name' => 'H. Riza Fahlevi',
                'location' => 'Denpasar',
                'message' => 'Umrah bersama keluarga besar berjalan sukses tanpa hambatan berarti. Makanan yang disediakan bervariasi dan enak sekali.',
                'rating' => 5,
                'order' => 11,
            ],
            [
                'name' => 'Hj. Ratna Sari',
                'location' => 'Balikpapan',
                'message' => 'Sangat mengapresiasi kesabaran para pembimbing umrah selama di Makkah. Kami merasa dibimbing dengan benar sesuai tuntunan sunnah.',
                'rating' => 5,
                'order' => 12,
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
