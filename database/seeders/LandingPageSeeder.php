<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Partner;
use App\Models\Setting;
use App\Models\Team;
use Illuminate\Database\Seeder;

class LandingPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Settings Data
        $settings = [
            'about_title' => 'Melayani Perjalanan Suci Anda dengan Sepenuh Hati',
            'about_description' => "Sebuah perusahaan yang bergerak di bidang biro perjalanan umroh dengan pelayanan dan kualitas nyaman yang menyasar para generasi muda juga kalangan menengah kebawah. Paket Umroh izitravel: Paket Hemat: menawarkan pengalaman ibadah umroh yang lebih nyaman dan terstruktur. Paket Hemat Banget: berfokus pada efisiensi biaya dengan menyediakan fasilitas standar yang esensial bagi jamaah yang ingin umroh dengan anggaran seminimal mungkin.",
            'about_satisfaction_rate' => '99',
            'about_departed_count' => '10',
            'about_vision' => 'Menjadi penyelenggara perjalanan ibadah Umrah dan Haji premium terbaik di Indonesia yang terpercaya, modern, dan profesional tanpa mengurangi nilai kemurnian ibadah.',
            'about_mission' => "Menyediakan pelayanan prima melalui kepastian program, bimbingan syar'i terarah, fasilitas hotel dekat Haramain, serta pendampingan maksimal dari sebelum keberangkatan hingga kembali ke tanah air.",
            // Haramain Live & Info Center settings
            'haramain_density_base' => '254025',
            'haramain_youtube_makkah' => 'UCos52azQNBgW63_9uDJoPDA',
            'haramain_youtube_madinah' => 'UCROKYPep-UuODNwyipe6JMw',
            'contact_whatsapp' => '62818324244',
            'contact_whatsapp_cofounder' => '62818324244',
            'contact_phone' => '+62 818-324-244',
            'contact_email' => 'info@izitravel.com',
            'contact_address' => 'Jl. Abdullah Daeng Sirua No.61, Tamamaung, Kec. Panakkukang, Kota Makassar, Sulawesi Selatan 90231',
            'site_name' => 'IZI Travel',
            'site_tagline' => 'Wujudkan Umrah Impian Anda Bersama IZI Travel',
            'site_description' => 'Sebuah perusahaan yang bergerak di bidang biro perjalanan umroh dengan pelayanan dan kualitas nyaman yang menyasar para generasi muda juga kalangan menengah kebawah.',
            'site_logo' => 'images/Izi LOGO.webp',
            'site_favicon' => 'images/favicon.png',
            'contact_gmaps' => 'https://maps.google.com/maps?q=Jl.%20Abdullah%20Daeng%20Sirua%20No.61,%20Tamamaung,%20Makassar&t=&z=15&ie=UTF8&iwloc=&output=embed',
            'office_hours' => 'Senin - Sabtu: 08:00 - 17:00',
            'social_facebook' => 'https://facebook.com/izitravel',
            'social_instagram' => 'https://instagram.com/izitravel',
            'social_youtube' => 'https://youtube.com/izitravel',
            'social_tiktok' => 'https://tiktok.com/@izitravel',
            'social_twitter' => 'https://twitter.com/izitravel',
            'registration_step_1_title' => 'Pilih Paket',
            'registration_step_1_description' => 'Pilih paket yang sesuai dengan tanggal dan keinginan Anda.',
            'registration_step_2_title' => 'Konsultasi',
            'registration_step_2_description' => 'Hubungi customer service kami untuk detail keberangkatan.',
            'registration_step_3_title' => 'Kirim Berkas',
            'registration_step_3_description' => 'Lengkapi dokumen paspor, foto, dan syarat administrasi.',
            'registration_step_4_title' => 'Uang Muka (DP)',
            'registration_step_4_description' => 'Lakukan deposit untuk mengamankan kursi penerbangan Anda.',
            'registration_step_5_title' => 'Manasik',
            'registration_step_5_description' => 'Ikuti bimbingan manasik teori & praktek sesuai sunnah.',
            'registration_step_6_title' => 'Berangkat',
            'registration_step_6_description' => 'Pelepasan di bandara dan mulai perjalanan ibadah Anda.',
            'package_note_1' => 'Harga dapat berubah sewaktu-waktu mengikuti kurs & ketersediaan kuota. Hubungi tim kami untuk ketersediaan seat terbaru dan skema pembayaran.',
            'package_note_2' => 'Pendaftaran ditutup paling lambat 3 minggu sebelum keberangkatan atau saat kuota habis.',
            'package_note_3' => 'Tersedia skema cicilan hingga 12x tanpa bunga. Hubungi admin kami untuk info lebih lanjut.',

            // Hero Section
            'hero_badge' => 'Penyelenggara Umrah & Haji Khusus Resmi',
            'cta_packages_label' => 'Lihat Paket',
            'cta_consultation_label' => 'Konsultasi Gratis',
            'trust_icon_1' => 'Izin Resmi Kemenag (PPIU)',
            'trust_icon_2' => 'Pasti Berangkat',
            'trust_icon_3' => 'Layanan Premium Bintang 5',
            'hero_badge_1' => 'Standar Pelayanan Bintang 5',
            'hero_badge_2' => 'Hotel Eksklusif Pelataran Masjid',
            'hero_badge_3' => 'Jaminan Tiket PP & Visa Resmi',
            'hero_stat_title' => 'Total Keberangkatan',
            'hero_stat_subtitle' => 'Jamaah terberangkatkan',
            'hero_stat_value' => '10K+',
            'trust_card_1_title' => 'Berizin Resmi',
            'trust_card_1_subtitle' => 'Kemenag RI',
            'trust_card_1_icon' => 'shield-check',
            'trust_card_2_title' => 'Tim Profesional',
            'trust_card_2_subtitle' => 'Berpengalaman',
            'trust_card_2_icon' => 'users',
            'trust_card_3_title' => 'Fasilitas Lengkap',
            'trust_card_3_subtitle' => 'Hotel & Transportasi',
            'trust_card_3_icon' => 'building-2',
            'trust_card_4_title' => 'Pelayanan Prima',
            'trust_card_4_subtitle' => 'Kenyamanan Anda',
            'trust_card_4_icon' => 'heart',
            'hero_image' => 'images/package_kaaba.webp',

            // Packages Section
            'packages_section_title' => 'Paket Umrah Kami',
            'packages_section_subtitle' => 'Pilihan paket perjalanan terbaik dengan fasilitas hotel premium Ring 1 demi kenyamanan ibadah Anda.',
            'packages_price_label' => 'Mulai dari',
            'packages_detail_btn' => 'Detail Paket',

            // Features Section
            'features_section_title' => 'Keunggulan Layanan Kami',
            'features_section_subtitle' => 'Mitra tepercaya perjalanan ibadah Anda dengan standar pelayanan tinggi dan kekeluargaan.',
            'feature_1_title' => 'Legalitas Resmi Kemenag',
            'feature_1_desc' => 'Memiliki izin PPIU resmi dari Kementerian Agama RI untuk kepastian keamanan hukum perjalanan Anda.',
            'feature_2_title' => 'Jaminan Visa Umrah',
            'feature_2_desc' => 'Proses penerbitan visa yang aman, transparan, and terkonfirmasi langsung ke sistem kedutaan.',
            'feature_3_title' => 'Hotel Dekat Pelataran',
            'feature_3_desc' => 'Akomodasi hotel bintang pilihan dengan jarak yang dekat memudahkan Anda beribadah di Masjidil Haram & Nabawi.',
            'feature_4_title' => 'Muthawwif Khas Nusantara',
            'feature_4_desc' => 'Muthawwif & pembimbing ibadah bersertifikasi, membimbing sesuai sunnah dengan keramahan khas Indonesia.',
            'feature_5_title' => 'Layanan Siaga & Peduli',
            'feature_5_desc' => 'Customer support dan tim handling operasional siaga melayani Anda 24 jam dengan asas kekeluargaan.',
            'feature_6_title' => 'Kepastian Tiket Terbang',
            'feature_6_desc' => 'Kepastian tanggal keberangkatan dengan tiket pesawat premium (PP) yang telah issued sejak pendaftaran.',

            // About Section
            'about_badge' => 'Tentang Kami',
            'about_stat_1_label' => 'Kepuasan Jamaah',
            'about_stat_2_label' => 'Jamaah Berangkat',
            'about_vision_label' => 'Visi Kami',
            'about_mission_label' => 'Misi Kami',

            // Haramain Section
            'haramain_badge' => 'Info Haramain Live & Waktu Shalat',
            'haramain_title' => 'Kabar Tanah Suci & Jadwal Shalat',
            'haramain_subtitle' => 'Pantau kondisi langsung Masjidil Haram & Masjidil Nabawi, waktu shalat aktual, serta informasi cuaca Makkah secara real-time.',

            // Team Section
            'team_section_title' => 'Di Balik Layar IZI Travel',
            'team_section_subtitle' => 'Dipimpin oleh tim profesional berpengalaman yang mendedikasikan diri sepenuhnya untuk melayani tamu-tamu Allah SWT.',
            'team_other_section_label' => 'Tim Pendukung & Pembimbing',

            // Partners Section
            'partners_section_title' => 'Mitra Maskapai Penerbangan',
            'partners_extra' => '+ Akomodasi Bintang 5',

            // Gallery Section
            'gallery_label' => 'Galeri & Dokumentasi',
            'gallery_section_title' => 'Galeri Kegiatan & Testimoni',
            'gallery_section_subtitle' => 'Dokumentasi perjalanan jamaah IZI Travel dan testimoni langsung dari Baitullah.',
            'gallery_filter_all' => 'Semua',
            'gallery_filter_photo' => 'Foto Kegiatan',
            'gallery_filter_video' => 'Video Testimoni',

            // Testimonials Section
            'testimonials_section_title' => 'Testimonials',
            'testimonials_section_subtitle' => 'Apa kata jamaah yang telah mempercayakan perjalanan ibadah mereka kepada kami.',

            // Articles Section
            'articles_label' => 'Artikel & Inspirasi',
            'articles_section_title' => 'Kabar & Tips Umrah Terbaru',
            'articles_section_subtitle' => 'Dapatkan panduan ibadah terpercaya, informasi destinasi, serta tips kesehatan untuk kelancaran umrah Anda.',
            'articles_filter_all' => 'Semua',
            'articles_filter_panduan' => 'Panduan Umrah',
            'articles_filter_tips' => 'Tips & Doa',
            'articles_filter_haramain' => 'Info Haramain',
            'articles_read_suffix' => 'Baca',
            'articles_read_more' => 'Baca',

            // Partnership Section
            'partnership_badge' => 'Program Kemitraan',
            'partnership_title' => 'Mari Bergabung Menjadi Mitra Syiar Baitullah',
            'partnership_subtitle' => 'Menjadi mitra syiar baitullah berkesempatan mendapatkan komisi hingga puluhan juta rupiah bahkan berkesempatan untuk umroh.',
            'partnership_reg_label' => 'Biaya Pendaftaran',
            'partnership_tier_1_badge' => 'Freelance',
            'partnership_tier_1_title' => 'Mitra Freelance',
            'partnership_tier_1_price' => 'FREE',
            'partnership_tier_1_feature_1' => 'Komisi Menarik per Jemaah',
            'partnership_tier_1_feature_2' => 'Dukungan brosur digital & marketing kit',
            'partnership_tier_1_feature_3' => 'Bebas target bulanan & tanpa modal',
            'partnership_tier_1_feature_4' => 'Waktu kerja fleksibel',
            'partnership_tier_2_badge' => 'Agen Resmi',
            'partnership_tier_2_title' => 'Mitra Agen',
            'partnership_tier_2_price' => 'Rp 1.000.000',
            'partnership_tier_2_feature_1' => 'Komisi maksimal & bonus menarik',
            'partnership_tier_2_feature_2' => 'Starter kit fisik (spanduk & brosur cetak)',
            'partnership_tier_2_feature_3' => 'Sertifikat keagenan resmi IZI Travel',
            'partnership_tier_2_feature_4' => 'Pembekalan & prioritas bimbingan produk',
            'partnership_tier_3_title' => 'Keuntungan & Reward',
            'partnership_tier_3_subtitle' => 'Potensi Syiar Kemitraan',
            'partnership_reward_1_label' => 'Komisi per Jemaah',
            'partnership_reward_1_value' => 'Hingga Rp 2.000.000',
            'partnership_reward_1_desc' => 'Pendapatan langsung per jemaah yang melakukan pelunasan.',
            'partnership_reward_2_label' => 'Reward Prestasi',
            'partnership_reward_2_value' => 'Umroh Gratis',
            'partnership_reward_2_desc' => 'Kesempatan ibadah umrah gratis bagi mitra yang mencapai target syiar.',
            'partnership_cta_title' => 'Tertarik Menjadi Mitra IZI Travel?',
            'partnership_cta_desc' => 'Dapatkan proposal penawaran kemitraan resmi dan diskusikan peluang kerja sama bersama tim kami.',
            'partnership_cta_button' => 'Hubungi WhatsApp Kemitraan',

            // Registration Section
            'registration_title' => 'Alur Pendaftaran Mudah',
            'registration_subtitle' => '6 langkah mudah mempersiapkan perjalanan suci Anda bersama IZI Travel',

            // FAQ Section
            'faq_section_title' => 'Tanya Jawab (FAQ)',

            // CTA Section
            'cta_title' => 'Siap Memulai Perjalanan Suci Anda?',
            'cta_description' => 'Hubungi kami sekarang untuk mendapatkan rekomendasi paket terbaik sesuai kebutuhan dan budget perjalanan ibadah Anda.',
            'cta_button' => 'Hubungi WhatsApp',

            // Footer
            'footer_contact_heading' => 'Hubungi Kami',
            'footer_maps_heading' => 'Google Maps',
            'footer_social_heading' => 'Socials',
            'footer_ppiu_label' => 'Izin PPIU',
            'footer_ppiu_number' => 'A10BS81',

            // SEO & Search Engine Optimizations for Google / Bing Rank
            'seo_meta_keywords' => 'IZI TRAVEL, IZITRAVEL, IZI Travel Makassar, Travel Umroh Makassar, Travel Haji Makassar, Paket Umrah Premium Makassar, Penyelenggara Perjalanan Ibadah Umrah Resmi, Muthawwif Sunnah Makassar, Agen Umrah Terpercaya',
            'seo_author' => 'IZI TRAVEL Official',
            'seo_canonical_url' => 'https://izitravel.id',
            'seo_google_console_verification' => 'google80fbcfc0e29b139c',
            'seo_bing_verification' => 'BING_VERIFICATION_CODE_PLACEHOLDER',
            'seo_og_title' => 'IZI TRAVEL - Biro Perjalanan Umrah Premium Bintang 5 & Haji Khusus Resmi Kemenag',
            'seo_og_description' => 'IZITRAVEL adalah penyelenggara perjalanan ibadah Umrah dan Haji premium resmi Kemenag dengan hotel Ring 1 pelataran Masjidil Haram & Nabawi, serta bimbingan sesuai sunnah.',
            'seo_og_image' => 'images/package_kaaba.webp',
        ];

        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }

        // 2. Articles Data
        Article::updateOrCreate(
            ['slug' => 'panduan-lengkap-fiqih-umrah-sesuai-sunnah-bagi-pemula'],
            [
            'title' => 'Panduan Lengkap Fiqih Umrah Sesuai Sunnah bagi Pemula',
            'slug' => 'panduan-lengkap-fiqih-umrah-sesuai-sunnah-bagi-pemula',
            'category' => 'Panduan Umrah',
            'excerpt' => 'Kupas tuntas tata cara ibadah umrah sesuai sunnah Rasulullah SAW, mulai dari persiapan miqat, ihram, tawaf, sa\'i, hingga tahallul.',
            'content' => '
                <p class="text-slate-650 mb-6 text-sm md:text-base leading-relaxed">Menunaikan ibadah umrah merupakan dambaan setiap Muslim. Agar ibadah kita diterima oleh Allah SWT, sangat penting untuk memahami tata cara (fiqih) umrah yang shahih sesuai tuntunan Rasulullah SAW.</p>
                <h4 class="text-base font-bold text-slate-900 mt-6 mb-3">1. Persiapan Sebelum Miqat & Ihram</h4>
                <p class="text-slate-500 mb-4 text-xs md:text-sm leading-relaxed">Sebelum mengenakan pakaian ihram, disunnahkan untuk mandi besar (jinabah), memotong kuku, dan merapikan jenggot atau kumis. Bagi laki-laki, pakaian ihram terdiri dari dua helai kain putih tanpa jahitan, sedangkan wanita memakai pakaian syar\'i yang menutup seluruh aurat kecuali wajah dan telapak tangan.</p>
                <h4 class="text-base font-bold text-slate-900 mt-6 mb-3">2. Mengambil Miqat & Membaca Niat</h4>
                <p class="text-slate-500 mb-4 text-xs md:text-sm leading-relaxed">Miqat adalah batas tempat dimulainya ibadah umrah. Ketika melewati miqat, jemaah membaca niat umrah: <em>"Labbaykallahumma Umratan"</em>. Setelah berniat, jemaah dilarang melakukan larangan-larangan ihram seperti memakai wewangian, memotong rambut, atau berburu.</p>
            ',
            'author' => 'Ustadz Dr. H. Ahmad Fauzi',
            'author_role' => 'Pembimbing Utama IZI Travel',
            'published_at' => '12 Juni 2026',
            'read_time' => '6 Min',
            'image' => 'images/gallery_manasik.webp',
            'is_active' => true,
            'order' => 1,
        ]);

        Article::updateOrCreate(
            ['slug' => 'tips-menjaga-fisik-dan-stamina-selama-umrah-di-cuaca-ekstrem'],
            [
            'title' => 'Tips Menjaga Fisik dan Stamina Selama Umrah di Cuaca Ekstrem',
            'slug' => 'tips-menjaga-fisik-dan-stamina-selama-umrah-di-cuaca-ekstrem',
            'category' => 'Tips & Doa',
            'excerpt' => 'Perbedaan cuaca di tanah suci menuntut kesiapan fisik jemaah. Ikuti tips praktis ini agar kondisi tubuh Anda tetap bugar selama beribadah.',
            'content' => '
                <p class="text-slate-650 mb-6 text-sm md:text-base leading-relaxed">Suhu udara di Arab Saudi sering kali sangat berbeda dengan di Indonesia, baik saat musim panas maupun musim dingin. Kesiapan stamina menjadi kunci utama kenyamanan ibadah Anda.</p>
                <h4 class="text-base font-bold text-slate-900 mt-6 mb-3">1. Cukupi Kebutuhan Air (Cegah Dehidrasi)</h4>
                <p class="text-slate-500 mb-4 text-xs md:text-sm leading-relaxed">Jangan menunggu haus untuk minum. Minumlah air zam-zam atau air mineral minimal 3 liter sehari. Hindari minuman manis berlebih yang memicu rasa haus lebih cepat.</p>
            ',
            'author' => 'dr. Siti Aminah, Sp.KO',
            'author_role' => 'Tim Medis IZI Travel',
            'published_at' => '10 Juni 2026',
            'read_time' => '4 Min',
            'image' => 'images/gallery_departure.webp',
            'is_active' => true,
            'order' => 2,
        ]);

        Article::updateOrCreate(
            ['slug' => 'mengenal-sejarah-raudhah-di-masjid-nabawi-keutamaannya'],
            [
            'title' => 'Mengenal Sejarah Raudhah di Masjid Nabawi & Keutamaannya',
            'slug' => 'mengenal-sejarah-raudhah-di-masjid-nabawi-keutamaannya',
            'category' => 'Info Haramain',
            'excerpt' => 'Taman Surga di dunia yang terletak di dalam Masjid Nabawi. Pelajari sejarah, tata tertib masuk, serta doa-doa terbaik yang dianjurkan.',
            'content' => '
                <p class="text-slate-650 mb-6 text-sm md:text-base leading-relaxed">Raudhah adalah area mulia di dalam Masjid Nabawi yang terletak di antara makam Rasulullah SAW (dahulu kamar beliau) dan mimbar masjid. Tempat ini sangat mustajab untuk berdoa.</p>
            ',
            'author' => 'H. Irfan Novian',
            'author_role' => 'CEO & Tour Leader IZI Travel',
            'published_at' => '08 Juni 2026',
            'read_time' => '5 Min',
            'image' => 'images/gallery_madinah2.webp',
            'is_active' => true,
            'order' => 3,
        ]);

        // 3. Teams Data (Di Balik Layar)
        Team::updateOrCreate(
            ['name' => 'H. Irfan Novian'],
            ['role' => 'CEO & Founder', 'initial' => 'IN', 'description' => 'Seorang pengusaha Muslim yang berkomitmen memodernisasi layanan travel umrah dengan sentuhan teknologi tanpa mengurangi nilai syar\'i.', 'order' => 1, 'is_active' => true]
        );

        Team::updateOrCreate(
            ['name' => 'Hj. Ratna Sari'],
            ['role' => 'Co-Founder & COO', 'initial' => 'RS', 'description' => 'Berpengalaman lebih dari 10 tahun dalam manajemen operasional haji & umrah, memastikan kualitas layanan hotel dan akomodasi terbaik.', 'order' => 2, 'is_active' => true]
        );

        Team::updateOrCreate(
            ['name' => 'Ust. H. Ahmad Fauzan, Lc.'],
            ['role' => 'Pembina Ibadah / Syariah', 'initial' => 'AF', 'description' => 'Lulusan Universitas Islam Madinah yang membimbing jamaah selama manasik dan pelaksanaan umrah agar sepenuhnya sesuai tuntunan sunnah.', 'order' => 3, 'is_active' => true]
        );

        Team::updateOrCreate(
            ['name' => 'Siti Halimah'],
            ['role' => 'Customer Relation', 'initial' => 'SH', 'description' => 'Siap melayani kebutuhan administrasi, pendaftaran, dan informasi jadwal keberangkatan jamaah dengan ramah.', 'order' => 4, 'is_active' => true]
        );

        Team::updateOrCreate(
            ['name' => 'Ust. Abdullah Syakir'],
            ['role' => 'Muthawwif / Pembimbing', 'initial' => 'AS', 'description' => 'Pembimbing ibadah lapangan yang berpengalaman membimbing jamaah selama berada di tanah suci Makkah dan Madinah.', 'order' => 5, 'is_active' => true]
        );

        // 4. Partners Data (Mitra Maskapai)
        Partner::updateOrCreate(
            ['name' => 'Garuda Indonesia'],
            ['logo_type' => 'svg', 'logo_path' => '<svg class="h-7 text-slate-700" viewBox="0 0 200 40" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M10 20c5-5 15-5 20 2c-3-6-12-8-20-2z" fill="currentColor"/><path d="M8 23c6-3 16-2 18 5c-4-5-13-6-18-5z" fill="currentColor"/><path d="M7 26c6-1 15 2 15 9c-3-5-11-5-15-9z" fill="currentColor"/><text x="42" y="26" font-family="\'Plus Jakarta Sans\', sans-serif" font-weight="bold" font-size="16" fill="currentColor">Garuda Indonesia</text></svg>', 'order' => 1, 'is_active' => true]
        );

        Partner::updateOrCreate(
            ['name' => 'SAUDIA'],
            ['logo_type' => 'svg', 'logo_path' => '<svg class="h-7 text-slate-700" viewBox="0 0 160 40" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M15 10c0 5 4 8 4 13h-2c0-5-4-8-4-13z M19 23h2c0-5 4-8 4-13h-2c0 5-4 8-4 13z" fill="currentColor"/><path d="M17 12c-3-4-8-4-10-2c3-1 7-1 10 2z M17 12c3-4 8-4 10-2c-3-1-7-1-10 2z" fill="currentColor"/><text x="35" y="27" font-family="\'Plus Jakarta Sans\', sans-serif" font-weight="bold" font-size="18" letter-spacing="1" fill="currentColor">SAUDIA</text></svg>', 'order' => 2, 'is_active' => true]
        );

        Partner::updateOrCreate(
            ['name' => 'Lion Air'],
            ['logo_type' => 'svg', 'logo_path' => '<svg class="h-7 text-rose-600" viewBox="0 0 160 40" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M10 10c10 0 15 8 18 15c-5-5-12-8-18-15z" fill="#E11D48"/><path d="M8 15c10 3 13 12 14 18c-4-6-10-10-14-18z" fill="#E11D48"/><text x="35" y="27" font-family="\'Plus Jakarta Sans\', sans-serif" font-weight="extrabold" font-size="18" fill="#E11D48">Lion Air</text></svg>', 'order' => 3, 'is_active' => true]
        );

        Partner::updateOrCreate(
            ['name' => 'Emirates'],
            ['logo_type' => 'svg', 'logo_path' => '<svg class="h-7 text-red-600" viewBox="0 0 120 40" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M6 10c3-1 6-3 8-6c-1 3-3 6-8 6zm4 2c2-1 4-3 5-5c-1 2-2 4-5 5zm-7-2c2 0 4-2 5-4c-1 2-2 3-5 4z" fill="#D2042D"/><text x="24" y="27" font-family="\'Plus Jakarta Sans\', sans-serif" font-weight="800" font-size="16" fill="#D2042D" letter-spacing="1">Emirates</text></svg>', 'order' => 4, 'is_active' => true]
        );

        Partner::updateOrCreate(
            ['name' => 'Qatar Airways'],
            ['logo_type' => 'svg', 'logo_path' => '<svg class="h-7 text-slate-700" viewBox="0 0 180 40" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 10c0 5-5 8-8 12c4-2 8-6 8-12z" fill="#5A0B2C"/><path d="M12 10c2 4 6 7 10 9c-4-1-8-4-10-9z" fill="#5A0B2C"/><text x="30" y="26" font-family="\'Plus Jakarta Sans\', sans-serif" font-weight="bold" font-size="16" letter-spacing="1" fill="#5A0B2C">QATAR</text><text x="95" y="26" font-family="\'Plus Jakarta Sans\', sans-serif" font-weight="normal" font-size="13" letter-spacing="1" fill="currentColor">AIRWAYS</text></svg>', 'order' => 5, 'is_active' => true]
        );
    }
}
