<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
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
            'total_users' => User::count(),
            'total_admins' => User::where('is_admin', true)->count(),
            'total_masters' => User::where('is_master', true)->count(),
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
        $admins = User::where('is_admin', true)->orderBy('is_master', 'desc')->get();

        return view('master.dashboard', compact('stats', 'recentUsers', 'recentOrders', 'admins'));
    }

    /**
     * Manage admins
     */
    public function manageAdmins()
    {
        $admins = User::where('is_admin', true)->orderBy('is_master', 'desc')->get();
        $users = User::where('is_admin', false)->get();

        return view('master.admins', compact('admins', 'users'));
    }

    /**
     * Promote user to admin
     */
    public function promoteToAdmin(Request $request, User $user)
    {
        if (!auth()->user()->is_master) {
            abort(403);
        }

        $user->is_admin = true;
        $user->account_type = 'admin';
        $user->save();

        // Log activity
        ActivityLog::log(
            'promote_admin',
            'User dipromosikan menjadi Admin: ' . $user->name,
            $user
        );

        return back()->with('success', "User {$user->name} berhasil dijadikan Admin.");
    }

    /**
     * Demote admin to user
     */
    public function demoteAdmin(Request $request, User $user)
    {
        if (!auth()->user()->is_master) {
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

        $user->is_admin = false;
        $user->account_type = 'pengguna';
        $user->save();

        // Log activity
        ActivityLog::log(
            'demote_admin',
            'Admin diturunkan ke User: ' . $user->name,
            $user
        );

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
     * Activity logs
     */
    public function logs(Request $request)
    {
        $query = \App\Models\ActivityLog::with('user')
            ->latest();

        // Filter by action
        if ($request->action) {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->paginate(25);

        // Get unique actions for filter dropdown
        $actions = \App\Models\ActivityLog::select('action')
            ->distinct()
            ->pluck('action');

        return view('master.logs', compact('logs', 'actions'));
    }

    /**
     * Blockchain Wallet Dashboard
     */
    public function blockchain()
    {
        $blockchainService = new \App\Services\BlockchainService();
        $walletInfo = $blockchainService->getWalletInfo();

        // Get actual on-chain count from smart contract
        $contractStats = $blockchainService->getContractStats();

        // Get blockchain certificates stats
        $blockchainStats = [
            'total_blockchain' => $contractStats['totalCertificates'] ?? 0, // Use smart contract count
            'pending' => Certificate::where('blockchain_status', 'pending')->count(),
            'confirmed' => Certificate::where('blockchain_status', 'confirmed')->count(),
            'failed' => Certificate::where('blockchain_status', 'failed')->count(),
        ];

        // Recent blockchain transactions
        $recentBlockchainTx = Certificate::whereNotNull('blockchain_tx_hash')
            ->latest()
            ->take(10)
            ->get();

        return view('master.blockchain', compact('walletInfo', 'blockchainStats', 'recentBlockchainTx'));
    }
}
