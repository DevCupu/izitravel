<x-admin-layout :title="__('Daftar Tim Kami')">
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4 w-full">
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
                    {{ __('Daftar Anggota Tim') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
                    {{ __('Kelola profil founder dan tim manajemen di balik layar IZI Travel.') }}
                </p>
            </div>
            <a href="{{ route('admin.teams.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-xs text-white transition-all duration-150 shadow-lg shadow-blue-500/20">
                <i data-lucide="plus" class="w-4 h-4"></i>
                {{ __('Tambah Anggota') }}
            </a>
        </div>
    </x-slot>

    <div class="space-y-4 animate-fade-in">
        <div class="flex justify-start sm:justify-end">
            @include('admin.partials._search', ['action' => route('admin.teams.index'), 'value' => $search, 'placeholder' => __('Cari nama atau jabatan...')])
        </div>
        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 bg-slate-50/80 dark:bg-slate-900/50 table-header-bg border-b border-slate-100 dark:border-slate-700/50">
                            <th class="px-6 py-4">{{ __('Foto') }}</th>
                            <th class="px-6 py-4">{{ __('Nama / Inisial') }}</th>
                            <th class="px-6 py-4">{{ __('Jabatan') }}</th>
                            <th class="px-6 py-4">{{ __('Status') }}</th>
                            <th class="px-6 py-4">{{ __('Urutan') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse ($teams as $t)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition">
                                <td class="px-6 py-4 shrink-0">
                                    @if($t->image_url)
                                        <img src="{{ $t->image_url }}" alt="{{ $t->name }}" class="w-12 h-12 rounded-full object-cover border border-slate-100 dark:border-slate-700 shadow-sm">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center text-white font-extrabold text-sm border border-slate-100 dark:border-slate-700">
                                            {{ $t->initial ?? '...' }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $t->name }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5 font-bold uppercase tracking-wider">Inisial: {{ $t->initial ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-250">{{ $t->role }}</td>
                                <td class="px-6 py-4">
                                    @if ($t->is_active)
                                        <span class="inline-flex items-center gap-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                            Tampil
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-slate-50 dark:bg-slate-700/30 text-slate-400 dark:text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                            Sembunyi
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-450 font-semibold text-xs">{{ $t->order }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.teams.edit', $t->id) }}" class="p-2 rounded-lg text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition" title="Edit">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>

                                        <form x-data method="POST" action="{{ route('admin.teams.destroy', $t->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Apakah Anda yakin ingin menghapus anggota tim ini?' })"
                                                    class="p-2 rounded-lg text-slate-400 dark:text-slate-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50/50 dark:hover:bg-red-900/10 transition" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-900 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                        <i data-lucide="users" class="w-6 h-6"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-500 dark:text-slate-400">{{ __('Belum ada anggota tim.') }}</p>
                                    <a href="{{ route('admin.teams.create') }}" class="inline-flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400 font-bold mt-2 hover:underline">
                                        {{ __('Tambah Anggota Pertama') }}
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($teams->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-900/10">
                    {{ $teams->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
