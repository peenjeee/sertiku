<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\certificate; // sesuaikan dengan model kamu
use phpseclib3\File\ASN1\Maps\Certificate as MapsCertificate;

class VerifyController extends Controller
{
    public function index()
    {
        // halaman form verifikasi (yang biru gelap tadi)
        return view('verifikasi.index');
    }

    public function check(Request $request)
{
    $data = $request->validate([
        'hash' => 'required|string',
    ]);

    $hash = $data['hash'];

    // Dummy: jika hash cocok, anggap valid
    if ($hash === 'ABC123XYZ') {
        $certificate = [
            'nama'     => 'Mr. Ambatukam',
            'judul'    => 'Penghargaan',
            'tanggal'  => '06 Juli 2025',
            'penerbit' => 'Barbershop Ngawi',
        ];

        return view('verifikasi.valid', compact('hash', 'certificate'));
    }

    // selain itu arahkan ke halaman invalid
    return view('verifikasi.invalid', compact('hash'));
}


}
