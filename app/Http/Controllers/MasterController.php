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
            'total_accounts' => User::count(),
            'total_admins' => User::where('is_admin', true)->count(),
            'total_masters' => User::where('is_master', true)->count(),
            'total_lembaga' => User::whereIn('account_type', ['lembaga', 'institution'])->count(),
            'total_pengguna' => User::whereIn('account_type', ['pengguna', 'personal'])->count(),
            'total_certificates' => Certificate::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'paid')->sum('amount') ?? 0,
            'pendapatan_bulan_ini' => Order::where('status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount') ?? 0,
        ];

        // Get total blockchain transactions from wallet
        $blockchainService = new \App\Services\BlockchainService();
        $walletInfo = $blockchainService->getWalletInfo();
        // Fallback to database count if API fails
        $stats['total_blockchain_transactions'] = $walletInfo['transaction_count'] ?? Certificate::whereNotNull('blockchain_tx_hash')->count();

        // Recent activities
        $recentUsers = User::latest()->take(5)->get();
        $recentOrders = Order::with('user', 'package')->latest()->take(5)->get();

        // Chart data - certificates by month (Jan to Dec current year)
        $chartData = [];
        $currentYear = now()->year;
        for ($i = 1; $i <= 12; $i++) {
            $month = \Carbon\Carbon::create($currentYear, $i, 1);
            $count = Certificate::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->count();
            $chartData[] = [
                'month' => $month->format('M'),
                'count' => $count,
            ];
        }

        // Account type chart data (wallet, google, normal)
        $accountTypeData = [
            ['type' => 'Wallet', 'count' => User::whereNotNull('wallet_address')->count()],
            ['type' => 'Google', 'count' => User::whereNotNull('google_id')->count()],
            ['type' => 'Normal', 'count' => User::whereNull('wallet_address')->whereNull('google_id')->count()],
        ];

        // Payment status chart data
        $paymentStatusData = [
            ['status' => 'Pending', 'count' => Order::where('status', 'pending')->count()],
            ['status' => 'Paid', 'count' => Order::where('status', 'paid')->count()],
            ['status' => 'Failed', 'count' => Order::where('status', 'failed')->count()],
            ['status' => 'Expired', 'count' => Order::where('status', 'expired')->count()],
            ['status' => 'Cancelled', 'count' => Order::where('status', 'cancelled')->count()],
        ];

        // All admins list
        $admins = User::where('is_admin', true)->orderBy('is_master', 'desc')->get();

        return view('master.dashboard', compact('stats', 'recentUsers', 'recentOrders', 'admins', 'chartData', 'accountTypeData', 'paymentStatusData'));
    }

    /**
     * Manage admins
     */
    public function manageAdmins(Request $request)
    {
        $search = $request->input('search');

        $admins = User::where('is_admin', true)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('is_master', 'desc')
            ->get();

        $users = User::where('is_admin', false)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->get();

        return view('master.admins', compact('admins', 'users', 'search'));
    }

    /**
     * Promote user to admin
     */
    public function promoteToAdmin(Request $request, User $user)
    {
        if (!auth()->user()->is_master) {
            abort(403);
        }

        // Save original account type before promoting
        $user->original_account_type = $user->account_type;
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
        // Restore original account type or default to 'pengguna'
        $user->account_type = $user->original_account_type ?? 'pengguna';
        $user->original_account_type = null;
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

        // Filter by date range
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
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
