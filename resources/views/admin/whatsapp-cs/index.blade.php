<x-admin-layout :title="__('Tim CS WhatsApp')">
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Tim CS WhatsApp</h2>
            <p class="mt-0.5 hidden text-xs text-slate-500 sm:block dark:text-slate-400">Nomor penerima chat dan akun CRM.</p>
        </div>
    </x-slot>

    <div class="space-y-5 animate-fade-in">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-slate-600 dark:text-slate-300">Kelola akses dan pembagian chat untuk setiap CS.</p>
            <a href="{{ route('admin.whatsapp-cs.create') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#3166b1] px-4 py-2 text-sm font-bold text-white hover:bg-[#24548f] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#3166b1]"><i data-lucide="plus" class="h-4 w-4" aria-hidden="true"></i>Tambah CS</a>
        </div>
        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 sm:p-5 dark:border-blue-900 dark:bg-blue-950/30">
            <p class="text-sm font-bold text-[#102b52] dark:text-blue-100">Agar CS bisa menggunakan CRM</p>
            <ol class="mt-3 grid gap-3 text-sm text-slate-700 sm:grid-cols-2 dark:text-slate-200">
                <li class="flex gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#3166b1] text-xs font-bold text-white">1</span><span>Tambahkan nama dan nomor WhatsApp CS untuk pembagian chat.</span></li>
                <li class="flex gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#3166b1] text-xs font-bold text-white">2</span><span>Buat akun login CS, lalu berikan email dan kata sandi sementara kepadanya.</span></li>
            </ol>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Daftar CS</h3>
            @include('admin.partials._search', ['action' => route('admin.whatsapp-cs.index'), 'value' => $search, 'placeholder' => __('Cari nama atau nomor...')])
        </div>

        @if ($csList->count())
            <div data-record-views data-default-view="card">
            <div data-record-cards class="grid gap-4 lg:grid-cols-2">
                @foreach ($csList as $cs)
                    <article class="content-card rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-800">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h4 class="text-lg font-bold text-slate-900 dark:text-white">{{ $cs->name }}</h4>
                                <a href="{{ 'https://wa.me/'.preg_replace('/[^0-9]/', '', $cs->phone) }}" target="_blank" rel="noopener noreferrer" class="mt-1 inline-flex items-center gap-1 text-sm font-semibold text-[#3166b1] hover:underline dark:text-blue-300">
                                    {{ $cs->phone }}<i data-lucide="external-link" class="h-3.5 w-3.5" aria-hidden="true"></i><span class="sr-only">Buka WhatsApp</span>
                                </a>
                            </div>
                            <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $cs->is_active ? 'bg-emerald-50 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-200' }}">{{ $cs->is_active ? 'Menerima chat' : 'Chat nonaktif' }}</span>
                        </div>

                        <div class="mt-5 rounded-xl border p-4 {{ $cs->user ? ($cs->user->is_active ? 'border-blue-100 bg-blue-50/60 dark:border-blue-900 dark:bg-blue-950/20' : 'border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-900/30') : 'border-amber-200 bg-amber-50 dark:border-amber-900 dark:bg-amber-950/20' }}">
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">Akun login CRM</p>
                            @if ($cs->user)
                                <p class="mt-1 text-sm font-bold {{ $cs->user->is_active ? 'text-[#102b52] dark:text-blue-100' : 'text-slate-700 dark:text-slate-200' }}">{{ $cs->user->is_active ? 'Aktif' : 'Nonaktif' }} <span class="font-normal">· {{ $cs->user->email }}</span></p>
                            @else
                                <p class="mt-1 text-sm font-bold text-amber-900 dark:text-amber-200">Belum dibuat</p>
                                <p class="mt-1 text-xs text-amber-800 dark:text-amber-300">CS ini belum bisa masuk ke CRM.</p>
                            @endif
                        </div>

                        <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                            <p class="text-xs text-slate-500 dark:text-slate-400">Porsi pembagian chat: <strong class="text-slate-700 dark:text-slate-200">{{ $cs->weight }}</strong></p>
                            <div class="flex items-center gap-2">
                                @unless ($cs->user_id || $cs->leads_exists)
                                    <form x-data method="POST" action="{{ route('admin.whatsapp-cs.destroy', $cs) }}">
                                        @csrf @method('DELETE')
                                        <button type="button" @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Hapus data CS ini?' })" class="min-h-11 rounded-xl px-3 text-xs font-bold text-red-700 hover:bg-red-50 dark:text-red-300 dark:hover:bg-red-950/30">Hapus</button>
                                    </form>
                                @endunless
                                <a href="{{ route('admin.whatsapp-cs.edit', $cs) }}" class="inline-flex min-h-11 items-center justify-center gap-1.5 rounded-xl bg-[#3166b1] px-4 text-sm font-bold text-white hover:bg-[#24548f]">{{ $cs->user ? 'Kelola CS' : 'Buat akun login' }}<i data-lucide="arrow-right" class="h-4 w-4" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div data-record-table hidden class="overflow-x-auto rounded-2xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
                <table class="w-full min-w-[730px] text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 text-xs text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300"><tr><th scope="col" class="px-4 py-3">CS</th><th scope="col" class="px-4 py-3">WhatsApp</th><th scope="col" class="px-4 py-3">Status chat</th><th scope="col" class="px-4 py-3">Akun CRM</th><th scope="col" class="px-4 py-3">Porsi</th><th scope="col" class="px-4 py-3">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach ($csList as $cs)
                            <tr class="text-slate-700 dark:text-slate-200"><th scope="row" class="px-4 py-3 font-bold">{{ $cs->name }}</th><td class="px-4 py-3"><a href="{{ 'https://wa.me/'.preg_replace('/[^0-9]/', '', $cs->phone) }}" target="_blank" rel="noopener noreferrer" class="font-bold text-[#3166b1] hover:underline">{{ $cs->phone }}</a></td><td class="px-4 py-3">{{ $cs->is_active ? 'Menerima chat' : 'Chat nonaktif' }}</td><td class="px-4 py-3">{{ $cs->user ? ($cs->user->is_active ? 'Aktif' : 'Nonaktif') : 'Belum dibuat' }}@if ($cs->user)<span class="block text-xs text-slate-500">{{ $cs->user->email }}</span>@endif</td><td class="px-4 py-3">{{ $cs->weight }}</td><td class="whitespace-nowrap px-4 py-3"><a href="{{ route('admin.whatsapp-cs.edit', $cs) }}" class="font-bold text-[#3166b1] hover:underline">{{ $cs->user ? 'Kelola CS' : 'Buat akun login' }}</a>@unless ($cs->user_id || $cs->leads_exists)<form x-data method="POST" action="{{ route('admin.whatsapp-cs.destroy', $cs) }}" class="inline">@csrf @method('DELETE')<button type="button" @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Hapus data CS ini?' })" class="ml-3 font-bold text-red-700 hover:underline">Hapus</button></form>@endunless</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
        @else
            <div class="content-card rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center dark:border-slate-700 dark:bg-slate-800">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $search ? 'CS tidak ditemukan' : 'Belum ada CS WhatsApp' }}</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $search ? 'Coba nama atau nomor lain.' : 'Tambahkan CS pertama untuk menerima chat dan mengelola lead.' }}</p>
                @unless ($search)<a href="{{ route('admin.whatsapp-cs.create') }}" class="mt-4 inline-flex min-h-11 items-center rounded-xl bg-[#3166b1] px-4 text-sm font-bold text-white">Tambah CS pertama</a>@endunless
            </div>
        @endif

        @if ($csList->hasPages()) <div>{{ $csList->links() }}</div> @endif
    </div>
</x-admin-layout>
