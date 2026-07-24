<x-admin-layout :title="$mode === 'media' ? __('Album: ') . $albumName : __('Galeri & Album')">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            @if ($mode === 'media')
                <a href="{{ route('admin.galleries.index') }}" 
                   class="p-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-xl text-slate-700 dark:text-white transition active:scale-95 shadow-sm"
                   title="{{ __('Kembali ke Daftar Album') }}">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
            @endif
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight flex items-center gap-2">
                    @if ($mode === 'media')
                        <span>{{ __('Album:') }}</span>
                        <span class="text-blue-600 dark:text-blue-400">{{ $albumName }}</span>
                        <button type="button" 
                                @click="$dispatch('open-rename-album', { name: @js($albumName) })"
                                class="p-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition active:scale-95 shrink-0" 
                                title="Ubah Nama Album">
                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                        </button>
                    @else
                        {{ __('Galeri & Album') }}
                    @endif
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
                    @if ($mode === 'media')
                        {{ __('Kelola foto dan video kegiatan yang ada di dalam album ini.') }}
                    @else
                        {{ __('Kelola album dokumentasi perjalanan jamaah IZI Travel.') }}
                    @endif
                </p>
            </div>
        </div>
    </x-slot>

    <div x-data="{ showRenameModal: false, renameOldName: '', renameNewName: '' }"
         @open-rename-album.window="renameOldName = $event.detail.name; renameNewName = $event.detail.name; showRenameModal = true;"
         class="space-y-6">
        
        <!-- Top bar actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 animate-fade-in-up">
            <div class="flex items-center gap-3">
                @if ($mode === 'media')
                    <span class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">
                        <i data-lucide="image" class="w-3.5 h-3.5"></i>
                        {{ $galleries->total() }} Item Media
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">
                        <i data-lucide="folder" class="w-3.5 h-3.5"></i>
                        {{ $albums->count() }} Album
                    </span>
                @endif
            </div>

            @if ($mode === 'media')
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <form method="POST" action="{{ route('admin.galleries.delete-album') }}" class="inline w-full sm:w-auto">
                        @csrf
                        <input type="hidden" name="album_name" value="{{ $albumName }}">
                        <button type="button"
                                @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Hapus album &quot;{{ $albumName }}&quot; beserta seluruh foto dan video di dalamnya?' })"
                                class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-2.5 bg-white text-slate-700 hover:text-red-650 hover:bg-red-50 dark:bg-slate-800 dark:text-slate-300 dark:hover:text-red-400 dark:hover:bg-red-950/20 border border-slate-200 dark:border-slate-700 active:scale-[0.97] rounded-xl font-bold text-sm transition-all duration-150">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            {{ __('Hapus Album') }}
                        </button>
                    </form>
                    <a href="{{ route('admin.galleries.create', ['album' => $albumName]) }}" 
                       class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-sm text-white transition-all duration-150 shadow-lg shadow-blue-500/20">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        {{ __('Tambah Media') }}
                    </a>
                </div>
            @else
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('admin.galleries.create') }}" 
                       class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-sm text-white transition-all duration-150 shadow-lg shadow-blue-500/20">
                        <i data-lucide="folder-plus" class="w-4 h-4"></i>
                        {{ __('Buat Album Baru') }}
                    </a>
                </div>
            @endif
        </div>


        @if ($mode === 'albums')
            <!-- Search Albums -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-4 animate-fade-in-up shadow-sm">
                <form method="GET" action="{{ route('admin.galleries.index') }}" class="flex flex-col sm:flex-row gap-3 items-end">
                    <div class="flex-1 w-full">
                        <label for="search" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Cari Nama Album</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </span>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   class="w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition" 
                                   placeholder="Cari album perjalanan...">
                        </div>
                    </div>
                    <div class="flex gap-2 w-full sm:w-auto">
                        <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-slate-900 dark:bg-slate-700 text-white rounded-xl font-bold text-sm hover:bg-slate-800 dark:hover:bg-slate-600 transition active:scale-[0.98]">
                            Cari
                        </button>
                        @if(request()->filled('search'))
                            <a href="{{ route('admin.galleries.index') }}" class="w-full sm:w-auto px-5 py-2 bg-slate-100 dark:bg-slate-900 text-slate-650 dark:text-slate-400 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-800 transition active:scale-[0.98] text-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        @elseif ($mode === 'media')
            <!-- Inside Album: Filter Bar -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-4 animate-fade-in-up shadow-sm">
                <form method="GET" action="{{ route('admin.galleries.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                    <input type="hidden" name="album" value="{{ $albumName }}">
                    
                    <!-- Search -->
                    <div class="flex-1 w-full">
                        <label for="search" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Cari Media</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </span>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   class="w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-888 dark:text-white rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition" 
                                   placeholder="Cari judul media...">
                        </div>
                    </div>

                    <!-- Type Filter -->
                    <div class="w-full md:w-48">
                        <label for="type" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Tipe Media</label>
                        <select name="type" id="type" 
                                class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">Semua Tipe</option>
                            <option value="photo" {{ request('type') === 'photo' ? 'selected' : '' }}>Foto</option>
                            <option value="video" {{ request('type') === 'video' ? 'selected' : '' }}>Video</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="submit" class="w-full md:w-auto px-5 py-2 bg-slate-900 dark:bg-slate-700 text-white rounded-xl font-bold text-sm hover:bg-slate-800 dark:hover:bg-slate-600 transition active:scale-[0.98]">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'type']))
                            <a href="{{ route('admin.galleries.index', ['album' => $albumName]) }}" class="w-full md:w-auto px-5 py-2 bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-800 transition active:scale-[0.98] text-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        @endif

        @if ($mode === 'media')
            <!-- Inside Album / All Media: Table Layout -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 overflow-hidden shadow-sm animate-fade-in-up">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-700/30 border-b border-slate-100 dark:border-slate-700 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                <th class="px-5 py-4 w-20 text-center">Urutan</th>
                                <th class="px-5 py-4 w-28">Preview</th>
                                <th class="px-5 py-4">Judul Media</th>
                                <th class="px-5 py-4 w-36">Tipe</th>
                                <th class="px-5 py-4 w-44">Album</th>
                                <th class="px-5 py-4 w-32 text-center">Status</th>
                                <th class="px-5 py-4 w-36 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-sm">
                            @forelse ($galleries as $gallery)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors">
                                    <!-- Urutan -->
                                    <td class="px-5 py-4 text-center font-bold text-slate-500 dark:text-slate-400">
                                        #{{ $gallery->order }}
                                    </td>
                                    
                                    <!-- Preview Thumbnail -->
                                    <td class="px-5 py-4">
                                        <div class="relative w-16 h-10 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700 flex items-center justify-center border border-slate-200/50 dark:border-slate-700">
                                            @if ($gallery->image_url)
                                                <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover">
                                            @else
                                                <i data-lucide="image" class="w-4 h-4 text-slate-300 dark:text-slate-500"></i>
                                            @endif
                                            
                                            <!-- Icon Badge Overlay -->
                                            <span class="absolute bottom-1 right-1 p-0.5 bg-slate-950/70 text-white rounded text-[8px] flex items-center justify-center">
                                                <i data-lucide="{{ $gallery->type === 'video' ? 'play' : 'image' }}" class="w-2.5 h-2.5"></i>
                                            </span>
                                        </div>
                                    </td>
                                    
                                    <!-- Judul -->
                                    <td class="px-5 py-4 font-bold text-slate-800 dark:text-white">
                                        <a href="{{ route('admin.galleries.show', $gallery) }}" class="hover:text-blue-650 transition truncate block max-w-xs md:max-w-md lg:max-w-lg" title="{{ $gallery->title }}">
                                            {{ $gallery->title }}
                                        </a>
                                    </td>
                                    
                                    <!-- Tipe -->
                                    <td class="px-5 py-4">
                                        @if ($gallery->type === 'video')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-650 dark:bg-red-950/20 dark:text-red-400 border border-red-100/55 dark:border-red-900/30">
                                                <i data-lucide="video" class="w-3.5 h-3.5"></i>
                                                {{ ucfirst($gallery->video_platform ?: 'Video') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-600 dark:bg-blue-950/20 dark:text-blue-400 border border-blue-100/55 dark:border-blue-900/30">
                                                <i data-lucide="image" class="w-3.5 h-3.5"></i>
                                                Foto
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Album -->
                                    <td class="px-5 py-4 text-slate-650 dark:text-slate-350 font-bold truncate max-w-[150px]">
                                        📁 {{ trim($gallery->category_label) ?: 'Umum' }}
                                    </td>
                                    
                                    <!-- Status -->
                                    <td class="px-5 py-4 text-center">
                                        @if ($gallery->is_active)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-600 dark:bg-emerald-950/20 dark:text-emerald-400 border border-emerald-100/55 dark:border-emerald-900/30">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-50 text-rose-600 dark:bg-rose-950/20 dark:text-rose-400 border border-rose-100/55 dark:border-rose-900/30">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Aksi -->
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('admin.galleries.show', $gallery) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-650 hover:bg-blue-50 dark:hover:bg-blue-950/30 transition active:scale-95" title="{{ __('Detail') }}">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/30 transition active:scale-95" title="{{ __('Ubah') }}">
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.galleries.destroy', $gallery) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Hapus media &quot;{{ $gallery->title }}&quot; dari album?' })"
                                                        class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 transition active:scale-95"
                                                        title="{{ __('Hapus') }}">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-12 text-center text-slate-400 dark:text-slate-550 font-medium">
                                        <div class="flex flex-col items-center gap-2">
                                            <i data-lucide="image-off" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                            <p>{{ __('Belum ada foto atau video di sini.') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($galleries->hasPages())
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 px-5 py-4 shadow-sm">
                    {{ $galleries->links() }}
                </div>
            @endif
        @else
            <!-- Album List View (Folder Mode) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 animate-fade-in-up stagger-2">
                @forelse ($albums as $album)
                    <div class="group bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 p-4 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col gap-4 relative overflow-hidden">
                        <!-- Clickable link wrapper for the folder area -->
                        <a href="{{ route('admin.galleries.index', ['album' => $album->name]) }}" class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-slate-50 dark:bg-slate-900 border border-slate-200/40 dark:border-slate-700 flex items-center justify-center shadow-inner">
                            @if ($album->cover_url)
                                <img src="{{ $album->cover_url }}" alt="{{ $album->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="flex flex-col items-center gap-2 text-slate-300 dark:text-slate-655">
                                    <i data-lucide="folder" class="w-12 h-12 stroke-[1.5]"></i>
                                </div>
                            @endif
                            
                            <!-- Folder badge -->
                            <div class="absolute top-3 left-3 bg-slate-950/80 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider flex items-center gap-1 shadow-sm">
                                <i data-lucide="folder" class="w-3 h-3"></i>
                                Album
                            </div>
                            
                            <!-- Count badge inside folder -->
                            <div class="absolute bottom-3 right-3 bg-blue-600 text-white text-xs font-black px-3.5 py-1.5 rounded-xl shadow-md transition group-hover:bg-blue-700">
                                {{ $album->items_count }} Media
                            </div>
                        </a>
                        
                        <!-- Album Info -->
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex flex-col min-w-0 flex-1">
                                <a href="{{ route('admin.galleries.index', ['album' => $album->name]) }}" class="font-extrabold text-slate-800 dark:text-white text-base hover:text-blue-600 transition truncate block">
                                    {{ $album->name }}
                                </a>
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    {{ \Carbon\Carbon::parse($album->last_updated)->diffForHumans() }}
                                </span>
                            </div>
                            <div class="flex gap-1 shrink-0">
                                <button type="button" 
                                        @click="$dispatch('open-rename-album', { name: @js($album->name) })"
                                        class="p-1.5 rounded-lg bg-slate-50 dark:bg-slate-750/50 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition active:scale-95" 
                                        title="Ubah Nama Album">
                                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.galleries.delete-album') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="album_name" value="{{ $album->name }}">
                                    <button type="button"
                                            @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Hapus album &quot;{{ $album->name }}&quot; beserta seluruh foto dan video di dalamnya?' })"
                                            class="p-1.5 rounded-lg bg-slate-50 dark:bg-slate-750/50 text-slate-400 hover:text-red-650 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition active:scale-95"
                                            title="Hapus Album">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="content-card bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 px-6 py-16 text-center shadow-sm">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-50 dark:bg-slate-700 flex items-center justify-center">
                                    <i data-lucide="folder" class="w-7 h-7 text-slate-300 dark:text-slate-550"></i>
                                </div>
                                <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('Belum ada album atau galeri kegiatan.') }}</p>
                                <a href="{{ route('admin.galleries.create') }}" class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 transition inline-flex items-center gap-1">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                    Buat Album Pertama Anda
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            @if (isset($albumsData) && $albumsData->hasPages())
                <div class="mt-6">
                    {{ $albumsData->links() }}
                </div>
            @endif
        @endif

        <!-- Modal Rename Album -->
        <div x-show="showRenameModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showRenameModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 transition-opacity backdrop-blur-sm" @click="showRenameModal = false"></div>

                <!-- Modal panel -->
                <div x-show="showRenameModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 dark:border-slate-700">
                    
                    <form method="POST" action="{{ route('admin.galleries.rename-album') }}" class="p-6 space-y-4">
                        @csrf
                        <input type="hidden" name="old_name" :value="renameOldName">

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                                <i data-lucide="edit-3" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">Ubah Nama Album</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Ganti nama album untuk seluruh media di dalamnya.</p>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="new_name" class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Nama Album Baru</label>
                            <input type="text" id="new_name" name="new_name" x-model="renameNewName" required
                                   class="w-full px-4 py-2.5 text-sm border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm transition">
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-700">
                            <button type="button" @click="showRenameModal = false" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-500/20 transition active:scale-95">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
