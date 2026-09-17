<x-admin-layout :title="__('Ubah Ads')">
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:text-white dark:hover:bg-slate-700 transition"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
            <div><h2 class="text-lg font-bold text-slate-900 dark:text-white">Ubah Ads</h2><p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perbarui identitas link dan template pesan ads.</p></div>
        </div>
    </x-slot>
    <div class="max-w-3xl animate-fade-in-up">
        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.campaigns.ads.update', [$campaign, $ad]) }}" class="space-y-6">
                @csrf
                @method('PUT')
                @include('admin.campaign-ads._form')
                <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 rounded-xl font-bold text-sm text-white transition"><i data-lucide="save" class="w-4 h-4"></i>Simpan Perubahan</button>
                    <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="px-6 py-2.5 rounded-xl font-bold text-sm text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
