<x-admin-layout :title="__('Testimoni')">
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
            {{ __('Testimoni') }}
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
            {{ __('Kelola testimoni jamaah yang ditampilkan pada landing page.') }}
        </p>
    </x-slot>

    <div class="space-y-4">
        <!-- Header actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 animate-fade-in-up">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-xs font-bold px-3 py-1.5 rounded-lg">
                    <i data-lucide="message-square-quote" class="w-3.5 h-3.5"></i>
                    {{ $testimonials->total() }} testimoni
                </span>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                @include('admin.partials._search', ['action' => route('admin.testimonials.index'), 'value' => $search, 'placeholder' => __('Cari nama, lokasi, isi...')])
                <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-sm text-white transition-all duration-150 shadow-lg shadow-blue-500/20">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    {{ __('Tambah Testimoni') }}
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden animate-fade-in-up stagger-2">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 bg-slate-50/80 dark:bg-slate-900/50">
                            <th class="px-5 py-3">{{ __('Jamaah') }}</th>
                            <th class="px-5 py-3">{{ __('Pesan') }}</th>
                            <th class="px-5 py-3">{{ __('Rating') }}</th>
                            <th class="px-5 py-3">{{ __('Status') }}</th>
                            <th class="px-5 py-3 text-right">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse ($testimonials as $testimonial)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/30 transition group">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($testimonial->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <p class="font-semibold text-slate-800 dark:text-white">{{ $testimonial->name }}</p>
                                                @if ($testimonial->video_url)
                                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[9px] font-bold uppercase tracking-wider" title="Testimoni Video">
                                                        <i data-lucide="video" class="w-3 h-3"></i>
                                                        Video
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $testimonial->location }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400 max-w-md">
                                    <p class="line-clamp-2 text-xs leading-relaxed">{{ $testimonial->message }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i data-lucide="star" class="w-3.5 h-3.5 {{ $i <= $testimonial->rating ? 'text-amber-400 fill-amber-400' : 'text-slate-200 dark:text-slate-600' }}"></i>
                                        @endfor
                                        <span class="ml-1.5 text-xs font-bold text-slate-500 dark:text-slate-400">{{ $testimonial->rating }}.0</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if ($testimonial->is_active)
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
                                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="shrink-0 p-2.5 rounded-lg bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:border-blue-200 dark:hover:border-blue-800 hover:text-blue-600 dark:hover:text-blue-400 transition" title="{{ __('Ubah') }}">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" class="delete-form shrink-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Hapus testimoni dari &quot;{{ $testimonial->name }}&quot;?' })"
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
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                            <i data-lucide="message-square-quote" class="w-7 h-7 text-slate-300 dark:text-slate-500"></i>
                                        </div>
                                        <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('Belum ada testimoni.') }}</p>
                                        <a href="{{ route('admin.testimonials.create') }}" class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 transition inline-flex items-center gap-1">
                                            <i data-lucide="plus" class="w-4 h-4"></i>
                                            Tambah Testimoni Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($testimonials->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $testimonials->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
