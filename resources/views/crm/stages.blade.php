<x-admin-layout title="Atur tahap pipeline">
    <x-slot name="header">
        <div>
            <a href="{{ route('admin.crm.pipeline') }}" class="text-xs font-bold text-[#3166b1] hover:underline">← Kembali ke pipeline</a>
            <h1 class="mt-1 text-xl font-bold text-slate-900 dark:text-white">Atur tahap pipeline</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Nama dan urutan berlaku untuk admin dan seluruh CS.</p>
        </div>
    </x-slot>

    @if ($errors->any())
        <div role="alert" class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900 dark:bg-red-950/40 dark:text-red-200">
            <p class="font-bold">Periksa pengaturan tahap:</p>
            <ul class="mt-1 list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="grid items-start gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
        <section class="min-w-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900 sm:p-6" aria-labelledby="stage-list-heading">
            <div class="mb-5 flex flex-wrap items-end justify-between gap-2">
                <div><h2 id="stage-list-heading" class="text-lg font-bold text-[#102b52] dark:text-white">Urutan tahap</h2><p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400">Gunakan panah untuk mengubah urutan. Simpan setiap nama atau warna setelah diedit.</p></div>
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-[#245392] dark:bg-slate-800 dark:text-blue-300">{{ $stages->count() }} tahap</span>
            </div>
            <ol class="space-y-3">
                @foreach ($stages as $stage)
                    @php $used = (int) ($counts[$stage->key] ?? 0); @endphp
                    <li class="rounded-xl border border-slate-200 bg-slate-50/60 p-3 dark:border-slate-700 dark:bg-slate-800/50 sm:p-4">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-white text-xs font-extrabold text-slate-500 ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-700">{{ $loop->iteration }}</span>
                            <span class="size-3.5 shrink-0 rounded-full" style="background-color: {{ $stage->color }}" aria-hidden="true"></span>
                            <strong class="min-w-0 flex-1 text-sm text-[#102b52] dark:text-white">{{ $stage->label }}</strong>
                            <span class="rounded-full bg-white px-2.5 py-1 text-[11px] font-bold text-slate-600 ring-1 ring-slate-200 dark:bg-slate-900 dark:text-slate-300 dark:ring-slate-700">{{ $used }} lead{{ $stage->kind === 'won' ? ' · berhasil' : ($stage->kind === 'lost' ? ' · gagal' : '') }}</span>
                        </div>
                        <div class="mt-3 flex flex-wrap items-end gap-3">
                            <form method="POST" action="{{ route('admin.crm.stages.update', $stage->key) }}" class="grid min-w-0 flex-1 gap-2 sm:grid-cols-[minmax(160px,1fr)_112px_auto]">
                                @csrf @method('PATCH')
                                <div><label for="stage-label-{{ $stage->key }}" class="mb-1 block text-[11px] font-bold text-slate-600 dark:text-slate-300">Nama tahap</label><input id="stage-label-{{ $stage->key }}" name="label" value="{{ $stage->label }}" required maxlength="60" class="min-h-10 w-full rounded-lg border border-slate-300 bg-white px-2.5 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white"></div>
                                <div><label for="stage-color-{{ $stage->key }}" class="mb-1 block text-[11px] font-bold text-slate-600 dark:text-slate-300">Warna</label><input id="stage-color-{{ $stage->key }}" type="color" name="color" value="{{ $stage->color }}" class="h-10 w-full cursor-pointer rounded-lg border border-slate-300 bg-white p-1 dark:border-slate-600 dark:bg-slate-900"></div>
                                <button class="min-h-10 self-end rounded-lg bg-[#3166b1] px-4 text-xs font-bold text-white hover:bg-[#245392]">Simpan</button>
                            </form>
                            <div class="flex gap-1" aria-label="Pindahkan {{ $stage->label }}">
                                <form method="POST" action="{{ route('admin.crm.stages.move', $stage->key) }}">@csrf @method('PATCH')<input type="hidden" name="direction" value="up"><button class="flex size-10 items-center justify-center rounded-lg border border-slate-300 bg-white text-[#3166b1] hover:bg-blue-50 disabled:opacity-40 dark:border-slate-600 dark:bg-slate-900" aria-label="Naikkan {{ $stage->label }}" @disabled($loop->first)><i data-lucide="arrow-up" class="size-4" aria-hidden="true"></i></button></form>
                                <form method="POST" action="{{ route('admin.crm.stages.move', $stage->key) }}">@csrf @method('PATCH')<input type="hidden" name="direction" value="down"><button class="flex size-10 items-center justify-center rounded-lg border border-slate-300 bg-white text-[#3166b1] hover:bg-blue-50 disabled:opacity-40 dark:border-slate-600 dark:bg-slate-900" aria-label="Turunkan {{ $stage->label }}" @disabled($loop->last)><i data-lucide="arrow-down" class="size-4" aria-hidden="true"></i></button></form>
                            </div>
                        </div>
                        @if (! in_array($stage->key, ['baru', 'berhasil', 'gagal'], true))
                            <form method="POST" action="{{ route('admin.crm.stages.destroy', $stage->key) }}" onsubmit="return confirm('Hapus tahap ini? Lead yang memakainya akan dipindahkan ke tahap pengganti.')" class="mt-3 flex flex-wrap items-end gap-2 border-t border-slate-200 pt-3 dark:border-slate-700">
                                @csrf @method('DELETE')
                                @if ($used > 0)<div class="min-w-[180px] flex-1 sm:flex-none"><label for="replacement-{{ $stage->key }}" class="mb-1 block text-[11px] font-bold text-slate-600 dark:text-slate-300">Pindahkan {{ $used }} lead ke</label><select id="replacement-{{ $stage->key }}" name="replacement_stage" required class="min-h-10 w-full rounded-lg border border-slate-300 bg-white px-2.5 text-xs dark:border-slate-600 dark:bg-slate-900 dark:text-white"><option value="">Pilih tahap pengganti</option>@foreach ($stages as $target)@if ($target->key !== $stage->key && $target->kind === 'open')<option value="{{ $target->key }}">{{ $target->label }}</option>@endif @endforeach</select></div>@endif
                                <button class="min-h-10 rounded-lg border border-red-200 bg-white px-3 text-xs font-bold text-red-700 hover:bg-red-50 dark:border-red-900 dark:bg-slate-900 dark:text-red-300">Hapus tahap</button>
                            </form>
                        @else
                            <p class="mt-3 border-t border-slate-200 pt-3 text-[11px] text-slate-500 dark:border-slate-700 dark:text-slate-400">Tahap dasar ini dapat diganti nama dan urutannya, tetapi tetap diperlukan sistem.</p>
                        @endif
                    </li>
                @endforeach
            </ol>
        </section>

        <aside class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900 sm:p-6" aria-labelledby="new-stage-heading">
            <h2 id="new-stage-heading" class="text-lg font-bold text-[#102b52] dark:text-white">Tambah tahap</h2>
            <p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400">Tahap baru tersedia untuk semua lead. Posisinya dapat dipindah setelah dibuat.</p>
            <form method="POST" action="{{ route('admin.crm.stages.store') }}" class="mt-5 space-y-4">
                @csrf
                <div><label for="new-stage-label" class="mb-1 block text-sm font-bold text-slate-700 dark:text-slate-200">Nama tahap</label><input id="new-stage-label" name="label" value="{{ old('label') }}" required maxlength="60" placeholder="Contoh: Menunggu dokumen" class="min-h-11 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white"></div>
                <div><label for="new-stage-color" class="mb-1 block text-sm font-bold text-slate-700 dark:text-slate-200">Warna penanda</label><input id="new-stage-color" type="color" name="color" value="{{ old('color', '#4674ad') }}" class="h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white p-1 dark:border-slate-600 dark:bg-slate-800"></div>
                <button class="min-h-11 w-full rounded-xl bg-[#3166b1] px-4 text-sm font-bold text-white hover:bg-[#245392]">Tambah tahap</button>
            </form>
            <p class="mt-5 rounded-xl bg-blue-50 p-3 text-xs leading-relaxed text-[#245392] dark:bg-slate-800 dark:text-blue-300">Berhasil dan Gagal tetap mencatat konfirmasi atau alasan. Tahap baru dipakai untuk proses sebelum keputusan akhir.</p>
        </aside>
    </div>
</x-admin-layout>
