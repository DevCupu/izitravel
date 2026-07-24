<x-admin-layout :title="__('Paket Umrah')">
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
            {{ __('Paket Umrah') }}
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
            {{ __('Kelola paket umrah yang ditampilkan pada landing page.') }}
        </p>
    </x-slot>

    <div class="space-y-4">
        <!-- Header actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 animate-fade-in-up">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold px-3 py-1.5 rounded-lg">
                    <i data-lucide="package" class="w-3.5 h-3.5"></i>
                    {{ $packages->total() }} paket
                </span>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                @include('admin.partials._search', ['action' => route('admin.packages.index'), 'value' => $search, 'placeholder' => __('Cari paket, maskapai, hotel...')])
                <a href="{{ route('admin.packages.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-sm text-white transition-all duration-150 shadow-lg shadow-blue-500/20">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    {{ __('Tambah Paket') }}
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden animate-fade-in-up stagger-2">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 bg-slate-50/80 dark:bg-slate-900/50">
                            <th class="px-5 py-3">{{ __('Paket') }}</th>
                            <th class="px-5 py-3">{{ __('Kategori') }}</th>
                            <th class="px-5 py-3">{{ __('Keberangkatan') }}</th>
                            <th class="px-5 py-3">{{ __('Harga') }}</th>
                            <th class="px-5 py-3">{{ __('Badge') }}</th>
                            <th class="px-5 py-3">{{ __('Status') }}</th>
                            <th class="px-5 py-3 text-right">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse ($packages as $package)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/30 transition group">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 overflow-hidden shrink-0 flex items-center justify-center">
                                            @if ($package->image_url)
                                                <img src="{{ $package->image_url }}" alt="{{ $package->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                            @else
                                                <i data-lucide="image" class="w-5 h-5 text-slate-400 dark:text-slate-500"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-800 dark:text-white truncate">{{ $package->name }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $package->duration_days }} {{ __('Hari') }} &middot; {{ $package->airline }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if ($package->category)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-lg
                                            @switch($package->category)
                                                @case('Ekonomi') bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 @break
                                                @case('Premium') bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 @break
                                                @case('VVIP') bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 @break
                                                @default bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400
                                            @endswitch
                                        ">
                                            {{ $package->category }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600">&mdash;</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">{{ $package->departure_date->translatedFormat('d M Y') }}</td>
                                <td class="px-5 py-3.5 font-semibold text-slate-800 dark:text-white">Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                                <td class="px-5 py-3.5">
                                    @if ($package->badge_label)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider
                                            @switch($package->badge_color)
                                                @case('amber') bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 @break
                                                @case('emerald') bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 @break
                                                @case('indigo') bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 @break
                                                @case('rose') bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400 @break
                                                @default bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400
                                            @endswitch
                                        ">
                                            {{ $package->badge_label }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600">&mdash;</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    @if ($package->is_active)
                                        <span class="inline-flex items-center gap-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[11px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[11px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                            <i data-lucide="eye-off" class="w-3 h-3"></i>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1 opacity-60 group-hover:opacity-100 transition">
                                        <a href="{{ route('admin.packages.edit', $package) }}" class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 transition" title="{{ __('Ubah') }}">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Hapus paket &quot;{{ $package->name }}&quot;?' })"
                                                    class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 transition"
                                                    title="{{ __('Hapus') }}">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                            <i data-lucide="package" class="w-7 h-7 text-slate-300 dark:text-slate-500"></i>
                                        </div>
                                        <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('Belum ada paket umrah.') }}</p>
                                        <a href="{{ route('admin.packages.create') }}" class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 transition inline-flex items-center gap-1">
                                            <i data-lucide="plus" class="w-4 h-4"></i>
                                            Tambah Paket Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($packages->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $packages->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
