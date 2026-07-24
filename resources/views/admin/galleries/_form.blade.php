@php
    $platformOptions = [
        'youtube' => 'YouTube',
        'vimeo' => 'Vimeo',
        'instagram' => 'Instagram Reel',
    ];

    // Reconstruct video link for existing records
    $videoLink = '';
    if (isset($gallery) && $gallery->type === 'video' && $gallery->video_id) {
        if ($gallery->video_platform === 'youtube') {
            $videoLink = 'https://www.youtube.com/watch?v=' . $gallery->video_id;
        } elseif ($gallery->video_platform === 'instagram') {
            $videoLink = 'https://www.instagram.com/reel/' . $gallery->video_id;
        } elseif ($gallery->video_platform === 'vimeo') {
            $videoLink = 'https://vimeo.com/' . $gallery->video_id;
        }
    }
    $videoLink = old('video_link', $videoLink);

    // Initial category config
    $initialCategory = old('category_label', $gallery->category_label ?? $activeAlbum ?? '');
    $hasExistingCategories = $categories->isNotEmpty();
    $inExisting = $hasExistingCategories && in_array($initialCategory, $categories->toArray());
    $defaultMode = ($hasExistingCategories && ($inExisting || empty($initialCategory))) ? 'select' : 'new';
@endphp

