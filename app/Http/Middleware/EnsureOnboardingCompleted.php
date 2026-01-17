<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->profile_completed) {
            // Allow access to onboarding routes and logout to prevent infinite loops
            if (!$request->is('onboarding') && !$request->is('onboarding/*') && !$request->is('logout')) {
                return redirect()->route('onboarding');
            }
        }

        return $next($request);
    }
}
