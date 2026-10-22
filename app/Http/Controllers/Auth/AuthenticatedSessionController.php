<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->isCs() ? 'cs.dashboard' : 'admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'actor_name' => $request->user()->name,
            'action' => 'login',
            'subject' => 'Akun',
            'route_name' => 'login',
            'ip_address' => $request->ip(),
        ]);

        $destination = $request->user()->isCs() ? 'cs.dashboard' : 'admin.dashboard';

        return $request->user()->isCs()
            ? redirect()->route($destination)
            : redirect()->intended(route($destination, absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'actor_name' => $request->user()->name,
            'action' => 'logout',
            'subject' => 'Akun',
            'route_name' => 'logout',
            'ip_address' => $request->ip(),
        ]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
