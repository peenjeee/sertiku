<?php
namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function index()
    {
        $totalCertificates = \App\Models\Certificate::count();
        $totalLembaga = \App\Models\User::whereIn('account_type', ['lembaga', 'institution'])->count();

        return view('verifikasi.index', compact('totalCertificates', 'totalLembaga'));
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
            $isExpired = $certificate->expire_date && $certificate->expire_date->isPast();

            if ($isExpired) {
                $isValid = false;
                $certificate->status = 'expired'; // Virtual status for display
            }

            // Perform verification logging
            \App\Models\ActivityLog::log(
                'verify_certificate',
                "Sertifikat {$certificate->certificate_number} diverifikasi via check",
                $certificate,
                ['ip' => request()->ip(), 'user_agent' => request()->userAgent()]
            );

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

            // Auto-generate file hashes if they don't exist
            if (empty($certificate->certificate_sha256)) {
                $certificate->generateFileHashes();
                $certificate->refresh();
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
                'qr_code_url' => $certificate->qr_code_path ? asset('storage/' . $certificate->qr_code_path) : null,
                // Template positioning
                'template_orientation' => $certificate->template->orientation ?? 'landscape',
                'name_position_x' => $certificate->template->name_position_x ?? 50,
                'name_position_y' => $certificate->template->name_position_y ?? 45,
                'name_font_size' => $certificate->template->name_font_size ?? 52,
                'name_font_color' => $certificate->template->name_font_color ?? '#1a1a1a',
                'qr_position_x' => $certificate->template->qr_position_x ?? 90,
                'qr_position_y' => $certificate->template->qr_position_y ?? 85,
                'qr_size' => $certificate->template->qr_size ?? 80,
                // File Integrity Hashes
                'certificate_sha256' => $certificate->certificate_sha256,
                'certificate_md5' => $certificate->certificate_md5,
                'qr_sha256' => $certificate->qr_sha256,
                'qr_md5' => $certificate->qr_md5,
                'template_sha256' => $certificate->template?->sha256,
                'template_md5' => $certificate->template?->md5,
            ];

            // Return JSON for AJAX/API request (check POST method or Accept header)
            if ($request->isMethod('post') || $request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                $response = [
                    'valid' => $isValid,
                    'hash' => $certificate->hash,
                    'certificate' => $certificateData,
                    'is_revoked' => $certificate->status === 'revoked',
                    'is_expired' => $isExpired ?? false,
                ];

                // Add warning message for revoked or expired certificates
                if ($certificate->status === 'revoked') {
                    $response['revoked_message'] = 'Sertifikat ini telah DICABUT oleh penerbit dan tidak lagi valid.';
                } elseif (isset($isExpired) && $isExpired) {
                    $response['expired_message'] = 'Sertifikat ini telah KADALUARSA pada ' . $certificate->expire_date->format('d F Y') . ' dan tidak lagi valid.';
                }

                return response()->json($response);
            }

            // Check if current user is the certificate owner or issuer
            $isOwner = false;
            $isIssuer = false;
            if (auth()->check()) {
                $user = auth()->user();
                // Check if user is the certificate recipient
                if ($certificate->recipient_email && strtolower($user->email) === strtolower($certificate->recipient_email)) {
                    $isOwner = true;
                }
                // Check if user is the issuing institution
                if ($certificate->user_id && $certificate->user_id === $user->id) {
                    $isIssuer = true;
                }
            }

            return view('verifikasi.valid', [
                'hash' => $certificate->hash,
                'certificate' => $certificateData,
                'isOwner' => $isOwner,
                'isIssuer' => $isIssuer,
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
            $isExpired = $certificate->expire_date && $certificate->expire_date->isPast();

            if ($isExpired) {
                $isValid = false;
                $certificate->status = 'expired'; // Virtual status
            }

            // Log verification activity

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
                // Template positioning
                'template_orientation' => $certificate->template->orientation ?? 'landscape',
                'name_position_x' => $certificate->template->name_position_x ?? 50,
                'name_position_y' => $certificate->template->name_position_y ?? 45,
                'name_font_size' => $certificate->template->name_font_size ?? 52,
                'name_font_color' => $certificate->template->name_font_color ?? '#1a1a1a',
                'name_font_family' => $certificate->template->name_font_family ?? 'Great Vibes',
                'qr_position_x' => $certificate->template->qr_position_x ?? 90,
                'qr_position_y' => $certificate->template->qr_position_y ?? 85,
                'qr_size' => $certificate->template->qr_size ?? 80,
            ];

            // Check if current user is the certificate owner or issuer
            $isOwner = false;
            $isIssuer = false;
            if (auth()->check()) {
                $user = auth()->user();
                // Check if user is the certificate recipient
                if ($certificate->recipient_email && strtolower($user->email) === strtolower($certificate->recipient_email)) {
                    $isOwner = true;
                }
                // Check if user is the issuing institution
                if ($certificate->user_id && $certificate->user_id === $user->id) {
                    $isIssuer = true;
                }
            }

            return view('verifikasi.valid', [
                'hash' => $certificate->hash,
                'certificate' => $certificateData,
                'isOwner' => $isOwner,
                'isIssuer' => $isIssuer,
            ]);
        }

        // Certificate not found
        return view('verifikasi.invalid', compact('hash'));
    }

    /**
     * Download certificate PDF.
     * Only accessible by certificate owner (recipient) or issuing institution.
     */
    public function downloadPdf(Request $request, $hash)
    {
        $certificate = Certificate::where('hash', $hash)
            ->orWhere('certificate_number', $hash)
            ->firstOrFail();

        // Check authorization: must be certificate owner OR issuing institution
        $isOwner = false;
        $isIssuer = false;

        if (auth()->check()) {
            $user = auth()->user();
            // Check if user is the certificate recipient
            if ($certificate->recipient_email && strtolower($user->email) === strtolower($certificate->recipient_email)) {
                $isOwner = true;
            }
            // Check if user is the issuing institution
            if ($certificate->user_id && $certificate->user_id == $user->id) {
                $isIssuer = true;
            }
        }

        if (!$isOwner && !$isIssuer) {
            abort(403, 'Anda tidak memiliki akses untuk melihat sertifikat ini.');
        }

        // Check if PDF exists in PRIVATE storage, if not generate it
        if (!$certificate->pdf_path || !\Illuminate\Support\Facades\Storage::disk('local')->exists($certificate->pdf_path)) {
            $certificate->generatePdf();
        }

        // Support inline streaming - serve from PRIVATE storage
        if ($request->has('stream')) {
            return \Illuminate\Support\Facades\Storage::disk('local')->response($certificate->pdf_path);
        }

        return \Illuminate\Support\Facades\Storage::disk('local')->download(
            $certificate->pdf_path,
            'Sertifikat-' . $certificate->certificate_number . '.pdf'
        );
    }

    /**
     * Handle legacy storage URLs /storage/certificates/pdf/{filename}
     * Proxies to secure download if user is authorized.
     */
    public function downloadPublicPdf(Request $request, $filename)
    {
        // Extract certificate number from filename (assuming format SERT-XXXXXX-XXXXXX.pdf)
        // Or simply find by PDF path match, since we store the relative path in DB

        // Try to find by explicit pdf_path logic
        // pdf_path in DB is 'certificates/pdf/FILENAME.pdf'
        $dbPath = 'certificates/pdf/' . $filename;

        $certificate = Certificate::where('pdf_path', $dbPath)->first();

        // If not found by path, try by exact filename match query
        if (!$certificate) {
            // Fallback: try finding by certificate number derived from filename
            $certNum = str_replace('.pdf', '', $filename);
            $certificate = Certificate::where('certificate_number', $certNum)->firstOrFail();
        }

        // Reuse the downloadPdf logic/checks
        return $this->downloadPdf($request, $certificate->hash);
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

        // Send to n8n webhook
        $webhookUrl = config('services.n8n.webhook_report_url');
        if (!empty($webhookUrl)) {
            try {
                \Illuminate\Support\Facades\Http::timeout(10)->post($webhookUrl, [
                    'type' => 'fraud_report',
                    'report_id' => $report->id,
                    'reported_hash' => $validated['reported_hash'],
                    'reporter_name' => $validated['reporter_name'],
                    'reporter_email' => $validated['reporter_email'],
                    'reporter_phone' => $validated['reporter_phone'] ?? null,
                    'description' => $validated['description'],
                    'timestamp' => now()->toIso8601String(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('n8n fraud report webhook error', [
                    'error' => $e->getMessage(),
                    'report_id' => $report->id,
                ]);
            }
        }

        // Log activity
        try {
            \App\Models\ActivityLog::log(
                'fraud_report',
                'Laporan pemalsuan baru: ' . $validated['reported_hash'],
                null,
                [
                    'report_id' => $report->id,
                    'reporter_email' => $validated['reporter_email'],
                ]
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to log fraud report activity', ['error' => $e->getMessage()]);
        }

        // Send email notification to admin
        try {
            \Illuminate\Support\Facades\Mail::raw(
                "Laporan Pemalsuan Sertifikat Baru\n\n" .
                "ID Laporan: #{$report->id}\n" .
                "Hash yang Dilaporkan: {$validated['reported_hash']}\n" .
                "Nama Pelapor: {$validated['reporter_name']}\n" .
                "Email Pelapor: {$validated['reporter_email']}\n" .
                "Telepon: " . ($validated['reporter_phone'] ?? 'Tidak diisi') . "\n\n" .
                "Deskripsi:\n{$validated['description']}\n\n" .
                "Waktu: " . now()->format('d M Y H:i:s') . " WIB\n" .
                "IP Address: " . $request->ip() . "\n\n" .
                "---\n" .
                "SertiKu - Sistem Verifikasi Sertifikat Digital",
                function ($message) use ($report) {
                    $message->to('sertikuofficial@gmail.com')
                        ->subject("[SertiKu] Laporan Pemalsuan Baru #{$report->id}");
                }
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send fraud report email', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim. Terima kasih atas kontribusi Anda.',
            'report_id' => $report->id,
        ]);
    }
    /**
     * Verify certificate by file upload (Hash Check)
     */
    public function verifyFile(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB
        ]);

        $file = $request->file('document');

        // Calculate hash of uploaded file
        $sha256 = hash_file('sha256', $file->getRealPath());
        $md5 = hash_file('md5', $file->getRealPath());

        // Find certificate by hash
        $certificate = Certificate::where('certificate_sha256', $sha256)
            ->orWhere('certificate_md5', $md5)
            ->first();

        if ($certificate) {
            // Log verification
            \App\Models\ActivityLog::log(
                'verify_certificate',
                "Sertifikat {$certificate->certificate_number} diverifikasi via Upload File",
                $certificate,
                ['ip' => request()->ip(), 'user_agent' => request()->userAgent()]
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'redirect_url' => route('verifikasi.show', $certificate->hash)
                ]);
            }

            return redirect()->route('verifikasi.show', $certificate->hash);
        }

        // Invalid file
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen tidak dikenali atau telah dimodifikasi. Pastikan Anda mengunggah file asli (original) yang belum diedit.'
            ]);
        }

        return back()->with('error', 'Dokumen tidak dikenali atau telah dimodifikasi.');
    }
}
