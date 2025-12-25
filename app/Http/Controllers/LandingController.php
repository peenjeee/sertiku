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
            ->inRandomOrder()
            ->take(6)
            ->get();

        // Fetch Random Testimonials
        $testimonials = Testimonial::inRandomOrder()->take(3)->get();

        return view('landing', compact('totalCertificates', 'totalLembaga', 'lembagas', 'testimonials'));
    }
}
