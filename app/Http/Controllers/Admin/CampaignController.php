<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\ChatLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $campaigns = Campaign::query()
            ->withCount('chatLogs')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('utm_campaign', 'like', "%{$search}%");
                });
            })
            ->orderBy('is_active', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $totalCampaigns = Campaign::count();
        $totalLeads = ChatLog::count();
        $leadsToday = ChatLog::whereDate('created_at', today())->count();

        return view('admin.campaigns.index', compact('campaigns', 'search', 'totalCampaigns', 'totalLeads', 'leadsToday'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['utm_campaign'] = $this->resolveSlug($request, $data);
        $data['is_active'] = $request->boolean('is_active');

        Campaign::create($data);

        return redirect()->route('admin.campaigns.index')->with('status', 'Campaign berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $campaign = Campaign::findOrFail($id);

        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, string $id)
    {
        $campaign = Campaign::findOrFail($id);
        $data = $this->validateData($request, $campaign);
        $data['utm_campaign'] = $this->resolveSlug($request, $data);
        $data['is_active'] = $request->boolean('is_active');

        $campaign->update($data);

        return redirect()->route('admin.campaigns.index')->with('status', 'Campaign berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        Campaign::findOrFail($id)->delete();

        return redirect()->route('admin.campaigns.index')->with('status', 'Campaign berhasil dihapus.');
    }

    private function validateData(Request $request, ?Campaign $campaign = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'utm_campaign' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9\-_]+$/',
                Rule::unique('campaigns', 'utm_campaign')->ignore($campaign?->id),
            ],
            'wa_message_template' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function resolveSlug(Request $request, array $data): string
    {
        $slug = trim((string) ($data['utm_campaign'] ?? ''));

        if ($slug === '') {
            $slug = str_replace('-', '_', Str::slug($data['name'], '-'));
        }

        return $slug;
    }
}
