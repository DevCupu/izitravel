<div class="space-y-6">
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-900/30 text-violet-500 flex items-center justify-center shrink-0">
                <i data-lucide="tag" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Informasi Kategori</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Detail kategori paket umrah</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-group">
                <label for="name" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Nama Kategori') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required
                    class="transition"
                    placeholder="Contoh: Ekonomi, Premium, VVIP">
                @error('name') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="order" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Urutan Tampil') }}</label>
                <input type="number" id="order" name="order" min="0" value="{{ old('order', $category->order) }}"
                    class="transition" placeholder="Contoh: 1">
                @error('order') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="border-t border-slate-100 dark:border-slate-700"></div>

    <div>
        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600">
            <div class="flex items-center gap-3">
                <i data-lucide="eye" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                <div>
                    <p class="text-sm font-bold text-slate-800 dark:text-white">Aktifkan Kategori</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Kategori akan tersedia untuk dipilih pada paket umrah</p>
                </div>
            </div>
            <label class="relative inline-flex items-center cursor-pointer" x-data="{ checked: {{ old('is_active', $category->is_active ?? true) ? 'true' : 'false' }} }">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" x-model="checked" class="sr-only peer">
                <div @click="checked = !checked"
                     class="toggle-switch" :class="checked && 'active'">
                    <div class="toggle-dot"></div>
                </div>
            </label>
        </div>
    </div>
</div>
