<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSuperAdmin
{
    /**
     * Handle an incoming request.
     * Only allow access if user is a superadmin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (! auth()->user()->is_superadmin) {
            // Redirect based on account type
            if (auth()->user()->is_admin) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Akses ditolak. Halaman ini hanya untuk Super Admin.');
            }
            if (auth()->user()->isInstitution()) {
                return redirect()->route('lembaga.dashboard')
                    ->with('error', 'Akses ditolak. Halaman ini hanya untuk Super Admin.');
            }
            return redirect()->route('user.dashboard')
                ->with('error', 'Akses ditolak. Halaman ini hanya untuk Super Admin.');
        }

        return $next($request);
    }
}
