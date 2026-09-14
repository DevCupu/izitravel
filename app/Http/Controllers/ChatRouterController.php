<?php

namespace App\Http\Controllers;

use App\Services\ChatRouterService;
use Illuminate\Http\Request;

class ChatRouterController extends Controller
{
    /**
     * Route the inbound /chat lead: reads UTM params, matches an active
     * campaign, picks a CS by weighted-random, logs the lead, and builds the
     * wa.me URL. Renders the interstitial that describes what's about to happen.
     */
    public function redirect(Request $request, ChatRouterService $router)
    {
        $result = $router->route($request);

        return view('chat', $result);
    }

    /**
     * Fire-and-forget click beacon. The /chat page calls this (POST) with its
     * unique token whenever the visitor taps "Lanjut ke WhatsApp" OR the
     * auto-redirect fires — so admins can tell "who really opened WhatsApp"
     * apart from "who just saw the landing page".
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
