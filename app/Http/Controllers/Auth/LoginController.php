<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Login biasa: email + password
     */
    public function loginEmail(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email    = strtolower($credentials['email']);
        $password = $credentials['password'];

        /**
         * 1) CEK 3 AKUN DUMMY DULU
         * ----------------------------------------------------
         * admin@sertiku.my.id   / admin123
         * lembaga@sertiku.my.id / lembaga123
         * user@sertiku.my.id    / user123
         */
        $dummyUsers = [
            'admin@sertiku.my.id' => [
                'name'     => 'Admin SertiKu',
                'password' => 'admin123',
            ],
            'lembaga@sertiku.my.id' => [
                'name'     => 'Lembaga SertiKu',
                'password' => 'lembaga123',
            ],
            'user@sertiku.my.id' => [
                'name'     => 'User SertiKu',
                'password' => 'user123',
            ],
        ];

        if (isset($dummyUsers[$email]) && $password === $dummyUsers[$email]['password']) {
            // Cari / buat user di database
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'     => $dummyUsers[$email]['name'],
                    'password' => bcrypt($dummyUsers[$email]['password']),
                ]
            );

            Auth::login($user, true);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        /**
         * 2) Kalau BUKAN dummy account → pakai login normal Laravel
         * ---------------------------------------------------------
         */
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
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
        $data = $request->validate([
            'wallet_address' => ['required', 'string'],
        ]);

        $wallet = strtolower($data['wallet_address']);

        // cari user berdasarkan wallet_address
        $user = User::where('wallet_address', $wallet)->first();

        // kalau belum ada, buat user baru (dummy). Sesuaikan kebutuhanmu.
        if (! $user) {
            $user = User::create([
                'name'           => 'User Web3',
                'email'          => $wallet.'@wallet.local',
                'password'       => bcrypt(str()->random(32)),
                'wallet_address' => $wallet,
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
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
}
