<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSystemMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $maintenanceEnabled = Setting::getValue('maintenance_mode_enabled', '0') === '1';

        if (! $maintenanceEnabled) {
            return $next($request);
        }

        // Always allow admin dashboard and authentication routes
        if ($request->is('admin*') || $request->is('login*') || $request->is('logout*')) {
            return $next($request);
        }

        // Allow authenticated admin users ONLY if explicit admin bypass setting is activated
        $adminBypassEnabled = Setting::getValue('maintenance_bypass_admin', '0') === '1';
        if ($adminBypassEnabled && $request->user() && $request->user()->isAdmin()) {
            return $next($request);
        }

        // Check secret bypass token in query string (?bypass=...)
        $bypassKey = Setting::getValue('maintenance_bypass_key');
        if (! empty($bypassKey) && $request->query('bypass') === $bypassKey) {
            session(['maintenance_bypassed' => true]);
            return redirect()->to($request->url());
        }

        // Check if session previously unlocked maintenance
        if (session('maintenance_bypassed') === true) {
            return $next($request);
        }

        // Check IP whitelist
        $allowedIpsRaw = Setting::getValue('maintenance_allowed_ips', '');
        if (! empty($allowedIpsRaw)) {
            $allowedIps = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $allowedIpsRaw)));
            if (in_array($request->ip(), $allowedIps, true)) {
                return $next($request);
            }
        }

        $settings = Setting::allValues();
        return response()->view('errors.maintenance', compact('settings'), 503);
    }
}
