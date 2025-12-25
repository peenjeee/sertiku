<?php
namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * User Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Get user's certificates (received from institutions)
        $certificates = Certificate::where('recipient_email', $user->email)
            ->orWhere('user_id', $user->id)
            ->get();

        $stats = [
            'total_sertifikat' => $certificates->count(),
            'terverifikasi' => $certificates->where('status', 'active')->count(),
            'total_verifikasi' => $certificates->sum('verification_count') ?? 0,
            'pending' => $certificates->where('status', 'pending')->count(),
        ];

        // Chart data - certificates by month
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = $certificates->filter(function ($cert) use ($month) {
                return $cert->created_at && $cert->created_at->format('Y-m') === $month->format('Y-m');
            })->count();
            $chartData[] = [
                'month' => $month->format('M'),
                'count' => $count,
            ];
        }

        // Recent activity from database notifications
        $recentActivity = $user->notifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($notification) {
                $data = $notification->data;
                return [
                    'type' => $data['type'] ?? 'general',
                    'title' => $data['title'] ?? 'Notifikasi',
                    'subtitle' => $data['subtitle'] ?? '',
                    'time' => $notification->created_at->diffForHumans(),
                    'read' => $notification->read_at !== null,
                ];
            })->toArray();

        // Recent certificates
        $recentCertificates = $certificates->sortByDesc('created_at')->take(3);

        return view('user.dashboard', compact('stats', 'chartData', 'recentActivity', 'recentCertificates'));
    }

    /**
     * Sertifikat Saya
     */
    public function sertifikat(Request $request)
    {
        $user = Auth::user();

        $query = Certificate::where('recipient_email', $user->email)
            ->orWhere('user_id', $user->id);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('certificate_id', 'like', "%{$search}%")
                    ->orWhere('hash', 'like', "%{$search}%");
            });
        }

        $certificates = $query->latest()->paginate(10);

        $stats = [
            'total' => Certificate::where('recipient_email', $user->email)->orWhere('user_id', $user->id)->count(),
            'aktif' => Certificate::where(function ($q) use ($user) {
                $q->where('recipient_email', $user->email)->orWhere('user_id', $user->id);
            })->where('status', 'active')->count(),
            'dicabut' => Certificate::where(function ($q) use ($user) {
                $q->where('recipient_email', $user->email)->orWhere('user_id', $user->id);
            })->where('status', 'revoked')->count(),
        ];

        return view('user.sertifikat', compact('certificates', 'stats'));
    }

    /**
     * Profil Pengguna - View Profile with Achievements
     */
    public function profil()
    {
        $user = Auth::user();

        // Get user's certificates
        $certificates = Certificate::where('recipient_email', $user->email)
            ->orWhere('user_id', $user->id)
            ->get();

        // Stats
        $stats = [
            'total_sertifikat' => $certificates->count(),
            'terverifikasi' => $certificates->where('status', 'active')->count(),
            'total_lembaga' => $certificates->pluck('user_id')->unique()->count(),
            'total_kategori' => $certificates->pluck('category')->filter()->unique()->count(),
        ];

        // Recent certificates
        $recentCertificates = $certificates->sortByDesc('created_at')->take(3);

        // Achievements/Badges
        $achievements = $this->calculateAchievements($certificates);

        return view('user.profil', compact('user', 'stats', 'recentCertificates', 'achievements'));
    }

    /**
     * Edit Profil Page
     */
    public function editProfil()
    {
        $user = Auth::user();
        return view('user.edit-profil', compact('user'));
    }

    /**
     * Calculate user achievements based on certificates
     */
    private function calculateAchievements($certificates)
    {
        $totalCerts = $certificates->count();
        $activeCerts = $certificates->where('status', 'active')->count();
        $categories = $certificates->pluck('category')->filter()->unique()->count();
        $lembaga = $certificates->pluck('user_id')->unique()->count();

        return [
            [
                'name' => 'Pemula',
                'description' => 'Dapatkan sertifikat pertama',
                'icon' => '🎯',
                'unlocked' => $totalCerts >= 1,
            ],
            [
                'name' => 'Kolektor',
                'description' => 'Kumpulkan 5 sertifikat',
                'icon' => '📚',
                'unlocked' => $totalCerts >= 5,
            ],
            [
                'name' => 'Master',
                'description' => 'Kumpulkan 10 sertifikat',
                'icon' => '🏆',
                'unlocked' => $totalCerts >= 10,
            ],
            [
                'name' => 'Terverifikasi',
                'description' => '3 sertifikat aktif',
                'icon' => '✅',
                'unlocked' => $activeCerts >= 3,
            ],
            [
                'name' => 'Multitalenta',
                'description' => '3 kategori berbeda',
                'icon' => '🌟',
                'unlocked' => $categories >= 3,
            ],
            [
                'name' => 'Networker',
                'description' => 'Dari 3 lembaga berbeda',
                'icon' => '🤝',
                'unlocked' => $lembaga >= 3,
            ],
            [
                'name' => 'Legend',
                'description' => 'Kumpulkan 25 sertifikat',
                'icon' => '👑',
                'unlocked' => $totalCerts >= 25,
            ],
            [
                'name' => 'Elite',
                'description' => 'Kumpulkan 50 sertifikat',
                'icon' => '💎',
                'unlocked' => $totalCerts >= 50,
            ],
        ];
    }

    /**
     * Update Profil
     */
    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'occupation' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
        ]);

        $user->update($validated);

        return redirect()->route('user.profil.edit')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('password_success', 'Password berhasil diperbarui!');
    }

    /**
     * Upload Avatar
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,gif|max:2048',
        ]);

        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->avatar) {
            $oldPath = str_replace('/storage/', '', $user->avatar);
            \Storage::disk('public')->delete($oldPath);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update(['avatar' => '/storage/' . $path]);

        return redirect()->route('user.profil.edit')->with('avatar_success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Remove Avatar
     */
    public function removeAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            $path = str_replace('/storage/', '', $user->avatar);
            \Storage::disk('public')->delete($path);
            $user->update(['avatar' => null]);
        }

        return redirect()->route('user.profil.edit')->with('avatar_success', 'Foto profil berhasil dihapus!');
    }

    /**
     * Notifikasi
     */
    public function notifikasi()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(20);

        return view('user.notifikasi', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
