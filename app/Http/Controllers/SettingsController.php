<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /**
     * Show settings page
     */
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update avatar/profile photo
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = Auth::user();

        // Delete old avatar if exists and is local file
        if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update([
            'avatar' => '/storage/' . $path,
        ]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Remove avatar
     */
    public function removeAvatar()
    {
        $user = Auth::user();

        // Delete avatar file if exists and is local
        if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
        }

        $user->update(['avatar' => null]);

        return back()->with('success', 'Foto profil berhasil dihapus!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Check if user has password (not wallet-only user)
        if (!$user->password) {
            return back()->with('error', 'Akun Anda tidak memiliki password. Gunakan metode login lain.');
        }

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    /**
     * Delete account
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirm_delete' => 'required|in:HAPUS',
        ]);

        $user = Auth::user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun Anda telah dihapus.');
    }
}
