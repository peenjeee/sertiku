<?php
namespace App\Http\Controllers;

use App\Mail\CertificateIssuedMail;
use App\Models\ActivityLog;
use App\Models\Certificate;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class LembagaController extends Controller
{
    /**
     * Show the institution dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();

        $stats = [
            'total_certificates' => $user->certificates()->count(),
            'active_certificates' => $user->certificates()->where('status', 'active')->count(),
            'certificates_this_month' => $user->getCertificatesUsedThisMonth(),
            'total_templates' => $user->templates()->where('is_active', true)->count(),
            'recent_certificates' => $user->certificates()->latest()->take(5)->get(),
            // Count verifications from activity_logs where certificates belong to this user
            'total_verifications' => \App\Models\ActivityLog::where('action', 'verify_certificate')
                ->whereHasMorph('subject', [\App\Models\Certificate::class], function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->count(),
        ];

        return view('lembaga.dashboard', compact('stats'));
    }

    /**
     * Show the certificate creation form.
     */
    public function createSertifikat()
    {
        $user = Auth::user();
        $templates = $user->templates()->where('is_active', true)->latest()->get();

        $blockchainService = app(\App\Services\BlockchainService::class);
        $isLowBalance = $blockchainService->isLowBalance(0.01);
        $blockchainDisabled = $isLowBalance || !$user->canUseBlockchain();

        return view('lembaga.sertifikat.create', compact('templates', 'isLowBalance', 'blockchainDisabled'));
    }

    /**
     * Store a new certificate.
     */
    public function storeSertifikat(Request $request)
    {
        $user = Auth::user();

        // Check certificate limit
        if (!$user->canIssueCertificate()) {
            return back()->with('error', 'Kuota sertifikat bulan ini sudah habis. Silakan upgrade paket Anda.');
        }

        $validated = $request->validate([
            'template_id' => 'nullable|exists:templates,id',
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'nullable|email|max:255',
            'course_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'issue_date' => 'required|date',
            'expire_date' => 'nullable|date|after:issue_date',
            'blockchain_enabled' => 'nullable|boolean',
            'ipfs_enabled' => 'nullable|boolean',
            'send_email' => 'nullable|boolean',
        ]);

        // Set blockchain_enabled flag
        $validated['blockchain_enabled'] = $request->boolean('blockchain_enabled');

        // Get IPFS enabled flag (not stored in database, triggers job)
        $ipfsEnabled = $request->boolean('ipfs_enabled');

        // Check blockchain limit if user wants to use blockchain
        if ($validated['blockchain_enabled'] && !$user->canUseBlockchain()) {
            $blockchainUsed = $user->getBlockchainUsedThisMonth();
            $blockchainLimit = $user->getBlockchainLimit();
            return back()->with('error', "Kuota Blockchain bulan ini sudah habis ({$blockchainUsed}/{$blockchainLimit}). Silakan upgrade paket atau tunggu bulan depan.");
        }

        // Check IPFS limit if user wants to use IPFS
        if ($ipfsEnabled && !$user->canUseIpfs()) {
            $ipfsUsed = $user->getIpfsUsedThisMonth();
            $ipfsLimit = $user->getIpfsLimit();
            return back()->with('error', "Kuota IPFS bulan ini sudah habis ({$ipfsUsed}/{$ipfsLimit}). Silakan upgrade paket atau tunggu bulan depan.");
        }

        // Get send_email flag (not stored in database)
        $sendEmail = $request->boolean('send_email');

        // Remove non-database fields from validated data
        unset($validated['send_email']);
        unset($validated['ipfs_enabled']);

        // Explicitly set statuses at creation time (overrides DB defaults)
        $validated['ipfs_status'] = $ipfsEnabled ? 'pending' : null;
        $validated['blockchain_status'] = $validated['blockchain_enabled'] ? 'pending' : 'disabled';

        // Create certificate
        $certificate = $user->certificates()->create($validated);

        // Generate QR code for the certificate
        try {
            $certificate->generateQrCode();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to generate QR Code for cert {$certificate->id}: " . $e->getMessage());
        }

        // Generate PDF for the certificate (Critical for File Verification)
        try {
            $certificate->generatePdf();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to generate PDF (continuing without it): " . $e->getMessage());
            // Continue without PDF, but verifying file integrity will fail for uploaded files
        }

        // Generate file hashes (SHA256/MD5) for certificate and QR
        try {
            $certificate->generateFileHashes();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to generate file hashes for cert {$certificate->id}: " . $e->getMessage());
        }

        // Increment template usage if template was used
        if ($certificate->template_id) {
            $certificate->template->incrementUsage();
        }

        // Log activity
        ActivityLog::log(
            'create_certificate',
            'Sertifikat diterbitkan untuk: ' . $certificate->recipient_name,
            $certificate
        );

        // Send email to recipient if email exists and send_email is checked
        if ($sendEmail && !empty($validated['recipient_email'])) {
            // Send certificate email via queue
            try {
                Mail::to($validated['recipient_email'])
                    ->queue(new CertificateIssuedMail($certificate));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Failed to queue email for cert {$certificate->id}: " . $e->getMessage());
            }
        }

        // Send in-app notification to recipient if they have an account
        if (!empty($validated['recipient_email'])) {
            try {
                $recipient = \App\Models\User::where('email', $validated['recipient_email'])->first();
                if ($recipient) {
                    // Use queue to send notification in background
                    $recipient->notify((new \App\Notifications\CertificateReceived($certificate))->delay(now()->addSeconds(5)));
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send notification for cert {$certificate->id}: " . $e->getMessage());
            }
        }

        // If blockchain upload requested, dispatch job to process in background
        // Queue processed via cron (queue:work --once) to avoid OOM
        // Status already set at creation time, just dispatch jobs (same as BulkCertificateController)
        try {
            if ($validated['blockchain_enabled']) {
                $blockchainService = new \App\Services\BlockchainService();

                if ($blockchainService->isEnabled()) {
                    // Dispatch Blockchain Job to background queue
                    \App\Jobs\ProcessBlockchainCertificate::dispatch($certificate, $ipfsEnabled);
                } else {
                    // Blockchain not configured - update status
                    $certificate->update(['blockchain_status' => 'disabled']);
                }
            } elseif ($ipfsEnabled) {
                $ipfsService = new \App\Services\IpfsService();
                if ($ipfsService->isEnabled()) {
                    // IPFS only - dispatch to queue
                    \App\Jobs\ProcessIpfsCertificate::dispatch($certificate);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to dispatch Blockchain/IPFS job for cert {$certificate->id}: " . $e->getMessage());
        }

        if ($validated['blockchain_enabled'] || $ipfsEnabled) {
            return redirect()->route('lembaga.sertifikat.index')
                ->with('success', 'Sertifikat berhasil diterbitkan!')
                ->with('blockchain_process_started', true);
        }

        return redirect()->route('lembaga.sertifikat.index')
            ->with('success', 'Sertifikat berhasil diterbitkan!');
    }

    /**
     * Show the list of certificates.
     */
    public function indexSertifikat(Request $request)
    {
        $user = Auth::user();

        $query = $user->certificates()->with('template');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('recipient_name', 'like', "%{$search}%")
                    ->orWhere('recipient_email', 'like', "%{$search}%")
                    ->orWhere('course_name', 'like', "%{$search}%")
                    ->orWhere('certificate_number', 'like', "%{$search}%");
            });
        }

        // Filter by Date
        if ($request->has('issue_date') && $request->issue_date) {
            $query->whereDate('issue_date', $request->issue_date);
        }

        // Filter by Status
        if ($request->has('status') && $request->status) {
            $status = $request->status;
            if ($status === 'revoked') {
                $query->where('status', 'revoked');
            } elseif ($status === 'expired') {
                $query->where('expire_date', '<', now());
            } elseif ($status === 'active') {
                $query->where('status', '!=', 'revoked')
                    ->where(function ($q) {
                        $q->whereNull('expire_date')
                            ->orWhere('expire_date', '>=', now());
                    });
            }
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $certificates = $query->latest()->paginate(12);

        // Get stats
        $stats = [
            'total' => $user->certificates()->count(),
            'active' => $user->certificates()
                ->where('status', '!=', 'revoked')
                ->where(function ($q) {
                    $q->whereNull('expire_date')
                        ->orWhere('expire_date', '>=', now());
                })->count(),
            'revoked' => $user->certificates()->where('status', 'revoked')->count(),
            'expired' => $user->certificates()
                ->where('status', '!=', 'revoked')
                ->whereNotNull('expire_date')
                ->where('expire_date', '<', now())->count(),
        ];

        return view('lembaga.sertifikat.index', compact('certificates', 'stats'));
    }

    /**
     * Show a single certificate.
     */
    public function showSertifikat(Certificate $certificate)
    {
        // Ensure user owns this certificate
        if ((int) $certificate->user_id !== (int) Auth::id()) {
            abort(403);
        }

        return view('lembaga.sertifikat.show', compact('certificate'));
    }

    /**
     * Revoke a certificate.
     */
    public function revokeSertifikat(Request $request, Certificate $certificate)
    {
        // Ensure user owns this certificate
        if ((int) $certificate->user_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized access to this certificate.');
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $certificate->revoke($validated['reason'] ?? null);

        // Log Activity
        ActivityLog::log(
            'revoke_certificate',
            "Mencabut sertifikat {$certificate->certificate_number}",
            $certificate,
            ['reason' => $validated['reason'] ?? null]
        );

        return back()->with('success', 'Sertifikat berhasil dicabut.');
    }

    /**
     * Reactivate a revoked certificate.
     */
    public function reactivateSertifikat(Request $request, Certificate $certificate)
    {
        // Ensure user owns this certificate
        if ((int) $certificate->user_id !== (int) Auth::id()) {
            abort(403);
        }

        // Only allow reactivating revoked certificates
        if ($certificate->status !== 'revoked') {
            return back()->with('error', 'Sertifikat tidak dalam status dicabut.');
        }

        $certificate->update([
            'status' => 'active',
            'revoked_at' => null,
            'revoked_reason' => null,
        ]);

        // Log Activity
        ActivityLog::log(
            'reactivate_certificate',
            "Mengaktifkan kembali sertifikat {$certificate->certificate_number}",
            $certificate
        );

        return back()->with('success', 'Sertifikat berhasil diaktifkan kembali.');
    }

    /**
     * Show the template gallery.
     */
    public function indexTemplate(Request $request)
    {
        $user = Auth::user();
        $query = $user->templates()->latest();

        // Search by name
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        $templates = $query->paginate(12)->withQueryString();

        $stats = [
            'total' => $user->templates()->count(),
            'active' => $user->templates()->where('is_active', true)->count(),
        ];

        return view('lembaga.template.index', compact('templates', 'stats'));
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'template_file' => 'required|file|mimes:png,jpg,jpeg|max:10240', // Max 10MB
            'orientation' => 'required|in:landscape,portrait',
            // Position fields
            'name_position_x' => 'nullable|integer|min:0|max:100',
            'name_position_y' => 'nullable|integer|min:0|max:100',
            'name_font_size' => 'nullable|integer|min:20|max:100',
            'name_font_color' => 'nullable|string|max:10',
            'name_font_family' => 'nullable|string|max:255',
            'qr_position_x' => 'nullable|integer|min:0|max:100',
            'qr_position_y' => 'nullable|integer|min:0|max:100',
            'qr_size' => 'nullable|integer|min:50|max:150',
        ]);

        $user = Auth::user();

        // Store the template file
        $file = $request->file('template_file');

        // Check if file is valid before storing
        if (!$file->isValid()) {
            return back()->withErrors(['template_file' => 'File upload gagal: ' . $file->getErrorMessage()])->withInput();
        }

        $path = $file->store('templates/' . $user->id, 'local');

        // Check if file upload failed
        if ($path === false || empty($path)) {
            return back()->withErrors(['template_file' => 'Gagal menyimpan file. Silakan hubungi administrator.'])->withInput();
        }

        // Create thumbnail (for images)
        $thumbnailPath = null;
        if (in_array($file->getClientOriginalExtension(), ['png', 'jpg', 'jpeg'])) {
            $thumbnailPath = $path; // Use same path for now, could generate actual thumbnail
        }

        // Get image dimensions
        $width = null;
        $height = null;
        if (in_array($file->getClientOriginalExtension(), ['png', 'jpg', 'jpeg'])) {
            list($width, $height) = getimagesize($file->getRealPath());
        }

        // Calculate hashes
        $sha256 = hash_file('sha256', $file->getRealPath());
        $md5 = md5_file($file->getRealPath());

        // Create template record
        $template = $user->templates()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'file_path' => $path,
            'thumbnail_path' => $thumbnailPath,
            'orientation' => $validated['orientation'],
            'width' => $width,
            'height' => $height,
            'sha256' => $sha256,
            'md5' => $md5,
            // Position fields
            'name_position_x' => $validated['name_position_x'] ?? 50,
            'name_position_y' => $validated['name_position_y'] ?? 45,
            'name_font_size' => $validated['name_font_size'] ?? 52,
            'name_font_color' => $validated['name_font_color'] ?? '#1a1a1a',
            'is_name_visible' => $request->boolean('is_name_visible'),
            'name_font_family' => $validated['name_font_family'] ?? 'Great Vibes',
            'qr_position_x' => $validated['qr_position_x'] ?? 90,
            'qr_position_y' => $validated['qr_position_y'] ?? 85,
            'qr_size' => $validated['qr_size'] ?? 80,
            'is_qr_visible' => $request->boolean('is_qr_visible'),
        ]);

        return redirect()->route('lembaga.template.index')
            ->with('success', 'Template berhasil diupload!');
    }

    /**
     * Show form to edit template position settings.
     */
    public function editTemplatePosition(Template $template)
    {
        // Check ownership (use loose comparison for type flexibility)
        if ($template->user_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('lembaga.template.edit-position', compact('template'));
    }

    /**
     * Update template position settings.
     */
    public function updateTemplatePosition(Request $request, Template $template)
    {
        // Check ownership (use loose comparison for type flexibility)
        if ($template->user_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_position_x' => 'required|integer|min:0|max:100',
            'name_position_y' => 'required|integer|min:0|max:100',
            'name_font_size' => 'required|integer|min:20|max:150',
            'name_font_color' => 'required|string',
            'name_font_family' => 'nullable|string',
            'is_name_visible' => 'nullable|boolean',
            'qr_position_x' => 'required|integer|min:0|max:100',
            'qr_position_y' => 'required|integer|min:0|max:100',
            'qr_size' => 'required|integer|min:40|max:200',
            'is_qr_visible' => 'nullable|boolean',
        ]);

        // Handle checkboxes (boolean) explicitly as they might be missing from request if unchecked
        $data = $validated;
        $data['is_name_visible'] = $request->boolean('is_name_visible');
        $data['is_qr_visible'] = $request->boolean('is_qr_visible');

        $template->update($data);

        return redirect()->route('lembaga.template.index')
            ->with('success', 'Posisi template berhasil diperbarui!');
    }

    /**
     * Show the system template picker.
     */
    public function createTemplate()
    {
        // Define system presets
        $presets = [
            [
                'id' => 'blue_modern',
                'name' => 'Blue Modern',
                'description' => 'Desain profesional dengan nuansa biru.',
                'color' => 'bg-blue-600',
                'orientation' => 'landscape'
            ],
            [
                'id' => 'gold_classic',
                'name' => 'Gold Classic',
                'description' => 'Desain klasik elegan dengan aksen emas.',
                'color' => 'bg-yellow-500',
                'orientation' => 'landscape'
            ],
            [
                'id' => 'green_nature',
                'name' => 'Green Nature',
                'description' => 'Desain segar dengan nuansa hijau alam.',
                'color' => 'bg-green-600',
                'orientation' => 'landscape'
            ],
        ];

        return view('lembaga.template.create', compact('presets'));
    }

    /**
     * Store a selected system template.
     */
    public function storeSystemTemplate(Request $request)
    {
        $validated = $request->validate([
            'preset_id' => 'required|string',
            'name' => 'required|string|max:255',
            'orientation' => 'required|in:landscape,portrait',
        ]);

        $user = Auth::user();
        $presetId = $validated['preset_id'];
        $orientation = $validated['orientation'];

        // Define preset colors/styles map (Mocking generation)
        $presets = [
            'blue_modern' => ['r' => 37, 'g' => 99, 'b' => 235],
            'gold_classic' => ['r' => 234, 'g' => 179, 'b' => 8],
            'green_nature' => ['r' => 22, 'g' => 163, 'b' => 74],
        ];

        if (!isset($presets[$presetId])) {
            return back()->with('error', 'Preset tidak valid.');
        }

        // Generate a simple image for this preset
        // A4 Landscape: 1123x794, Portrait: 794x1123 (approx 96dpi)
        if ($orientation === 'portrait') {
            $width = 794;
            $height = 1123;
        } else {
            $width = 1123;
            $height = 794;
        }

        $img = imagecreatetruecolor($width, $height);

        $color = $presets[$presetId];
        $bgColor = imagecolorallocate($img, $color['r'], $color['g'], $color['b']);
        imagefill($img, 0, 0, $bgColor);

        // Add a white box in middle
        $white = imagecolorallocate($img, 255, 255, 255);
        imagefilledrectangle($img, 50, 50, $width - 50, $height - 50, $white);

        // Add border
        $borderColor = imagecolorallocate($img, $color['r'], $color['g'], $color['b']);
        imagerectangle($img, 70, 70, $width - 70, $height - 70, $borderColor);

        // Save to storage (PRIVATE)
        $filename = 'templates/' . $user->id . '/' . uniqid() . '.jpg';
        Storage::disk('local')->makeDirectory('templates/' . $user->id);
        $fullPath = Storage::disk('local')->path($filename);
        imagejpeg($img, $fullPath);
        imagedestroy($img);

        // Save DB record
        $user->templates()->create([
            'name' => $validated['name'],
            'description' => 'Generated System Template (' . $presetId . ')',
            'file_path' => $filename,
            'thumbnail_path' => $filename,
            'orientation' => $orientation,
            'is_active' => true,
        ]);

        return redirect()->route('lembaga.template.index')
            ->with('success', 'Template berhasil dibuat oleh sistem!');
    }

    /**
     * Delete a template.
     */
    public function destroyTemplate(Template $template)
    {
        // Ensure user owns this template
        if ($template->user_id != Auth::id()) {
            abort(403);
        }

        // Delete file from storage
        if ($template->file_path) {
            Storage::disk('local')->delete($template->file_path);
        }
        if ($template->thumbnail_path && $template->thumbnail_path !== $template->file_path) {
            Storage::disk('local')->delete($template->thumbnail_path);
        }

        $template->delete();

        return back()->with('success', 'Template berhasil dihapus.');
    }

    /**
     * Toggle template active status.
     */
    public function toggleTemplate(Template $template)
    {
        // Ensure user owns this template
        if ($template->user_id != Auth::id()) {
            abort(403);
        }

        $template->update(['is_active' => !$template->is_active]);


        $status = $template->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Template berhasil {$status}.");
    }

    /**
     * Show AI Template Generator page.
     */
    public function createAITemplate()
    {
        return view('lembaga.template.ai-create');
    }

    /**
     * Generate AI templates via n8n webhook.
     */
    public function generateAITemplate(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:500',
            'model' => 'nullable|string',
            'style' => 'required|string|in:modern,classic,elegant,minimalist,futuristic',
            'orientation' => 'required|string|in:landscape,portrait',
            'primary_color' => 'required|string|max:20',
            'accent_color' => 'nullable|string|max:20',
            'variations' => 'required|integer|min:1|max:4',
        ]);

        try {
            // Build color description
            $colorDesc = $validated['primary_color'];
            if (!empty($validated['accent_color'])) {
                $colorDesc .= ' and ' . $validated['accent_color'];
            }

            // Style keyword mapping
            $styleKeywords = [
                'modern' => 'modern minimalist clean sleek contemporary',
                'classic' => 'classic traditional ornate vintage decorative',
                'elegant' => 'elegant sophisticated luxury premium refined',
                'minimalist' => 'minimal simple clean whitespace',
                'futuristic' => 'futuristic tech digital gradient modern',
            ];

            $stylePrompt = $styleKeywords[$validated['style']] ?? $validated['style'];

            // Dimensions based on orientation (A4 at 96dpi)
            $width = $validated['orientation'] === 'portrait' ? 794 : 1123;
            $height = $validated['orientation'] === 'portrait' ? 1123 : 794;

            $images = [];
            $webhookUrl = env('N8N_WEBHOOK_TEMPLATE_URL');
            $n8nSuccess = false;
            $source = 'Pollinations (Fallback)';

            // IF N8N WEBHOOK IS CONFIGURED
            if ($webhookUrl) {
                try {
                    $response = \Illuminate\Support\Facades\Http::timeout(30)->post($webhookUrl, [
                        'prompt_description' => $validated['description'],
                        'model' => $validated['model'] ?? 'stabilityai/stable-diffusion-xl-base-1.0',
                        'style' => $validated['style'],
                        'style_keywords' => $stylePrompt,
                        'orientation' => $validated['orientation'],
                        'primary_color' => $validated['primary_color'],
                        'accent_color' => $validated['accent_color'],
                        'variations' => $validated['variations'],
                        'width' => $width,
                        'height' => $height,
                        'seed' => rand(100000, 999999),
                    ]);

                    if (!$response->successful()) {
                        throw new \Exception('Status: ' . $response->status() . ' - ' . $response->body());
                    }

                    $n8nData = $response->json();
                    $rawImages = [];

                    // Flexible parsing for n8n response
                    if (isset($n8nData['images']) && is_array($n8nData['images'])) {
                        $rawImages = $n8nData['images'];
                    } elseif (isset($n8nData['output']) && is_array($n8nData['output'])) {
                        $rawImages = $n8nData['output'];
                    } elseif (isset($n8nData['output']) && is_string($n8nData['output'])) {
                        $rawImages = [$n8nData['output']];
                    } elseif (is_array($n8nData)) {
                        foreach ($n8nData as $key => $val) {
                            if (is_string($val) && (filter_var($val, FILTER_VALIDATE_URL) || preg_match('/^data:image\/(\w+);base64,/', $val))) {
                                $rawImages[] = $val;
                            }
                        }
                    }

                    // Normalize
                    foreach ($rawImages as $img) {
                        if (is_array($img) && isset($img['url'])) {
                            $images[] = $img;
                        } elseif (is_string($img)) {
                            $isBase64 = preg_match('/^data:image\/(\w+);base64,/', $img);
                            $images[] = [
                                'url' => $img,
                                'seed' => null,
                                'prompt' => $isBase64 ? 'Generated by N8N (Base64)' : 'Generated by N8N'
                            ];
                        }
                    }

                    if (!empty($images)) {
                        $n8nSuccess = true;
                        $source = 'N8N AI';
                    }

                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('N8N Webhook Failed, switching to fallback. Error: ' . $e->getMessage());
                    // Proceed to fallback
                }
            }

            // FALLBACK TO POLLINATIONS.AI IF N8N FAILED OR NOT CONFIGURED
            if (!$n8nSuccess) {
                $images = [];

                // Simple style mapping
                $styleMap = [
                    'modern' => 'geometric gradient',
                    'classic' => 'vintage texture',
                    'elegant' => 'luxury golden',
                    'minimalist' => 'simple clean',
                    'futuristic' => 'neon cyberpunk'
                ];

                $style = $styleMap[$validated['style']] ?? 'gradient';
                $layout = $validated['orientation'] === 'landscape' ? 'wide horizontal' : 'tall vertical';

                for ($i = 0; $i < $validated['variations']; $i++) {
                    $seed = rand(100000, 999999);
                    $prompt = "{$style} {$layout} abstract wallpaper background, " .
                        "{$validated['primary_color']} and {$validated['accent_color']} colors, " .
                        "smooth gradient, {$validated['description']}, 8k wallpaper, " .
                        "--no text --no letters --no words --no writing --no frame --no border --no certificate --no document";

                    $encodedPrompt = urlencode($prompt);
                    $imageUrl = "https://image.pollinations.ai/prompt/{$encodedPrompt}?width={$width}&height={$height}&seed={$seed}&model=turbo&nologo=true";

                    $images[] = [
                        'url' => $imageUrl,
                        'seed' => $seed,
                        'prompt' => $prompt
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'images' => $images,
                'message' => 'Template berhasil di-generate! (' . $source . ')'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store selected AI-generated template.
     */
    public function storeAITemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image_url' => 'required', // Removed |url validation to allow Base64
            'orientation' => 'required|string|in:landscape,portrait',
            'style' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();

        try {
            // Determine if Base64 or URL
            $imageContent = null;
            if (preg_match('/^data:image\/(\w+);base64,/', $validated['image_url'], $type)) {
                // Handle Base64
                $data = substr($validated['image_url'], strpos($validated['image_url'], ',') + 1);
                $type = strtolower($type[1]); // jpg, png, etc.

                if (!in_array($type, ['jpg', 'jpeg', 'png', 'webp'])) {
                    throw new \Exception('Tipe gambar tidak didukung (hanya jpg, png, webp)');
                }

                $imageContent = base64_decode($data);

                if ($imageContent === false) {
                    throw new \Exception('Gagal decode gambar Base64');
                }
            } else {
                // Handle URL
                if (!filter_var($validated['image_url'], FILTER_VALIDATE_URL)) {
                    throw new \Exception('Format URL gambar tidak valid');
                }

                $imageContent = file_get_contents($validated['image_url']);

                if ($imageContent === false) {
                    return back()->with('error', 'Gagal mengunduh gambar dari URL.');
                }
            }

            // Generate filename
            $filename = 'templates/' . $user->id . '/' . uniqid('ai_') . '.jpg'; // Convert all to jpg for consistency? Or keep original extension?
            // Let's stick to .jpg for simplicity as per existing code, or detect from base64. 
            // Existing code hardcoded .jpg. Let's keep it but ideally we should convert.
            // For now, simpler is better.

            Storage::disk('local')->makeDirectory('templates/' . $user->id);

            // Save image
            Storage::disk('local')->put($filename, $imageContent);

            // Calculate hashes
            $md5 = md5($imageContent);
            $sha256 = hash('sha256', $imageContent);

            // Set dimensions based on orientation
            $width = $validated['orientation'] === 'portrait' ? 794 : 1123;
            $height = $validated['orientation'] === 'portrait' ? 1123 : 794;

            // Create template record
            $user->templates()->create([
                'name' => $validated['name'],
                'description' => 'AI Generated Template (' . ($validated['style'] ?? 'custom') . ')',
                'file_path' => $filename,
                'thumbnail_path' => $filename, // Use same file for thumbnail for now
                'orientation' => $validated['orientation'],
                'width' => $width,
                'height' => $height,
                'is_active' => true,
                'md5' => $md5,
                'sha256' => $sha256,
            ]);

            return redirect()->route('lembaga.template.index')
                ->with('success', 'Template AI berhasil disimpan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan template: ' . $e->getMessage());
        }
    }

    /**
     * Show the settings page.
     */
    public function settings()
    {
        $user = Auth::user();
        return view('lembaga.pengaturan', compact('user'));
    }

    /**
     * Update profile information.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'institution_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'institution_name', 'phone', 'occupation', 'city']));

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update avatar.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = Auth::user();

        // Delete old avatar if exists and is local file
        if ($user->avatar && str_starts_with($user->avatar, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update([
            'avatar' => '/storage/' . $path,
        ]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Remove avatar.
     */
    public function removeAvatar()
    {
        $user = Auth::user();

        if ($user->avatar && str_starts_with($user->avatar, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
        }

        $user->update(['avatar' => null]);

        return back()->with('success', 'Foto profil berhasil dihapus!');
    }

    /**
     * Download template image (Secure).
     */
    public function downloadTemplateImage(Template $template)
    {
        // Ensure user owns this template
        if ($template->user_id != Auth::id()) {
            abort(403);
        }

        if (!Storage::disk('local')->exists($template->file_path)) {
            abort(404);
        }

        return Storage::disk('local')->response($template->file_path, null, [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    /**
     * Download template thumbnail (Secure).
     */
    public function downloadTemplateThumbnail(Template $template)
    {
        // Ensure user owns this template
        if ($template->user_id != Auth::id()) {
            abort(403);
        }

        $path = $template->thumbnail_path ?? $template->file_path;

        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return Storage::disk('local')->response($path, null, [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!password_verify($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return back()->with('password_success', 'Password berhasil diperbarui!');
    }

    /**
     * Show activity log for the institution.
     */
    public function activityLog(Request $request)
    {
        $user = Auth::user();

        // Get activity logs related to this user
        $logs = ActivityLog::where('user_id', $user->id)
            ->orWhereHasMorph('loggable', [Certificate::class], function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(20);

        return view('lembaga.activity-log', compact('logs'));
    }

    /**
     * Update a document (NPWP, Akta, SIUP).
     */
    public function updateDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:doc_npwp,doc_akta,doc_siup',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $user = Auth::user();
        $documentType = $request->document_type;
        $pathField = $documentType . '_path';

        // Delete old file if exists (Check both disks)
        if ($user->$pathField) {
            if (Storage::disk('local')->exists($user->$pathField)) {
                Storage::disk('local')->delete($user->$pathField);
            } elseif (Storage::disk('public')->exists($user->$pathField)) {
                Storage::disk('public')->delete($user->$pathField);
            }
        }

        // Store new file
        $path = $request->file('document')->store('documents/' . $user->id, 'local');

        // Update user
        $user->update([
            $pathField => $path,
        ]);

        $documentNames = [
            'doc_npwp' => 'NPWP / NIB',
            'doc_akta' => 'Akta Pendirian / SK Lembaga',
            'doc_siup' => 'SIUP / Izin Operasional',
        ];

        return back()->with('success', 'Dokumen ' . $documentNames[$documentType] . ' berhasil diperbarui!');
    }

    /**
     * Delete a document (NPWP, Akta, SIUP).
     */
    public function deleteDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:doc_npwp,doc_akta,doc_siup',
        ]);

        $user = Auth::user();
        $documentType = $request->document_type;
        $pathField = $documentType . '_path';

        // Delete file if exists (Check both disks)
        if ($user->$pathField) {
            if (Storage::disk('local')->exists($user->$pathField)) {
                Storage::disk('local')->delete($user->$pathField);
            } elseif (Storage::disk('public')->exists($user->$pathField)) {
                Storage::disk('public')->delete($user->$pathField);
            }
        }

        // Update user
        $user->update([
            $pathField => null,
        ]);

        $documentNames = [
            'doc_npwp' => 'NPWP / NIB',
            'doc_akta' => 'Akta Pendirian / SK Lembaga',
            'doc_siup' => 'SIUP / Izin Operasional',
        ];

        return back()->with('success', 'Dokumen ' . $documentNames[$documentType] . ' berhasil dihapus!');
    }

    /**
     * Download secure document (NPWP, Akta, SIUP).
     */
    public function downloadDocument($type)
    {
        $validTypes = ['doc_npwp', 'doc_akta', 'doc_siup'];

        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $user = Auth::user();
        $pathField = $type . '_path';
        $path = $user->$pathField;

        if (!$path) {
            abort(404);
        }

        // Check if file exists in local (private) storage
        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->response($path);
        }

        // Check if file exists in public storage (Legacy fallback)
        if (Storage::disk('public')->exists($path)) {
            // OPTIONAL: Move to local storage for better security in future
            try {
                $content = Storage::disk('public')->get($path);
                Storage::disk('local')->put($path, $content);
                Storage::disk('public')->delete($path);

                return Storage::disk('local')->response($path);
            } catch (\Exception $e) {
                // If move fails, just serve from public for now
                return Storage::disk('public')->response($path);
            }
        }

        abort(404);
    }

    /**
     * Delete account (for lembaga users).
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirm_delete' => 'required|in:HAPUS',
        ]);

        $user = Auth::user();

        // Log the account deletion before deleting
        ActivityLog::log(
            'account_deleted',
            'Akun lembaga "' . ($user->institution_name ?? $user->name) . '" telah dihapus oleh pemilik akun',
            null,
            ['user_id' => $user->id]
        );

        // Delete all related data
        // 1. Delete certificates and their files
        foreach ($user->certificates as $certificate) {
            if ($certificate->pdf_path) {
                Storage::disk('public')->delete($certificate->pdf_path);
            }
            if ($certificate->qr_path) {
                Storage::disk('public')->delete($certificate->qr_path);
            }
            $certificate->delete();
        }

        // 2. Delete templates and their files
        foreach ($user->templates as $template) {
            if ($template->image_path) {
                Storage::disk('public')->delete($template->image_path);
            }
            $template->delete();
        }

        // 3. Delete document files
        if ($user->doc_npwp_path) {
            Storage::disk('local')->delete($user->doc_npwp_path);
        }
        if ($user->doc_akta_path) {
            Storage::disk('local')->delete($user->doc_akta_path);
        }
        if ($user->doc_siup_path) {
            Storage::disk('local')->delete($user->doc_siup_path);
        }

        // 4. Delete avatar if exists
        if ($user->avatar && str_starts_with($user->avatar, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun lembaga Anda telah dihapus.');
    }

    /**
     * Revoke a certificate.
     */
    public function revokeCertificate(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // Ensure user owns the certificate (via template/institution relation)
        if ($certificate->user_id != Auth::id()) {
            abort(403);
        }

        $certificate->update([
            'status' => 'revoked',
            'revocation_reason' => $validated['reason'],
        ]);

        // Notify Recipient
        // Notify Recipient
        // Try to find registered user by email
        $recipientUser = \App\Models\User::where('email', $certificate->recipient_email)->first();

        if ($recipientUser) {
            $recipientUser->notify(new \App\Notifications\CertificateRevoked($certificate, $validated['reason']));
        } else {
            // Fallback for non-registered users (Email Only)
            \Illuminate\Support\Facades\Notification::route('mail', $certificate->recipient_email)
                ->notify(new \App\Notifications\CertificateRevoked($certificate, $validated['reason']));
        }

        return back()->with('success', 'Sertifikat berhasil dicabut dan penerima telah diberitahu.');
    }

    /**
     * Reactivate a revoked certificate.
     */
    public function reactivateCertificate(Request $request, Certificate $certificate)
    {
        // Ensure user owns the certificate
        if ($certificate->user_id != Auth::id()) {
            abort(403);
        }

        $certificate->update([
            'status' => 'active',
            'revocation_reason' => null,
        ]);

        // Notify Recipient
        // Try to find registered user by email
        $recipientUser = \App\Models\User::where('email', $certificate->recipient_email)->first();

        if ($recipientUser) {
            $recipientUser->notify(new \App\Notifications\CertificateReactivated($certificate));
        } else {
            // Fallback for non-registered users (Email Only)
            \Illuminate\Support\Facades\Notification::route('mail', $certificate->recipient_email)
                ->notify(new \App\Notifications\CertificateReactivated($certificate));
        }

        return back()->with('success', 'Sertifikat berhasil diaktifkan kembali.');
    }
}
