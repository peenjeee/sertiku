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

        $currentMonthCount = $certificates->where('created_at', '>=', now()->startOfMonth())->count();

        $stats = [
            'total_sertifikat' => $certificates->count(),
            'growth_sertifikat' => $currentMonthCount,
            'terverifikasi' => $certificates->where('status', 'active')->count(),
            'total_verifikasi' => $certificates->sum('verification_count') ?? 0,
            'pending' => $certificates->where('status', 'pending')->count(),
        ];

        // Chart data - Jan to Dec current year
        $chartData = [];
        $currentYear = now()->year;
        for ($i = 1; $i <= 12; $i++) {
            $month = \Carbon\Carbon::create($currentYear, $i, 1);
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

        $query = Certificate::where(function ($q) use ($user) {
            $q->where('recipient_email', $user->email)
                ->orWhere('user_id', $user->id);
        });

        // Search
        if ($request->filled('search') || $request->filled('id')) {
            $query->where(function ($q) use ($request) {
                if ($request->filled('search')) {
                    $search = $request->search;
                    $q->where('course_name', 'like', "%{$search}%")
                        ->orWhere('certificate_number', 'like', "%{$search}%")
                        ->orWhere('hash', 'like', "%{$search}%");
                }

                if ($request->filled('id')) {
                    $id = $request->id;
                    $q->orWhere('certificate_number', 'like', "%{$id}%")
                        ->orWhere('hash', 'like', "%{$id}%");
                }
            });
        }

        $certificates = $query->latest()->paginate(10);

        $stats = [
            'total' => Certificate::where('recipient_email', $user->email)->orWhere('user_id', $user->id)->count(),
            'aktif' => Certificate::where(function ($q) use ($user) {
                $q->where('recipient_email', $user->email)->orWhere('user_id', $user->id);
            })->where('status', '!=', 'revoked')
                ->where(function ($q) {
                    $q->whereNull('expire_date')
                        ->orWhere('expire_date', '>=', now());
                })->count(),
            'kadaluarsa' => Certificate::where(function ($q) use ($user) {
                $q->where('recipient_email', $user->email)->orWhere('user_id', $user->id);
            })->where('status', '!=', 'revoked')
                ->whereNotNull('expire_date')
                ->where('expire_date', '<', now())->count(),
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
                'icon' => '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>', // Lightning
                'unlocked' => $totalCerts >= 1,
            ],
            [
                'name' => 'Kolektor',
                'description' => 'Kumpulkan 5 sertifikat',
                // Book/Collection
                'icon' => '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>',
                'unlocked' => $totalCerts >= 5,
            ],
            [
                'name' => 'Master',
                'description' => 'Kumpulkan 10 sertifikat',
                // Trophy/Cup
                'icon' => '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'unlocked' => $totalCerts >= 10,
            ],
            [
                'name' => 'Terverifikasi',
                'description' => '3 sertifikat aktif',
                // Shield Check
                'icon' => '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'unlocked' => $activeCerts >= 3,
            ],
            [
                'name' => 'Multitalenta',
                'description' => '3 kategori berbeda',
                // Cube/Puzzle
                'icon' => '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>',
                'unlocked' => $categories >= 3,
            ],
            [
                'name' => 'Networker',
                'description' => 'Dari 3 lembaga berbeda',
                // Globe/Network
                'icon' => '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>',
                'unlocked' => $lembaga >= 3,
            ],
            [
                'name' => 'Legend',
                'description' => 'Kumpulkan 25 sertifikat',
                // Crown
                'icon' => '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z" /></svg>',
                'unlocked' => $totalCerts >= 25,
            ],
            [
                'name' => 'Elite',
                'description' => 'Kumpulkan 50 sertifikat',
                // Diamond/Star
                'icon' => '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>',
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

        // Map form field to database column
        $updateData = [
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'occupation' => $validated['occupation'] ?? null,
            'user_institution' => $validated['institution'] ?? null,
            'country' => $validated['country'] ?? null,
        ];

        $user->update($updateData);

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
