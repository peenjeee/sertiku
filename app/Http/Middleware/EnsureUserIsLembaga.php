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
     * Admin, Master, and regular users are NOT allowed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        // Only allow lembaga/institution accounts
        if (!$user->isInstitution()) {
            // Determine where to redirect based on role
            $redirectRoute = match (true) {
                $user->is_admin => 'master.dashboard',
                $user->is_master => 'master.dashboard',
                default => 'user.dashboard',
            };

            return redirect()->route($redirectRoute)
                ->with('error', 'Pembelian paket hanya tersedia untuk akun Lembaga/Institusi.');
        }

        return $next($request);
    }
}
