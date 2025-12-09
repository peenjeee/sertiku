<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
        return $this->account_type === 'personal';
    }

    /**
     * Check if user is an institution account
     */
    public function isInstitution(): bool
    {
        return $this->account_type === 'institution';
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
