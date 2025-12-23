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
        'province',
        'postal_code',
        'country',
        'admin_name',
        'admin_phone',
        'admin_position',
        'wallet_address',
        'profile_completed',
        'package_id',
        'is_admin',
        'is_master',
        'email_verified_at',
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
     * Returns Starter package if no paid subscription
     */
    public function getActivePackage()
    {
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
     * Get certificate limit for current package
     */
    public function getCertificateLimit(): int
    {
        $package = $this->getActivePackage();
        return $package ? $package->certificates_limit : 67;
    }

    /**
     * Get certificates issued this month (dummy for now)
     */
    public function getCertificatesUsedThisMonth(): int
    {
        return $this->certificates()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    /**
     * Get remaining certificates this month
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
