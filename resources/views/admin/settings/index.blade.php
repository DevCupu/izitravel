<x-admin-layout :title="__('Pengaturan Website')">
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
            {{ __('Pengaturan Website') }}
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
            {{ __('Kelola profil perusahaan, kontak, visi misi, media sosial, dan alur pendaftaran.') }}
        </p>
    </x-slot>

    <script>
        function settingsData() {
            return {
                tab: localStorage.getItem('adminSettingsTab') || 'identity',
                q: '',
                siteDesc: (@json(old('site_description', $settings['site_description'] ?? ''))) || '',
                ogDesc: (@json(old('seo_og_description', $settings['seo_og_description'] ?? ''))) || '',
                gaId: (@json(old('seo_google_analytics_id', $settings['seo_google_analytics_id'] ?? ''))) || '',
                gscVerification: (@json(old('seo_google_console_verification', $settings['seo_google_console_verification'] ?? ''))) || '',
                bingVerification: (@json(old('seo_bing_verification', $settings['seo_bing_verification'] ?? ''))) || '',
                init() {
                    this.$watch('tab', v => localStorage.setItem('adminSettingsTab', v));
                    this.$watch('q', v => filterSettings(v));
                }
            };
        }
    </script>

    <div class="animate-fade-in-up" x-data="settingsData()">
        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 dark:border-red-800 bg-red-50/90 dark:bg-red-900/20 p-5">
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                    <h3 class="text-sm font-bold text-red-700 dark:text-red-300">Gagal menyimpan — {{ $errors->count() }} kolom perlu diperbaiki</h3>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-600 dark:text-red-400">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Banner Petunjuk Pengaturan Admin -->
        <div class="mb-6 rounded-2xl border border-blue-100 dark:border-blue-900/40 bg-gradient-to-r from-blue-50/80 via-slate-50 to-indigo-50/60 dark:from-slate-800 dark:to-slate-800/80 p-5 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                    <i data-lucide="help-circle" class="w-5 h-5"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white flex items-center gap-2">
                        Panduan Pengaturan Website untuk Admin
                        <span class="px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 text-[10px] font-bold">Informatif & Petunjuk Jelas</span>
                    </h3>
                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 leading-relaxed">
                        Halaman ini mengontrol seluruh informasi perusahaan, teks tombol, gambar banner, nomor WhatsApp, hingga SEO di website utama IZI Travel.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-3.5 pt-3 border-t border-blue-100/80 dark:border-slate-700/60 text-xs">
                        <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <span class="w-5 h-5 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 font-extrabold text-[11px] flex items-center justify-center shrink-0">1</span>
                            <span><b>Pilih Tab / Cari:</b> Klik kelompok tab di bawah atau ketik kata kunci di kotak cari.</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <span class="w-5 h-5 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 font-extrabold text-[11px] flex items-center justify-center shrink-0">2</span>
                            <span><b class="text-blue-600 dark:text-blue-400">📍 Petunjuk Lokasi:</b> Setiap kolom memiliki petunjuk di mana teks akan tampil di website.</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <span class="w-5 h-5 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 font-extrabold text-[11px] flex items-center justify-center shrink-0">3</span>
                            <span><b>Simpan Sekaligus:</b> Perubahan di seluruh tab tersimpan otomatis saat tombol Simpan ditekan.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kotak pencarian: cari pengaturan di semua tab sekaligus -->
        <div class="mb-5">
            <div class="relative max-w-lg">
                <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                <input type="text" x-model.debounce.200ms="q"
                       placeholder="Cari pengaturan… contoh: whatsapp, logo, harga, instagram"
                       class="w-full pl-10 pr-10 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                <button type="button" x-show="q.length" @click="q = ''" x-cloak
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <p x-show="q.length" x-cloak class="text-xs text-slate-500 dark:text-slate-400 mt-2 flex items-center gap-1.5">
                <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                Menampilkan hasil dari semua tab. Kosongkan kotak untuk kembali ke tampilan normal.
            </p>
        </div>

        @php
            $settingTabs = [
                'Pengaturan Utama' => [
                    ['identity', 'Identitas Website', 'globe', 'Nama website, logo perusahaan, dan favicon.'],
                    ['seo_advanced', 'SEO & Webmaster', 'sliders', 'Pengaturan lanjutan SEO, Open Graph, sitemap, dan robots.txt.'],
                ],
                'Tata Letak Halaman Utama (Berurutan)' => [
                    ['hero', '1. Section Hero / Beranda', 'sparkles', 'Teks kaligrafi, slogan, badge atas, 3 floating badges, statistik, 4 trust indicators, dan gambar background.'],
                    ['about', '2. Section Tentang Kami', 'info', 'Badge, judul utama, statistik kepuasan, visi & misi, dan gambar kolase Tentang Kami.'],
                    ['features', '3. Section Keunggulan (Kenapa Kami)', 'award', 'Badge, judul, subjudul, dan 6 kartu keunggulan beserta ikon/gambarnya.'],
                    ['flow', '4. Section Alur Pendaftaran', 'list-checks', 'Judul, subjudul, dan 6 langkah pendaftaran beserta ikonnya.'],
                    ['packages', '5. Section Paket Umrah', 'compass', 'Badge, judul, subjudul, label harga, teks tombol detail, dan 3 catatan penting paket.'],
                    ['gallery_testimonials', '6. Section Galeri & Testimoni', 'image', 'Badge, judul, subjudul untuk galeri kegiatan dan testimoni jamaah.'],
                    ['articles', '7. Section Artikel & Inspirasi', 'newspaper', 'Badge, judul, subjudul, tombol baca, dan filter kategori artikel.'],
                    ['haramain', '8. Section Kabar Haramain', 'video', 'Badge, judul, subjudul, link live stream YouTube (Makkah/Madinah), dan estimasi kepadatan.'],
                    ['partnership', '9. Section Kemitraan (Mitra Syiar)', 'handshake', 'Badge, judul, subjudul, 2 tier kemitraan, reward prestasi, dan CTA WhatsApp.'],
                ],
                'Kontak & Informasi Penutup' => [
                    ['contact_social', 'Kontak & Media Sosial', 'phone', 'WhatsApp, telepon, email, alamat kantor, Google Maps, dan tautan media sosial.'],
                    ['faq', 'Section Tanya Jawab (FAQ)', 'help-circle', 'Judul section dan tautan bantuan FAQ.'],
                    ['footer', 'Section Footer (Penutup)', 'panel-bottom', 'Judul kontak footer, nomor izin PPIU resmi, dan hak cipta website.'],
                ],
            ];
        @endphp

        <!-- Layout 2 Kolom: Sidebar Navigasi (Kiri) & Form Pengaturan (Kanan) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Sidebar Navigasi Kiri (Desktop) -->
            <div id="settingsSidebar" x-show="!q.length" class="lg:col-span-4 space-y-4 lg:sticky lg:top-24">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-4 shadow-sm space-y-4">
                    <p class="text-xs font-black uppercase tracking-wider text-slate-400 px-1 flex items-center gap-1.5">
                        <i data-lucide="layers" class="w-4 h-4 text-blue-500"></i> Menu Pengaturan
                    </p>

                    @foreach ($settingTabs as $groupName => $tabsInGroup)
                        <div class="space-y-1 pt-2.5 border-t border-slate-100 dark:border-slate-700/60 first:border-0 first:pt-0">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 px-2 mb-1">{{ $groupName }}</p>
                            @foreach ($tabsInGroup as [$tid, $tlabel, $ticon, $tdesc])
                                <button type="button" @click="tab = '{{ $tid }}'"
                                    :class="tab === '{{ $tid }}' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20 font-bold' : 'bg-transparent text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700/60 font-semibold'"
                                    class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition-all duration-150 text-left">
                                    <span class="flex items-center gap-2.5">
                                        <span :class="tab === '{{ $tid }}' ? 'text-white' : 'text-slate-400'" class="shrink-0 flex items-center justify-center transition-colors">
                                            <i data-lucide="{{ $ticon }}" class="w-4 h-4"></i>
                                        </span>
                                        <span>{{ $tlabel }}</span>
                                    </span>
                                    <span :class="tab === '{{ $tid }}' ? 'text-white translate-x-0.5' : 'text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100'" class="transition-all duration-150 shrink-0 flex items-center justify-center">
                                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <!-- Penjelasan singkat tab yang sedang aktif -->
                <div class="bg-blue-50/80 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/40 rounded-2xl p-4 text-xs text-slate-600 dark:text-slate-300 shadow-sm">
                    <div class="flex items-center gap-1.5 font-bold text-blue-700 dark:text-blue-300 mb-1">
                        <i data-lucide="info" class="w-4 h-4 shrink-0"></i>
                        <span>Info Tab Aktif</span>
                    </div>
                    @foreach ($settingTabs as $tabsInGroup)
                        @foreach ($tabsInGroup as [$tid, $tlabel, $ticon, $tdesc])
                            <p x-show="tab === '{{ $tid }}'" x-cloak class="leading-relaxed"><b class="text-slate-900 dark:text-white">{{ $tlabel }}:</b> {{ $tdesc }}</p>
                        @endforeach
                    @endforeach
                </div>
            </div>

            <!-- Area Form Utama (Kanan) -->
            <div :class="q.length ? 'lg:col-span-12' : 'lg:col-span-8'">
                <!-- Pesan saat pencarian tidak menemukan apa pun -->
                <div id="settingsNoResults" style="display:none" class="mb-6 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-6 text-center">
                    <i data-lucide="search-x" class="w-6 h-6 text-slate-400 mx-auto mb-2"></i>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Tidak ada pengaturan yang cocok dengan pencarian Anda.</p>
                </div>

                <form id="settingsForm" method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf

            <!-- Section 1: Identitas Website -->
            <div x-show="q.length ? true : tab === 'identity'" class="space-y-6">
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                            <i data-lucide="globe" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Identitas & SEO Website</h3>
                    </div>

                    <div class="form-group">
                        <label for="site_name">Nama Website / Perusahaan</label>
                        <input type="text" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}" placeholder="Contoh: IZI Travel" maxlength="100">
                        <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-1 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>Tampil di: Nama tab browser, hak cipta Footer bawah, dan teks logo Header jika logo gambar kosong.</p>
                        @error('site_name') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="seo_google_analytics_id">Google Analytics ID (GA4)</label>
                            <input type="text" id="seo_google_analytics_id" name="seo_google_analytics_id" x-model="gaId" placeholder="Contoh: G-XXXXXXXXXX" maxlength="50">
                            <p class="text-[10px] text-slate-400 mt-1">Masukkan ID Pengukuran Google Analytics 4 Anda untuk melacak statistik pengunjung.</p>
                            
                            <!-- Real-time GA4 ID format validation -->
                            <div class="mt-1.5 text-xs font-semibold" x-show="gaId && gaId.length > 0" x-cloak>
                                <template x-if="/^G-[A-Z0-9]+$/i.test(gaId)">
                                    <span class="text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                                        Format ID valid.
                                    </span>
                                </template>
                                <template x-if="!/^G-[A-Z0-9]+$/i.test(gaId)">
                                    <span class="text-amber-500 flex items-center gap-1.5">
                                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                                        Format tidak cocok. Biasanya diawali 'G-' diikuti huruf & angka.
                                    </span>
                                </template>
                            </div>
                            @error('seo_google_analytics_id') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label for="seo_meta_keywords">Kata Kunci Meta (Keywords)</label>
                            <input type="text" id="seo_meta_keywords" name="seo_meta_keywords" value="{{ old('seo_meta_keywords', $settings['seo_meta_keywords'] ?? '') }}" placeholder="Contoh: umrah makassar, travel umrah resmi, paket umrah murah" maxlength="500">
                            <p class="text-[10px] text-slate-400 mt-1">Gunakan koma (,) sebagai pemisah antar kata kunci.</p>
                            @error('seo_meta_keywords') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Logo Upload -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Logo Website</label>
                            <div class="flex items-center gap-4">
                                <div class="w-24 h-16 rounded-xl border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 flex items-center justify-center overflow-hidden p-2">
                                    @if(isset($settings['site_logo']))
                                        <img id="logo-preview" src="{{ str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="max-w-full max-h-full object-contain">
                                    @else
                                        <img id="logo-preview" class="hidden max-w-full max-h-full object-contain">
                                        <span id="logo-placeholder" class="text-xs text-slate-400">No Logo</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="site_logo" name="site_logo" class="hidden" accept="image/*" onchange="previewImage(this, 'logo-preview', 'logo-placeholder')">
                                    <label for="site_logo" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-xs font-bold text-slate-700 dark:text-slate-300 rounded-xl cursor-pointer transition">
                                        Pilih File Logo
                                    </label>
                                    <p class="text-[10px] text-slate-400 mt-1">Format: PNG, JPG, WEBP. Maks 2MB.</p>
                                    <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-1 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>Tampil di: Pojok kiri atas Header menu utama dan bagian Footer bawah.</p>
                                </div>
                            </div>
                        </div>
 
                        <!-- Favicon Upload -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Favicon</label>
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-xl border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 flex items-center justify-center overflow-hidden p-3">
                                    @if(isset($settings['site_favicon']))
                                        <img id="favicon-preview" src="{{ str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" class="max-w-full max-h-full object-contain">
                                    @else
                                        <img id="favicon-preview" class="hidden max-w-full max-h-full object-contain">
                                        <span id="favicon-placeholder" class="text-xs text-slate-400">No Fav</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="site_favicon" name="site_favicon" class="hidden" accept="image/*" onchange="previewImage(this, 'favicon-preview', 'favicon-placeholder')">
                                    <label for="site_favicon" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-xs font-bold text-slate-700 dark:text-slate-300 rounded-xl cursor-pointer transition">
                                        Pilih File Favicon
                                    </label>
                                    <p class="text-[10px] text-slate-400 mt-1">Format: ICO, PNG. Maks 1MB.</p>
                                    <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-1 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>Tampil di: Ikon kecil di tab browser Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Section: SEO Advanced & Webmaster -->
            <div x-show="q.length ? true : tab === 'seo_advanced'" class="space-y-6" x-cloak>
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Verifikasi Situs Webmaster</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="seo_google_console_verification">Google Search Console Verification Tag</label>
                            <input type="text" id="seo_google_console_verification" name="seo_google_console_verification" x-model="gscVerification" placeholder="Contoh: google-site-verification-code" maxlength="255">
                            <p class="text-[10px] text-slate-400 mt-1">Masukkan kode verifikasi unik Google Anda (isi dari atribut <code>content</code> di meta tag verifikasi HTML).</p>
                            
                            <!-- Real-time HTML meta tag check -->
                            <div class="mt-1.5 text-xs font-semibold" x-show="gscVerification && gscVerification.length > 0" x-cloak>
                                <template x-if="gscVerification && (gscVerification.includes('<meta') || gscVerification.includes('content='))">
                                    <span class="text-amber-500 flex items-center gap-1.5">
                                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                                        Peringatan: Masukkan *hanya* kode verifikasinya saja, bukan keseluruhan tag HTML <code>&lt;meta...&gt;</code>.
                                    </span>
                                </template>
                            </div>
                            @error('seo_google_console_verification') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label for="seo_bing_verification">Bing Webmaster Verification Tag</label>
                            <input type="text" id="seo_bing_verification" name="seo_bing_verification" x-model="bingVerification" placeholder="Contoh: bing-verification-code" maxlength="255">
                            <p class="text-[10px] text-slate-400 mt-1">Masukkan kode verifikasi unik Bing Webmaster Tools Anda.</p>
                            
                            <!-- Real-time HTML meta tag check -->
                            <div class="mt-1.5 text-xs font-semibold" x-show="bingVerification && bingVerification.length > 0" x-cloak>
                                <template x-if="bingVerification && (bingVerification.includes('<meta') || bingVerification.includes('content='))">
                                    <span class="text-amber-500 flex items-center gap-1.5">
                                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                                        Peringatan: Masukkan *hanya* kode verifikasinya saja, bukan keseluruhan tag HTML.
                                    </span>
                                </template>
                            </div>
                            @error('seo_bing_verification') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 flex items-center justify-center shrink-0">
                            <i data-lucide="share-2" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Metadata Open Graph (Social Sharing)</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="seo_author">Meta Author</label>
                            <input type="text" id="seo_author" name="seo_author" value="{{ old('seo_author', $settings['seo_author'] ?? '') }}" placeholder="Contoh: IZI Travel Team" maxlength="100">
                            @error('seo_author') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label for="seo_canonical_url">Canonical URL (Base URL)</label>
                            <input type="text" id="seo_canonical_url" name="seo_canonical_url" value="{{ old('seo_canonical_url', $settings['seo_canonical_url'] ?? '') }}" placeholder="Contoh: https://izitravel.com" maxlength="255">
                            <p class="text-[10px] text-slate-400 mt-1">Direkomendasikan untuk menghindari isu konten duplikat.</p>
                            @error('seo_canonical_url') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2 border-t border-slate-100 dark:border-slate-700/50">
                        <div class="space-y-4">
                            <div class="form-group">
                                <label for="seo_og_title">Kustom Judul Share (OG Title)</label>
                                <input type="text" id="seo_og_title" name="seo_og_title" value="{{ old('seo_og_title', $settings['seo_og_title'] ?? '') }}" placeholder="Contoh: IZI Travel - Layanan Perjalanan Umrah Premium" maxlength="150">
                                <p class="text-[10px] text-slate-400 mt-1">Judul kustom saat link halaman utama website dibagikan.</p>
                                @error('seo_og_title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                            </div>

                             <div class="form-group">
                                 <label for="seo_og_description" class="flex items-center justify-between">
                                     <span>Kustom Deskripsi Share (OG Description)</span>
                                     <span class="text-[11px] font-bold text-slate-400"
                                           :class="ogDesc && ogDesc.length >= 120 && ogDesc.length <= 160 ? 'text-emerald-500' : (ogDesc && ogDesc.length > 160 ? 'text-rose-500' : 'text-amber-500')">
                                         <span x-text="ogDesc ? ogDesc.length : 0"></span>/160 karakter
                                     </span>
                                 </label>
                                 <textarea id="seo_og_description" name="seo_og_description" rows="3" x-model="ogDesc" placeholder="Deskripsi kustom saat link halaman utama dibagikan..." maxlength="500">{{ old('seo_og_description', $settings['seo_og_description'] ?? '') }}</textarea>
                                 
                                 <!-- Real-time OG character count validation indicator -->
                                 <div class="mt-1.5 flex items-center gap-1.5 text-xs font-semibold" x-show="ogDesc && ogDesc.length > 0" x-cloak>
                                     <template x-if="ogDesc && ogDesc.length > 0 && ogDesc.length < 120">
                                         <span class="text-amber-500 flex items-center gap-1">
                                             <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                                             Terlalu pendek (idealnya 120–160 karakter).
                                         </span>
                                     </template>
                                     <template x-if="ogDesc && ogDesc.length > 160">
                                         <span class="text-rose-500 flex items-center gap-1">
                                             <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                                             Terlalu panjang (idealnya 120–160 karakter).
                                         </span>
                                     </template>
                                     <template x-if="ogDesc && ogDesc.length >= 120 && ogDesc.length <= 160">
                                         <span class="text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                             <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                                             Panjang optimal untuk dibagikan!
                                         </span>
                                     </template>
                                 </div>
                                 @error('seo_og_description') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                             </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-750 dark:text-slate-350">Gambar Preview Sosial Media (OG Image)</label>
                            <div class="flex items-center gap-4">
                                <div class="w-32 h-24 rounded-xl border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 flex items-center justify-center overflow-hidden p-1 shrink-0">
                                    @if(isset($settings['seo_og_image']) && !empty($settings['seo_og_image']))
                                        <img id="og-image-preview" src="{{ str_starts_with($settings['seo_og_image'], 'images/') ? asset($settings['seo_og_image']) : asset('storage/' . $settings['seo_og_image']) }}" alt="OG Image" class="max-w-full max-h-full object-cover rounded-lg">
                                    @else
                                        <img id="og-image-preview" class="hidden max-w-full max-h-full object-cover rounded-lg">
                                        <span id="og-image-placeholder" class="text-xs text-slate-400">Default Logo</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="seo_og_image" name="seo_og_image" class="hidden" accept="image/*" onchange="previewImage(this, 'og-image-preview', 'og-image-placeholder')">
                                    <label for="seo_og_image" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-xs font-bold text-slate-700 dark:text-slate-300 rounded-xl cursor-pointer transition">
                                        Pilih Gambar OG
                                    </label>
                                    <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, WEBP. Maks 2MB. Dimensi ideal 1200x630 pixel.</p>
                                    @if(isset($settings['seo_og_image']) && !empty($settings['seo_og_image']))
                                        <label class="flex items-center gap-1 mt-1.5 text-[10px] font-semibold text-red-500 cursor-pointer">
                                            <input type="checkbox" name="seo_og_image_remove" value="1" class="rounded border-slate-300 text-red-500 focus:ring-red-400 w-3 h-3">
                                            Hapus Gambar Kustom
                                        </label>
                                    @endif
                                    @error('seo_og_image') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-900/30 text-violet-500 flex items-center justify-center shrink-0">
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pengaturan Sitemap & Robots.txt</h3>
                        </div>
                        
                        <div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="seo_sitemap_enabled" value="1" class="sr-only peer" {{ ($settings['seo_sitemap_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ml-3 text-xs font-bold text-slate-700 dark:text-slate-300">Aktifkan Sitemap XML</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="seo_robots_txt">Isi Berkas Robots.txt</label>
                        <textarea id="seo_robots_txt" name="seo_robots_txt" rows="5" class="font-mono text-xs" placeholder="User-agent: *&#10;Disallow: /admin&#10;Allow: /">{{ old('seo_robots_txt', $settings['seo_robots_txt'] ?? '') }}</textarea>
                        <p class="text-[10px] text-slate-400 mt-1">Mengontrol bagian website mana saja yang boleh dirayapi mesin pencari. Biarkan kosong untuk menggunakan konfigurasi bawaan yang aman.</p>
                        @error('seo_robots_txt') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

        <!-- Section: Hero & Banner Utama -->
            <div x-show="q.length ? true : tab === 'hero'" class="space-y-6" x-cloak>
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-500 flex items-center justify-center shrink-0">
                            <i data-lucide="layout" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pengaturan Hero Halaman Utama</h3>
                    </div>

                    <!-- 1. Kaligrafi Arab (Paling Atas) -->
                    <div class="form-group">
                        <label for="hero_calligraphy">Kaligrafi Arab (Bismillah / ayat singkat)</label>
                        <input type="text" id="hero_calligraphy" name="hero_calligraphy" value="{{ old('hero_calligraphy', $settings['hero_calligraphy'] ?? 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ') }}" placeholder="بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ" dir="rtl" maxlength="150" style="font-family:'Amiri','El Messiri',serif; font-size:1.1rem;">
                        <p class="text-[10px] text-slate-400 mt-1">Tampil di atas hero. Kosongkan untuk menyembunyikan.</p>
                        @error('hero_calligraphy') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <!-- 2. Badge Atas (Izin Resmi Kemenag) -->
                    <div class="form-group">
                        <label for="hero_badge">Badge Atas (Teks Kategori/Izin)</label>
                        <input type="text" id="hero_badge" name="hero_badge" value="{{ old('hero_badge', $settings['hero_badge'] ?? '') }}" placeholder="Penyelenggara Umrah & Haji Khusus Resmi" maxlength="150">
                        @error('hero_badge') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <!-- 3. Slogan / Tagline Hero -->
                    <div class="form-group">
                        <label for="site_tagline">Slogan / Tagline Hero</label>
                        <input type="text" id="site_tagline" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline'] ?? 'Perjalanan Umrah Nyaman & Penuh Makna') }}" placeholder="Contoh: Perjalanan Umrah Nyaman & Penuh Makna" maxlength="150">
                        <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-1 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>Tampil di: Judul utama berukuran besar di banner Beranda (Hero).</p>
                        @error('site_tagline') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <!-- 4. Deskripsi Singkat Hero -->
                    <div class="form-group">
                        <label for="site_description">Deskripsi Singkat Hero</label>
                        <textarea id="site_description" name="site_description" rows="3" placeholder="Penyelenggara perjalanan ibadah Umrah dan Haji Premium dengan layanan bintang 5..." maxlength="300">{{ old('site_description', $settings['site_description'] ?? 'Penyelenggara perjalanan ibadah Umrah dan Haji Premium dengan layanan bintang 5 di Ring 1 pelataran Masjidil Haram & Nabawi, serta bimbingan ibadah yang mutawatir sesuai sunnah.') }}</textarea>
                        <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-1 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>Tampil di: Teks deskripsi paragraf di bawah judul utama banner Beranda (Hero).</p>
                        @error('site_description') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <!-- 5. 3 Floating Badges (Rating, Akomodasi, Tiket) -->
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">3 Floating Badges (Rating, Akomodasi, Penerbangan)</label>
                        <p class="text-xs text-slate-500 dark:text-slate-400 -mt-2">Kosongkan teks (dan hapus gambar) pada sebuah badge untuk menyembunyikannya dari halaman depan.</p>
                        @php
                            $heroBadges = [
                                1 => ['Floating Badge 1 (Rating/Pelayanan)', 'Standar Pelayanan Bintang 5'],
                                2 => ['Floating Badge 2 (Hotel/Akomodasi)', 'Hotel Eksklusif Pelataran Masjid'],
                                3 => ['Floating Badge 3 (Tiket/Penerbangan)', 'Jaminan Tiket PP & Visa Resmi'],
                            ];
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-stretch">
                            @foreach ($heroBadges as $b => [$bLabel, $bPlaceholder])
                                @php $bImg = $settings['hero_badge_'.$b.'_image'] ?? null; @endphp
                                <div class="flex flex-col h-full rounded-xl border border-slate-100 dark:border-slate-700 bg-slate-50/40 dark:bg-slate-900/30 p-3">
                                    <div class="form-group !mb-0">
                                        <label for="hero_badge_{{ $b }}">{{ $bLabel }}</label>
                                        <input type="text" id="hero_badge_{{ $b }}" name="hero_badge_{{ $b }}" value="{{ old('hero_badge_'.$b, $settings['hero_badge_'.$b] ?? $bPlaceholder) }}" placeholder="{{ $bPlaceholder }}" maxlength="150">
                                        @error('hero_badge_'.$b) <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="mt-auto pt-3 flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 flex items-center justify-center overflow-hidden p-1.5 shrink-0">
                                            @if(!empty($bImg))
                                                <img id="hero-badge-{{ $b }}-preview" src="{{ str_starts_with($bImg, 'images/') ? asset($bImg) : asset('storage/' . $bImg) }}" alt="" class="max-w-full max-h-full object-contain">
                                            @else
                                                <img id="hero-badge-{{ $b }}-preview" class="hidden max-w-full max-h-full object-contain">
                                                <span id="hero-badge-{{ $b }}-placeholder" class="text-[8px] text-slate-400 text-center leading-none">Ikon</span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <input type="file" id="hero_badge_{{ $b }}_image" name="hero_badge_{{ $b }}_image" class="hidden" accept="image/*" onchange="previewImage(this, 'hero-badge-{{ $b }}-preview', 'hero-badge-{{ $b }}-placeholder')">
                                            <label for="hero_badge_{{ $b }}_image" class="inline-flex w-full items-center justify-center px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-[11px] font-bold text-slate-700 dark:text-slate-300 rounded-lg cursor-pointer transition">
                                                Gambar (opsional)
                                            </label>
                                            @if(!empty($bImg))
                                                <label class="flex items-center gap-1 mt-1.5 text-[10px] font-semibold text-red-500 cursor-pointer">
                                                    <input type="checkbox" name="hero_badge_{{ $b }}_image_remove" value="1" class="rounded border-slate-300 text-red-500 focus:ring-red-400 w-3 h-3">
                                                    Hapus (pakai ikon)
                                                </label>
                                            @endif
                                            @error('hero_badge_'.$b.'_image') <p class="mt-1 text-[11px] text-red-500 font-semibold">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- 6. Widget Banner Statistik Hero (Kanan) -->
                    <div class="space-y-4 border-t border-slate-150 dark:border-slate-700 pt-5">
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">Widget Banner Statistik Hero (Departed Stat Badge)</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Tampil pada banner widget di sisi kanan Hero Halaman Depan.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-group">
                                <label for="hero_stat_title">Judul Statistik Hero</label>
                                <input type="text" id="hero_stat_title" name="hero_stat_title" value="{{ old('hero_stat_title', $settings['hero_stat_title'] ?? 'Total Keberangkatan') }}" placeholder="Total Keberangkatan" maxlength="100">
                                @error('hero_stat_title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label for="hero_stat_subtitle">Subjudul Statistik Hero</label>
                                <input type="text" id="hero_stat_subtitle" name="hero_stat_subtitle" value="{{ old('hero_stat_subtitle', $settings['hero_stat_subtitle'] ?? 'Jamaah terberangkatkan') }}" placeholder="Jamaah terberangkatkan" maxlength="100">
                                @error('hero_stat_subtitle') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label for="hero_stat_value">Angka / Teks Statistik Hero</label>
                                <input type="text" id="hero_stat_value" name="hero_stat_value" value="{{ old('hero_stat_value', $settings['hero_stat_value'] ?? '10K+') }}" placeholder="10K+" maxlength="50">
                                @error('hero_stat_value') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 7. 4 Kartu Keunggulan / Trust Indicators (Bawah Hero) -->
                    <div class="space-y-4 border-t border-slate-150 dark:border-slate-700 pt-5">
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">4 Kartu Keunggulan / Trust Indicators (Bawah Hero)</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Tampil pada 4 kotak kaca di bawah teks utama Hero Beranda.</p>
                        </div>
                        @php
                            $trustCards = [
                                1 => ['Kartu 1 (Kemenag)', 'Berizin Resmi', 'Kemenag RI', 'shield-check'],
                                2 => ['Kartu 2 (Tim)', 'Tim Profesional', 'Berpengalaman', 'users'],
                                3 => ['Kartu 3 (Fasilitas)', 'Fasilitas Lengkap', 'Hotel & Transportasi', 'building-2'],
                                4 => ['Kartu 4 (Pelayanan)', 'Pelayanan Prima', 'Kenyamanan Anda', 'heart'],
                            ];
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($trustCards as $tc => [$tcLabel, $tcDefaultTitle, $tcDefaultSub, $tcDefaultIcon])
                                <div class="rounded-xl border border-slate-150 dark:border-slate-700/70 bg-slate-50/50 dark:bg-slate-900/30 p-3.5 space-y-3">
                                    <p class="text-xs font-extrabold text-blue-600 dark:text-blue-400">{{ $tcLabel }}</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <div class="form-group sm:col-span-1">
                                            <label for="trust_card_{{ $tc }}_title">Judul Utama</label>
                                            <input type="text" id="trust_card_{{ $tc }}_title" name="trust_card_{{ $tc }}_title" value="{{ old('trust_card_'.$tc.'_title', $settings['trust_card_'.$tc.'_title'] ?? $tcDefaultTitle) }}" placeholder="{{ $tcDefaultTitle }}" maxlength="100">
                                            @error('trust_card_'.$tc.'_title') <p class="mt-1 text-[11px] text-red-500 font-semibold">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="form-group sm:col-span-1">
                                            <label for="trust_card_{{ $tc }}_subtitle">Subjudul</label>
                                            <input type="text" id="trust_card_{{ $tc }}_subtitle" name="trust_card_{{ $tc }}_subtitle" value="{{ old('trust_card_'.$tc.'_subtitle', $settings['trust_card_'.$tc.'_subtitle'] ?? $tcDefaultSub) }}" placeholder="{{ $tcDefaultSub }}" maxlength="100">
                                            @error('trust_card_'.$tc.'_subtitle') <p class="mt-1 text-[11px] text-red-500 font-semibold">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="form-group sm:col-span-1">
                                            <label class="block text-xs font-bold text-slate-750 dark:text-slate-300 mb-1" for="trust_card_{{ $tc }}_icon">Ikon Lucide</label>
                                            <x-icon-picker 
                                                id="trust_card_{{ $tc }}_icon" 
                                                name="trust_card_{{ $tc }}_icon" 
                                                :value="old('trust_card_'.$tc.'_icon', $settings['trust_card_'.$tc.'_icon'] ?? $tcDefaultIcon)" 
                                                :placeholder="$tcDefaultIcon" />
                                            @error('trust_card_'.$tc.'_icon') <p class="mt-1 text-[11px] text-red-500 font-semibold">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- 8. Trust Badges (Rating / Sertifikasi) -->
                    <div class="space-y-4 border-t border-slate-150 dark:border-slate-700 pt-5">
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">Trust Badges (Rating / Sertifikasi)</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Teks penunjuk kepercayaan di atas atau bagian hero.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-group">
                                <label for="trust_icon_1">Trust Badge 1</label>
                                <input type="text" id="trust_icon_1" name="trust_icon_1" value="{{ old('trust_icon_1', $settings['trust_icon_1'] ?? 'Izin Resmi Kemenag (PPIU)') }}" placeholder="Izin Resmi Kemenag (PPIU)" maxlength="100">
                                @error('trust_icon_1') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label for="trust_icon_2">Trust Badge 2</label>
                                <input type="text" id="trust_icon_2" name="trust_icon_2" value="{{ old('trust_icon_2', $settings['trust_icon_2'] ?? 'Pasti Berangkat') }}" placeholder="Pasti Berangkat" maxlength="100">
                                @error('trust_icon_2') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label for="trust_icon_3">Trust Badge 3</label>
                                <input type="text" id="trust_icon_3" name="trust_icon_3" value="{{ old('trust_icon_3', $settings['trust_icon_3'] ?? 'Layanan Premium Bintang 5') }}" placeholder="Layanan Premium Bintang 5" maxlength="100">
                                @error('trust_icon_3') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 9. Gambar Background Hero -->
                    <div class="space-y-2 border-t border-slate-150 dark:border-slate-700 pt-5">
                        <label class="block text-xs font-bold text-slate-750 dark:text-slate-300">Gambar Latar Belakang Hero Utama</label>
                        <div class="flex items-center gap-4">
                            <div class="w-32 h-20 rounded-xl border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 flex items-center justify-center overflow-hidden p-1 shrink-0">
                                @if(isset($settings['hero_image']))
                                    <img id="hero-preview" src="{{ str_starts_with($settings['hero_image'], 'images/') ? asset($settings['hero_image']) : asset('storage/' . $settings['hero_image']) }}" alt="Hero Image" class="max-w-full max-h-full object-cover rounded-lg">
                                @else
                                    <img id="hero-preview" class="hidden max-w-full max-h-full object-cover rounded-lg">
                                    <span id="hero-placeholder" class="text-xs text-slate-400">No Image</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" id="hero_image" name="hero_image" class="hidden" accept="image/*" onchange="previewImage(this, 'hero-preview', 'hero-placeholder')">
                                <label for="hero_image" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-xs font-bold text-slate-700 dark:text-slate-300 rounded-xl cursor-pointer transition">
                                    Pilih File Gambar Hero
                                </label>
                                <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, WEBP. Maks 4MB. Rekomendasi rasio landscape.</p>
                                @error('hero_image') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Tentang Kami -->
            <div x-show="q.length ? true : tab === 'about'" class="space-y-6" x-cloak>
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center shrink-0">
                            <i data-lucide="info" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Tentang Perusahaan</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label for="about_badge">Badge Section</label>
                            <input type="text" id="about_badge" name="about_badge" value="{{ old('about_badge', $settings['about_badge'] ?? 'Tentang Kami') }}" placeholder="Tentang Kami" maxlength="100">
                            @error('about_badge') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="about_vision_label">Label Visi</label>
                            <input type="text" id="about_vision_label" name="about_vision_label" value="{{ old('about_vision_label', $settings['about_vision_label'] ?? 'Visi Kami') }}" placeholder="Visi Kami" maxlength="100">
                            @error('about_vision_label') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="about_mission_label">Label Misi</label>
                            <input type="text" id="about_mission_label" name="about_mission_label" value="{{ old('about_mission_label', $settings['about_mission_label'] ?? 'Misi Kami') }}" placeholder="Misi Kami" maxlength="100">
                            @error('about_mission_label') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="about_title">Judul Utama</label>
                        <input type="text" id="about_title" name="about_title" value="{{ old('about_title', $settings['about_title'] ?? '') }}" placeholder="Contoh: Melayani Perjalanan Suci Anda dengan Sepenuh Hati" maxlength="200">
                        <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-1 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>Tampil di: Judul besar bagian section "Tentang Kami" (di bawah banner Beranda).</p>
                        @error('about_title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
 
                    <div class="form-group">
                        <label for="about_description">Deskripsi</label>
                        <textarea id="about_description" name="about_description" rows="4" placeholder="Ceritakan sejarah singkat dan keunggulan perusahaan...">{{ old('about_description', $settings['about_description'] ?? '') }}</textarea>
                        <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-1 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>Tampil di: Paragraf profil penjelasan perusahaan di section "Tentang Kami".</p>
                        @error('about_description') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
 
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="about_satisfaction_rate">Tingkat Kepuasan (%)</label>
                            <input type="number" id="about_satisfaction_rate" name="about_satisfaction_rate" value="{{ old('about_satisfaction_rate', $settings['about_satisfaction_rate'] ?? '') }}" placeholder="Contoh: 99" min="0" max="100">
                            <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-1 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>Tampil di: Angka persentase kepuasan di kotak statistik section "Tentang Kami".</p>
                            @error('about_satisfaction_rate') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
 
                        <div class="form-group">
                            <label for="about_departed_count">Jumlah Jamaah Berangkat (Ribuan+)</label>
                            <input type="number" id="about_departed_count" name="about_departed_count" value="{{ old('about_departed_count', $settings['about_departed_count'] ?? '') }}" placeholder="Contoh: 10" min="0">
                            <p class="text-[10px] text-amber-600 dark:text-amber-400 font-bold mt-1 flex items-start gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0 mt-0.5"></i>Tampil di: Angka utama di widget "Total Keberangkatan" (misal: 10K+) di samping banner Hero Beranda, dan kotak statistik "Tentang Kami".</p>
                            @error('about_departed_count') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>
 
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="about_stat_1_label">Label Statistik 1</label>
                            <input type="text" id="about_stat_1_label" name="about_stat_1_label" value="{{ old('about_stat_1_label', $settings['about_stat_1_label'] ?? '') }}" placeholder="Contoh: Kepuasan Jamaah" maxlength="100">
                            @error('about_stat_1_label') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
 
                        <div class="form-group">
                            <label for="about_stat_2_label">Label Statistik 2</label>
                            <input type="text" id="about_stat_2_label" name="about_stat_2_label" value="{{ old('about_stat_2_label', $settings['about_stat_2_label'] ?? '') }}" placeholder="Contoh: Jamaah Berangkat" maxlength="100">
                            @error('about_stat_2_label') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>
 
                    <div class="form-group">
                        <label for="about_vision">Visi Perusahaan</label>
                        <textarea id="about_vision" name="about_vision" rows="3" placeholder="Contoh: Menjadi penyelenggara perjalanan ibadah Umrah dan Haji premium terbaik di Indonesia...">{{ old('about_vision', $settings['about_vision'] ?? '') }}</textarea>
                        <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-1 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>Tampil di: Bento Card Visi di bagian bawah deskripsi section "Tentang Kami".</p>
                        @error('about_vision') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
 
                    <div class="form-group">
                        <label for="about_mission">Misi Perusahaan</label>
                        <textarea id="about_mission" name="about_mission" rows="3" placeholder="Contoh: Menyediakan pelayanan prima melalui kepastian program, bimbingan syar'i terarah...">{{ old('about_mission', $settings['about_mission'] ?? '') }}</textarea>
                        <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-1 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>Tampil di: Bento Card Misi di samping Bento Card Visi section "Tentang Kami".</p>
                        @error('about_mission') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Gambar Kolase Tentang Kami -->
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center shrink-0">
                            <i data-lucide="images" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Gambar Kolase (di samping teks)</h3>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 -mt-2">Dua gambar yang tampil bertumpuk di samping bagian Tentang Kami. Kosongkan untuk memakai gambar bawaan.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-slate-100 dark:border-slate-700 pb-6">
                        <div class="form-group">
                            <label for="about_ppiu_label">Label Badge Izin (di atas kolase)</label>
                            <input type="text" id="about_ppiu_label" name="about_ppiu_label" value="{{ old('about_ppiu_label', $settings['about_ppiu_label'] ?? '') }}" placeholder="Contoh: Izin PPIU Resmi" maxlength="100">
                            @error('about_ppiu_label') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="footer_ppiu_number">Nomor Izin PPIU</label>
                            <input type="text" id="footer_ppiu_number" name="footer_ppiu_number" value="{{ old('footer_ppiu_number', $settings['footer_ppiu_number'] ?? '') }}" placeholder="Contoh: A10BS81" maxlength="150">
                            <p class="text-[10px] text-slate-400 mt-1">Tampil di badge Tentang Kami & di footer.</p>
                            @error('footer_ppiu_number') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    @php
                        $aboutImages = [
                            'about_image_1' => ['Gambar Belakang (besar)', 'images/gallery_departure.webp', 'Gambar latar belakang berukuran besar pada kolase foto Tentang Kami.'],
                            'about_image_2' => ['Gambar Depan (kecil)', 'images/gallery_manasik.webp', 'Gambar overlay di depan/atas bertumpuk pada kolase foto Tentang Kami.'],
                        ];
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($aboutImages as $imgKey => [$imgLabel, $imgDefault, $imgDesc])
                            @php $imgVal = $settings[$imgKey] ?? null; @endphp
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">{{ $imgLabel }}</label>
                                <div class="flex items-center gap-4">
                                    <div class="w-24 h-20 rounded-xl border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 flex items-center justify-center overflow-hidden p-1 shrink-0">
                                        <img id="{{ $imgKey }}-preview" src="{{ !empty($imgVal) ? (str_starts_with($imgVal, 'images/') ? asset($imgVal) : asset('storage/' . $imgVal)) : asset($imgDefault) }}" alt="{{ $imgLabel }}" class="max-w-full max-h-full object-cover rounded-lg">
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" id="{{ $imgKey }}" name="{{ $imgKey }}" class="hidden" accept="image/*" onchange="previewImage(this, '{{ $imgKey }}-preview')">
                                        <label for="{{ $imgKey }}" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-xs font-bold text-slate-700 dark:text-slate-300 rounded-xl cursor-pointer transition">
                                            Pilih Gambar
                                        </label>
                                        <p class="text-[10px] text-slate-400 mt-1">JPG/PNG/WEBP, maks 4MB.</p>
                                        <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-1 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>Tampil di: {{ $imgDesc }}</p>
                                        @if(!empty($imgVal))
                                            <label class="inline-flex items-center gap-1.5 mt-2 text-[11px] font-semibold text-red-500 cursor-pointer">
                                                <input type="checkbox" name="{{ $imgKey }}_remove" value="1" class="rounded border-slate-300 text-red-500 focus:ring-red-400">
                                                Hapus (pakai gambar bawaan)
                                            </label>
                                        @endif
                                        @error($imgKey) <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Section Tim (Sub-bagian Tentang Kami) -->
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                            <i data-lucide="users" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pengaturan Section Tim Profesional</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="team_section_title">Judul Section Tim</label>
                            <input type="text" id="team_section_title" name="team_section_title" value="{{ old('team_section_title', $settings['team_section_title'] ?? 'Tim Profesional Kami') }}" placeholder="Tim Profesional Kami" maxlength="150">
                            @error('team_section_title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="team_other_section_label">Label Tim Pendukung & Pembimbing</label>
                            <input type="text" id="team_other_section_label" name="team_other_section_label" value="{{ old('team_other_section_label', $settings['team_other_section_label'] ?? 'Tim Pendukung & Pembimbing') }}" placeholder="Tim Pendukung & Pembimbing" maxlength="150">
                            @error('team_other_section_label') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="team_section_subtitle">Subjudul Section Tim</label>
                        <textarea id="team_section_subtitle" name="team_section_subtitle" rows="2" placeholder="Tuliskan subjudul deskripsi..." maxlength="255">{{ old('team_section_subtitle', $settings['team_section_subtitle'] ?? 'Didukung oleh tim profesional berpengalaman yang mendedikasikan diri sepenuhnya untuk melayani tamu-tamu Allah SWT.') }}</textarea>
                        @error('team_section_subtitle') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 8: Keunggulan Kami (Kenapa Kami) -->
            <div x-show="q.length ? true : tab === 'features'" class="space-y-6" x-cloak>
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-500 flex items-center justify-center shrink-0">
                            <i data-lucide="award" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Keunggulan Layanan (Kenapa Kami)</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label class="block text-xs font-bold text-slate-750 dark:text-slate-300" for="features_badge">Label Badge Section</label>
                            <input type="text" id="features_badge" name="features_badge" value="{{ old('features_badge', $settings['features_badge'] ?? '') }}" placeholder="Contoh: Kenapa Kami" class="w-full px-4 py-2.5 text-sm rounded-xl" maxlength="100">
                            @error('features_badge') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group md:col-span-2">
                            <label class="block text-xs font-bold text-slate-750 dark:text-slate-300" for="features_section_title">Judul Section Utama</label>
                            <input type="text" id="features_section_title" name="features_section_title" value="{{ old('features_section_title', $settings['features_section_title'] ?? '') }}" placeholder="Contoh: Keunggulan Layanan Kami" class="w-full px-4 py-2.5 text-sm rounded-xl" maxlength="150">
                            @error('features_section_title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="block text-xs font-bold text-slate-750 dark:text-slate-300" for="features_section_subtitle">Deskripsi Section</label>
                        <input type="text" id="features_section_subtitle" name="features_section_subtitle" value="{{ old('features_section_subtitle', $settings['features_section_subtitle'] ?? '') }}" placeholder="Contoh: Mitra tepercaya perjalanan ibadah Anda dengan standar pelayanan tinggi dan kekeluargaan." class="w-full px-4 py-2.5 text-sm rounded-xl" maxlength="255">
                        @error('features_section_subtitle') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div class="h-px bg-slate-100 dark:bg-slate-700 my-6"></div>

                    <p class="text-xs text-slate-500 dark:text-slate-400 -mt-2">Nama ikon mengikuti <a href="https://lucide.dev/icons/" target="_blank" class="text-blue-500 underline">Lucide Icons</a> (contoh: <code>award</code>, <code>file-check</code>, <code>building-2</code>).</p>
                    @php
                        $featuresPlaceholder = [
                            1 => ['Legalitas Resmi Kemenag', 'Memiliki izin PPIU resmi dari Kementerian Agama RI untuk kepastian keamanan hukum perjalanan Anda.', 'award'],
                            2 => ['Jaminan Visa Umrah', 'Proses penerbitan visa yang aman, transparan, and terkonfirmasi langsung ke sistem kedutaan.', 'file-check'],
                            3 => ['Hotel Dekat Pelataran', 'Akomodasi hotel bintang pilihan dengan jarak yang dekat memudahkan Anda beribadah di Masjidil Haram & Nabawi.', 'building-2'],
                            4 => ['Muthawwif Khas Nusantara', 'Muthawwif & pembimbing ibadah bersertifikasi, membimbing sesuai sunnah dengan keramahan khas Indonesia.', 'compass'],
                            5 => ['Layanan Siaga & Peduli', 'Customer support dan tim handling operasional siaga melayani Anda 24 jam dengan asas kekeluargaan.', 'phone-call'],
                            6 => ['Kepastian Tiket Terbang', 'Kepastian tanggal keberangkatan dengan tiket pesawat premium (PP) yang telah issued sejak pendaftaran.', 'plane-takeoff'],
                        ];
                    @endphp

                    @for ($f = 1; $f <= 6; $f++)
                        <div class="border-b border-slate-100 dark:border-slate-700 pb-6 last:border-0 last:pb-0">
                            <h4 class="text-xs font-black uppercase text-slate-400 mb-4">Fitur / Keunggulan {{ $f }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="form-group">
                                    <label class="block text-xs font-bold text-slate-750 dark:text-slate-300 mb-1" for="feature_{{ $f }}_icon">Nama Ikon</label>
                                    <x-icon-picker 
                                        id="feature_{{ $f }}_icon" 
                                        name="feature_{{ $f }}_icon" 
                                        :value="old('feature_'.$f.'_icon', $settings['feature_'.$f.'_icon'] ?? '')" 
                                        :placeholder="$featuresPlaceholder[$f][2]" />
                                    @error('feature_'.$f.'_icon') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="block text-xs font-bold text-slate-750 dark:text-slate-300" for="feature_{{ $f }}_title">Judul Keunggulan</label>
                                    <input type="text" id="feature_{{ $f }}_title" name="feature_{{ $f }}_title"
                                        value="{{ old('feature_'.$f.'_title', $settings['feature_'.$f.'_title'] ?? '') }}"
                                        placeholder="{{ $featuresPlaceholder[$f][0] }}" class="w-full px-4 py-2.5 text-sm rounded-xl" maxlength="100">
                                    @error('feature_'.$f.'_title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-750 dark:text-slate-300" for="feature_{{ $f }}_desc">Deskripsi Keunggulan</label>
                                    <input type="text" id="feature_{{ $f }}_desc" name="feature_{{ $f }}_desc"
                                        value="{{ old('feature_'.$f.'_desc', $settings['feature_'.$f.'_desc'] ?? '') }}"
                                        placeholder="{{ $featuresPlaceholder[$f][1] }}" class="w-full px-4 py-2.5 text-sm rounded-xl" maxlength="255">
                                    @error('feature_'.$f.'_desc') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- Gambar (menggantikan ikon bila diisi) -->
                            @php $fImg = $settings['feature_'.$f.'_image'] ?? null; @endphp
                            <div class="mt-4 flex items-center gap-4">
                                <div class="w-14 h-14 rounded-xl border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 flex items-center justify-center overflow-hidden p-2 shrink-0">
                                    @if(!empty($fImg))
                                        <img id="feature-{{ $f }}-preview" src="{{ str_starts_with($fImg, 'images/') ? asset($fImg) : asset('storage/' . $fImg) }}" alt="Gambar fitur {{ $f }}" class="max-w-full max-h-full object-contain">
                                    @else
                                        <img id="feature-{{ $f }}-preview" class="hidden max-w-full max-h-full object-contain">
                                        <span id="feature-{{ $f }}-placeholder" class="text-[9px] text-slate-400 text-center leading-tight">Pakai Ikon</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="feature_{{ $f }}_image" name="feature_{{ $f }}_image" class="hidden" accept="image/*" onchange="previewImage(this, 'feature-{{ $f }}-preview', 'feature-{{ $f }}-placeholder')">
                                    <label for="feature_{{ $f }}_image" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-xs font-bold text-slate-700 dark:text-slate-300 rounded-xl cursor-pointer transition">
                                        Pilih Gambar (opsional)
                                    </label>
                                    <p class="text-[10px] text-slate-400 mt-1">PNG/JPG/WEBP, maks 2MB. Jika diisi, gambar menggantikan ikon. Kosongkan untuk tetap pakai ikon.</p>
                                    @if(!empty($fImg))
                                        <label class="inline-flex items-center gap-1.5 mt-2 text-[11px] font-semibold text-red-500 cursor-pointer">
                                            <input type="checkbox" name="feature_{{ $f }}_image_remove" value="1" class="rounded border-slate-300 text-red-500 focus:ring-red-400">
                                            Hapus gambar (kembali ke ikon)
                                        </label>
                                    @endif
                                    @error('feature_'.$f.'_image') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Section 5: Alur Pendaftaran -->
            <div x-show="q.length ? true : tab === 'flow'" class="space-y-6" x-cloak>
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-900/30 text-violet-500 flex items-center justify-center shrink-0">
                            <i data-lucide="help-circle" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Alur Pendaftaran Mudah</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-slate-100 dark:border-slate-700 pb-4">
                        <div class="form-group">
                            <label for="registration_title">Judul Section</label>
                            <input type="text" id="registration_title" name="registration_title" value="{{ old('registration_title', $settings['registration_title'] ?? 'Alur Pendaftaran Mudah') }}" placeholder="Alur Pendaftaran Mudah" maxlength="150">
                            @error('registration_title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="registration_subtitle">Subjudul Section</label>
                            <input type="text" id="registration_subtitle" name="registration_subtitle" value="{{ old('registration_subtitle', $settings['registration_subtitle'] ?? 'Hanya beberapa langkah menuju Tanah Suci...') }}" placeholder="Hanya beberapa langkah menuju Tanah Suci..." maxlength="255">
                            @error('registration_subtitle') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <p class="text-xs text-slate-500 dark:text-slate-400 -mt-2">Nama ikon mengikuti <a href="https://lucide.dev/icons/" target="_blank" class="text-blue-500 underline">Lucide Icons</a> (contoh: <code>compass</code>, <code>credit-card</code>, <code>plane-takeoff</code>).</p>
                    @php
                        $stepPlaceholders = [
                            1 => ['Contoh: Pilih Paket', 'Contoh: Pilih paket yang sesuai dengan tanggal dan keinginan Anda.', 'message-square'],
                            2 => ['Contoh: Konsultasi', 'Contoh: Hubungi customer service kami untuk detail keberangkatan.', 'compass'],
                            3 => ['Contoh: Kirim Berkas', 'Contoh: Lengkapi dokumen paspor, foto, dan syarat administrasi.', 'credit-card'],
                            4 => ['Contoh: Uang Muka (DP)', 'Contoh: Lakukan deposit untuk mengamankan kursi penerbangan Anda.', 'file-text'],
                            5 => ['Contoh: Manasik', 'Contoh: Ikuti bimbingan manasik teori & praktek sesuai sunnah.', 'book-open'],
                            6 => ['Contoh: Berangkat', 'Contoh: Pelepasan di bandara dan mulai perjalanan ibadah Anda.', 'plane-takeoff'],
                        ];
                    @endphp
                    @for ($i = 1; $i <= 6; $i++)
                        <div class="border-b border-slate-100 dark:border-slate-700 pb-6 last:border-0 last:pb-0">
                            <h4 class="text-xs font-black uppercase text-slate-400 mb-4">Langkah {{ $i }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="form-group">
                                    <label class="block text-xs font-bold text-slate-750 dark:text-slate-300 mb-1" for="registration_step_{{ $i }}_icon">Nama Ikon</label>
                                    <x-icon-picker 
                                        id="registration_step_{{ $i }}_icon" 
                                        name="registration_step_{{ $i }}_icon" 
                                        :value="old('registration_step_'.$i.'_icon', $settings['registration_step_'.$i.'_icon'] ?? '')" 
                                        :placeholder="$stepPlaceholders[$i][2]" />
                                    @error('registration_step_'.$i.'_icon') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="registration_step_{{ $i }}_title">Judul Langkah</label>
                                    <input type="text" id="registration_step_{{ $i }}_title" name="registration_step_{{ $i }}_title"
                                        value="{{ old('registration_step_'.$i.'_title', $settings['registration_step_'.$i.'_title'] ?? '') }}"
                                        placeholder="{{ $stepPlaceholders[$i][0] }}" maxlength="100">
                                    @error('registration_step_'.$i.'_title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group md:col-span-2">
                                    <label for="registration_step_{{ $i }}_description">Deskripsi Langkah</label>
                                    <input type="text" id="registration_step_{{ $i }}_description" name="registration_step_{{ $i }}_description"
                                        value="{{ old('registration_step_'.$i.'_description', $settings['registration_step_'.$i.'_description'] ?? '') }}"
                                        placeholder="{{ $stepPlaceholders[$i][1] }}" maxlength="255">
                                    @error('registration_step_'.$i.'_description') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Section 6: Paket Umrah -->
            <div x-show="q.length ? true : tab === 'packages'" class="space-y-6" x-cloak>
                <!-- Card 1: Judul & Tombol Section -->
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                            <i data-lucide="compass" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pengaturan Teks & Judul Section Paket</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="packages_label">Label Badge Section</label>
                            <input type="text" id="packages_label" name="packages_label" value="{{ old('packages_label', $settings['packages_label'] ?? 'Paket Pilihan') }}" placeholder="Paket Pilihan" maxlength="100">
                            @error('packages_label') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="packages_section_title">Judul Section</label>
                            <input type="text" id="packages_section_title" name="packages_section_title" value="{{ old('packages_section_title', $settings['packages_section_title'] ?? 'Paket Umrah Kami') }}" placeholder="Paket Umrah Kami" maxlength="150">
                            @error('packages_section_title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="packages_section_subtitle">Subjudul Section</label>
                        <textarea id="packages_section_subtitle" name="packages_section_subtitle" rows="2" placeholder="Tuliskan subjudul deskripsi..." maxlength="255">{{ old('packages_section_subtitle', $settings['packages_section_subtitle'] ?? 'Pilihan paket perjalanan terbaik dengan fasilitas hotel premium Ring 1 demi kenyamanan ibadah Anda.') }}</textarea>
                        @error('packages_section_subtitle') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="packages_price_label">Label Harga (Mulai dari / dll)</label>
                            <input type="text" id="packages_price_label" name="packages_price_label" value="{{ old('packages_price_label', $settings['packages_price_label'] ?? 'Mulai dari') }}" placeholder="Mulai dari" maxlength="100">
                            @error('packages_price_label') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="packages_detail_btn">Teks Tombol Detail</label>
                            <input type="text" id="packages_detail_btn" name="packages_detail_btn" value="{{ old('packages_detail_btn', $settings['packages_detail_btn'] ?? 'Lihat Detail') }}" placeholder="Lihat Detail" maxlength="100">
                            @error('packages_detail_btn') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Informasi Penting Detail Paket -->
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-500 flex items-center justify-center shrink-0">
                            <i data-lucide="sticky-note" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Informasi Penting Halaman Detail Paket</h3>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 -mt-2">Teks ini ditampilkan di bagian "Informasi Penting" pada setiap halaman detail paket.</p>

                    <div class="form-group">
                        <label for="package_note_1">Catatan 1 (Info Harga)</label>
                        <textarea id="package_note_1" name="package_note_1" rows="2" maxlength="500"
                            placeholder="Contoh: Harga dapat berubah sewaktu-waktu mengikuti kurs & ketersediaan kuota...">{{ old('package_note_1', $settings['package_note_1'] ?? '') }}</textarea>
                        @error('package_note_1') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="package_note_2">Catatan 2 (Info Pendaftaran)</label>
                        <textarea id="package_note_2" name="package_note_2" rows="2" maxlength="500"
                            placeholder="Contoh: Pendaftaran ditutup paling lambat 3 minggu sebelum keberangkatan...">{{ old('package_note_2', $settings['package_note_2'] ?? '') }}</textarea>
                        @error('package_note_2') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="package_note_3">Catatan 3 (Info Pembayaran)</label>
                        <textarea id="package_note_3" name="package_note_3" rows="2" maxlength="500"
                            placeholder="Contoh: Tersedia skema cicilan hingga 12x tanpa bunga...">{{ old('package_note_3', $settings['package_note_3'] ?? '') }}</textarea>
                        @error('package_note_3') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Card 3: Didukung & Dipercaya Oleh (Maskapai & Hotel) -->
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 flex items-center justify-center shrink-0">
                            <i data-lucide="handshake" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Section Partner Maskapai & Hotel</h3>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 -mt-2">Judul section logo maskapai penerbangan dan keterangan akomodasi hotel di bagian bawah daftar paket.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="partners_section_title">Judul Section Partner</label>
                            <input type="text" id="partners_section_title" name="partners_section_title" value="{{ old('partners_section_title', $settings['partners_section_title'] ?? 'Mitra Maskapai Penerbangan') }}" placeholder="Mitra Maskapai Penerbangan" maxlength="150">
                            @error('partners_section_title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="partners_extra">Keterangan Akomodasi Tambahan</label>
                            <input type="text" id="partners_extra" name="partners_extra" value="{{ old('partners_extra', $settings['partners_extra'] ?? '+ Akomodasi Bintang 5') }}" placeholder="+ Akomodasi Bintang 5" maxlength="150">
                            @error('partners_extra') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 7: Galeri & Testimoni -->
            <div x-show="q.length ? true : tab === 'gallery_testimonials'" class="space-y-6" x-cloak>
                <!-- Card 1: Judul Section Galeri -->
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                            <i data-lucide="image" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Section Galeri Kegiatan & Dokumentasi</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="gallery_label">Label Badge Section</label>
                            <input type="text" id="gallery_label" name="gallery_label" value="{{ old('gallery_label', $settings['gallery_label'] ?? 'Galeri') }}" placeholder="Galeri" maxlength="100">
                            @error('gallery_label') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="gallery_section_title">Judul Section</label>
                            <input type="text" id="gallery_section_title" name="gallery_section_title" value="{{ old('gallery_section_title', $settings['gallery_section_title'] ?? 'Galeri Kegiatan & Dokumentasi') }}" placeholder="Galeri Kegiatan & Dokumentasi" maxlength="150">
                            @error('gallery_section_title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="gallery_section_subtitle">Subjudul Section</label>
                        <textarea id="gallery_section_subtitle" name="gallery_section_subtitle" rows="2" placeholder="Tuliskan subjudul deskripsi..." maxlength="255">{{ old('gallery_section_subtitle', $settings['gallery_section_subtitle'] ?? 'Momen perjalanan ibadah jamaah kami yang penuh berkah dan kenyamanan.') }}</textarea>
                        @error('gallery_section_subtitle') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Card 2: Judul Section Testimoni -->
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center shrink-0">
                            <i data-lucide="message-square" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Section Testimoni Jamaah</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="testimonials_label">Label Badge Section</label>
                            <input type="text" id="testimonials_label" name="testimonials_label" value="{{ old('testimonials_label', $settings['testimonials_label'] ?? 'Testimoni') }}" placeholder="Testimoni" maxlength="100">
                            @error('testimonials_label') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="testimonials_section_title">Judul Section</label>
                            <input type="text" id="testimonials_section_title" name="testimonials_section_title" value="{{ old('testimonials_section_title', $settings['testimonials_section_title'] ?? 'Apa Kata Jamaah Kami') }}" placeholder="Apa Kata Jamaah Kami" maxlength="150">
                            @error('testimonials_section_title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="testimonials_section_subtitle">Subjudul Section</label>
                        <textarea id="testimonials_section_subtitle" name="testimonials_section_subtitle" rows="2" placeholder="Tuliskan subjudul deskripsi..." maxlength="255">{{ old('testimonials_section_subtitle', $settings['testimonials_section_subtitle'] ?? 'Kepercayaan jamaah adalah prioritas kami dalam memberikan pelayanan terbaik.') }}</textarea>
                        @error('testimonials_section_subtitle') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 8: Artikel & Inspirasi -->
            <div x-show="q.length ? true : tab === 'articles'" class="space-y-6" x-cloak>
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                            <i data-lucide="newspaper" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Section Artikel & Inspirasi</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="articles_label">Label Badge Section</label>
                            <input type="text" id="articles_label" name="articles_label" value="{{ old('articles_label', $settings['articles_label'] ?? 'Artikel') }}" placeholder="Artikel" maxlength="100">
                            @error('articles_label') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="articles_section_title">Judul Section</label>
                            <input type="text" id="articles_section_title" name="articles_section_title" value="{{ old('articles_section_title', $settings['articles_section_title'] ?? 'Artikel & Informasi Terkini') }}" placeholder="Artikel & Informasi Terkini" maxlength="150">
                            @error('articles_section_title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="articles_section_subtitle">Subjudul Section</label>
                        <textarea id="articles_section_subtitle" name="articles_section_subtitle" rows="2" placeholder="Tuliskan subjudul deskripsi..." maxlength="255">{{ old('articles_section_subtitle', $settings['articles_section_subtitle'] ?? 'Panduan, tips, dan informasi seputar perjalanan haji & umrah Anda.') }}</textarea>
                        @error('articles_section_subtitle') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-100 dark:border-slate-700 pt-4">
                        <div class="form-group">
                            <label for="articles_read_more">Teks "Baca Selengkapnya"</label>
                            <input type="text" id="articles_read_more" name="articles_read_more" value="{{ old('articles_read_more', $settings['articles_read_more'] ?? 'Baca Selengkapnya') }}" placeholder="Baca Selengkapnya" maxlength="100">
                            @error('articles_read_more') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="articles_read_suffix">Sufiks Waktu Baca</label>
                            <input type="text" id="articles_read_suffix" name="articles_read_suffix" value="{{ old('articles_read_suffix', $settings['articles_read_suffix'] ?? 'menit baca') }}" placeholder="menit baca" maxlength="50">
                            @error('articles_read_suffix') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-4 border-t border-slate-100 dark:border-slate-700 pt-4">
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">Teks Filter Kategori</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="form-group">
                                <label for="articles_filter_all">Filter: Semua</label>
                                <input type="text" id="articles_filter_all" name="articles_filter_all" value="{{ old('articles_filter_all', $settings['articles_filter_all'] ?? 'Semua') }}" placeholder="Semua" maxlength="50">
                                @error('articles_filter_all') <p class="mt-1 text-[11px] text-red-500 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label for="articles_filter_haramain">Filter: Haramain</label>
                                <input type="text" id="articles_filter_haramain" name="articles_filter_haramain" value="{{ old('articles_filter_haramain', $settings['articles_filter_haramain'] ?? 'Haramain') }}" placeholder="Haramain" maxlength="50">
                                @error('articles_filter_haramain') <p class="mt-1 text-[11px] text-red-500 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label for="articles_filter_panduan">Filter: Panduan</label>
                                <input type="text" id="articles_filter_panduan" name="articles_filter_panduan" value="{{ old('articles_filter_panduan', $settings['articles_filter_panduan'] ?? 'Panduan') }}" placeholder="Panduan" maxlength="50">
                                @error('articles_filter_panduan') <p class="mt-1 text-[11px] text-red-500 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label for="articles_filter_tips">Filter: Tips</label>
                                <input type="text" id="articles_filter_tips" name="articles_filter_tips" value="{{ old('articles_filter_tips', $settings['articles_filter_tips'] ?? 'Tips') }}" placeholder="Tips" maxlength="50">
                                @error('articles_filter_tips') <p class="mt-1 text-[11px] text-red-500 font-semibold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Kabar Haramain & Live Stream -->
            <div x-show="q.length ? true : tab === 'haramain'" class="space-y-6" x-cloak>
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 flex items-center justify-center shrink-0">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pengaturan Kabar Haramain & Live Stream</h3>
                    </div>

                    <div class="form-group">
                        <label for="haramain_badge">Label Badge Section</label>
                        <input type="text" id="haramain_badge" name="haramain_badge" value="{{ old('haramain_badge', $settings['haramain_badge'] ?? 'Info Haramain Live & Waktu Shalat') }}" placeholder="Contoh: Info Haramain Live & Waktu Shalat" maxlength="150">
                        <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-1 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>📍 Tampil di: Badge kecil berwarna emas di atas judul section Kabar Haramain.</p>
                        @error('haramain_badge') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="haramain_title">Judul Section</label>
                        <input type="text" id="haramain_title" name="haramain_title" value="{{ old('haramain_title', $settings['haramain_title'] ?? 'Kabar Tanah Suci & Jadwal Shalat') }}" placeholder="Contoh: Kabar Tanah Suci & Jadwal Shalat" maxlength="150">
                        <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-1 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>📍 Tampil di: Judul utama section Kabar Haramain.</p>
                        @error('haramain_title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="haramain_subtitle">Subjudul Section</label>
                        <textarea id="haramain_subtitle" name="haramain_subtitle" rows="2" placeholder="Tuliskan subjudul deskripsi..." maxlength="255">{{ old('haramain_subtitle', $settings['haramain_subtitle'] ?? 'Pantau kondisi langsung Masjidil Haram & Masjidil Nabawi, waktu shalat aktual, serta informasi cuaca Makkah secara real-time.') }}</textarea>
                        <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-1 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>📍 Tampil di: Deskripsi subjudul di bawah judul utama section Kabar Haramain.</p>
                        @error('haramain_subtitle') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="haramain_density_base">Estimasi Awal Kepadatan Jamaah Hari Ini</label>
                        <input type="number" id="haramain_density_base" name="haramain_density_base" value="{{ old('haramain_density_base', $settings['haramain_density_base'] ?? '254025') }}" placeholder="Contoh: 254025" min="0">
                        <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-1 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>📍 Tampil di: Widget estimasi jumlah jamaah di Masjidil Haram pada halaman depan (di samping widget jadwal shalat).</p>
                        @error('haramain_density_base') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="haramain_youtube_makkah">YouTube Live Stream Masjidil Haram Makkah (Link / ID / Channel)</label>
                        <input type="text" id="haramain_youtube_makkah" name="haramain_youtube_makkah" value="{{ old('haramain_youtube_makkah', $settings['haramain_youtube_makkah'] ?? 'UCos52azQNBgW63_9uDJoPDA') }}" placeholder="Contoh: https://www.youtube.com/live/... atau https://www.youtube.com/watch?v=..." maxlength="255">
                        <p class="text-[10px] text-slate-400 mt-1">Bisa diisi URL YouTube Live (<code>/live/...</code>), Link Video (<code>/watch?v=...</code>), Video ID, atau Channel ID (KSA Qur'an TV).</p>
                        <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-1 flex items-center gap-1"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>📍 Tampil di: Pemutar Video Siaran Langsung 24/7 Masjidil Haram di halaman depan.</p>
                        @error('haramain_youtube_makkah') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 9: Kemitraan (Mitra Syiar) -->
            <div x-show="q.length ? true : tab === 'partnership'" class="space-y-6" x-cloak>
                @php
                    $partnershipGroups = [
                        ['Section Kemitraan (Partnership) - Judul', 'handshake', [
                            ['partnership_badge', 'Label Badge Section', 'Kemitraan', false],
                            ['partnership_title', 'Judul Section', 'Mari Bergabung Menjadi Mitra Syiar Baitullah', false],
                            ['partnership_subtitle', 'Subjudul Section', 'Menjadi mitra syiar baitullah berkesempatan...', true],
                            ['partnership_reg_label', 'Label Biaya Pendaftaran', 'Biaya Pendaftaran', false],
                        ]],
                        ['Mitra Freelance (Tier 1)', 'user-check', [
                            ['partnership_tier_1_badge', 'Badge Kartu', 'Freelance', false],
                            ['partnership_tier_1_title', 'Nama Paket Kemitraan', 'Mitra Freelance', false],
                            ['partnership_tier_1_price', 'Biaya Pendaftaran', 'FREE', false],
                            ['partnership_tier_1_feature_1', 'Fitur/Keuntungan 1', 'Komisi Menarik per Jemaah', false],
                            ['partnership_tier_1_feature_2', 'Fitur/Keuntungan 2', 'Dukungan brosur digital & marketing kit', false],
                            ['partnership_tier_1_feature_3', 'Fitur/Keuntungan 3', 'Bebas target bulanan & tanpa modal', false],
                            ['partnership_tier_1_feature_4', 'Fitur/Keuntungan 4', 'Waktu kerja fleksibel', false],
                        ]],
                        ['Mitra Agen (Tier 2)', 'briefcase', [
                            ['partnership_tier_2_badge', 'Badge Kartu', 'Agen Resmi', false],
                            ['partnership_tier_2_title', 'Nama Paket Kemitraan', 'Mitra Agen', false],
                            ['partnership_tier_2_price', 'Biaya Pendaftaran', 'Rp 1.000.000', false],
                            ['partnership_tier_2_feature_1', 'Fitur/Keuntungan 1', 'Komisi maksimal & bonus menarik', false],
                            ['partnership_tier_2_feature_2', 'Fitur/Keuntungan 2', 'Starter kit fisik (spanduk & brosur cetak)', false],
                            ['partnership_tier_2_feature_3', 'Fitur/Keuntungan 3', 'Sertifikat keagenan resmi IZI Travel', false],
                            ['partnership_tier_2_feature_4', 'Fitur/Keuntungan 4', 'Pembekalan & prioritas bimbingan produk', false],
                        ]],
                        ['Keuntungan & Reward (Tier 3)', 'gift', [
                            ['partnership_tier_3_title', 'Judul Kartu', 'Keuntungan & Reward', false],
                            ['partnership_tier_3_subtitle', 'Subjudul Kartu', 'Potensi Syiar Kemitraan', false],
                            ['partnership_reward_1_label', 'Label Reward 1', 'Komisi per Jemaah', false],
                            ['partnership_reward_1_value', 'Nilai Reward 1', 'Hingga Rp 2.000.000', false],
                            ['partnership_reward_1_desc', 'Keterangan Reward 1', 'Pendapatan langsung per jemaah yang melakukan pelunasan.', false],
                            ['partnership_reward_2_label', 'Label Reward 2', 'Reward Prestasi', false],
                            ['partnership_reward_2_value', 'Nilai Reward 2', 'Umroh Gratis', false],
                            ['partnership_reward_2_desc', 'Keterangan Reward 2', 'Kesempatan ibadah umrah gratis bagi mitra yang mencapai target syiar.', false],
                        ]],
                        ['Ajakan Kemitraan (CTA)', 'megaphone', [
                            ['partnership_cta_title', 'Judul Ajakan', 'Tertarik Menjadi Mitra IZI Travel?', false],
                            ['partnership_cta_desc', 'Deskripsi Ajakan', 'Daftarkan diri Anda sekarang dan nikmati keuntungannya.', true],
                            ['partnership_cta_button', 'Teks Tombol WA', 'Hubungi WhatsApp Kemitraan', false],
                        ]],
                        ['Ajakan Penutup (CTA Utama)', 'megaphone', [
                            ['cta_title', 'Judul', 'Wujudkan Perjalanan Ibadah Impian Anda', false],
                            ['cta_description', 'Deskripsi', 'Konsultasikan rencana umrah Anda bersama tim kami...', true],
                            ['cta_button', 'Teks Tombol Utama', 'Hubungi Kami', false],
                            ['cta_packages_label', 'Teks Tombol "Lihat Paket"', 'Lihat Paket', false],
                            ['cta_consultation_label', 'Teks Tombol "Konsultasi"', 'Konsultasi Gratis', false],
                        ]],
                    ];
                @endphp
                @foreach ($partnershipGroups as [$groupTitle, $groupIcon, $fields])
                    <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                                <i data-lucide="{{ $groupIcon }}" class="w-4 h-4"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ $groupTitle }}</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($fields as [$fkey, $flabel, $fplaceholder, $flong])
                                <div class="form-group {{ $flong ? 'md:col-span-2' : '' }}">
                                    <label for="{{ $fkey }}">{{ $flabel }}</label>
                                    @if ($flong)
                                        <textarea id="{{ $fkey }}" name="{{ $fkey }}" rows="2" maxlength="500" placeholder="{{ $fplaceholder }}">{{ old($fkey, $settings[$fkey] ?? '') }}</textarea>
                                    @else
                                        <input type="text" id="{{ $fkey }}" name="{{ $fkey }}" value="{{ old($fkey, $settings[$fkey] ?? '') }}" placeholder="{{ $fplaceholder }}" maxlength="255">
                                    @endif
                                    @error($fkey) <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Section: Kontak & Media Sosial -->
            <div x-show="q.length ? true : tab === 'contact_social'" class="space-y-6" x-cloak>
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-500 flex items-center justify-center shrink-0">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Kontak & Operasional</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="contact_whatsapp">Nomor WhatsApp</label>
                            <input type="text" id="contact_whatsapp" name="contact_whatsapp" value="{{ old('contact_whatsapp', $settings['contact_whatsapp'] ?? '') }}" placeholder="Contoh: 6281112345678" maxlength="20">
                            <p class="text-[10px] text-slate-400 mt-1">Mulai dengan kode negara <b>tanpa tanda +</b> dan tanpa angka 0 di depan. Contoh: 0811-1234-5678 ditulis <b>6281112345678</b>.</p>
                            @error('contact_whatsapp') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label for="contact_whatsapp_cofounder">Nomor WhatsApp Co-Founder / Manager</label>
                            <input type="text" id="contact_whatsapp_cofounder" name="contact_whatsapp_cofounder" value="{{ old('contact_whatsapp_cofounder', $settings['contact_whatsapp_cofounder'] ?? '') }}" placeholder="Contoh: 6282112345678" maxlength="20">
                            <p class="text-[10px] text-slate-400 mt-1">Nomor yang langsung terhubung ke Co-Founder/Manager. Format sama, mulai dengan kode negara tanpa +.</p>
                            @error('contact_whatsapp_cofounder') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label for="contact_phone">Nomor Telepon Teks (tampil di website)</label>
                            <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" placeholder="Contoh: +62 811-1234-5678" maxlength="30">
                            @error('contact_phone') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="contact_email">Email Perusahaan</label>
                            <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" placeholder="Contoh: info@izitravel.com" class="w-full px-4 py-2.5 text-sm rounded-xl">
                            @error('contact_email') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label for="office_hours">Jam Kerja / Operasional</label>
                            <input type="text" id="office_hours" name="office_hours" value="{{ old('office_hours', $settings['office_hours'] ?? '') }}" placeholder="Contoh: Senin - Sabtu: 08:00 - 17:00" maxlength="100">
                            @error('office_hours') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact_address">Alamat Fisik</label>
                        <textarea id="contact_address" name="contact_address" rows="2" placeholder="Contoh: Jl. Urip Sumoharjo No. 12, Makassar, Sulawesi Selatan" maxlength="500">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                        @error('contact_address') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="contact_gmaps">Peta Lokasi Kantor (Google Maps)</label>
                        <textarea id="contact_gmaps" name="contact_gmaps" rows="2" class="text-xs font-mono" placeholder="Contoh: https://www.google.com/maps/embed?pb=...">{{ old('contact_gmaps', $settings['contact_gmaps'] ?? '') }}</textarea>
                        <p class="text-[10px] text-slate-400 mt-1">Cara ambil: buka Google Maps → cari lokasi kantor → <b>Bagikan</b> → tab <b>Sematkan peta</b> → salin link yang ada di dalam tanda kutip <code>src="..."</code>.</p>
                        @error('contact_gmaps') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-pink-50 dark:bg-pink-900/30 text-pink-500 flex items-center justify-center shrink-0">
                            <i data-lucide="share-2" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Tautan Media Sosial</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="social_facebook">Facebook URL</label>
                            <input type="url" id="social_facebook" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}" placeholder="https://facebook.com/username">
                            @error('social_facebook') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label for="social_instagram">Instagram URL</label>
                            <input type="url" id="social_instagram" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}" placeholder="https://instagram.com/username">
                            @error('social_instagram') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="social_youtube">YouTube Channel URL</label>
                            <input type="url" id="social_youtube" name="social_youtube" value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}" placeholder="https://youtube.com/c/channelname">
                            @error('social_youtube') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label for="social_tiktok">TikTok URL</label>
                            <input type="url" id="social_tiktok" name="social_tiktok" value="{{ old('social_tiktok', $settings['social_tiktok'] ?? '') }}" placeholder="https://tiktok.com/@username">
                            @error('social_tiktok') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="social_twitter">Twitter / X URL</label>
                        <input type="url" id="social_twitter" name="social_twitter" value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}" placeholder="https://twitter.com/username">
                        @error('social_twitter') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 11: Tanya Jawab (FAQ) -->
            <div x-show="q.length ? true : tab === 'faq'" class="space-y-6" x-cloak>
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                            <i data-lucide="help-circle" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Section Tanya Jawab (FAQ)</h3>
                    </div>
                    <div class="form-group">
                        <label for="faq_section_title">Judul Section FAQ</label>
                        <input type="text" id="faq_section_title" name="faq_section_title" value="{{ old('faq_section_title', $settings['faq_section_title'] ?? 'Tanya Jawab (FAQ)') }}" placeholder="Tanya Jawab (FAQ)" maxlength="150">
                        @error('faq_section_title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 12: Footer (Penutup) -->
            <div x-show="q.length ? true : tab === 'footer'" class="space-y-6" x-cloak>
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                            <i data-lucide="panel-bottom" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Section Footer (Penutup)</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="footer_contact_heading">Judul Kolom Kontak Footer</label>
                            <input type="text" id="footer_contact_heading" name="footer_contact_heading" value="{{ old('footer_contact_heading', $settings['footer_contact_heading'] ?? 'Hubungi Kami') }}" placeholder="Hubungi Kami" maxlength="150">
                            @error('footer_contact_heading') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="footer_ppiu_number">Nomor Izin Resmi PPIU</label>
                            <input type="text" id="footer_ppiu_number" name="footer_ppiu_number" value="{{ old('footer_ppiu_number', $settings['footer_ppiu_number'] ?? '') }}" placeholder="Contoh: A10BS81" maxlength="150">
                            @error('footer_ppiu_number') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Simpan: menempel di bawah layar agar selalu terlihat -->
            <div class="sticky bottom-0 z-20 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-3 bg-white/90 dark:bg-slate-900/90 backdrop-blur border-t border-slate-200 dark:border-slate-700 flex items-center justify-between gap-3">
                <p class="text-xs text-slate-500 dark:text-slate-400 hidden sm:flex items-center gap-1.5">
                    <i data-lucide="info" class="w-3.5 h-3.5"></i>
                    Perubahan di semua tab tersimpan sekaligus saat menekan tombol ini.
                </p>
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-sm text-white transition-all duration-150 shadow-lg shadow-blue-500/20 shrink-0">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    {{ __('Simpan Pengaturan') }}
                </button>
            </div>
        </form>
    </div>
    </div>
    </div>

    @push('scripts')
    <script>
        // Pencarian: tampilkan hanya kartu pengaturan yang cocok dengan kata kunci (di semua tab).
        function filterSettings(qRaw) {
            const q = (qRaw || '').trim().toLowerCase();
            const form = document.getElementById('settingsForm');
            if (!form) return;
            const cards = form.querySelectorAll('.content-card');
            const searching = q.length > 0;
            let anyVisible = false;

            cards.forEach((card) => {
                if (!searching) {
                    card.style.display = '';
                    return;
                }
                const match = card.textContent.toLowerCase().includes(q);
                card.style.display = match ? '' : 'none';
                if (match) anyVisible = true;
            });

            const noRes = document.getElementById('settingsNoResults');
            if (noRes) noRes.style.display = (searching && !anyVisible) ? '' : 'none';

            if (window.lucide) lucide.createIcons();
        }

        function previewImage(input, previewId, placeholderId) {
            const preview = document.getElementById(previewId);
            const placeholder = placeholderId ? document.getElementById(placeholderId) : null;
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form[action*="settings"]') || document.querySelector('form');
            if (!form) return;

            // Feedback "file terpilih + preview + belum disimpan" untuk semua input gambar
            form.querySelectorAll('input[type="file"]').forEach((input) => {
                input.addEventListener('change', () => {
                    const label = form.querySelector('label[for="' + input.id + '"]');
                    let hint = document.getElementById(input.id + '__hint');
                    if (!hint && label && label.parentNode) {
                        hint = document.createElement('div');
                        hint.id = input.id + '__hint';
                        hint.className = 'mt-2';
                        label.parentNode.insertBefore(hint, label.nextSibling);
                    }
                    if (input.files && input.files[0]) {
                        const file = input.files[0];
                        const url = URL.createObjectURL(file);
                        if (hint) {
                            hint.innerHTML =
                                '<div class="inline-flex flex-col gap-1.5 p-2 rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50/60 dark:bg-emerald-900/20">' +
                                    '<img src="' + url + '" alt="Preview" class="rounded-lg max-h-32 max-w-[180px] object-contain bg-white dark:bg-slate-900" onload="URL.revokeObjectURL(this.src)">' +
                                    '<div class="flex items-center gap-1.5 flex-wrap text-[11px] font-semibold">' +
                                        '<span class="text-emerald-700 dark:text-emerald-400 break-all max-w-[180px]">&#10003; ' + file.name + '</span>' +
                                        '<span class="inline-flex items-center px-1.5 py-0.5 rounded-md bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 text-[10px] font-bold uppercase tracking-wide">Belum disimpan</span>' +
                                    '</div>' +
                                '</div>';
                        }
                        if (label) label.classList.add('ring-2', 'ring-emerald-400');
                        // Pilih gambar baru otomatis membatalkan centang "hapus"
                        const rm = form.querySelector('[name="' + input.name + '_remove"]');
                        if (rm) rm.checked = false;
                    } else {
                        if (hint) hint.innerHTML = '';
                        if (label) label.classList.remove('ring-2', 'ring-emerald-400');
                    }
                });
            });

            // Status "Menyimpan..." saat submit supaya jelas sedang diproses
            form.addEventListener('submit', () => {
                const btn = form.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.classList.add('opacity-70', 'cursor-wait');
                    btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Menyimpan...';
                    if (window.lucide) lucide.createIcons();
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>
