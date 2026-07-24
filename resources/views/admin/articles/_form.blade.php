<div class="space-y-6">
    <!-- Section: Informasi Artikel -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                <i data-lucide="info" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Konten Artikel</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Detail isi postingan artikel</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{ 
            slugLocked: {{ isset($article) ? 'true' : 'true' }},
            slug: '{{ old('slug', $article->slug ?? '') }}',
            generateSlug(val) {
                if (!this.slugLocked) return;
                this.slug = val
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        }">
            <div class="md:col-span-2 form-group">
                <label for="title">{{ __('Judul Artikel') }}</label>
                <input type="text" id="title" name="title" value="{{ old('title', $article->title ?? '') }}" required
                    class="transition"
                    @input="generateSlug($event.target.value)"
                    placeholder="Contoh: Panduan Lengkap Fiqih Umrah">
                @error('title') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2 form-group">
                <div class="flex items-center justify-between mb-2">
                    <label for="slug" class="mb-0 font-bold text-xs text-slate-600 dark:text-slate-400 uppercase tracking-wider">{{ __('Slug URL / Link Artikel') }}</label>
                    <button type="button" @click="slugLocked = !slugLocked" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 transition flex items-center gap-1">
                        <span x-show="slugLocked" class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path></svg>
                            Edit Manual
                        </span>
                        <span x-show="!slugLocked" class="flex items-center gap-1" x-cloak>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path></svg>
                            Kunci Otomatis
                        </span>
                    </button>
                </div>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs text-slate-400 dark:text-slate-500 font-bold select-none">
                        /artikel/
                    </span>
                    <input type="text" id="slug" name="slug" x-model="slug" required
                        class="transition pl-[4.5rem]"
                        :readonly="slugLocked"
                        :class="slugLocked ? 'bg-slate-50 dark:bg-slate-800 text-slate-500 border-slate-200 dark:border-slate-700 cursor-not-allowed' : 'bg-white dark:bg-slate-700'"
                        placeholder="panduan-lengkap-fiqih-umrah">
                </div>
                <p class="mt-1 text-[11px] text-slate-400 dark:text-slate-500">
                    Alamat URL ramah mesin pencari (SEO-friendly URL). Otomatis dihasilkan dari judul saat diketik jika dikunci.
                </p>
                @error('slug') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="category">{{ __('Kategori') }}</label>
                <input type="text" id="category" name="category" value="{{ old('category', $article->category ?? '') }}" required
                    class="transition"
                    placeholder="Contoh: Panduan Umrah, Tips & Doa">
                @error('category') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="read_time">{{ __('Waktu Baca (Contoh: 5 Min)') }}</label>
                <input type="text" id="read_time" name="read_time" value="{{ old('read_time', $article->read_time ?? '') }}"
                    class="transition"
                    placeholder="Contoh: 5 Min">
                @error('read_time') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2 form-group">
                <label for="excerpt">{{ __('Kutipan Ringkas (Excerpt)') }}</label>
                <textarea id="excerpt" name="excerpt" rows="2"
                    class="transition"
                    placeholder="Tuliskan rangkuman artikel singkat untuk card preview...">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
                @error('excerpt') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2 form-group">
                <label for="content">{{ __('Isi Konten Artikel') }}</label>
                <textarea id="content" name="content" rows="8" required
                    class="transition"
                    placeholder="Tulis isi konten lengkap artikel dalam format teks / HTML...">{{ old('content', $article->content ?? '') }}</textarea>
                @error('content') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="border-t border-slate-100 dark:border-slate-700"></div>

    <!-- Section: Penulis & Info Publikasi -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-500 flex items-center justify-center shrink-0">
                <i data-lucide="user" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Penulis & Informasi</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Atur penulis, urutan, dan status aktif</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-group">
                <label for="author">{{ __('Nama Penulis') }}</label>
                <input type="text" id="author" name="author" value="{{ old('author', $article->author ?? '') }}" required
                    class="transition"
                    placeholder="Contoh: Ustadz Dr. H. Ahmad Fauzi">
                @error('author') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="author_role">{{ __('Jabatan Penulis') }}</label>
                <input type="text" id="author_role" name="author_role" value="{{ old('author_role', $article->author_role ?? '') }}"
                    class="transition"
                    placeholder="Contoh: Pembimbing Utama IZI Travel">
                @error('author_role') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="published_at">{{ __('Tanggal Terbit (Contoh: 12 Juni 2026)') }}</label>
                <input type="text" id="published_at" name="published_at" value="{{ old('published_at', $article->published_at ?? now()->translatedFormat('d F Y')) }}"
                    class="transition">
                @error('published_at') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="order">{{ __('Urutan Tampil') }}</label>
                <input type="number" id="order" name="order" min="0" value="{{ old('order', $article->order ?? 0) }}"
                    class="transition" placeholder="Contoh: 1">
                @error('order') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
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
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Media & Status</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Upload cover artikel dan status tampil</p>
            </div>
        </div>

        <div class="space-y-4">
            <!-- Toggle status -->
            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600">
                <div class="flex items-center gap-3">
                    <i data-lucide="eye" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                    <div>
                        <p class="text-sm font-bold text-slate-800 dark:text-white">Tampilkan di landing page</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Artikel akan terlihat oleh pengunjung</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer" x-data="{ checked: {{ old('is_active', $article->is_active ?? true) ? 'true' : 'false' }} }">
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
                preview: '{{ isset($article) ? $article->image_url : '' }}',
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
                            <img :src="preview" class="w-40 h-24 object-cover rounded-xl shadow-sm border border-slate-200 dark:border-slate-600">
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
                                <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">Drag & drop gambar cover di sini</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">atau klik untuk memilih file</p>
                            </div>
                        </div>
                    </template>

                    <input x-ref="fileInput" type="file" id="image" name="image" accept="image/*" class="hidden"
                           @change="handleFile($event.target.files[0])">
                </div>
                <p class="mt-1.5 text-[11px] text-slate-400 dark:text-slate-500">Format JPG/PNG/WEBP, maksimal 2MB.</p>
                @error('image') <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
    /* Styling for CKEditor 5 to integrate beautifully with our UI */
    .ck-editor__editable_inline {
        min-height: 350px;
        color: #1e293b !important;
        font-family: inherit;
    }
    .ck.ck-editor__main>.ck-editor__editable {
        background: #ffffff !important;
        border-bottom-left-radius: 0.75rem !important;
        border-bottom-right-radius: 0.75rem !important;
        border-color: #cbd5e1 !important;
    }
    .ck.ck-toolbar {
        background: #f8fafc !important;
        border-top-left-radius: 0.75rem !important;
        border-top-right-radius: 0.75rem !important;
        border-color: #cbd5e1 !important;
    }
    .dark .ck.ck-editor__main>.ck-editor__editable {
        background: #1e293b !important;
        color: #f8fafc !important;
        border-color: #475569 !important;
    }
    .dark .ck.ck-toolbar {
        background: #0f172a !important;
        border-color: #475569 !important;
    }
    .dark .ck.ck-toolbar .ck-button {
        color: #f8fafc !important;
    }
    .dark .ck.ck-toolbar .ck-button:hover {
        background: #1e293b !important;
    }
</style>
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const textarea = document.querySelector('#content');
        if (textarea) {
            ClassicEditor
                .create(textarea, {
                    toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ]
                })
                .then(editor => {
                    const form = textarea.closest('form');
                    if (form) {
                        form.addEventListener('submit', () => {
                            textarea.value = editor.getData();
                        });
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        }
    });
</script>
@endpush
