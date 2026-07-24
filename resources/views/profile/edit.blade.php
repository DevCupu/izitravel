<x-admin-layout :title="__('Edit Profil Admin')">
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
            {{ __('Edit Profil Admin') }}
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
            {{ __('Kelola informasi profil akun, email, kata sandi, dan keamanan administrator.') }}
        </p>
    </x-slot>

    <div class="space-y-6 animate-fade-in-up">
        <!-- Banner Summary Profil Admin -->
        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 relative overflow-hidden shadow-sm">
            <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                <!-- Avatar Large Circle -->
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black text-2xl shadow-lg shadow-blue-500/20 shrink-0">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                </div>

                <div class="text-center sm:text-left space-y-1.5 flex-1 min-w-0">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white truncate">
                            {{ Auth::user()->name }}
                        </h3>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 text-[10px] font-black uppercase tracking-wider">
                            <i data-lucide="shield-check" class="w-3 h-3 text-blue-500"></i>
                            Administrator
                        </span>
                    </div>
                    
                    <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center justify-center sm:justify-start gap-1.5">
                        <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400"></i>
                        {{ Auth::user()->email }}
                    </p>

                    <p class="text-[11px] text-slate-400 dark:text-slate-500 pt-1">
                        Terdaftar sejak: {{ Auth::user()->created_at ? Auth::user()->created_at->translatedFormat('d F Y') : '-' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Card 1: Update Informas Profil (Nama & Email) -->
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-2 mb-2 pb-4 border-b border-slate-100 dark:border-slate-700">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Informasi Profil</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Ubah nama akun dan alamat email Anda.</p>
                    </div>
                </div>

                <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('patch')

                    <div class="form-group">
                        <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="w-full text-sm">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Alamat Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="w-full text-sm">
                        @error('email')
                            <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2 flex items-center gap-4">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-xs text-white transition-all shadow-md shadow-blue-500/20">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            Simpan Perubahan
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                                Berhasil disimpan.
                            </p>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Card 2: Update Kata Sandi (Password) -->
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-2 mb-2 pb-4 border-b border-slate-100 dark:border-slate-700">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 flex items-center justify-center shrink-0">
                        <i data-lucide="key-round" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Perbarui Kata Sandi</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Gunakan kata sandi yang kuat demi keamanan sistem.</p>
                    </div>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <label for="update_password_current_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi Saat Ini</label>
                        <input type="password" id="update_password_current_password" name="current_password" autocomplete="current-password" class="w-full text-sm">
                        @error('current_password', 'updatePassword')
                            <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="update_password_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi Baru</label>
                        <input type="password" id="update_password_password" name="password" autocomplete="new-password" class="w-full text-sm">
                        @error('password', 'updatePassword')
                            <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="update_password_password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password" class="w-full text-sm">
                        @error('password_confirmation', 'updatePassword')
                            <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2 flex items-center gap-4">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.97] rounded-xl font-bold text-xs text-white transition-all shadow-md shadow-indigo-500/20">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                            Perbarui Kata Sandi
                        </button>

                        @if (session('status') === 'password-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                                Kata sandi diperbarui.
                            </p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
