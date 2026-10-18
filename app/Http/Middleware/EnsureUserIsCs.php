<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsCs
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        abort_unless($user?->isCs() && $user->is_active && $user->whatsappCs()->exists(), 403);

        if ($user->must_change_password && ! $request->routeIs('cs.password.*')) {
            return redirect()->route('cs.password.edit');
        }

        return $next($request);
    }
}
