<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Show checkout page for a package
     */
    public function checkout($slug)
    {
        $package = Package::where('slug', $slug)->where('is_active', true)->firstOrFail();

        // If enterprise package, redirect to contact form
        if ($package->slug === 'enterprise') {
            return redirect()->route('contact.enterprise');
        }

        // Check if user already has this package or higher
        $user = auth()->user();

        \Log::info('Checkout Check', [
            'user_id' => $user->id ?? 'null',
            'user_package_id' => $user->package_id ?? 'null',
            'target_package_slug' => $slug,
            'user_package_slug' => $user && $user->package_id ? Package::find($user->package_id)->slug : 'N/A'
        ]);

        if ($user && $user->package_id) {
            $currentPackage = Package::find($user->package_id);

            // If user already has same or higher package, redirect to dashboard
            if ($currentPackage && $currentPackage->slug === $package->slug) {
                \Log::info('Checkout Redirect: User already has package');
                return redirect()->route('lembaga.dashboard')
                    ->with('info', 'Anda sudah memiliki paket ' . $package->name);
            }

            // If user has professional and trying to buy starter, redirect
            if ($currentPackage && $currentPackage->slug === 'professional' && $package->slug === 'starter') {
                \Log::info('Checkout Redirect: Downgrade attempt');
                return redirect()->route('lembaga.dashboard')
                    ->with('info', 'Anda sudah memiliki paket Professional');
            }
        } else {
            \Log::info('Checkout: User has no package or logic skipped');
        }

        return view('payment.checkout', [
            'package' => $package,
            'clientKey' => config('midtrans.client_key'),
        ]);
    }

    /**
     * Quick upgrade - create order and return Snap token for inline payment
     */
    public function quickUpgrade(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'package_slug' => 'required|string',
        ]);

        $package = Package::where('slug', $validated['package_slug'])
            ->where('is_active', true)
            ->first();

        if (!$package) {
            return response()->json(['error' => 'Paket tidak ditemukan'], 404);
        }

        if ($package->price == 0) {
            return response()->json(['error' => 'Paket gratis tidak perlu pembayaran'], 400);
        }

        // Create pending order
        $order = Order::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'order_number' => Order::generateOrderNumber(),
            'name' => $user->name ?? $user->institution_name,
            'email' => $user->email,
            'phone' => $user->phone ?? $user->admin_phone,
            'institution' => $user->institution_name,
            'amount' => $package->price,
            'status' => 'pending',
            'expired_at' => now()->addHours(24),
        ]);

        // Create Midtrans Snap token
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->amount,
            ],
            'customer_details' => [
                'first_name' => $order->name,
                'email' => $order->email,
                'phone' => $order->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => $package->slug,
                    'price' => (int) $package->price,
                    'quantity' => 1,
                    'name' => 'Upgrade ke ' . $package->name,
                ],
            ],
            'callbacks' => [
                'finish' => route('lembaga.dashboard'),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $order->update(['snap_token' => $snapToken]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_number' => $order->order_number,
                'package_name' => $package->name,
                'amount' => $package->formatted_price,
            ]);
        } catch (\Exception $e) {
            Log::error('Quick Upgrade Midtrans Error: ' . $e->getMessage());

            return response()->json([
                'error' => 'Gagal memproses pembayaran. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Process checkout and create Snap token
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'institution' => 'nullable|string|max:255',
        ]);

        $package = Package::findOrFail($validated['package_id']);

        // For free package, create order and redirect to success
        if ($package->price == 0) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'package_id' => $package->id,
                'order_number' => Order::generateOrderNumber(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'institution' => $validated['institution'] ?? null,
                'amount' => 0,
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            return redirect()->route('payment.success', $order->order_number);
        }

        // Create pending order
        $order = Order::create([
            'user_id' => auth()->id(),
            'package_id' => $package->id,
            'order_number' => Order::generateOrderNumber(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'institution' => $validated['institution'] ?? null,
            'amount' => $package->price,
            'status' => 'pending',
            'expired_at' => now()->addHours(24),
        ]);

        // Create Midtrans Snap token
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->amount,
            ],
            'customer_details' => [
                'first_name' => $order->name,
                'email' => $order->email,
                'phone' => $order->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => $package->slug,
                    'price' => (int) $package->price,
                    'quantity' => 1,
                    'name' => $package->name . ' - Paket Bulanan',
                ],
            ],
            'callbacks' => [
                'finish' => route('payment.success', $order->order_number),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $order->update(['snap_token' => $snapToken]);

            return response()->json([
                'snap_token' => $snapToken,
                'order_number' => $order->order_number,
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage());

            return response()->json([
                'error' => 'Gagal memproses pembayaran. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Handle Midtrans callback/notification
     */
    public function callback(Request $request)
    {
        try {
            $notification = new Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $paymentType = $notification->payment_type;
            $fraudStatus = $notification->fraud_status ?? null;

            $order = Order::where('order_number', $orderId)->first();

            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            if ($transactionStatus == 'capture') {
                if ($paymentType == 'credit_card') {
                    if ($fraudStatus == 'challenge') {
                        $order->update(['status' => 'pending']);
                    } else {
                        $order->markAsPaid($paymentType, $notification->transaction_id);
                    }
                }
            } elseif ($transactionStatus == 'settlement') {
                $order->markAsPaid($paymentType, $notification->transaction_id);
            } elseif ($transactionStatus == 'pending') {
                $order->update(['status' => 'pending']);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $order->update(['status' => $transactionStatus === 'expire' ? 'expired' : 'cancelled']);
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

    /**
     * Confirm payment from client-side (for sandbox/localhost where callback doesn't work)
     */
    public function confirmPayment(Request $request)
    {
        \Log::info('confirmPayment called', $request->all());

        $validated = $request->validate([
            'order_number' => 'required|string',
        ]);

        $order = Order::where('order_number', $validated['order_number'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$order) {
            \Log::info('confirmPayment: Order not found', ['order_number' => $validated['order_number'], 'user_id' => auth()->id()]);
            return response()->json(['error' => 'Order not found'], 404);
        }

        \Log::info('confirmPayment: Order found', ['order_number' => $order->order_number, 'status' => $order->status]);

        // Only process if order is still pending
        if ($order->status === 'pending') {
            \Log::info('confirmPayment: Marking as paid');
            $order->markAsPaid('snap', 'client-confirmed-' . now()->timestamp);
        } else {
            \Log::info('confirmPayment: Order already ' . $order->status);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment confirmed',
            'package_name' => $order->package->name ?? 'Unknown',
        ]);
    }

    /**
     * Payment success page
     */
    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('package')->firstOrFail();

        // If order is paid and WA not yet sent, send it now (backup mechanism)
        if ($order->status === 'paid' && !$order->wa_sent && $order->phone) {
            try {
                $fonnte = new \App\Services\FonnteService();
                if ($fonnte->isConfigured()) {
                    $result = $fonnte->sendInvoice(
                        phone: $order->phone,
                        customerName: $order->name,
                        orderNumber: $order->order_number,
                        packageName: $order->package->name,
                        amount: (int) $order->amount,
                        paymentDate: $order->paid_at ? $order->paid_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i')
                    );

                    if ($result['success']) {
                        $order->update(['wa_sent' => true]);
                        \Log::info('WA Invoice sent via success page', ['order' => $orderNumber]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('WA Invoice error on success page: ' . $e->getMessage());
            }
        }

        return view('payment.success', compact('order'));
    }

    /**
     * Contact enterprise form
     */
    public function contactEnterprise()
    {
        return view('payment.contact-enterprise');
    }

    /**
     * Send enterprise contact
     */
    public function sendContactEnterprise(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'institution' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Here you would typically send an email or store in database
        // For now, we'll just redirect with success message

        return redirect()->route('contact.enterprise')
            ->with('success', 'Terima kasih! Tim kami akan segera menghubungi Anda.');
    }
}
