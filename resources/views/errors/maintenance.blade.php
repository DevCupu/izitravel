<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $settings['maintenance_title'] ?? 'Sistem Sedang Dalam Pemeliharaan' }} - {{ $settings['site_name'] ?? 'IZI Travel' }}</title>
    
    @if (!empty($settings['site_favicon']))
        <link rel="icon" href="{{ str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon']) }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        @keyframes pulse-subtle {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.85; transform: scale(1.02); }
        }
        .animate-subtle {
            animation: pulse-subtle 4s ease-in-out infinite;
        }
    </style>
</head>
<body class="h-full bg-slate-950 text-slate-100 flex flex-col justify-between selection:bg-blue-600 selection:text-white relative overflow-x-hidden">
    @if (auth()->check() && auth()->user()->isAdmin())
        <div class="relative z-30 w-full bg-gradient-to-r from-amber-600 via-amber-500 to-amber-600 text-slate-950 px-4 py-2.5 text-center text-xs font-bold shadow-md flex items-center justify-center gap-3 flex-wrap">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-slate-950 text-amber-400 text-[10px] uppercase font-black">
                <i data-lucide="shield-alert" class="w-3 h-3"></i> Mode Pemeliharaan Aktif
            </span>
            <span>Pengunjung umum melihat halaman ini (HTTP 503). Anda login sebagai <b>Administrator</b>.</span>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.system.index') }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-slate-950 text-white hover:bg-slate-900 text-[11px] font-bold transition">
                    <i data-lucide="settings" class="w-3 h-3 text-amber-400"></i> Pengaturan Sistem
                </a>
                @if (!empty($settings['maintenance_bypass_key']))
                    <a href="{{ url('/') }}?bypass={{ $settings['maintenance_bypass_key'] }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-amber-200 text-slate-900 hover:bg-white text-[11px] font-bold transition">
                        <i data-lucide="unlock" class="w-3 h-3"></i> Buka Bypass
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- Ambient Glow Background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -right-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-emerald-600/10 rounded-full blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px] opacity-30"></div>
    </div>

    <!-- Top Navigation Header -->
    <header class="relative z-10 w-full max-w-6xl mx-auto px-6 py-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            @if (!empty($settings['site_logo']))
                <img src="{{ str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo']) }}" 
                     alt="{{ $settings['site_name'] ?? 'IZI Travel' }}" 
                     class="h-10 w-auto object-contain">
            @else
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center font-black text-white text-lg shadow-lg shadow-blue-500/30">
                        IZI
                    </div>
                    <span class="text-xl font-black tracking-tight text-white">{{ $settings['site_name'] ?? 'IZI Travel' }}</span>
                </div>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-semibold backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                Mode Pemeliharaan Terjadwal
            </span>
        </div>
    </header>

    <!-- Main Hero Content -->
    <main class="relative z-10 w-full max-w-4xl mx-auto px-6 py-10 flex-1 flex flex-col items-center justify-center text-center">
        <!-- Floating Status Icon -->
        <div class="relative mb-8">
            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-cyan-500 p-0.5 shadow-2xl shadow-blue-500/25 animate-subtle">
                <div class="w-full h-full bg-slate-900 rounded-[22px] flex items-center justify-center text-blue-400">
                    <i data-lucide="wrench" class="w-12 h-12 stroke-[1.75]"></i>
                </div>
            </div>
            <div class="absolute -bottom-2 -right-2 w-9 h-9 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center shadow-lg font-bold">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Headings -->
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white max-w-2xl leading-tight">
            {{ $settings['maintenance_title'] ?? 'Sistem Sedang Dalam Pemeliharaan Terjadwal' }}
        </h1>

        <p class="mt-4 text-base sm:text-lg text-slate-300 max-w-xl leading-relaxed">
            {{ $settings['maintenance_message'] ?? 'Kami sedang melakukan peningkatan infrastruktur server dan pembaruan sistem berkala untuk memberikan pengalaman layanan ibadah umrah yang lebih cepat, aman, dan nyaman.' }}
        </p>

        <!-- Estimated Completion Box if set -->
        @if (!empty($settings['maintenance_estimated_end']))
            <div class="mt-8 p-4 sm:p-5 rounded-2xl bg-slate-900/80 border border-slate-800 shadow-xl backdrop-blur-md max-w-md w-full">
                <p class="text-xs uppercase font-extrabold tracking-wider text-slate-400 mb-1 flex items-center justify-center gap-1.5">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-blue-400"></i>
                    Estimasi Selesai / Siap Kembali
                </p>
                <p class="text-lg font-bold text-blue-400 tabular-nums">
                    {{ \Carbon\Carbon::parse($settings['maintenance_estimated_end'])->translatedFormat('l, d F Y - H:i') }} WIB
                </p>
            </div>
        @endif

        <!-- Action & Emergency WhatsApp Support -->
        <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
            @php
                $waContact = $settings['maintenance_emergency_contact'] ?? $settings['contact_whatsapp'] ?? null;
            @endphp
            @if ($waContact)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waContact) }}?text={{ urlencode('Halo Admin IZI Travel, saya membutuhkan bantuan terkait layanan umrah.') }}" 
                   target="_blank"
                   class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white font-bold text-sm shadow-lg shadow-emerald-600/25 transition-all transform hover:-translate-y-0.5">
                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                    Hubungi Bantuan Darurat WhatsApp
                </a>
            @endif

            @if (auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('admin.system.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 transition transform hover:-translate-y-0.5">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    Kembali ke Panel Admin
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-2 px-5 py-3.5 rounded-xl bg-slate-900/80 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white font-semibold text-sm transition">
                    <i data-lucide="lock" class="w-4 h-4 text-slate-400"></i>
                    Masuk Administrator
                </a>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 w-full max-w-6xl mx-auto px-6 py-6 border-t border-slate-900 text-center text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-3">
        <p>&copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'IZI Travel' }}. Seluruh hak cipta dilindungi undang-undang.</p>
        <p class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            Server Core Infrastructure Active
        </p>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
