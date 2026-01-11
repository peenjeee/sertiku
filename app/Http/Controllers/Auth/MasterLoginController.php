<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class MasterLoginController extends Controller
{
    /**
     * Show master login form
     */
    public function showLoginForm()
    {
        // If already logged in as master, redirect to dashboard
        if (Auth::check() && Auth::user()->is_master) {
            return redirect()->route('master.dashboard');
        }

        return view('auth.master-login');
    }

    /**
     * Handle master login request
     */
    public function login(Request $request)
    {
        // Rate Limiting (5 attempts per minute)
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->with('error', "Terlalu banyak percobaan login. Silakan coba lagi dalam $seconds detik.")
                ->with('rate_limit', $seconds);
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'cf-turnstile-response' => ['required', 'string'],
        ], [
            'cf-turnstile-response.required' => 'Silakan selesaikan CAPTCHA terlebih dahulu.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Verify Turnstile
        if (!$this->verifyTurnstile($request->input('cf-turnstile-response'))) {
            return back()->withInput()->with('error', 'Verifikasi CAPTCHA gagal. Silakan coba lagi.');
        }

        $email = strtolower($credentials['email']);
        $password = $credentials['password'];

        // Check if this is the dummy master credentials
        $dummyMaster = [
            'email' => 'master@sertiku.web.id',
            'password' => 'Master123',
            'name' => 'Master SertiKu',
            'is_admin' => true,
            'is_master' => true,
            'account_type' => 'admin',
            'profile_completed' => true,
        ];

        // If using dummy credentials, create or update the user
        if ($email === $dummyMaster['email'] && $password === $dummyMaster['password']) {
            RateLimiter::clear($throttleKey);

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $dummyMaster['name'],
                    'password' => bcrypt($dummyMaster['password']),
                    'account_type' => $dummyMaster['account_type'],
                    'profile_completed' => $dummyMaster['profile_completed'],
                ]
            );

            // Sync master fields
            $user->is_admin = $dummyMaster['is_admin'];
            $user->is_master = $dummyMaster['is_master'];
            $user->account_type = $dummyMaster['account_type'];
            $user->save();

            // Login the user (without remember me)
            Auth::login($user);
            $request->session()->regenerate();

            // Log activity
            ActivityLog::log('login', 'Master login via akun dummy', $user);

            return redirect()->route('master.dashboard')
                ->with('success', 'Selamat datang kembali, Master!');
        }

        // For other users, find in database
        $user = User::where('email', $email)->first();

        if (!$user) {
            RateLimiter::hit($throttleKey);
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Akun tidak ditemukan.');
        }

        if (!$user->is_master) {
            RateLimiter::hit($throttleKey);
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Akses ditolak. Halaman ini hanya untuk Master.');
        }

        // Check password
        if (!Hash::check($password, $user->password)) {
            RateLimiter::hit($throttleKey);
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Password salah.');
        }

        // Clear rate limiter on success
        RateLimiter::clear($throttleKey);

        // Login the user (without remember me)
        Auth::login($user);
        $request->session()->regenerate();

        // Log activity
        ActivityLog::log('login', 'Master login: ' . $user->email, $user);

        return redirect()->route('master.dashboard')
            ->with('success', 'Selamat datang kembali, Master!');
    }

    /**
     * Handle master logout
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        // Log activity before logout
        if ($user) {
            ActivityLog::log('logout', 'Master logout: ' . $user->email, $user);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('master.login')
            ->with('success', 'Berhasil logout.');
    }

    /**
     * Verify Turnstile Token
     */
    private function verifyTurnstile($token)
    {
        $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
        $data = [
            'secret' => env('TURNSTILE_SECRET_KEY'),
            'response' => $token,
            'remoteip' => request()->ip(),
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $response = json_decode($result);

        return $response->success;
    }
}
