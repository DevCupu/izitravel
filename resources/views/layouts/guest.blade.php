<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            }

            h1, h2, .font-islamic {
                font-family: 'El Messiri', 'Plus Jakarta Sans', sans-serif;
            }

            .auth-pattern {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z' fill='%23d97706' fill-opacity='0.03'/%3E%3C/svg%3E");
                background-size: 60px 60px;
            }
        </style>
    </head>
    <body class="text-slate-800 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-10 sm:pt-0 bg-stone-50 auth-pattern px-4">
            <a href="/" class="mb-2">
                <img src="{{ asset('images/Izi LOGO.webp') }}" alt="IZI Travel" class="h-12 w-auto" width="687" height="240">
            </a>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 sm:px-10 sm:py-10 bg-white border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden sm:rounded-3xl">
                {{ $slot }}
            </div>

            <a href="/" class="mt-6 mb-10 text-xs font-bold text-slate-400 hover:text-blue-600 transition uppercase tracking-wider">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </body>
</html>
