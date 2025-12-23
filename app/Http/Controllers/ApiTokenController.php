<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    /**
     * Show API tokens page
     */
    public function index()
    {
        $user = auth()->user();

        // Check if user has Professional or Enterprise package
        if (!$this->canAccessApi($user)) {
            return redirect()->route('lembaga.dashboard')
                ->with('error', 'Fitur API hanya tersedia untuk paket Professional dan Enterprise.');
        }

        $tokens = $user->tokens()->orderBy('created_at', 'desc')->get();

        return view('lembaga.api-tokens', [
            'tokens' => $tokens,
        ]);
    }

    /**
     * Create new API token
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$this->canAccessApi($user)) {
            return redirect()->back()->with('error', 'Fitur API tidak tersedia untuk paket Anda.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Limit tokens per user
        if ($user->tokens()->count() >= 5) {
            return redirect()->back()->with('error', 'Maksimal 5 API Token per akun.');
        }

        $token = $user->createToken($request->name);

        return redirect()->back()->with([
            'success' => 'API Token berhasil dibuat!',
            'newToken' => $token->plainTextToken,
        ]);
    }

    /**
     * Delete API token
     */
    public function destroy(Request $request, $tokenId)
    {
        $user = auth()->user();

        $token = $user->tokens()->find($tokenId);

        if (!$token) {
            return redirect()->back()->with('error', 'Token tidak ditemukan.');
        }

        $token->delete();

        return redirect()->back()->with('success', 'API Token berhasil dihapus.');
    }

    /**
     * Check if user can access API features
     */
    private function canAccessApi($user): bool
    {
        $package = $user->getActivePackage();

        if (!$package) {
            return false;
        }

        return in_array($package->slug, ['professional', 'enterprise']);
    }
}
