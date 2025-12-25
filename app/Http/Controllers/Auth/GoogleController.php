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

            $isNewUser = !$existingUser;

            // For new users, create with Google avatar
            // For existing users, preserve their custom avatar if they have one
            if ($isNewUser) {
                $user = User::create([
                    'google_id' => $googleUser->getId(),
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                $user = $existingUser;
                // Update google_id if not set
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                }
                // Only update avatar if user doesn't have a custom local avatar
                // Custom avatars start with /storage/
                if (!$user->avatar || !str_starts_with($user->avatar, '/storage/')) {
                    $user->avatar = $googleUser->getAvatar();
                }
                // Update name if empty
                if (!$user->name) {
                    $user->name = $googleUser->getName();
                }
                $user->save();
            }

            Auth::login($user);

            // Log activity
            ActivityLog::log('login', 'Login via Google: ' . $user->email, $user);

            // If email not verified, send OTP and redirect to verification
            if (!$user->email_verified_at) {
                // Generate and send OTP
                $verification = EmailVerification::generateOtp($user);
                Mail::to($user->email)->send(new OtpMail($verification));

                $message = $isNewUser
                    ? 'Akun berhasil dibuat. Silakan verifikasi email Anda.'
                    : 'Silakan verifikasi email Anda untuk melanjutkan.';

                return redirect()->route('verification.otp')->with('success', $message);
            }

            // Check if profile is completed
            if (!$user->isProfileCompleted()) {
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
