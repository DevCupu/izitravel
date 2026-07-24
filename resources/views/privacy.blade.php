@php
    $siteName = $settings['site_name'] ?? 'IZI Travel';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kebijakan Privasi - {{ $siteName }}</title>
    <meta name="description" content="Kebijakan privasi {{ $siteName }} terkait pengumpulan dan penggunaan data pengunjung.">
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
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/Izi LOGO.webp') }}" alt="{{ $siteName }}" class="h-8 w-auto">
            </a>
            <a href="{{ url('/') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700">&larr; Kembali</a>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-12">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-8 font-islamic">Kebijakan Privasi</h1>
        <div class="prose prose-slate max-w-none space-y-6 text-sm leading-relaxed">
            <p class="text-slate-600">Terakhir diperbarui: {{ date('d F Y') }}</p>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-3">1. Informasi yang Kami Kumpulkan</h2>
                <p class="text-slate-600">Kami mengumpulkan informasi yang Anda berikan secara sukarela, seperti nama, alamat email, nomor telepon, dan informasi lain yang diperlukan saat Anda mengisi formulir pendaftaran atau menghubungi kami.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-3">2. Penggunaan Informasi</h2>
                <p class="text-slate-600">Informasi yang kami kumpulkan digunakan untuk: memproses pendaftaran, merespons pertanyaan, mengirimkan informasi terkait layanan, dan meningkatkan kualitas layanan kami.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-3">3. Perlindungan Data</h2>
                <p class="text-slate-600">Kami menerapkan langkah-langkah keamanan yang wajar untuk melindungi informasi pribadi Anda dari akses, perubahan, pengungkapan, atau penghancuran yang tidak sah.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-3">4. Cookie</h2>
                <p class="text-slate-600">Situs web kami menggunakan cookie untuk meningkatkan pengalaman pengguna. Anda dapat mengatur preferensi cookie melalui pengaturan peramban Anda.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-3">5. Pihak Ketiga</h2>
                <p class="text-slate-600">Kami tidak menjual, menukarkan, atau mentransfer informasi pribadi Anda kepada pihak ketiga tanpa persetujuan Anda, kecuali diwajibkan oleh hukum.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mt-8 mb-3">6. Perubahan Kebijakan</h2>
                <p class="text-slate-600">Kebijakan privasi ini dapat diperbarui sewaktu-waktu. Setiap perubahan akan dipublikasikan di halaman ini.</p>
            </section>
        </div>
    </main>

    <footer class="border-t border-slate-200 py-8 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
    </footer>
</body>
</html>
