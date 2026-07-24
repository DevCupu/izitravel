@php
    $siteName = $settings['site_name'] ?? 'IZI Travel';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Syarat & Ketentuan - {{ $siteName }}</title>
    <meta name="description" content="Syarat dan ketentuan penggunaan layanan {{ $siteName }}.">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], islamic: ['El Messiri', 'serif'] }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-slate-50 text-slate-800 antialiased">
    <!-- Simple Nav -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/Izi LOGO.webp') }}" alt="{{ $siteName }}" class="h-8 w-auto">
            </a>
            <a href="{{ url('/') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700">&larr; Kembali</a>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-12">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-8 font-islamic">Syarat & Ketentuan</h1>
        <div class="prose prose-slate max-w-none space-y-6 text-sm leading-relaxed">
            <p class="text-slate-600">Terakhir diperbarui: {{ date('d F Y') }}</p>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-3">1. Penerimaan Ketentuan</h2>
                <p class="text-slate-600">Dengan mengakses dan menggunakan situs web {{ $siteName }}, Anda menyetujui untuk terikat oleh syarat dan ketentuan yang tercantum di halaman ini. Jika Anda tidak setuju dengan bagian mana pun dari ketentuan ini, Anda tidak diperkenankan menggunakan layanan kami.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-3">2. Layanan</h2>
                <p class="text-slate-600">{{ $siteName }} menyediakan layanan informasi dan pendaftaran paket perjalanan umrah. Kami berusaha menyajikan informasi yang akurat dan terkini, namun tidak menjamin kebebasan dari kesalahan atau kelalaian.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-3">3. Pendaftaran & Pembayaran</h2>
                <p class="text-slate-600">Pendaftaran dan pembayaran paket umrah dilakukan sesuai prosedur yang berlaku. Setelah pendaftaran dikonfirmasi, akan ada perjanjian terpisah yang mengatur hak dan kewajiban kedua belah pihak.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-3">4. Kekayaan Intelektual</h2>
                <p class="text-slate-600">Seluruh konten yang terdapat di situs ini, termasuk teks, gambar, logo, dan materi lainnya, adalah milik {{ $siteName }} dan dilindungi oleh undang-undang hak cipta yang berlaku.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-3">5. Perubahan Ketentuan</h2>
                <p class="text-slate-600">Kami berhak untuk mengubah syarat dan ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya. Perubahan akan berlaku segera setelah dipublikasikan di halaman ini.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-3">6. Hubungi Kami</h2>
                <p class="text-slate-600">Jika Anda memiliki pertanyaan mengenai syarat dan ketentuan ini, silakan hubungi kami melalui halaman kontak yang tersedia.</p>
            </section>
        </div>
    </main>

    <footer class="border-t border-slate-200 py-8 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
    </footer>
</body>
</html>
