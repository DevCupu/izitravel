<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignAd;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CampaignAdController extends Controller
{
    public function create(Campaign $campaign)
    {
        return view('admin.campaign-ads.create', compact('campaign'));
    }

    public function store(Request $request, Campaign $campaign)
    {
        $data = $this->validateData($request, $campaign);
        $data['utm_content'] = $this->resolveSlug($data);
        $data['is_active'] = $request->boolean('is_active');
        $campaign->ads()->create($data);

        return redirect()->route('admin.campaigns.edit', $campaign)
            ->with('status', 'Ads berhasil ditambahkan. Link tracking sudah siap digunakan.');
    }

    public function edit(Campaign $campaign, CampaignAd $ad)
    {
        return view('admin.campaign-ads.edit', compact('campaign', 'ad'));
    }

    public function update(Request $request, Campaign $campaign, CampaignAd $ad)
    {
        $data = $this->validateData($request, $campaign, $ad);
        $data['utm_content'] = $this->resolveSlug($data);
        $data['is_active'] = $request->boolean('is_active');
        $ad->update($data);

        return redirect()->route('admin.campaigns.edit', $campaign)
            ->with('status', 'Ads berhasil diperbarui.');
    }

    public function destroy(Campaign $campaign, CampaignAd $ad)
    {
        $ad->delete();

        return redirect()->route('admin.campaigns.edit', $campaign)
            ->with('status', 'Ads berhasil dihapus. Lead lama tetap tersimpan.');
    }

    private function validateData(Request $request, Campaign $campaign, ?CampaignAd $ad = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'utm_content' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9\-_]+$/',
                Rule::unique('campaign_ads', 'utm_content')
                    ->where(fn ($query) => $query->where('campaign_id', $campaign->id))
                    ->ignore($ad?->id),
            ],
            'wa_message_template' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function resolveSlug(array $data): string
    {
        $slug = trim((string) ($data['utm_content'] ?? ''));

        return $slug !== '' ? $slug : str_replace('-', '_', Str::slug($data['name'], '-'));
    }
}
