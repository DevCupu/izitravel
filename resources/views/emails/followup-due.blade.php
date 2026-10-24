Assalamu'alaikum {{ $task->assignee->name }},

Tugas follow-up untuk {{ $task->lead->display_name }} telah jatuh tempo.
Jadwal: {{ $task->due_at->copy()->timezone('Asia/Makassar')->format('d M Y H:i') }} WITA
Tugas: {{ $task->description }}

Buka lead: {{ route('cs.leads.show', $task->lead) }}

IZI Travel CRM
