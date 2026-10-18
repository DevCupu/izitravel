<x-dynamic-component :component="$isAdmin ? 'admin-layout' : 'cs-layout'" title="Beranda CRM">
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ $isAdmin ? 'Beranda CRM' : 'Halo, '.auth()->user()->name }}</h1>
            <p class="text-xs text-slate-500">Prioritas kerja hari ini · WITA</p>
        </div>
    </x-slot>
    <div class="space-y-7">
        <section aria-label="Tindakan utama" class="flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-[#102b52] p-5 text-white sm:p-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-blue-200">Mulai dari sini</p>
                <h2 class="mt-1 text-xl font-bold">Tindak lanjuti calon jemaah</h2>
                <p class="mt-1 max-w-xl text-sm text-blue-100">Selesaikan tugas yang terlambat, hubungi calon jemaah, lalu catat perkembangan lead.</p>
            </div>
            <div class="flex w-full flex-wrap gap-2 sm:w-auto">
                @if ($csPhone)
                    <a href="{{ 'https://wa.me/'.preg_replace('/[^0-9]/', '', $csPhone) }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-white/40 px-4 text-sm font-bold text-white hover:bg-white/10"><i data-lucide="message-circle" class="h-4 w-4" aria-hidden="true"></i>Buka WhatsApp<span class="sr-only">(tab baru)</span></a>
                @endif
                <a href="{{ route($routePrefix.'leads.create') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-white px-4 text-sm font-bold text-[#102b52] hover:bg-blue-50"><i data-lucide="plus" class="h-4 w-4" aria-hidden="true"></i>Catat calon jemaah</a>
            </div>
        </section>

        @if ($recentLeads->isEmpty() && ! $isAdmin)
            <section aria-labelledby="getting-started-heading" class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
                <h2 id="getting-started-heading" class="text-base font-bold text-[#102b52]">Cara mulai menggunakan CRM</h2>
                <ol class="mt-3 grid gap-3 text-sm text-slate-700 sm:grid-cols-3">
                    <li><strong class="block text-[#3166b1]">1. Terima chat</strong>Hubungi calon jemaah melalui WhatsApp.</li>
                    <li><strong class="block text-[#3166b1]">2. Catat lead</strong>Isi data setelah calon jemaah benar-benar menghubungi Anda.</li>
                    <li><strong class="block text-[#3166b1]">3. Jadwalkan tindak lanjut</strong>Buka detail lead untuk membuat tugas follow-up.</li>
                </ol>
                <a href="{{ route('cs.guide') }}" class="mt-4 inline-flex min-h-11 items-center gap-1 text-sm font-bold text-[#3166b1] hover:underline">Buka panduan CS <i data-lucide="arrow-right" class="h-4 w-4" aria-hidden="true"></i></a>
            </section>
        @endif

        <div class="grid grid-cols-2 gap-3 lg:grid-cols-3">
            <a href="{{ route($routePrefix.'tasks', ['period' => 'overdue']) }}" class="rounded-2xl p-4 text-white shadow-sm transition hover:brightness-95" style="background-color: #b42318"><p class="text-xs font-bold">Terlambat</p><p class="mt-2 text-3xl font-extrabold">{{ $overdueCount }}</p><p class="text-xs text-white/90">Tugas yang perlu ditangani</p></a>
            <a href="{{ route($routePrefix.'leads.index', ['stage' => 'baru']) }}" class="rounded-2xl p-4 text-white shadow-sm transition hover:brightness-95" style="background-color: #3166b1"><p class="text-xs font-bold">{{ $stageLabels['baru'] }}</p><p class="mt-2 text-3xl font-extrabold">{{ $newCount }}</p><p class="text-xs text-white/90">Belum ditindaklanjuti</p></a>
            <div class="col-span-2 rounded-2xl p-4 text-white shadow-sm lg:col-span-1" style="background-color: #177d64"><p class="text-xs font-bold">Booking berhasil bulan ini</p><p class="mt-2 text-3xl font-extrabold">{{ $wonThisMonth }}</p><p class="text-xs text-white/90">Berdasarkan konfirmasi lead</p></div>
        </div>
        <div class="grid gap-6 lg:grid-cols-2">
            <section><x-ui.section-heading title="Terlambat" description="Tugas yang melewati jadwal"><a href="{{ route($routePrefix.'tasks', ['period' => 'overdue']) }}" class="ui-button-secondary">Lihat semua</a></x-ui.section-heading><div class="mt-3 space-y-3">@forelse ($overdue as $task) @include('crm._task-row') @empty <x-ui.empty-state title="Tidak ada tugas terlambat" description="Lanjutkan tindak lanjut yang dijadwalkan hari ini." icon="circle-check" /> @endforelse</div></section>
            <section><x-ui.section-heading title="Hari ini" description="Tindak lanjut pada tanggal WITA hari ini"><a href="{{ route($routePrefix.'tasks', ['period' => 'today']) }}" class="ui-button-secondary">Lihat semua</a></x-ui.section-heading><div class="mt-3 space-y-3">@forelse ($dueToday as $task) @include('crm._task-row') @empty <x-ui.empty-state title="Belum ada follow-up hari ini" description="Buka lead untuk menjadwalkan tugas berikutnya." icon="calendar-check" /> @endforelse</div></section>
        </div>
        <section aria-labelledby="recent-heading"><div class="mb-3 flex items-center justify-between"><h2 id="recent-heading" class="text-lg font-bold">Lead terbaru</h2><a href="{{ route($routePrefix.'leads.index') }}" class="text-xs font-bold text-[#3166b1]">Lihat daftar</a></div><div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">@forelse ($recentLeads as $lead) @include('crm._lead-card') @empty <div class="rounded-xl border border-dashed border-slate-300 p-6 text-sm text-slate-500">Belum ada lead. Tambahkan setelah calon jemaah benar-benar menghubungi CS.</div> @endforelse</div></section>
    </div>
</x-dynamic-component>
