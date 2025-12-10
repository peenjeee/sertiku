<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LembagaController extends Controller
{
    /**
     * Show the institution dashboard.
     */
    public function dashboard()
    {
        return view('lembaga.dashboard');
    }

    /**
     * Show the certificate creation form.
     */
    public function createSertifikat()
    {
        return view('lembaga.sertifikat.create');
    }

    /**
     * Store a new certificate.
     */
    public function storeSertifikat(Request $request)
    {
        // TODO: Implement certificate creation logic
        return redirect()->route('lembaga.sertifikat.index')
            ->with('success', 'Sertifikat berhasil diterbitkan!');
    }

    /**
     * Show the list of certificates.
     */
    public function indexSertifikat()
    {
        return view('lembaga.sertifikat.index');
    }

    /**
     * Show the template gallery.
     */
    public function indexTemplate()
    {
        return view('lembaga.template.index');
    }

    /**
     * Show the template upload page.
     */
    public function uploadTemplate()
    {
        return view('lembaga.template.upload');
    }

    /**
     * Store a new template.
     */
    public function storeTemplate(Request $request)
    {
        // TODO: Implement template upload logic
        return redirect()->route('lembaga.template.index')
            ->with('success', 'Template berhasil diupload!');
    }
}
