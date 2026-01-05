<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CertificateApiController extends Controller
{
    /**
     * Verify a certificate by hash
     * GET /api/v1/verify/{hash}
     */
    public function verify(string $hash): JsonResponse
    {
        $certificate = Certificate::with('user')->where('hash', $hash)->first();

        if (!$certificate) {
            // Try finding by certificate number
            $certificate = Certificate::with('user')->where('certificate_number', $hash)->first();
        }

        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Sertifikat tidak ditemukan',
                'data' => null
            ], 404);
        }

        $isValid = $certificate->isValid();

        return response()->json([
            'success' => true,
            'message' => $isValid ? 'Sertifikat valid' : 'Sertifikat tidak valid',
            'data' => [
                'valid' => $isValid,
                'certificate' => [
                    'certificate_number' => $certificate->certificate_number,
                    'recipient_name' => $certificate->recipient_name,
                    'recipient_email' => $certificate->recipient_email,
                    'course_name' => $certificate->course_name,
                    'category' => $certificate->category,
                    'description' => $certificate->description,
                    'issue_date' => $certificate->issue_date?->format('Y-m-d'),
                    'expire_date' => $certificate->expire_date?->format('Y-m-d'),
                    'status' => $certificate->status,
                    'issuer' => [
                        'name' => $certificate->user->institution_name ?? $certificate->user->name,
                        'type' => $certificate->user->institution_type ?? 'Personal',
                    ],
                    'blockchain' => [
                        'enabled' => $certificate->blockchain_enabled,
                        'verified' => $certificate->isOnBlockchain(),
                        'tx_hash' => $certificate->blockchain_tx_hash,
                        'explorer_url' => $certificate->blockchain_explorer_url,
                    ],
                    'verification_url' => $certificate->verification_url,
                    'pdf_url' => $certificate->pdf_url,
                    'qr_code_url' => $certificate->qr_code_url,
                ],
            ]
        ]);
    }

    /**
     * Get platform statistics
     * GET /api/v1/stats
     */
    public function stats(): JsonResponse
    {
        $totalCertificates = Certificate::count();
        $activeCertificates = Certificate::where('status', 'active')->count();
        $totalIssuers = User::whereHas('certificates')->count();

        // Get actual on-chain count from smart contract
        $blockchainService = new \App\Services\BlockchainService();
        $contractStats = $blockchainService->getContractStats();
        $blockchainCertificates = $contractStats['totalCertificates'] ?? 0;

        return response()->json([
            'success' => true,
            'data' => [
                'total_certificates' => $totalCertificates,
                'active_certificates' => $activeCertificates,
                'total_issuers' => $totalIssuers,
                'blockchain_verified' => $blockchainCertificates,
                'platform' => [
                    'name' => 'SertiKu',
                    'version' => '1.0.0',
                    'website' => config('app.url'),
                ],
            ]
        ]);
    }

    /**
     * List certificates for authenticated user
     * GET /api/v1/certificates
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $certificates = $user->certificates()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $certificates
        ]);
    }

    /**
     * Get single certificate
     * GET /api/v1/certificates/{id}
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $certificate = $user->certificates()->find($id);

        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Sertifikat tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $certificate
        ]);
    }

    /**
     * Create/Upload new certificate
     * POST /api/v1/certificates
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Check if user can issue certificates
        if (!$user->canIssueCertificate()) {
            return response()->json([
                'success' => false,
                'message' => 'Batas sertifikat bulanan telah tercapai',
                'data' => [
                    'used' => $user->getCertificatesUsedThisMonth(),
                    'limit' => $user->getCertificateLimit(),
                ]
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'nullable|email|max:255',
            'course_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'issue_date' => 'required|date',
            'expire_date' => 'nullable|date|after:issue_date',
            'template_id' => 'nullable|exists:templates,id',
            'blockchain_enabled' => 'nullable|boolean',
            'ipfs_enabled' => 'nullable|boolean',
            'send_email' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $blockchainEnabled = $request->boolean('blockchain_enabled', false);
        $ipfsEnabled = $request->boolean('ipfs_enabled', false);
        $sendEmail = $request->boolean('send_email', false);

        // Check blockchain limit
        if ($blockchainEnabled && !$user->canUseBlockchain()) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota Blockchain bulan ini sudah habis',
                'data' => [
                    'used' => $user->getBlockchainUsedThisMonth(),
                    'limit' => $user->getBlockchainLimit(),
                ]
            ], 403);
        }

        // Check IPFS limit
        if ($ipfsEnabled && !$user->canUseIpfs()) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota IPFS bulan ini sudah habis',
                'data' => [
                    'used' => $user->getIpfsUsedThisMonth(),
                    'limit' => $user->getIpfsLimit(),
                ]
            ], 403);
        }

        // Create certificate
        $certificate = $user->certificates()->create([
            'recipient_name' => $request->recipient_name,
            'recipient_email' => $request->recipient_email,
            'course_name' => $request->course_name,
            'category' => $request->category,
            'description' => $request->description,
            'issue_date' => $request->issue_date,
            'expire_date' => $request->expire_date,
            'template_id' => $request->template_id,
            'blockchain_enabled' => $blockchainEnabled,
            'status' => 'active',
        ]);

        // Generate QR Code
        $certificate->generateQrCode();

        // Generate PDF
        try {
            $certificate->generatePdf();
        } catch (\Throwable $e) {
            \Log::error("API: Failed to generate PDF: " . $e->getMessage());
        }

        // Generate file hashes
        $certificate->generateFileHashes();

        // Process blockchain if enabled (background queue, processed via cron)
        if ($blockchainEnabled) {
            $blockchainService = new \App\Services\BlockchainService();
            if ($blockchainService->isEnabled()) {
                $certificate->update(['blockchain_status' => 'pending']);
                \App\Jobs\ProcessBlockchainCertificate::dispatch($certificate, $ipfsEnabled);
            }
        } elseif ($ipfsEnabled) {
            // IPFS only
            $ipfsService = new \App\Services\IpfsService();
            if ($ipfsService->isEnabled()) {
                \App\Jobs\ProcessIpfsCertificate::dispatch($certificate);
            }
        }

        // Send email if requested
        if ($sendEmail && !empty($request->recipient_email)) {
            \Mail::to($request->recipient_email)
                ->queue(new \App\Mail\CertificateIssuedMail($certificate));
        }

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil dibuat',
            'data' => [
                'id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
                'recipient_name' => $certificate->recipient_name,
                'recipient_email' => $certificate->recipient_email,
                'course_name' => $certificate->course_name,
                'category' => $certificate->category,
                'issue_date' => $certificate->issue_date?->format('Y-m-d'),
                'expire_date' => $certificate->expire_date?->format('Y-m-d'),
                'status' => $certificate->status,
                'blockchain_enabled' => $certificate->blockchain_enabled,
                'blockchain_status' => $certificate->blockchain_status,
                'verification_url' => $certificate->verification_url,
                'pdf_url' => $certificate->pdf_url,
                'created_at' => $certificate->created_at->toISOString(),
            ]
        ], 201);
    }

    /**
     * Revoke a certificate
     * PUT /api/v1/certificates/{id}/revoke
     */
    public function revoke(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Try to find by ID or certificate_number
        $certificate = $user->certificates()->find($id);

        if (!$certificate) {
            $certificate = $user->certificates()->where('certificate_number', $id)->first();
        }

        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Sertifikat tidak ditemukan'
            ], 404);
        }

        if ($certificate->status === 'revoked') {
            return response()->json([
                'success' => false,
                'message' => 'Sertifikat sudah dicabut sebelumnya'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:500',
        ]);

        $certificate->revoke($request->reason ?? 'Dicabut melalui API');

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil dicabut',
            'data' => [
                'id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
                'status' => $certificate->fresh()->status,
                'revoked_at' => $certificate->revoked_at?->toISOString(),
                'revoke_reason' => $certificate->revoke_reason,
            ]
        ]);
    }

    /**
     * Reactivate a revoked certificate
     * PUT /api/v1/certificates/{id}/reactivate
     */
    public function reactivate(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Try to find by ID or certificate_number
        $certificate = $user->certificates()->find($id);

        if (!$certificate) {
            $certificate = $user->certificates()->where('certificate_number', $id)->first();
        }

        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Sertifikat tidak ditemukan'
            ], 404);
        }

        if ($certificate->status !== 'revoked') {
            return response()->json([
                'success' => false,
                'message' => 'Sertifikat tidak dalam status dicabut'
            ], 400);
        }

        // Reactivate the certificate
        $certificate->update([
            'status' => 'active',
            'revoked_at' => null,
            'revoke_reason' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil diaktifkan kembali',
            'data' => [
                'id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
                'recipient_name' => $certificate->recipient_name,
                'status' => $certificate->fresh()->status,
                'reactivated_at' => now()->toISOString(),
            ]
        ]);
    }
}
