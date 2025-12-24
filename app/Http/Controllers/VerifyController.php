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

            // Increment verification count
            $certificate->increment('verification_count');

            // Send notification to certificate owner if they exist in system
            if ($certificate->recipient_email) {
                $owner = \App\Models\User::where('email', $certificate->recipient_email)->first();
                if ($owner) {
                    $owner->notify(new \App\Notifications\CertificateViewed($certificate, [
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]));
                }
            }

            $certificateData = [
                'id' => $certificate->id,
                'nama' => $certificate->recipient_name,
                'email' => $certificate->recipient_email,
                'judul' => $certificate->course_name,
                'kategori' => $certificate->category,
                'deskripsi' => $certificate->description,
                'tanggal' => $certificate->issue_date->format('d F Y'),
                'kadaluarsa' => $certificate->expire_date?->format('d F Y'),
                'nomor' => $certificate->certificate_number,
                'penerbit' => $certificate->issuer->institution_name ?? $certificate->issuer->name,
                'status' => $certificate->status,
                'is_valid' => $isValid,
                'blockchain_tx_hash' => $certificate->blockchain_tx_hash,
                'blockchain_status' => $certificate->blockchain_status,
                'ipfs_cid' => $certificate->ipfs_cid,
                'ipfs_url' => $certificate->ipfs_url,
            ];

            // Return JSON for AJAX/API request (check POST method or Accept header)
            if ($request->isMethod('post') || $request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'valid' => $isValid,
                    'hash' => $certificate->hash,
                    'certificate' => $certificateData,
                ]);
            }

            return view('verifikasi.valid', [
                'hash' => $certificate->hash,
                'certificate' => $certificateData,
            ]);
        }

        // Certificate not found
        if ($request->isMethod('post') || $request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'valid' => false,
                'hash' => $hash,
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

            // Increment verification count and log the verification
            $certificate->increment('verification_count');

            // Log verification activity
            \App\Models\ActivityLog::log(
                'verify_certificate',
                "Sertifikat {$certificate->certificate_number} diverifikasi",
                $certificate,
                ['ip' => request()->ip(), 'user_agent' => request()->userAgent()]
            );

            // Get template image if exists
            $templateImage = null;
            if ($certificate->template && $certificate->template->file_path) {
                $templateImage = asset('storage/' . $certificate->template->file_path);
            }

            $certificateData = [
                'id' => $certificate->id,
                'nama' => $certificate->recipient_name,
                'email' => $certificate->recipient_email,
                'judul' => $certificate->course_name,
                'kategori' => $certificate->category,
                'deskripsi' => $certificate->description,
                'tanggal' => $certificate->issue_date->format('d F Y'),
                'kadaluarsa' => $certificate->expire_date?->format('d F Y'),
                'nomor' => $certificate->certificate_number,
                'penerbit' => $certificate->issuer->institution_name ?? $certificate->issuer->name,
                'status' => $certificate->status,
                'is_valid' => $isValid,
                'template_image' => $templateImage,
                'qr_code_url' => $certificate->qr_code_url,
                'blockchain_tx_hash' => $certificate->blockchain_tx_hash,
                'blockchain_status' => $certificate->blockchain_status,
                'ipfs_cid' => $certificate->ipfs_cid,
                'ipfs_url' => $certificate->ipfs_url,
            ];

            return view('verifikasi.valid', [
                'hash' => $certificate->hash,
                'certificate' => $certificateData,
            ]);
        }

        // Certificate not found
        return view('verifikasi.invalid', compact('hash'));
    }

    /**
     * Store a fraud report
     */
    public function storeFraudReport(Request $request)
    {
        $validated = $request->validate([
            'reported_hash' => 'required|string|max:255',
            'reporter_name' => 'required|string|max:255',
            'reporter_email' => 'required|email|max:255',
            'reporter_phone' => 'nullable|string|max:20',
            'description' => 'required|string|min:10|max:2000',
        ], [
            'reported_hash.required' => 'Hash sertifikat wajib diisi.',
            'reporter_name.required' => 'Nama lengkap wajib diisi.',
            'reporter_email.required' => 'Email wajib diisi.',
            'reporter_email.email' => 'Format email tidak valid.',
            'description.required' => 'Deskripsi pemalsuan wajib diisi.',
            'description.min' => 'Deskripsi minimal 10 karakter.',
        ]);

        // Create fraud report
        $report = \App\Models\FraudReport::create([
            'reported_hash' => $validated['reported_hash'],
            'reporter_name' => $validated['reporter_name'],
            'reporter_email' => $validated['reporter_email'],
            'reporter_phone' => $validated['reporter_phone'] ?? null,
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim. Terima kasih atas kontribusi Anda.',
            'report_id' => $report->id,
        ]);
    }
}
