{{-- Reusable admin search bar. Vars: $action (route url), $value (current search), $placeholder --}}
<form method="GET" action="{{ $action }}" class="flex gap-2 animate-fade-in-up">
    <div class="relative flex-1 sm:flex-none">
        <i data-lucide="search" class="w-4 h-4 text-slate-400 dark:text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
        <input
            type="text"
            name="search"
            value="{{ $value }}"
            placeholder="{{ $placeholder ?? __('Cari...') }}"
            class="w-full sm:w-72 pl-10 pr-4 py-2.5 text-sm border-slate-200 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm"
        >
    </div>
    <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-sm text-white transition-all duration-150 shadow-lg shadow-blue-500/20" title="{{ __('Cari') }}">
        <i data-lucide="search" class="w-4 h-4"></i>
    </button>
    @if (!empty($value))
        <a href="{{ $action }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-sm transition" title="{{ __('Reset') }}">
            <i data-lucide="x" class="w-4 h-4"></i>
        </a>
    @endif
</form>
