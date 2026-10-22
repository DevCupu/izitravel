<x-dynamic-component :component="$isAdmin ? 'admin-layout' : 'cs-layout'" title="Beranda CRM">
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ $isAdmin ? 'Beranda CRM' : 'Halo, '.auth()->user()->name }}</h1>
            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ now('Asia/Makassar')->locale('id')->translatedFormat('l, d F Y') }} · WITA</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-5">
        <section aria-labelledby="crm-focus-title" class="ui-card border-l-4 border-l-[#3166b1] p-5 sm:p-6">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="min-w-0">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.13em] text-[#3166b1] dark:text-blue-300">Prioritas hari ini</p>
                    <h2 id="crm-focus-title" class="mt-1.5 text-xl font-bold leading-snug text-[#102b52] dark:text-white sm:text-2xl">
                        @if ($overdueCount > 0)
                            {{ $overdueCount }} tugas melewati jadwal
                        @elseif ($dueTodayCount > 0)
                            {{ $dueTodayCount }} tugas perlu ditangani hari ini
                        @else
                            Tidak ada tugas mendesak
                        @endif
                    </h2>
                    <p class="mt-1.5 max-w-2xl text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                        @if ($overdueCount > 0)
                            Mulai dari tugas yang terlambat, lalu lanjutkan jadwal hari ini.
                        @elseif ($dueTodayCount > 0)
                            Buka tugas terdekat dan catat hasil tindak lanjut pada lead.
                        @else
                            Periksa lead baru atau catat calon jemaah yang sudah menghubungi tim.
                        @endif
                    </p>
                </div>
                <div class="flex shrink-0 flex-wrap gap-2">
                    @if ($overdueCount > 0 || $dueTodayCount > 0)
                        <a href="{{ route($routePrefix.'tasks', ['period' => $overdueCount > 0 ? 'overdue' : 'today']) }}" class="ui-button-primary"><i data-lucide="list-checks" class="size-4" aria-hidden="true"></i>Lihat tugas</a>
                    @else
                        <a href="{{ route($routePrefix.'leads.index', ['stage' => 'baru']) }}" class="ui-button-primary"><i data-lucide="users" class="size-4" aria-hidden="true"></i>Lihat lead baru</a>
                    @endif
                    <a href="{{ route($routePrefix.'leads.create') }}" class="ui-button-secondary"><i data-lucide="plus" class="size-4" aria-hidden="true"></i>Catat lead</a>
                </div>
            </div>
        </section>

        <section aria-label="Ringkasan CRM" class="grid grid-cols-2 gap-3 xl:grid-cols-4">
            <a href="{{ route($routePrefix.'tasks', ['period' => 'overdue']) }}" class="ui-card group flex min-h-28 flex-col justify-between p-4 transition-colors hover:border-red-300 dark:hover:border-red-700">
                <span class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-300"><span class="size-2 rounded-full bg-red-600" aria-hidden="true"></span>Tugas terlambat</span>
                <span class="flex items-end justify-between"><strong class="text-3xl font-extrabold tabular-nums text-red-700 dark:text-red-400">{{ $overdueCount }}</strong><i data-lucide="arrow-up-right" class="size-4 text-slate-400 group-hover:text-red-700" aria-hidden="true"></i></span>
            </a>
            <a href="{{ route($routePrefix.'tasks', ['period' => 'today']) }}" class="ui-card group flex min-h-28 flex-col justify-between p-4 transition-colors hover:border-amber-300 dark:hover:border-amber-700">
                <span class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-300"><span class="size-2 rounded-full bg-amber-500" aria-hidden="true"></span>Tugas hari ini</span>
                <span class="flex items-end justify-between"><strong class="text-3xl font-extrabold tabular-nums text-amber-700 dark:text-amber-300">{{ $dueTodayCount }}</strong><i data-lucide="arrow-up-right" class="size-4 text-slate-400 group-hover:text-amber-700" aria-hidden="true"></i></span>
            </a>
            <a href="{{ route($routePrefix.'leads.index', ['stage' => 'baru']) }}" class="ui-card group flex min-h-28 flex-col justify-between p-4 transition-colors hover:border-blue-300 dark:hover:border-blue-700">
                <span class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-300"><span class="size-2 rounded-full bg-[#3166b1]" aria-hidden="true"></span>Lead baru</span>
                <span class="flex items-end justify-between"><strong class="text-3xl font-extrabold tabular-nums text-[#3166b1] dark:text-blue-300">{{ $newCount }}</strong><i data-lucide="arrow-up-right" class="size-4 text-slate-400 group-hover:text-[#3166b1]" aria-hidden="true"></i></span>
            </a>
            <div class="ui-card flex min-h-28 flex-col justify-between p-4">
                <span class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-300"><span class="size-2 rounded-full bg-emerald-600" aria-hidden="true"></span>Booking bulan ini</span>
                <strong class="text-3xl font-extrabold tabular-nums text-emerald-700 dark:text-emerald-400">{{ $wonThisMonth }}</strong>
            </div>
        </section>

        <div class="grid items-start gap-5 xl:grid-cols-[minmax(0,1.5fr)_minmax(320px,1fr)]">
            <section aria-labelledby="crm-tasks-heading" class="ui-card min-w-0">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-5 py-4 dark:border-slate-700 sm:px-6">
                    <div><h2 id="crm-tasks-heading" class="ui-section-title">Tindak lanjut</h2><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Tugas terdekat yang membutuhkan tindakan.</p></div>
                    <a href="{{ route($routePrefix.'tasks') }}" class="text-sm font-bold text-[#3166b1] hover:underline dark:text-blue-300">Semua tugas</a>
                </div>
                @if ($overdueCount === 0 && $dueTodayCount === 0)
                    <div class="px-5 py-9 text-center sm:px-6">
                        <span class="mx-auto flex size-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300"><i data-lucide="circle-check" class="size-5" aria-hidden="true"></i></span>
                        <p class="mt-3 text-sm font-bold text-slate-900 dark:text-white">Tidak ada tugas mendesak</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Tugas berikutnya bisa dilihat di daftar tugas.</p>
                    </div>
                @else
                <div class="divide-y divide-slate-200 dark:divide-slate-700">
                    <div class="px-5 py-4 sm:px-6">
                        <div class="mb-3 flex items-center justify-between gap-2">
                            <h3 class="flex items-center gap-2 text-sm font-bold text-slate-900 dark:text-white"><span class="size-2 rounded-full bg-red-600" aria-hidden="true"></span>Terlambat <span class="font-normal text-slate-500 dark:text-slate-400">({{ $overdueCount }})</span></h3>
                            @if ($overdueCount > $overdue->count())<a href="{{ route($routePrefix.'tasks', ['period' => 'overdue']) }}" class="text-xs font-bold text-[#3166b1] hover:underline dark:text-blue-300">Lihat semua</a>@endif
                        </div>
                        @forelse ($overdue as $task)
                            @include('crm._dashboard-task', ['task' => $task, 'isOverdue' => true])
                        @empty
                            <p class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-500 dark:bg-slate-900/40 dark:text-slate-400">Tidak ada tugas terlambat.</p>
                        @endforelse
                    </div>
                    <div class="px-5 py-4 sm:px-6">
                        <div class="mb-3 flex items-center justify-between gap-2">
                            <h3 class="flex items-center gap-2 text-sm font-bold text-slate-900 dark:text-white"><span class="size-2 rounded-full bg-amber-500" aria-hidden="true"></span>Hari ini <span class="font-normal text-slate-500 dark:text-slate-400">({{ $dueTodayCount }})</span></h3>
                            @if ($dueTodayCount > $dueToday->count())<a href="{{ route($routePrefix.'tasks', ['period' => 'today']) }}" class="text-xs font-bold text-[#3166b1] hover:underline dark:text-blue-300">Lihat semua</a>@endif
                        </div>
                        @forelse ($dueToday as $task)
                            @include('crm._dashboard-task', ['task' => $task, 'isOverdue' => false])
                        @empty
                            <p class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-500 dark:bg-slate-900/40 dark:text-slate-400">Belum ada tugas yang dijadwalkan hari ini.</p>
                        @endforelse
                    </div>
                </div>
                @endif
            </section>

            <section aria-labelledby="crm-leads-heading" class="ui-card min-w-0">
                <div class="flex items-center justify-between gap-2 border-b border-slate-200 px-5 py-4 dark:border-slate-700">
                    <div><h2 id="crm-leads-heading" class="ui-section-title">Lead terbaru</h2><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Perkembangan calon jemaah yang baru dicatat.</p></div>
                    <a href="{{ route($routePrefix.'leads.index') }}" class="shrink-0 text-sm font-bold text-[#3166b1] hover:underline dark:text-blue-300">Semua lead</a>
                </div>
                @forelse ($recentLeads as $lead)
                    <a href="{{ route($routePrefix.'leads.show', $lead) }}" class="group flex items-start justify-between gap-3 border-b border-slate-100 px-5 py-4 transition-colors hover:bg-slate-50 focus-visible:outline-offset-[-3px] last:border-b-0 dark:border-slate-700 dark:hover:bg-slate-700/40">
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-bold text-slate-900 group-hover:text-[#3166b1] dark:text-white dark:group-hover:text-blue-300">{{ $lead->display_name }}</span>
                            <span class="mt-1 block truncate text-xs text-slate-500 dark:text-slate-400">{{ $lead->package?->name ?? 'Paket belum dipilih' }}@if ($isAdmin) · PIC {{ $lead->cs?->name ?? 'Belum ditentukan' }}@endif</span>
                        </span>
                        <span class="shrink-0 rounded-md px-2 py-1 text-[11px] font-bold {{ $lead->stage === 'berhasil' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300' : ($lead->stage === 'gagal' ? 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300' : 'bg-blue-50 text-[#3166b1] dark:bg-blue-900/30 dark:text-blue-300') }}">{{ $stageLabels[$lead->stage] ?? $lead->stage }}</span>
                    </a>
                @empty
                    <div class="px-5 py-7 text-sm text-slate-600 dark:text-slate-300">
                        <p>Belum ada lead yang dicatat.</p>
                        <a href="{{ route($routePrefix.'leads.create') }}" class="mt-3 inline-flex min-h-11 items-center text-sm font-bold text-[#3166b1] hover:underline dark:text-blue-300">Catat calon jemaah pertama <i data-lucide="arrow-right" class="ml-1 size-4" aria-hidden="true"></i></a>
                        @unless ($isAdmin)<p class="mt-3 text-xs text-slate-500 dark:text-slate-400">Perlu bantuan? <a href="{{ route('cs.guide') }}" class="font-bold text-[#3166b1] hover:underline dark:text-blue-300">Buka panduan CS</a>.</p>@endunless
                    </div>
                @endforelse
                @if ($csPhone)
                    <div class="border-t border-slate-200 px-5 py-4 dark:border-slate-700">
                        <a href="{{ 'https://wa.me/'.preg_replace('/[^0-9]/', '', $csPhone) }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-11 items-center gap-2 text-sm font-bold text-[#3166b1] hover:underline dark:text-blue-300"><i data-lucide="message-circle" class="size-4" aria-hidden="true"></i>Buka WhatsApp <span class="sr-only">(tab baru)</span></a>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-dynamic-component>
