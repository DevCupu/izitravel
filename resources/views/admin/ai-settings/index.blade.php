<x-admin-layout title="Pengaturan AI">
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="text-lg font-bold text-slate-900 dark:text-white">Pengaturan AI</h1>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Atur cara asisten memilih paket umrah untuk calon jemaah.</p>
            </div>
            <a href="{{ route('public.packages.recommend') }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-[#3166b1] hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-blue-300"><i data-lucide="external-link" class="size-4" aria-hidden="true"></i>Lihat halaman publik</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl space-y-5">
        @if (session('status'))
            <div role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div role="alert" class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800 dark:border-rose-800 dark:bg-rose-950/40 dark:text-rose-200">
                <p class="font-bold">Periksa pengaturan berikut:</p>
                <ul class="mt-2 list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex items-center gap-3">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-blue-50 text-[#3166b1] dark:bg-blue-950/50 dark:text-blue-300"><i data-lucide="key-round" class="size-5" aria-hidden="true"></i></span>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Koneksi OpenAI</h2>
                        <p class="text-xs {{ $ai['key_verified'] ? 'text-emerald-700 dark:text-emerald-300' : 'text-amber-700 dark:text-amber-300' }}">
                            @if ($ai['key_verified'])
                                {{ $ai['key_source'] === 'panel' ? 'API key panel terverifikasi' : 'API key server terverifikasi' }}
                            @elseif ($ai['key_configured'])
                                API key terpasang, belum terverifikasi
                            @else
                                API key belum terpasang
                            @endif
                        </p>
                    </div>
                </div>
                <p class="mt-4 text-xs leading-6 text-slate-500 dark:text-slate-400">Key dari panel disimpan terenkripsi dan dipakai lebih dulu. <code class="rounded bg-slate-100 px-1.5 py-0.5 text-slate-700 dark:bg-slate-700 dark:text-slate-200">OPENAI_API_KEY</code> di server menjadi cadangan. Form AI di Paket Umrah hanya tampil setelah koneksi berhasil diverifikasi.</p>
                @if ($ai['key_configured'])
                    <form method="POST" action="{{ route('admin.ai-settings.verify') }}" class="mt-4">@csrf<button type="submit" class="inline-flex min-h-9 items-center gap-2 rounded-lg border border-blue-200 px-3 text-xs font-bold text-[#3166b1] hover:bg-blue-50 dark:border-blue-800 dark:text-blue-300 dark:hover:bg-blue-950"><i data-lucide="refresh-cw" class="size-3.5" aria-hidden="true"></i>Periksa koneksi sekarang</button></form>
                @endif
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex items-center gap-3"><span class="flex size-10 items-center justify-center rounded-xl bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300"><i data-lucide="shield-check" class="size-5" aria-hidden="true"></i></span><div><h2 class="text-sm font-bold text-slate-900 dark:text-white">Persetujuan pengunjung</h2><p class="text-xs text-slate-500 dark:text-slate-400">Tetap wajib sebelum permintaan AI</p></div></div>
                <p class="mt-4 text-xs leading-6 text-slate-500 dark:text-slate-400">Saat AI dimatikan, key belum tersedia, batas harian tercapai, atau layanan gagal, pengunjung tetap melihat paket dari filter biasa.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.ai-settings.update') }}" class="space-y-5">
            @csrf
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-7" aria-labelledby="ai-basic-heading">
                <div class="mb-6 flex items-center gap-3"><i data-lucide="sparkles" class="size-5 text-[#3166b1]" aria-hidden="true"></i><h2 id="ai-basic-heading" class="text-base font-bold text-slate-900 dark:text-white">Perilaku AI</h2></div>
                <div class="mb-6"><label for="api_key" class="mb-2 block text-xs font-bold text-slate-700 dark:text-slate-200">API key OpenAI</label><input id="api_key" name="api_key" type="password" autocomplete="new-password" maxlength="2000" placeholder="Kosongkan untuk mempertahankan key saat ini"><p class="mt-2 text-xs leading-5 text-slate-500 dark:text-slate-400">Isi hanya saat memasang atau mengganti key. Key tidak ditampilkan di halaman maupun disimpan dalam riwayat formulir.</p>@if ($ai['key_source'] === 'panel' && $ai['key_configured'])<label class="mt-3 flex items-center gap-2 text-xs font-semibold text-rose-700 dark:text-rose-300"><input type="checkbox" name="clear_api_key" value="1" @checked(old('clear_api_key')) class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">Hapus key panel dan gunakan key server jika tersedia</label>@endif</div>
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-blue-100 bg-blue-50/70 p-4 dark:border-blue-900 dark:bg-blue-950/30">
                    <input type="checkbox" name="enabled" value="1" @checked(old('enabled', $ai['enabled'])) class="mt-0.5 rounded border-slate-300 text-[#3166b1] focus:ring-[#3166b1]">
                    <span><strong class="block text-sm text-[#102b52] dark:text-blue-100">Aktifkan pemeringkatan AI</strong><span class="mt-1 block text-xs leading-5 text-slate-600 dark:text-slate-300">Pencarian paket tetap tersedia ketika AI dimatikan.</span></span>
                </label>
                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <div><label for="model" class="mb-2 block text-xs font-bold text-slate-700 dark:text-slate-200">Model OpenAI</label><input id="model" name="model" type="text" required maxlength="80" value="{{ old('model', $ai['model']) }}" placeholder="gpt-5.4-mini"><p class="mt-2 text-xs leading-5 text-slate-500 dark:text-slate-400">Masukkan ID model yang mendukung Structured Outputs. Model tidak tersedia akan memakai hasil filter biasa.</p></div>
                    <div><label for="daily_limit" class="mb-2 block text-xs font-bold text-slate-700 dark:text-slate-200">Batas permintaan AI per 24 jam</label><input id="daily_limit" name="daily_limit" type="number" min="0" max="10000" required value="{{ old('daily_limit', $ai['daily_limit']) }}"><p class="mt-2 text-xs leading-5 text-slate-500 dark:text-slate-400">Isi 0 untuk tanpa batas. Permintaan yang gagal tetap dihitung.</p></div>
                </div>
                <div class="mt-5"><label for="guidance" class="mb-2 block text-xs font-bold text-slate-700 dark:text-slate-200">Arahan prioritas untuk AI <span class="font-normal text-slate-500">(opsional)</span></label><textarea id="guidance" name="guidance" rows="4" maxlength="1000" placeholder="Contoh: Utamakan hotel dekat masjid saat fasilitas paket sebanding.">{{ old('guidance', $ai['guidance']) }}</textarea><p class="mt-2 text-xs leading-5 text-slate-500 dark:text-slate-400">Arahan ini menambah prioritas pilihan. Aturan untuk hanya memakai data paket aktif tetap berlaku.</p></div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-7" aria-labelledby="ai-limits-heading">
                <div class="mb-6 flex items-center gap-3"><i data-lucide="sliders-horizontal" class="size-5 text-[#3166b1]" aria-hidden="true"></i><h2 id="ai-limits-heading" class="text-base font-bold text-slate-900 dark:text-white">Jumlah & waktu respons</h2></div>
                <div class="grid gap-5 sm:grid-cols-3">
                    <div><label for="candidate_limit" class="mb-2 block text-xs font-bold text-slate-700 dark:text-slate-200">Paket yang dinilai AI</label><input id="candidate_limit" name="candidate_limit" type="number" min="6" max="30" required value="{{ old('candidate_limit', $ai['candidate_limit']) }}"><p class="mt-2 text-xs leading-5 text-slate-500 dark:text-slate-400">6–30 paket terdekat sesuai filter.</p></div>
                    <div><label for="result_limit" class="mb-2 block text-xs font-bold text-slate-700 dark:text-slate-200">Hasil yang ditampilkan</label><input id="result_limit" name="result_limit" type="number" min="1" max="5" required value="{{ old('result_limit', $ai['result_limit']) }}"><p class="mt-2 text-xs leading-5 text-slate-500 dark:text-slate-400">1–5 rekomendasi untuk pengunjung.</p></div>
                    <div><label for="timeout_seconds" class="mb-2 block text-xs font-bold text-slate-700 dark:text-slate-200">Batas waktu AI (detik)</label><input id="timeout_seconds" name="timeout_seconds" type="number" min="3" max="30" required value="{{ old('timeout_seconds', $ai['timeout_seconds']) }}"><p class="mt-2 text-xs leading-5 text-slate-500 dark:text-slate-400">Setelah batas ini, hasil filter ditampilkan.</p></div>
                </div>
            </section>

            <div class="flex justify-end"><button type="submit" class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-[#3166b1] px-6 text-sm font-bold text-white hover:bg-[#245392] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#3166b1]"><i data-lucide="save" class="size-4" aria-hidden="true"></i>Simpan pengaturan AI</button></div>
        </form>
    </div>
</x-admin-layout>
