<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsLembaga
{
    /**
     * Handle an incoming request.
     * Only allow access if user has lembaga (institution) account type.
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

        if (! $user->isInstitution()) {
            // Redirect personal users back to their dashboard
            return redirect()->route('user.dashboard')
                ->with('error', 'Halaman ini hanya untuk akun Lembaga.');
        }

        return $next($request);
    }
}
