<x-admin-layout :title="__('Detail Item Galeri')">
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.galleries.index', ['album' => trim($gallery->category_label) ?: 'Umum']) }}" class="p-1.5 rounded-lg text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">{{ __('Detail Item Galeri') }}</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">{{ __('Lihat rincian lengkap foto atau video dokumentasi.') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl animate-fade-in-up">
        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
            <!-- Title & Album Info -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-700 pb-5">
                <div class="space-y-1">
                    <span class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">
                        <i data-lucide="{{ $gallery->type === 'video' ? 'video' : 'image' }}" class="w-3.5 h-3.5"></i>
                        {{ $gallery->type === 'video' ? 'Video Dokumentasi' : 'Foto Kegiatan' }}
                    </span>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white leading-snug">{{ $gallery->title }}</h3>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-400 dark:text-slate-500">Album:</span>
                    <a href="{{ route('admin.galleries.index', ['album' => trim($gallery->category_label) ?: 'Umum']) }}" 
                       class="inline-flex items-center gap-1.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-white text-xs font-bold px-3 py-1.5 rounded-xl transition">
                        <i data-lucide="folder" class="w-3.5 h-3.5 text-slate-400"></i>
                        {{ trim($gallery->category_label) ?: 'Umum' }}
                    </a>
                </div>
            </div>

            <!-- Media Preview Card -->
            <div class="relative bg-slate-50 dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-700 flex items-center justify-center p-2">
                @if ($gallery->type === 'video')
                    @php
                        $embedUrl = '';
                        if ($gallery->video_platform === 'youtube') {
                            $embedUrl = "https://www.youtube.com/embed/{$gallery->video_id}";
                        } elseif ($gallery->video_platform === 'instagram') {
                            $embedUrl = "https://www.instagram.com/reel/{$gallery->video_id}/embed";
                        } elseif ($gallery->video_platform === 'vimeo') {
                            $embedUrl = "https://player.vimeo.com/video/{$gallery->video_id}";
                        }
                    @endphp

                    @if ($embedUrl)
                        <div class="w-full aspect-video rounded-xl overflow-hidden shadow-inner">
                            <iframe src="{{ $embedUrl }}" 
                                    class="w-full h-full border-0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    allowfullscreen>
                            </iframe>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i data-lucide="video-off" class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-3"></i>
                            <p class="text-sm text-slate-400">Link video tidak valid.</p>
                        </div>
                    @endif
                @else
                    @if ($gallery->image_url)
                        <div class="w-full max-h-[480px] overflow-hidden rounded-xl">
                            <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" class="w-full h-full object-contain mx-auto">
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i data-lucide="image-off" class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-3"></i>
                            <p class="text-sm text-slate-400">Gambar tidak tersedia.</p>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Detailed Grid Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50/50 dark:bg-slate-800/50 p-4 sm:p-5 rounded-2xl border border-slate-100 dark:border-slate-700">
                <div class="space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-2">
                        <span class="text-xs text-slate-400 dark:text-slate-500">Urutan Tampil</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-white">#{{ $gallery->order }}</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-2">
                        <span class="text-xs text-slate-400 dark:text-slate-500">Status Publikasi</span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $gallery->is_active ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400' }}">
                            <i data-lucide="{{ $gallery->is_active ? 'eye' : 'eye-off' }}" class="w-3 h-3"></i>
                            {{ $gallery->is_active ? 'Aktif / Tampil' : 'Nonaktif / Sembunyi' }}
                        </span>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-2">
                        <span class="text-xs text-slate-400 dark:text-slate-500">Tanggal Ditambahkan</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-350">
                            {{ $gallery->created_at ? $gallery->created_at->translatedFormat('d F Y, H:i') : '-' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-2">
                        <span class="text-xs text-slate-400 dark:text-slate-500">Terakhir Diperbarui</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-350">
                            {{ $gallery->updated_at ? $gallery->updated_at->translatedFormat('d F Y, H:i') : '-' }}
                        </span>
                    </div>
                </div>

                @if ($gallery->type === 'video')
                    <div class="sm:col-span-2 space-y-3 pt-2">
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-2">
                            <span class="text-xs text-slate-400 dark:text-slate-500">Platform Video</span>
                            <span class="text-sm font-bold text-slate-800 dark:text-white uppercase">{{ $gallery->video_platform }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-2">
                            <span class="text-xs text-slate-400 dark:text-slate-500">ID Video / Shortcode</span>
                            <span class="text-sm font-mono font-semibold text-slate-700 dark:text-slate-350 select-all">{{ $gallery->video_id }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons Footer -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('admin.galleries.edit', $gallery) }}" 
                       class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-sm text-white transition-all duration-150 shadow-lg shadow-blue-500/20">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                        {{ __('Ubah Item') }}
                    </a>
                    <form method="POST" action="{{ route('admin.galleries.destroy', $gallery) }}" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                                @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Hapus media &quot;{{ $gallery->title }}&quot; ini secara permanen?' })"
                                class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-5 py-2.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 active:scale-[0.97] border border-rose-200 dark:border-rose-800 rounded-xl font-bold text-sm transition-all duration-150">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            {{ __('Hapus Item') }}
                        </button>
                    </form>
                </div>
                <a href="{{ route('admin.galleries.index', ['album' => trim($gallery->category_label) ?: 'Umum']) }}" 
                   class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-2.5 rounded-xl font-bold text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                    {{ __('Kembali') }}
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
