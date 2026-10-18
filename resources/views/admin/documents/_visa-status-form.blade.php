<form method="POST" action="{{ route('admin.documents.visa-status.update', $registration) }}">
    @csrf @method('PATCH')
    <label for="visa-status-{{ $registration->id }}-{{ $surface }}" class="sr-only">Status visa {{ $registration->jemaah->name }}</label>
    <select id="visa-status-{{ $registration->id }}-{{ $surface }}" name="visa_status" onchange="this.form.submit()" class="w-full min-w-[185px] rounded-lg border px-2.5 py-2 text-xs font-bold focus:border-blue-500 focus:ring-blue-500 {{ match($registration->visa_status) { 'completed' => 'border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300', 'mofa_issued' => 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-800 dark:bg-amber-900/30 dark:text-amber-300', 'kemenag_process' => 'border-blue-200 bg-blue-50 text-blue-800 dark:border-blue-800 dark:bg-blue-900/30 dark:text-blue-300', default => 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200' } }}">
        @foreach (\App\Models\Registration::VISA_STATUSES as $visaKey => $visaLabel)<option value="{{ $visaKey }}" @selected($registration->visa_status === $visaKey)>{{ $visaLabel }}</option>@endforeach
    </select>
</form>