<div x-data="{ 
    type: '{{ old('type', $gallery->type ?? 'photo') }}',
    categoryMode: '{{ $defaultMode }}',
    selectedCategory: '{{ addslashes($initialCategory) }}'
}" class="space-y-6">

    <!-- 1. Tipe Media -->
    <div class="space-y-2">
        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Pilih Jenis Media</label>
        <div class="grid grid-cols-2 gap-3">
            <button type="button" @click="type = 'photo'"
                :class="type === 'photo' ? 'border-blue-500 bg-blue-50/50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 ring-2 ring-blue-500/10' : 'border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-500'"
                class="flex items-center justify-center gap-2 p-3 border-2 rounded-xl text-sm font-bold transition active:scale-[0.98]">
                <i data-lucide="image" class="w-4 h-4"></i>
                 Foto Kegiatan
            </button>
            <button type="button" @click="type = 'video'"
                :class="type === 'video' ? 'border-blue-500 bg-blue-50/50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 ring-2 ring-blue-500/10' : 'border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-500'"
                class="flex items-center justify-center gap-2 p-3 border-2 rounded-xl text-sm font-bold transition active:scale-[0.98]">
                <i data-lucide="video" class="w-4 h-4"></i>
                 Video Dokumentasi
            </button>
        </div>
        <input type="hidden" name="type" :value="type">
    </div>

    <!-- 2. Pengelompokan Album -->
    <div class="space-y-2.5">
        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Pilih Album Galeri</label>
        
        @if ($hasExistingCategories)
            <div class="flex gap-2">
                <button type="button" @click="categoryMode = 'select'"
                    :class="categoryMode === 'select' ? 'bg-slate-800 text-white dark:bg-slate-700' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'"
                    class="px-3.5 py-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 active:scale-[0.98]">
                    <i data-lucide="folder" class="w-3.5 h-3.5"></i>
                    Pilih Album Terdaftar
                </button>
                <button type="button" @click="categoryMode = 'new'"
                    :class="categoryMode === 'new' ? 'bg-slate-800 text-white dark:bg-slate-700' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'"
                    class="px-3.5 py-2 rounded-lg text-xs font-bold transition flex items-center gap-1.5 active:scale-[0.98]">
                    <i data-lucide="folder-plus" class="w-3.5 h-3.5"></i>
                    Ketik / Edit Nama Album
                </button>
            </div>
        @endif

        <!-- Dropdown Album -->
        <div x-show="categoryMode === 'select'" x-cloak>
            <select id="category_select" x-model="selectedCategory"
                class="w-full px-3 py-2 text-sm border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 rounded-xl transition">
                <option value="">-- Masukkan ke Album Umum --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <!-- Input Nama Album Baru -->
        <div x-show="categoryMode === 'new' || !{{ $hasExistingCategories ? 'true' : 'false' }}" x-cloak>
            <input type="text" id="category_label" placeholder="Masukkan nama album baru (Contoh: Haji 2026 atau Umroh Syawal)" 
                x-model="selectedCategory"
                class="w-full px-3 py-2 text-sm border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 rounded-xl transition">
        </div>

        <input type="hidden" name="category_label" :value="selectedCategory">
        @error('category_label') <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
    </div>

    <!-- 3. Judul -->
    <div class="space-y-1">
        <label for="title" class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Judul Dokumentasi</label>
        <input type="text" id="title" name="title" value="{{ old('title', $gallery->title) }}" required
            class="w-full px-3 py-2.5 text-sm border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 rounded-xl transition"
            placeholder="Contoh: Foto Bersama Jamaah di Depan Ka'bah">
        @error('title') <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
    </div>

    <!-- 4. Link Video (Hanya jika Video terpilih) -->
    <div x-show="type === 'video'" x-cloak class="space-y-1">
        <label for="video_link" class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Link Video</label>
        <input type="url" id="video_link" name="video_link" placeholder="Salin & tempel URL YouTube atau Instagram Reel di sini" 
            value="{{ $videoLink }}"
            class="w-full px-3 py-2.5 text-sm border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 rounded-xl transition">
        <p class="text-[10px] text-slate-400 dark:text-slate-500 leading-relaxed mt-1">Mendukung link video YouTube biasa/shorts/sharing, serta link Instagram Reel.</p>
        @error('video_link') <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <!-- 5. Urutan Tampil -->
        <div class="space-y-1">
            <label for="order" class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Urutan</label>
            <input type="number" id="order" name="order" min="0" value="{{ old('order', $gallery->order ?? 0) }}"
                class="w-full px-3 py-2.5 text-sm border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 rounded-xl transition">
            @error('order') <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
        </div>

        <!-- 6. Status Tampil -->
        <div class="space-y-1">
            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Status Tampil</label>
            <div class="flex items-center gap-3 p-2 bg-slate-50 dark:bg-slate-700/30 border border-slate-100 dark:border-slate-600 rounded-xl justify-between h-[42px] px-3">
                <span class="text-xs text-slate-500 dark:text-slate-400">Tampilkan di Web</span>
                <label class="relative inline-flex items-center cursor-pointer" x-data="{ checked: {{ old('is_active', $gallery->is_active ?? true) ? 'true' : 'false' }} }">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" x-model="checked" class="sr-only peer">
                    <div @click="checked = !checked" class="toggle-switch" :class="checked && 'active'">
                        <div class="toggle-dot"></div>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <!-- 7. Upload Gambar / Thumbnail -->
    <div class="space-y-2" x-data="{
        preview: '{{ $gallery->image_url }}',
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
        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
            <span x-text="type === 'video' ? 'Upload Cover / Thumbnail Video' : 'Upload Gambar Foto'"></span>
        </label>

        <div x-ref="zone"
             class="upload-zone relative p-5 text-center cursor-pointer"
             @dragover.prevent="$refs.zone.classList.add('dragover')"
             @dragleave.prevent="$refs.zone.classList.remove('dragover')"
             @drop.prevent="handleDrop($event)"
             @click="$refs.fileInput.click()">

             <template x-if="preview">
                 <div class="relative inline-block">
                     <img :src="preview" class="w-40 h-24 object-cover rounded-lg border border-slate-200 dark:border-slate-600">
                     <button type="button" @click.stop="preview = ''; $refs.fileInput.value = ''"
                             class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center shadow hover:bg-red-600 transition text-xs">
                         ✕
                     </button>
                 </div>
             </template>

             <template x-if="!preview">
                 <div class="flex flex-col items-center gap-1.5 py-3">
                     <i data-lucide="upload-cloud" class="w-6 h-6 text-slate-400"></i>
                     <div class="text-xs text-slate-500 dark:text-slate-400">
                         <span class="font-bold text-slate-700 dark:text-slate-200">Klik untuk memilih berkas</span> atau seret ke sini
                     </div>
                 </div>
             </template>

             <input x-ref="fileInput" type="file" id="image" name="image" accept="image/*" class="hidden"
                    @change="handleFile($event.target.files[0])">
        </div>
        <p class="text-[10px] text-slate-400 dark:text-slate-500">Format JPG/PNG/WEBP, maksimal 2MB.</p>
        @error('image') <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
    </div>

</div>
