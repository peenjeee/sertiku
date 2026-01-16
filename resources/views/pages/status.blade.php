{{-- resources/views/pages/status.blade.php --}}
<x-layouts.app title="SertiKu – Status Sistem">

    {{-- Hero --}}
    <section class="overflow-hidden py-16 md:py-20 px-4">

        <div class="mx-auto max-w-4xl px-4 text-center relative z-10">
            {{-- Status Badge --}}
            @if($overallStatus === 'operational')
                <div
                    class="inline-flex items-center gap-2 rounded-full bg-[#10B981]/20 border border-[#10B981]/30 px-4 py-2 mb-6">
                    <span class="w-2 h-2 rounded-full bg-[#10B981] animate-pulse"></span>
                    <span class="text-sm font-medium text-[#10B981]">Semua Sistem Berjalan Normal</span>
                </div>
            @elseif($overallStatus === 'degraded')
                <div
                    class="inline-flex items-center gap-2 rounded-full bg-[#F59E0B]/20 border border-[#F59E0B]/30 px-4 py-2 mb-6">
                    <span class="w-2 h-2 rounded-full bg-[#F59E0B] animate-pulse"></span>
                    <span class="text-sm font-medium text-[#F59E0B]">Beberapa Layanan Terganggu</span>
                </div>
            @else
                <div
                    class="inline-flex items-center gap-2 rounded-full bg-[#EF4444]/20 border border-[#EF4444]/30 px-4 py-2 mb-6">
                    <span class="w-2 h-2 rounded-full bg-[#EF4444] animate-pulse"></span>
                    <span class="text-sm font-medium text-[#EF4444]">Sistem Mengalami Gangguan</span>
                </div>
            @endif

            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Status Sistem</h1>
            <p class="text-lg text-[#BEDBFF]/80 max-w-2xl mx-auto">
                Pantau status layanan SertiKu secara real-time
            </p>
            <p class="text-sm text-[#BEDBFF]/50 mt-2">
                Terakhir diperbarui: {{ now()->format('d M Y, H:i:s') }} WIB
            </p>
        </div>
    </section>

    {{-- Services Status --}}
    <section class="py-12 px-4">
        <div class="mx-auto max-w-3xl">
            <div class="space-y-4">
                @foreach($services as $service)
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 rounded-xl bg-white/5 border border-white/10 p-4 sm:p-5">
                        <div class="flex items-center gap-3 sm:gap-4">
                            @if($service['status'] === 'operational')
                                <span class="w-3 h-3 rounded-full bg-[#10B981] flex-shrink-0"></span>
                            @elseif($service['status'] === 'degraded')
                                <span class="w-3 h-3 rounded-full bg-[#F59E0B] flex-shrink-0"></span>
                            @else
                                <span class="w-3 h-3 rounded-full bg-[#EF4444] flex-shrink-0"></span>
                            @endif
                            <span class="text-white font-medium text-sm sm:text-base">{{ $service['name'] }}</span>
                        </div>
                        <div class="flex items-center gap-3 sm:gap-4 pl-6 sm:pl-0">
                            <span class="text-xs sm:text-sm text-[#BEDBFF]/60">{{ $service['response_time'] ?? '' }}</span>
                            <span class="text-xs sm:text-sm text-[#BEDBFF]/60">Uptime: {{ $service['uptime'] }}</span>
                            @if($service['status'] === 'operational')
                                <span class="text-xs sm:text-sm text-[#10B981]">Operational</span>
                            @elseif($service['status'] === 'degraded')
                                <span class="text-xs sm:text-sm text-[#F59E0B]">Degraded</span>
                            @else
                                <span class="text-xs sm:text-sm text-[#EF4444]">Down</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Uptime Chart (Visual) --}}
    <!-- <section class="py-12 px-4">
        <div class="mx-auto max-w-3xl">
            <h2 class="text-xl font-bold text-white mb-6">Uptime 30 Hari Terakhir</h2>
            <div class="rounded-xl bg-white/5 border border-white/10 p-6">
                <div class="flex gap-1">
                    @foreach($uptimeHistory as $day)
                        @php
                            $color = $day['uptime'] >= 99 ? 'bg-[#10B981]' : ($day['uptime'] >= 95 ? 'bg-[#F59E0B]' : 'bg-[#EF4444]');
                        @endphp
                        <div class="flex-1 h-10 rounded {{ $color }} hover:brightness-110 transition"
                            title="{{ $day['date'] }}: {{ $day['uptime'] }}%"></div>
                    @endforeach
                </div>
                <div class="flex justify-between mt-3 text-xs text-[#BEDBFF]/60">
                    <span>30 hari lalu</span>
                    <span>Hari ini</span>
                </div>
            </div>
        </div>
    </section> -->

    {{-- Recent Incidents --}}
    <!-- <section class="py-12 px-4">
        <div class="mx-auto max-w-3xl">
            <h2 class="text-xl font-bold text-white mb-6">Insiden Terbaru</h2>
            <div class="rounded-xl bg-white/5 border border-white/10 p-6">
                @if(!empty($incidents))
                    <div class="space-y-4">
                        @foreach($incidents as $incident)
                            <div
                                class="flex items-start gap-4 p-4 rounded-lg {{ $incident['status'] === 'down' ? 'bg-[#EF4444]/10 border border-[#EF4444]/30' : 'bg-[#F59E0B]/10 border border-[#F59E0B]/30' }}">
                                <div class="flex-shrink-0">
                                    @if($incident['status'] === 'down')
                                        <span class="flex h-3 w-3 relative">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#EF4444] opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-[#EF4444]"></span>
                                        </span>
                                    @else
                                        <span class="flex h-3 w-3 relative">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#F59E0B] opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-[#F59E0B]"></span>
                                        </span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="font-medium {{ $incident['status'] === 'down' ? 'text-[#EF4444]' : 'text-[#F59E0B]' }}">{{ $incident['name'] }}</span>
                                        <span
                                            class="text-xs px-2 py-0.5 rounded {{ $incident['status'] === 'down' ? 'bg-[#EF4444]/20 text-[#EF4444]' : 'bg-[#F59E0B]/20 text-[#F59E0B]' }}">
                                            {{ $incident['status_text'] }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-[#BEDBFF]/70">{{ $incident['message'] }}</p>
                                    @if(!empty($incident['url']))
                                        <p class="text-xs text-[#BEDBFF]/50 mt-1">{{ $incident['url'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex items-center gap-3 text-[#BEDBFF]/70">
                        <svg class="w-5 h-5 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Tidak ada insiden saat ini. Semua layanan berjalan normal.</span>
                    </div>
                @endif
            </div>
        </div>
    </section> -->

    {{-- Subscribe --}}
    <!-- <section class="py-12 px-4">
        <div class="mx-auto max-w-3xl text-center">
            <div class="rounded-2xl bg-[#1E3A8F]/20 border border-[#3B82F6]/30 p-8">
                <h3 class="text-xl font-semibold text-white mb-2">Dapatkan Notifikasi Status</h3>
                <p class="text-[#BEDBFF]/80 mb-6">Dapatkan update jika ada gangguan layanan</p>
                <form id="statusSubscribeForm" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                    @csrf
                    <input type="email" id="statusEmail" placeholder="email@contoh.com" required
                        class="flex-1 rounded-lg bg-white/10 border border-white/20 px-4 py-3 text-white text-sm placeholder:text-white/50 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                    <button type="submit" id="statusSubmitBtn"
                        class="w-full sm:w-auto rounded-lg bg-[#2563EB] px-6 py-3 text-sm font-medium text-white shadow-md shadow-blue-500/20 hover:bg-[#3B82F6] transition flex items-center justify-center gap-2 disabled:opacity-50">
                        <span id="statusBtnText">Subscribe</span>
                        <svg id="statusSpinner" class="hidden animate-spin h-4 w-4 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </section> -->

    <script>
        document.getElementById('statusSubscribeForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const email = document.getElementById('statusEmail').value;
            const btn = document.getElementById('statusSubmitBtn');
            const spinner = document.getElementById('statusSpinner');
            const btnText = document.getElementById('statusBtnText');

            // Disable button and show loading
            btn.disabled = true;
            spinner.classList.remove('hidden');
            btnText.textContent = 'Memproses...';

            try {
                const response = await fetch('{{ route("leads.subscribe-status") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                });

                const data = await response.json();

                if (data.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Berhasil subscribe notifikasi!',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    } else {
                        alert('Berhasil subscribe notifikasi status!');
                    }
                    document.getElementById('statusEmail').value = '';
                } else {
                    throw new Error(data.message || 'Gagal subscribe');
                }
            } catch (error) {
                console.error('Status Subscribe Error:', error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Terjadi kesalahan, coba lagi',
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else {
                    alert('Terjadi kesalahan, silakan coba lagi.');
                }
            } finally {
                // Reset button
                btn.disabled = false;
                spinner.classList.add('hidden');
                btnText.textContent = 'Subscribe';
            }
        });
    </script>

</x-layouts.app>