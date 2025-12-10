<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class OnboardingController extends Controller
{
    /**
     * Get the appropriate dashboard route based on account type
     */
    protected function getDashboardRoute($user)
    {
        if ($user->isInstitution()) {
            return route('lembaga.dashboard');
        }
        return route('dashboard');
    }

    /**
     * Show the onboarding form
     */
    public function show()
    {
        $user = Auth::user();

        // If profile is already completed, redirect to appropriate dashboard
        if ($user->isProfileCompleted()) {
            return redirect($this->getDashboardRoute($user));
        }

        return view('onboarding.index', compact('user'));
    }

    /**
     * Store the onboarding data
     */
    public function store(Request $request)
    {
        $user        = Auth::user();
        $accountType = $request->input('account_type');

        // Base validation rules
        $rules = [
            'account_type' => 'required|in:personal,institution',
        ];

        if ($accountType === 'personal') {
            $rules = array_merge($rules, [
                'name'             => 'required|string|max:255',
                'phone'            => 'nullable|string|max:20',
                'occupation'       => 'nullable|string|max:255',
                'user_institution' => 'nullable|string|max:255',
                'city'             => 'nullable|string|max:100',
            ]);
        } else {
            $rules = array_merge($rules, [
                // Institution info
                'institution_name' => 'required|string|max:255',
                'institution_type' => 'required|string|max:255',
                'sector'           => 'nullable|string|max:255',
                'website'          => 'nullable|url|max:255',
                'description'      => 'nullable|string|max:1000',
                // Address (simplified)
                'address_line'     => 'nullable|string|max:500',
                'city'             => 'required|string|max:100',
                'province'         => 'nullable|string|max:100',
                'postal_code'      => 'nullable|string|max:10',
                'country'          => 'nullable|string|max:100',
                // Admin info
                'admin_name'       => 'required|string|max:255',
                'admin_phone'      => 'nullable|string|max:20',
                'admin_position'   => 'nullable|string|max:255',
            ]);
        }

        // Optional email/password (only if not already set)
        if (! $user->hasGoogleLogin() && ! $user->hasWalletLogin()) {
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
        }

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        }

        $validated = $request->validate($rules);

        // Update user data
        $updateData = [
            'account_type'      => $accountType,
            'profile_completed' => true,
        ];

        if ($accountType === 'personal') {
            $updateData = array_merge($updateData, [
                'name'             => $validated['name'],
                'phone'            => $validated['phone'] ?? null,
                'occupation'       => $validated['occupation'] ?? null,
                'user_institution' => $validated['user_institution'] ?? null,
                'city'             => $validated['city'] ?? null,
            ]);
        } else {
            $updateData = array_merge($updateData, [
                'institution_name' => $validated['institution_name'],
                'institution_type' => $validated['institution_type'],
                'sector'           => $validated['sector'] ?? null,
                'website'          => $validated['website'] ?? null,
                'description'      => $validated['description'] ?? null,
                'address_line'     => $validated['address_line'] ?? null,
                'city'             => $validated['city'],
                'province'         => $validated['province'] ?? null,
                'postal_code'      => $validated['postal_code'] ?? null,
                'country'          => $validated['country'] ?? null,
                'admin_name'       => $validated['admin_name'],
                'admin_phone'      => $validated['admin_phone'] ?? null,
                'admin_position'   => $validated['admin_position'] ?? null,
                'name'             => $validated['admin_name'], // Use admin name as user name
            ]);
        }

        // Handle optional email
        if (isset($validated['email'])) {
            $updateData['email'] = $validated['email'];
        }

        // Handle optional password
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Check if AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            $redirectUrl = $user->isInstitution() ? route('lembaga.dashboard') : route('user.dashboard');
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil disimpan!',
                'redirect_url' => $redirectUrl,
                'account_type' => $accountType,
            ]);
        }

        // Redirect to appropriate dashboard based on account type
        $redirectRoute = $user->isInstitution() ? 'lembaga.dashboard' : 'user.dashboard';
        return redirect()->route($redirectRoute)->with('success', 'Profil berhasil dilengkapi! Selamat datang di SertiKu.');
    }

    /**
     * Skip onboarding (mark as completed without full data)
     */
    public function skip()
    {
        $user = Auth::user();

        // Set a default account type and mark as completed
        $user->update([
            'account_type'      => 'personal',
            'profile_completed' => true,
        ]);

        return redirect()->route('dashboard')->with('info', 'Anda dapat melengkapi profil nanti di pengaturan akun.');
    }
}
