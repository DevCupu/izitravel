@props([
    'id' => null,
    'name',
    'value' => '',
    'placeholder' => 'Pilih ikon...',
    'label' => null,
    'error' => null
])

@php
    $id = $id ?? $name;
    $defaultValue = $value ?: '';
@endphp

@once
<script>
    if (typeof window.iconPicker === 'undefined') {
        window.iconPicker = function(initialValue) {
            const defaultIcons = [
                'award', 'badge-check', 'building-2', 'compass', 'credit-card', 'file-check', 
                'file-text', 'plane', 'plane-takeoff', 'shield-check', 'sparkles', 'star', 
                'user-check', 'users', 'wallet', 'utensils', 'luggage', 'book-open', 'check-circle', 
                'clock', 'map-pin', 'phone', 'phone-call', 'mail', 'calendar', 'user', 'bus', 
                'sun', 'moon', 'camera', 'gift', 'package', 'flag', 'globe', 'map', 'thumbs-up', 
                'smile', 'coffee', 'bed', 'wifi', 'anchor', 'leaf', 'home', 'zap', 'image', 
                'video', 'music', 'headphones', 'mic', 'radio', 'help-circle', 'info', 
                'message-square', 'message-circle', 'heart', 'shield', 'check', 'settings', 
                'bell', 'search', 'lock', 'activity', 'bookmark', 'briefcase', 'layers', 'sliders'
            ];

            let iconsList = [...defaultIcons];
            if (initialValue && !iconsList.includes(initialValue)) {
                iconsList.unshift(initialValue);
            }

            return {
                selected: initialValue || '',
                open: false,
                search: '',
                icons: iconsList,
                get filteredIcons() {
                    if (!this.search) return this.icons;
                    return this.icons.filter(i => i.toLowerCase().includes(this.search.toLowerCase()));
                },
                toggle() {
                    this.open = !this.open;
                    if (this.open) {
                        this.search = '';
                        this.$nextTick(() => {
                            if (window.refreshLucideIcons) window.refreshLucideIcons();
                            else if (window.lucide) lucide.createIcons();
                        });
                    }
                },
                select(icon) {
                    this.selected = icon;
                    this.open = false;
                    this.$nextTick(() => {
                        if (window.refreshLucideIcons) window.refreshLucideIcons();
                        else if (window.lucide) lucide.createIcons();
                    });
                }
            };
        };
    }
</script>
@endonce

<div x-data="iconPicker('{{ addslashes($defaultValue) }}')" class="relative">
    @if($label)
        <label for="{{ $id }}" class="block text-xs font-bold text-slate-750 dark:text-slate-300 mb-1.5">{{ $label }}</label>
    @endif

    <!-- Hidden Input for Form Submission -->
    <input type="hidden" id="{{ $id }}" name="{{ $name }}" :value="selected">

    <!-- Trigger Button -->
    <div class="relative">
        <button type="button" 
            @click="toggle()" 
            class="w-full flex items-center justify-between gap-3 px-3 py-2 text-sm bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl hover:border-violet-400 dark:hover:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition group shadow-sm"
            :class="{ 'ring-2 ring-violet-500/30 border-violet-500': open }">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 flex items-center justify-center shrink-0 border border-violet-100 dark:border-violet-800/40">
                    <template x-if="selected">
                        <i :data-lucide="selected" class="w-4 h-4"></i>
                    </template>
                    <template x-if="!selected">
                        <i data-lucide="help-circle" class="w-4 h-4 text-slate-400"></i>
                    </template>
                </div>
                <span class="truncate text-xs font-semibold text-slate-700 dark:text-slate-200" x-text="selected || '{{ $placeholder }}'"></span>
            </div>
            <div class="flex items-center gap-1 shrink-0 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300">
                <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400">Pilih</span>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </div>
        </button>

        <!-- Dropdown Popup -->
        <div x-show="open" 
            x-cloak 
            @click.outside="open = false" 
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
            class="absolute left-0 top-full mt-1.5 z-50 p-3 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full sm:w-80">
            
            <div class="relative mb-2.5">
                <i data-lucide="search" class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                <input x-model="search"
                    type="text"
                    placeholder="Cari ikon... (cth: compass)"
                    class="w-full pl-8 pr-3 py-1.5 text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:border-violet-500 focus:ring-violet-500 focus:bg-white dark:focus:bg-slate-800">
            </div>

            <div class="grid grid-cols-6 sm:grid-cols-7 gap-1.5 max-h-52 overflow-y-auto pr-1">
                <template x-for="icon in filteredIcons" :key="icon">
                    <button type="button"
                        @click="select(icon)"
                        :title="icon"
                        class="p-2 rounded-xl flex flex-col items-center justify-center gap-1 transition-all group relative border border-transparent"
                        :class="selected === icon 
                            ? 'bg-violet-500 text-white font-bold shadow-md shadow-violet-500/30' 
                            : 'hover:bg-slate-100 dark:hover:bg-slate-700/60 text-slate-600 dark:text-slate-300 border-slate-100/50 dark:border-slate-700/50'">
                        <i :data-lucide="icon" class="w-4 h-4 pointer-events-none"></i>
                    </button>
                </template>
                <template x-if="filteredIcons.length === 0">
                    <div class="col-span-6 sm:col-span-7 py-6 text-center text-xs text-slate-400">
                        <i data-lucide="search-x" class="w-5 h-5 mx-auto mb-1 opacity-50"></i>
                        Ikon "<span x-text="search"></span>" tidak ditemukan
                    </div>
                </template>
            </div>

            <div class="mt-2.5 pt-2 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-[11px] text-slate-400 dark:text-slate-500">
                <span>Dipilih: <strong class="text-violet-600 dark:text-violet-400 font-bold" x-text="selected || 'Belum ada'"></strong></span>
                <button type="button" x-show="selected" @click="select('')" class="text-red-500 hover:underline">Hapus</button>
            </div>
        </div>
    </div>

    @if($error)
        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $error }}</p>
    @endif
</div>
