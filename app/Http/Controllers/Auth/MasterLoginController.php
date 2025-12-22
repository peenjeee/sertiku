<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $email    = strtolower($credentials['email']);
        $password = $credentials['password'];

        // Check if this is the dummy master credentials
        $dummyMaster = [
            'email'             => 'master@sertiku.web.id',
            'password'          => 'Master123',
            'name'              => 'Master SertiKu',
            'is_admin'          => true,
            'is_master'         => true,
            'account_type'      => 'admin',
            'profile_completed' => true,
        ];

        // If using dummy credentials, create or update the user
        if ($email === $dummyMaster['email'] && $password === $dummyMaster['password']) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'              => $dummyMaster['name'],
                    'password'          => bcrypt($dummyMaster['password']),
                    'account_type'      => $dummyMaster['account_type'],
                    'profile_completed' => $dummyMaster['profile_completed'],
                ]
            );

            // Sync master fields
            $user->is_admin     = $dummyMaster['is_admin'];
            $user->is_master    = $dummyMaster['is_master'];
            $user->account_type = $dummyMaster['account_type'];
            $user->save();

            // Login the user
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            // Log activity
            ActivityLog::log('login', 'Master login via akun dummy', $user);

            return redirect()->route('master.dashboard')
                ->with('success', 'Selamat datang, Master!');
        }

        // For other users, find in database
        $user = User::where('email', $email)->first();

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun tidak ditemukan.']);
        }

        if (! $user->is_master) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akses ditolak. Halaman ini hanya untuk Master.']);
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

        // Log activity
        ActivityLog::log('login', 'Master login: ' . $user->email, $user);

        return redirect()->route('master.dashboard')
            ->with('success', 'Selamat datang, Master!');
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
}
