<x-cs-layout title="Panduan CS">
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Panduan CS</h1>
            <p class="text-xs text-slate-500">Cara singkat memakai CRM IZI Travel</p>
        </div>
    </x-slot>

    <div class="max-w-5xl space-y-6">
        <section class="rounded-2xl bg-[#102b52] p-5 text-white sm:p-7" aria-labelledby="guide-intro">
            <p class="text-xs font-bold uppercase tracking-wider text-blue-200">Mulai bekerja</p>
            <h2 id="guide-intro" class="mt-2 text-2xl font-bold">Dari chat masuk sampai hasil akhir</h2>
            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-blue-100">Catat calon jemaah yang benar-benar menghubungi Anda. Setelah itu, simpan hasil percakapan dan jadwalkan langkah berikutnya agar tidak terlewat.</p>
            <a href="{{ route('cs.leads.create') }}" class="mt-5 inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-white px-5 text-sm font-bold text-[#102b52] hover:bg-blue-50">
                <i data-lucide="plus" class="h-4 w-4" aria-hidden="true"></i>Catat calon jemaah
            </a>
        </section>

        <section aria-labelledby="steps-heading">
            <div class="mb-4">
                <h2 id="steps-heading" class="text-xl font-bold text-slate-900">Empat langkah kerja CS</h2>
                <p class="mt-1 text-sm text-slate-600">Ikuti urutannya setiap kali ada calon jemaah baru.</p>
            </div>
            <ol class="grid gap-4 md:grid-cols-2">
                <li class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-start gap-4">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-sm font-extrabold text-[#3166b1]">1</span>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Catat calon jemaah</h3>
                            <p class="mt-1 text-sm leading-relaxed text-slate-600">Isi nama dan nomor WhatsApp setelah orang tersebut menghubungi Anda. Tambahkan kota, bulan rencana, dan kebutuhan jika diketahui. Pilih <strong>Lainnya</strong> untuk menulis kebutuhan sendiri.</p>
                            <a href="{{ route('cs.leads.create') }}" class="mt-4 inline-flex min-h-11 items-center gap-1 text-sm font-bold text-[#3166b1] hover:underline">Buka formulir lead <i data-lucide="arrow-right" class="h-4 w-4" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </li>
                <li class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-start gap-4">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-sm font-extrabold text-[#3166b1]">2</span>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Hubungi dan buat tugas</h3>
                            <p class="mt-1 text-sm leading-relaxed text-slate-600">Jadwalkan tindak lanjut saat mencatat lead, atau buat dari detail lead setelah menghubungi calon jemaah. Jam yang tampil menggunakan WITA.</p>
                            <a href="{{ route('cs.tasks') }}" class="mt-4 inline-flex min-h-11 items-center gap-1 text-sm font-bold text-[#3166b1] hover:underline">Lihat tugas saya <i data-lucide="arrow-right" class="h-4 w-4" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </li>
                <li class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-start gap-4">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-sm font-extrabold text-[#3166b1]">3</span>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Perbarui perkembangan</h3>
                            <p class="mt-1 text-sm leading-relaxed text-slate-600">Setelah berbicara, tambah catatan dan pilih tahap yang sesuai. Selesaikan tugas sambil menjadwalkan langkah berikutnya agar lead tidak terlewat.</p>
                            <a href="{{ route('cs.pipeline') }}" class="mt-4 inline-flex min-h-11 items-center gap-1 text-sm font-bold text-[#3166b1] hover:underline">Lihat pipeline <i data-lucide="arrow-right" class="h-4 w-4" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </li>
                <li class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-start gap-4">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-sm font-extrabold text-[#3166b1]">4</span>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Catat hasil akhir</h3>
                            <p class="mt-1 text-sm leading-relaxed text-slate-600">Jika <strong>Berhasil</strong>, isi tanggal dan catatan konfirmasi; nomor booking boleh dikosongkan. Jika <strong>Gagal</strong>, tulis alasannya. Minta admin jika hasil Berhasil perlu dikoreksi.</p>
                            <a href="{{ route('cs.leads.index') }}" class="mt-4 inline-flex min-h-11 items-center gap-1 text-sm font-bold text-[#3166b1] hover:underline">Buka daftar lead <i data-lucide="arrow-right" class="h-4 w-4" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </li>
            </ol>
        </section>

        <aside class="rounded-2xl border border-amber-200 bg-[#fff9db] p-5 text-sm text-amber-950 sm:p-6" aria-label="Jika nomor sudah tercatat">
            <p class="font-bold">Nomor WhatsApp sudah tercatat?</p>
            <p class="mt-1 leading-relaxed">Periksa lead sebelumnya. Jika orang yang sama bertanya tentang perjalanan baru, Anda tetap bisa membuat lead baru dengan mencentang konfirmasi pada formulir.</p>
        </aside>
    </div>
</x-cs-layout>
