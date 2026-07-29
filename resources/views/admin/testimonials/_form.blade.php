<div class="space-y-6">
    <!-- Section: Informasi Jamaah -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-500 flex items-center justify-center shrink-0">
                <i data-lucide="user" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Informasi Jamaah</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Detail penulis testimoni</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-group">
                <label for="name" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Nama Jamaah') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name', $testimonial->name) }}" required
                    class="transition"
                    placeholder="Contoh: Ahmad Rizki">
                @error('name') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="location" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Asal Kota') }}</label>
                <input type="text" id="location" name="location" value="{{ old('location', $testimonial->location) }}" required
                    class="transition"
                    placeholder="Contoh: Makassar">
                @error('location') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-group mt-4">
            <label for="photo" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Foto Profil (Avatar)') }}</label>
            <div class="flex items-center gap-4">
                @if(!empty($testimonial->photo))
                    <div class="w-14 h-14 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-50 shrink-0 relative group">
                        <img src="{{ asset('storage/' . $testimonial->photo) }}" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="w-14 h-14 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center shrink-0 border border-slate-200 dark:border-slate-700">
                        <i data-lucide="user" class="w-6 h-6"></i>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <input type="file" id="photo" name="photo" accept="image/*" class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 dark:file:bg-blue-900/20 file:text-blue-600 dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-900/35 transition cursor-pointer">
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Mendukung format PNG, JPG, JPEG, atau WEBP. Maksimal 2MB.</p>
                </div>
            </div>
            @error('photo') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="border-t border-slate-100 dark:border-slate-700"></div>

    <!-- Section: Pesan -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                <i data-lucide="message-square" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pesan Testimoni</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Isi pesan dari jamaah</p>
            </div>
        </div>

        <div class="form-group">
            <label for="message" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Pesan') }}</label>
            <textarea id="message" name="message" rows="4" required
                class="transition"
                placeholder="Tulis pesan testimoni dari jamaah...">{{ old('message', $testimonial->message) }}</textarea>
            @error('message') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="border-t border-slate-100 dark:border-slate-700"></div>

    <!-- Section: Link Video -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-500 flex items-center justify-center shrink-0">
                <i data-lucide="video" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Video Testimoni (Opsional)</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Hubungkan video dari YouTube, Instagram Reels, atau Vimeo</p>
            </div>
        </div>

        <div class="form-group">
            <label for="video_url" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Link Video') }}</label>
            <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $testimonial->video_url ?? '') }}"
                class="transition"
                placeholder="Contoh: https://www.youtube.com/watch?v=xxxxxx atau https://www.instagram.com/reel/xxxxxx">
            @error('video_url') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1.5">Mendukung link YouTube (termasuk YouTube Shorts), Instagram Reels, atau Vimeo.</p>
        </div>
    </div>

    <div class="border-t border-slate-100 dark:border-slate-700"></div>

    <!-- Section: Rating & Status -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center shrink-0">
                <i data-lucide="star" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Rating & Status</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Atur rating dan visibilitas</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-group">
                <label for="rating" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Rating') }}</label>
                <select id="rating" name="rating"
                    class="transition">
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" @selected((int) old('rating', $testimonial->rating ?? 5) === $i)>⭐ {{ $i }} {{ __('Bintang') }}</option>
                    @endfor
                </select>
                @error('rating') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="order" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">{{ __('Urutan Tampil') }}</label>
                <input type="number" id="order" name="order" min="0" value="{{ old('order', $testimonial->order) }}"
                    class="transition" placeholder="Contoh: 1">
                @error('order') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Toggle status -->
        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600 mt-4">
            <div class="flex items-center gap-3">
                <i data-lucide="eye" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                <div>
                    <p class="text-sm font-bold text-slate-800 dark:text-white">Tampilkan di landing page</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Testimoni akan terlihat oleh pengunjung</p>
                </div>
            </div>
            <label class="relative inline-flex items-center cursor-pointer" x-data="{ checked: {{ old('is_active', $testimonial->is_active ?? true) ? 'true' : 'false' }} }">
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
