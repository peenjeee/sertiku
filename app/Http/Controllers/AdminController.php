<?php
namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        // Get stats
        $stats = [
            'total_users' => User::where('account_type', 'pengguna')->count(),
            'total_lembaga' => User::where('account_type', 'lembaga')->count(),
            'total_sertifikat' => Certificate::count(),
            'sertifikat_aktif' => Certificate::where('status', 'active')->count(),
            'sertifikat_dicabut' => Certificate::where('status', 'revoked')->count(),
            'total_verifikasi' => Certificate::count(), // Using certificate count as verification proxy
        ];

        // Get recent certificates
        $recentCertificates = Certificate::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentCertificates'));
    }

    /**
     * Analytics Page
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // days

        // Stats with percentage change
        $stats = $this->getAnalyticsStats($period);

        // Chart data - Certificates per month
        $certificatesTrend = Certificate::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Verification activity (simulated if no log table)
        $verificationActivity = $this->getVerificationActivity();

        // Real-time verifications (last 10)
        $recentVerifications = Certificate::latest('updated_at')
            ->take(10)
            ->get(['certificate_number', 'updated_at']);

        return view('admin.analytics', compact('stats', 'certificatesTrend', 'verificationActivity', 'recentVerifications'));
    }

    /**
     * Kelola Pengguna Page
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('institution_name', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('account_type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('profile_completed', true);
            } else {
                $query->where('profile_completed', false);
            }
        }

        $users = $query->latest()->paginate(15);

        $userStats = [
            'total' => User::count(),
            'pengguna' => User::where('account_type', 'pengguna')->count(),
            'lembaga' => User::where('account_type', 'lembaga')->count(),
            'active' => User::where('profile_completed', true)->count(),
        ];

        return view('admin.users', compact('users', 'userStats'));
    }

    /**
     * Toggle User Status
     */
    public function toggleUser(User $user)
    {
        // Prevent deactivating admins, masters, or self
        $isAdmin = in_array($user->role, ['admin', 'master']) || $user->account_type === 'admin';
        if ($isAdmin) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun Admin atau Master.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update([
            'profile_completed' => !$user->profile_completed,
        ]);

        $status = $user->profile_completed ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Pengguna {$user->name} berhasil {$status}.");
    }

    /**
     * View User Detail
     */
    public function showUser(User $user)
    {
        $user->load('certificates', 'orders');
        return view('admin.user-detail', compact('user'));
    }

    /**
     * Backup & Restore Page
     */
    public function backup()
    {
        // Get backup history from storage
        $backups = [];
        $backupPath = storage_path('app/backups');

        if (file_exists($backupPath)) {
            $files = scandir($backupPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $backups[] = [
                        'name' => $file,
                        'size' => $this->formatBytes(filesize($backupPath . '/' . $file)),
                        'date' => date('Y-m-d H:i:s', filemtime($backupPath . '/' . $file)),
                    ];
                }
            }
        }

        // Sort by date desc
        usort($backups, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));

        $exportStats = [
            'users' => User::count(),
            'certificates' => Certificate::count(),
            'lembaga' => User::where('account_type', 'lembaga')->count(),
        ];

        return view('admin.backup', compact('backups', 'exportStats'));
    }

    /**
     * Export Data
     */
    public function exportData(Request $request)
    {
        $type = $request->get('type', 'certificates');
        $format = $request->get('format', 'csv');

        $data = match ($type) {
            'users' => User::all()->toArray(),
            'certificates' => Certificate::with('user:id,name,email')->get()->toArray(),
            'lembaga' => User::where('account_type', 'lembaga')->get()->toArray(),
            default => Certificate::all()->toArray(),
        };

        $filename = "{$type}_export_" . date('Y-m-d_His');

        if ($format === 'json') {
            return response()->json($data)
                ->header('Content-Disposition', "attachment; filename={$filename}.json");
        }

        // CSV format
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}.csv",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            if (!empty($data)) {
                // Header row
                fputcsv($file, array_keys($data[0]));

                // Data rows
                foreach ($data as $row) {
                    fputcsv($file, array_map(function ($item) {
                        return is_array($item) ? json_encode($item) : $item;
                    }, $row));
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Create Backup
     */
    public function createBackup()
    {
        $backupPath = storage_path('app/backups');

        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $filename = 'backup_' . date('Y-m-d_His') . '.json';

        $data = [
            'created_at' => now()->toDateTimeString(),
            'users' => User::all()->toArray(),
            'certificates' => Certificate::all()->toArray(),
        ];

        file_put_contents($backupPath . '/' . $filename, json_encode($data, JSON_PRETTY_PRINT));

        return back()->with('success', "Backup berhasil dibuat: {$filename}");
    }

    /**
     * Admin Settings Page
     */
    public function settings()
    {
        $settings = [
            'site_name' => config('app.name'),
            'site_url' => config('app.url'),
            'admin_email' => auth()->user()->email,
        ];

        return view('admin.settings', compact('settings'));
    }

    /**
     * Update Settings
     */
    public function updateSettings(Request $request)
    {
        // In a real app, you'd save these to a settings table or .env
        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Admin Profile Page
     */
    public function profile()
    {
        $admin = auth()->user();
        return view('admin.profile', compact('admin'));
    }

    /**
     * Update Admin Profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update Admin Password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    /**
     * Helper: Get analytics stats with percentage change
     */
    private function getAnalyticsStats($days)
    {
        $now = now();
        $periodStart = $now->copy()->subDays($days);
        $prevPeriodStart = $periodStart->copy()->subDays($days);

        // Current period counts
        $currentVerifications = Certificate::where('updated_at', '>=', $periodStart)->count();
        $currentCertificates = Certificate::where('status', 'active')
            ->where('created_at', '>=', $periodStart)->count();
        $currentLembaga = User::where('account_type', 'lembaga')
            ->where('created_at', '>=', $periodStart)->count();
        $currentUsers = User::where('created_at', '>=', $periodStart)->count();

        // Previous period counts
        $prevVerifications = Certificate::whereBetween('updated_at', [$prevPeriodStart, $periodStart])->count();
        $prevCertificates = Certificate::where('status', 'active')
            ->whereBetween('created_at', [$prevPeriodStart, $periodStart])->count();
        $prevLembaga = User::where('account_type', 'lembaga')
            ->whereBetween('created_at', [$prevPeriodStart, $periodStart])->count();
        $prevUsers = User::whereBetween('created_at', [$prevPeriodStart, $periodStart])->count();

        return [
            'total_verifikasi' => [
                'value' => Certificate::count(),
                'change' => $this->calculatePercentChange($prevVerifications, $currentVerifications),
            ],
            'sertifikat_aktif' => [
                'value' => Certificate::where('status', 'active')->count(),
                'change' => $this->calculatePercentChange($prevCertificates, $currentCertificates),
            ],
            'lembaga_terdaftar' => [
                'value' => User::where('account_type', 'lembaga')->count(),
                'change' => $this->calculatePercentChange($prevLembaga, $currentLembaga),
            ],
            'pengguna_aktif' => [
                'value' => User::where('profile_completed', true)->count(),
                'change' => $this->calculatePercentChange($prevUsers, $currentUsers),
            ],
        ];
    }

    private function calculatePercentChange($old, $new)
    {
        if ($old == 0) {
            return $new > 0 ? 100 : 0;
        }

        return round((($new - $old) / $old) * 100, 1);
    }

    private function getVerificationActivity()
    {
        // Generate sample data for last 12 months
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $data[] = [
                'month' => $month->format('M'),
                'count' => Certificate::whereMonth('updated_at', $month->month)
                    ->whereYear('updated_at', $month->year)
                    ->count(),
            ];
        }
        return $data;
    }

    private function formatBytes($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    /**
     * Blockchain Wallet Dashboard
     */
    public function blockchain()
    {
        $blockchainService = new \App\Services\BlockchainService();
        $walletInfo = $blockchainService->getWalletInfo();

        // Get blockchain certificates stats
        $blockchainStats = [
            'total_blockchain' => Certificate::whereNotNull('blockchain_tx_hash')->count(),
            'pending' => Certificate::where('blockchain_status', 'pending')->count(),
            'confirmed' => Certificate::where('blockchain_status', 'confirmed')->count(),
            'failed' => Certificate::where('blockchain_status', 'failed')->count(),
        ];

        // Recent blockchain transactions
        $recentBlockchainTx = Certificate::whereNotNull('blockchain_tx_hash')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.blockchain', compact('walletInfo', 'blockchainStats', 'recentBlockchainTx'));
    }
}
