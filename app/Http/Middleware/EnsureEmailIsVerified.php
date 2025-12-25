<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Skip if not authenticated
        if (!$user) {
            return $next($request);
        }

        // Skip if email is verified
        if ($user->email_verified_at) {
            return $next($request);
        }

        // Skip for internal domain
        if (str_ends_with($user->email ?? '', '@sertiku.web.id')) {
            return $next($request);
        }

        // Skip if admin/master (optional - remove if you want admins to verify too)
        // if ($user->is_admin || $user->is_master) {
        //     return $next($request);
        // }

        // Check if user has valid email
        if (empty($user->email) || str_ends_with($user->email, '@wallet.local')) {
            return redirect()->route('verification.email.input');
        }

        // Redirect to OTP verification
        return redirect()->route('verification.otp');
    }
}
