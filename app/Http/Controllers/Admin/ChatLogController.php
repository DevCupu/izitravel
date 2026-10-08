<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignAd;
use App\Models\ChatLog;
use App\Models\WhatsAppCs;
use Illuminate\Http\Request;

class ChatLogController extends Controller
{
    public function index(Request $request)
    {
        $campaignId = $request->integer('campaign_id');
        $adId = $request->integer('campaign_ad_id');
        $csId = $request->integer('cs_id');
        $dateFrom = $request->string('from')->trim()->toString();
        $dateTo = $request->string('to')->trim()->toString();

        $query = ChatLog::query()
            ->humanTraffic()
            ->when($campaignId > 0, fn ($query) => $query->where('campaign_id', $campaignId))
            ->when($adId > 0, fn ($query) => $query->where('campaign_ad_id', $adId))
            ->when($csId > 0, fn ($query) => $query->where('cs_id', $csId))
            ->when($dateFrom !== '', fn ($query) => $query->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo !== '', fn ($query) => $query->whereDate('created_at', '<=', $dateTo));

        $totalFiltered = (clone $query)->count();
        $openedFiltered = (clone $query)->opened()->count();

        $logs = $query->with(['campaign', 'campaignAd', 'cs'])
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $campaigns = Campaign::orderBy('name')->get();
        $ads = $campaignId > 0
            ? CampaignAd::where('campaign_id', $campaignId)->orderBy('name')->get()
            : CampaignAd::with('campaign')->orderBy('name')->get();
        $csList = WhatsAppCs::orderBy('name')->get();

        $statsToday = [
            'visits' => ChatLog::humanTraffic()->whereDate('created_at', today())->count(),
            'opened' => ChatLog::humanTraffic()->opened()->whereDate('created_at', today())->count(),
            'per_campaign' => Campaign::query()
                ->withCount([
                    'chatLogs as visits_count' => fn ($q) => $q->humanTraffic()->whereDate('chat_logs.created_at', today()),
                    'chatLogs as opens_count' => fn ($q) => $q->humanTraffic()->opened()->whereDate('chat_logs.created_at', today()),
                ])
                ->whereHas('chatLogs', fn ($q) => $q->humanTraffic()->whereDate('created_at', today()))
                ->orderByDesc('visits_count')
                ->limit(10)
                ->get(),
            'per_cs' => WhatsAppCs::query()
                ->withCount([
                    'chatLogs as visits_count' => fn ($q) => $q->humanTraffic()->whereDate('chat_logs.created_at', today()),
                    'chatLogs as opens_count' => fn ($q) => $q->humanTraffic()->opened()->whereDate('chat_logs.created_at', today()),
                ])
                ->whereHas('chatLogs', fn ($q) => $q->humanTraffic()->whereDate('created_at', today()))
                ->orderByDesc('visits_count')
                ->get(),
            'unassigned' => ChatLog::humanTraffic()->whereDate('created_at', today())
                ->whereNull('cs_id')
                ->count(),
        ];
        $statsToday['open_rate'] = $statsToday['visits'] > 0
            ? round(($statsToday['opened'] / $statsToday['visits']) * 100, 1)
            : 0;

        return view('admin.chat-logs.index', compact(
            'logs',
            'campaigns',
            'csList',
            'campaignId',
            'ads',
            'adId',
            'csId',
            'dateFrom',
            'dateTo',
            'statsToday',
            'totalFiltered',
            'openedFiltered'
        ));
    }
}
