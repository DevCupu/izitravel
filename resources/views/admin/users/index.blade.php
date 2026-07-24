<x-admin-layout :title="__('Pengguna')">
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
            {{ __('Pengguna') }}
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
            {{ __('Daftar seluruh pengguna terdaftar di IZI Travel.') }}
        </p>
    </x-slot>

    <div class="space-y-4">
        <!-- Header + Search -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 animate-fade-in-up">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold px-3 py-1.5 rounded-lg">
                    <i data-lucide="users" class="w-3.5 h-3.5"></i>
                    {{ $users->total() }} pengguna
                </span>
            </div>

            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 dark:text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="{{ __('Cari nama atau email...') }}"
                        class="w-full sm:w-64 pl-10 pr-4 py-2.5 text-sm border-slate-200 dark:border-slate-600 dark:bg-slate-800 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm"
                    >
                </div>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-sm text-white transition-all duration-150 shadow-lg shadow-blue-500/20">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </button>
                @if ($search)
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl font-bold text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                @endif
            </form>
        </div>

        <!-- Users table -->
        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden animate-fade-in-up stagger-2">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 bg-slate-50/80 dark:bg-slate-900/50">
                            <th class="px-5 py-3">{{ __('Nama') }}</th>
                            <th class="px-5 py-3">{{ __('Email') }}</th>
                            <th class="px-5 py-3">{{ __('Role') }}</th>
                            <th class="px-5 py-3">{{ __('Bergabung') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/30 transition">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="relative shrink-0">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br {{ $user->role === 'admin' ? 'from-amber-400 to-orange-500' : 'from-blue-500 to-indigo-600' }} flex items-center justify-center text-white text-xs font-bold">
                                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <span class="font-semibold text-slate-800 dark:text-white">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">{{ $user->email }}</td>
                                <td class="px-5 py-3.5">
                                    @if ($user->role === 'admin')
                                        <span class="inline-flex items-center gap-1 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-[11px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                            <i data-lucide="shield-check" class="w-3 h-3"></i>
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[11px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                            <i data-lucide="user" class="w-3 h-3"></i>
                                            User
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400 text-xs">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                            <i data-lucide="users" class="w-7 h-7 text-slate-300 dark:text-slate-500"></i>
                                        </div>
                                        <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('Tidak ada pengguna ditemukan.') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
