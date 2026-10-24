<x-admin-layout :title="$email->subject ?: 'Email masuk'">
    <x-slot name="header">
        <div>
            <a href="{{ route('admin.crm.inbox.index', ['status' => $email->status]) }}" class="text-xs font-bold text-[#3166b1]">← Inbox Email</a>
            <h1 class="mt-1 text-xl font-bold text-slate-900 dark:text-white">{{ $email->subject ?: '(Tanpa subjek)' }}</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Dari {{ $email->from_name ? $email->from_name.' · ' : '' }}{{ $email->from_email }}</p>
        </div>
    </x-slot>

    <div class="crm-page grid gap-5 xl:grid-cols-[minmax(0,1fr)_340px]">
        <section class="min-w-0 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-6">
            @if (session('status'))<p role="status" class="mb-4 rounded-xl bg-emerald-50 p-3 text-sm font-bold text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-200">{{ session('status') }}</p>@endif
            <dl class="grid gap-3 border-b border-slate-200 pb-4 text-sm dark:border-slate-700 sm:grid-cols-2">
                <div><dt class="text-xs text-slate-500">Pengirim</dt><dd class="break-all font-bold">{{ $email->from_email }}</dd></div>
                <div><dt class="text-xs text-slate-500">Diterima</dt><dd class="font-bold">{{ $email->received_at?->copy()->timezone('Asia/Makassar')->format('d M Y H:i') ?? 'Tidak tersedia' }} WITA</dd></div>
            </dl>
            <div class="mt-5 whitespace-pre-wrap break-words text-sm leading-relaxed text-slate-800 dark:text-slate-100">{{ $email->body_text ?: 'Tidak ada isi teks. Lampiran dan format asli dapat dibuka melalui webmail.' }}</div>
            <p class="mt-6 border-t border-slate-200 pt-4 text-xs text-slate-500 dark:border-slate-700 dark:text-slate-400">Lampiran dan balasan email ditangani melalui webmail.</p>
        </section>

        <aside class="space-y-4">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <h2 class="font-bold text-slate-900 dark:text-white">Hubungkan ke lead</h2>
                @if ($email->lead)
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Saat ini tertaut ke <a class="font-bold text-[#3166b1] underline" href="{{ route('admin.crm.leads.show', $email->lead) }}">{{ $email->lead->display_name }}</a>.</p>
                @endif
                <form method="GET" action="{{ route('admin.crm.inbox.show', $email) }}" class="mt-4 flex gap-2">
                    <label for="lead-search" class="sr-only">Cari nama atau nomor lead</label>
                    <input id="lead-search" name="q" value="{{ $query }}" placeholder="Nama atau WhatsApp" maxlength="100" class="min-w-0 flex-1 rounded-xl border-slate-300 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                    <button class="rounded-xl bg-[#3166b1] px-4 text-sm font-bold text-white">Cari</button>
                </form>
                @if ($query !== '')
                    <div class="mt-3 space-y-2">
                        @forelse ($leads as $lead)
                            <form method="POST" action="{{ route('admin.crm.inbox.link', $email) }}" class="flex items-center justify-between gap-2 rounded-xl border border-slate-200 p-3 dark:border-slate-700">@csrf
                                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                <span class="min-w-0 text-xs"><strong class="block truncate">{{ $lead->display_name }}</strong><span class="text-slate-500">{{ $lead->contact->phone_normalized }} · {{ $lead->cs?->name }}</span></span>
                                <button class="shrink-0 text-xs font-bold text-[#3166b1]">Tautkan</button>
                            </form>
                        @empty
                            <p class="text-xs text-slate-500">Lead tidak ditemukan.</p>
                        @endforelse
                    </div>
                @endif
                @if ($email->status === 'new')
                    <a href="{{ route('admin.crm.leads.create', ['inbound_email' => $email->id]) }}" class="mt-4 inline-flex min-h-10 items-center rounded-xl border border-[#3166b1] px-4 text-xs font-bold text-[#3166b1]">Buat lead dari email ini</a>
                    <p class="mt-2 text-xs text-slate-500">Nomor WhatsApp dan CS harus diisi sebelum lead dibuat.</p>
                @endif
                @error('lead_id')<p role="alert" class="mt-2 text-xs text-red-700">{{ $message }}</p>@enderror
            </section>
            <section class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-800">
                @if ($email->status === 'ignored')
                    <form method="POST" action="{{ route('admin.crm.inbox.reopen', $email) }}">@csrf<button class="text-sm font-bold text-[#3166b1]">Kembalikan ke inbox baru</button></form>
                @else
                    <form method="POST" action="{{ route('admin.crm.inbox.ignore', $email) }}">@csrf<button class="text-sm font-bold text-red-700 dark:text-red-300">Tandai tidak perlu ditangani</button></form>
                @endif
            </section>
        </aside>
    </div>
</x-admin-layout>
