<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'price_label',
        'description',
        'features',
        'certificates_limit',
        'blockchain_limit',
        'ipfs_limit',
        'is_popular',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getFormattedPriceAttribute()
    {
        if ($this->price_label) {
            return $this->price_label;
        }

        if ($this->price == 0) {
            return 'Gratis';
        }

        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
