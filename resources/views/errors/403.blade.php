<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>403 - Akses Ditolak | IZI Travel</title>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@700&family=Outfit:wght@700;800;900&family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                        islamic: ['El Messiri', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes bounceSlow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }
        .animate-bounce-slow { animation: bounceSlow 4s ease-in-out infinite; }
        @keyframes pulseSlow {
            0%, 100% { opacity: 1; filter: drop-shadow(0 0 25px rgba(239, 68, 68, 0.45)); }
            50% { opacity: 0.85; filter: drop-shadow(0 0 8px rgba(239, 68, 68, 0.15)); }
        }
        .animate-pulse-slow { animation: pulseSlow 3s ease-in-out infinite; }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-950 via-rose-950 to-slate-900 text-slate-100 min-h-screen flex items-center justify-center relative overflow-hidden font-sans antialiased">
    <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z' fill='%23ef4444'/%3E%3C/svg%3E&quot;); background-size: 60px 60px;"></div>
    <div class="absolute -right-32 -top-32 w-[600px] h-[600px] bg-rose-600/10 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute -left-32 -bottom-32 w-[600px] h-[600px] bg-red-500/10 rounded-full blur-[130px] pointer-events-none"></div>

    <div class="relative z-10 max-w-lg mx-4 text-center bg-white/5 backdrop-blur-xl border border-white/10 p-8 sm:p-16 rounded-[2.5rem] shadow-2xl flex flex-col items-center">
        <div class="animate-bounce-slow mb-6">
            <svg class="w-20 h-20 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
        </div>

        <h1 class="text-8xl sm:text-9xl font-black tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-red-400 via-rose-300 to-red-500 animate-pulse-slow font-heading">403</h1>

        <h2 class="text-2xl sm:text-3xl font-extrabold text-white mt-6 mb-3 font-heading tracking-tight">Akses Ditolak</h2>

        <p class="text-slate-400 text-xs sm:text-sm leading-relaxed mb-8 max-w-sm">
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.
        </p>

        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold px-8 py-3.5 rounded-xl transition duration-300 shadow-lg shadow-blue-500/20 hover:shadow-blue-500/30 transform active:scale-95 text-xs sm:text-sm">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Kembali ke Beranda
        </a>

        <p class="text-[10px] sm:text-xs text-slate-500 mt-8 font-bold uppercase tracking-widest font-heading">IZI Travel</p>
    </div>
</body>
</html>
