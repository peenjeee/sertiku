<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsPengguna
{
    /**
     * Handle an incoming request.
     * Only allow access if user has pengguna (personal) account type.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Allow admin to access anything
        if ($user->is_admin) {
            return $next($request);
        }

        if ($user->isInstitution()) {
            // Redirect lembaga users back to their dashboard
            return redirect()->route('lembaga.dashboard')
                ->with('error', 'Halaman ini hanya untuk akun Pengguna Personal.');
        }

        return $next($request);
    }
}
