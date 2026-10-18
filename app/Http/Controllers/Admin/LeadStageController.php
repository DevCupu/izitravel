<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadStage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LeadStageController extends Controller
{
    public function index(): View
    {
        $stages = LeadStage::ordered()->get();
        $counts = Lead::withTrashed()->selectRaw('stage, count(*) as total')->groupBy('stage')->pluck('total', 'stage');

        return view('crm.stages', compact('stages', 'counts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:60', Rule::unique('lead_stages', 'label')],
            'color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);
        DB::transaction(function () use ($data) {
            $position = (int) LeadStage::where('kind', 'open')->max('position') + 1;
            LeadStage::where('position', '>=', $position)->increment('position');
            LeadStage::create([
                'key' => 't_'.Str::lower(Str::random(16)),
                'label' => trim($data['label']),
                'kind' => 'open',
                'position' => $position,
                'color' => $data['color'],
            ]);
        });

        return back()->with('status', 'Tahap baru ditambahkan.');
    }

    public function update(Request $request, string $stage): RedirectResponse
    {
        $record = LeadStage::findOrFail($stage);
        $data = $request->validate([
            'label' => ['required', 'string', 'max:60', Rule::unique('lead_stages', 'label')->ignore($record->key, 'key')],
            'color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);
        $record->update(['label' => trim($data['label']), 'color' => $data['color']]);

        return back()->with('status', 'Tahap diperbarui.');
    }

    public function move(Request $request, string $stage): RedirectResponse
    {
        $data = $request->validate(['direction' => ['required', Rule::in(['up', 'down'])]]);
        DB::transaction(function () use ($stage, $data) {
            $stages = LeadStage::ordered()->lockForUpdate()->get();
            $index = $stages->search(fn (LeadStage $item) => $item->key === $stage);
            abort_if($index === false, 404);
            $otherIndex = $index + ($data['direction'] === 'up' ? -1 : 1);
            if (! $stages->has($otherIndex)) return;
            $current = $stages[$index];
            $other = $stages[$otherIndex];
            $position = $current->position;
            $current->update(['position' => $other->position]);
            $other->update(['position' => $position]);
        });

        return back()->with('status', 'Urutan tahap diperbarui.');
    }

    public function destroy(Request $request, string $stage): RedirectResponse
    {
        abort_if(in_array($stage, ['baru', 'berhasil', 'gagal'], true), 403);
        $data = $request->validate([
            'replacement_stage' => ['nullable', 'string', Rule::exists('lead_stages', 'key')->where('kind', 'open'), Rule::notIn([$stage])],
        ]);
        DB::transaction(function () use ($request, $stage, $data) {
            $record = LeadStage::whereKey($stage)->lockForUpdate()->firstOrFail();
            $leads = Lead::withTrashed()->where('stage', $stage)->lockForUpdate()->get();
            if ($leads->isNotEmpty() && empty($data['replacement_stage'])) {
                throw ValidationException::withMessages(['replacement_stage' => 'Pilih tahap pengganti untuk '. $leads->count().' lead yang masih memakai tahap ini.']);
            }
            if ($leads->isNotEmpty()) {
                $replacement = LeadStage::whereKey($data['replacement_stage'])->where('kind', 'open')->lockForUpdate()->firstOrFail();
                foreach ($leads as $lead) {
                    $lead->update(['stage' => $replacement->key]);
                    $lead->activities()->create([
                        'actor_user_id' => $request->user()->id,
                        'type' => 'stage_changed',
                        'note' => 'Tahap '.$record->label.' dihapus; lead dipindahkan ke '.$replacement->label.'.',
                        'changes' => [
                            'stage' => ['from' => $record->key, 'to' => $replacement->key],
                            'stage_labels' => ['from' => $record->label, 'to' => $replacement->label],
                        ],
                    ]);
                }
            }
            $record->delete();
        });

        return back()->with('status', 'Tahap dihapus dan lead terkait dipindahkan.');
    }
}
