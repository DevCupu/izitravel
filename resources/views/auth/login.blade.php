<x-guest-layout>
    <div class="text-center mb-8">
        <span class="bg-blue-50 text-blue-600 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider shadow-sm shadow-blue-500/5">
            Selamat Datang Kembali
        </span>
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight mt-4">
            Masuk ke Akun Anda
        </h1>
        <p class="text-slate-500 text-sm mt-2 font-light">
            Kelola perjalanan ibadah Umrah &amp; Haji Anda bersama IZI Travel.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>



        <x-primary-button class="w-full">
            {{ __('Log in') }}
        </x-primary-button>
    </form>
</x-guest-layout>
