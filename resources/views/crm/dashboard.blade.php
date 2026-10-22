@php($attentionCount = $overdueCount + $dueTodayCount + $needsFollowupCount)
<x-dynamic-component :component="$isAdmin ? 'admin-layout' : 'cs-layout'" title="Beranda CRM">
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ $isAdmin ? 'Pusat kerja CRM' : 'Halo, '.auth()->user()->name }}</h1>
            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ now('Asia/Makassar')->locale('id')->translatedFormat('l, d F Y') }} · WITA</p>
        </div>
    </x-slot>

    <div class="crm-page mx-auto max-w-7xl space-y-5 pb-5">
        <section aria-labelledby="crm-focus-title" class="relative isolate overflow-hidden rounded-[1.75rem] bg-[#102b52] px-5 py-6 text-white shadow-[0_18px_45px_rgba(16,43,82,.14)] sm:px-8 sm:py-8">
            <div aria-hidden="true" class="pointer-events-none absolute -right-16 -top-32 size-80 rounded-full border border-white/10 shadow-[0_0_0_55px_rgba(255,255,255,.025),0_0_0_110px_rgba(255,255,255,.018)]"></div>
            <div class="relative grid gap-6 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end">
                <div class="min-w-0 border-l-[3px] border-[#fcde05] pl-4 sm:pl-5">
                    <p class="text-[11px] font-extrabold uppercase tracking-[.16em] text-[#fcde05]">Prioritas hari ini</p>
                    <h2 id="crm-focus-title" class="mt-2 max-w-2xl text-2xl font-bold leading-tight sm:text-[2rem]">
                        @if ($attentionCount)
                            {{ number_format($attentionCount, 0, ',', '.') }} pekerjaan perlu perhatian
                        @else
                            Semua pekerjaan mendesak tertangani
                        @endif
                    </h2>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-blue-100">
                        {{ $isAdmin ? 'Pantau tindak lanjut tim dan bantu lead yang belum punya langkah berikutnya.' : 'Kerjakan yang terlambat lebih dulu, lalu siapkan langkah berikutnya untuk setiap lead.' }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 lg:justify-end">
                    <a href="#crm-priority" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#fcde05] px-4 py-2.5 text-sm font-extrabold text-[#102b52] transition hover:bg-[#ffe845] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"><i data-lucide="list-checks" class="size-4" aria-hidden="true"></i>Lihat prioritas</a>
                    <a href="{{ route($routePrefix.'leads.create') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-white/35 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"><i data-lucide="plus" class="size-4" aria-hidden="true"></i>Catat lead</a>
                </div>
            </div>
        </section>

        <section aria-label="Ringkasan pekerjaan CRM" class="grid grid-cols-2 gap-3 lg:grid-cols-4">
            <a href="{{ route($routePrefix.'tasks', ['period' => 'overdue']) }}" class="ui-card group flex min-h-28 flex-col justify-between border-t-[3px] border-t-red-600 p-4 transition hover:border-red-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#3166b1]">
                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Tugas terlambat</span>
                <span class="flex items-end justify-between"><strong class="text-3xl font-extrabold tabular-nums text-red-700 dark:text-red-400">{{ $overdueCount }}</strong><i data-lucide="arrow-up-right" class="size-4 text-slate-400 group-hover:text-red-700" aria-hidden="true"></i></span>
            </a>
            <a href="{{ route($routePrefix.'tasks', ['period' => 'today']) }}" class="ui-card group flex min-h-28 flex-col justify-between border-t-[3px] border-t-amber-500 p-4 transition hover:border-amber-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#3166b1]">
                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Tugas hari ini</span>
                <span class="flex items-end justify-between"><strong class="text-3xl font-extrabold tabular-nums text-amber-700 dark:text-amber-300">{{ $dueTodayCount }}</strong><i data-lucide="arrow-up-right" class="size-4 text-slate-400 group-hover:text-amber-700" aria-hidden="true"></i></span>
            </a>
            <a href="{{ route($routePrefix.'leads.index', ['needs_followup' => 1]) }}" class="ui-card group flex min-h-28 flex-col justify-between border-t-[3px] border-t-[#3166b1] p-4 transition hover:border-blue-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#3166b1]">
                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Belum ada tugas berikutnya</span>
                <span class="flex items-end justify-between"><strong class="text-3xl font-extrabold tabular-nums text-[#3166b1] dark:text-blue-300">{{ $needsFollowupCount }}</strong><i data-lucide="arrow-up-right" class="size-4 text-slate-400 group-hover:text-[#3166b1]" aria-hidden="true"></i></span>
            </a>
            <a href="{{ route($routePrefix.'leads.index', ['stage' => 'baru']) }}" class="ui-card group flex min-h-28 flex-col justify-between border-t-[3px] border-t-[#102b52] p-4 transition hover:border-blue-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#3166b1]">
                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Lead baru</span>
                <span class="flex items-end justify-between"><strong class="text-3xl font-extrabold tabular-nums text-[#102b52] dark:text-white">{{ $newCount }}</strong><i data-lucide="arrow-up-right" class="size-4 text-slate-400 group-hover:text-[#3166b1]" aria-hidden="true"></i></span>
            </a>
        </section>

        <div class="grid items-start gap-5 xl:grid-cols-[minmax(0,1.55fr)_minmax(280px,.9fr)]">
            <section id="crm-priority" aria-labelledby="crm-priority-heading" class="ui-card min-w-0 scroll-mt-24 overflow-hidden">
                <div class="flex flex-wrap items-end justify-between gap-3 border-b border-slate-200 px-5 py-5 dark:border-slate-700 sm:px-6">
                    <div><p class="text-[11px] font-extrabold uppercase tracking-[.13em] text-[#3166b1] dark:text-blue-300">Jalur kerja</p><h2 id="crm-priority-heading" class="mt-1 text-xl font-bold text-[#102b52] dark:text-white">Yang perlu ditangani</h2></div>
                    <a href="{{ route($routePrefix.'tasks') }}" class="inline-flex min-h-10 items-center text-xs font-bold text-[#3166b1] hover:underline dark:text-blue-300">Buka semua tugas <i data-lucide="arrow-right" class="ml-1 size-4" aria-hidden="true"></i></a>
                </div>

                <div class="divide-y divide-slate-200 dark:divide-slate-700">
                    <div class="grid sm:grid-cols-[112px_minmax(0,1fr)]">
                        <div class="border-l-[3px] border-red-600 bg-red-50/50 px-4 py-5 dark:bg-red-950/20 sm:px-5"><h3 class="text-sm font-extrabold text-red-800 dark:text-red-300">Terlambat</h3><p class="mt-1 text-xs tabular-nums text-red-700 dark:text-red-400">{{ $overdueCount }} tugas</p></div>
                        <div class="min-w-0 px-4 py-2 sm:px-6">
                            @forelse ($overdue as $task)
                                @include('crm._dashboard-task', ['task' => $task, 'isOverdue' => true])
                            @empty
                                <p class="py-4 text-sm text-slate-500 dark:text-slate-400">Tidak ada tugas terlambat.</p>
                            @endforelse
                            @if ($overdueCount > $overdue->count())<a href="{{ route($routePrefix.'tasks', ['period' => 'overdue']) }}" class="inline-flex min-h-10 items-center text-xs font-bold text-[#3166b1] hover:underline dark:text-blue-300">Lihat semua yang terlambat</a>@endif
                        </div>
                    </div>
                    <div class="grid sm:grid-cols-[112px_minmax(0,1fr)]">
                        <div class="border-l-[3px] border-amber-500 bg-amber-50/50 px-4 py-5 dark:bg-amber-950/20 sm:px-5"><h3 class="text-sm font-extrabold text-amber-900 dark:text-amber-300">Hari ini</h3><p class="mt-1 text-xs tabular-nums text-amber-800 dark:text-amber-400">{{ $dueTodayCount }} tugas</p></div>
                        <div class="min-w-0 px-4 py-2 sm:px-6">
                            @forelse ($dueToday as $task)
                                @include('crm._dashboard-task', ['task' => $task, 'isOverdue' => false])
                            @empty
                                <p class="py-4 text-sm text-slate-500 dark:text-slate-400">Belum ada tugas untuk hari ini.</p>
                            @endforelse
                            @if ($dueTodayCount > $dueToday->count())<a href="{{ route($routePrefix.'tasks', ['period' => 'today']) }}" class="inline-flex min-h-10 items-center text-xs font-bold text-[#3166b1] hover:underline dark:text-blue-300">Lihat semua tugas hari ini</a>@endif
                        </div>
                    </div>
                    <div class="grid sm:grid-cols-[112px_minmax(0,1fr)]">
                        <div class="border-l-[3px] border-[#3166b1] bg-blue-50/60 px-4 py-5 dark:bg-blue-950/20 sm:px-5"><h3 class="text-sm font-extrabold leading-snug text-[#245392] dark:text-blue-300">Tanpa tugas</h3><p class="mt-1 text-xs tabular-nums text-[#245392] dark:text-blue-400">{{ $needsFollowupCount }} lead</p></div>
                        <div class="min-w-0 px-4 py-2 sm:px-6">
                            @forelse ($needsFollowup as $lead)
                                @include('crm._dashboard-untasked', ['lead' => $lead])
                            @empty
                                <p class="py-4 text-sm text-slate-500 dark:text-slate-400">Semua lead aktif sudah punya tugas berikutnya.</p>
                            @endforelse
                            @if ($needsFollowupCount > $needsFollowup->count())<a href="{{ route($routePrefix.'leads.index', ['needs_followup' => 1]) }}" class="inline-flex min-h-10 items-center text-xs font-bold text-[#3166b1] hover:underline dark:text-blue-300">Lihat semua lead tanpa tugas</a>@endif
                        </div>
                    </div>
                </div>
            </section>

            <aside class="min-w-0 space-y-5">
                @if ($isAdmin)
                    <section aria-labelledby="crm-team-heading" class="ui-card min-w-0 overflow-hidden">
                        <div class="border-b border-slate-200 px-5 py-5 dark:border-slate-700"><p class="text-[11px] font-extrabold uppercase tracking-[.13em] text-[#3166b1] dark:text-blue-300">Pantauan supervisor</p><h2 id="crm-team-heading" class="mt-1 text-lg font-bold text-[#102b52] dark:text-white">Beban CS</h2><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Tugas terlambat dan lead tanpa jadwal per PIC.</p></div>
                        <div class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse ($teamWorkload as $row)
                                <div class="flex flex-wrap items-center justify-between gap-2 px-5 py-3.5">
                                    <a href="{{ route('admin.crm.leads.index', ['cs_id' => $row['cs']->id]) }}" class="min-w-0 text-sm font-bold text-[#102b52] hover:underline dark:text-white">{{ $row['cs']->name }}</a>
                                    <div class="flex flex-wrap gap-1.5 text-[11px] font-bold">
                                        <a href="{{ route('admin.crm.tasks', ['period' => 'overdue', 'cs_id' => $row['cs']->id]) }}" class="rounded-lg bg-red-50 px-2 py-1.5 text-red-800 hover:bg-red-100 dark:bg-red-950/30 dark:text-red-300">{{ $row['overdue'] }} terlambat</a>
                                        <a href="{{ route('admin.crm.leads.index', ['needs_followup' => 1, 'cs_id' => $row['cs']->id]) }}" class="rounded-lg bg-blue-50 px-2 py-1.5 text-[#245392] hover:bg-blue-100 dark:bg-blue-950/30 dark:text-blue-300">{{ $row['without_task'] }} tanpa tugas</a>
                                    </div>
                                </div>
                            @empty
                                <p class="px-5 py-6 text-sm text-slate-500 dark:text-slate-400">Belum ada CS yang perlu ditampilkan.</p>
                            @endforelse
                        </div>
                    </section>
                    <section aria-label="Booking bulan ini" class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900/60 dark:bg-emerald-950/20">
                        <p class="text-xs font-bold text-emerald-900 dark:text-emerald-300">Booking bulan ini</p>
                        <div class="mt-2 flex items-end justify-between gap-3"><strong class="text-3xl font-extrabold tabular-nums text-emerald-800 dark:text-emerald-300">{{ $wonThisMonth }}</strong><a href="{{ route('admin.crm.report') }}" class="inline-flex min-h-10 items-center gap-1 text-xs font-bold text-emerald-900 hover:underline dark:text-emerald-300">Lihat laporan <i data-lucide="arrow-right" class="size-4" aria-hidden="true"></i></a></div>
                    </section>
                @else
                    <section aria-labelledby="crm-cs-next-heading" class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
                        <p class="text-[11px] font-extrabold uppercase tracking-[.13em] text-[#3166b1]">Langkah cepat</p>
                        <h2 id="crm-cs-next-heading" class="mt-1 text-lg font-bold text-[#102b52]">Ada calon jemaah baru?</h2>
                        <p class="mt-1 text-sm leading-relaxed text-slate-600">Catat kontaknya sekarang. Jadwal tindak lanjut bisa dibuat dalam formulir yang sama.</p>
                        <a href="{{ route('cs.leads.create') }}" class="ui-button-primary mt-4">Catat calon jemaah</a>
                    </section>
                @endif
            </aside>
        </div>

        <section aria-labelledby="crm-leads-heading" class="ui-card overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-5 py-4 dark:border-slate-700 sm:px-6"><div><h2 id="crm-leads-heading" class="ui-section-title">Lead terbaru</h2><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Lanjutkan percakapan dari lead yang baru dicatat.</p></div><a href="{{ route($routePrefix.'leads.index') }}" class="inline-flex min-h-10 items-center gap-1 text-xs font-bold text-[#3166b1] hover:underline dark:text-blue-300">Semua lead <i data-lucide="arrow-right" class="size-4" aria-hidden="true"></i></a></div>
            <div class="grid sm:grid-cols-2 xl:grid-cols-5">
                @forelse ($recentLeads as $lead)
                    <a href="{{ route($routePrefix.'leads.show', $lead) }}" class="group flex min-w-0 flex-col gap-2 border-b border-slate-100 px-5 py-4 transition hover:bg-blue-50/50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-[-3px] focus-visible:outline-[#3166b1] dark:border-slate-700 dark:hover:bg-slate-700/40 sm:border-r xl:border-b-0">
                        <span class="truncate text-sm font-bold text-[#102b52] group-hover:text-[#3166b1] dark:text-white">{{ $lead->display_name }}</span>
                        <span class="truncate text-xs text-slate-500 dark:text-slate-400">{{ $lead->package?->name ?? 'Paket belum dipilih' }}</span>
                        <span class="mt-auto flex flex-wrap items-center gap-2 pt-2 text-[11px] text-slate-500 dark:text-slate-400"><span class="rounded-md bg-blue-50 px-2 py-1 font-bold text-[#245392] dark:bg-blue-900/30 dark:text-blue-200">{{ $stageLabels[$lead->stage] ?? $lead->stage }}</span>@if ($isAdmin)<span class="truncate">{{ $lead->cs?->name }}</span>@endif</span>
                    </a>
                @empty
                    <div class="px-5 py-7 text-sm text-slate-600 dark:text-slate-300">Belum ada lead. <a href="{{ route($routePrefix.'leads.create') }}" class="font-bold text-[#3166b1] hover:underline">Catat calon jemaah pertama</a>.</div>
                @endforelse
            </div>
        </section>
    </div>
</x-dynamic-component>
