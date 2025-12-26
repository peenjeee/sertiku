<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfessionalPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Allow if user is Professional OR Enterprise
        if ($user->isProfessionalPlan() || $user->isEnterprisePlan()) {
            return $next($request);
        }

        return redirect()->route('lembaga.dashboard')
            ->with('error', 'Fitur ini hanya tersedia untuk Paket Professional ke atas. Silakan upgrade paket Anda.');
    }
}
