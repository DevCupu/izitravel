<div class="space-y-6">
    <!-- Section: Informasi Promo -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                <i data-lucide="megaphone" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Detail Promo</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Judul, label, deskripsi dan tombol aksi</p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                    <label for="title">{{ __('Judul Promo') }}</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $promo->title ?? '') }}" required
                        class="transition"
                        placeholder="Contoh: Paket Umrah Reguler 2026">
                    @error('title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label for="label">{{ __('Label Badge') }}</label>
                    <input type="text" id="label" name="label" value="{{ old('label', $promo->label ?? '') }}"
                        class="transition"
                        placeholder="Contoh: Promo Umrah">
                    @error('label') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description">{{ __('Deskripsi') }}</label>
                <textarea id="description" name="description" rows="3" required
                    class="transition"
                    placeholder="Cerita singkat tentang promo ini...">{{ old('description', $promo->description ?? '') }}</textarea>
                @error('description') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                    <label for="cta_text">{{ __('Teks Tombol') }}</label>
                    <input type="text" id="cta_text" name="cta_text" value="{{ old('cta_text', $promo->cta_text ?? '') }}"
                        class="transition"
                        placeholder="Contoh: Lihat Paket">
                    @error('cta_text') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label for="action_url">{{ __('Tautan Tombol') }}</label>
                    <input type="text" id="action_url" name="action_url" value="{{ old('action_url', $promo->action_url ?? '#') }}"
                        class="transition"
                        placeholder="Contoh: /#paket-umrah atau https://wa.me/...">
                    @error('action_url') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                    <label for="order">{{ __('Urutan Tampil') }}</label>
                    <input type="number" id="order" name="order" min="0" value="{{ old('order', $promo->order ?? 0) }}"
                        class="transition" placeholder="Contoh: 1">
                    @error('order') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-slate-100 dark:border-slate-700"></div>

    <!-- Section: Gambar & Status -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center shrink-0">
                <i data-lucide="image" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Gambar & Status Tampil</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Pilih gambar background promo (disarankan lebar) dan status aktif</p>
            </div>
        </div>

        <div class="space-y-4">
            <!-- Toggle status -->
            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600">
                <div class="flex items-center gap-3">
                    <i data-lucide="eye" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                    <div>
                        <p class="text-sm font-bold text-slate-800 dark:text-white">Tampilkan di landing page</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Promo akan terlihat oleh pengunjung</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer" x-data="{ checked: {{ old('is_active', $promo->is_active ?? true) ? 'true' : 'false' }} }">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" x-model="checked" class="sr-only peer">
                    <div @click="checked = !checked"
                        class="toggle-switch" :class="checked && 'active'">
                        <div class="toggle-dot"></div>
                    </div>
                </label>
            </div>

            <!-- Upload gambar -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Gambar Background</label>
                <div x-data="{
                    preview: '{{ (isset($promo) && $promo->image_url) ? $promo->image_url : '' }}',
                    handleDrop(e) {
                        e.preventDefault();
                        this.$refs.fileInput.files = e.dataTransfer.files;
                        this.handleFile(e.dataTransfer.files[0]);
                        this.$refs.zone.classList.remove('dragover');
                    },
                    handleFile(file) {
                        if (file && file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = (e) => this.preview = e.target.result;
                            reader.readAsDataURL(file);
                        }
                    }
                }">
                    <div x-ref="zone"
                        class="upload-zone relative p-6 text-center cursor-pointer"
                        @dragover.prevent="$refs.zone.classList.add('dragover')"
                        @dragleave.prevent="$refs.zone.classList.remove('dragover')"
                        @drop.prevent="handleDrop($event)"
                        @click="$refs.fileInput.click()">

                        <template x-if="preview">
                            <div class="relative inline-block mb-3">
                                <img :src="preview" class="w-64 h-36 object-cover rounded-xl shadow-sm border border-slate-200 dark:border-slate-600">
                                <button type="button" @click.stop="preview = ''; $refs.fileInput.value = ''"
                                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </template>

                        <template x-if="!preview">
                            <div class="flex flex-col items-center gap-2 py-2">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                    <i data-lucide="upload-cloud" class="w-5 h-5 text-slate-400 dark:text-slate-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Drag & drop gambar promo</p>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">atau klik untuk memilih file</p>
                                </div>
                            </div>
                        </template>

                        <input x-ref="fileInput" type="file" id="image" name="image" accept="image/*" class="hidden"
                            @change="handleFile($event.target.files[0])">
                    </div>
                </div>
                <p class="mt-1.5 text-[11px] text-slate-400 dark:text-slate-500">Format PNG/JPG/WEBP, maksimal 4MB. Disarankan rasio lebar (landscape).</p>
                @error('image') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>