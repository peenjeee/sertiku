{{-- resources/views/pages/status.blade.php --}}
<x-layouts.app title="SertiKu – Status Sistem">

        {{-- Hero --}}
        <section class="overflow-hidden py-16 md:py-20 px-4">
            <div class="pointer-events-none absolute -left-32 top-0 h-96 w-96 rounded-full bg-gradient-to-r from-[#10B98133] to-[#00B8DB33] blur-3xl opacity-60 hidden md:block"></div>
            <div class="mx-auto max-w-4xl px-4 text-center relative z-10">
                {{-- Status Badge --}}
                <div class="inline-flex items-center gap-2 rounded-full bg-[#10B981]/20 border border-[#10B981]/30 px-4 py-2 mb-6">
                    <span class="w-2 h-2 rounded-full bg-[#10B981] animate-pulse"></span>
                    <span class="text-sm font-medium text-[#10B981]">Semua Sistem Berjalan Normal</span>
                </div>

                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Status Sistem</h1>
                <p class="text-lg text-[#BEDBFF]/80 max-w-2xl mx-auto">
                    Pantau status layanan SertiKu secara real-time
                </p>
            </div>
        </section>

        {{-- Services Status --}}
        <section class="py-12 px-4">
            <div class="mx-auto max-w-3xl">
                <div class="space-y-4">
                    @php
                        $services = [
                            ['name' => 'Website & Dashboard', 'status' => 'operational', 'uptime' => '99.99%'],
                            ['name' => 'API Sertifikat', 'status' => 'operational', 'uptime' => '99.98%'],
                            ['name' => 'Verifikasi Blockchain', 'status' => 'operational', 'uptime' => '99.95%'],
                            ['name' => 'Sistem Pembayaran', 'status' => 'operational', 'uptime' => '99.99%'],
                            ['name' => 'Email Notifications', 'status' => 'operational', 'uptime' => '99.90%'],
                        ];
                    @endphp

                    @foreach($services as $service)
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 rounded-xl bg-white/5 border border-white/10 p-4 sm:p-5">
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
                            <span class="text-xs sm:text-sm text-[#BEDBFF]/60">Uptime: {{ $service['uptime'] }}</span>
                            <span class="text-xs sm:text-sm text-[#10B981]">Operational</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Uptime Chart (Visual) --}}
        <section class="py-12 px-4">
            <div class="mx-auto max-w-3xl">
                <h2 class="text-xl font-bold text-white mb-6">Uptime 30 Hari Terakhir</h2>
                <div class="rounded-xl bg-white/5 border border-white/10 p-6">
                    <div class="flex gap-1">
                        @for($i = 0; $i < 30; $i++)
                        <div class="flex-1 h-10 rounded bg-[#10B981] hover:brightness-110 transition" title="Hari {{ 30 - $i }}: 100%"></div>
                        @endfor
                    </div>
                    <div class="flex justify-between mt-3 text-xs text-[#BEDBFF]/60">
                        <span>30 hari lalu</span>
                        <span>Hari ini</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Recent Incidents --}}
        <section class="py-12 px-4">
            <div class="mx-auto max-w-3xl">
                <h2 class="text-xl font-bold text-white mb-6">Insiden Terbaru</h2>
                <div class="rounded-xl bg-white/5 border border-white/10 p-6">
                    <div class="flex items-center gap-3 text-[#BEDBFF]/70">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Tidak ada insiden dalam 90 hari terakhir</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Subscribe --}}
        <section class="py-12 px-4">
            <div class="mx-auto max-w-3xl text-center">
                <div class="rounded-2xl bg-gradient-to-r from-[#1E3A8F]/30 to-[#3B82F6]/30 border border-[#3B82F6]/30 p-8">
                    <h3 class="text-xl font-semibold text-white mb-2">Dapatkan Notifikasi Status</h3>
                    <p class="text-[#BEDBFF]/80 mb-6">Dapatkan update jika ada gangguan layanan</p>
                    <form class="flex gap-3 max-w-md mx-auto">
                        <input type="email" placeholder="email@contoh.com"
                               class="flex-1 rounded-lg bg-white/10 border border-white/20 px-4 py-3 text-white text-sm placeholder:text-white/50 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                        <button type="button"
                                class="rounded-lg bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6] px-6 py-3 text-sm font-medium text-white hover:brightness-110 transition">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </section>

</x-layouts.app>
