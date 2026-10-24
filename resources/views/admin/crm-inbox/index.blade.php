<x-admin-layout title="Inbox Email CRM">
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Inbox Email CRM</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Pertanyaan baru ke web@izitravel.id untuk ditinjau admin.</p>
        </div>
    </x-slot>

    <div class="crm-page space-y-5">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 text-sm shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <p class="font-bold text-slate-900 dark:text-white">Status sinkronisasi</p>
            <p class="mt-1 text-slate-600 dark:text-slate-300">
                @if ($mailbox?->last_synced_at)
                    Terakhir diperiksa {{ $mailbox->last_synced_at->copy()->timezone('Asia/Makassar')->format('d M Y H:i') }} WITA.
                @else
                    Belum pernah tersambung. Pemeriksaan pertama akan mulai dari email baru setelah aktivasi.
                @endif
            </p>
            @if ($mailbox?->last_error)<p role="alert" class="mt-2 text-red-700 dark:text-red-300">Kesalahan terakhir: {{ $mailbox->last_error }}</p>@endif
        </section>

        <nav aria-label="Filter email" class="flex flex-wrap gap-2">
            @foreach (['new' => 'Baru', 'linked' => 'Tertaut', 'ignored' => 'Diabaikan'] as $value => $label)
                <a href="{{ route('admin.crm.inbox.index', ['status' => $value]) }}" @if ($status === $value) aria-current="page" @endif class="rounded-xl border px-4 py-2 text-sm font-bold {{ $status === $value ? 'border-[#3166b1] bg-[#3166b1] text-white' : 'border-slate-300 bg-white text-slate-700 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200' }}">{{ $label }}</a>
            @endforeach
        </nav>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            @forelse ($emails as $email)
                <a href="{{ route('admin.crm.inbox.show', $email) }}" class="block border-b border-slate-100 p-4 transition hover:bg-blue-50/60 focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#3166b1] dark:border-slate-700 dark:hover:bg-slate-700/50 sm:p-5">
                    <div class="flex flex-wrap items-start justify-between gap-2"><p class="text-sm font-extrabold text-slate-900 dark:text-white">{{ $email->subject ?: '(Tanpa subjek)' }}</p><time class="text-xs text-slate-500 dark:text-slate-400">{{ $email->received_at?->copy()->timezone('Asia/Makassar')->format('d M Y H:i') ?? 'Waktu tidak tersedia' }} WITA</time></div>
                    <p class="mt-1 text-xs text-slate-600 dark:text-slate-300">{{ $email->from_name ? $email->from_name.' · ' : '' }}{{ $email->from_email }}@if ($email->lead) · Lead: {{ $email->lead->display_name }}@endif</p>
                    <p class="mt-2 line-clamp-2 text-sm text-slate-600 dark:text-slate-300">{{ $email->body_text ?: 'Tidak ada isi teks.' }}</p>
                </a>
            @empty
                <p class="p-8 text-center text-sm text-slate-600 dark:text-slate-300">Belum ada email dalam kategori ini.</p>
            @endforelse
        </div>
        {{ $emails->links() }}
    </div>
</x-admin-layout>
