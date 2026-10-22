@php
    $stageDetails = $stageOptions->mapWithKeys(fn ($stage) => [$stage->key => ['hint' => $stage->kind === 'won' ? 'Sudah terkonfirmasi' : ($stage->kind === 'lost' ? 'Tidak berlanjut' : 'Lead dalam tahap ini'), 'color' => $stage->color]])->all();
    $activeCount = $stageOptions->where('kind', 'open')->sum(fn ($stage) => (int) ($counts[$stage->key] ?? 0));
@endphp
<x-dynamic-component :component="$isAdmin ? 'admin-layout' : 'cs-layout'" title="Pipeline">
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Pipeline</h1>
            <p class="text-xs text-slate-500">Perjalanan setiap calon jemaah</p>
        </div>
    </x-slot>

    @if ($isAdmin && $errors->any())
        <div role="alert" class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900 dark:bg-red-950/40 dark:text-red-200">
            <p class="font-bold">Periksa kembali data berikut:</p>
            <ul class="mt-1 list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="crm-page" x-data="crmPipeline" data-today="{{ now('Asia/Makassar')->toDateString() }}"
        data-old-lead-id="{{ old('lead_id') }}" data-old-stage="{{ old('stage') }}"
        data-old-confirmation-note="{{ old('confirmation_note') }}" data-old-confirmed-on="{{ old('confirmed_on') }}"
        data-old-booking-reference="{{ old('booking_reference') }}" data-old-lost-reason="{{ old('lost_reason') }}"
        data-old-pipeline-form="{{ old('pipeline_form') }}" data-old-display-name="{{ old('display_name') }}"
        data-old-package-id="{{ old('package_id') }}" data-old-city="{{ old('city') }}"
        data-old-planned-departure-month="{{ old('planned_departure_month') }}" data-old-assigned-cs-id="{{ old('assigned_cs_id') }}">
        <section class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900" aria-label="Ringkasan pipeline">
            <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div class="min-w-0">
                    <p class="text-[11px] font-extrabold uppercase tracking-[.16em] text-[#3166b1] dark:text-blue-300">Alur calon jemaah</p>
                    <h2 class="mt-1 text-xl font-bold text-[#102b52] dark:text-white sm:text-2xl">{{ $isAdmin ? 'Perkembangan lead tim' : 'Perkembangan lead saya' }}</h2>
                    <p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400 sm:text-sm">Pilih tahap untuk melihat lead dan langkah berikutnya.</p>
                </div>
                <div class="flex flex-wrap gap-2 self-start sm:self-auto">
                @if ($isAdmin)<a href="{{ route('admin.crm.stages.index') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-bold text-[#245392] hover:bg-blue-50 dark:border-slate-600 dark:bg-slate-800 dark:text-blue-300 dark:hover:bg-slate-700"><i data-lucide="settings-2" class="size-4" aria-hidden="true"></i>Atur tahap</a>@endif
                <a href="{{ route($routePrefix.'leads.create') }}" class="inline-flex min-h-11 shrink-0 items-center justify-center gap-2 rounded-xl bg-[#3166b1] px-4 text-sm font-bold text-white transition hover:bg-[#245392] focus-visible:outline-[#3166b1]">
                    <i data-lucide="plus" class="h-4 w-4" aria-hidden="true"></i>Tambah lead
                </a>
                </div>
            </div>
            <dl class="grid grid-cols-3 divide-x divide-slate-100 dark:divide-slate-700">
                <div class="px-3 py-3.5 sm:px-6"><dt class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Dalam proses</dt><dd class="mt-1 text-xl font-extrabold text-[#102b52] dark:text-white sm:text-2xl">{{ $activeCount }}</dd></div>
                <div class="px-3 py-3.5 sm:px-6"><dt class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">{{ $stageLabels['berhasil'] }}</dt><dd class="mt-1 text-xl font-extrabold text-emerald-700 dark:text-emerald-300 sm:text-2xl">{{ (int) ($counts['berhasil'] ?? 0) }}</dd></div>
                <div class="px-3 py-3.5 sm:px-6"><dt class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">{{ $stageLabels['gagal'] }}</dt><dd class="mt-1 text-xl font-extrabold text-slate-600 dark:text-slate-200 sm:text-2xl">{{ (int) ($counts['gagal'] ?? 0) }}</dd></div>
            </dl>
        </section>

        <div class="mb-3 flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-[#102b52] dark:text-white">Tahap lead</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400"><span class="lg:hidden">Pilih tahap, lalu edit data atau ubah tahap lead.</span><span class="hidden lg:inline">Edit data lead atau ubah tahap. Pada tampilan card, tarik pegangan kartu ke tahap tujuan. Maksimal {{ $previewLimit }} lead terbaru per tahap.</span></p>
            </div>
            <div class="flex w-full flex-wrap items-end gap-2 sm:w-auto">
                <div role="group" aria-label="Tampilan pipeline" class="flex min-h-11 items-center gap-1 rounded-xl border border-slate-200 bg-white p-1 dark:border-slate-700 dark:bg-slate-800">
                    <button type="button" @click="setViewMode('card')" :aria-pressed="viewMode === 'card'" :class="viewMode === 'card' ? 'bg-[#3166b1] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700'" class="inline-flex min-h-9 items-center justify-center gap-1.5 rounded-lg px-3 text-xs font-bold focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#3166b1]"><i data-lucide="layout-grid" class="size-4" aria-hidden="true"></i>Card</button>
                    <button type="button" @click="setViewMode('table')" :aria-pressed="viewMode === 'table'" :class="viewMode === 'table' ? 'bg-[#3166b1] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700'" class="inline-flex min-h-9 items-center justify-center gap-1.5 rounded-lg px-3 text-xs font-bold focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#3166b1]"><i data-lucide="list" class="size-4" aria-hidden="true"></i>Table</button>
                </div>
            @if ($isAdmin)
                <form method="GET" action="{{ route($routePrefix.'pipeline') }}" class="flex w-full flex-wrap items-end gap-2 rounded-xl border border-slate-200 bg-white p-2.5 dark:border-slate-700 dark:bg-slate-800 sm:w-auto" aria-label="Filter pipeline berdasarkan PIC">
                    <div class="min-w-[180px] flex-1 sm:flex-none">
                        <label for="pipeline-cs-filter" class="mb-1 block text-[11px] font-bold text-slate-600 dark:text-slate-300">Tampilkan PIC</label>
                        <select id="pipeline-cs-filter" name="cs_id" class="min-h-10 w-full rounded-lg border border-slate-300 bg-white px-2.5 text-xs text-slate-800 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 sm:w-[190px]">
                            <option value="">Semua PIC</option>
                            @foreach ($csList as $cs)
                                <option value="{{ $cs->id }}" @selected((string) $selectedCsId === (string) $cs->id)>{{ $cs->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="min-h-10 rounded-lg bg-[#3166b1] px-3.5 text-xs font-bold text-white hover:bg-[#245392]">Terapkan</button>
                </form>
            @else
                <span class="hidden rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 sm:inline-flex">{{ $counts->sum() }} lead tercatat</span>
            @endif
            </div>
        </div>

        <div class="mb-4 flex items-end gap-2 lg:hidden">
            <div class="min-w-0 flex-1"><label for="pipeline-mobile-stage" class="mb-1 block text-xs font-bold text-slate-600 dark:text-slate-300">Tahap yang ditampilkan</label>
                <select id="pipeline-mobile-stage" x-model="activeStage" @change="resetBulk()" class="min-h-11 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm font-bold text-[#102b52] focus:border-[#3166b1] focus:ring-[#3166b1] dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                    @foreach ($stageOptions as $stageItem)<option value="{{ $stageItem->key }}">{{ $stageItem->label }} · {{ (int) ($counts[$stageItem->key] ?? 0) }}</option>@endforeach
                </select>
            </div>
            <button type="button" @click="stepStage(-1)" :disabled="activeStage === stages[0]" aria-label="Tahap sebelumnya" class="flex size-11 shrink-0 items-center justify-center rounded-xl border border-slate-300 bg-white text-[#3166b1] disabled:opacity-40 dark:border-slate-600 dark:bg-slate-800"><i data-lucide="chevron-left" class="size-4" aria-hidden="true"></i></button>
            <button type="button" @click="stepStage(1)" :disabled="activeStage === stages[stages.length - 1]" aria-label="Tahap berikutnya" class="flex size-11 shrink-0 items-center justify-center rounded-xl border border-slate-300 bg-white text-[#3166b1] disabled:opacity-40 dark:border-slate-600 dark:bg-slate-800"><i data-lucide="chevron-right" class="size-4" aria-hidden="true"></i></button>
        </div>

        <div aria-label="Pilih tahap pipeline" class="mb-4 hidden gap-2 lg:grid lg:grid-cols-4 xl:grid-cols-7">
            @foreach ($stageLabels as $stage => $label)
                <button type="button" @click="scrollToStage('{{ $stage }}')" @dragover="allowDrop($event, '{{ $stage }}')" @dragleave="leaveDrop($event)" @drop="drop($event, '{{ $stage }}')" :aria-pressed="activeStage === '{{ $stage }}'"
                    :class="hoverStage === '{{ $stage }}' ? 'pipeline-stage-hover' : (activeStage === '{{ $stage }}' ? 'pipeline-stage-active' : '')"
                    class="flex min-h-14 min-w-0 items-center justify-between gap-2 rounded-xl border border-slate-200 bg-white px-3 text-left text-xs font-bold text-slate-700 transition hover:border-[#3166b1] hover:text-[#3166b1] dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                    <span class="flex min-w-0 items-center gap-2"><span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: {{ $stageDetails[$stage]['color'] }}" aria-hidden="true"></span><span class="truncate">{{ $label }}</span></span>
                    <span class="shrink-0 text-slate-400">{{ (int) ($counts[$stage] ?? 0) }}</span>
                </button>
            @endforeach
        </div>

        <div x-cloak x-show="visibleIds().length > 0 && ({{ $isAdmin ? 'true' : 'false' }} || activeStage !== 'berhasil')"
             class="mb-4 rounded-2xl border border-blue-200 bg-blue-50/80 p-3 dark:border-blue-800 dark:bg-blue-950/30 sm:p-4" aria-label="Aksi massal pipeline">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" @click="toggleAllVisible()" class="min-h-10 rounded-lg px-2 text-xs font-bold text-[#245392] hover:bg-blue-100 dark:text-blue-200 dark:hover:bg-blue-900/40"
                            x-text="visibleIds().every(id => selectedIds.includes(id)) ? 'Batalkan pilihan' : 'Pilih semua yang tampil'"></button>
                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-300" x-text="selectedIds.length + ' lead dipilih'"></span>
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">Hanya lead yang tampil di tahap ini (maks. {{ $previewLimit }}).</p>
            </div>
            <form method="POST" action="{{ route($routePrefix.'pipeline.bulk') }}" @submit="submitBulk($event)" class="mt-3 flex flex-wrap items-end gap-2 border-t border-blue-100 pt-3 dark:border-blue-900">
                @csrf
                <input type="hidden" name="source_stage" :value="activeStage">
                <template x-for="id in selectedIds" :key="id"><input type="hidden" name="ids[]" :value="id"></template>
                <div class="min-w-36 flex-1 sm:flex-none"><label for="pipeline-bulk-action" class="mb-1 block text-[11px] font-bold text-slate-600 dark:text-slate-300">Aksi massal</label>
                    <select id="pipeline-bulk-action" name="action" x-model="bulkAction" @change="bulkTarget = ''; bulkCsId = ''" class="min-h-10 w-full rounded-lg border border-slate-300 bg-white px-2.5 text-xs font-semibold text-slate-800 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                        <option value="move">Pindah tahap proses</option>
                        @if ($isAdmin)<option value="assign">Ganti PIC</option><option value="archive">Arsipkan lead</option>@endif
                    </select>
                </div>
                <div x-show="bulkAction === 'move'" class="min-w-40 flex-1 sm:flex-none"><label for="pipeline-bulk-stage" class="mb-1 block text-[11px] font-bold text-slate-600 dark:text-slate-300">Tahap tujuan</label>
                    <select id="pipeline-bulk-stage" name="target_stage" x-model="bulkTarget" :required="bulkAction === 'move'" :disabled="bulkAction !== 'move'" class="min-h-10 w-full rounded-lg border border-slate-300 bg-white px-2.5 text-xs font-semibold text-slate-800 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                        <option value="">Pilih tahap</option>
                        @foreach ($stageOptions->where('kind', 'open') as $stageItem)<option value="{{ $stageItem->key }}" x-bind:disabled="activeStage === '{{ $stageItem->key }}'">{{ $stageItem->label }}</option>@endforeach
                    </select>
                </div>
                @if ($isAdmin)
                    <div x-show="bulkAction === 'assign'" class="min-w-40 flex-1 sm:flex-none"><label for="pipeline-bulk-cs" class="mb-1 block text-[11px] font-bold text-slate-600 dark:text-slate-300">PIC tujuan</label>
                        <select id="pipeline-bulk-cs" name="assigned_cs_id" x-model="bulkCsId" :required="bulkAction === 'assign'" :disabled="bulkAction !== 'assign'" class="min-h-10 w-full rounded-lg border border-slate-300 bg-white px-2.5 text-xs font-semibold text-slate-800 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                            <option value="">Pilih CS</option>
                            @foreach ($csList as $cs) @if ($cs->is_active && $cs->user?->is_active)<option value="{{ $cs->id }}">{{ $cs->name }}</option>@endif @endforeach
                        </select>
                    </div>
                @endif
                <button type="submit" :disabled="selectedIds.length === 0" class="min-h-10 rounded-lg bg-[#3166b1] px-4 text-xs font-bold text-white hover:bg-[#245392] disabled:cursor-not-allowed disabled:opacity-50"
                        x-text="bulkAction === 'archive' ? 'Arsipkan terpilih' : 'Terapkan ke pilihan'"></button>
            </form>
        </div>

        <div x-ref="board" class="min-w-0 max-w-full pb-3" aria-label="Papan pipeline lead">
            <div class="min-w-0">
                @foreach ($stageLabels as $stage => $label)
                    <section data-column-stage="{{ $stage }}" aria-label="Tahap {{ $label }}" @dragover="allowDrop($event, '{{ $stage }}')" @dragleave="leaveDrop($event)" @drop="drop($event, '{{ $stage }}')"
                        :class="[activeStage === '{{ $stage }}' ? '!flex' : '', hoverStage === '{{ $stage }}' ? 'pipeline-lane-hover' : '']"
                        class="hidden min-w-0 w-full flex-col rounded-2xl border border-slate-200 bg-[#f0f3f8] p-3 transition-colors dark:border-slate-700 dark:bg-slate-800 sm:p-4">
                        <div class="mb-3 rounded-xl border border-slate-200/80 bg-white px-4 py-3.5 shadow-sm dark:border-slate-700 dark:bg-slate-900" style="border-left: 4px solid {{ $stageDetails[$stage]['color'] }}">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-base font-bold text-[#102b52] dark:text-white">{{ $label }}</h3>
                                <span class="inline-flex min-w-7 justify-center rounded-full bg-[#eaf0f8] px-2 py-1 text-xs font-extrabold text-[#102b52] dark:bg-slate-700 dark:text-white">{{ (int) ($counts[$stage] ?? 0) }}</span>
                            </div>
                            <p class="mt-0.5 text-[11px] font-medium text-slate-500 dark:text-slate-400">{{ ($counts[$stage] ?? 0) > $previewLimit ? $previewLimit.' terbaru ditampilkan' : $stageDetails[$stage]['hint'] }}</p>
                        </div>

                        <div x-cloak x-show="viewMode === 'card'" role="region" aria-label="Kartu lead tahap {{ $label }}" class="grid min-w-0 flex-1 gap-3 rounded-xl border border-dashed border-transparent p-0.5 transition-colors sm:grid-cols-[repeat(auto-fit,minmax(300px,1fr))] {{ ($leads[$stage] ?? collect())->isEmpty() ? 'min-h-32 place-items-center' : '' }}"
                            :class="hoverStage === '{{ $stage }}' ? 'border-[#3166b1]' : ''">
                            @forelse ($leads[$stage] ?? [] as $lead)
                                <article data-lead-id="{{ $lead->id }}" data-lead-name="{{ $lead->display_name }}" data-stage="{{ $stage }}"
                                    data-stage-url="{{ route($routePrefix.'leads.stage', $lead) }}"
                                    data-details-url="{{ route($routePrefix.'leads.pipeline-details', $lead) }}"
                                    data-show-url="{{ route($routePrefix.'leads.show', $lead) }}"
                                    data-package-id="{{ $lead->package_id }}" data-city="{{ $lead->city }}"
                                    data-planned-departure-month="{{ $lead->planned_departure_month }}" data-assigned-cs-id="{{ $lead->assigned_cs_id }}"
                                    data-confirmation-note="{{ $lead->confirmation_note }}"
                                    data-confirmed-on="{{ $lead->won_at?->timezone('Asia/Makassar')->toDateString() }}"
                                    data-booking-reference="{{ $lead->booking_reference }}" data-lost-reason="{{ $lead->lost_reason }}"
                                    :class="selectedIds.includes('{{ $lead->id }}') ? 'border-[#3166b1] ring-2 ring-[#3166b1]/30' : ''"
                                    class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-[#8ca9d6] hover:shadow-md dark:border-slate-700 dark:bg-slate-900">
                                    <div class="flex items-start justify-between gap-2">
                                        @if ($isAdmin || $stage !== 'berhasil')
                                            <label class="flex size-8 shrink-0 items-center justify-center rounded-lg hover:bg-blue-50 dark:hover:bg-slate-800" title="Pilih {{ $lead->display_name }}">
                                                <input type="checkbox" value="{{ $lead->id }}" x-model="selectedIds" aria-label="Pilih {{ $lead->display_name }}" class="size-5 rounded border-slate-300 text-[#3166b1] focus:ring-[#3166b1]">
                                            </label>
                                        @endif
                                        <a href="{{ route($routePrefix.'leads.show', $lead) }}" draggable="false" class="min-w-0 flex-1 text-sm font-extrabold leading-snug text-[#102b52] underline-offset-2 hover:text-[#3166b1] hover:underline dark:text-white dark:hover:text-blue-300">{{ $lead->display_name }}</a>
                                        @if ($isAdmin || $stage !== 'berhasil')
                                            <span draggable="true" @dragstart="beginDrag($event)" @dragend="endDrag()" aria-hidden="true" title="Tarik untuk pindah tahap" class="hidden h-8 w-8 shrink-0 cursor-grab items-center justify-center rounded-lg text-slate-400 hover:bg-blue-50 hover:text-[#3166b1] active:cursor-grabbing dark:hover:bg-slate-800 dark:hover:text-blue-300 lg:inline-flex"><i data-lucide="grip-vertical" class="h-4 w-4"></i></span>
                                        @endif
                                    </div>
                                    <p class="mt-1.5 line-clamp-2 text-xs leading-relaxed text-slate-600 dark:text-slate-300">{{ $lead->package?->name ?? 'Paket belum dipilih' }}</p>
                                    @if ($lead->city || $lead->planned_departure_month)
                                        <div class="mt-3 flex flex-wrap gap-1.5">
                                            @if ($lead->city)<span class="rounded-md bg-[#f2f5f9] px-2 py-1 text-[11px] font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $lead->city }}</span>@endif
                                            @if ($lead->planned_departure_month)<span class="rounded-md bg-[#f2f5f9] px-2 py-1 text-[11px] font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ \Carbon\Carbon::parse($lead->planned_departure_month.'-01')->locale('id')->translatedFormat('M Y') }}</span>@endif
                                        </div>
                                    @endif
                                    @if ($isAdmin)<p class="mt-3 text-[11px] text-slate-500 dark:text-slate-400">PIC: <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $lead->cs?->name ?? 'Belum ditugaskan' }}</span></p>@endif
                                    <div class="mt-3 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-2.5 dark:border-slate-700">
                                        <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500">{{ $lead->updated_at->timezone('Asia/Makassar')->locale('id')->translatedFormat('d M Y') }}</span>
                                        <div class="flex flex-wrap items-center gap-1">
                                            <button type="button" @click="openDetailsFromButton($event)" class="min-h-9 rounded-lg px-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-100 focus-visible:outline-[#3166b1] dark:text-slate-200 dark:hover:bg-slate-800">Edit data</button>
                                            @if ($isAdmin || $stage !== 'berhasil')
                                                <button type="button" @click="openMoveFromButton($event)" class="min-h-9 rounded-lg bg-blue-50 px-2.5 text-xs font-bold text-[#245392] transition hover:bg-blue-100 focus-visible:outline-[#3166b1] dark:bg-slate-800 dark:text-blue-300 dark:hover:bg-slate-700">Ubah tahap</button>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @empty
                                <div class="flex min-h-32 flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 px-5 text-center text-slate-500 dark:border-slate-600 dark:text-slate-400">
                                    <i data-lucide="inbox" class="mb-2 h-5 w-5 text-slate-400" aria-hidden="true"></i>
                                    <p class="text-xs font-semibold">Belum ada lead di tahap ini.</p>
                                    @if ($stage === 'baru')<a href="{{ route($routePrefix.'leads.create') }}" class="mt-2 text-xs font-bold text-[#3166b1] underline underline-offset-2">Tambah lead pertama</a>@endif
                                </div>
                            @endforelse
                        </div>
                        <div x-cloak x-show="viewMode === 'table'" class="min-w-0 overflow-x-auto rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900" role="region" aria-label="Tabel lead tahap {{ $label }}" tabindex="0">
                            <table class="w-full min-w-[760px] text-left text-xs">
                                <caption class="sr-only">Lead pada tahap {{ $label }}</caption>
                                <thead class="border-b border-slate-200 bg-slate-50 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                    <tr>
                                        @if ($isAdmin || $stage !== 'berhasil')<th scope="col" class="px-4 py-3 font-bold">Pilih</th>@endif
                                        <th scope="col" class="px-4 py-3 font-bold">Lead</th>
                                        <th scope="col" class="px-4 py-3 font-bold">Paket</th>
                                        <th scope="col" class="px-4 py-3 font-bold">Kota</th>
                                        <th scope="col" class="px-4 py-3 font-bold">Rencana umrah</th>
                                        @if ($isAdmin)<th scope="col" class="px-4 py-3 font-bold">PIC</th>@endif
                                        <th scope="col" class="px-4 py-3 font-bold">Diperbarui</th>
                                        <th scope="col" class="px-4 py-3 text-right font-bold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @forelse ($leads[$stage] ?? [] as $lead)
                                        <tr data-lead-id="{{ $lead->id }}" data-lead-name="{{ $lead->display_name }}" data-stage="{{ $stage }}"
                                            data-stage-url="{{ route($routePrefix.'leads.stage', $lead) }}"
                                            data-details-url="{{ route($routePrefix.'leads.pipeline-details', $lead) }}"
                                            data-show-url="{{ route($routePrefix.'leads.show', $lead) }}"
                                            data-package-id="{{ $lead->package_id }}" data-city="{{ $lead->city }}"
                                            data-planned-departure-month="{{ $lead->planned_departure_month }}" data-assigned-cs-id="{{ $lead->assigned_cs_id }}"
                                            data-confirmation-note="{{ $lead->confirmation_note }}"
                                            data-confirmed-on="{{ $lead->won_at?->timezone('Asia/Makassar')->toDateString() }}"
                                            data-booking-reference="{{ $lead->booking_reference }}" data-lost-reason="{{ $lead->lost_reason }}"
                                             :class="selectedIds.includes('{{ $lead->id }}') ? 'bg-blue-50 dark:bg-blue-900/20' : ''"
                                            class="text-slate-700 hover:bg-blue-50/50 dark:text-slate-200 dark:hover:bg-slate-800/60">
                                             @if ($isAdmin || $stage !== 'berhasil')<td class="px-4 py-3"><input type="checkbox" value="{{ $lead->id }}" x-model="selectedIds" aria-label="Pilih {{ $lead->display_name }}" class="size-5 rounded border-slate-300 text-[#3166b1] focus:ring-[#3166b1]"></td>@endif
                                            <th scope="row" class="px-4 py-3 text-sm font-bold"><a href="{{ route($routePrefix.'leads.show', $lead) }}" class="text-[#102b52] hover:text-[#3166b1] hover:underline dark:text-white dark:hover:text-blue-300">{{ $lead->display_name }}</a></th>
                                            <td class="max-w-48 truncate px-4 py-3" title="{{ $lead->package?->name ?? 'Paket belum dipilih' }}">{{ $lead->package?->name ?? 'Paket belum dipilih' }}</td>
                                            <td class="px-4 py-3">{{ $lead->city ?: '—' }}</td>
                                            <td class="whitespace-nowrap px-4 py-3">{{ $lead->planned_departure_month ? \Carbon\Carbon::parse($lead->planned_departure_month.'-01')->locale('id')->translatedFormat('M Y') : '—' }}</td>
                                            @if ($isAdmin)<td class="px-4 py-3">{{ $lead->cs?->name ?? 'Belum ditugaskan' }}</td>@endif
                                            <td class="whitespace-nowrap px-4 py-3">{{ $lead->updated_at->timezone('Asia/Makassar')->locale('id')->translatedFormat('d M Y') }}</td>
                                            <td class="whitespace-nowrap px-4 py-3 text-right">
                                                <button type="button" @click="openDetailsFromButton($event)" class="min-h-9 rounded-lg px-2.5 font-bold text-[#245392] hover:bg-blue-50 focus-visible:outline-[#3166b1] dark:text-blue-300 dark:hover:bg-slate-700">Edit data</button>
                                                @if ($isAdmin || $stage !== 'berhasil')<button type="button" @click="openMoveFromButton($event)" class="min-h-9 rounded-lg px-2.5 font-bold text-[#245392] hover:bg-blue-50 focus-visible:outline-[#3166b1] dark:text-blue-300 dark:hover:bg-slate-700">Ubah tahap</button>@endif
                                            </td>
                                        </tr>
                                    @empty
                                         <tr><td colspan="{{ $isAdmin ? 8 : ($stage === 'berhasil' ? 6 : 7) }}" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">Belum ada lead di tahap ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if (($counts[$stage] ?? 0) > $previewLimit)
                            <a href="{{ route($routePrefix.'leads.index', array_filter(['stage' => $stage, 'cs_id' => $selectedCsId])) }}" class="mt-2.5 flex min-h-10 items-center justify-center rounded-lg border border-[#bed0eb] bg-white px-2 text-center text-xs font-bold text-[#3166b1] hover:border-[#3166b1] dark:border-slate-700 dark:bg-slate-900 dark:text-blue-300">
                                Lihat semua {{ (int) $counts[$stage] }} lead <span class="ml-1" aria-hidden="true">→</span>
                            </a>
                        @endif
                    </section>
                @endforeach
            </div>
        </div>

        <p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400">Memindahkan lead ke <strong>{{ $stageLabels['berhasil'] }}</strong> memerlukan tanggal dan catatan konfirmasi. Memindahkan ke <strong>{{ $stageLabels['gagal'] }}</strong> memerlukan alasan. Semua perubahan tercatat di riwayat lead.</p>

        <form x-ref="quickStageForm" method="POST" :action="quickUrl" class="hidden">
            @csrf @method('PATCH')
            <input type="hidden" name="stage" :value="quickStage">
        </form>

        <dialog x-ref="detailsDialog" @close="details = null" aria-labelledby="pipeline-details-title" class="w-[calc(100%-2rem)] max-w-xl max-h-[calc(100dvh-2rem)] overflow-y-auto rounded-2xl border border-slate-200 bg-white p-0 text-slate-900 shadow-2xl backdrop:bg-[#102b52]/55 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
            <form method="POST" :action="details?.url || '#'" @submit="savePosition(details?.stage)" class="p-5 sm:p-6">
                @csrf @method('PATCH')
                <input type="hidden" name="pipeline_form" value="details">
                <input type="hidden" name="lead_id" :value="details?.id || ''">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-[.16em] text-[#3166b1]">Edit data lead</p>
                        <h2 id="pipeline-details-title" class="mt-1 text-xl font-bold text-[#102b52] dark:text-white" x-text="details?.name || 'Lead'"></h2>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Perubahan tersimpan dalam riwayat lead.</p>
                    </div>
                    <button type="button" @click="$refs.detailsDialog.close()" aria-label="Tutup" class="flex size-10 shrink-0 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"><i data-lucide="x" class="size-5" aria-hidden="true"></i></button>
                </div>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2"><label for="pipeline-display-name" class="block text-sm font-bold">Nama pada lead <span class="text-red-600">*</span></label><input id="pipeline-display-name" name="display_name" x-model="detailsName" required maxlength="255" class="mt-1.5 min-h-11 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-900 dark:border-slate-600 dark:bg-slate-800 dark:text-white"></div>
                    <div><label for="pipeline-package" class="block text-sm font-bold">Paket diminati</label><select id="pipeline-package" name="package_id" x-model="detailsPackageId" class="mt-1.5 min-h-11 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-900 dark:border-slate-600 dark:bg-slate-800 dark:text-white"><option value="">Belum dipilih</option>@foreach ($packages as $package)<option value="{{ $package->id }}">{{ $package->name }}</option>@endforeach</select></div>
                    <div><label for="pipeline-city" class="block text-sm font-bold">Kota domisili</label><input id="pipeline-city" name="city" x-model="detailsCity" maxlength="120" placeholder="Contoh: Makassar" class="mt-1.5 min-h-11 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-900 dark:border-slate-600 dark:bg-slate-800 dark:text-white"></div>
                    <div><label for="pipeline-departure" class="block text-sm font-bold">Rencana umrah</label><input id="pipeline-departure" type="month" name="planned_departure_month" x-model="detailsDeparture" class="mt-1.5 min-h-11 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-900 dark:border-slate-600 dark:bg-slate-800 dark:text-white"></div>
                    @if ($isAdmin)
                        <div><label for="pipeline-assignee" class="block text-sm font-bold">PIC <span class="text-red-600">*</span></label><select id="pipeline-assignee" name="assigned_cs_id" x-model="detailsAssigneeId" required class="mt-1.5 min-h-11 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-900 dark:border-slate-600 dark:bg-slate-800 dark:text-white">@foreach ($csList as $cs)<option value="{{ $cs->id }}" @if (! $cs->is_active || ! $cs->user?->is_active) :disabled="detailsAssigneeId !== '{{ $cs->id }}'" @endif>{{ $cs->name }}{{ $cs->is_active && $cs->user?->is_active ? '' : ' (nonaktif)' }}</option>@endforeach</select></div>
                    @endif
                </div>
                <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-between">
                    <a :href="details?.showUrl || '#'" class="inline-flex min-h-11 items-center justify-center rounded-xl px-3 text-sm font-bold text-[#3166b1] hover:bg-blue-50 dark:text-blue-300 dark:hover:bg-slate-800">Lihat riwayat lengkap</a>
                    <div class="flex flex-col-reverse gap-2 sm:flex-row"><button type="button" @click="$refs.detailsDialog.close()" class="min-h-11 rounded-xl border border-slate-300 px-4 text-sm font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800">Batal</button><button type="submit" class="min-h-11 rounded-xl bg-[#3166b1] px-5 text-sm font-bold text-white hover:bg-[#245392]">Simpan data</button></div>
                </div>
            </form>
        </dialog>

        <dialog x-ref="stageDialog" @close="selected = null" aria-labelledby="pipeline-dialog-title" class="w-[calc(100%-2rem)] max-w-lg rounded-2xl border border-slate-200 bg-white p-0 text-slate-900 shadow-2xl backdrop:bg-[#102b52]/55 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
            <form method="POST" :action="selected?.url || '#'" @submit="savePosition()" class="p-5 sm:p-6">
                @csrf @method('PATCH')
                <input type="hidden" name="lead_id" :value="selected?.id || ''">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-[.16em] text-[#3166b1]">Pindah tahap</p>
                        <h2 id="pipeline-dialog-title" class="mt-1 text-xl font-bold text-[#102b52] dark:text-white" x-text="selected?.name || 'Lead'"></h2>
                    </div>
                    <button type="button" @click="$refs.stageDialog.close()" aria-label="Tutup" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800"><i data-lucide="x" class="h-5 w-5" aria-hidden="true"></i></button>
                </div>

                <label for="pipeline-stage" class="mt-5 block text-sm font-bold text-slate-800 dark:text-slate-200">Tahap tujuan <span class="text-red-600">*</span></label>
                <select id="pipeline-stage" name="stage" x-model="targetStage" required autofocus class="mt-1.5 min-h-11 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-900 focus:border-[#3166b1] focus:ring-[#3166b1] dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                    <option value="">Pilih tahap</option>
                    @foreach ($stageLabels as $stage => $label)
                        <option value="{{ $stage }}">{{ $label }}</option>
                    @endforeach
                </select>

                <div x-show="targetStage === 'berhasil'" class="mt-4 space-y-3 rounded-xl border border-emerald-200 bg-emerald-50/70 p-4 dark:border-emerald-900 dark:bg-emerald-950/30">
                    <p class="text-xs font-semibold text-emerald-800 dark:text-emerald-300">Isi bukti konfirmasi sebelum menyimpan.</p>
                    <div><label for="pipeline-confirmed-on" class="block text-xs font-bold text-slate-700 dark:text-slate-200">Tanggal konfirmasi <span class="text-red-600">*</span></label><input id="pipeline-confirmed-on" type="date" name="confirmed_on" x-model="confirmedOn" :required="targetStage === 'berhasil'" :disabled="targetStage !== 'berhasil'" class="mt-1 min-h-11 w-full rounded-lg border border-slate-300 bg-white px-3 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"></div>
                    <div><label for="pipeline-confirmation-note" class="block text-xs font-bold text-slate-700 dark:text-slate-200">Catatan konfirmasi <span class="text-red-600">*</span></label><textarea id="pipeline-confirmation-note" name="confirmation_note" x-model="confirmationNote" :required="targetStage === 'berhasil'" :disabled="targetStage !== 'berhasil'" rows="3" maxlength="3000" placeholder="Contoh: DP diterima dan jadwal disepakati" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"></textarea></div>
                    <div><label for="pipeline-booking-reference" class="block text-xs font-bold text-slate-700 dark:text-slate-200">Nomor booking <span class="font-normal text-slate-500 dark:text-slate-400">(opsional)</span></label><input id="pipeline-booking-reference" type="text" name="booking_reference" x-model="bookingReference" :disabled="targetStage !== 'berhasil'" maxlength="255" class="mt-1 min-h-11 w-full rounded-lg border border-slate-300 bg-white px-3 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"></div>
                </div>
                <div x-show="targetStage === 'gagal'" class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800">
                    <label for="pipeline-lost-reason" class="block text-xs font-bold text-slate-700 dark:text-slate-200">Alasan tidak berlanjut <span class="text-red-600">*</span></label>
                    <textarea id="pipeline-lost-reason" name="lost_reason" x-model="lostReason" :required="targetStage === 'gagal'" :disabled="targetStage !== 'gagal'" rows="3" maxlength="3000" placeholder="Contoh: Rencana perjalanan ditunda" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"></textarea>
                </div>

                <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                    <button type="button" @click="$refs.stageDialog.close()" class="min-h-11 rounded-xl border border-slate-300 px-4 text-sm font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800">Batal</button>
                    <button type="submit" class="min-h-11 rounded-xl bg-[#3166b1] px-5 text-sm font-bold text-white hover:bg-[#245392]">Simpan tahap</button>
                </div>
            </form>
        </dialog>
    </div>
</x-dynamic-component>
