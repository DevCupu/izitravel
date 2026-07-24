<div class="space-y-6">
    <!-- Section: Pertanyaan -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-900/30 text-violet-500 flex items-center justify-center shrink-0">
                <i data-lucide="help-circle" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pertanyaan & Jawaban</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Isi konten FAQ</p>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label for="question" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Pertanyaan') }}</label>
                <input type="text" id="question" name="question" value="{{ old('question', $faq->question) }}" required
                    class="w-full px-4 py-2.5 text-sm border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm transition"
                    placeholder="Contoh: Apa saja yang termasuk dalam paket umrah?">
                @error('question') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="answer" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Jawaban') }}</label>
                <textarea id="answer" name="answer" rows="4" required
                    class="w-full px-4 py-2.5 text-sm border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm transition"
                    placeholder="Tulis jawaban yang jelas dan informatif...">{{ old('answer', $faq->answer) }}</textarea>
                @error('answer') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="border-t border-slate-100 dark:border-slate-700"></div>

    <!-- Section: Pengaturan -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                <i data-lucide="settings" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pengaturan</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Urutan dan visibilitas</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="order" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Urutan Tampil') }}</label>
                <input type="number" id="order" name="order" min="0" value="{{ old('order', $faq->order) }}"
                    class="w-full px-4 py-2.5 text-sm border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm transition" placeholder="Contoh: 1">
                @error('order') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Toggle status -->
        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600 mt-4">
            <div class="flex items-center gap-3">
                <i data-lucide="eye" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                <div>
                    <p class="text-sm font-bold text-slate-800 dark:text-white">Tampilkan di landing page</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">FAQ akan terlihat oleh pengunjung</p>
                </div>
            </div>
            <label class="relative inline-flex items-center cursor-pointer" x-data="{ checked: {{ old('is_active', $faq->is_active ?? true) ? 'true' : 'false' }} }">
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
