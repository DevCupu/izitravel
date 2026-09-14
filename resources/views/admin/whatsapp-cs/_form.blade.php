<div class="space-y-6">
    <!-- Section: Informasi CS -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center shrink-0">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Detail CS WhatsApp</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Informasi utama customer service yang menerima lead</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-group">
                <label for="name">{{ __('Nama CS') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name', $cs->name ?? '') }}" required
                    class="transition" placeholder="Contoh: Budi Santoso">
                @error('name') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="phone">{{ __('Nomor WhatsApp') }}</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $cs->phone ?? '') }}" required
                    class="transition" placeholder="Contoh: 6281312345678">
                <p class="mt-1.5 text-[11px] text-slate-400 dark:text-slate-500">Format internasional tanpa tanda +. Awalan 0 akan otomatis diubah ke 62.</p>
                @error('phone') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="weight">{{ __('Bobot (Weight)') }}</label>
                <input type="number" id="weight" name="weight" min="1" max="1000" value="{{ old('weight', $cs->weight ?? 1) }}"
                    class="transition" placeholder="Contoh: 50">
                <p class="mt-1.5 text-[11px] text-slate-400 dark:text-slate-500">Semakin besar angka, semakin sering CS ini terpilih. Contoh 50 : 30 : 20.</p>
                @error('weight') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="border-t border-slate-100 dark:border-slate-700"></div>

    <!-- Section: Status -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                <i data-lucide="activity" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Status Penerima Lead</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Nonaktifkan saat CS libur agar tidak menerima lead</p>
            </div>
        </div>

        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                <div>
                    <p class="text-sm font-bold text-slate-800 dark:text-white">Menerima lead</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">CS aktif akan diikutsertakan dalam pembagian lead</p>
                </div>
            </div>
            <label class="relative inline-flex items-center cursor-pointer" x-data="{ checked: {{ old('is_active', $cs->is_active ?? true) ? 'true' : 'false' }} }">
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