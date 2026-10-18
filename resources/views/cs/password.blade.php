<x-cs-layout title="Ganti kata sandi">
    <x-slot name="header"><h1 class="text-xl font-bold">Ganti kata sandi</h1></x-slot>
    <div class="max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm text-slate-600 mb-6">{{ auth()->user()->must_change_password ? 'Sebelum mulai bekerja, ganti kata sandi sementara Anda.' : 'Perbarui kata sandi akun Anda.' }}</p>
        <form method="POST" action="{{ route('cs.password.update') }}" class="space-y-4">@csrf @method('PUT')
            <div><label for="current_password" class="block text-sm font-bold mb-1">Kata sandi saat ini</label><input id="current_password" name="current_password" type="password" autocomplete="current-password" required class="w-full rounded-xl border-slate-300"></div>
            <div><label for="password" class="block text-sm font-bold mb-1">Kata sandi baru</label><input id="password" name="password" type="password" autocomplete="new-password" required class="w-full rounded-xl border-slate-300"></div>
            <div><label for="password_confirmation" class="block text-sm font-bold mb-1">Ulangi kata sandi baru</label><input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="w-full rounded-xl border-slate-300"></div>
            <button class="w-full rounded-xl bg-[#3166b1] px-5 py-3 text-sm font-bold text-white hover:bg-[#24548f]">Simpan kata sandi</button>
        </form>
    </div>
</x-cs-layout>
