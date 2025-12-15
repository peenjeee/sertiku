<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SuperadminLoginController extends Controller
{
    /**
     * Show superadmin login form
     */
    public function showLoginForm()
    {
        // If already logged in as superadmin, redirect to dashboard
        if (Auth::check() && Auth::user()->is_superadmin) {
            return redirect()->route('superadmin.dashboard');
        }

        return view('auth.superadmin-login');
    }

    /**
     * Handle superadmin login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $email    = strtolower($credentials['email']);
        $password = $credentials['password'];

        // Check if this is the dummy superadmin credentials
        $dummySuperadmin = [
            'email'             => 'superadmin@sertiku.web.id',
            'password'          => 'SuperAdmin123',
            'name'              => 'Super Admin SertiKu',
            'is_admin'          => true,
            'is_superadmin'     => true,
            'account_type'      => 'admin',
            'profile_completed' => true,
        ];

        // If using dummy credentials, create or update the user
        if ($email === $dummySuperadmin['email'] && $password === $dummySuperadmin['password']) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'              => $dummySuperadmin['name'],
                    'password'          => bcrypt($dummySuperadmin['password']),
                    'account_type'      => $dummySuperadmin['account_type'],
                    'profile_completed' => $dummySuperadmin['profile_completed'],
                ]
            );

            // Sync superadmin fields
            $user->is_admin      = $dummySuperadmin['is_admin'];
            $user->is_superadmin = $dummySuperadmin['is_superadmin'];
            $user->account_type  = $dummySuperadmin['account_type'];
            $user->save();

            // Login the user
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->route('superadmin.dashboard')
                ->with('success', 'Selamat datang, Super Admin!');
        }

        // For other users, find in database
        $user = User::where('email', $email)->first();

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun tidak ditemukan.']);
        }

        if (! $user->is_superadmin) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akses ditolak. Halaman ini hanya untuk Super Admin.']);
        }

        // Check password
        if (! Hash::check($password, $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['password' => 'Password salah.']);
        }

        // Login the user
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Selamat datang, Super Admin!');
    }

    /**
     * Handle superadmin logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('superadmin.login')
            ->with('success', 'Berhasil logout.');
    }
}
