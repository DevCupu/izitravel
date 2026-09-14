<x-admin-layout :title="__('WhatsApp CS')">
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4 w-full">
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
                    {{ __('Daftar CS WhatsApp') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
                    {{ __('Kelola customer service penerima lead dari Meta Ads.') }}
                </p>
            </div>
            <a href="{{ route('admin.whatsapp-cs.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-xs text-white transition-all duration-150 shadow-lg shadow-blue-500/20">
                <i data-lucide="plus" class="w-4 h-4"></i>
                {{ __('Tambah CS') }}
            </a>
        </div>
    </x-slot>

    <div class="space-y-4 animate-fade-in">
        <div class="flex justify-start sm:justify-end">
            @include('admin.partials._search', ['action' => route('admin.whatsapp-cs.index'), 'value' => $search, 'placeholder' => __('Cari nama atau nomor...')])
        </div>
        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 bg-slate-50/80 dark:bg-slate-900/50 table-header-bg border-b border-slate-100 dark:border-slate-700/50">
                            <th class="px-6 py-4">{{ __('Nama') }}</th>
                            <th class="px-6 py-4">{{ __('Nomor WhatsApp') }}</th>
                            <th class="px-6 py-4">{{ __('Bobot') }}</th>
                            <th class="px-6 py-4">{{ __('Status') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse ($csList as $cs)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-600 flex items-center justify-center text-white font-extrabold text-sm border border-slate-100 dark:border-slate-700">
                                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($cs->name, 0, 1)) }}
                                        </div>
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $cs->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs font-semibold text-slate-600 dark:text-slate-300">
                                    {{ $cs->phone }}
                                    @php $waPhone = str_starts_with($cs->phone, '0') ? '62'.substr($cs->phone, 1) : $cs->phone; @endphp
                                    <a href="{{ 'https://wa.me/' . preg_replace('/[^0-9]/', '', $waPhone) }}" target="_blank" rel="noopener" class="ml-1 text-emerald-500 hover:text-emerald-600" title="Buka WhatsApp">
                                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                        {{ $cs->weight }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($cs->is_active)
                                        <span class="inline-flex items-center gap-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                            <i data-lucide="circle-dot" class="w-3 h-3"></i>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-slate-50 dark:bg-slate-700/30 text-slate-400 dark:text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.whatsapp-cs.edit', $cs->id) }}" class="p-2 rounded-lg text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition" title="Edit">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>

                                        <form x-data method="POST" action="{{ route('admin.whatsapp-cs.destroy', $cs->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Apakah Anda yakin ingin menghapus CS &quot;{{ $cs->name }}&quot;? Lead lama tetap tersimpan.' })"
                                                    class="p-2 rounded-lg text-slate-400 dark:text-slate-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50/50 dark:hover:bg-red-900/10 transition" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-900 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                        <i data-lucide="message-circle" class="w-6 h-6"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-500 dark:text-slate-400">{{ __('Belum ada CS WhatsApp.') }}</p>
                                    <a href="{{ route('admin.whatsapp-cs.create') }}" class="inline-flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400 font-bold mt-2 hover:underline">
                                        {{ __('Tambah CS Pertama') }}
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($csList->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-900/10">
                    {{ $csList->links() }}
                </div>
            @endif
        </div>

        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                    <i data-lucide="info" class="w-4 h-4"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800 dark:text-white">Cara kerja pembagian lead</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                        Setiap pengunjung <code class="font-mono bg-slate-100 dark:bg-slate-700 px-1 py-0.5 rounded">izitravel.id/chat</code> dipilihkan satu CS aktif secara <strong>weighted random</strong> berdasarkan bobot. Contoh bobot 50 : 30 : 20 berarti dalam jangka panjang masing-masing menerima ±50%, ±30%, dan ±20% lead. Nonaktifkan CS yang libur agar otomatis tidak dipilih.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>