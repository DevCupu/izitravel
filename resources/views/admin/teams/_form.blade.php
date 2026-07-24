<div class="space-y-6">
    <!-- Section: Informasi Anggota Tim -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                <i data-lucide="user" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Detail Anggota Tim</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Informasi utama profil tim/founder</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-group">
                <label for="name">{{ __('Nama Lengkap') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name', $team->name ?? '') }}" required
                    class="transition"
                    placeholder="Contoh: H. Irfan Novian">
                @error('name') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="role">{{ __('Jabatan / Peran') }}</label>
                <input type="text" id="role" name="role" value="{{ old('role', $team->role ?? '') }}" required
                    class="transition"
                    placeholder="Contoh: CEO & Founder">
                @error('role') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="initial">{{ __('Inisial (Maks 3 Karakter, e.g. IN, RS, AF)') }}</label>
                <input type="text" id="initial" name="initial" value="{{ old('initial', $team->initial ?? '') }}"
                    class="transition" maxlength="5"
                    placeholder="Contoh: IN">
                @error('initial') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="order">{{ __('Urutan Tampil') }}</label>
                <input type="number" id="order" name="order" min="0" value="{{ old('order', $team->order ?? 0) }}"
                    class="transition" placeholder="Contoh: 1">
                @error('order') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2 form-group">
                <label for="description">{{ __('Deskripsi / Biografi Singkat') }}</label>
                <textarea id="description" name="description" rows="3"
                    class="transition"
                    placeholder="Tuliskan latar belakang singkat tentang peran dan dedikasinya...">{{ old('description', $team->description ?? '') }}</textarea>
                @error('description') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="border-t border-slate-100 dark:border-slate-700"></div>

    <!-- Section: Media & Status -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center shrink-0">
                <i data-lucide="image" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Foto & Status</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Atur foto profil dan visibilitas</p>
            </div>
        </div>

        <div class="space-y-4">
            <!-- Toggle status -->
            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600">
                <div class="flex items-center gap-3">
                    <i data-lucide="eye" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                    <div>
                        <p class="text-sm font-bold text-slate-800 dark:text-white">Tampilkan di landing page</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Tim akan terlihat oleh pengunjung</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer" x-data="{ checked: {{ old('is_active', $team->is_active ?? true) ? 'true' : 'false' }} }">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" x-model="checked" class="sr-only peer">
                    <div @click="checked = !checked"
                         class="toggle-switch" :class="checked && 'active'">
                        <div class="toggle-dot"></div>
                    </div>
                </label>
            </div>

            <!-- Image upload -->
            <div x-data="{
                preview: '{{ isset($team) ? $team->image_url : '' }}',
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

                    <!-- Preview -->
                    <template x-if="preview">
                        <div class="relative inline-block mb-3">
                            <img :src="preview" class="w-32 h-32 object-cover rounded-full shadow-sm border border-slate-200 dark:border-slate-600">
                            <button type="button" @click.stop="preview = ''; $refs.fileInput.value = ''"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </template>

                    <template x-if="!preview">
                        <div class="flex flex-col items-center gap-2 py-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                <i data-lucide="upload-cloud" class="w-6 h-6 text-slate-400 dark:text-slate-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">Drag & drop foto profil</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">atau klik untuk memilih file</p>
                            </div>
                        </div>
                    </template>

                    <input x-ref="fileInput" type="file" id="image" name="image" accept="image/*" class="hidden"
                           @change="handleFile($event.target.files[0])">
                </div>
                <p class="mt-1.5 text-[11px] text-slate-400 dark:text-slate-500">Format JPG/PNG/WEBP, maksimal 2MB. Jika dikosongkan, inisial nama akan digunakan sebagai avatar.</p>
                @error('image') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>
