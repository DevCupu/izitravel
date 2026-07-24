<x-admin-layout :title="__('Dashboard')">
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
            {{ __('Ringkasan aktivitas platform IZI Travel.') }}
        </p>
    </x-slot>

    <div class="space-y-6" x-data="{
        animateValue(el, target) {
            let current = 0;
            const duration = 1200;
            const step = target / (duration / 16);
            const timer = setInterval(() => {
                current += step;
                if (current >= target) { current = target; clearInterval(timer); }
                el.textContent = Math.floor(current).toLocaleString('id-ID');
            }, 16);
        }
    }">

        <!-- ═══════ Welcome Banner ═══════ -->
        <div class="relative overflow-hidden rounded-2xl px-6 py-7 sm:px-8 sm:py-8 animate-fade-in-up"
             style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 40%, #6366f1 70%, #8b5cf6 100%);">
            <!-- Decorative orbs -->
            <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-white/[0.06] blur-2xl"></div>
            <div class="absolute -bottom-16 -left-16 w-48 h-48 rounded-full bg-white/[0.04] blur-xl"></div>
            <div class="absolute top-1/2 right-1/4 w-32 h-32 rounded-full bg-indigo-400/10 blur-xl"></div>

            <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 bg-white/10 text-white/90 text-[11px] font-bold px-3 py-1 rounded-lg uppercase tracking-wider backdrop-blur-sm">
                        <i data-lucide="sparkles" class="w-3 h-3"></i>
                        Admin Panel
                    </span>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-white mt-3" x-data x-init="
                        const hour = new Date().getHours();
                        const greeting = hour < 12 ? 'Selamat Pagi' : hour < 17 ? 'Selamat Siang' : 'Selamat Malam';
                        $el.textContent = greeting + ', {{ Auth::user()->name }}';
                    ">
                        Selamat Datang, {{ Auth::user()->name }}
                    </h3>
                    <p class="text-blue-100/80 text-sm mt-1.5 max-w-lg">
                        Pantau dan kelola semua konten platform IZI Travel dari satu tempat.
                    </p>
                </div>
                <div class="hidden sm:flex flex-col items-end gap-1 text-right">
                    <p class="text-white/70 text-xs font-medium" x-data x-init="
                        const updateTime = () => {
                            const now = new Date();
                            $el.textContent = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                        };
                        updateTime();
                    "></p>
                    <p class="text-white font-bold text-lg tabular-nums" x-data x-init="
                        const update = () => {
                            const now = new Date();
                            $el.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                        };
                        update(); setInterval(update, 1000);
                    "></p>
                </div>
            </div>
        </div>

        <!-- ═══════ Stat Cards ═══════ -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Packages -->
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 card-hover animate-fade-in-up stagger-1">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Paket Umrah</p>
                        <p class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-white tabular-nums"
                           x-init="animateValue($el, {{ $totalPackages }})">{{ $totalPackages }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                        <i data-lucide="package" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-xs">
                    <span class="text-slate-400 dark:text-slate-500">{{ $activePackages }} paket aktif ditampilkan</span>
                </div>
            </div>

            <!-- Total Galleries -->
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 card-hover animate-fade-in-up stagger-2">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Galeri Media</p>
                        <p class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-white tabular-nums"
                           x-init="animateValue($el, {{ $totalGalleries }})">{{ $totalGalleries }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center shrink-0">
                        <i data-lucide="image" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-xs">
                    <span class="text-slate-400 dark:text-slate-500">{{ $activeGalleries }} media aktif ditampilkan</span>
                </div>
            </div>

            <!-- Total Articles -->
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 card-hover animate-fade-in-up stagger-3">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Artikel & Berita</p>
                        <p class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-white tabular-nums"
                           x-init="animateValue($el, {{ $totalArticles }})">{{ $totalArticles }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-pink-50 dark:bg-pink-900/30 text-pink-500 flex items-center justify-center shrink-0">
                        <i data-lucide="book-open" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-xs">
                    <span class="text-slate-400 dark:text-slate-500">{{ $activeArticles }} artikel aktif dibaca</span>
                </div>
            </div>

            <!-- Total Content -->
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 card-hover animate-fade-in-up stagger-4">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Semua Konten</p>
                        <p class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-white tabular-nums"
                           x-init="animateValue($el, {{ $totalContent }})">{{ $totalContent }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-violet-50 dark:bg-violet-900/30 text-violet-500 flex items-center justify-center shrink-0">
                        <i data-lucide="layers" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-xs">
                    <span class="text-slate-400 dark:text-slate-500">{{ $activePackages + $activeTestimonials + $activeGalleries + $activeFaqs + $activeArticles + $activeTeams + $activePartners }} aktif ditampilkan</span>
                </div>
            </div>
        </div>

        <!-- ═══════ Charts Row ═══════ -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <!-- Departure Schedule Trend Chart -->
            <div class="lg:col-span-2 content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 sm:p-6 animate-fade-in-up stagger-3 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Jadwal Keberangkatan Paket</h3>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Proyeksi keberangkatan umrah 6 bulan mendatang</p>
                    </div>
                    <span class="inline-flex items-center gap-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold px-3 py-1 rounded-lg">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        6 Bulan Kedepan
                    </span>
                </div>
                <div class="relative" style="height: 240px;">
                    <canvas id="departureTrendChart"></canvas>
                </div>
            </div>

            <!-- Content Distribution Doughnut Chart -->
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 sm:p-6 animate-fade-in-up stagger-4 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Proporsi Konten</h3>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Distribusi berdasarkan modul</p>
                </div>
                
                <div class="relative mx-auto my-2" style="height: 170px; width: 170px;">
                    <canvas id="contentDistChart"></canvas>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-2 text-xs pt-3 border-t border-slate-100 dark:border-slate-700/60">
                    @foreach($contentDistribution as $item)
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full shrink-0" style="background: {{ $item['color'] }}"></span>
                            <span class="text-slate-600 dark:text-slate-400 truncate text-[11px]">{{ $item['label'] }}</span>
                            <span class="ml-auto font-extrabold text-slate-900 dark:text-white text-[11px] tabular-nums">{{ $item['count'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- ═══════ SEO & Website Health Center ═══════ -->
        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 animate-fade-in-up stagger-4 shadow-sm" x-data="{ showTab: 'critical' }">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left: Circular Progress Ring & Diagnostics Summary -->
                <div class="flex flex-col items-center justify-center text-center lg:border-r border-slate-100 dark:border-slate-700/50 pb-6 lg:pb-0 lg:pr-8">
                    <h3 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Kesehatan SEO & E-E-A-T</h3>
                    
                    <!-- SVG Radial Ring -->
                    <div class="relative w-36 h-36 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90">
                            <!-- Background track -->
                            <circle cx="72" cy="72" r="60" stroke-width="10" stroke="currentColor" class="text-slate-100 dark:text-slate-700" fill="transparent"/>
                            <!-- Indicator circle -->
                            <circle cx="72" cy="72" r="60" stroke-width="10" 
                                    stroke="{{ $seoScore >= 80 ? '#10b981' : ($seoScore >= 50 ? '#f59e0b' : '#ef4444') }}" 
                                    stroke-dasharray="377" 
                                    stroke-dashoffset="{{ 377 - (377 * $seoScore / 100) }}" 
                                    stroke-linecap="round" 
                                    class="transition-all duration-1000 ease-out" 
                                    fill="transparent"/>
                        </svg>
                        <div class="absolute flex flex-col items-center justify-center">
                            <span class="text-3xl font-extrabold text-slate-900 dark:text-white tabular-nums">{{ $seoScore }}%</span>
                            <span class="text-[10px] font-bold mt-0.5 uppercase tracking-wider
                                {{ $seoScore >= 80 ? 'text-emerald-500' : ($seoScore >= 50 ? 'text-amber-500' : 'text-red-500') }}">
                                {{ $seoScore >= 80 ? 'Optimal' : ($seoScore >= 50 ? 'Perlu Perbaikan' : 'Kritis') }}
                            </span>
                        </div>
                    </div>
                    
                    <p class="text-xs text-slate-550 dark:text-slate-400 mt-4 leading-relaxed max-w-xs">
                        Skor berdasarkan analisis metadata, gambar, kredibilitas izin PPIU, dan kelengkapan konten website Anda.
                    </p>
                </div>

                <!-- Middle: Category Breakdown (Score details) -->
                <div class="flex flex-col justify-center space-y-4 lg:border-r border-slate-100 dark:border-slate-700/50 pb-6 lg:pb-0 lg:pr-8">
                    <h3 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Rincian Penilaian</h3>
                    
                    <div class="space-y-3">
                        @foreach ($seoBreakdown as $key => $data)
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs font-bold">
                                    <span class="text-slate-750 dark:text-slate-350">{{ $data['label'] }}</span>
                                    <span class="text-slate-500 dark:text-slate-400 tabular-nums">{{ $data['score'] }}/{{ $data['max'] }} pts</span>
                                </div>
                                <div class="w-full bg-slate-100 dark:bg-slate-700/80 rounded-full h-2 overflow-hidden shadow-inner">
                                    <div class="{{ $data['color'] }} h-2 rounded-full transition-all duration-1000" style="width: {{ ($data['score'] / $data['max']) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right: SEO Checklist Tasks -->
                <div class="flex flex-col justify-between">
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                            <h3 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Rekomendasi Tindakan</h3>
                            
                            @php
                                $pass = collect($seoChecklist)->where('status', 'pass')->count();
                                $warn = collect($seoChecklist)->where('status', 'warning')->count();
                                $fail = collect($seoChecklist)->where('status', 'fail')->count();
                                $criticalCount = $warn + $fail;
                            @endphp
                            
                            <div class="flex gap-1.5 shrink-0">
                                <button type="button" @click="showTab = 'critical'" 
                                        :class="showTab === 'critical' ? 'bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 border-red-200 dark:border-red-800' : 'bg-slate-50 dark:bg-slate-900/30 text-slate-400 border-slate-100 dark:border-slate-800'"
                                        class="flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold rounded-lg border transition">
                                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                                    Perlu Perbaikan ({{ $criticalCount }})
                                </button>
                                <button type="button" @click="showTab = 'optimal'"
                                        :class="showTab === 'optimal' ? 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' : 'bg-slate-50 dark:bg-slate-900/30 text-slate-400 border-slate-100 dark:border-slate-800'"
                                        class="flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold rounded-lg border transition">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                    Optimal ({{ $pass }})
                                </button>
                            </div>
                        </div>
                        
                        <!-- Scrollable Checklist -->
                        <div class="space-y-2 max-h-56 overflow-y-auto pr-1">
                            <!-- Critical (Failed & Warning) Items -->
                            <template x-if="showTab === 'critical'">
                                <div class="space-y-2">
                                    @php $hasCritical = false; @endphp
                                    @foreach ($seoChecklist as $item)
                                        @if ($item['status'] !== 'pass')
                                            @php $hasCritical = true; @endphp
                                            <div class="flex items-start justify-between gap-4 p-2.5 rounded-xl border {{ $item['status'] === 'fail' ? 'border-red-100 dark:border-red-950/30 bg-red-50/20 dark:bg-red-950/10' : 'border-amber-100 dark:border-amber-950/30 bg-amber-50/20 dark:bg-amber-950/10' }} transition">
                                                <div class="flex items-start gap-2.5">
                                                    <span class="shrink-0 mt-0.5">
                                                        @if ($item['status'] === 'fail')
                                                            <i data-lucide="x-circle" class="w-4 h-4 text-red-500"></i>
                                                        @else
                                                            <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-500"></i>
                                                        @endif
                                                    </span>
                                                    <p class="text-xs font-semibold leading-relaxed text-slate-700 dark:text-slate-300">
                                                        {{ $item['label'] }}
                                                    </p>
                                                </div>
                                                @if (isset($item['fix']))
                                                    <a href="{{ $item['fix'] }}#seo_advanced" class="shrink-0 inline-flex items-center gap-0.5 text-[10px] font-black uppercase text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                                                        Perbaiki
                                                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                    @if (!$hasCritical)
                                        <div class="p-6 text-center text-slate-400 dark:text-slate-500">
                                            <i data-lucide="party-popper" class="w-8 h-8 mx-auto mb-2 text-emerald-500"></i>
                                            <p class="text-xs font-semibold">Luar biasa! Tidak ada masalah SEO kritis yang terdeteksi.</p>
                                        </div>
                                    @endif
                                </div>
                            </template>

                            <!-- Passed/Optimal Items -->
                            <template x-if="showTab === 'optimal'">
                                <div class="space-y-2">
                                    @php $hasPass = false; @endphp
                                    @foreach ($seoChecklist as $item)
                                        @if ($item['status'] === 'pass')
                                            @php $hasPass = true; @endphp
                                            <div class="flex items-start gap-2.5 p-2.5 rounded-xl border border-slate-100/50 dark:border-slate-700/30 bg-slate-50/50 dark:bg-slate-900/30">
                                                <span class="shrink-0 mt-0.5">
                                                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500"></i>
                                                </span>
                                                <p class="text-xs font-semibold leading-relaxed text-slate-600 dark:text-slate-400">
                                                    {{ $item['label'] }}
                                                </p>
                                            </div>
                                        @endif
                                    @endforeach
                                    @if (!$hasPass)
                                        <div class="p-6 text-center text-slate-400 dark:text-slate-500">
                                            <p class="text-xs">Belum ada parameter yang optimal.</p>
                                        </div>
                                    @endif
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/50 flex flex-wrap gap-x-6 gap-y-2 items-center text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider">
                        <span class="flex items-center gap-1"><i data-lucide="globe" class="w-3.5 h-3.5 text-blue-500"></i> Dasar</span>
                        <span class="flex items-center gap-1"><i data-lucide="shield" class="w-3.5 h-3.5 text-emerald-500"></i> E-E-A-T</span>
                        <span class="flex items-center gap-1"><i data-lucide="sliders" class="w-3.5 h-3.5 text-indigo-500"></i> Lanjutan</span>
                        <span class="flex items-center gap-1"><i data-lucide="package" class="w-3.5 h-3.5 text-amber-500"></i> Paket</span>
                        <span class="flex items-center gap-1"><i data-lucide="book-open" class="w-3.5 h-3.5 text-pink-500"></i> Artikel</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- ═══════ Quick Actions + Content Stats ═══════ -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            <!-- Quick Actions -->
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 animate-fade-in-up stagger-4">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('admin.packages.create') }}"
                       class="group flex flex-col items-center gap-2.5 p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-blue-200 dark:hover:border-blue-800 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i data-lucide="package" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 text-center">Tambah Paket</span>
                    </a>
                    <a href="{{ route('admin.testimonials.create') }}"
                       class="group flex flex-col items-center gap-2.5 p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-amber-200 dark:hover:border-amber-800 hover:bg-amber-50/50 dark:hover:bg-amber-900/10 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i data-lucide="message-square-quote" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 text-center">Tambah Testimoni</span>
                    </a>
                    <a href="{{ route('admin.galleries.create') }}"
                       class="group flex flex-col items-center gap-2.5 p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-emerald-200 dark:hover:border-emerald-800 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i data-lucide="image" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 text-center">Tambah Galeri</span>
                    </a>
                    <a href="{{ route('admin.faqs.create') }}"
                       class="group flex flex-col items-center gap-2.5 p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-violet-200 dark:hover:border-violet-800 hover:bg-violet-50/50 dark:hover:bg-violet-900/10 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl bg-violet-50 dark:bg-violet-900/30 text-violet-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i data-lucide="help-circle" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 text-center">Tambah FAQ</span>
                    </a>
                </div>
            </div>

            <!-- Content Overview Cards -->
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 animate-fade-in-up stagger-5">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4">Ringkasan Konten</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <a href="{{ route('admin.packages.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                            <i data-lucide="package" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-white">Paket Umrah</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $activePackages }} aktif / {{ $totalPackages }} total</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-base font-extrabold text-slate-900 dark:text-white">{{ $totalPackages }}</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-blue-500 transition"></i>
                        </div>
                    </a>
                    <a href="{{ route('admin.testimonials.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-500 flex items-center justify-center shrink-0">
                            <i data-lucide="message-square-quote" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-white">Testimoni</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $activeTestimonials }} aktif / {{ $totalTestimonials }} total</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-base font-extrabold text-slate-900 dark:text-white">{{ $totalTestimonials }}</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-amber-500 transition"></i>
                        </div>
                    </a>
                    <a href="{{ route('admin.galleries.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center shrink-0">
                            <i data-lucide="image" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-white">Galeri</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $activeGalleries }} aktif / {{ $totalGalleries }} total</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-base font-extrabold text-slate-900 dark:text-white">{{ $totalGalleries }}</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-emerald-500 transition"></i>
                        </div>
                    </a>
                    <a href="{{ route('admin.faqs.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-violet-50 dark:bg-violet-900/30 text-violet-500 flex items-center justify-center shrink-0">
                            <i data-lucide="help-circle" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-white">FAQ</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $activeFaqs }} aktif / {{ $totalFaqs }} total</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-base font-extrabold text-slate-900 dark:text-white">{{ $totalFaqs }}</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-violet-500 transition"></i>
                        </div>
                    </a>
                    <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-pink-50 dark:bg-pink-900/30 text-pink-500 flex items-center justify-center shrink-0">
                            <i data-lucide="book-open" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-white">Artikel</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $activeArticles }} aktif / {{ $totalArticles }} total</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-base font-extrabold text-slate-900 dark:text-white">{{ $totalArticles }}</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-pink-500 transition"></i>
                        </div>
                    </a>
                    <a href="{{ route('admin.teams.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 flex items-center justify-center shrink-0">
                            <i data-lucide="users" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-white">Tim Kami</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $activeTeams }} aktif / {{ $totalTeams }} total</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-base font-extrabold text-slate-900 dark:text-white">{{ $totalTeams }}</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-indigo-500 transition"></i>
                        </div>
                    </a>
                    <a href="{{ route('admin.partners.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-teal-50 dark:bg-teal-900/30 text-teal-500 flex items-center justify-center shrink-0">
                            <i data-lucide="plane-takeoff" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-white">Mitra Maskapai</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $activePartners }} aktif / {{ $totalPartners }} total</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-base font-extrabold text-slate-900 dark:text-white">{{ $totalPartners }}</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-teal-500 transition"></i>
                        </div>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center shrink-0">
                            <i data-lucide="settings" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-white">Pengaturan Web</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Kontak, Alur, &amp; Tentang</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-slate-800 dark:group-hover:text-white transition"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- ═══════ Upcoming Departures ═══════ -->
        @if($upcomingDepartures->isNotEmpty())
        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden animate-fade-in-up stagger-5">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-700">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Keberangkatan Mendatang</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Paket umrah terdekat</p>
                </div>
                <a href="{{ route('admin.packages.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 transition inline-flex items-center gap-1">
                    Lihat Semua
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 dark:divide-slate-700">
                @foreach($upcomingDepartures as $dep)
                    @php
                        $daysUntil = now()->startOfDay()->diffInDays($dep->departure_date->startOfDay(), false);
                    @endphp
                    <div class="p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md
                                {{ $daysUntil <= 7 ? 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400' : ($daysUntil <= 30 ? 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400') }}">
                                <i data-lucide="calendar" class="w-3 h-3"></i>
                                {{ $daysUntil <= 0 ? 'Hari ini' : $daysUntil . ' hari lagi' }}
                            </span>
                        </div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $dep->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            {{ $dep->departure_date->translatedFormat('d M Y') }} &middot; {{ $dep->airline }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif


    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#94a3b8' : '#64748b';
        const gridColor = isDark ? 'rgba(148,163,184,0.08)' : 'rgba(0,0,0,0.04)';

        // 1. Departure Schedule Trend Chart
        const depCtx = document.getElementById('departureTrendChart');
        if (depCtx) {
            const ctx = depCtx.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 240);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.35)');
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            new Chart(depCtx, {
                type: 'line',
                data: {
                    labels: @json($departureTrend->pluck('short_label')),
                    datasets: [{
                        label: 'Paket Berangkat',
                        data: @json($departureTrend->pluck('count')),
                        borderColor: '#3b82f6',
                        borderWidth: 3,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.38,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#0f172a' : '#ffffff',
                            titleColor: isDark ? '#f8fafc' : '#0f172a',
                            bodyColor: isDark ? '#94a3b8' : '#475569',
                            borderColor: isDark ? '#334155' : '#e2e8f0',
                            borderWidth: 1,
                            cornerRadius: 10,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: ctx => ' ' + ctx.parsed.y + ' Paket Terjadwal'
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { size: 12, weight: 600 } }
                        },
                        y: {
                            grid: { color: gridColor },
                            ticks: { color: textColor, font: { size: 11 }, stepSize: 1 },
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // 2. Content Distribution Chart
        const distCtx = document.getElementById('contentDistChart');
        if (distCtx) {
            new Chart(distCtx, {
                type: 'doughnut',
                data: {
                    labels: @json(collect($contentDistribution)->pluck('label')),
                    datasets: [{
                        data: @json(collect($contentDistribution)->pluck('count')),
                        backgroundColor: @json(collect($contentDistribution)->pluck('color')),
                        borderWidth: 0,
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#0f172a' : '#ffffff',
                            titleColor: isDark ? '#f8fafc' : '#0f172a',
                            bodyColor: isDark ? '#94a3b8' : '#475569',
                            borderColor: isDark ? '#334155' : '#e2e8f0',
                            borderWidth: 1,
                            cornerRadius: 10,
                            padding: 12,
                            displayColors: true,
                            callbacks: {
                                label: ctx => ' ' + ctx.label + ': ' + ctx.parsed + ' item'
                            }
                        }
                    }
                }
            });
        }
    });
    </script>
    @endpush
</x-admin-layout>
