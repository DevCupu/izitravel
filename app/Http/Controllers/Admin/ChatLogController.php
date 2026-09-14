<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\ChatLog;
use App\Models\WhatsAppCs;
use Illuminate\Http\Request;

class ChatLogController extends Controller
{
    public function index(Request $request)
    {
        $campaignId = $request->integer('campaign_id');
        $csId = $request->integer('cs_id');
        $dateFrom = $request->string('from')->trim()->toString();
        $dateTo = $request->string('to')->trim()->toString();

        $query = ChatLog::query()
            ->when($campaignId > 0, fn ($query) => $query->where('campaign_id', $campaignId))
            ->when($csId > 0, fn ($query) => $query->where('cs_id', $csId))
            ->when($dateFrom !== '', fn ($query) => $query->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo !== '', fn ($query) => $query->whereDate('created_at', '<=', $dateTo));

        $totalFiltered = (clone $query)->count();

        $logs = $query->with(['campaign', 'cs'])
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $campaigns = Campaign::orderBy('name')->get();
        $csList = WhatsAppCs::orderBy('name')->get();

        $statsToday = [
            'total' => ChatLog::whereDate('created_at', today())->count(),
            'per_campaign' => Campaign::query()
                ->withCount(['chatLogs' => fn ($q) => $q->whereDate('chat_logs.created_at', today())])
                ->whereHas('chatLogs', fn ($q) => $q->whereDate('created_at', today()))
                ->orderByDesc('chat_logs_count')
                ->limit(10)
                ->get(),
            'per_cs' => WhatsAppCs::query()
                ->withCount(['chatLogs' => fn ($q) => $q->whereDate('chat_logs.created_at', today())])
                ->whereHas('chatLogs', fn ($q) => $q->whereDate('created_at', today()))
                ->orderByDesc('chat_logs_count')
                ->get(),
            'unassigned' => ChatLog::whereDate('created_at', today())
                ->whereNull('cs_id')
                ->count(),
        ];

        return view('admin.chat-logs.index', compact(
            'logs',
            'campaigns',
            'csList',
            'campaignId',
            'csId',
            'dateFrom',
            'dateTo',
            'statsToday',
            'totalFiltered'
        ));
    }
}
