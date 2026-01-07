<x-layouts.app title="Pembayaran Berhasil - SertiKu">

    <section class="mx-auto max-w-2xl px-4 py-16 lg:px-0">
        <div class="rounded-[28px] border border-[rgba(255,255,255,0.14)] bg-[rgba(15,23,42,0.9)] p-8 text-center">

            {{-- Success Icon --}}
            <div
                class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-[#22C55E] to-[#4ADE80]">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="mt-6 text-2xl font-semibold text-white">
                @if($order->status === 'paid')
                    Pembayaran Berhasil!
                @else
                    Pesanan Dibuat
                @endif
            </h1>

            <p class="mt-3 text-sm text-[rgba(190,219,255,0.7)]">
                @if($order->status === 'paid')
                    Terima kasih! Pembayaran Anda telah kami terima.
                @else
                    Menunggu konfirmasi pembayaran...
                @endif
            </p>

            {{-- Order Details --}}
            <div class="mt-8 rounded-[18px] bg-[rgba(15,23,42,0.5)] p-6 text-left">
                <h2 class="text-sm font-semibold text-[#8EC5FF]">Detail Pesanan</h2>

                <div class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-[rgba(190,219,255,0.7)]">Nomor Pesanan</span>
                        <span class="font-mono font-semibold text-white">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[rgba(190,219,255,0.7)]">Paket</span>
                        <span class="text-white">{{ $order->package->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[rgba(190,219,255,0.7)]">Nama</span>
                        <span class="text-white">{{ $order->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[rgba(190,219,255,0.7)]">Email</span>
                        <span class="text-white">{{ $order->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[rgba(190,219,255,0.7)]">No. Whatsapp</span>
                        <span class="text-white">{{ $order->phone }}</span>
                    </div>
                    <div class="flex justify-between border-t border-[rgba(255,255,255,0.1)] pt-3">
                        <span class="text-[rgba(190,219,255,0.7)]">Total</span>
                        <span class="text-lg font-bold text-white">{{ $order->formatted_amount }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[rgba(190,219,255,0.7)]">Status</span>
                        @if($order->status === 'paid')
                            <span class="rounded-full bg-[#05DF72]/20 px-3 py-1 text-xs font-semibold text-[#05DF72]">
                                Lunas
                            </span>
                        @else
                            <span class="rounded-full bg-yellow-500/20 px-3 py-1 text-xs font-semibold text-yellow-400">
                                Menunggu Pembayaran
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Next Steps --}}
            <div class="mt-8">
                <p class="text-sm text-[rgba(190,219,255,0.7)]">
                    @if($order->status === 'paid')
                        Paket Anda sudah aktif. Silakan lanjutkan ke dashboard untuk mulai menggunakan fitur premium.
                    @else
                        Kami akan mengirim konfirmasi ke Whatsapp <strong class="text-white">{{ $order->phone }}</strong> setelah
                        pembayaran terverifikasi.
                    @endif
                </p>
            </div>

            {{-- Actions --}}
            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                @if($order->status !== 'paid')
                    {{-- Check Status Button --}}
                    <button id="checkStatusBtn" onclick="checkPaymentStatus()"
                        class="inline-flex items-center justify-center gap-2 rounded-[12px] bg-gradient-to-b from-[#059669] to-[#10B981] px-6 py-3 text-sm font-semibold text-white shadow-[0_20px_40px_-20px_rgba(16,185,129,0.9)] hover:brightness-110 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span id="checkStatusText">Cek Status</span>
                    </button>
                @endif
                <a href="{{ route('home') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-[12px] bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6] px-6 py-3 text-sm font-semibold text-white shadow-[0_20px_40px_-20px_rgba(37,99,235,0.9)] hover:brightness-110 transition">
                    Kembali ke Beranda
                </a>
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-[12px] border border-[rgba(255,255,255,0.2)] px-6 py-3 text-sm font-semibold text-white hover:bg-[rgba(15,23,42,1)] transition">
                        Ke Dashboard
                    </a>
                @endauth
            </div>

            @if($order->status !== 'paid')
                <script>
                    function checkPaymentStatus() {
                        const btn = document.getElementById('checkStatusBtn');
                        const text = document.getElementById('checkStatusText');
                        
                        btn.disabled = true;
                        text.textContent = 'Mengecek...';
                        
                        fetch('{{ route("payment.confirm") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({ order_number: '{{ $order->order_number }}' })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                text.textContent = 'Berhasil! Memuat ulang...';
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                text.textContent = 'Cek Status Pembayaran';
                                btn.disabled = false;
                            }
                        })
                        .catch(() => {
                            text.textContent = 'Cek Status Pembayaran';
                            btn.disabled = false;
                        });
                    }
                    
                    // Auto-refresh every 10 seconds for pending orders
                    setTimeout(() => window.location.reload(), 10000);
                </script>
            @endif
        </div>
    </section>

</x-layouts.app>