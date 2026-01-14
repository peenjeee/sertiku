<x-layouts.app title="Checkout - SertiKu">

    <section class="mx-auto max-w-4xl px-4 py-16 lg:px-0">
        <div class="grid gap-8 lg:grid-cols-5">

            {{-- Form Checkout --}}
            <div class="lg:col-span-3">
                <div class="rounded-[28px] border border-[rgba(255,255,255,0.14)] bg-[rgba(15,23,42,0.9)] p-6 md:p-8">
                    <h1 class="text-2xl font-semibold text-white">Checkout</h1>
                    <p class="mt-2 text-sm text-[rgba(190,219,255,0.7)]">
                        Lengkapi data berikut untuk melanjutkan pembayaran.
                    </p>

                    <form id="checkout-form" class="mt-8 space-y-5">
                        @csrf
                        <input type="hidden" name="package_id" value="{{ $package->id }}">

                        {{-- Nama --}}
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-[rgba(219,234,254,0.9)]">
                                Nama Lengkap <span class="text-red-400">*</span>
                            </label>
                            <input type="text" id="name" name="name" required
                                class="w-full rounded-[12px] border border-[rgba(255,255,255,0.12)] bg-[rgba(15,23,42,0.6)] px-4 py-3 text-sm text-white placeholder:text-[rgba(190,219,255,0.5)] focus:border-[#3B82F6] focus:outline-none focus:ring-2 focus:ring-[#3B82F6]/30"
                                placeholder="Masukkan nama lengkap">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-[rgba(219,234,254,0.9)]">
                                Email <span class="text-red-400">*</span>
                            </label>
                            <input type="email" id="email" name="email" required
                                class="w-full rounded-[12px] border border-[rgba(255,255,255,0.12)] bg-[rgba(15,23,42,0.6)] px-4 py-3 text-sm text-white placeholder:text-[rgba(190,219,255,0.5)] focus:border-[#3B82F6] focus:outline-none focus:ring-2 focus:ring-[#3B82F6]/30"
                                placeholder="email@contoh.com">
                        </div>

                        {{-- Telepon --}}
                        <div>
                            <label for="phone" class="mb-2 block text-sm font-medium text-[rgba(219,234,254,0.9)]">
                                Nomor Telepon <span class="text-red-400">*</span>
                            </label>
                            <input type="tel" id="phone" name="phone" required
                                class="w-full rounded-[12px] border border-[rgba(255,255,255,0.12)] bg-[rgba(15,23,42,0.6)] px-4 py-3 text-sm text-white placeholder:text-[rgba(190,219,255,0.5)] focus:border-[#3B82F6] focus:outline-none focus:ring-2 focus:ring-[#3B82F6]/30"
                                placeholder="08xxxxxxxxxx">
                        </div>

                        {{-- Institusi --}}
                        <div>
                            <label for="institution"
                                class="mb-2 block text-sm font-medium text-[rgba(219,234,254,0.9)]">
                                Nama Institusi/Lembaga <span class="text-red-400">*</span>
                            </label>
                            <input type="text" id="institution" name="institution" required
                                class="w-full rounded-[12px] border border-[rgba(255,255,255,0.12)] bg-[rgba(15,23,42,0.6)] px-4 py-3 text-sm text-white placeholder:text-[rgba(190,219,255,0.5)] focus:border-[#3B82F6] focus:outline-none focus:ring-2 focus:ring-[#3B82F6]/30"
                                placeholder="Nama universitas/organisasi">
                        </div>

                        {{-- Payment Method Selection --}}
                        <div>
                            <label class="mb-3 block text-sm font-medium text-[rgba(219,234,254,0.9)]">
                                Metode Pembayaran
                            </label>
                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                {{-- Midtrans --}}
                                <label
                                    class="relative flex cursor-pointer rounded-[12px] border border-[rgba(255,255,255,0.12)] bg-[rgba(15,23,42,0.6)] p-4 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] hover:bg-[rgba(15,23,42,0.8)] transition">
                                    <input type="radio" name="payment_method" value="midtrans" class="peer sr-only"
                                        checked>
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-white">Transfer & QRIS</span>
                                            <span
                                                class="mt-1 flex items-center text-xs text-[rgba(190,219,255,0.7)]">Virtual
                                                Account, QRIS, E-Wallet</span>
                                        </span>
                                    </span>
                                    <svg class="h-5 w-5 text-blue-500 opacity-0 peer-checked:opacity-100"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span
                                        class="pointer-events-none absolute -inset-px rounded-[12px] border-2 border-transparent peer-checked:border-blue-500"
                                        aria-hidden="true"></span>
                                </label>

                                {{-- Crypto --}}
                                <!-- <label
                                    class="relative flex cursor-pointer rounded-[12px] border border-[rgba(255,255,255,0.12)] bg-[rgba(15,23,42,0.6)] p-4 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] hover:bg-[rgba(15,23,42,0.8)] transition">
                                    <input type="radio" name="payment_method" value="crypto" class="peer sr-only">
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-white">Cryptocurrency</span>
                                            <span
                                                class="mt-1 flex items-center text-xs text-[rgba(190,219,255,0.7)]">BTC,
                                                ETH, USDT, SOL</span>
                                        </span>
                                    </span>
                                    <svg class="h-5 w-5 text-blue-500 opacity-0 peer-checked:opacity-100"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span
                                        class="pointer-events-none absolute -inset-px rounded-[12px] border-2 border-transparent peer-checked:border-blue-500"
                                        aria-hidden="true"></span>
                                </label> -->
                            </div>
                        </div>

                        {{-- Crypto Currency Selection (Hidden by default) --}}
                        <div id="crypto-options" class="hidden">
                            <label for="pay_currency"
                                class="mb-2 block text-sm font-medium text-[rgba(219,234,254,0.9)]">
                                Pilih Mata Uang Crypto
                            </label>
                            <select id="pay_currency" name="pay_currency"
                                class="w-full rounded-[12px] border border-[rgba(255,255,255,0.12)] bg-[rgba(15,23,42,0.6)] px-4 py-3 text-sm text-white focus:border-[#3B82F6] focus:outline-none focus:ring-2 focus:ring-[#3B82F6]/30">
                                <option value="usdttrc20">USDT (TRC20 - Tron)</option>
                                <option value="usdt">USDT (ERC20 - Ethereum)</option>
                                <option value="btc">BTC (Bitcoin)</option>
                                <option value="eth">ETH (Ethereum)</option>
                                <option value="matic">MATIC (Polygon)</option>
                                <option value="bnb">BNB (BSC - BEP20)</option>
                                <option value="sol">SOL (Solana)</option>
                                <option value="trx">TRX (Tron)</option>
                                <option value="ltc">LTC (Litecoin)</option>
                            </select>
                            <p class="mt-2 text-xs text-yellow-400">
                                * Pastikan memilih jaringan yang benar saat pembayaran. USDT TRC20 lebih hemat fee.
                            </p>
                        </div>

                        {{-- Error/Info Message --}}
                        <div id="error-message"
                            class="hidden rounded-[12px] bg-red-500/20 border border-red-500/30 p-4 text-sm text-red-300">
                        </div>

                        {{-- Pending Order Info & Actions --}}
                        @if($pendingOrder)
                            <div id="pending-order-info"
                                class="rounded-[12px] bg-yellow-500/20 border border-yellow-500/30 p-4">
                                <p class="text-sm text-yellow-300 mb-3">
                                    Anda memiliki pesanan tertunda ({{ $pendingOrder->order_number }}).
                                    <br><span class="text-xs text-yellow-400/70">Expired:
                                        {{ $pendingOrder->expired_at->format('d M Y H:i') }}</span>
                                </p>
                                <div class="flex gap-2">
                                    <button type="button" id="btn-continue-payment"
                                        class="flex-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition">
                                        Lanjut Bayar
                                    </button>
                                    <button type="button" id="btn-change-payment"
                                        class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition">
                                        Ganti Metode
                                    </button>
                                    <button type="button" id="btn-cancel-order"
                                        class="flex-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition">
                                        Batalkan
                                    </button>
                                </div>
                            </div>
                        @endif

                        {{-- Submit Button --}}
                        <button type="submit" id="pay-button"
                            class="w-full rounded-[12px] bg-[#2563EB] px-6 py-3.5 text-sm font-semibold text-white shadow-md shadow-blue-500/30 hover:bg-[#3B82F6] transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="button-text">
                                @if($package->price == 0)
                                    Daftar Gratis
                                @elseif($pendingOrder)
                                    Buat Pesanan Baru
                                @else
                                    Bayar {{ $package->formatted_price }}
                                @endif
                            </span>
                            <span id="button-loading" class="hidden">
                                <svg class="inline-block w-5 h-5 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-2">
                <div class="top-8 rounded-[28px] border border-[rgba(255,255,255,0.14)] bg-[rgba(15,23,42,0.9)] p-6">
                    <h2 class="text-lg font-semibold text-white">Ringkasan Pesanan</h2>

                    <div class="mt-6 flex items-center gap-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-[16px] bg-[#2B7FFF]">
                            <svg width="24" height="24" viewBox="0 0 32 32" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20.631 17.1825L22.6505 28.5477C22.6731 28.6815 22.6543 28.8191 22.5966 28.9419C22.539 29.0648 22.4452 29.1671 22.3278 29.2353C22.2104 29.3034 22.075 29.334 21.9397 29.3231C21.8044 29.3122 21.6757 29.2603 21.5707 29.1742L16.7986 25.5924C16.5682 25.4203 16.2883 25.3273 16.0008 25.3273C15.7132 25.3273 15.4333 25.4203 15.2029 25.5924L10.4228 29.1729C10.3179 29.2588 10.1893 29.3106 10.0542 29.3216C9.91908 29.3325 9.78384 29.3019 9.66652 29.234C9.5492 29.1661 9.45539 29.064 9.39759 28.9414C9.3398 28.8187 9.32077 28.6814 9.34305 28.5477L11.3612 17.1825"
                                    stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M15.9961 18.6621C20.4133 18.6621 23.9941 15.0813 23.9941 10.6641C23.9941 6.24686 20.4133 2.66602 15.9961 2.66602C11.5789 2.66602 7.99805 6.24686 7.99805 10.6641C7.99805 15.0813 11.5789 18.6621 15.9961 18.6621Z"
                                    stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-white">{{ $package->name }}</p>
                            <p class="text-xs text-[rgba(190,219,255,0.7)]">Langganan Bulanan</p>
                        </div>
                    </div>

                    {{-- Features --}}
                    @if($package->features)
                        <ul class="mt-6 space-y-2 text-xs text-[rgba(219,234,254,0.9)]">
                            @foreach($package->features as $feature)
                                <li class="flex items-center gap-2">
                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M18.1673 8.33332C18.5479 10.2011 18.2767 12.1428 17.3989 13.8348C16.5211 15.5268 15.0897 16.8667 13.3436 17.6311C11.5975 18.3955 9.64203 18.5381 7.80342 18.0353C5.96482 17.5325 4.35417 16.4145 3.24007 14.8678C2.12597 13.3212 1.57577 11.4394 1.68123 9.53615C1.78668 7.63294 2.5414 5.8234 3.81955 4.4093C5.09769 2.9952 6.82199 2.06202 8.70489 1.76537C10.5878 1.46872 12.5155 1.82654 14.1665 2.77916"
                                            stroke="#05DF72" stroke-width="1.66667" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M7.5 9.16659L10 11.6666L18.3333 3.33325" stroke="#05DF72"
                                            stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="mt-6 border-t border-[rgba(255,255,255,0.1)] pt-6">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-[rgba(190,219,255,0.7)]">Total</span>
                            <span class="text-2xl font-bold text-white">{{ $package->formatted_price }}</span>
                        </div>
                        @if($package->price > 0)
                            <p class="mt-1 text-right text-xs text-[rgba(190,219,255,0.5)]">/bulan</p>
                        @endif
                    </div>

                    <div class="mt-6 flex items-center gap-2 text-xs text-[rgba(190,219,255,0.7)]">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        Pembayaran aman dengan Midtrans
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($package->price > 0)
        {{-- Midtrans Snap JS --}}
        <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $clientKey }}"></script>
        {{-- SweetAlert2 --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Store order data from server (if pending order exists)
            let currentSnapToken = {!! $pendingOrder ? "'" . $pendingOrder->snap_token . "'" : 'null' !!};
            let currentOrderNumber = {!! $pendingOrder ? "'" . $pendingOrder->order_number . "'" : 'null' !!};
            let orderExpiredAt = {!! $pendingOrder ? "new Date('" . $pendingOrder->expired_at->toISOString() . "')" : 'null' !!};

            // Check if current order is expired
            function isOrderExpired() {
                if (!orderExpiredAt) return false;
                return new Date() > orderExpiredAt;
            }

            // Clear stored order data (for creating new order)
            function clearStoredOrder() {
                currentSnapToken = null;
                currentOrderNumber = null;
                orderExpiredAt = null;
            }

            // Function to check if form is valid
            function isFormValid() {
                const nameInput = document.querySelector('input[name="name"]');
                const emailInput = document.querySelector('input[name="email"]');
                const phoneInput = document.querySelector('input[name="phone"]');
                const instInput = document.querySelector('input[name="institution"]');

                return nameInput.value.trim() !== '' &&
                    emailInput.value.trim() !== '' &&
                    phoneInput.value.trim() !== '' &&
                    instInput.value.trim() !== '';
            }

            // Function to show validation alert
            function showValidationAlert() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    text: 'Mohon lengkapi semua data yang bertanda bintang (*).',
                    confirmButtonColor: '#3B82F6',
                });
            }

            // Function to toggle button states
            function updateButtonStates() {
                const payButton = document.getElementById('pay-button');
                const hasPendingOrder = currentSnapToken && currentOrderNumber && !isOrderExpired();

                // Main "Buat Pesanan Baru" button: disabled if pending order exists OR data not valid
                payButton.disabled = hasPendingOrder || !isFormValid();
            }

            // Listen for input changes
            document.addEventListener('DOMContentLoaded', function () {
                const nameInput = document.querySelector('input[name="name"]');
                const emailInput = document.querySelector('input[name="email"]');
                const phoneInput = document.querySelector('input[name="phone"]');
                const instInput = document.querySelector('input[name="institution"]');

                nameInput.addEventListener('input', updateButtonStates);
                emailInput.addEventListener('input', updateButtonStates);
                phoneInput.addEventListener('input', updateButtonStates);
                instInput.addEventListener('input', updateButtonStates);

                // Initial check
                updateButtonStates();
            });

            // Function to open Midtrans popup
            function openMidtransPopup(snapToken, orderNumber) {
                window.snap.pay(snapToken, {
                    onSuccess: async function (result) {
                        // Confirm payment on server
                        try {
                            await fetch('/payment/confirm', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ order_number: orderNumber }),
                            });
                        } catch (e) {
                            console.log('Confirm error:', e);
                        }
                        window.location.href = '{{ url("/payment/success") }}/' + orderNumber;
                    },
                    onPending: function (result) {
                        // Stay on checkout page
                        currentSnapToken = snapToken;
                        currentOrderNumber = orderNumber;

                        Swal.fire({
                            icon: 'info',
                            title: 'Menunggu Pembayaran',
                            text: 'Silakan selesaikan pembayaran Anda.',
                            confirmButtonColor: '#3B82F6',
                        }).then(() => {
                            location.reload();
                        });
                    },
                    onError: function (result) {
                        // Reset button state
                        const payButton = document.getElementById('pay-button');
                        const buttonText = document.getElementById('button-text');
                        const buttonLoading = document.getElementById('button-loading');
                        payButton.disabled = false;
                        buttonText.classList.remove('hidden');
                        buttonLoading.classList.add('hidden');

                        Swal.fire({
                            icon: 'error',
                            title: 'Pembayaran Gagal',
                            text: 'Silakan coba lagi.',
                            confirmButtonColor: '#EF4444',
                        });
                    },
                    onClose: function () {
                        // Just re-enable button, stay on page
                        // User can click again to resume the same order
                        const payButton = document.getElementById('pay-button');
                        const buttonText = document.getElementById('button-text');
                        const buttonLoading = document.getElementById('button-loading');

                        payButton.disabled = false;
                        buttonText.classList.remove('hidden');
                        buttonLoading.classList.add('hidden');

                        // Store tokens for next click
                        currentSnapToken = snapToken;
                        currentOrderNumber = orderNumber;
                    }
                });
            }

            // Payment Method Toggle
            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const cryptoOptions = document.getElementById('crypto-options');
                    if (this.value === 'crypto') {
                        cryptoOptions.classList.remove('hidden');
                    } else {
                        cryptoOptions.classList.add('hidden');
                    }
                });
            });

            document.getElementById('checkout-form').addEventListener('submit', async function (e) {
                e.preventDefault();

                const form = e.target;
                const payButton = document.getElementById('pay-button');
                const buttonText = document.getElementById('button-text');
                const buttonLoading = document.getElementById('button-loading');
                const errorMessage = document.getElementById('error-message');

                // Always validate required fields first
                const nameInput = form.querySelector('input[name="name"]');
                const emailInput = form.querySelector('input[name="email"]');

                if (!nameInput.value.trim()) {
                    nameInput.focus();
                    nameInput.reportValidity();
                    return;
                }
                if (!emailInput.value.trim()) {
                    emailInput.focus();
                    emailInput.reportValidity();
                    return;
                }

                const phoneInput = form.querySelector('input[name="phone"]');
                const instInput = form.querySelector('input[name="institution"]');

                if (!phoneInput.value.trim()) {
                    phoneInput.focus();
                    phoneInput.reportValidity();
                    return;
                }
                if (!instInput.value.trim()) {
                    instInput.focus();
                    instInput.reportValidity();
                    return;
                }

                // Check payment method
                const paymentMethod = form.querySelector('input[name="payment_method"]:checked').value;

                // Disable button and show loading
                payButton.disabled = true;
                buttonText.classList.add('hidden');
                buttonLoading.classList.remove('hidden');
                errorMessage.classList.add('hidden');

                try {
                    const formData = new FormData(form);

                    // Choose endpoint based on payment method
                    const endpoint = paymentMethod === 'crypto'
                        ? '{{ route("payment.crypto") }}'
                        : '{{ route("checkout.process") }}';

                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    const data = await response.json();

                    if (data.error) {
                        throw new Error(data.error);
                    }

                    if (paymentMethod === 'crypto') {
                        // Redirect to NOWPayments invoice
                        if (data.invoice_url) {
                            window.location.href = data.invoice_url;
                        } else {
                            throw new Error('Gagal mendapatkan link pembayaran.');
                        }
                    } else {
                        // Midtrans Flow
                        // Store for future use (in case user closes popup and clicks again)
                        currentSnapToken = data.snap_token;
                        currentOrderNumber = data.order_number;
                        // Set expiry to 24 hours from now for new orders
                        orderExpiredAt = new Date(Date.now() + 24 * 60 * 60 * 1000);

                        // Open Midtrans Snap popup
                        openMidtransPopup(data.snap_token, data.order_number);
                    }

                } catch (error) {
                    errorMessage.textContent = error.message || 'Terjadi kesalahan. Silakan coba lagi.';
                    errorMessage.classList.remove('hidden');
                    errorMessage.classList.remove('text-yellow-400');
                    errorMessage.classList.add('text-red-400');

                    // Re-enable button
                    payButton.disabled = false;
                    buttonText.classList.remove('hidden');
                    buttonLoading.classList.add('hidden');
                }
            });

            // Helper function for Cancel/Change Order
            async function cancelCurrentOrder(isChangeMethod = false) {
                if (!currentOrderNumber) return;

                const actionText = isChangeMethod
                    ? 'Pesanan saat ini akan dibatalkan agar Anda dapat memilih metode pembayaran baru.'
                    : 'Anda yakin ingin membatalkan pesanan ini?';

                const confirmButtonText = isChangeMethod ? 'Ya, Ganti Metode' : 'Ya, Batalkan';

                const result = await Swal.fire({
                    title: 'Konfirmasi',
                    text: actionText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: confirmButtonText,
                    cancelButtonText: 'Tidak'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch('/payment/cancel', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ order_number: currentOrderNumber }),
                        });

                        const data = await response.json();

                        if (data.success) {
                            const pendingOrderInfo = document.getElementById('pending-order-info');
                            if (pendingOrderInfo) pendingOrderInfo.classList.add('hidden');

                            clearStoredOrder();
                            updateButtonStates();

                            // Hide error message if any
                            const errorMessage = document.getElementById('error-message');
                            if (errorMessage) errorMessage.classList.add('hidden');

                            await Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Pesanan berhasil dibatalkan.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            throw new Error(data.error || 'Gagal membatalkan pesanan.');
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: error.message || 'Terjadi kesalahan saat membatalkan pesanan.',
                            confirmButtonColor: '#EF4444',
                        });
                    }
                }
            }

            // If there's a pending order, setup button handlers
            @if($pendingOrder)
                document.addEventListener('DOMContentLoaded', function () {
                    const btnContinue = document.getElementById('btn-continue-payment');
                    const btnChange = document.getElementById('btn-change-payment');
                    const btnCancel = document.getElementById('btn-cancel-order');
                    const pendingOrderInfo = document.getElementById('pending-order-info');

                    // Continue Payment
                    btnContinue.addEventListener('click', function () {
                        if (!isFormValid()) {
                            showValidationAlert();
                            return;
                        }

                        if (currentSnapToken && !isOrderExpired()) {
                            openMidtransPopup(currentSnapToken, currentOrderNumber);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Expired',
                                text: 'Pesanan sudah expired. Silakan buat pesanan baru.',
                                confirmButtonColor: '#EF4444',
                            }).then(() => {
                                if (pendingOrderInfo) pendingOrderInfo.classList.add('hidden');
                                clearStoredOrder();
                                updateButtonStates();
                            });
                        }
                    });

                    // Change Payment Method -> Trigger Cancel -> New Order Flow
                    btnChange.addEventListener('click', function () {
                        if (!isFormValid()) {
                            showValidationAlert();
                            return;
                        }
                        cancelCurrentOrder(true);
                    });

                    // Cancel Order
                    btnCancel.addEventListener('click', function () {
                        cancelCurrentOrder(false);
                    });
                });
            @endif
        </script>
    @else
        {{-- For free package, submit normally --}}
        <script>
            document.getElementById('checkout-form').addEventListener('submit', async function (e) {
                e.preventDefault();

                const form = e.target;
                const payButton = document.getElementById('pay-button');
                const buttonText = document.getElementById('button-text');
                const buttonLoading = document.getElementById('button-loading');
                const errorMessage = document.getElementById('error-message');

                payButton.disabled = true;
                buttonText.classList.add('hidden');
                buttonLoading.classList.remove('hidden');
                errorMessage.classList.add('hidden');

                try {
                    const formData = new FormData(form);

                    const response = await fetch('{{ route("checkout.process") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    // For free package, redirect happens server-side
                    if (response.redirected) {
                        window.location.href = response.url;
                        return;
                    }

                    const data = await response.json();
                    if (data.order_number) {
                        window.location.href = '{{ url("/payment/success") }}/' + data.order_number;
                    }
                } catch (error) {
                    errorMessage.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                    errorMessage.classList.remove('hidden');

                    payButton.disabled = false;
                    buttonText.classList.remove('hidden');
                    buttonLoading.classList.add('hidden');
                }
            });
        </script>
    @endif

</x-layouts.app>
