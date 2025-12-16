<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'otp',
        'expires_at',
        'verified_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the user that owns this verification
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if already verified
     */
    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    /**
     * Generate a new OTP for user
     */
    public static function generateOtp(User $user, ?string $email = null): self
    {
        // Delete old unverified OTPs for this user
        self::where('user_id', $user->id)
            ->whereNull('verified_at')
            ->delete();

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return self::create([
            'user_id' => $user->id,
            'email' => $email ?? $user->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10), // 10 minutes expiry
        ]);
    }

    /**
     * Verify OTP for user
     */
    public static function verifyOtp(User $user, string $otp): bool
    {
        $verification = self::where('user_id', $user->id)
            ->where('otp', $otp)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return false;
        }

        // Mark as verified
        $verification->update(['verified_at' => now()]);

        // Also verify user's email
        $user->update(['email_verified_at' => now()]);

        return true;
    }
}
