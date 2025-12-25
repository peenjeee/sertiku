<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Show the form for creating a new feedback.
     */
    public function create()
    {
        // Cek apakah user sudah pernah memberikan feedback
        $existingFeedback = Testimonial::where('user_id', Auth::id())->first();

        if ($existingFeedback) {
            return view('feedback.create', compact('existingFeedback'));
        }

        return view('feedback.create');
    }

    /**
     * Store a newly created feedback in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:500',
        ]);

        // Cek double submit
        if (Testimonial::where('user_id', Auth::id())->exists()) {
            return redirect()->route('user.dashboard')->with('error', 'Anda hanya dapat memberikan feedback satu kali.');
        }

        $user = Auth::user();

        // Buat Initial dari Nama atau Institusi
        $nameToUse = $user->institution_name ?? $user->name;
        $initial = strtoupper(substr($nameToUse, 0, 1));

        // Simpan feedback
        Testimonial::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'role' => $user->role ?? ($user->account_type == 'lembaga' ? 'Perwakilan Lembaga' : 'User'),
            'institution' => $user->institution_name ?? '-',
            'initial' => $initial,
            'rating' => $request->rating,
            'content' => $request->content,
            'avatar' => $user->avatar,
            'is_featured' => false, // Default tidak tampil di landing
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Terima kasih! Feedback Anda telah terkirim.');
    }
}
