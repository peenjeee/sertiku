<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class OnboardingController extends Controller
{
    /**
     * Show the onboarding form
     */
    public function show()
    {
        $user = Auth::user();
        
        // If profile is already completed, redirect to dashboard
        if ($user->isProfileCompleted()) {
            return redirect()->route('dashboard');
        }
        
        return view('onboarding.index', compact('user'));
    }

    /**
     * Store the onboarding data
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $accountType = $request->input('account_type');

        // Base validation rules
        $rules = [
            'account_type' => 'required|in:personal,institution',
        ];

        if ($accountType === 'personal') {
            $rules = array_merge($rules, [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'occupation' => 'required|string|max:255',
                'user_institution' => 'nullable|string|max:255',
            ]);
        } else {
            $rules = array_merge($rules, [
                // Institution info
                'institution_name' => 'required|string|max:255',
                'institution_type' => 'required|string|max:255',
                'sector' => 'required|string|max:255',
                'website' => 'nullable|url|max:255',
                'description' => 'nullable|string|max:1000',
                // Address
                'address_line' => 'required|string|max:500',
                'city' => 'required|string|max:100',
                'province' => 'required|string|max:100',
                'postal_code' => 'required|string|max:10',
                'country' => 'required|string|max:100',
                // Admin info
                'admin_name' => 'required|string|max:255',
                'admin_phone' => 'required|string|max:20',
                'admin_position' => 'required|string|max:255',
            ]);
        }

        // Optional email/password (only if not already set)
        if (!$user->hasGoogleLogin() && !$user->hasWalletLogin()) {
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
        }
        
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        }

        $validated = $request->validate($rules);

        // Update user data
        $updateData = [
            'account_type' => $accountType,
            'profile_completed' => true,
        ];

        if ($accountType === 'personal') {
            $updateData = array_merge($updateData, [
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'occupation' => $validated['occupation'],
                'user_institution' => $validated['user_institution'] ?? null,
            ]);
        } else {
            $updateData = array_merge($updateData, [
                'institution_name' => $validated['institution_name'],
                'institution_type' => $validated['institution_type'],
                'sector' => $validated['sector'],
                'website' => $validated['website'] ?? null,
                'description' => $validated['description'] ?? null,
                'address_line' => $validated['address_line'],
                'city' => $validated['city'],
                'province' => $validated['province'],
                'postal_code' => $validated['postal_code'],
                'country' => $validated['country'],
                'admin_name' => $validated['admin_name'],
                'admin_phone' => $validated['admin_phone'],
                'admin_position' => $validated['admin_position'],
                'name' => $validated['admin_name'], // Use admin name as user name
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

        return redirect()->route('dashboard')->with('success', 'Profil berhasil dilengkapi! Selamat datang di SertiKu.');
    }

    /**
     * Skip onboarding (mark as completed without full data)
     */
    public function skip()
    {
        $user = Auth::user();
        
        // Set a default account type and mark as completed
        $user->update([
            'account_type' => 'personal',
            'profile_completed' => true,
        ]);

        return redirect()->route('dashboard')->with('info', 'Anda dapat melengkapi profil nanti di pengaturan akun.');
    }
}
