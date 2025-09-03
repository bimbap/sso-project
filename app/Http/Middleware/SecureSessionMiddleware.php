<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SecureSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Check if session is expired (2 hours of inactivity)
            if ($user->last_activity && $user->last_activity->diffInHours(now()) > 2) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/login')->with('message', 'Session expired due to inactivity.');
            }

            // Update last activity
            $user->updateLastActivity();

            // Regenerate session ID periodically for security
            if (!$request->session()->has('last_regeneration') ||
                now()->diffInMinutes($request->session()->get('last_regeneration')) > 30) {
                $request->session()->regenerate();
                $request->session()->put('last_regeneration', now());
            }
        }

        return $next($request);
    }
}
