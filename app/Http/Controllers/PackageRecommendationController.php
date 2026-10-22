<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Setting;
use App\Services\PackageRecommendationService;
use App\Support\AiRecommendationSettings;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PackageRecommendationController extends Controller
{
    public function index()
    {
        return $this->page();
    }

    public function recommend(Request $request, PackageRecommendationService $recommendations)
    {
        $cities = $this->cities();
        $preferences = $request->validate([
            'budget' => ['nullable', 'integer', 'min:1000000', 'max:1000000000'],
            'month' => ['nullable', 'date_format:Y-m', 'after_or_equal:'.today()->format('Y-m')],
            'city' => ['nullable', 'string', Rule::in($cities)],
            'notes' => ['nullable', 'string', 'max:300'],
            'ai_consent' => ['nullable', 'boolean'],
        ]);

        $result = $recommendations->recommend($preferences);
        $request->session()->put('package_recommendations', [
            'ids' => $result['packages']->pluck('id')->all(),
            'preferences' => $preferences,
        ]);

        return $this->page($preferences, $result);
    }

    public function searchPrompt(Request $request, PackageRecommendationService $recommendations)
    {
        $data = $request->validate([
            'prompt' => ['required', 'string', 'min:10', 'max:300'],
            'ai_consent' => ['accepted'],
        ]);

        $result = $recommendations->searchByPrompt(trim($data['prompt']));
        if (in_array($result['status'], ['ok', 'no_match'], true)) {
            $request->session()->put('package_recommendations', [
                'ids' => $result['packages']->pluck('id')->all(),
                'preferences' => ['notes' => trim($data['prompt'])],
            ]);
        }

        $httpStatus = match ($result['status']) {
            'unavailable' => 503,
            'quota_exceeded' => 429,
            'failed' => 502,
            default => 200,
        };

        return response()->json([
            'status' => $result['status'],
            'package_ids' => $result['packages']->pluck('id')->all(),
            'message' => match ($result['status']) {
                'ok' => $result['packages']->count().' paket ditemukan untuk kebutuhan Anda. Harga dan kursi perlu dikonfirmasi dengan CS.',
                'no_match' => 'Belum ada paket yang cocok dengan kebutuhan ini. Coba ubah pencarian Anda.',
                'quota_exceeded' => 'Batas pencarian AI hari ini sudah tercapai. Silakan coba lagi nanti.',
                'unavailable' => 'Pencarian AI belum tersedia. Anda masih bisa melihat semua paket.',
                default => 'Pencarian AI sedang terganggu. Silakan coba lagi nanti.',
            },
        ], $httpStatus);
    }

    private function page(?array $preferences = null, ?array $result = null)
    {
        return view('packages.recommend', [
            'settings' => Setting::allValues(),
            'cities' => $this->cities(),
            'preferences' => $preferences,
            'result' => $result,
            'ai' => AiRecommendationSettings::current(),
        ]);
    }

    private function cities(): array
    {
        return Package::query()
            ->where('is_active', true)
            ->whereDate('departure_date', '>=', today())
            ->whereNotNull('start_city')
            ->where('start_city', '!=', '')
            ->distinct()
            ->orderBy('start_city')
            ->pluck('start_city')
            ->all();
    }
}
