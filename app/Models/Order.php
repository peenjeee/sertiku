<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'order_number',
        'name',
        'email',
        'phone',
        'institution',
        'amount',
        'status',
        'snap_token',
        'payment_type',
        'transaction_id',
        'notes',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -5));
        return "{$prefix}{$date}{$random}";
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function markAsPaid($paymentType = null, $transactionId = null)
    {
        $this->update([
            'status' => 'paid',
            'payment_type' => $paymentType,
            'transaction_id' => $transactionId,
            'paid_at' => now(),
        ]);
    }
}
