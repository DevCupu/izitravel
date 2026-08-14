<x-admin-layout :title="__('Kategori')">
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
            {{ __('Kategori Paket') }}
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
            {{ __('Kelola kategori paket umrah yang tersedia.') }}
        </p>
    </x-slot>

    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 animate-fade-in-up">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 bg-violet-50 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 text-xs font-bold px-3 py-1.5 rounded-lg">
                    <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                    {{ $categories->total() }} kategori
                </span>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                @include('admin.partials._search', ['action' => route('admin.categories.index'), 'value' => $search, 'placeholder' => __('Cari kategori...')])
                <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-700 active:scale-[0.97] rounded-xl font-bold text-sm text-white transition-all duration-150 shadow-lg shadow-violet-500/20">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    {{ __('Tambah Kategori') }}
                </a>
            </div>
        </div>

        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden animate-fade-in-up stagger-2">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 bg-slate-50/80 dark:bg-slate-900/50">
                            <th class="px-5 py-3">{{ __('Kategori') }}</th>
                            <th class="px-5 py-3">{{ __('Slug') }}</th>
                            <th class="px-5 py-3">{{ __('Urutan') }}</th>
                            <th class="px-5 py-3">{{ __('Paket') }}</th>
                            <th class="px-5 py-3">{{ __('Status') }}</th>
                            <th class="px-5 py-3 text-right">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse ($categories as $category)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/30 transition group">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($category->name, 0, 1)) }}
                                        </div>
                                        <p class="font-semibold text-slate-800 dark:text-white">{{ $category->name }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $category->slug }}</td>
                                <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">{{ $category->order }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-300">{{ $category->packages_count ?? $category->packages()->count() }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if ($category->is_active)
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
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="shrink-0 p-2.5 rounded-lg bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:border-blue-200 dark:hover:border-blue-800 hover:text-blue-600 dark:hover:text-blue-400 transition" title="{{ __('Ubah') }}">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="delete-form shrink-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Hapus kategori &quot;{{ $category->name }}&quot;?' })"
                                                    class="shrink-0 p-2.5 rounded-lg bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:bg-red-50 dark:hover:bg-red-900/30 hover:border-red-200 dark:hover:border-red-800 hover:text-red-600 dark:hover:text-red-400 transition"
                                                    title="{{ __('Hapus') }}">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                            <i data-lucide="tag" class="w-7 h-7 text-slate-300 dark:text-slate-500"></i>
                                        </div>
                                        <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('Belum ada kategori.') }}</p>
                                        <a href="{{ route('admin.categories.create') }}" class="text-sm font-bold text-violet-600 dark:text-violet-400 hover:text-violet-700 transition inline-flex items-center gap-1">
                                            <i data-lucide="plus" class="w-4 h-4"></i>
                                            Tambah Kategori Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($categories->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
