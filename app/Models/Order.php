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

        // Update user's package to the purchased package
        if ($this->user && $this->package_id) {
            $this->user->update([
                'package_id' => $this->package_id,
            ]);
        }

        // Send WhatsApp invoice if phone number exists
        $this->sendWhatsAppInvoice();
    }

    /**
     * Send invoice to WhatsApp via Fonnte
     */
    protected function sendWhatsAppInvoice(): void
    {
        \Illuminate\Support\Facades\Log::info('sendWhatsAppInvoice called', [
            'order_number' => $this->order_number,
            'phone' => $this->phone,
        ]);

        // Check if phone number exists
        if (empty($this->phone)) {
            \Illuminate\Support\Facades\Log::info('sendWhatsAppInvoice: No phone number');
            return;
        }

        // Check if package exists
        if (!$this->package) {
            \Illuminate\Support\Facades\Log::info('sendWhatsAppInvoice: No package');
            return;
        }

        try {
            $fonnte = new \App\Services\FonnteService();

            if (!$fonnte->isConfigured()) {
                \Illuminate\Support\Facades\Log::info('sendWhatsAppInvoice: Fonnte not configured');
                return;
            }

            \Illuminate\Support\Facades\Log::info('sendWhatsAppInvoice: Sending to ' . $this->phone);

            $result = $fonnte->sendInvoice(
                phone: $this->phone,
                customerName: $this->name,
                orderNumber: $this->order_number,
                packageName: $this->package->name,
                amount: (int) $this->amount,
                paymentDate: $this->paid_at->format('d/m/Y H:i')
            );

            \Illuminate\Support\Facades\Log::info('sendWhatsAppInvoice result', $result);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send WhatsApp invoice: ' . $e->getMessage());
        }
    }
}
