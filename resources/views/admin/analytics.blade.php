<x-layouts.admin>
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8 animate-fade-in">
        <div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold">Analytics</h1>
            <p class="text-white/60 text-sm mt-1">Monitor pertumbuhan dan aktivitas platform</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" class="flex items-center gap-3">
                <div class="relative">
                    <input type="date" name="start_date" value="{{ $startDateStr }}" onchange="this.form.submit()"
                        class="bg-white/10 border border-white/20 text-white rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 [&::-webkit-calendar-picker-indicator]:invert">
                </div>
                <span class="text-white/60 font-medium">-</span>
                <div class="relative">
                    <input type="date" name="end_date" value="{{ $endDateStr }}" onchange="this.form.submit()"
                        class="bg-white/10 border border-white/20 text-white rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 [&::-webkit-calendar-picker-indicator]:invert">
                </div>
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

        <!-- Verifikasi Hari Ini -->
        <div class="stat-card-green rounded-2xl p-5 hover-lift animate-fade-in-up stagger-2">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-10 h-10 icon-circle-green rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-gray-600 text-sm font-medium">Verifikasi Hari Ini</span>
            </div>
            <p class="text-gray-800 text-3xl font-bold mb-2">{{ number_format($stats['verifikasi_hari_ini']['value']) }}
            </p>
            <div class="flex items-center gap-1">
                @if($stats['verifikasi_hari_ini']['change'] >= 0)
                    <span
                        class="text-emerald-600 text-sm font-medium">+{{ $stats['verifikasi_hari_ini']['change'] }}%</span>
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                @else
                    <span class="text-red-600 text-sm font-medium">{{ $stats['verifikasi_hari_ini']['change'] }}%</span>
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                @endif
                <span class="text-gray-500 text-xs ml-1">vs kemarin</span>
            </div>
        </div>

        <!-- Total Verifikasi Blockchain -->
        <div class="stat-card-purple rounded-2xl p-5 hover-lift animate-fade-in-up stagger-3">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-10 h-10 icon-circle-purple rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <span class="text-gray-600 text-sm font-medium">Total Verifikasi BC</span>
            </div>
            <p class="text-gray-800 text-3xl font-bold mb-2">
                {{ number_format($stats['total_verifikasi_blockchain']['value']) }}
            </p>
            <div class="flex items-center gap-1">
                @if($stats['total_verifikasi_blockchain']['change'] >= 0)
                    <span
                        class="text-emerald-600 text-sm font-medium">+{{ $stats['total_verifikasi_blockchain']['change'] }}%</span>
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                @else
                    <span
                        class="text-red-600 text-sm font-medium">{{ $stats['total_verifikasi_blockchain']['change'] }}%</span>
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                @endif
                <span class="text-gray-500 text-xs ml-1">vs periode lalu</span>
            </div>
        </div>

        <!-- Verifikasi Blockchain Hari Ini -->
        <div class="stat-card-orange rounded-2xl p-5 hover-lift animate-fade-in-up stagger-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-10 h-10 icon-circle-orange rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="text-gray-600 text-sm font-medium">Verifikasi BC Hari Ini</span>
            </div>
            <p class="text-gray-800 text-3xl font-bold mb-2">
                {{ number_format($stats['verifikasi_blockchain_hari_ini']['value']) }}
            </p>
            <div class="flex items-center gap-1">
                @if($stats['verifikasi_blockchain_hari_ini']['change'] >= 0)
                    <span
                        class="text-emerald-600 text-sm font-medium">+{{ $stats['verifikasi_blockchain_hari_ini']['change'] }}%</span>
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                @else
                    <span
                        class="text-red-600 text-sm font-medium">{{ $stats['verifikasi_blockchain_hari_ini']['change'] }}%</span>
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                @endif
                <span class="text-gray-500 text-xs ml-1">vs kemarin</span>
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

    <!-- Revenue Trend Chart -->
    <div class="mb-8">
        <div class="glass-card rounded-2xl p-6 animate-fade-in">
            <h3 class="text-gray-800 font-bold text-lg mb-4">Tren Pendapatan Bulanan</h3>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Row 2: Package Distribution & Real-time Log -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Distribusi Paket -->
        <div class="glass-card rounded-2xl p-6 animate-fade-in lg:col-span-1">
            <h3 class="text-gray-800 font-bold text-lg mb-4">Distribusi Paket Lembaga</h3>
            <div class="h-64 relative">
                <canvas id="packageChart"></canvas>
            </div>
            <div class="mt-4 space-y-2">
                @foreach($packageDistribution as $pkg)
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full" style="background-color: {{ $pkg['color'] }}"></span>
                            <span class="text-gray-600">{{ $pkg['label'] }}</span>
                        </div>
                        <span class="font-medium text-gray-900">{{ $pkg['count'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Real-time Verifikasi -->
        <div class="glass-card rounded-2xl p-6 animate-fade-in lg:col-span-2">
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

            <div class="space-y-3 max-h-[340px] overflow-y-auto" id="realtimeLog">
                @foreach($recentVerifications as $index => $log)
                    @php
                        $isBlockchain = $log->action === 'blockchain_verification';
                        $title = $isBlockchain ? 'Verifikasi Blockchain' : 'Verifikasi Standar';

                        // Parse Certificate Number / Hash from description if subject is missing
                        if ($isBlockchain) {
                            $certNumber = str_replace('Verifikasi Blockchain: ', '', $log->description);
                        } else {
                            // Try to get HASH from subject relation first (User request: "hash kalo seperti kirim bagikan")
                            $certNumber = $log->subject->hash ?? null;

                            // Fallback: extract from description if subject/hash missing
                            if (!$certNumber) {
                                if (preg_match('/Sertifikat\s+(\S+)\s+diverifikasi/i', $log->description, $matches)) {
                                    $certNumber = $matches[1];
                                } else {
                                    $certNumber = 'Unknown';
                                }
                            }
                        }

                        $iconBg = $isBlockchain ? 'bg-purple-100' : 'bg-blue-100';
                        $iconColor = $isBlockchain ? 'text-purple-600' : 'text-blue-600';
                    @endphp
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg animate-slide-in-left"
                        style="animation-delay: {{ $index * 0.1 }}s">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 {{ $iconBg }} rounded-lg flex items-center justify-center flex-shrink-0">
                                @if($isBlockchain)
                                    <!-- Cube Icon for Blockchain -->
                                    <svg class="w-4 h-4 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                @else
                                    <!-- Shield Icon for Standard -->
                                    <svg class="w-4 h-4 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-gray-800 text-sm font-medium truncate">{{ $title }}</p>
                                <p class="text-gray-500 text-xs font-mono truncate max-w-[160px] md:max-w-xs lg:max-w-none">
                                    {{ $certNumber }}</p>
                            </div>
                        </div>
                        <span
                            class="text-gray-400 text-xs flex-shrink-0 ml-2">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach

                @if($recentVerifications->count() === 0)
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-gray-400 text-sm">Belum ada aktivitas verifikasi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Certificate Trend Chart (Image 2: Stepped Line)
            const certCtx = document.getElementById('certificateChart').getContext('2d');
            new Chart(certCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(collect($certificatesTrend)->pluck('month')) !!},
                    datasets: [{
                        label: 'Sertifikat',
                        data: {!! json_encode(collect($certificatesTrend)->pluck('count')) !!},
                        borderColor: '#EF4444', // Red
                        backgroundColor: 'rgba(239, 68, 68, 0.2)',
                        fill: false,
                        stepped: true, // Stepped Line
                        tension: 0
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

            // Verification Activity Chart (Image 1: Line Chart - Straight)
            const verifyCtx = document.getElementById('verificationChart').getContext('2d');
            new Chart(verifyCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(collect($verificationActivity)->pluck('month')) !!},
                    datasets: [
                        {
                            label: 'Verifikasi Standar',
                            data: {!! json_encode(collect($verificationActivity)->pluck('standard')) !!},
                            borderColor: '#3B82F6', // Blue
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            tension: 0, // Straight lines
                            fill: false
                        },
                        {
                            label: 'Verifikasi Blockchain',
                            data: {!! json_encode(collect($verificationActivity)->pluck('blockchain')) !!},
                            borderColor: '#8B5CF6', // Purple
                            backgroundColor: 'rgba(139, 92, 246, 0.5)',
                            tension: 0, // Straight lines
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'top' }
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

            // Revenue Trend Chart (Image 3: Point Styling)
            const revCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(collect($revenueTrend)->pluck('month')) !!},
                    datasets: [{
                        label: 'Pendapatan (IDR)',
                        data: {!! json_encode(collect($revenueTrend)->pluck('count')) !!},
                        borderColor: '#10B981', // Green
                        backgroundColor: 'rgba(16, 185, 129, 0.5)',
                        borderWidth: 2,
                        pointStyle: 'circle',
                        pointRadius: 10,
                        pointHoverRadius: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        try {
                                            label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                        } catch (e) {
                                            label += context.parsed.y;
                                        }
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: {
                                callback: function (value) {
                                    try {
                                        return new Intl.NumberFormat('id-ID', { notation: "compact", compactDisplay: "short" }).format(value);
                                    } catch (e) {
                                        return value;
                                    }
                                }
                            }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // Package Distribution Chart
            const packageCtx = document.getElementById('packageChart').getContext('2d');
            new Chart(packageCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($packageDistribution->pluck('label')) !!},
                    datasets: [{
                        data: {!! json_encode($packageDistribution->pluck('count')) !!},
                        backgroundColor: {!! json_encode($packageDistribution->pluck('color')) !!},
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false } // Custom legend implemented below
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
</x-layouts.admin>