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
     * Admin, Master, and regular users are NOT allowed to checkout.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Not logged in -> redirect to login
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        // Only allow lembaga/institution accounts to proceed
        if ($user->isInstitution()) {
            return $next($request);
        }

        // Redirect based on role with appropriate message
        $message = 'Pembelian paket hanya tersedia untuk akun Lembaga/Institusi.';

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard')->with('error', $message);
        }

        if ($user->is_master) {
            return redirect()->route('master.dashboard')->with('error', $message);
        }

        if ($user->isPersonal()) {
            return redirect()->route('user.dashboard')->with('error', $message);
        }

        // Default: redirect to landing page
        return redirect()->route('landing')->with('error', $message);
    }
}
