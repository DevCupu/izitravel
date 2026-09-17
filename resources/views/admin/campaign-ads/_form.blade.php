<div class="space-y-6">
    <div class="rounded-xl bg-indigo-50/70 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/40 px-4 py-3 flex items-start gap-3">
        <i data-lucide="megaphone" class="w-4 h-4 text-indigo-500 mt-0.5 shrink-0"></i>
        <div>
            <p class="text-xs font-bold text-indigo-900 dark:text-indigo-200">{{ $campaign->name }}</p>
            <p class="text-[11px] font-mono text-indigo-500 dark:text-indigo-400 mt-0.5">utm_campaign={{ $campaign->utm_campaign }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4">
        <div class="form-group">
            <label for="name">{{ __('Nama Ads') }}</label>
            <input type="text" id="name" name="name" value="{{ old('name', $ad->name ?? '') }}" required class="transition" placeholder="Contoh: Video Testimoni 01">
            @error('name') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="utm_content">{{ __('Kode Ads / UTM Content') }}</label>
            <input type="text" id="utm_content" name="utm_content" value="{{ old('utm_content', $ad->utm_content ?? '') }}" class="transition" placeholder="Contoh: video_testimoni_01">
            <p class="mt-1.5 text-[11px] text-slate-400 dark:text-slate-500">Unik di dalam campaign. Jika kosong, dibuat otomatis dari nama ads.</p>
            @error('utm_content') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="wa_message_template">{{ __('Template Pesan WhatsApp') }}</label>
            <textarea id="wa_message_template" name="wa_message_template" rows="5" maxlength="1000" class="transition" placeholder="Assalamu'alaikum Admin IZI Travel, saya tertarik dengan {campaign} dari iklan {ad}.">{{ old('wa_message_template', $ad->wa_message_template ?? '') }}</textarea>
            <p class="mt-1.5 text-[11px] text-slate-400 dark:text-slate-500">Gunakan <code class="font-mono bg-slate-100 dark:bg-slate-700 px-1 py-0.5 rounded">{campaign}</code> dan <code class="font-mono bg-slate-100 dark:bg-slate-700 px-1 py-0.5 rounded">{ad}</code>. Kosongkan untuk template default.</p>
            @error('wa_message_template') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600">
        <div class="flex items-center gap-3">
            <i data-lucide="activity" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
            <div>
                <p class="text-sm font-bold text-slate-800 dark:text-white">Ads aktif</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Link aktif akan memakai template ads ini</p>
            </div>
        </div>
        <label class="relative inline-flex items-center cursor-pointer" x-data="{ checked: {{ old('is_active', $ad->is_active ?? true) ? 'true' : 'false' }} }">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" x-model="checked" class="sr-only peer">
            <div @click="checked = !checked" class="toggle-switch" :class="checked && 'active'"><div class="toggle-dot"></div></div>
        </label>
    </div>
</div>
