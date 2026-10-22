<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignAd;
use App\Models\ChatLog;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\LeadStage;
use App\Models\LeadFollowup;
use App\Models\Package;
use App\Models\WhatsAppCs;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CrmController extends Controller
{
    private const TZ = 'Asia/Makassar';
    private const PIPELINE_PREVIEW_LIMIT = 10;

    private function admin(Request $request): bool { return $request->user()->isAdmin(); }
    private function route(Request $request, string $name): string { return ($this->admin($request) ? 'admin.crm.' : 'cs.').$name; }

    private function visibleLead(Request $request, int $id): Lead
    {
        return Lead::query()->visibleTo($request->user())->findOrFail($id);
    }

    private function viewData(Request $request): array
    {
        $stageOptions = LeadStage::ordered()->get();
        return [
            'isAdmin' => $this->admin($request),
            'routePrefix' => $this->admin($request) ? 'admin.crm.' : 'cs.',
            'packages' => Package::query()->orderBy('name')->get(['id', 'name']),
            'campaigns' => Campaign::query()->orderBy('name')->get(['id', 'name']),
            'ads' => CampaignAd::query()->orderBy('name')->get(['id', 'campaign_id', 'name']),
            'csList' => $this->admin($request) ? WhatsAppCs::query()->with('user')->orderBy('name')->get() : collect(),
            'stageOptions' => $stageOptions,
            'stageLabels' => $stageOptions->pluck('label', 'key')->all(),
        ];
    }

    public function dashboard(Request $request): View
    {
        $today = now(self::TZ)->toDateString();
        $base = Lead::query()->visibleTo($request->user());
        $needsFollowupBase = (clone $base)->needsFollowup();
        $tasks = LeadFollowup::query()->with(['lead.contact', 'lead.cs', 'assignee'])
            ->where('status', 'open')
            ->whereHas('lead', fn ($q) => $q->visibleTo($request->user()))
            ->when(! $this->admin($request), fn ($q) => $q->where('assigned_user_id', $request->user()->id));
        $currentTime = now();
        $overdueCount = (clone $tasks)->where('due_at', '<', $currentTime)->count();
        $dueTodayRange = [$currentTime, Carbon::parse($today, self::TZ)->endOfDay()->utc()];
        $dueTodayCount = (clone $tasks)->whereBetween('due_at', $dueTodayRange)->count();
        $overdue = (clone $tasks)->where('due_at', '<', $currentTime)->orderBy('due_at')->limit(4)->get();
        $dueToday = (clone $tasks)->whereBetween('due_at', $dueTodayRange)->orderBy('due_at')->limit(4)->get();

        $viewData = $this->viewData($request);
        $teamWorkload = collect();
        if ($this->admin($request)) {
            $overdueByCs = LeadFollowup::query()
                ->join('leads', 'leads.id', '=', 'lead_followups.lead_id')
                ->whereNull('leads.deleted_at')->whereNull('lead_followups.deleted_at')
                ->where('lead_followups.status', 'open')->where('lead_followups.due_at', '<', $currentTime)
                ->selectRaw('leads.assigned_cs_id, count(*) as total')
                ->groupBy('leads.assigned_cs_id')->pluck('total', 'assigned_cs_id');
            $withoutTaskByCs = Lead::query()->needsFollowup()
                ->selectRaw('assigned_cs_id, count(*) as total')
                ->groupBy('assigned_cs_id')->pluck('total', 'assigned_cs_id');
            $teamWorkload = $viewData['csList']->map(fn (WhatsAppCs $cs) => [
                'cs' => $cs,
                'overdue' => (int) ($overdueByCs[$cs->id] ?? 0),
                'without_task' => (int) ($withoutTaskByCs[$cs->id] ?? 0),
            ])->filter(fn (array $row) => $row['cs']->is_active || $row['overdue'] || $row['without_task'])
                ->sortByDesc(fn (array $row) => $row['overdue'] * 2 + $row['without_task'])->values();
        }

        return view('crm.dashboard', array_merge($viewData, [
            'overdue' => $overdue, 'overdueCount' => $overdueCount,
            'dueToday' => $dueToday, 'dueTodayCount' => $dueTodayCount,
            'needsFollowupCount' => (clone $needsFollowupBase)->count(),
            'needsFollowup' => (clone $needsFollowupBase)->with(['cs', 'package'])->oldest('created_at')->oldest('id')->limit(4)->get(),
            'teamWorkload' => $teamWorkload,
            'csPhone' => $this->admin($request) ? null : $request->user()->whatsappCs?->phone,
            'newCount' => (clone $base)->where('stage', 'baru')->count(),
            'wonThisMonth' => (clone $base)->where('stage', 'berhasil')->whereBetween('won_at', [now(self::TZ)->startOfMonth()->utc(), now(self::TZ)->endOfMonth()->utc()])->count(),
            'recentLeads' => (clone $base)->with(['cs', 'package'])->latest()->limit(5)->get(),
        ]));
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'stage' => ['nullable', Rule::exists('lead_stages', 'key')],
            'cs_id' => ['nullable', 'integer'],
            'campaign_id' => ['nullable', 'integer'],
            'package_id' => ['nullable', 'integer'],
            'campaign_ad_id' => ['nullable', 'integer'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'needs_followup' => ['nullable', Rule::in(['1'])],
        ]);
        $leads = Lead::query()->visibleTo($request->user())->with(['contact', 'cs', 'package'])
            ->when($filters['search'] ?? null, fn ($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('display_name', 'like', "%{$s}%")
                    ->orWhereHas('contact', fn ($c) => $c->where('phone_normalized', 'like', "%{$s}%"));
            }))
            ->when($filters['stage'] ?? null, fn ($q, $v) => $q->where('stage', $v))
            ->when($this->admin($request) && ($filters['cs_id'] ?? null), fn ($q) => $q->where('assigned_cs_id', $filters['cs_id']))
            ->when($filters['campaign_id'] ?? null, fn ($q, $v) => $q->where('campaign_id', $v))
            ->when($filters['package_id'] ?? null, fn ($q, $v) => $q->where('package_id', $v))
            ->when($filters['campaign_ad_id'] ?? null, fn ($q, $v) => $q->where('campaign_ad_id', $v))
            ->when($filters['from'] ?? null, fn ($q, $v) => $q->where('created_at', '>=', Carbon::parse($v, self::TZ)->startOfDay()->utc()))
            ->when($filters['to'] ?? null, fn ($q, $v) => $q->where('created_at', '<=', Carbon::parse($v, self::TZ)->endOfDay()->utc()))
            ->when(($filters['needs_followup'] ?? null) === '1', fn ($q) => $q->needsFollowup())
            ->latest()->paginate(15)->withQueryString();

        return view('crm.index', array_merge($this->viewData($request), compact('leads', 'filters')));
    }

    public function archivedLeads(Request $request): View
    {
        abort_unless($this->admin($request), 403);
        $filters = $request->validate(['search' => ['nullable', 'string', 'max:100']]);
        $leads = Lead::onlyTrashed()->with(['cs', 'package'])
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('display_name', 'like', "%{$search}%"))
            ->orderByDesc('deleted_at')->paginate(15)->withQueryString();
        return view('crm.archived-leads', array_merge($this->viewData($request), compact('leads', 'filters')));
    }

    public function archiveLead(Request $request, int $lead): RedirectResponse
    {
        abort_unless($this->admin($request), 403);
        DB::transaction(function () use ($request, $lead) {
            $record = Lead::query()->lockForUpdate()->findOrFail($lead);
            $record->activities()->create(['actor_user_id' => $request->user()->id, 'type' => 'lead_archived', 'note' => 'Lead diarsipkan oleh admin.']);
            $record->delete();
        });
        return to_route('admin.crm.leads.index')->with('status', 'Lead diarsipkan.');
    }

    public function bulkArchiveLeads(Request $request): RedirectResponse
    {
        abort_unless($this->admin($request), 403);
        $ids = $this->selectedIds($request);
        DB::transaction(function () use ($request, $ids) {
            $leads = Lead::query()->whereKey($ids)->lockForUpdate()->get();
            abort_unless($leads->count() === count($ids), 404);
            foreach ($leads as $lead) {
                $lead->activities()->create(['actor_user_id' => $request->user()->id, 'type' => 'lead_archived', 'note' => 'Lead diarsipkan oleh admin.']);
                $lead->delete();
            }
        });
        return back()->with('status', count($ids).' lead diarsipkan.');
    }

    public function restoreLead(Request $request, int $lead): RedirectResponse
    {
        abort_unless($this->admin($request), 403);
        DB::transaction(function () use ($request, $lead) {
            $record = Lead::onlyTrashed()->lockForUpdate()->findOrFail($lead);
            $record->restore();
            $record->activities()->create(['actor_user_id' => $request->user()->id, 'type' => 'lead_restored', 'note' => 'Lead dipulihkan oleh admin.']);
        });
        return back()->with('status', 'Lead dipulihkan.');
    }

    public function bulkRestoreLeads(Request $request): RedirectResponse
    {
        abort_unless($this->admin($request), 403);
        $ids = $this->selectedIds($request);
        DB::transaction(function () use ($request, $ids) {
            $leads = Lead::onlyTrashed()->whereKey($ids)->lockForUpdate()->get();
            abort_unless($leads->count() === count($ids), 404);
            foreach ($leads as $lead) {
                $lead->restore();
                $lead->activities()->create(['actor_user_id' => $request->user()->id, 'type' => 'lead_restored', 'note' => 'Lead dipulihkan oleh admin.']);
            }
        });
        return back()->with('status', count($ids).' lead dipulihkan.');
    }

    public function create(Request $request): View
    {
        return view('crm.create', $this->viewData($request));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'assigned_cs_id' => [$this->admin($request) ? 'required' : 'nullable', 'nullable', 'integer', 'exists:whatsapp_cs,id'],
            'package_id' => ['nullable', 'integer', 'exists:packages,id'],
            'campaign_id' => ['nullable', 'integer', 'exists:campaigns,id'],
            'campaign_ad_id' => ['nullable', 'integer', 'exists:campaign_ads,id'],
            'city' => ['nullable', 'string', 'max:120'],
            'planned_departure_month' => ['nullable', 'date_format:Y-m'],
            'need_type' => ['nullable', Rule::in(array_keys(Lead::NEED_TYPES))],
            'needs_summary' => ['required_if:need_type,other', 'nullable', 'string', 'max:3000'],
            'followup_due_at' => ['nullable', 'required_with:followup_description', 'date_format:Y-m-d\\TH:i'],
            'followup_description' => ['nullable', 'required_with:followup_due_at', 'string', 'max:255'],
            'confirm_duplicate' => ['nullable', 'boolean'],
        ]);
        $phone = $this->normalizePhone($data['phone']);
        $cs = $this->resolveCs($request, $data['assigned_cs_id'] ?? null);
        $this->validateSource($data);
        $existing = Contact::where('phone_normalized', $phone)->first();
        if ($existing && $existing->leads()->withTrashed()->exists() && ! $request->boolean('confirm_duplicate')) {
            $history = Lead::query()->visibleTo($request->user())->where('contact_id', $existing->id)
                ->latest()->limit(5)->get(['id', 'display_name', 'stage'])->toArray();
            return back()->withInput()
                ->with('duplicate_warning', 'Nomor ini sudah memiliki lead. Pastikan ini peluang perjalanan baru, lalu centang konfirmasi untuk melanjutkan.')
                ->with('duplicate_leads', $history);
        }

        $lead = DB::transaction(function () use ($request, $data, $phone, $cs) {
            $contact = Contact::firstOrCreate(['phone_normalized' => $phone], ['name' => trim($data['name'])]);
            $lead = Lead::create([
                'contact_id' => $contact->id,
                'display_name' => trim($data['name']),
                'assigned_cs_id' => $cs->id,
                'package_id' => $data['package_id'] ?? null,
                'campaign_id' => $data['campaign_id'] ?? null,
                'campaign_ad_id' => $data['campaign_ad_id'] ?? null,
                'attribution_method' => ! empty($data['campaign_id']) ? 'manual' : 'unknown',
                'city' => isset($data['city']) ? trim($data['city']) : null,
                'planned_departure_month' => $data['planned_departure_month'] ?? null,
                'need_type' => $data['need_type'] ?? null,
                'needs_summary' => $data['needs_summary'] ?? null,
            ]);
            $lead->activities()->create(['actor_user_id' => $request->user()->id, 'type' => 'created', 'note' => 'Lead dibuat setelah kontak diterima.']);
            if (! empty($data['followup_due_at'])) {
                $due = Carbon::createFromFormat('Y-m-d\\TH:i', $data['followup_due_at'], self::TZ)->utc();
                $lead->followups()->create(['assigned_user_id' => $cs->user_id, 'due_at' => $due, 'description' => $data['followup_description']]);
                $lead->activities()->create([
                    'actor_user_id' => $request->user()->id, 'type' => 'followup_added',
                    'note' => $data['followup_description'], 'changes' => ['due_at' => $due->toIso8601String()],
                ]);
            }
            return $lead;
        });

        return to_route($this->route($request, 'leads.show'), $lead)->with('status', 'Lead berhasil ditambahkan.');
    }

    public function show(Request $request, int $lead): View
    {
        $lead = $this->visibleLead($request, $lead)->load(['contact', 'cs.user', 'package', 'campaign', 'campaignAd', 'activities.actor', 'followups.assignee']);
        return view('crm.show', array_merge($this->viewData($request), compact('lead')));
    }

    public function update(Request $request, int $lead): RedirectResponse
    {
        $lead = $this->visibleLead($request, $lead);
        $data = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'package_id' => ['nullable', 'integer', 'exists:packages,id'],
            'campaign_id' => ['nullable', 'integer', 'exists:campaigns,id'],
            'campaign_ad_id' => ['nullable', 'integer', 'exists:campaign_ads,id'],
            'city' => ['nullable', 'string', 'max:120'],
            'planned_departure_month' => ['nullable', 'date_format:Y-m'],
            'need_type' => ['nullable', Rule::in(array_keys(Lead::NEED_TYPES))],
            'needs_summary' => ['required_if:need_type,other', 'nullable', 'string', 'max:3000'],
            'assigned_cs_id' => ['nullable', 'integer', 'exists:whatsapp_cs,id'],
        ]);
        $this->validateSource($data);
        if ($this->admin($request) && isset($data['assigned_cs_id']) && (int) $data['assigned_cs_id'] !== $lead->assigned_cs_id) {
            $newCs = $this->resolveCs($request, $data['assigned_cs_id']);
            $data['assigned_cs_id'] = $newCs->id;
        } else {
            unset($data['assigned_cs_id']);
        }
        $data['attribution_method'] = ! empty($data['campaign_id']) ? 'manual' : 'unknown';
        $data['campaign_ad_id'] = $data['campaign_ad_id'] ?? null;
        $data['campaign_id'] = $data['campaign_id'] ?? null;
        $data['package_id'] = $data['package_id'] ?? null;
        $data['city'] = isset($data['city']) ? trim($data['city']) : null;
        $data['planned_departure_month'] = $data['planned_departure_month'] ?? null;
        $data['need_type'] = $data['need_type'] ?? null;
        if (! array_key_exists('needs_summary', $data) && $data['need_type'] !== $lead->need_type) {
            $data['needs_summary'] = null;
        }
        $old = $lead->only(array_keys($data));

        DB::transaction(function () use ($lead, $data, $old, $request) {
            $lead->update($data);
            $changes = [];
            foreach ($data as $key => $value) {
                if ((string) ($old[$key] ?? '') !== (string) ($value ?? '')) $changes[$key] = ['from' => $old[$key] ?? null, 'to' => $value];
            }
            if ($changes) {
                if (isset($changes['assigned_cs_id'])) {
                    $assignee = $lead->cs->user_id;
                    if ($assignee) $lead->followups()->where('status', 'open')->update(['assigned_user_id' => $assignee]);
                }
                $lead->activities()->create([
                    'actor_user_id' => $request->user()->id,
                    'type' => 'updated',
                    'note' => $this->describeChanges($changes),
                    'changes' => $changes,
                ]);
            }
        });
        return back()->with('status', 'Detail lead diperbarui.');
    }

    public function pipelineDetails(Request $request, int $lead): RedirectResponse
    {
        $lead = $this->visibleLead($request, $lead);
        $data = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'package_id' => ['nullable', 'integer', 'exists:packages,id'],
            'city' => ['nullable', 'string', 'max:120'],
            'planned_departure_month' => ['nullable', 'date_format:Y-m'],
            'assigned_cs_id' => [$this->admin($request) ? 'required' : 'prohibited', 'integer', 'exists:whatsapp_cs,id'],
        ]);
        $updates = [
            'display_name' => trim($data['display_name']),
            'package_id' => $data['package_id'] ?? null,
            'city' => isset($data['city']) ? trim($data['city']) : null,
            'planned_departure_month' => $data['planned_departure_month'] ?? null,
        ];
        if ($this->admin($request)) {
            $updates['assigned_cs_id'] = (int) $data['assigned_cs_id'] === $lead->assigned_cs_id
                ? $lead->assigned_cs_id
                : $this->resolveCs($request, $data['assigned_cs_id'])->id;
        }
        $old = $lead->only(array_keys($updates));
        $changes = [];
        foreach ($updates as $key => $value) {
            if ((string) ($old[$key] ?? '') !== (string) ($value ?? '')) {
                $changes[$key] = ['from' => $old[$key] ?? null, 'to' => $value];
            }
        }
        if ($changes) {
            DB::transaction(function () use ($lead, $request, $updates, $changes) {
                $lead->update($updates);
                if (isset($changes['assigned_cs_id']) && $lead->cs?->user_id) {
                    $lead->followups()->where('status', 'open')->update(['assigned_user_id' => $lead->cs->user_id]);
                }
                $lead->activities()->create([
                    'actor_user_id' => $request->user()->id,
                    'type' => 'updated',
                    'note' => $this->describeChanges($changes),
                    'changes' => $changes,
                ]);
            });
        }
        return back()->with('status', $changes ? 'Data lead diperbarui.' : 'Tidak ada perubahan data lead.');
    }

    public function stage(Request $request, int $lead): RedirectResponse
    {
        $lead = $this->visibleLead($request, $lead);
        if ($lead->stage === 'berhasil' && ! $this->admin($request)) abort(403);
        $data = $request->validate([
            'stage' => ['required', Rule::exists('lead_stages', 'key')],
            'confirmation_note' => ['required_if:stage,berhasil', 'nullable', 'string', 'max:3000'],
            'confirmed_on' => ['required_if:stage,berhasil', 'nullable', 'date_format:Y-m-d'],
            'booking_reference' => ['nullable', 'string', 'max:255'],
            'lost_reason' => ['required_if:stage,gagal', 'nullable', 'string', 'max:3000'],
        ]);
        $old = $lead->stage;
        $stageLabels = LeadStage::labels();
        DB::transaction(function () use ($request, $lead, $data, $old, $stageLabels) {
            $updates = ['stage' => $data['stage']];
            if ($data['stage'] !== 'baru' && ! $lead->first_contacted_at) $updates['first_contacted_at'] = now();
            if ($data['stage'] === 'berhasil') {
                $updates['won_at'] = Carbon::createFromFormat('Y-m-d', $data['confirmed_on'], self::TZ)->startOfDay()->utc();
                $updates['confirmation_note'] = $data['confirmation_note'];
                $updates['booking_reference'] = $data['booking_reference'] ?? null;
                $updates['lost_reason'] = null;
            } elseif ($data['stage'] === 'gagal') {
                $updates['lost_reason'] = $data['lost_reason'];
                $updates['won_at'] = null;
                $updates['confirmation_note'] = null;
                $updates['booking_reference'] = null;
            } else {
                $updates += ['won_at' => null, 'confirmation_note' => null, 'booking_reference' => null, 'lost_reason' => null];
            }
            $lead->update($updates);
            $lead->activities()->create([
                'actor_user_id' => $request->user()->id, 'type' => 'stage_changed',
                'note' => $data['stage'] === 'berhasil' ? $data['confirmation_note'] : ($data['stage'] === 'gagal' ? $data['lost_reason'] : null),
                'changes' => [
                    'stage' => ['from' => $old, 'to' => $data['stage']],
                    'stage_labels' => ['from' => $stageLabels[$old] ?? $old, 'to' => $stageLabels[$data['stage']]],
                    'booking_reference' => $data['booking_reference'] ?? null,
                ],
            ]);
            if (in_array($data['stage'], ['berhasil', 'gagal'])) $lead->followups()->where('status', 'open')->update(['status' => 'completed', 'completed_at' => now()]);
        });
        return back()->with('status', 'Tahap lead diperbarui.');
    }

    public function note(Request $request, int $lead): RedirectResponse
    {
        $lead = $this->visibleLead($request, $lead);
        $data = $request->validate(['note' => ['required', 'string', 'max:3000']]);
        $lead->activities()->create(['actor_user_id' => $request->user()->id, 'type' => 'note', 'note' => $data['note']]);
        return back()->with('status', 'Catatan ditambahkan.');
    }

    public function followup(Request $request, int $lead): RedirectResponse
    {
        $lead = $this->visibleLead($request, $lead)->load('cs');
        if (in_array($lead->stage, ['berhasil', 'gagal'])) throw ValidationException::withMessages(['due_at' => 'Buka kembali lead sebelum membuat tindak lanjut.']);
        $data = $request->validate(['due_at' => ['required', 'date_format:Y-m-d\\TH:i'], 'description' => ['required', 'string', 'max:255']]);
        $assignee = $lead->cs->user_id;
        if (! $assignee) throw ValidationException::withMessages(['due_at' => 'PIC belum memiliki akun CS.']);
        $due = Carbon::createFromFormat('Y-m-d\\TH:i', $data['due_at'], self::TZ)->utc();
        DB::transaction(function () use ($lead, $request, $data, $assignee, $due) {
            $lead->followups()->create(['assigned_user_id' => $assignee, 'due_at' => $due, 'description' => $data['description']]);
            $lead->activities()->create(['actor_user_id' => $request->user()->id, 'type' => 'followup_added', 'note' => $data['description'], 'changes' => ['due_at' => $due->toIso8601String()]]);
        });
        return back()->with('status', 'Tindak lanjut dijadwalkan.');
    }

    private function openFollowup(Request $request, int $id): LeadFollowup
    {
        $task = LeadFollowup::query()->with('lead')
            ->whereHas('lead', fn ($query) => $query->visibleTo($request->user()))
            ->when(! $this->admin($request), fn ($query) => $query->where('assigned_user_id', $request->user()->id))
            ->lockForUpdate()
            ->findOrFail($id);
        abort_unless($task->status === 'open', 409, 'Tugas ini sudah selesai.');
        return $task;
    }

    private function selectedIds(Request $request): array
    {
        return $request->validate([
            'ids' => ['required', 'array', 'min:1', 'max:100'],
            'ids.*' => ['required', 'integer', 'min:1', 'distinct'],
        ])['ids'];
    }

    private function selectedOpenFollowups(Request $request, array $ids)
    {
        $tasks = LeadFollowup::query()->with('lead')->whereKey($ids)
            ->where('status', 'open')
            ->whereHas('lead', fn ($query) => $query->visibleTo($request->user()))
            ->when(! $this->admin($request), fn ($query) => $query->where('assigned_user_id', $request->user()->id))
            ->lockForUpdate()->get();
        abort_unless($tasks->count() === count($ids), 404);
        return $tasks;
    }

    public function updateFollowup(Request $request, int $followup): RedirectResponse
    {
        $data = $request->validate(['due_at' => ['required', 'date_format:Y-m-d\\TH:i'], 'description' => ['required', 'string', 'max:255']]);
        $due = Carbon::createFromFormat('Y-m-d\\TH:i', $data['due_at'], self::TZ)->utc();
        DB::transaction(function () use ($request, $followup, $data, $due) {
            $task = $this->openFollowup($request, $followup);
            $changes = [];
            if (! $task->due_at->equalTo($due)) $changes['due_at'] = ['from' => $task->due_at->toIso8601String(), 'to' => $due->toIso8601String()];
            if ($task->description !== $data['description']) $changes['description'] = ['from' => $task->description, 'to' => $data['description']];
            if (! $changes) return;
            $task->update(['due_at' => $due, 'description' => $data['description']]);
            $task->lead->activities()->create([
                'actor_user_id' => $request->user()->id, 'type' => 'followup_updated',
                'note' => $data['description'], 'changes' => $changes,
            ]);
        });
        return back()->with('status', 'Tugas diperbarui.');
    }

    public function archiveFollowup(Request $request, int $followup): RedirectResponse
    {
        DB::transaction(function () use ($request, $followup) {
            $task = $this->openFollowup($request, $followup);
            $task->lead->activities()->create(['actor_user_id' => $request->user()->id, 'type' => 'followup_archived', 'note' => $task->description]);
            $task->delete();
        });
        return back()->with('status', 'Tugas diarsipkan.');
    }

    public function bulkCompleteFollowups(Request $request): RedirectResponse
    {
        $ids = $this->selectedIds($request);
        DB::transaction(function () use ($request, $ids) {
            foreach ($this->selectedOpenFollowups($request, $ids) as $task) {
                $task->update(['status' => 'completed', 'completed_at' => now()]);
                $task->lead->activities()->create(['actor_user_id' => $request->user()->id, 'type' => 'followup_completed', 'note' => $task->description]);
            }
        });
        return back()->with('status', count($ids).' tugas diselesaikan.');
    }

    public function bulkArchiveFollowups(Request $request): RedirectResponse
    {
        $ids = $this->selectedIds($request);
        DB::transaction(function () use ($request, $ids) {
            foreach ($this->selectedOpenFollowups($request, $ids) as $task) {
                $task->lead->activities()->create(['actor_user_id' => $request->user()->id, 'type' => 'followup_archived', 'note' => $task->description]);
                $task->delete();
            }
        });
        return back()->with('status', count($ids).' tugas diarsipkan.');
    }

    public function completeFollowup(Request $request, int $followup): RedirectResponse
    {
        $data = $request->validate([
            'next_due_at' => ['nullable', 'required_with:next_description', 'date_format:Y-m-d\\TH:i'],
            'next_description' => ['nullable', 'required_with:next_due_at', 'string', 'max:255'],
        ]);
        DB::transaction(function () use ($request, $followup, $data) {
            $task = $this->openFollowup($request, $followup);
            if (! empty($data['next_due_at'])) {
                if (! LeadStage::whereKey($task->lead->stage)->where('kind', 'open')->exists()) {
                    throw ValidationException::withMessages(['next_due_at' => 'Buka kembali lead sebelum menjadwalkan tindak lanjut.']);
                }
                $task->lead->load('cs');
                if (! $task->lead->cs?->user_id) {
                    throw ValidationException::withMessages(['next_due_at' => 'PIC belum memiliki akun CS.']);
                }
            }
            $task->update(['status' => 'completed', 'completed_at' => now()]);
            $task->lead->activities()->create(['actor_user_id' => $request->user()->id, 'type' => 'followup_completed', 'note' => $task->description]);
            if (! empty($data['next_due_at'])) {
                $due = Carbon::createFromFormat('Y-m-d\\TH:i', $data['next_due_at'], self::TZ)->utc();
                $task->lead->followups()->create([
                    'assigned_user_id' => $task->lead->cs->user_id,
                    'due_at' => $due,
                    'description' => $data['next_description'],
                ]);
                $task->lead->activities()->create([
                    'actor_user_id' => $request->user()->id, 'type' => 'followup_added',
                    'note' => $data['next_description'], 'changes' => ['due_at' => $due->toIso8601String()],
                ]);
            }
        });
        return back()->with('status', ! empty($data['next_due_at']) ? 'Tugas selesai dan tindak lanjut berikutnya dijadwalkan.' : 'Tindak lanjut selesai.');
    }

    public function tasks(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'period' => ['nullable', Rule::in(['overdue', 'today', 'upcoming'])],
            'cs_id' => ['nullable', 'integer', 'exists:whatsapp_cs,id'],
        ]);
        $today = now(self::TZ);
        $endToday = $today->copy()->endOfDay()->utc();
        $tasks = LeadFollowup::query()->with(['lead.contact', 'lead.cs', 'assignee'])
            ->whereHas('lead', fn ($q) => $q->visibleTo($request->user()))
            ->when(! $this->admin($request), fn ($q) => $q->where('assigned_user_id', $request->user()->id))
            ->where('status', 'open')
            ->when($filters['search'] ?? null, fn ($q, $search) => $q->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('lead', fn ($lead) => $lead->where('display_name', 'like', "%{$search}%")
                        ->orWhereHas('contact', fn ($contact) => $contact->where('phone_normalized', 'like', "%{$search}%")));
            }))
            ->when($this->admin($request) && ($filters['cs_id'] ?? null), fn ($q) => $q->whereHas('lead', fn ($lead) => $lead->where('assigned_cs_id', $filters['cs_id'])))
            ->when(($filters['period'] ?? null) === 'overdue', fn ($q) => $q->where('due_at', '<', now()))
            ->when(($filters['period'] ?? null) === 'today', fn ($q) => $q->whereBetween('due_at', [now(), $endToday]))
            ->when(($filters['period'] ?? null) === 'upcoming', fn ($q) => $q->where('due_at', '>', $endToday))
            ->orderBy('due_at')->paginate(20)->withQueryString();
        return view('crm.tasks', array_merge($this->viewData($request), compact('tasks', 'filters')));
    }

    public function archivedFollowups(Request $request): View
    {
        abort_unless($this->admin($request), 403);
        $tasks = LeadFollowup::onlyTrashed()->with(['lead', 'assignee'])
            ->whereHas('lead')->orderByDesc('deleted_at')->paginate(20);
        return view('crm.archived-tasks', array_merge($this->viewData($request), compact('tasks')));
    }

    public function restoreFollowup(Request $request, int $followup): RedirectResponse
    {
        abort_unless($this->admin($request), 403);
        DB::transaction(function () use ($request, $followup) {
            $task = LeadFollowup::onlyTrashed()->with('lead')->whereHas('lead')->lockForUpdate()->findOrFail($followup);
            $task->restore();
            $task->lead->activities()->create(['actor_user_id' => $request->user()->id, 'type' => 'followup_restored', 'note' => $task->description]);
        });
        return back()->with('status', 'Tugas dipulihkan.');
    }

    public function bulkRestoreFollowups(Request $request): RedirectResponse
    {
        abort_unless($this->admin($request), 403);
        $ids = $this->selectedIds($request);
        DB::transaction(function () use ($request, $ids) {
            $tasks = LeadFollowup::onlyTrashed()->with('lead')->whereHas('lead')->whereKey($ids)->lockForUpdate()->get();
            abort_unless($tasks->count() === count($ids), 404);
            foreach ($tasks as $task) {
                $task->restore();
                $task->lead->activities()->create(['actor_user_id' => $request->user()->id, 'type' => 'followup_restored', 'note' => $task->description]);
            }
        });
        return back()->with('status', count($ids).' tugas dipulihkan.');
    }

    public function bulkPipeline(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', Rule::in(['move', 'assign', 'archive'])],
            'source_stage' => ['required', Rule::exists('lead_stages', 'key')],
            'target_stage' => ['required_if:action,move', 'nullable', Rule::exists('lead_stages', 'key')],
            'assigned_cs_id' => ['required_if:action,assign', 'nullable', 'integer', 'exists:whatsapp_cs,id'],
        ]);
        $ids = $this->selectedIds($request);
        $action = $data['action'];
        if ($action !== 'move') abort_unless($this->admin($request), 403);
        if (! $this->admin($request) && $data['source_stage'] === 'berhasil') abort(403);

        $target = null;
        $cs = null;
        $sourceLabel = null;
        if ($action === 'move') {
            $target = LeadStage::findOrFail($data['target_stage']);
            $sourceLabel = LeadStage::whereKey($data['source_stage'])->value('label') ?? $data['source_stage'];
            if ($target->kind !== 'open' || $target->key === $data['source_stage']) {
                throw ValidationException::withMessages(['target_stage' => 'Pilih tahap proses lain. Hasil Berhasil dan Gagal harus dicatat per lead.']);
            }
        } elseif ($action === 'assign') {
            $cs = $this->resolveCs($request, (int) $data['assigned_cs_id']);
        }

        $changed = DB::transaction(function () use ($request, $ids, $data, $action, $target, $sourceLabel, $cs) {
            $leads = Lead::query()->visibleTo($request->user())->whereKey($ids)->lockForUpdate()->get();
            abort_unless($leads->count() === count($ids), 404);
            abort_unless($leads->every(fn (Lead $lead) => $lead->stage === $data['source_stage']), 409, 'Tahap lead sudah berubah. Muat ulang pipeline.');

            $changed = 0;
            foreach ($leads as $lead) {
                if ($action === 'move') {
                    $updates = [
                        'stage' => $target->key,
                        'won_at' => null, 'confirmation_note' => null,
                        'booking_reference' => null, 'lost_reason' => null,
                    ];
                    if ($target->key !== 'baru' && ! $lead->first_contacted_at) $updates['first_contacted_at'] = now();
                    $lead->update($updates);
                    $lead->activities()->create([
                        'actor_user_id' => $request->user()->id,
                        'type' => 'stage_changed',
                        'note' => 'Tahap dipindahkan secara massal dari pipeline.',
                        'changes' => [
                            'stage' => ['from' => $data['source_stage'], 'to' => $target->key],
                            'stage_labels' => [
                                'from' => $sourceLabel,
                                'to' => $target->label,
                            ],
                        ],
                    ]);
                } elseif ($action === 'assign') {
                    if ($lead->assigned_cs_id === $cs->id) continue;
                    $previousCsId = $lead->assigned_cs_id;
                    $lead->update(['assigned_cs_id' => $cs->id]);
                    $lead->followups()->where('status', 'open')->update(['assigned_user_id' => $cs->user_id]);
                    $lead->activities()->create([
                        'actor_user_id' => $request->user()->id,
                        'type' => 'updated',
                        'note' => 'PIC dipindahkan secara massal dari pipeline.',
                        'changes' => ['assigned_cs_id' => ['from' => $previousCsId, 'to' => $cs->id]],
                    ]);
                } else {
                    $lead->activities()->create(['actor_user_id' => $request->user()->id, 'type' => 'lead_archived', 'note' => 'Lead diarsipkan secara massal dari pipeline.']);
                    $lead->delete();
                }
                $changed++;
            }
            return $changed;
        });

        $message = match ($action) {
            'move' => $changed.' lead dipindahkan ke '.$target->label.'.',
            'assign' => $changed.' lead dialihkan ke '.$cs->name.'.',
            'archive' => $changed.' lead diarsipkan.',
        };
        return back()->with('status', $message);
    }

    public function pipeline(Request $request): View
    {
        $requestedCsId = filter_var($request->query('cs_id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $selectedCsId = $this->admin($request) && $requestedCsId && WhatsAppCs::query()->whereKey($requestedCsId)->exists()
            ? $requestedCsId : null;
        $previewLimit = self::PIPELINE_PREVIEW_LIMIT;
        $stages = LeadStage::ordered()->pluck('key')->all();
        $base = Lead::query()->visibleTo($request->user())
            ->when($selectedCsId, fn ($query) => $query->where('assigned_cs_id', $selectedCsId));
        $counts = (clone $base)
            ->selectRaw('stage, count(*) as total')->groupBy('stage')->pluck('total', 'stage');
        $leads = collect($stages)->mapWithKeys(fn ($stage) => [
            $stage => (clone $base)->with(['cs', 'package'])
                ->where('stage', $stage)->orderByDesc('updated_at')->orderByDesc('id')->limit($previewLimit)->get(),
        ]);
        return view('crm.pipeline', array_merge($this->viewData($request), compact('leads', 'counts', 'previewLimit', 'selectedCsId')));
    }

    public function report(Request $request): View
    {
        abort_unless($this->admin($request), 403);
        $data = $request->validate(['from' => ['nullable', 'date_format:Y-m-d'], 'to' => ['nullable', 'date_format:Y-m-d']]);
        $from = $data['from'] ?? now(self::TZ)->startOfMonth()->toDateString();
        $to = $data['to'] ?? now(self::TZ)->toDateString();
        if ($from > $to) throw ValidationException::withMessages(['to' => 'Tanggal akhir harus sama atau setelah tanggal awal.']);
        $start = Carbon::parse($from, self::TZ)->startOfDay()->utc();
        $end = Carbon::parse($to, self::TZ)->endOfDay()->utc();
        $leads = Lead::query()->whereBetween('created_at', [$start, $end]);
        $visitsDaily = $this->reportDailyCounts(
            ChatLog::humanTraffic()->whereBetween('created_at', [$start, $end]), 'created_at'
        );
        $redirectsDaily = $this->reportDailyCounts(
            ChatLog::humanTraffic()->redirected()->whereBetween('clicked_at', [$start, $end]), 'clicked_at'
        );
        $leadsDaily = $this->reportDailyCounts(
            Lead::query()->whereBetween('created_at', [$start, $end]), 'created_at'
        );
        $winsDaily = $this->reportDailyCounts(
            Lead::query()->where('stage', 'berhasil')->whereBetween('won_at', [$start, $end]), 'won_at'
        );

        $firstDay = Carbon::parse($from, self::TZ)->startOfDay();
        $lastDay = Carbon::parse($to, self::TZ)->startOfDay();
        $days = (int) $firstDay->diffInDays($lastDay) + 1;
        $granularity = $days <= 45 ? 'day' : ($days <= 180 ? 'week' : 'month');
        $trend = [];
        $bucketByDay = [];
        for ($cursor = $firstDay->copy(); $cursor->lte($lastDay); $cursor = $next) {
            $next = match ($granularity) {
                'day' => $cursor->copy()->addDay(),
                'week' => $cursor->copy()->addDays(7),
                default => $cursor->copy()->startOfMonth()->addMonth(),
            };
            $bucketEnd = $next->copy()->subDay();
            if ($bucketEnd->gt($lastDay)) $bucketEnd = $lastDay->copy();
            $index = count($trend);
            $trend[] = [
                'label' => $granularity === 'month'
                    ? $cursor->copy()->locale('id')->translatedFormat('M Y')
                    : $cursor->copy()->locale('id')->translatedFormat('d M'),
                'start' => $cursor->toDateString(),
                'end' => $bucketEnd->toDateString(),
                'visits' => 0, 'redirects' => 0, 'leads' => 0, 'wins' => 0,
            ];
            for ($day = $cursor->copy(); $day->lte($bucketEnd); $day->addDay()) {
                $bucketByDay[$day->toDateString()] = $index;
            }
        }
        foreach ([
            'visits' => $visitsDaily, 'redirects' => $redirectsDaily,
            'leads' => $leadsDaily, 'wins' => $winsDaily,
        ] as $metric => $daily) {
            foreach ($daily as $day => $total) {
                if (isset($bucketByDay[$day])) $trend[$bucketByDay[$day]][$metric] += (int) $total;
            }
        }

        return view('crm.report', array_merge($this->viewData($request), [
            'from' => $from, 'to' => $to, 'trend' => $trend, 'granularity' => $granularity,
            'visits' => (int) $visitsDaily->sum(), 'redirects' => (int) $redirectsDaily->sum(),
            'leadCount' => (int) $leadsDaily->sum(), 'wonCount' => (int) $winsDaily->sum(),
            'manualCount' => (clone $leads)->where('attribution_method', 'manual')->count(),
            'unknownCount' => (clone $leads)->where('attribution_method', 'unknown')->count(),
            'byStage' => (clone $leads)->selectRaw('stage, count(*) as total')->groupBy('stage')->pluck('total', 'stage'),
            'byCs' => (clone $leads)->selectRaw('assigned_cs_id, count(*) as total')->groupBy('assigned_cs_id')->pluck('total', 'assigned_cs_id'),
            'wonByCs' => (clone $leads)->where('stage', 'berhasil')->selectRaw('assigned_cs_id, count(*) as total')->groupBy('assigned_cs_id')->pluck('total', 'assigned_cs_id'),
            'byCampaign' => (clone $leads)->where('attribution_method', 'manual')->whereNotNull('campaign_id')->selectRaw('campaign_id, count(*) as total')->groupBy('campaign_id')->pluck('total', 'campaign_id'),
            'byAd' => (clone $leads)->where('attribution_method', 'manual')->whereNotNull('campaign_ad_id')->selectRaw('campaign_ad_id, count(*) as total')->groupBy('campaign_ad_id')->pluck('total', 'campaign_ad_id'),
            'byPackage' => (clone $leads)->whereNotNull('package_id')->selectRaw('package_id, count(*) as total')->groupBy('package_id')->pluck('total', 'package_id'),
            'byNeed' => (clone $leads)->whereNotNull('need_type')->selectRaw('need_type, count(*) as total')->groupBy('need_type')->pluck('total', 'need_type'),
            'byCity' => (clone $leads)->whereNotNull('city')->where('city', '!=', '')->selectRaw('city, count(*) as total')->groupBy('city')->pluck('total', 'city'),
            'byMonth' => (clone $leads)->whereNotNull('planned_departure_month')->selectRaw('planned_departure_month, count(*) as total')->groupBy('planned_departure_month')->pluck('total', 'planned_departure_month'),
        ]));
    }

    private function reportDailyCounts($query, string $column)
    {
        $day = DB::connection()->getDriverName() === 'sqlite'
            ? "date({$column}, '+8 hours')"
            : "DATE(DATE_ADD({$column}, INTERVAL 8 HOUR))";

        return $query->selectRaw("{$day} as report_day, count(*) as total")
            ->groupByRaw($day)->orderBy('report_day')->pluck('total', 'report_day');
    }

    private function resolveCs(Request $request, ?int $id): WhatsAppCs
    {
        $cs = $this->admin($request) ? WhatsAppCs::with('user')->findOrFail($id) : $request->user()->whatsappCs;
        if (! $cs || ! $cs->user || ! $cs->user->is_active || ($this->admin($request) && ! $cs->is_active)) {
            throw ValidationException::withMessages(['assigned_cs_id' => 'Pilih CS aktif yang memiliki akun login aktif.']);
        }
        return $cs;
    }

    private function validateSource(array $data): void
    {
        if (! empty($data['campaign_ad_id']) && (empty($data['campaign_id']) || ! CampaignAd::whereKey($data['campaign_ad_id'])->where('campaign_id', $data['campaign_id'])->exists())) {
            throw ValidationException::withMessages(['campaign_ad_id' => 'Ads harus berasal dari Campaign yang dipilih.']);
        }
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if (str_starts_with($digits, '0')) $digits = '62'.substr($digits, 1);
        if (! preg_match('/^62[1-9][0-9]{7,12}$/', $digits)) {
            throw ValidationException::withMessages(['phone' => 'Gunakan nomor Indonesia yang valid, misalnya 081234567890.']);
        }
        return $digits;
    }

    private function describeChanges(array $changes): string
    {
        $descriptions = [];
        foreach ($changes as $field => $change) {
            [$before, $after] = [$change['from'], $change['to']];
            $descriptions[] = match ($field) {
                'display_name' => "Nama: {$before} → {$after}",
                'assigned_cs_id' => 'PIC: '.(WhatsAppCs::find($before)?->name ?? '—').' → '.(WhatsAppCs::find($after)?->name ?? '—'),
                'package_id' => 'Paket: '.(Package::find($before)?->name ?? 'Belum dipilih').' → '.(Package::find($after)?->name ?? 'Belum dipilih'),
                'campaign_id' => 'Campaign: '.(Campaign::find($before)?->name ?? 'Tidak diketahui').' → '.(Campaign::find($after)?->name ?? 'Tidak diketahui'),
                'campaign_ad_id' => 'Ads: '.(CampaignAd::find($before)?->name ?? 'Tidak diketahui').' → '.(CampaignAd::find($after)?->name ?? 'Tidak diketahui'),
                'city' => 'Kota: '.($before ?: 'Belum diketahui').' → '.($after ?: 'Belum diketahui'),
                'planned_departure_month' => 'Rencana umrah: '.($before ?: 'Belum diketahui').' → '.($after ?: 'Belum diketahui'),
                'need_type' => 'Kebutuhan: '.(Lead::NEED_TYPES[$before] ?? 'Belum diketahui').' → '.(Lead::NEED_TYPES[$after] ?? 'Belum diketahui'),
                'needs_summary' => 'Ringkasan kebutuhan diperbarui',
                default => null,
            };
        }

        return implode('; ', array_filter($descriptions));
    }
}
