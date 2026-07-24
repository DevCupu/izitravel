<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>419 - Sesi Berakhir | IZI Travel</title>
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
            0%, 100% { opacity: 1; filter: drop-shadow(0 0 25px rgba(245, 158, 11, 0.45)); }
            50% { opacity: 0.85; filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.15)); }
        }
        .animate-pulse-slow { animation: pulseSlow 3s ease-in-out infinite; }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-950 via-amber-950 to-slate-900 text-slate-100 min-h-screen flex items-center justify-center relative overflow-hidden font-sans antialiased">
    <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z' fill='%23f59e0b'/%3E%3C/svg%3E&quot;); background-size: 60px 60px;"></div>
    <div class="absolute -right-32 -top-32 w-[600px] h-[600px] bg-amber-600/10 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute -left-32 -bottom-32 w-[600px] h-[600px] bg-yellow-500/10 rounded-full blur-[130px] pointer-events-none"></div>

    <div class="relative z-10 max-w-lg mx-4 text-center bg-white/5 backdrop-blur-xl border border-white/10 p-8 sm:p-16 rounded-[2.5rem] shadow-2xl flex flex-col items-center">
        <div class="animate-bounce-slow mb-6">
            <svg class="w-20 h-20 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h1 class="text-8xl sm:text-9xl font-black tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-yellow-300 to-amber-500 animate-pulse-slow font-heading">419</h1>

        <h2 class="text-2xl sm:text-3xl font-extrabold text-white mt-6 mb-3 font-heading tracking-tight">Sesi Berakhir</h2>

        <p class="text-slate-400 text-xs sm:text-sm leading-relaxed mb-8 max-w-sm">
            Sesi Anda telah berakhir. Silakan login kembali untuk melanjutkan.
        </p>

        <a href="{{ url('/login') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold px-8 py-3.5 rounded-xl transition duration-300 shadow-lg shadow-blue-500/20 hover:shadow-blue-500/30 transform active:scale-95 text-xs sm:text-sm">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
            </svg>
            Login Kembali
        </a>

        <p class="text-[10px] sm:text-xs text-slate-500 mt-8 font-bold uppercase tracking-widest font-heading">IZI Travel</p>
    </div>
</body>
</html>
