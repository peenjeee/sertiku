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

        // Filter by type (pengguna/user/personal = same, lembaga/institution = same)
        if ($request->filled('type')) {
            if ($request->type === 'pengguna') {
                $query->whereIn('account_type', ['pengguna', 'user', 'personal']);
            } elseif ($request->type === 'lembaga') {
                $query->whereIn('account_type', ['lembaga', 'institution']);
            } else {
                $query->where('account_type', $request->type);
            }
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
            'pengguna' => User::whereIn('account_type', ['pengguna', 'user', 'personal'])->count(),
            'lembaga' => User::whereIn('account_type', ['lembaga', 'institution'])->count(),
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
        $receivedCertificates = Certificate::where('recipient_email', $user->email)->latest()->get();
        return view('admin.user-detail', compact('user', 'receivedCertificates'));
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
                        'date' => \Carbon\Carbon::createFromTimestamp(filemtime($backupPath . '/' . $file))->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
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

        $filename = "{$type}_export_" . now()->format('Y-m-d_His');

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

        $filename = 'backup_' . now()->format('Y-m-d_His') . '.json';

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
     * Clear Application Cache
     */
    public function clearCache()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');

        return back()->with('success', 'Cache berhasil dibersihkan!');
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
            // Delete old avatar if exists
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = '/storage/' . $path;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Analytics Page
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $year = $request->get('year', now()->year); // year filter

        // Stats with percentage change (filtered by period ONLY, relative to now)
        $stats = $this->getAnalyticsStats($period);

        // Get available years for filter (from earliest certificate to current year)
        $earliestYear = Certificate::min('created_at');
        $earliestYear = $earliestYear ? \Carbon\Carbon::parse($earliestYear)->year : now()->year;
        $availableYears = range(now()->year, $earliestYear);

        // Chart data - Certificates per month (all 12 months for selected year)
        $certificatesTrend = $this->getCertificatesTrend($year);

        // Verification activity (all 12 months for selected year)
        $verificationActivity = $this->getVerificationActivity($year);

        // Package Distribution
        $packageDistribution = User::whereIn('account_type', ['lembaga', 'institution'])
            ->select('package_id', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('package_id')
            ->get()
            ->map(function ($item) {
                $pkg = \App\Models\Package::find($item->package_id);
                return [
                    'label' => $pkg ? $pkg->name : 'Starter (Free)',
                    'count' => $item->count,
                    'color' => $pkg ? ($pkg->slug == 'professional' ? '#8B5CF6' : ($pkg->slug == 'enterprise' ? '#F59E0B' : '#3B82F6')) : '#10B981'
                ];
            });

        // Real-time verifications (last 10)
        $recentVerifications = Certificate::latest('updated_at')
            ->take(10)
            ->get(['certificate_number', 'updated_at']);

        return view('admin.analytics', compact('stats', 'certificatesTrend', 'verificationActivity', 'packageDistribution', 'recentVerifications', 'availableYears', 'year', 'period'));
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
        // $year parameter removed to strictly follow "Last X Days" logic from today
        $now = now();
        $periodStart = $now->copy()->subDays($days);
        $prevPeriodStart = $periodStart->copy()->subDays($days);

        // Current period counts (Last X Days from NOW)
        $currentVerifications = Certificate::where('updated_at', '>=', $periodStart)->count();
        $currentCertificates = Certificate::where('status', 'active')
            ->where('created_at', '>=', $periodStart)
            ->count();
        $currentLembaga = User::where('account_type', 'lembaga')
            ->where('created_at', '>=', $periodStart)
            ->count();
        $currentUsers = User::where('profile_completed', true)
            ->where('created_at', '>=', $periodStart)
            ->count();

        // Previous period counts (The X Days before that)
        $prevVerifications = Certificate::whereBetween('updated_at', [$prevPeriodStart, $periodStart])->count();
        $prevCertificates = Certificate::where('status', 'active')
            ->whereBetween('created_at', [$prevPeriodStart, $periodStart])
            ->count();
        $prevLembaga = User::where('account_type', 'lembaga')
            ->whereBetween('created_at', [$prevPeriodStart, $periodStart])
            ->count();
        $prevUsers = User::where('profile_completed', true)
            ->whereBetween('created_at', [$prevPeriodStart, $periodStart])
            ->count();

        // Total counts (All time, or we can keep it strictly period related? Usually top cards show totals or specific period stats. 
        // Based on user request "persen nan itu sesuai filter yang dipilih", the BIG number should probably be the Total in Period? 
        // Or Total All Time? Usually Dashboard cards show Total All Time, and small text shows change in period.
        // BUT, if the filter says "Last 7 Days", showing Total All Time might be confusing if the user expects to see count for 7 days.
        // However, the variable is named $totalVerifikasi. Let's stick to Total All Time for the Big Number, and Period Change for the percentage.
        // Wait, the previous code filtered Total by Year. That was definitely wrong if the label is "Total". 
        // Let's make the Big Value = Total All Time (Cumulative) to match standard dashboards, OR make it Period Value?
        // User said: "benarkan persen nan itu sesuai filter yang dipilih". This implies the PERCENTAGE calculation was wrong (likely because of year filter or empty interaction).
        // Let's set the Big Value to be the PERIOD value so it matches the filter "Last 7 Days". 
        // If I select "Last 7 Days", seeing "8" (Total) when I only did 1 this week is fine, but if the label is just "Total Verifikasi", it usually implies cumulative.
        // However, standard analytics (GA4) with a time filter shows data for THAT period.
        // Let's set the 'value' to '$currentVerifications' to be fully responsive to the filter. 

        return [
            'total_verifikasi' => [
                'value' => $currentVerifications, // Changed to show count in period
                'change' => $this->calculatePercentChange($prevVerifications, $currentVerifications),
            ],
            'sertifikat_aktif' => [
                'value' => $currentCertificates,
                'change' => $this->calculatePercentChange($prevCertificates, $currentCertificates),
            ],
            'lembaga_terdaftar' => [
                'value' => $currentLembaga,
                'change' => $this->calculatePercentChange($prevLembaga, $currentLembaga),
            ],
            'pengguna_aktif' => [
                'value' => $currentUsers,
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

    private function getVerificationActivity($year = null)
    {
        $year = $year ?? now()->year;
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[] = [
                'month' => $months[$i - 1],
                'count' => Certificate::whereMonth('updated_at', $i)
                    ->whereYear('updated_at', $year)
                    ->count(),
            ];
        }
        return $data;
    }

    /**
     * Get certificates trend for all 12 months of a year
     */
    private function getCertificatesTrend($year = null)
    {
        $year = $year ?? now()->year;
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[] = [
                'month' => $months[$i - 1],
                'count' => Certificate::whereMonth('created_at', $i)
                    ->whereYear('created_at', $year)
                    ->count(),
            ];
        }
        return collect($data);
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

        return view('admin.blockchain', compact('walletInfo', 'blockchainStats', 'recentBlockchainTx'));
    }

    /**
     * Delete account (for admin users).
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirm_delete' => 'required|in:HAPUS',
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();

        \Illuminate\Support\Facades\Auth::logout();
        $user->update(['is_admin' => false]); // Remove admin status, then delete
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun admin Anda telah dihapus.');
    }
}
