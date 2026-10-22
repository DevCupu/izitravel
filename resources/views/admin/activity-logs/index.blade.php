<x-admin-layout :title="__('Log Aktivitas')">
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">Log Aktivitas</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Riwayat aksi admin dan CS di aplikasi.</p>
    </x-slot>

    @php
        $actionLabels = [
            'create' => 'Tambah', 'update' => 'Ubah', 'delete' => 'Hapus',
            'archive' => 'Arsip', 'restore' => 'Pulihkan', 'login' => 'Masuk',
            'logout' => 'Keluar', 'other' => 'Lainnya',
        ];
        $actionColors = [
            'create' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
            'update' => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
            'delete' => 'bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',
            'archive' => 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
            'restore' => 'bg-teal-50 text-teal-700 dark:bg-teal-900/30 dark:text-teal-300',
            'login' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300',
            'logout' => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
            'other' => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
        ];
    @endphp

    <div class="space-y-4">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="content-card bg-white dark:bg-slate-800 p-4 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-3 items-end">
            <div class="form-group">
                <label for="user_id">Pengguna</label>
                <select id="user_id" name="user_id">
                    <option value="">Semua pengguna</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(($filters['user_id'] ?? null) == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="action">Aksi</label>
                <select id="action" name="action">
                    <option value="">Semua aksi</option>
                    @foreach ($actionLabels as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['action'] ?? null) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="from">Dari tanggal</label>
                <input type="date" id="from" name="from" value="{{ $filters['from'] ?? '' }}">
            </div>
            <div class="form-group">
                <label for="to">Sampai tanggal</label>
                <input type="date" id="to" name="to" value="{{ $filters['to'] ?? '' }}">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 text-sm font-bold text-white hover:bg-blue-700">
                    <i data-lucide="filter" class="w-4 h-4"></i>Filter
                </button>
                <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl px-3 text-sm font-semibold text-slate-500 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Reset</a>
            </div>
        </form>

        <div class="content-card bg-white dark:bg-slate-800 overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-700">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Riwayat aktivitas</h3>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ number_format($logs->total(), 0, ',', '.') }} catatan ditemukan</p>
                </div>
                <i data-lucide="history" class="w-5 h-5 text-slate-400" aria-hidden="true"></i>
            </div>
            <div class="overflow-x-auto">
                <table data-record-view class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                            <th>Bagian dan referensi</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                <td class="whitespace-nowrap px-5 py-3">
                                    <div class="font-semibold text-slate-800 dark:text-white">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $log->created_at->format('H:i:s') }}</div>
                                </td>
                                <td class="px-5 py-3 font-semibold text-slate-800 dark:text-white">{{ $log->actor_name }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex rounded-md px-2 py-1 text-xs font-bold {{ $actionColors[$log->action] ?? $actionColors['other'] }}">{{ $actionLabels[$log->action] ?? 'Lainnya' }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="font-semibold text-slate-800 dark:text-white">{{ $log->subject }}@if ($log->subject_id) <span class="font-normal text-slate-500 dark:text-slate-400">#{{ $log->subject_id }}</span>@endif</div>
                                    @if ($log->route_name)
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $log->route_name }}</div>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $log->ip_address ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-slate-500 dark:text-slate-400">Belum ada aktivitas untuk filter ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($logs->hasPages())
                <div class="border-t border-slate-200 px-5 py-4 dark:border-slate-700">{{ $logs->links() }}</div>
            @endif
        </div>
    </div>
</x-admin-layout>
