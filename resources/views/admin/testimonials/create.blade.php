<x-admin-layout :title="__('Tambah Testimoni')">
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.testimonials.index') }}" class="p-1.5 rounded-lg text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">{{ __('Tambah Testimoni') }}</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">{{ __('Tambahkan testimoni jamaah baru untuk ditampilkan pada landing page.') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl animate-fade-in-up">
        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.testimonials.store') }}" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @php($testimonial = new \App\Models\Testimonial())
                @include('admin.testimonials._form')

                <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-sm text-white transition-all duration-150 shadow-lg shadow-blue-500/20">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        {{ __('Simpan Testimoni') }}
                    </button>
                    <a href="{{ route('admin.testimonials.index') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        {{ __('Batal') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
