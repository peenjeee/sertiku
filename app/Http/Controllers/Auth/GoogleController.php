<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\ActivityLog;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        // Handle jika user klik Batal
        if (request()->has('error')) {
            return redirect('/login')->with('error', 'Login dibatalkan.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user already exists
            $existingUser = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            $isNewUser = ! $existingUser;

            $user = User::updateOrCreate(
                ['google_id' => $googleUser->getId()],
                [
                    'name'   => $googleUser->getName(),
                    'email'  => $googleUser->getEmail(),
                    'avatar' => $googleUser->getAvatar(),
                    // Do NOT auto-verify email - user must verify via OTP
                ]
            );

            Auth::login($user);

            // Log activity
            ActivityLog::log('login', 'Login via Google: ' . $user->email, $user);

            // If email not verified, send OTP and redirect to verification
            if (! $user->email_verified_at) {
                // Generate and send OTP
                $verification = EmailVerification::generateOtp($user);
                Mail::to($user->email)->send(new OtpMail($verification));

                $message = $isNewUser
                    ? 'Akun berhasil dibuat. Silakan verifikasi email Anda.'
                    : 'Silakan verifikasi email Anda untuk melanjutkan.';

                return redirect()->route('verification.otp')->with('success', $message);
            }

            // Check if profile is completed
            if (! $user->isProfileCompleted()) {
                return redirect()->route('onboarding')->with('info', 'Silakan lengkapi profil Anda.');
            }

            // Redirect to appropriate dashboard based on account type
            $redirectRoute = $user->isInstitution() ? 'lembaga.dashboard' : 'dashboard';
            return redirect()->route($redirectRoute)->with('success', 'Selamat datang, ' . $user->name . '!');
        } catch (\Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        $user = Auth::user();

        // Log activity before logout
        if ($user) {
            ActivityLog::log('logout', 'User logout: ' . $user->email, $user);
        }

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda telah berhasil logout!');
    }
}
