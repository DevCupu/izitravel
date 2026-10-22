<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Services\ChatRouterService;
use Illuminate\Http\Request;

class ChatRouterController extends Controller
{
    /**
     * Route the inbound /chat visit: reads UTM params, matches an active
     * campaign, picks a CS by weighted-random, logs the human visit, and builds the
     * wa.me URL. Renders the interstitial that describes what's about to happen.
     */
    public function redirect(Request $request, ChatRouterService $router)
    {
        $message = null;
        $recommendedId = filter_var($request->query('recommended_package_id'), FILTER_VALIDATE_INT);
        $saved = $request->session()->get('package_recommendations', []);

        if ($recommendedId && in_array($recommendedId, $saved['ids'] ?? [], true)) {
            $package = Package::query()
                ->whereKey($recommendedId)
                ->where('is_active', true)
                ->whereDate('departure_date', '>=', today())
                ->first();

            if ($package) {
                $preferences = $saved['preferences'] ?? [];
                $details = ["paket {$package->name}"];
                if (! empty($preferences['budget'])) {
                    $details[] = 'anggaran maksimal Rp '.number_format((int) $preferences['budget'], 0, ',', '.');
                }
                if (! empty($preferences['month'])) {
                    $details[] = 'target berangkat '.$preferences['month'];
                }
                if (! empty($preferences['city'])) {
                    $details[] = 'kota asal '.$preferences['city'];
                }
                if (! empty($preferences['notes'])) {
                    $details[] = 'catatan: '.preg_replace('/[\r\n\x00-\x1f]+/u', ' ', $preferences['notes']);
                }

                $message = "Assalamu'alaikum IZI Travel, saya ingin konsultasi tentang ".implode(', ', $details).'.';
            }
        }

        $result = $router->route($request, $message);

        if ($result['wa_url'] !== null) {
            $router->markRedirected($result['token']);

            return redirect()
                ->away($result['wa_url'])
                ->cookie(ChatRouterService::VISITOR_COOKIE, $result['visitor_uid'], 60 * 24 * 365)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }

        return response()
            ->view('chat', $result)
            ->cookie(ChatRouterService::VISITOR_COOKIE, $result['visitor_uid'], 60 * 24 * 365)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Backward-compatible click beacon for previously cached /chat pages. The
     * current flow records the redirect server-side before issuing the 302.
     * Cached pages may still call this endpoint (POST) with their
     * unique token whenever its old auto-redirect fires. The status represents
     * a redirect attempt, not proof that WhatsApp opened or a message was sent.
     *
     * Returns 204 (no body) to keep the beacon light; the page never waits on
     * it because the WhatsApp redirect already happened.
     */
    public function trackClick(Request $request, ChatRouterService $router)
    {
        $router->markClicked($request->input('token'));

        return response()->noContent();
    }
}
