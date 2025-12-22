<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\ActivityLog;
use App\Models\EmailVerification;
use App\Models\User;
use App\Rules\Recaptcha;
use App\Rules\Turnstile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    /**
     * Get the appropriate dashboard route based on account type
     */
    protected function getDashboardRoute($user)
    {
        // Master users go to master panel
        if ($user->is_master) {
            return route('master.dashboard');
        }
        // Admin users go to admin panel
        if ($user->is_admin) {
            return route('admin.dashboard');
        }
        if ($user->isInstitution()) {
            return route('lembaga.dashboard');
        }
        return route('dashboard');
    }

    /**
     * Check if user is exempt from OTP verification
     * Exempt: @sertiku.web.id emails (admin, lembaga, user dummy accounts)
     */
    protected function isExemptFromOtp($user): bool
    {
        if (empty($user->email)) {
            return false;
        }
        return str_ends_with($user->email, '@sertiku.web.id');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Login biasa: email + password
     */
    public function loginEmail(Request $request)
    {
        // Build validation rules
        $rules = [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];

        // Add reCAPTCHA validation if enabled
        if (config('recaptcha.enabled') && config('recaptcha.site_key')) {
            $rules['g-recaptcha-response'] = ['required', new Recaptcha];
        }

        // Add Cloudflare Turnstile validation if enabled
        if (config('turnstile.enabled') && config('turnstile.site_key')) {
            $rules['cf-turnstile-response'] = ['required', new Turnstile];
        }

        $request->validate($rules, [
            'g-recaptcha-response.required'  => 'Mohon verifikasi bahwa Anda bukan robot.',
            'cf-turnstile-response.required' => 'Mohon verifikasi Cloudflare Turnstile.',
        ]);

        // Only use email and password for authentication
        $email       = strtolower($request->input('email'));
        $password    = $request->input('password');
        $credentials = ['email' => $email, 'password' => $password];

        /**
         * 1) CEK 3 AKUN DUMMY DULU
         * ----------------------------------------------------
         */
        $dummyUsers = [
            'master@sertiku.web.id'  => [
                'name'              => 'Master SertiKu',
                'password'          => 'Master123',
                'is_admin'          => true,
                'is_master'         => true,
                'account_type'      => 'admin',
                'profile_completed' => true,
            ],
            'admin@sertiku.web.id'   => [
                'name'              => 'Admin SertiKu',
                'password'          => 'Admin123',
                'is_admin'          => true,
                'is_master'         => false,
                'account_type'      => 'admin',
                'profile_completed' => true,
            ],
            'lembaga@sertiku.web.id' => [
                'name'              => 'Lembaga SertiKu',
                'password'          => 'lembaga123',
                'is_admin'          => false,
                'is_master'         => false,
                'account_type'      => 'lembaga',
                'profile_completed' => true,
            ],
            'user@sertiku.web.id'    => [
                'name'              => 'User SertiKu',
                'password'          => 'user123',
                'is_admin'          => false,
                'is_master'         => false,
                'account_type'      => 'pengguna',
                'profile_completed' => true,
            ],
        ];

        if (isset($dummyUsers[$email]) && $password === $dummyUsers[$email]['password']) {
            // Cari / buat user di database
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'              => $dummyUsers[$email]['name'],
                    'password'          => bcrypt($dummyUsers[$email]['password']),
                    'account_type'      => $dummyUsers[$email]['account_type'],
                    'profile_completed' => $dummyUsers[$email]['profile_completed'],
                ]
            );

            // Update user fields from dummy config (syncs existing users)
            $user->is_admin          = $dummyUsers[$email]['is_admin'];
            $user->is_master         = $dummyUsers[$email]['is_master'];
            $user->account_type      = $dummyUsers[$email]['account_type'];
            $user->profile_completed = $dummyUsers[$email]['profile_completed'];
            $user->save();

            Auth::login($user, true);
            $request->session()->regenerate();

            // Log activity
            ActivityLog::log('login', 'Login via akun dummy: ' . $user->email, $user);

            // Check email verification first (skip for @sertiku.web.id)
            if (! $user->email_verified_at && ! $this->isExemptFromOtp($user)) {
                $this->sendOtpIfNeeded($user);
                return redirect()->route('verification.otp');
            }

            // Master users go to master dashboard
            if ($user->is_master) {
                return redirect()->route('master.dashboard');
            }

            // Admin users skip onboarding
            if ($user->is_admin) {
                return redirect()->route('admin.dashboard');
            }

            // Check if profile is completed
            if (! $user->isProfileCompleted()) {
                return redirect()->route('onboarding');
            }

            return redirect()->intended($this->getDashboardRoute($user));
        }

        /**
         * 2) Kalau BUKAN dummy account → pakai login normal Laravel
         * ---------------------------------------------------------
         */
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Log activity
            ActivityLog::log('login', 'Login via email: ' . $user->email, $user);

            // Check email verification first (skip for @sertiku.web.id)
            if (! $user->email_verified_at && ! $this->isExemptFromOtp($user)) {
                $this->sendOtpIfNeeded($user);
                return redirect()->route('verification.otp');
            }

            // Master users go to master dashboard
            if ($user->is_master) {
                return redirect()->route('master.dashboard');
            }

            // Admin users skip onboarding and go to admin panel
            if ($user->is_admin) {
                return redirect()->route('admin.dashboard');
            }

            if (! $user->isProfileCompleted()) {
                return redirect()->route('onboarding');
            }

            return redirect()->intended($this->getDashboardRoute($user));
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    /**
     * Login via wallet (contoh simpel: auto-registrasi kalau belum ada).
     * Di production wajib ditambah verifikasi signature, dsb.
     */
    public function loginWallet(Request $request)
    {
        // Build validation rules
        $rules = [
            'wallet_address' => ['required', 'string'],
        ];

        // Add reCAPTCHA validation if enabled
        if (config('recaptcha.enabled') && config('recaptcha.site_key')) {
            $rules['g-recaptcha-response'] = ['required', new Recaptcha];
        }

        // Add Cloudflare Turnstile validation if enabled
        if (config('turnstile.enabled') && config('turnstile.site_key')) {
            $rules['cf-turnstile-response'] = ['required', new Turnstile];
        }

        $data = $request->validate($rules, [
            'g-recaptcha-response.required'  => 'Mohon verifikasi bahwa Anda bukan robot.',
            'cf-turnstile-response.required' => 'Mohon verifikasi Cloudflare Turnstile.',
        ]);

        $wallet = strtolower($data['wallet_address']);

        // cari user berdasarkan wallet_address
        $user      = User::where('wallet_address', $wallet)->first();
        $isNewUser = ! $user;

        // kalau belum ada, buat user baru (dummy). Sesuaikan kebutuhanmu.
        if (! $user) {
            $user = User::create([
                'name'           => 'User Web3',
                'email'          => $wallet . '@wallet.local',
                'password'       => bcrypt(str()->random(32)),
                'wallet_address' => $wallet,
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        // Log activity
        ActivityLog::log('login', 'Login via wallet: ' . $wallet, $user);

        // Check email verification - wallet users need to input real email first (skip for @sertiku.web.id)
        if (! $user->email_verified_at && ! $this->isExemptFromOtp($user)) {
            // If user has dummy email, redirect to email input
            if (str_ends_with($user->email, '@wallet.local')) {
                $message = $isNewUser
                    ? 'Akun berhasil dibuat. Masukkan email untuk verifikasi.'
                    : 'Masukkan email untuk verifikasi akun.';
                return redirect()->route('verification.email.input')->with('info', $message);
            }

            // If user has real email, send OTP
            $this->sendOtpIfNeeded($user);
            return redirect()->route('verification.otp');
        }

        // Check if profile is completed
        if (! $user->isProfileCompleted()) {
            return redirect()->route('onboarding');
        }

        return redirect()->intended($this->getDashboardRoute($user));
    }

    // === GOOGLE LOGIN (kalau nanti mau pakai Socialite di sini) ===
    public function redirectToGoogle()
    {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'     => $googleUser->getName() ?: $googleUser->getNickname(),
                'password' => bcrypt(str()->random(32)),
            ]
        );

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Send OTP if user doesn't have a recent one
     */
    protected function sendOtpIfNeeded(User $user): void
    {
        // Check if user has valid email
        if (empty($user->email) || str_ends_with($user->email, '@wallet.local')) {
            return; // Will be redirected to email input page
        }

        // Check if there's a recent OTP (within last 2 minutes)
        $recentOtp = EmailVerification::where('user_id', $user->id)
            ->where('created_at', '>', now()->subMinutes(2))
            ->whereNull('verified_at')
            ->first();

        if ($recentOtp) {
            return; // Already sent recently
        }

        // Generate and send new OTP
        $verification = EmailVerification::generateOtp($user);
        Mail::to($user->email)->send(new OtpMail($verification));
    }
}
