<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     * Only allow access if user is an admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (! auth()->user()->is_admin) {
            // Redirect to appropriate dashboard based on account type
            if (auth()->user()->isInstitution()) {
                return redirect()->route('lembaga.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman Admin.');
            }
            return redirect()->route('user.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman Admin.');
        }

        return $next($request);
    }
}
