<?php
namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    /**
     * Show master dashboard
     */
    public function dashboard()
    {
        // Get comprehensive stats
        $stats = [
            'total_users'        => User::count(),
            'total_admins'       => User::where('is_admin', true)->count(),
            'total_masters'      => User::where('is_master', true)->count(),
            'total_lembaga'      => User::where('account_type', 'lembaga')->count(),
            'total_pengguna'     => User::where('account_type', 'pengguna')->count(),
            'total_certificates' => Certificate::count(),
            'total_orders'       => Order::count(),
            'total_revenue'      => Order::where('status', 'paid')->sum('amount') ?? 0,
        ];

        // Recent activities
        $recentUsers  = User::latest()->take(5)->get();
        $recentOrders = Order::with('user', 'package')->latest()->take(5)->get();

        // All admins list
        $admins = User::where('is_admin', true)->orderBy('is_master', 'desc')->get();

        return view('master.dashboard', compact('stats', 'recentUsers', 'recentOrders', 'admins'));
    }

    /**
     * Manage admins
     */
    public function manageAdmins()
    {
        $admins = User::where('is_admin', true)->orderBy('is_master', 'desc')->get();
        $users  = User::where('is_admin', false)->get();

        return view('master.admins', compact('admins', 'users'));
    }

    /**
     * Promote user to admin
     */
    public function promoteToAdmin(Request $request, User $user)
    {
        if (! auth()->user()->is_master) {
            abort(403);
        }

        $user->is_admin     = true;
        $user->account_type = 'admin';
        $user->save();

        return back()->with('success', "User {$user->name} berhasil dijadikan Admin.");
    }

    /**
     * Demote admin to user
     */
    public function demoteAdmin(Request $request, User $user)
    {
        if (! auth()->user()->is_master) {
            abort(403);
        }

        // Cannot demote master
        if ($user->is_master) {
            return back()->with('error', 'Tidak dapat menurunkan Master.');
        }

        // Cannot demote self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menurunkan diri sendiri.');
        }

        $user->is_admin     = false;
        $user->account_type = 'pengguna';
        $user->save();

        return back()->with('success', "Admin {$user->name} berhasil diturunkan ke User.");
    }

    /**
     * System settings
     */
    public function settings()
    {
        return view('master.settings');
    }

    /**
     * Activity logs (placeholder)
     */
    public function logs()
    {
        return view('master.logs');
    }

    /**
     * Blockchain Wallet Dashboard
     */
    public function blockchain()
    {
        $blockchainService = new \App\Services\BlockchainService();
        $walletInfo        = $blockchainService->getWalletInfo();

        // Get blockchain certificates stats
        $blockchainStats = [
            'total_blockchain' => Certificate::whereNotNull('blockchain_tx_hash')->count(),
            'pending'          => Certificate::where('blockchain_status', 'pending')->count(),
            'confirmed'        => Certificate::where('blockchain_status', 'confirmed')->count(),
            'failed'           => Certificate::where('blockchain_status', 'failed')->count(),
        ];

        // Recent blockchain transactions
        $recentBlockchainTx = Certificate::whereNotNull('blockchain_tx_hash')
            ->latest()
            ->take(10)
            ->get();

        return view('master.blockchain', compact('walletInfo', 'blockchainStats', 'recentBlockchainTx'));
    }
}
