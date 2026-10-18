<div class="space-y-6">
    <!-- Section: Informasi CS -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center shrink-0">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Detail CS WhatsApp</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Nama dan nomor tujuan chat pelanggan</p>
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
                <label for="weight">{{ __('Porsi pembagian chat') }}</label>
                <input type="number" id="weight" name="weight" min="1" max="1000" value="{{ old('weight', $cs->weight ?? 1) }}"
                    class="transition" placeholder="Contoh: 50">
                <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Semakin besar angka dibanding CS lain, semakin sering nomor ini menerima chat. Misalnya 50 dan 50 berarti pembagian seimbang.</p>
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
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Penerimaan chat</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Matikan saat CS libur agar tidak dipilih di halaman /chat</p>
            </div>
        </div>

        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                <div>
                    <p class="text-sm font-bold text-slate-800 dark:text-white">Terima chat baru</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Saat dicentang, nomor ini dapat dipilih untuk chat baru</p>
                </div>
            </div>
            <label class="relative inline-flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $cs->is_active ?? true)) class="h-5 w-5 rounded border-slate-400 text-[#3166b1] focus:ring-[#3166b1]">
                <span class="sr-only">Terima chat baru</span>
            </label>
        </div>
    </div>
</div>
