<?php
namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Order;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        // Get stats
        $stats = [
            'total_verifikasi' => \App\Models\ActivityLog::where('action', 'verify_certificate')->count(),
            'verifikasi_hari_ini' => \App\Models\ActivityLog::where('action', 'verify_certificate')
                ->whereDate('created_at', now())
                ->count(),
            'total_verifikasi_blockchain' => \App\Models\ActivityLog::where('action', 'blockchain_verification')->count(),
            'verifikasi_blockchain_hari_ini' => \App\Models\ActivityLog::where('action', 'blockchain_verification')
                ->whereDate('created_at', now())
                ->count(),
            'total_revenue' => Order::where('status', 'paid')->sum('amount'),
            'pendapatan_bulan_ini' => Order::where('status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
            'total_sertifikat' => Certificate::count(),
            'sertifikat_aktif' => Certificate::where('status', '!=', 'revoked')
                ->where(function ($q) {
                    $q->whereNull('expire_date')
                        ->orWhereDate('expire_date', '>=', now());
                })->count(),
            'sertifikat_dicabut' => Certificate::where('status', 'revoked')->count(),
            'sertifikat_kedaluarsa' => Certificate::whereDate('expire_date', '<', now())->count(),
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
            'admin' => User::where('account_type', 'admin')->count(),
            'pengguna' => User::whereIn('account_type', ['pengguna', 'user', 'personal'])->count(),
            'lembaga' => User::whereIn('account_type', ['lembaga', 'institution'])->count(),
            'active' => User::where('profile_completed', true)->count(),
            'inactive' => User::where('profile_completed', false)->count(),
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
        // Default to last 30 days if no dates provided
        $endDate = $request->get('end_date') ? \Carbon\Carbon::parse($request->get('end_date'))->endOfDay() : now()->endOfDay();
        $startDate = $request->get('start_date') ? \Carbon\Carbon::parse($request->get('start_date'))->startOfDay() : now()->subDays(29)->startOfDay();

        // Validated dates for view
        $startDateStr = $startDate->format('Y-m-d');
        $endDateStr = $endDate->format('Y-m-d');

        // Stats with percentage change based on custom range
        $stats = $this->getAnalyticsStats($startDate, $endDate);

        // Chart data - Dynamic aggregation based on range duration
        $certificatesTrend = $this->getCertificatesTrend($startDate, $endDate);
        $verificationActivity = $this->getVerificationActivity($startDate, $endDate);
        $revenueTrend = $this->getRevenueTrend($startDate, $endDate);

        // Package Distribution (Ensure all packages are present)
        $packageCounts = User::whereIn('account_type', ['lembaga', 'institution'])
            ->select('package_id', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('package_id')
            ->pluck('count', 'package_id');

        $allPackages = \App\Models\Package::all();
        // Add fake "Starter" package (users with null package_id)
        $starterCount = User::whereIn('account_type', ['lembaga', 'institution'])->whereNull('package_id')->count();

        $packageDistribution = collect([
            [
                'label' => 'Starter (Free)',
                'count' => $starterCount,
                'color' => '#10B981'
            ]
        ]);

        foreach ($allPackages as $pkg) {
            // Skip "Normal" package as it's equivalent to Starter/Free
            if (strtolower($pkg->name) === 'normal') {
                continue;
            }

            $color = match ($pkg->slug) {
                'professional' => '#8B5CF6',
                'enterprise' => '#F59E0B',
                default => '#3B82F6'
            };

            $packageDistribution->push([
                'label' => $pkg->name,
                'count' => $packageCounts->get($pkg->id, 0),
                'color' => $color
            ]);
        }

        // Real-time verifications (last 10)
        // Fetch from ActivityLog to capture actual verification events, not just updates
        $recentVerifications = \App\Models\ActivityLog::with('subject')
            ->whereIn('action', ['verify_certificate', 'blockchain_verification'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($log) {
                // If standard verification and subject is missing, try to find cert by number in description
                if ($log->action === 'verify_certificate' && !$log->subject) {
                    if (preg_match('/Sertifikat\s+(\S+)\s+diverifikasi/i', $log->description, $matches)) {
                        $certNumber = $matches[1];
                        $cert = \App\Models\Certificate::where('certificate_number', $certNumber)->first();
                        if ($cert) {
                            $log->setRelation('subject', $cert);
                        }
                    }
                }
                return $log;
            });

        return view('admin.analytics', compact(
            'stats',
            'certificatesTrend',
            'verificationActivity',
            'revenueTrend',
            'packageDistribution',
            'recentVerifications',
            'startDateStr',
            'endDateStr'
        ));
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
    private function getAnalyticsStats($startDate, $endDate)
    {
        $durationInDays = $startDate->diffInDays($endDate) + 1;
        // Previous period ends just before current start
        $prevPeriodEnd = $startDate->copy()->subSecond();
        $prevPeriodStart = $startDate->copy()->subDays($durationInDays);

        // --- Current Period Counts ---

        // 1. Total Verifikasi (Web/Upload) in Period (Action: verify_certificate)
        $currentVerifikasi = \App\Models\ActivityLog::where('action', 'verify_certificate')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // 2. Verifikasi Hari Ini (Relative to Real-time Today aka "Live")
        $todayVerifikasi = \App\Models\ActivityLog::where('action', 'verify_certificate')
            ->whereDate('created_at', today())
            ->count();
        $yesterdayVerifikasi = \App\Models\ActivityLog::where('action', 'verify_certificate')
            ->whereDate('created_at', today()->subDay())
            ->count();

        // 3. Total Verifikasi Blockchain in Period
        $currentVerifikasiBC = \App\Models\ActivityLog::where('action', 'blockchain_verification')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // 4. Verifikasi Blockchain Hari Ini (Live)
        $todayVerifikasiBC = \App\Models\ActivityLog::where('action', 'blockchain_verification')
            ->whereDate('created_at', today())
            ->count();
        $yesterdayVerifikasiBC = \App\Models\ActivityLog::where('action', 'blockchain_verification')
            ->whereDate('created_at', today()->subDay())
            ->count();

        // --- Previous Period Counts (for % change of Period stats) ---
        $prevVerifikasi = \App\Models\ActivityLog::where('action', 'verify_certificate')
            ->whereBetween('created_at', [$prevPeriodStart, $prevPeriodEnd])
            ->count();

        $prevVerifikasiBC = \App\Models\ActivityLog::where('action', 'blockchain_verification')
            ->whereBetween('created_at', [$prevPeriodStart, $prevPeriodEnd])
            ->count();

        return [
            'total_verifikasi' => [
                'value' => $currentVerifikasi,
                'change' => $this->calculatePercentChange($prevVerifikasi, $currentVerifikasi),
            ],
            'verifikasi_hari_ini' => [
                'value' => $todayVerifikasi,
                'change' => $this->calculatePercentChange($yesterdayVerifikasi, $todayVerifikasi),
            ],
            'total_verifikasi_blockchain' => [
                'value' => $currentVerifikasiBC,
                'change' => $this->calculatePercentChange($prevVerifikasiBC, $currentVerifikasiBC),
            ],
            'verifikasi_blockchain_hari_ini' => [
                'value' => $todayVerifikasiBC,
                'change' => $this->calculatePercentChange($yesterdayVerifikasiBC, $todayVerifikasiBC),
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

    /**
     * Get Revenue Trend (Dynamic consolidation)
     */
    private function getRevenueTrend($startDate, $endDate)
    {
        $diffDays = $startDate->diffInDays($endDate);
        $isDaily = $diffDays <= 60;
        // Format for DB Grouping
        $dbFormat = $isDaily ? '%Y-%m-%d' : '%Y-%m';
        // Format for PHP key matching
        $keyFormat = $isDaily ? 'Y-m-d' : 'Y-m';

        $query = Order::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(created_at, '{$dbFormat}') as date, sum(amount) as aggregate")
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('aggregate', 'date');

        $data = [];
        $period = \Carbon\CarbonPeriod::create($startDate, $isDaily ? '1 day' : '1 month', $endDate);

        foreach ($period as $dt) {
            $key = $dt->format($keyFormat);
            $data[] = [
                'month' => $isDaily ? $dt->format('d M') : $dt->format('M Y'),
                'count' => (float) ($query[$key] ?? 0),
            ];
        }
        return collect($data);
    }

    /**
     * Get Certificates Trend (Dynamic consolidation)
     */
    private function getCertificatesTrend($startDate, $endDate)
    {
        $diffDays = $startDate->diffInDays($endDate);
        $isDaily = $diffDays <= 60;
        $dbFormat = $isDaily ? '%Y-%m-%d' : '%Y-%m';
        $keyFormat = $isDaily ? 'Y-m-d' : 'Y-m';

        $query = Certificate::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(created_at, '{$dbFormat}') as date, count(*) as aggregate")
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('aggregate', 'date');

        $data = [];
        $period = \Carbon\CarbonPeriod::create($startDate, $isDaily ? '1 day' : '1 month', $endDate);

        foreach ($period as $dt) {
            $key = $dt->format($keyFormat);
            $data[] = [
                'month' => $isDaily ? $dt->format('d M') : $dt->format('M Y'),
                'count' => (int) ($query[$key] ?? 0),
            ];
        }
        return collect($data);
    }

    /**
     * Get Verification Activity (Dynamic consolidation)
     */
    private function getVerificationActivity($startDate, $endDate)
    {
        $diffDays = $startDate->diffInDays($endDate);
        $isDaily = $diffDays <= 60;
        $dbFormat = $isDaily ? '%Y-%m-%d' : '%Y-%m';
        $keyFormat = $isDaily ? 'Y-m-d' : 'Y-m';

        $verifyQuery = \App\Models\ActivityLog::where('action', 'verify_certificate')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(created_at, '{$dbFormat}') as date, count(*) as aggregate")
            ->groupBy('date')
            ->pluck('aggregate', 'date');

        $bcQuery = \App\Models\ActivityLog::where('action', 'blockchain_verification')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(created_at, '{$dbFormat}') as date, count(*) as aggregate")
            ->groupBy('date')
            ->pluck('aggregate', 'date');

        $data = [];
        $period = \Carbon\CarbonPeriod::create($startDate, $isDaily ? '1 day' : '1 month', $endDate);

        foreach ($period as $dt) {
            $key = $dt->format($keyFormat);
            $data[] = [
                'month' => $isDaily ? $dt->format('d M') : $dt->format('M Y'),
                'standard' => (int) ($verifyQuery[$key] ?? 0),
                'blockchain' => (int) ($bcQuery[$key] ?? 0),
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
