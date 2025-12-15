<?php
namespace App\Http\Controllers;

use App\Models\Certificate;
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

        $hash = trim($data['hash']);

        // Try to find certificate by hash or certificate number
        $certificate = Certificate::where('hash', $hash)
            ->orWhere('certificate_number', $hash)
            ->first();

        if ($certificate) {
            $isValid = $certificate->isValid();

            $certificateData = [
                'id'         => $certificate->id,
                'nama'       => $certificate->recipient_name,
                'email'      => $certificate->recipient_email,
                'judul'      => $certificate->course_name,
                'kategori'   => $certificate->category,
                'deskripsi'  => $certificate->description,
                'tanggal'    => $certificate->issue_date->format('d F Y'),
                'kadaluarsa' => $certificate->expire_date?->format('d F Y'),
                'nomor'      => $certificate->certificate_number,
                'penerbit'   => $certificate->issuer->institution_name ?? $certificate->issuer->name,
                'status'     => $certificate->status,
                'is_valid'   => $isValid,
            ];

            // Return JSON for AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'valid'       => $isValid,
                    'hash'        => $certificate->hash,
                    'certificate' => $certificateData,
                ]);
            }

            return view('verifikasi.valid', [
                'hash'        => $certificate->hash,
                'certificate' => $certificateData,
            ]);
        }

        // Certificate not found
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'valid'   => false,
                'hash'    => $hash,
                'message' => 'Sertifikat tidak ditemukan dalam sistem kami',
            ]);
        }

        return view('verifikasi.invalid', compact('hash'));
    }

    /**
     * Show verification result page based on hash
     */
    public function show($hash)
    {
        // Try to find certificate by hash or certificate number
        $certificate = Certificate::where('hash', $hash)
            ->orWhere('certificate_number', $hash)
            ->first();

        if ($certificate) {
            $isValid = $certificate->isValid();

            // Get template image if exists
            $templateImage = null;
            if ($certificate->template && $certificate->template->file_path) {
                $templateImage = asset('storage/' . $certificate->template->file_path);
            }

            $certificateData = [
                'id'             => $certificate->id,
                'nama'           => $certificate->recipient_name,
                'email'          => $certificate->recipient_email,
                'judul'          => $certificate->course_name,
                'kategori'       => $certificate->category,
                'deskripsi'      => $certificate->description,
                'tanggal'        => $certificate->issue_date->format('d F Y'),
                'kadaluarsa'     => $certificate->expire_date?->format('d F Y'),
                'nomor'          => $certificate->certificate_number,
                'penerbit'       => $certificate->issuer->institution_name ?? $certificate->issuer->name,
                'status'         => $certificate->status,
                'is_valid'       => $isValid,
                'template_image' => $templateImage,
                'qr_code_url'    => $certificate->qr_code_url,
            ];

            return view('verifikasi.valid', [
                'hash'        => $certificate->hash,
                'certificate' => $certificateData,
            ]);
        }

        // Certificate not found
        return view('verifikasi.invalid', compact('hash'));
    }
}
