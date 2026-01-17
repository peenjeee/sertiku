<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordQueued($token));
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'account_type',
        'phone',
        'occupation',
        'user_institution',
        'institution_name',
        'institution_type',
        'sector',
        'website',
        'description',
        'address_line',
        'city',
        'district',
        'village',
        'province',
        'postal_code',
        'country',
        'admin_name',
        'admin_phone',
        'admin_position',
        'wallet_address',
        'profile_completed',
        'package_id',
        'subscription_expires_at',
        'is_admin',
        'is_master',
        'email_verified_at',
        'doc_npwp_path',
        'doc_akta_path',
        'doc_siup_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'profile_completed' => 'boolean',
            'subscription_expires_at' => 'datetime',
        ];
    }

    /**
     * Get user's orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get user's templates
     */
    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    /**
     * Get user's issued certificates
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Get the active package for this user
     * Returns Starter package if subscription expired or no paid subscription
     */
    public function getActivePackage()
    {
        // If subscription has expired, return Starter package
        if ($this->isSubscriptionExpired()) {
            return Package::where('slug', 'starter')->first();
        }

        // Check for active paid order
        $activeOrder = $this->orders()
            ->where('status', 'paid')
            ->whereHas('package', function ($q) {
                $q->where('slug', '!=', 'starter');
            })
            ->latest('paid_at')
            ->first();

        if ($activeOrder) {
            return $activeOrder->package;
        }

        // Default to Starter package
        return Package::where('slug', 'starter')->first();
    }

    /**
     * Check if user's subscription has expired
     */
    public function isSubscriptionExpired(): bool
    {
        // If no expiration date set or user is on starter, not expired
        if (!$this->subscription_expires_at) {
            return false;
        }

        return $this->subscription_expires_at->isPast();
    }

    /**
     * Check if subscription is expiring within 7 days
     */
    public function isSubscriptionExpiringSoon(): bool
    {
        if (!$this->subscription_expires_at || $this->isSubscriptionExpired()) {
            return false;
        }

        return $this->subscription_expires_at->diffInDays(now()) <= 7;
    }

    /**
     * Get remaining days until subscription expires
     */
    public function getSubscriptionDaysRemaining(): ?int
    {
        if (!$this->subscription_expires_at) {
            return null;
        }

        if ($this->isSubscriptionExpired()) {
            return 0;
        }

        return (int) now()->diffInDays($this->subscription_expires_at);
    }

    /**
     * Get certificate limit for current package
     */
    public function getCertificateLimit(): int
    {
        $package = $this->getActivePackage();
        return $package ? $package->certificates_limit : 50;
    }

    /**
     * Get the start date of the current 30-day billing cycle
     * Based on email_verified_at, cycles every 30 days
     */
    public function getBillingCycleStart(): ?\Carbon\Carbon
    {
        $verifiedAt = $this->email_verified_at;
        if (!$verifiedAt) {
            // If not verified, use created_at as fallback
            $verifiedAt = $this->created_at;
        }
        if (!$verifiedAt)
            return null;

        $daysSinceVerification = $verifiedAt->diffInDays(now());
        $completedCycles = floor($daysSinceVerification / 30);

        return $verifiedAt->copy()->addDays($completedCycles * 30);
    }

    /**
     * Get days remaining in current billing cycle
     */
    public function getDaysRemainingInCycle(): int
    {
        $cycleStart = $this->getBillingCycleStart();
        if (!$cycleStart)
            return 30;

        $cycleEnd = $cycleStart->copy()->addDays(30);
        return max(0, (int) now()->diffInDays($cycleEnd, false));
    }

    /**
     * Get certificates issued in current billing cycle
     * If subscription expired, reset counter (only count from expiration date)
     */
    public function getCertificatesUsedThisMonth(): int
    {
        // If subscription has expired, reset counter - only count certificates created AFTER expiration
        if ($this->isSubscriptionExpired() && $this->subscription_expires_at) {
            return $this->certificates()
                ->where('created_at', '>=', $this->subscription_expires_at)
                ->count();
        }

        $cycleStart = $this->getBillingCycleStart();
        if (!$cycleStart) {
            // Fallback to calendar month if no cycle start
            return $this->certificates()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        }

        return $this->certificates()
            ->where('created_at', '>=', $cycleStart)
            ->count();
    }

    /**
     * Get remaining certificates in current billing cycle
     */
    public function getRemainingCertificates(): int
    {
        $limit = $this->getCertificateLimit();
        if ($limit === 0) {
            return PHP_INT_MAX; // Unlimited
        }
        return max(0, $limit - $this->getCertificatesUsedThisMonth());
    }

    /**
     * Check if user can issue more certificates
     */
    public function canIssueCertificate(): bool
    {
        $limit = $this->getCertificateLimit();
        if ($limit === 0) {
            return true; // Unlimited
        }
        return $this->getCertificatesUsedThisMonth() < $limit;
    }

    /**
     * Check if user is on Starter plan
     */
    public function isStarterPlan(): bool
    {
        $package = $this->getActivePackage();
        return $package && $package->slug === 'starter';
    }

    /**
     * Check if user is on Professional plan
     */
    public function isProfessionalPlan(): bool
    {
        $package = $this->getActivePackage();
        return $package && $package->slug === 'professional';
    }

    /**
     * Check if user is on Enterprise plan
     */
    public function isEnterprisePlan(): bool
    {
        $package = $this->getActivePackage();
        return $package && $package->slug === 'enterprise';
    }

    /**
     * Get package usage percentage
     */
    public function getUsagePercentage(): int
    {
        $limit = $this->getCertificateLimit();
        if ($limit === 0) {
            return 0; // Unlimited
        }
        return min(100, round(($this->getCertificatesUsedThisMonth() / $limit) * 100));
    }

    /**
     * Get blockchain limit for current package
     */
    public function getBlockchainLimit(): int
    {
        $package = $this->getActivePackage();
        return $package ? ($package->blockchain_limit ?? 0) : 0;
    }

    /**
     * Get IPFS limit for current package
     */
    public function getIpfsLimit(): int
    {
        $package = $this->getActivePackage();
        return $package ? ($package->ipfs_limit ?? 0) : 50;
    }

    /**
     * Get blockchain usage in current billing cycle
     * Resets when subscription expires
     */
    public function getBlockchainUsedThisMonth(): int
    {
        // If subscription has expired, reset counter - only count after expiration
        if ($this->isSubscriptionExpired() && $this->subscription_expires_at) {
            return $this->certificates()
                ->where('created_at', '>=', $this->subscription_expires_at)
                ->whereIn('blockchain_status', ['confirmed', 'pending', 'processing'])
                ->count();
        }

        $cycleStart = $this->getBillingCycleStart();
        if (!$cycleStart) {
            return $this->certificates()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->whereIn('blockchain_status', ['confirmed', 'pending', 'processing'])
                ->count();
        }

        return $this->certificates()
            ->where('created_at', '>=', $cycleStart)
            ->whereIn('blockchain_status', ['confirmed', 'pending', 'processing'])
            ->count();
    }

    /**
     * Get IPFS usage in current billing cycle
     * Resets when subscription expires
     */
    public function getIpfsUsedThisMonth(): int
    {
        // If subscription has expired, reset counter - only count after expiration
        if ($this->isSubscriptionExpired() && $this->subscription_expires_at) {
            return $this->certificates()
                ->where('created_at', '>=', $this->subscription_expires_at)
                ->whereIn('ipfs_status', ['pending', 'processing', 'success'])
                ->count();
        }

        $cycleStart = $this->getBillingCycleStart();
        if (!$cycleStart) {
            return $this->certificates()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->whereIn('ipfs_status', ['pending', 'processing', 'success'])
                ->count();
        }

        return $this->certificates()
            ->where('created_at', '>=', $cycleStart)
            ->whereIn('ipfs_status', ['pending', 'processing', 'success'])
            ->count();
    }

    /**
     * Get remaining blockchain transactions this month
     */
    public function getRemainingBlockchain(): int
    {
        $limit = $this->getBlockchainLimit();
        return max(0, $limit - $this->getBlockchainUsedThisMonth());
    }

    /**
     * Get remaining IPFS uploads this month
     */
    public function getRemainingIpfs(): int
    {
        $limit = $this->getIpfsLimit();
        return max(0, $limit - $this->getIpfsUsedThisMonth());
    }

    /**
     * Check if user can use blockchain feature
     */
    public function canUseBlockchain(): bool
    {
        $limit = $this->getBlockchainLimit();
        return $this->getBlockchainUsedThisMonth() < $limit;
    }

    /**
     * Check if user can use IPFS feature
     */
    public function canUseIpfs(): bool
    {
        $limit = $this->getIpfsLimit();
        return $this->getIpfsUsedThisMonth() < $limit;
    }

    /**
     * Check if user profile is completed
     */
    public function isProfileCompleted(): bool
    {
        return $this->profile_completed === true;
    }

    /**
     * Check if user is a personal account
     */
    public function isPersonal(): bool
    {
        return in_array($this->account_type, ['personal', 'pengguna']);
    }

    /**
     * Check if user is an institution account
     */
    public function isInstitution(): bool
    {
        return in_array($this->account_type, ['lembaga', 'institution']);
    }

    /**
     * Check if user is a master
     */
    public function isMaster(): bool
    {
        return $this->is_master === true;
    }

    /**
     * Check if user has Google login
     */
    public function hasGoogleLogin(): bool
    {
        return !empty($this->google_id);
    }

    /**
     * Check if user has wallet login
     */
    public function hasWalletLogin(): bool
    {
        return !empty($this->wallet_address);
    }

    /**
     * Check if user has password (email/password login)
     */
    public function hasPasswordLogin(): bool
    {
        return !empty($this->password);
    }
}
