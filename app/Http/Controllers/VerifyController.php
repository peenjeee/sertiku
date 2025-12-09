<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function index()
    {
        return view('verifikasi.index');
    }

    public function check(Request $request)
    {
        $data = $request->validate([
            'hash' => 'required|string',
        ]);

        $hash = $data['hash'];

        // Dummy: jika hash cocok, anggap valid
        if ($hash === 'SERT-AMBA123') {
            $certificate = [
                'nama'     => 'Mr. Ambatukam',
                'judul'    => 'Penghargaan',
                'tanggal'  => '06 Juli 2025',
                'penerbit' => 'Barbershop Ngawi',
            ];

            // Return JSON for AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'valid' => true,
                    'hash' => $hash,
                    'certificate' => $certificate
                ]);
            }

            return view('verifikasi.valid', compact('hash', 'certificate'));
        }

        // Invalid certificate
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'valid' => false,
                'hash' => $hash,
                'message' => 'Sertifikat tidak ditemukan dalam sistem kami'
            ]);
        }

        return view('verifikasi.invalid', compact('hash'));
    }

    /**
     * Show verification result page based on hash
     */
    public function show($hash)
    {
        // Dummy: jika hash cocok, anggap valid
        if ($hash === 'SERT-AMBA123') {
            $certificate = [
                'nama'     => 'Mr. Ambatukam',
                'judul'    => 'Penghargaan',
                'tanggal'  => '06 Juli 2025',
                'penerbit' => 'Barbershop Ngawi',
            ];

            return view('verifikasi.valid', compact('hash', 'certificate'));
        }

        // Invalid certificate
        return view('verifikasi.invalid', compact('hash'));
    }
}
