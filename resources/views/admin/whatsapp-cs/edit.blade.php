<x-admin-layout :title="'Kelola '.$cs->name">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.whatsapp-cs.index') }}" aria-label="Kembali ke daftar CS" class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700"><i data-lucide="arrow-left" class="h-5 w-5" aria-hidden="true"></i></a>
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Kelola {{ $cs->name }}</h2>
                <p class="mt-0.5 hidden text-xs text-slate-500 sm:block dark:text-slate-400">Akun login CRM dan pembagian chat WhatsApp.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl space-y-5 animate-fade-in-up">
        <section aria-labelledby="account-heading" class="content-card rounded-2xl border {{ $cs->user ? 'border-slate-200 dark:border-slate-700' : 'border-amber-200 dark:border-amber-900' }} bg-white p-5 sm:p-7 dark:bg-slate-800">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-[#3166b1] dark:text-blue-300">Akun untuk CRM</p>
                    <h3 id="account-heading" class="mt-1 text-xl font-bold text-slate-900 dark:text-white">Akun login CS</h3>
                    <p class="mt-1 max-w-2xl text-sm leading-relaxed text-slate-600 dark:text-slate-300">Dengan akun ini, CS dapat masuk ke <a href="{{ route('login') }}" class="font-bold text-[#3166b1] underline dark:text-blue-300">halaman login</a> dan mengelola lead yang ditugaskan kepadanya.</p>
                </div>
                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $cs->user ? ($cs->user->is_active ? 'bg-emerald-50 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-200') : 'bg-amber-100 text-amber-900 dark:bg-amber-900/40 dark:text-amber-200' }}">{{ $cs->user ? ($cs->user->is_active ? 'Akun aktif' : 'Akun nonaktif') : 'Belum dibuat' }}</span>
            </div>

            @if (session('temporary_password'))
                <div class="mt-5 rounded-xl border border-amber-300 bg-amber-50 p-4 text-sm text-amber-950 dark:border-amber-800 dark:bg-amber-950/30 dark:text-amber-200" role="status">
                    <p class="font-bold">Kata sandi sementara — tampil sekali</p>
                    <p class="mt-2 break-all rounded-lg bg-white px-3 py-2 font-mono text-base font-bold select-all dark:bg-slate-900 dark:text-slate-100">{{ session('temporary_password') }}</p>
                    <p class="mt-2">Salin sekarang dan berikan kepada CS melalui saluran internal. CS wajib menggantinya saat pertama masuk.</p>
                </div>
            @endif

            @if ($cs->user)
                <div class="mt-5 rounded-xl bg-slate-50 p-4 dark:bg-slate-900/40">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Email untuk masuk</p>
                    <p class="mt-1 break-all text-base font-bold text-slate-900 dark:text-white">{{ $cs->user->email }}</p>
                    <p class="mt-1 text-xs text-slate-600 dark:text-slate-300">{{ $cs->user->must_change_password ? 'CS perlu mengganti kata sandi saat masuk.' : 'CS sudah mengganti kata sandinya.' }}</p>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    <form method="POST" action="{{ route('admin.whatsapp-cs.account.reset', $cs) }}">@csrf
                        <button type="submit" class="min-h-11 rounded-xl border border-[#3166b1] px-4 text-sm font-bold text-[#3166b1] hover:bg-blue-50 dark:border-blue-400 dark:text-blue-300 dark:hover:bg-blue-950/30">Buat kata sandi baru</button>
                    </form>
                    <form method="POST" action="{{ route('admin.whatsapp-cs.account.status', $cs) }}">@csrf @method('PATCH')
                        <input type="hidden" name="is_active" value="{{ $cs->user->is_active ? 0 : 1 }}">
                        <button type="submit" class="min-h-11 rounded-xl px-4 text-sm font-bold {{ $cs->user->is_active ? 'bg-red-50 text-red-800 hover:bg-red-100 dark:bg-red-950/30 dark:text-red-300' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 dark:bg-emerald-950/30 dark:text-emerald-300' }}">{{ $cs->user->is_active ? 'Nonaktifkan akun login' : 'Aktifkan akun login' }}</button>
                    </form>
                </div>
                <details class="mt-5 border-t border-slate-200 pt-4 dark:border-slate-700" @if ($errors->has('email')) open @endif>
                    <summary class="cursor-pointer text-sm font-bold text-[#3166b1] dark:text-blue-300">Ubah email login</summary>
                    <form method="POST" action="{{ route('admin.whatsapp-cs.account.email', $cs) }}" class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-end">@csrf @method('PATCH')
                        <div class="flex-1">
                            <label for="account_email" class="block text-sm font-bold text-slate-700 dark:text-slate-200">Email baru</label>
                            <input id="account_email" name="email" type="email" required autocomplete="email" value="{{ old('email', $cs->user->email) }}" @error('email') aria-invalid="true" aria-describedby="account-email-error" @enderror class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                            @error('email')<p id="account-email-error" class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="min-h-11 rounded-xl bg-[#3166b1] px-4 text-sm font-bold text-white hover:bg-[#24548f]">Simpan email</button>
                    </form>
                </details>
            @else
                <div class="mt-5 rounded-xl bg-amber-50 p-4 text-sm text-amber-900 dark:bg-amber-950/20 dark:text-amber-200">CS ini sudah tercatat sebagai penerima chat, tetapi belum bisa masuk ke CRM. Buat akun dengan email yang akan dipakai untuk login.</div>
                <form method="POST" action="{{ route('admin.whatsapp-cs.account.store', $cs) }}" class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-end">@csrf
                    <div class="flex-1">
                        <label for="cs_email" class="block text-sm font-bold text-slate-700 dark:text-slate-200">Email login CS</label>
                        <input id="cs_email" name="email" type="email" required autocomplete="off" value="{{ old('email') }}" placeholder="nama@example.com" @error('email') aria-invalid="true" aria-describedby="cs-email-error" @enderror class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                        @error('email')<p id="cs-email-error" class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="min-h-11 rounded-xl bg-[#3166b1] px-5 text-sm font-bold text-white hover:bg-[#24548f]">Buat akun login CS</button>
                </form>
                <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">Kata sandi sementara akan ditampilkan sekali setelah akun dibuat.</p>
            @endif
        </section>

        <section aria-labelledby="routing-heading" class="content-card rounded-2xl border border-slate-200 bg-white p-5 sm:p-7 dark:border-slate-700 dark:bg-slate-800">
            <div class="mb-6">
                <p class="text-xs font-bold uppercase tracking-wide text-[#3166b1] dark:text-blue-300">Pembagian chat</p>
                <h3 id="routing-heading" class="mt-1 text-xl font-bold text-slate-900 dark:text-white">Nomor WhatsApp dan penerimaan chat</h3>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Pengaturan ini menentukan apakah CS menerima chat dari halaman /chat. Status akun login di atas diatur terpisah.</p>
            </div>
            <form method="POST" action="{{ route('admin.whatsapp-cs.update', $cs->id) }}" class="space-y-6">
                @csrf @method('PUT')
                @include('admin.whatsapp-cs._form')
                <div class="border-t border-slate-200 pt-5 dark:border-slate-700">
                    <button type="submit" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#3166b1] px-5 text-sm font-bold text-white hover:bg-[#24548f]"><i data-lucide="save" class="h-4 w-4" aria-hidden="true"></i>Simpan pengaturan WhatsApp</button>
                </div>
            </form>
        </section>
    </div>
</x-admin-layout>
