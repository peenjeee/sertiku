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
    ];

    protected $casts = [
        'issue_date'  => 'date',
        'expire_date' => 'date',
        'revoked_at'  => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($certificate) {
            // Generate certificate number if not set
            if (! $certificate->certificate_number) {
                $certificate->certificate_number = self::generateCertificateNumber();
            }

            // Generate hash if not set
            if (! $certificate->hash) {
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
        $year   = date('Y');
        $month  = date('m');
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
            'status'         => 'revoked',
            'revoked_at'     => now(),
            'revoked_reason' => $reason,
        ]);
    }

    /**
     * Get verification URL.
     */
    public function getVerificationUrlAttribute(): string
    {
        return url('/verifikasi?hash=' . $this->hash);
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
     * Generate and save QR code for this certificate.
     * The QR contains verification URL with hash.
     */
    public function generateQrCode(): string
    {
        // Create qrcodes directory if not exists
        $directory = 'qrcodes';
        if (! Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Generate filename based on certificate number
        $filename = $directory . '/' . $this->certificate_number . '.png';

        // Generate QR code with verification URL
        $verificationUrl = $this->verification_url;

        $qrCode = QrCode::format('png')
            ->size(300)
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
}
