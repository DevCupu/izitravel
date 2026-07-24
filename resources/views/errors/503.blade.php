<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>503 - Pemeliharaan | IZI Travel</title>
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
            0%, 100% { opacity: 1; filter: drop-shadow(0 0 25px rgba(99, 102, 241, 0.45)); }
            50% { opacity: 0.85; filter: drop-shadow(0 0 8px rgba(99, 102, 241, 0.15)); }
        }
        .animate-pulse-slow { animation: pulseSlow 3s ease-in-out infinite; }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 text-slate-100 min-h-screen flex items-center justify-center relative overflow-hidden font-sans antialiased">
    <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z' fill='%236366f1'/%3E%3C/svg%3E&quot;); background-size: 60px 60px;"></div>
    <div class="absolute -right-32 -top-32 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute -left-32 -bottom-32 w-[600px] h-[600px] bg-violet-500/10 rounded-full blur-[130px] pointer-events-none"></div>

    <div class="relative z-10 max-w-lg mx-4 text-center bg-white/5 backdrop-blur-xl border border-white/10 p-8 sm:p-16 rounded-[2.5rem] shadow-2xl flex flex-col items-center">
        <div class="animate-bounce-slow mb-6">
            <svg class="w-20 h-20 text-indigo-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342"/>
            </svg>
        </div>

        <h1 class="text-8xl sm:text-9xl font-black tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-violet-300 to-indigo-500 animate-pulse-slow font-heading">503</h1>

        <h2 class="text-2xl sm:text-3xl font-extrabold text-white mt-6 mb-3 font-heading tracking-tight">Sedang Pemeliharaan</h2>

        <p class="text-slate-400 text-xs sm:text-sm leading-relaxed mb-8 max-w-sm">
            Saat ini kami sedang melakukan pemeliharaan untuk meningkatkan layanan. Silakan kembali lagi beberapa saat.
        </p>

        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold px-8 py-3.5 rounded-xl transition duration-300 shadow-lg shadow-blue-500/20 hover:shadow-blue-500/30 transform active:scale-95 text-xs sm:text-sm">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Coba Lagi
        </a>

        <p class="text-[10px] sm:text-xs text-slate-500 mt-8 font-bold uppercase tracking-widest font-heading">IZI Travel</p>
    </div>
</body>
</html>
