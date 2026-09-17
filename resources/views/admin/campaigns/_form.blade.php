<div class="space-y-6">
    <!-- Section: Informasi Campaign -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 flex items-center justify-center shrink-0">
                <i data-lucide="megaphone" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Detail Campaign</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Informasi kampanye iklan Meta Ads</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <div class="form-group">
                <label for="name">{{ __('Nama Campaign') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name', $campaign->name ?? '') }}" required
                    class="transition" placeholder="Contoh: Visa Umrah September">
                @error('name') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="utm_campaign">{{ __('UTM Campaign (opsional)') }}</label>
                <input type="text" id="utm_campaign" name="utm_campaign" value="{{ old('utm_campaign', $campaign->utm_campaign ?? '') }}"
                    class="transition" placeholder="Contoh: visa_umrah_september">
                <p class="mt-1.5 text-[11px] text-slate-400 dark:text-slate-500">Huruf kecil, angka, tanda strip (-) atau underscore (_). Jika kosong, akan dibuat otomatis dari nama campaign.</p>
                @error('utm_campaign') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

        </div>
    </div>

    <div class="border-t border-slate-100 dark:border-slate-700"></div>

    <!-- Section: Status -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center shrink-0">
                <i data-lucide="activity" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Status Campaign</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Campaign nonaktif tetap dicatat namun tidak dicocokkan ke lead</p>
            </div>
        </div>

        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600">
            <div class="flex items-center gap-3">
                <i data-lucide="flag" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                <div>
                    <p class="text-sm font-bold text-slate-800 dark:text-white">Campaign aktif</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Lead dengan utm_campaign ini akan dipetakan ke campaign</p>
                </div>
            </div>
            <label class="relative inline-flex items-center cursor-pointer" x-data="{ checked: {{ old('is_active', $campaign->is_active ?? true) ? 'true' : 'false' }} }">
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
