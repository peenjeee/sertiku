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
            'terverifikasi'    => $certificates->where('status', 'active')->count(),
            'total_verifikasi' => $certificates->sum('verification_count') ?? 0,
            'pending'          => $certificates->where('status', 'pending')->count(),
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

        // Recent activity (mock for now, can be replaced with actual activity log)
        $recentActivity = [
            [
                'type'     => 'new',
                'title'    => 'Sertifikat baru ditambahkan',
                'subtitle' => 'Workshop Web Development',
                'time'     => '2 jam lalu',
            ],
            [
                'type'     => 'view',
                'title'    => 'Sertifikat dilihat',
                'subtitle' => 'Seminar Cybersecurity',
                'time'     => '5 jam lalu',
            ],
            [
                'type'     => 'share',
                'title'    => 'Sertifikat dibagikan',
                'subtitle' => 'Training Data Science',
                'time'     => '1 hari lalu',
            ],
        ];

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
            'total'   => Certificate::where('recipient_email', $user->email)->orWhere('user_id', $user->id)->count(),
            'aktif'   => Certificate::where(function ($q) use ($user) {
                $q->where('recipient_email', $user->email)->orWhere('user_id', $user->id);
            })->where('status', 'active')->count(),
            'dicabut' => Certificate::where(function ($q) use ($user) {
                $q->where('recipient_email', $user->email)->orWhere('user_id', $user->id);
            })->where('status', 'revoked')->count(),
        ];

        return view('user.sertifikat', compact('certificates', 'stats'));
    }

    /**
     * Profil Pengguna
     */
    public function profil()
    {
        $user = Auth::user();
        return view('user.profil', compact('user'));
    }

    /**
     * Update Profil
     */
    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'nullable|string|max:50',
            'occupation'  => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'country'     => 'nullable|string|max:100',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    /**
     * Notifikasi
     */
    public function notifikasi()
    {
        $user          = Auth::user();
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
