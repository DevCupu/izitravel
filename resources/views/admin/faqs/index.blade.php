<x-admin-layout :title="__('FAQ')">
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
            {{ __('FAQ') }}
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
            {{ __('Kelola daftar pertanyaan yang sering diajukan pada landing page.') }}
        </p>
    </x-slot>

    <div class="space-y-4">
        <!-- Header actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 animate-fade-in-up">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 bg-violet-50 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 text-xs font-bold px-3 py-1.5 rounded-lg">
                    <i data-lucide="help-circle" class="w-3.5 h-3.5"></i>
                    {{ $faqs->total() }} pertanyaan
                </span>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                @include('admin.partials._search', ['action' => route('admin.faqs.index'), 'value' => $search, 'placeholder' => __('Cari pertanyaan atau jawaban...')])
                <a href="{{ route('admin.faqs.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-sm text-white transition-all duration-150 shadow-lg shadow-blue-500/20">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    {{ __('Tambah FAQ') }}
                </a>
            </div>
        </div>

        <!-- FAQ Accordion List -->
        <div class="space-y-3 animate-fade-in-up stagger-2">
            @forelse ($faqs as $faq)
                <div x-data="{ expanded: false }" class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden card-hover">
                    <div class="flex items-start gap-3 p-5 cursor-pointer" @click="expanded = !expanded">
                        <!-- Order number -->
                        <span class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                            {{ $faq->order }}
                        </span>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-800 dark:text-white leading-relaxed">{{ $faq->question }}</p>
                            <div x-show="expanded" x-collapse x-cloak class="mt-3 text-sm text-slate-500 dark:text-slate-400 leading-relaxed border-t border-slate-100 dark:border-slate-700 pt-3">
                                {{ $faq->answer }}
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            @if ($faq->is_active)
                                <span class="inline-flex items-center gap-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold px-1.5 py-0.5 rounded uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[10px] font-bold px-1.5 py-0.5 rounded uppercase tracking-wider">
                                    Off
                                </span>
                            @endif

                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="p-1.5 rounded-lg text-slate-400 dark:text-slate-500 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 transition" title="{{ __('Ubah') }}" @click.stop>
                                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            @click.stop="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Hapus FAQ ini?' })"
                                            class="p-1.5 rounded-lg text-slate-400 dark:text-slate-500 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 transition"
                                            title="{{ __('Hapus') }}">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>

                            <button class="p-1 rounded-lg text-slate-400 dark:text-slate-500 transition" @click.stop="expanded = !expanded">
                                <i data-lucide="chevron-down" :class="expanded ? 'w-4 h-4 shrink-0 transition-transform duration-200 rotate-180' : 'w-4 h-4 shrink-0 transition-transform duration-200'"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 px-6 py-16 text-center">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                            <i data-lucide="help-circle" class="w-7 h-7 text-slate-300 dark:text-slate-500"></i>
                        </div>
                        <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('Belum ada FAQ.') }}</p>
                        <a href="{{ route('admin.faqs.create') }}" class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 transition inline-flex items-center gap-1">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Tambah FAQ Pertama
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($faqs->hasPages())
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 px-5 py-4">
                {{ $faqs->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
