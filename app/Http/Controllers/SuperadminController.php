<?php
namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class SuperadminController extends Controller
{
    /**
     * Show superadmin dashboard
     */
    public function dashboard()
    {
        // Get comprehensive stats
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('is_admin', true)->count(),
            'total_superadmins' => User::where('is_superadmin', true)->count(),
            'total_lembaga' => User::where('account_type', 'lembaga')->count(),
            'total_pengguna' => User::where('account_type', 'pengguna')->count(),
            'total_certificates' => Certificate::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'paid')->sum('amount') ?? 0,
        ];

        // Recent activities
        $recentUsers = User::latest()->take(5)->get();
        $recentOrders = Order::with('user', 'package')->latest()->take(5)->get();

        // All admins list
        $admins = User::where('is_admin', true)->orderBy('is_superadmin', 'desc')->get();

        return view('superadmin.dashboard', compact('stats', 'recentUsers', 'recentOrders', 'admins'));
    }

    /**
     * Manage admins
     */
    public function manageAdmins()
    {
        $admins = User::where('is_admin', true)->orderBy('is_superadmin', 'desc')->get();
        $users = User::where('is_admin', false)->get();

        return view('superadmin.admins', compact('admins', 'users'));
    }

    /**
     * Promote user to admin
     */
    public function promoteToAdmin(Request $request, User $user)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403);
        }

        // Save original account type before promoting
        $user->original_account_type = $user->account_type;
        $user->is_admin = true;
        $user->account_type = 'admin';
        $user->save();

        return back()->with('success', "User {$user->name} berhasil dijadikan Admin.");
    }

    /**
     * Demote admin to user
     */
    public function demoteAdmin(Request $request, User $user)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403);
        }

        // Cannot demote superadmin
        if ($user->is_superadmin) {
            return back()->with('error', 'Tidak dapat menurunkan Super Admin.');
        }

        // Cannot demote self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menurunkan diri sendiri.');
        }

        $user->is_admin = false;
        // Restore original account type or default to 'pengguna'
        $user->account_type = $user->original_account_type ?? 'pengguna';
        $user->original_account_type = null;
        $user->save();

        return back()->with('success', "Admin {$user->name} berhasil diturunkan ke User.");
    }

    /**
     * System settings
     */
    public function settings()
    {
        return view('superadmin.settings');
    }

    /**
     * Activity logs (placeholder)
     */
    public function logs()
    {
        return view('superadmin.logs');
    }
}
