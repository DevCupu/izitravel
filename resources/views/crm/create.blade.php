<x-dynamic-component :component="$isAdmin ? 'admin-layout' : 'cs-layout'" title="Catat calon jemaah">
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Catat calon jemaah</h1>
            <p class="text-xs text-slate-500">Buat lead setelah calon jemaah benar-benar menghubungi CS</p>
        </div>
    </x-slot>

    <div class="max-w-3xl space-y-5">
        <div class="rounded-2xl bg-[#102b52] p-5 text-white sm:p-6">
            <p class="text-xs font-bold uppercase tracking-wide text-blue-200">Lead baru</p>
            <h2 class="mt-1 text-xl font-bold">Mulai dari data yang sudah ada</h2>
            <p class="mt-1 text-sm leading-relaxed text-blue-100">Nama dan nomor WhatsApp cukup untuk mulai. Paket, sumber, dan tindak lanjut bisa dilengkapi setelah lead tersimpan.</p>
        </div>

        <form method="POST" action="{{ route($routePrefix.'leads.store') }}" x-data="{ needType: @js(old('need_type', old('needs_summary') ? 'other' : '')) }" class="space-y-5">
            @csrf

            @if (session('duplicate_warning'))
                <div role="alert" class="rounded-2xl border border-amber-300 bg-amber-50 p-4 text-sm text-amber-950 sm:p-5">
                    <h2 class="font-bold">Nomor WhatsApp ini sudah pernah dicatat</h2>
                    <p class="mt-1">Periksa lead sebelumnya. Jika ini rencana perjalanan baru, centang konfirmasi di bawah lalu simpan lagi. Jika nomornya salah, perbaiki nomor tanpa mencentang.</p>
                    @if (session('duplicate_leads'))
                        <ul class="mt-3 space-y-2">
                            @foreach (session('duplicate_leads') as $previous)
                                <li><a class="font-bold underline underline-offset-2" href="{{ route($routePrefix.'leads.show', $previous['id']) }}">{{ $previous['display_name'] }} · {{ $stageLabels[$previous['stage']] ?? $previous['stage'] }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            <section aria-labelledby="basic-heading" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                <div class="mb-5">
                    <h2 id="basic-heading" class="text-lg font-bold text-slate-900">Data utama</h2>
                    <p class="mt-1 text-sm text-slate-600">Isi data dari percakapan yang sudah masuk. Tanda * berarti wajib.</p>
                </div>
                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-800">Nama calon jemaah <span class="text-red-700">*</span></label>
                        <input id="name" name="name" type="text" autocomplete="name" value="{{ old('name') }}" required maxlength="255" placeholder="Contoh: Ibu Siti" @error('name') aria-invalid="true" aria-describedby="name-error" @enderror class="mt-2 w-full min-h-11 rounded-xl border-slate-300 text-base">
                        @error('name')<p id="name-error" class="mt-1.5 text-sm font-semibold text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-bold text-slate-800">Nomor WhatsApp <span class="text-red-700">*</span></label>
                        <input id="phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" value="{{ old('phone') }}" required maxlength="30" placeholder="Contoh: 0812 3456 7890" aria-describedby="phone-help @error('phone') phone-error @enderror" @error('phone') aria-invalid="true" @enderror class="mt-2 w-full min-h-11 rounded-xl border-slate-300 text-base">
                        <p id="phone-help" class="mt-1.5 text-xs text-slate-600">Boleh diawali 08 atau 62. Nomor akan dirapikan otomatis saat disimpan.</p>
                        @error('phone')<p id="phone-error" class="mt-1.5 text-sm font-semibold text-red-700">{{ $message }}</p>@enderror
                    </div>
                    @if ($isAdmin)
                        <div>
                            <label for="assigned_cs_id" class="block text-sm font-bold text-slate-800">CS penanggung jawab <span class="text-red-700">*</span></label>
                            <select id="assigned_cs_id" name="assigned_cs_id" required @error('assigned_cs_id') aria-invalid="true" aria-describedby="assigned-cs-error" @enderror class="mt-2 w-full min-h-11 rounded-xl border-slate-300 text-base">
                                <option value="">Pilih CS</option>
                                @foreach ($csList as $cs)
                                    @if ($cs->is_active && $cs->user?->is_active)<option value="{{ $cs->id }}" @selected(old('assigned_cs_id') == $cs->id)>{{ $cs->name }}</option>@endif
                                @endforeach
                            </select>
                            @error('assigned_cs_id')<p id="assigned-cs-error" class="mt-1.5 text-sm font-semibold text-red-700">{{ $message }}</p>@enderror
                            @unless ($csList->contains(fn ($cs) => $cs->is_active && $cs->user?->is_active))
                                <p class="mt-2 text-sm text-amber-800">Belum ada akun CS aktif. <a href="{{ route('admin.whatsapp-cs.index') }}" class="font-bold underline">Atur tim dan akun CS</a> terlebih dahulu.</p>
                            @endunless
                        </div>
                    @endif
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="city" class="block text-sm font-bold text-slate-800">Kota domisili <span class="font-normal text-slate-500">(opsional)</span></label>
                            <input id="city" name="city" type="text" autocomplete="address-level2" value="{{ old('city') }}" maxlength="120" placeholder="Contoh: Makassar" @error('city') aria-invalid="true" aria-describedby="city-error" @enderror class="mt-2 w-full min-h-11 rounded-xl border-slate-300 text-base">
                            @error('city')<p id="city-error" class="mt-1.5 text-sm font-semibold text-red-700">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="planned_departure_month" class="block text-sm font-bold text-slate-800">Rencana umrah bulan <span class="font-normal text-slate-500">(opsional)</span></label>
                            <input id="planned_departure_month" name="planned_departure_month" type="month" value="{{ old('planned_departure_month') }}" @error('planned_departure_month') aria-invalid="true" aria-describedby="departure-error" @enderror class="mt-2 w-full min-h-11 rounded-xl border-slate-300 text-base">
                            <p class="mt-1 text-xs text-slate-600">Pilih bulan dan tahun jika sudah ada rencana.</p>
                            @error('planned_departure_month')<p id="departure-error" class="mt-1.5 text-sm font-semibold text-red-700">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label for="need_type" class="block text-sm font-bold text-slate-800">Kebutuhan utama <span class="font-normal text-slate-500">(opsional)</span></label>
                        <select id="need_type" name="need_type" x-model="needType" @error('need_type') aria-invalid="true" aria-describedby="need-type-error" @enderror class="mt-2 w-full min-h-11 rounded-xl border-slate-300 text-base">
                            <option value="">Belum diketahui</option>
                            @foreach (\App\Models\Lead::NEED_TYPES as $key => $label)<option value="{{ $key }}" @selected(old('need_type') === $key)>{{ $label }}</option>@endforeach
                        </select>
                        <p class="mt-1 text-xs text-slate-600">Pilih Lainnya jika kebutuhannya tidak ada di daftar.</p>
                        @error('need_type')<p id="need-type-error" class="mt-1.5 text-sm font-semibold text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <div x-show="needType === 'other'" x-cloak>
                        <label for="needs_summary" class="block text-sm font-bold text-slate-800">Tulis kebutuhan lainnya <span class="text-red-700">*</span></label>
                        <textarea id="needs_summary" name="needs_summary" rows="3" maxlength="3000" x-bind:disabled="needType !== 'other'" x-bind:required="needType === 'other'" placeholder="Tulis kebutuhan calon jemaah secara singkat" @error('needs_summary') aria-invalid="true" aria-describedby="needs-error" @enderror class="mt-2 w-full rounded-xl border-slate-300 text-base">{{ old('needs_summary') }}</textarea>
                        @error('needs_summary')<p id="needs-error" class="mt-1.5 text-sm font-semibold text-red-700">{{ $message }}</p>@enderror
                    </div>
                </div>
            </section>

            <details class="rounded-2xl border border-slate-200 bg-white shadow-sm" @if (old('package_id') || old('campaign_id') || old('campaign_ad_id') || $errors->has('package_id') || $errors->has('campaign_id') || $errors->has('campaign_ad_id')) open @endif>
                <summary class="flex min-h-14 cursor-pointer items-center justify-between gap-3 px-5 py-4 text-sm font-bold text-[#3166b1] marker:content-none sm:px-7">
                    <span class="flex items-center gap-2"><i data-lucide="sliders-horizontal" class="h-4 w-4" aria-hidden="true"></i>Tambah paket atau sumber (opsional)</span>
                    <i data-lucide="chevron-down" class="h-4 w-4 shrink-0" aria-hidden="true"></i>
                </summary>
                <div class="grid gap-4 border-t border-slate-100 px-5 pb-6 pt-5 sm:grid-cols-2 sm:px-7">
                    <div class="sm:col-span-2">
                        <label for="package_id" class="block text-sm font-bold text-slate-800">Paket yang diminati</label>
                        <select id="package_id" name="package_id" @error('package_id') aria-invalid="true" aria-describedby="package-error" @enderror class="mt-2 w-full min-h-11 rounded-xl border-slate-300">
                            <option value="">Belum diketahui</option>
                            @foreach ($packages as $package)<option value="{{ $package->id }}" @selected(old('package_id') == $package->id)>{{ $package->name }}</option>@endforeach
                        </select>
                        @error('package_id')<p id="package-error" class="mt-1.5 text-sm font-semibold text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="campaign_id" class="block text-sm font-bold text-slate-800">Campaign</label>
                        <select id="campaign_id" name="campaign_id" @error('campaign_id') aria-invalid="true" aria-describedby="campaign-error" @enderror class="mt-2 w-full min-h-11 rounded-xl border-slate-300">
                            <option value="">Tidak diketahui</option>
                            @foreach ($campaigns as $campaign)<option value="{{ $campaign->id }}" @selected(old('campaign_id') == $campaign->id)>{{ $campaign->name }}</option>@endforeach
                        </select>
                        @error('campaign_id')<p id="campaign-error" class="mt-1.5 text-sm font-semibold text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="campaign_ad_id" class="block text-sm font-bold text-slate-800">Iklan</label>
                        <select id="campaign_ad_id" name="campaign_ad_id" @error('campaign_ad_id') aria-invalid="true" aria-describedby="ad-error" @enderror class="mt-2 w-full min-h-11 rounded-xl border-slate-300">
                            <option value="">Tidak diketahui</option>
                            @foreach ($ads as $ad)<option value="{{ $ad->id }}" @selected(old('campaign_ad_id') == $ad->id)>{{ $ad->name }} · {{ $campaigns->firstWhere('id', $ad->campaign_id)?->name }}</option>@endforeach
                        </select>
                        @error('campaign_ad_id')<p id="ad-error" class="mt-1.5 text-sm font-semibold text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <p class="text-xs leading-relaxed text-slate-600 sm:col-span-2">Pilih sumber hanya jika diketahui dari percakapan. Pilihan ini dicatat manual, bukan bukti iklan yang dilihat pelanggan. Jika memilih iklan, pilih campaign yang sama.</p>
                </div>
            </details>

            @if (session('duplicate_warning') || old('confirm_duplicate'))
                <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-amber-300 bg-amber-50 p-4 text-sm font-semibold text-amber-950">
                    <input type="checkbox" name="confirm_duplicate" value="1" @checked(old('confirm_duplicate')) class="mt-0.5 h-5 w-5 shrink-0 rounded border-amber-500 text-[#3166b1]">
                    <span>Saya sudah memeriksa. Ini rencana perjalanan baru, jadi buat lead baru dengan nomor yang sama.</span>
                </label>
            @endif

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <button type="submit" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-[#3166b1] px-6 text-sm font-bold text-white hover:bg-[#24548f]"><i data-lucide="save" class="h-4 w-4" aria-hidden="true"></i>Simpan calon jemaah</button>
                <a href="{{ route($routePrefix.'leads.index') }}" class="inline-flex min-h-12 items-center justify-center rounded-xl border border-slate-300 px-6 text-sm font-bold text-slate-700 hover:bg-slate-50">Kembali ke daftar</a>
            </div>
            <p class="text-xs text-slate-600">Setelah disimpan, Anda bisa menjadwalkan tindak lanjut di halaman detail lead.</p>
        </form>
    </div>
</x-dynamic-component>
