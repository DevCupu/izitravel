<x-admin-layout :title="__('Daftar Artikel')">
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4 w-full">
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
                    {{ __('Daftar Artikel') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
                    {{ __('Kelola konten artikel dan panduan umrah untuk landing page.') }}
                </p>
            </div>
            <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-xs text-white transition-all duration-150 shadow-lg shadow-blue-500/20">
                <i data-lucide="plus" class="w-4 h-4"></i>
                {{ __('Tambah Artikel') }}
            </a>
        </div>
    </x-slot>

    <div class="space-y-4 animate-fade-in">
        <div class="flex justify-start sm:justify-end">
            @include('admin.partials._search', ['action' => route('admin.articles.index'), 'value' => $search, 'placeholder' => __('Cari judul, kategori, penulis...')])
        </div>
        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 bg-slate-50/80 dark:bg-slate-900/50 table-header-bg border-b border-slate-100 dark:border-slate-700/50">
                            <th class="px-6 py-4">{{ __('Gambar') }}</th>
                            <th class="px-6 py-4">{{ __('Judul / Kategori') }}</th>
                            <th class="px-6 py-4">{{ __('Penulis') }}</th>
                            <th class="px-6 py-4">{{ __('Status') }}</th>
                            <th class="px-6 py-4">{{ __('Urutan') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse ($articles as $article)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition">
                                <td class="px-6 py-4 shrink-0">
                                    @if($article->image_url)
                                        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-16 h-12 object-cover rounded-lg border border-slate-100 dark:border-slate-700 shadow-sm">
                                    @else
                                        <div class="w-16 h-12 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400">
                                            <i data-lucide="image" class="w-5 h-5"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 dark:text-white line-clamp-1">{{ $article->title }}</div>
                                    <div class="text-[11px] font-bold text-blue-600 dark:text-blue-400 mt-0.5 uppercase tracking-wider">{{ $article->category }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800 dark:text-slate-200">{{ $article->author }}</div>
                                    <div class="text-xs text-slate-400 dark:text-slate-500">{{ $article->author_role }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($article->is_active)
                                        <span class="inline-flex items-center gap-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-slate-50 dark:bg-slate-700/30 text-slate-400 dark:text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-450 font-semibold text-xs">{{ $article->order }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.articles.edit', $article->id) }}" class="p-2 rounded-lg text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition" title="Edit">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>

                                        <form x-data method="POST" action="{{ route('admin.articles.destroy', $article->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Apakah Anda yakin ingin menghapus artikel ini?' })"
                                                    class="p-2 rounded-lg text-slate-400 dark:text-slate-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50/50 dark:hover:bg-red-900/10 transition" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-900 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                        <i data-lucide="book-open" class="w-6 h-6"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-500 dark:text-slate-400">{{ __('Belum ada artikel.') }}</p>
                                    <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400 font-bold mt-2 hover:underline">
                                        {{ __('Tulis Artikel Pertama') }}
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($articles->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-900/10">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
