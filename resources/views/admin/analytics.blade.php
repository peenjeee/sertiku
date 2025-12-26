<x-layouts.admin>
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8 animate-fade-in">
        <div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold">Analytics</h1>
            <p class="text-white/60 text-sm mt-1">Monitor pertumbuhan dan aktivitas platform</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" class="flex items-center gap-3">
                <select name="year" onchange="this.form.submit()"
                    class="bg-white/10 border border-white/20 text-white rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }} class="text-gray-800">{{ $y }}</option>
                    @endforeach
                </select>
                <select name="period" onchange="this.form.submit()"
                    class="bg-white/10 border border-white/20 text-white rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="7" {{ request('period') == '7' ? 'selected' : '' }} class="text-gray-800">7 Hari
                        Terakhir</option>
                    <option value="30" {{ request('period', '30') == '30' ? 'selected' : '' }} class="text-gray-800">30
                        Hari Terakhir</option>
                    <option value="90" {{ request('period') == '90' ? 'selected' : '' }} class="text-gray-800">90 Hari
                        Terakhir</option>
                </select>
            </form>
            <div class="flex items-center gap-2 px-4 py-2 bg-emerald-500/20 border border-emerald-500/30 rounded-lg">
                <span class="w-2 h-2 bg-emerald-500 rounded-full live-dot"></span>
                <span class="text-emerald-400 text-sm font-medium">Live</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards with % Change -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <!-- Total Verifikasi -->
        <div class="stat-card-blue rounded-2xl p-5 hover-lift animate-fade-in-up stagger-1">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-10 h-10 icon-circle-blue rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <span class="text-gray-600 text-sm font-medium">Total Verifikasi</span>
            </div>
            <p class="text-gray-800 text-3xl font-bold mb-2">{{ number_format($stats['total_verifikasi']['value']) }}
            </p>
            <div class="flex items-center gap-1">
                @if($stats['total_verifikasi']['change'] >= 0)
                    <span class="text-emerald-600 text-sm font-medium">+{{ $stats['total_verifikasi']['change'] }}%</span>
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                @else
                    <span class="text-red-600 text-sm font-medium">{{ $stats['total_verifikasi']['change'] }}%</span>
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                @endif
                <span class="text-gray-500 text-xs ml-1">vs periode lalu</span>
            </div>
        </div>

        <!-- Sertifikat Aktif -->
        <div class="stat-card-green rounded-2xl p-5 hover-lift animate-fade-in-up stagger-2">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-10 h-10 icon-circle-green rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <span class="text-gray-600 text-sm font-medium">Sertifikat Aktif</span>
            </div>
            <p class="text-gray-800 text-3xl font-bold mb-2">{{ number_format($stats['sertifikat_aktif']['value']) }}
            </p>
            <div class="flex items-center gap-1">
                @if($stats['sertifikat_aktif']['change'] >= 0)
                    <span class="text-emerald-600 text-sm font-medium">+{{ $stats['sertifikat_aktif']['change'] }}%</span>
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                @else
                    <span class="text-red-600 text-sm font-medium">{{ $stats['sertifikat_aktif']['change'] }}%</span>
                @endif
                <span class="text-gray-500 text-xs ml-1">vs periode lalu</span>
            </div>
        </div>

        <!-- Lembaga Terdaftar -->
        <div class="stat-card-purple rounded-2xl p-5 hover-lift animate-fade-in-up stagger-3">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-10 h-10 icon-circle-purple rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-gray-600 text-sm font-medium">Lembaga Terdaftar</span>
            </div>
            <p class="text-gray-800 text-3xl font-bold mb-2">{{ number_format($stats['lembaga_terdaftar']['value']) }}
            </p>
            <div class="flex items-center gap-1">
                @if($stats['lembaga_terdaftar']['change'] >= 0)
                    <span class="text-emerald-600 text-sm font-medium">+{{ $stats['lembaga_terdaftar']['change'] }}%</span>
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                @else
                    <span class="text-red-600 text-sm font-medium">{{ $stats['lembaga_terdaftar']['change'] }}%</span>
                @endif
                <span class="text-gray-500 text-xs ml-1">vs periode lalu</span>
            </div>
        </div>

        <!-- Pengguna Aktif -->
        <div class="stat-card-orange rounded-2xl p-5 hover-lift animate-fade-in-up stagger-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-10 h-10 icon-circle-orange rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-gray-600 text-sm font-medium">Pengguna Aktif</span>
            </div>
            <p class="text-gray-800 text-3xl font-bold mb-2">{{ number_format($stats['pengguna_aktif']['value']) }}</p>
            <div class="flex items-center gap-1">
                @if($stats['pengguna_aktif']['change'] >= 0)
                    <span class="text-emerald-600 text-sm font-medium">+{{ $stats['pengguna_aktif']['change'] }}%</span>
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                @else
                    <span class="text-red-600 text-sm font-medium">{{ $stats['pengguna_aktif']['change'] }}%</span>
                @endif
                <span class="text-gray-500 text-xs ml-1">vs periode lalu</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Tren Penerbitan Sertifikat -->
        <div class="glass-card rounded-2xl p-6 animate-fade-in">
            <h3 class="text-gray-800 font-bold text-lg mb-4">Tren Penerbitan Sertifikat</h3>
            <div class="h-64">
                <canvas id="certificateChart"></canvas>
            </div>
        </div>

        <!-- Aktivitas Verifikasi -->
        <div class="glass-card rounded-2xl p-6 animate-fade-in">
            <h3 class="text-gray-800 font-bold text-lg mb-4">Aktivitas Verifikasi</h3>
            <div class="h-64">
                <canvas id="verificationChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Real-time Verifikasi -->
    <div class="glass-card rounded-2xl p-6 animate-fade-in">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <h3 class="text-gray-800 font-bold text-lg">Real-time Verifikasi</h3>
                <span
                    class="flex items-center gap-1 px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full live-dot"></span>
                    Live
                </span>
            </div>
        </div>

        <div class="space-y-3 max-h-64 overflow-y-auto" id="realtimeLog">
            @foreach($recentVerifications as $index => $item)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg animate-slide-in-left"
                    style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-800 text-sm font-medium">Sertifikat Diverifikasi</p>
                            <p class="text-gray-500 text-xs font-mono">{{ $item->certificate_number }}</p>
                        </div>
                    </div>
                    <span class="text-gray-400 text-xs">{{ $item->updated_at->diffForHumans() }}</span>
                </div>
            @endforeach

            @if($recentVerifications->count() === 0)
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-400 text-sm">Belum ada aktivitas verifikasi</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Certificate Trend Chart
            const certCtx = document.getElementById('certificateChart').getContext('2d');
            new Chart(certCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($certificatesTrend->pluck('month')) !!},
                    datasets: [{
                        label: 'Sertifikat',
                        data: {!! json_encode($certificatesTrend->pluck('count')) !!},
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // Verification Activity Chart
            const verifyCtx = document.getElementById('verificationChart').getContext('2d');
            new Chart(verifyCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(collect($verificationActivity)->pluck('month')) !!},
                    datasets: [{
                        label: 'Verifikasi',
                        data: {!! json_encode(collect($verificationActivity)->pluck('count')) !!},
                        backgroundColor: 'rgba(139, 92, 246, 0.8)',
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
</x-layouts.admin>