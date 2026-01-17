<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Stats Logic
        $totalCertificates = Certificate::count();
        $totalLembaga = User::whereIn('account_type', ['lembaga', 'institution'])->count();

        // Fetch Random Lembaga for "Dipercaya oleh" section
        $lembagas = User::whereIn('account_type', ['lembaga', 'institution'])
            ->whereNotNull('institution_name')
            ->withCount('certificates')
            ->orderByDesc('certificates_count')
            ->take(6)
            ->get();

        // Fetch Static Featured Testimonials (Panji, Rama, Nakada Alpha)
        $testimonials = Testimonial::where('is_featured', true)->orderBy('id')->take(3)->get();

        // Total rating/feedback count
        $totalRatings = Testimonial::count();
        $averageRating = Testimonial::avg('rating') ?? 0;

        // Total Blockchain Transactions
        $totalBlockchainTransactions = Certificate::whereNotNull('blockchain_tx_hash')->count();

        return view('landing', compact('totalCertificates', 'totalLembaga', 'lembagas', 'testimonials', 'totalRatings', 'averageRating', 'totalBlockchainTransactions'));
    }
}
