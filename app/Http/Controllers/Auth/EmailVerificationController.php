<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    /**
     * Show OTP verification page
     */
    public function show()
    {
        $user = auth()->user();

        // If already verified, redirect to dashboard
        if ($user->email_verified_at) {
            return redirect()->route('dashboard');
        }

        // Check if user has email
        if (empty($user->email) || str_ends_with($user->email, '@wallet.local')) {
            return redirect()->route('verification.email.input');
        }

        // Check if user has a recent OTP (within last 10 minutes)
        $hasRecentOtp = EmailVerification::where('user_id', $user->id)
            ->where('created_at', '>', now()->subMinutes(10))
            ->whereNull('verified_at')
            ->exists();

        // If no recent OTP, send one
        if (!$hasRecentOtp) {
            $this->sendOtp($user);
            session()->flash('success', 'Kode OTP telah dikirim ke email Anda.');
        }

        return view('auth.verify-otp', [
            'email' => $user->email,
        ]);
    }

    /**
     * Show email input page (for wallet users)
     */
    public function showEmailInput()
    {
        $user = auth()->user();

        // If already verified, redirect to dashboard
        if ($user->email_verified_at) {
            return redirect()->route('dashboard');
        }

        // If has valid email, go to OTP page
        if (!empty($user->email) && !str_ends_with($user->email, '@wallet.local')) {
            return redirect()->route('verification.otp');
        }

        return view('auth.input-email');
    }

    /**
     * Store email for wallet users
     */
    public function storeEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email,' . auth()->id()],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan akun lain.',
        ]);

        $user = auth()->user();
        $user->update(['email' => $validated['email']]);

        // Send OTP
        $this->sendOtp($user);

        return redirect()->route('verification.otp')
            ->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    /**
     * Send OTP to user
     */
    public function sendOtp($user = null)
    {
        $user = $user ?? auth()->user();

        // Generate OTP
        $verification = EmailVerification::generateOtp($user);

        // Send email
        Mail::to($user->email)->send(new OtpMail($verification));

        return $verification;
    }

    /**
     * Resend OTP
     */
    public function resend(Request $request)
    {
        $user = auth()->user();

        // Check cooldown (60 seconds)
        $lastOtp = EmailVerification::where('user_id', $user->id)
            ->latest()
            ->first();

        if ($lastOtp && $lastOtp->created_at->diffInSeconds(now()) < 60) {
            $remaining = 60 - $lastOtp->created_at->diffInSeconds(now());
            return back()->with('error', "Tunggu {$remaining} detik sebelum mengirim ulang.");
        }

        $this->sendOtp($user);

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    /**
     * Verify OTP
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size' => 'Kode OTP harus 6 digit.',
        ]);

        $user = auth()->user();
        $otp = $request->input('otp');

        if (EmailVerification::verifyOtp($user, $otp)) {
            // Redirect based on account type
            if ($user->is_admin) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Email berhasil diverifikasi! Selamat datang.');
            }

            if (!$user->isProfileCompleted()) {
                return redirect()->route('onboarding')
                    ->with('success', 'Email berhasil diverifikasi! Silakan lengkapi profil.');
            }

            $route = $user->isInstitution() ? 'lembaga.dashboard' : 'user.dashboard';
            return redirect()->route($route)
                ->with('success', 'Email berhasil diverifikasi! Selamat datang.');
        }

        return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kadaluarsa.']);
    }
}
