<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'template_id',
        'recipient_name',
        'recipient_email',
        'course_name',
        'category',
        'description',
        'issue_date',
        'expire_date',
        'certificate_number',
        'hash',
        'qr_code_path',
        'pdf_path',
        'image_path',
        'status',
        'revoked_at',
        'revoked_reason',
        // Blockchain fields
        'blockchain_enabled',
        'blockchain_tx_hash',
        'blockchain_hash',
        'blockchain_verified_at',
        'blockchain_status',
        // IPFS fields
        'ipfs_cid',
        'ipfs_metadata_cid',
        'ipfs_url',
        'ipfs_uploaded_at',
        // File hash fields
        'certificate_sha256',
        'certificate_md5',
        'qr_sha256',
        'qr_md5',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expire_date' => 'date',
        'revoked_at' => 'datetime',
        'blockchain_enabled' => 'boolean',
        'blockchain_verified_at' => 'datetime',
        'ipfs_uploaded_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($certificate) {
            // Generate certificate number if not set
            if (!$certificate->certificate_number) {
                $certificate->certificate_number = self::generateCertificateNumber();
            }

            // Generate hash if not set
            if (!$certificate->hash) {
                $certificate->hash = self::generateHash();
            }
        });
    }

    /**
     * Get the issuer (user/institution).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias for user() - the issuer.
     */
    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the template used.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Generate a unique certificate number.
     */
    public static function generateCertificateNumber(): string
    {
        $prefix = 'SERT';
        $year = date('Y');
        $month = date('m');
        $random = strtoupper(Str::random(6));

        return "{$prefix}-{$year}{$month}-{$random}";
    }

    /**
     * Generate a unique hash for verification.
     */
    public static function generateHash(): string
    {
        return Str::uuid()->toString();
    }

    /**
     * Check if certificate is valid (active and not expired).
     */
    public function isValid(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->expire_date && $this->expire_date->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Revoke this certificate.
     */
    public function revoke(string $reason = null): void
    {
        $this->update([
            'status' => 'revoked',
            'revoked_at' => now(),
            'revoked_reason' => $reason,
        ]);
    }

    /**
     * Get verification URL.
     */
    public function getVerificationUrlAttribute(): string
    {
        return url('/verifikasi/' . $this->hash);
    }

    /**
     * Get the full URL to the PDF file.
     */
    public function getPdfUrlAttribute(): ?string
    {
        return $this->pdf_path ? asset('storage/' . $this->pdf_path) : null;
    }

    /**
     * Get the full URL to the QR code image.
     */
    public function getQrCodeUrlAttribute(): ?string
    {
        return $this->qr_code_path ? asset('storage/' . $this->qr_code_path) : null;
    }

    /**
     * Check if certificate is stored on blockchain.
     */
    public function isOnBlockchain(): bool
    {
        return $this->blockchain_enabled
            && !empty($this->blockchain_tx_hash)
            && $this->blockchain_status === 'confirmed';
    }

    /**
     * Get blockchain explorer URL for the transaction.
     */
    public function getBlockchainExplorerUrlAttribute(): ?string
    {
        if (empty($this->blockchain_tx_hash)) {
            return null;
        }

        $explorerUrl = config('blockchain.explorer_url', 'https://amoy.polygonscan.com');
        return "{$explorerUrl}/tx/{$this->blockchain_tx_hash}";
    }

    /**
     * Get blockchain status badge class.
     */
    public function getBlockchainBadgeClass(): string
    {
        return match ($this->blockchain_status) {
            'confirmed' => 'bg-emerald-500/20 text-emerald-400',
            'pending' => 'bg-yellow-500/20 text-yellow-400',
            'failed' => 'bg-red-500/20 text-red-400',
            default => 'bg-gray-500/20 text-gray-400',
        };
    }

    /**
     * Get blockchain status text.
     */
    public function getBlockchainStatusText(): string
    {
        return match ($this->blockchain_status) {
            'confirmed' => 'Terverifikasi di Blockchain',
            'pending' => 'Menunggu Konfirmasi',
            'failed' => 'Gagal Upload ke Blockchain',
            default => 'Tidak di Blockchain',
        };
    }

    /**
     * Check if certificate is stored on IPFS.
     */
    public function isOnIpfs(): bool
    {
        return !empty($this->ipfs_cid);
    }

    /**
     * Get IPFS gateway URL for the certificate.
     */
    public function getIpfsGatewayUrlAttribute(): ?string
    {
        if (empty($this->ipfs_cid)) {
            return null;
        }
        return config('ipfs.gateway_url', 'https://w3s.link/ipfs') . '/' . $this->ipfs_cid;
    }

    /**
     * Get IPFS status badge class.
     */
    public function getIpfsBadgeClass(): string
    {
        return $this->isOnIpfs()
            ? 'bg-purple-500/20 text-purple-400'
            : 'bg-gray-500/20 text-gray-400';
    }

    /**
     * Get IPFS status text.
     */
    public function getIpfsStatusText(): string
    {
        return $this->isOnIpfs()
            ? 'Tersimpan di IPFS'
            : 'Tidak di IPFS';
    }

    /**
     * Generate and save QR code for this certificate.
     * The QR contains verification URL with hash.
     */
    public function generateQrCode(): string
    {
        // Create qrcodes directory if not exists
        $directory = 'certificates/qrcodes';
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Generate filename based on certificate number (using SVG format - no imagick needed)
        $filename = $directory . '/' . $this->certificate_number . '.svg';

        // Generate QR code with verification URL
        $verificationUrl = $this->verification_url;

        // Use SVG format which doesn't require imagick extension
        $qrCode = QrCode::size(300)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($verificationUrl);

        // Save QR code to storage
        Storage::disk('public')->put($filename, $qrCode);

        // Update certificate with QR code path
        $this->update(['qr_code_path' => $filename]);

        return $filename;
    }

    /**
     * Find certificate by hash.
     */
    public static function findByHash(string $hash): ?self
    {
        return self::where('hash', $hash)->first();
    }

    /**
     * Find certificate by certificate number.
     */
    public static function findByNumber(string $number): ?self
    {
        return self::where('certificate_number', $number)->first();
    }

    /**
     * Scope for active certificates.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for current month's certificates.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    /**
     * Generate SHA256 and MD5 hashes for certificate and QR code files.
     * Returns array of generated hashes.
     */
    public function generateFileHashes(): array
    {
        $hashes = [];

        // Generate hashes for certificate PDF/image if exists
        // Generate hashes for certificate PDF/image if exists
        $certificatePath = $this->pdf_path ?? $this->image_path;

        if ($certificatePath && Storage::disk('public')->exists($certificatePath)) {
            $content = Storage::disk('public')->get($certificatePath);
            $hashes['certificate_sha256'] = hash('sha256', $content);
            $hashes['certificate_md5'] = md5($content);
        } elseif ($this->template_id && $this->template) {
            // If no custom file but using template, use template's hashes
            if ($this->template->sha256) {
                $hashes['certificate_sha256'] = $this->template->sha256;
            }
            if ($this->template->md5) {
                $hashes['certificate_md5'] = $this->template->md5;
            }
        }

        // Generate hashes for QR code if exists
        if ($this->qr_code_path && Storage::disk('public')->exists($this->qr_code_path)) {
            $qrContent = Storage::disk('public')->get($this->qr_code_path);
            $hashes['qr_sha256'] = hash('sha256', $qrContent);
            $hashes['qr_md5'] = md5($qrContent);
        }

        // Update certificate with hashes
        if (!empty($hashes)) {
            $this->update($hashes);
        }

        return $hashes;
    }

    /**
     * Get all file hashes as array (for blockchain/IPFS metadata).
     */
    public function getFileHashes(): array
    {
        return [
            'certificate' => [
                'sha256' => $this->certificate_sha256,
                'md5' => $this->certificate_md5,
            ],
            'qr_code' => [
                'sha256' => $this->qr_sha256,
                'md5' => $this->qr_md5,
            ],
        ];
    }
}
