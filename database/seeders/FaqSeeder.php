<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Apakah harga paket sudah termasuk pengurusan visa?',
                'answer' => 'Ya, seluruh paket Umrah IZI Travel sudah termasuk pengurusan visa Umrah resmi secara terintegrasi langsung dengan sistem kedutaan.',
                'order' => 1,
            ],
            [
                'question' => 'Bagaimana sistem pembayaran paket?',
                'answer' => 'Pembayaran dapat dilakukan dengan aman melalui transfer bank ke rekening resmi perusahaan atas nama PT IZI Travel, atau kunjungan langsung ke kantor pusat kami.',
                'order' => 2,
            ],
            [
                'question' => 'Seberapa dekat jarak hotel dengan Masjidil Haram & Nabawi?',
                'answer' => 'Kami memilih hotel-hotel pilihan bintang 4 & 5 terbaik di Ring 1 pelataran Masjidil Haram di Makkah dan Masjidil Nabawi di Madinah untuk kenyamanan ibadah Anda.',
                'order' => 3,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}
