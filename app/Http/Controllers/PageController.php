<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function bantuan()
    {
        return view('pages.bantuan');
    }

    public function dokumentasi()
    {
        return view('pages.dokumentasi');
    }

    public function status()
    {
        return view('pages.status');
    }

    public function kontak()
    {
        return view('pages.kontak');
    }

    public function sendKontak(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // For now, just redirect with success message
        // TODO: Implement actual email sending
        return redirect()->back()->with('success', 'Pesan berhasil dikirim! Kami akan menghubungi Anda segera.');
    }

    public function privasi()
    {
        return view('pages.privasi');
    }

    public function syarat()
    {
        return view('pages.syarat');
    }
}
