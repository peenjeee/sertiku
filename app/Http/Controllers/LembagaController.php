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
        $templates = $user->templates()->where('is_active', true)->get();

        return view('lembaga.sertifikat.create', compact('templates'));
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
        $validated['blockchain_enabled'] = $request->has('blockchain_enabled') && $request->blockchain_enabled == '1';

        // Get IPFS enabled flag (not stored in database, triggers job)
        $ipfsEnabled = $request->has('ipfs_enabled') && $request->ipfs_enabled == '1';

        // Get send_email flag (not stored in database)
        $sendEmail = $request->has('send_email') && $request->send_email == '1';

        // Remove non-database fields from validated data
        unset($validated['send_email']);
        unset($validated['ipfs_enabled']);

        // Create certificate
        $certificate = $user->certificates()->create($validated);

        // Generate QR code for the certificate
        $certificate->generateQrCode();

        // Generate PDF for the certificate (Critical for File Verification)
        try {
            $certificate->generatePdf();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to generate PDF (continuing without it): " . $e->getMessage());
            // Continue without PDF, but verifying file integrity will fail for uploaded files
        }

        // Generate file hashes (SHA256/MD5) for certificate and QR
        $certificate->generateFileHashes();

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
            Mail::to($validated['recipient_email'])
                ->queue(new CertificateIssuedMail($certificate));
        }

        // Send in-app notification to recipient if they have an account
        if (!empty($validated['recipient_email'])) {
            $recipient = \App\Models\User::where('email', $validated['recipient_email'])->first();
            if ($recipient) {
                // Use queue to send notification in background
                $recipient->notify((new \App\Notifications\CertificateReceived($certificate))->delay(now()->addSeconds(5)));
            }
        }

        // If blockchain upload requested, dispatch job to process in background
        // IPFS will be triggered AFTER blockchain confirms (inside the job)
        if ($validated['blockchain_enabled']) {
            $blockchainService = new \App\Services\BlockchainService();

            if ($blockchainService->isEnabled()) {
                // Mark as pending and dispatch background job
                $certificate->update([
                    'blockchain_status' => 'pending',
                ]);

                // Dispatch job to process blockchain in background
                // Pass ipfsEnabled so IPFS is only dispatched if user requested it
                \App\Jobs\ProcessBlockchainCertificate::dispatch($certificate, $ipfsEnabled);
            } else {
                // Blockchain not configured
                $certificate->update([
                    'blockchain_status' => 'disabled',
                ]);
            }
        } elseif ($ipfsEnabled) {
            // IPFS only (no blockchain) - upload directly without waiting for blockchain
            $ipfsService = new \App\Services\IpfsService();
            if ($ipfsService->isEnabled()) {
                \App\Jobs\ProcessIpfsCertificate::dispatch($certificate);
            }
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
                    ->orWhere('course_name', 'like', "%{$search}%")
                    ->orWhere('certificate_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $certificates = $query->latest()->paginate(12);

        // Get stats
        $stats = [
            'total' => $user->certificates()->count(),
            'active' => $user->certificates()->where('status', 'active')->count(),
            'revoked' => $user->certificates()->where('status', 'revoked')->count(),
        ];

        return view('lembaga.sertifikat.index', compact('certificates', 'stats'));
    }

    /**
     * Show a single certificate.
     */
    public function showSertifikat(Certificate $certificate)
    {
        // Ensure user owns this certificate
        if ($certificate->user_id !== Auth::id()) {
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
        // Ensure user owns this certificate
        if ((int) $certificate->user_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized access to this certificate.');
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $certificate->revoke($validated['reason'] ?? null);

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

        return back()->with('success', 'Sertifikat berhasil diaktifkan kembali.');
    }

    /**
     * Show the template gallery.
     */
    public function indexTemplate()
    {
        $user = Auth::user();
        $templates = $user->templates()->latest()->paginate(12);

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
            'template_file' => 'required|file|mimes:png,jpg,jpeg,pdf|max:10240', // Max 10MB
            'orientation' => 'required|in:landscape,portrait',
        ]);

        $user = Auth::user();

        // Store the template file
        $file = $request->file('template_file');
        $path = $file->store('templates/' . $user->id, 'public');

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
        ]);

        return redirect()->route('lembaga.template.index')
            ->with('success', 'Template berhasil diupload!');
    }

    /**
     * Delete a template.
     */
    public function destroyTemplate(Template $template)
    {
        // Ensure user owns this template
        if ($template->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete file from storage
        if ($template->file_path) {
            Storage::disk('public')->delete($template->file_path);
        }
        if ($template->thumbnail_path && $template->thumbnail_path !== $template->file_path) {
            Storage::disk('public')->delete($template->thumbnail_path);
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
        if ($template->user_id !== Auth::id()) {
            abort(403);
        }

        $template->update(['is_active' => !$template->is_active]);

        $status = $template->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Template berhasil {$status}.");
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
}
