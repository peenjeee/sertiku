{{-- resources/views/user/dashboard.blade.php --}}
<x-layouts.user title="Dashboard – SertiKu">

    {{-- Welcome Banner --}}
    <div class="welcome-banner rounded-2xl lg:rounded-3xl p-5 lg:p-8 mb-6 animate-fade-in-up">
        <h1 class="text-xl lg:text-2xl font-bold text-white mb-4">
            Selamat Datang Kembali, {{ auth()->user()->name ?? 'User' }}! ✨
        </h1>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('user.sertifikat') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 border border-white/20 text-white text-sm hover:bg-white/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Lihat Sertifikat
            </a>
            <a href="{{ route('user.profil') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm hover:brightness-110 transition">
                Edit Profil
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Sertifikat --}}
        <div class="stat-card-blue rounded-xl lg:rounded-2xl p-4 lg:p-5 animate-fade-in-up stagger-1">
            @if(isset($stats['growth_sertifikat']) && $stats['growth_sertifikat'] > 0)
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <span class="text-green-400 text-xs flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        +{{ $stats['growth_sertifikat'] }}
                    </span>
                </div>
            @else
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                </div>
            @endif
            <p class="text-2xl lg:text-3xl font-bold text-white">{{ $stats['total_sertifikat'] }}</p>
            <p class="text-white/60 text-xs lg:text-sm mt-1">Total Sertifikat</p>
        </div>

        {{-- Terverifikasi --}}
        <div class="stat-card-green rounded-xl lg:rounded-2xl p-4 lg:p-5 animate-fade-in-up stagger-2">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-yellow-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl lg:text-3xl font-bold text-white">{{ $stats['terverifikasi'] }}</p>
            <p class="text-white/60 text-xs lg:text-sm mt-1">Terverifikasi</p>
        </div>

        {{-- Total Verifikasi --}}
        <div class="stat-card rounded-xl lg:rounded-2xl p-4 lg:p-5 animate-fade-in-up stagger-3">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl lg:text-3xl font-bold text-white">{{ $stats['total_verifikasi'] }}</p>
            <p class="text-white/60 text-xs lg:text-sm mt-1">Total Verifikasi</p>
        </div>

        {{-- Pending --}}
        <div class="stat-card-red rounded-xl lg:rounded-2xl p-4 lg:p-5 animate-fade-in-up stagger-4">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-red-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl lg:text-3xl font-bold text-white">{{ $stats['pending'] }}</p>
            <p class="text-white/60 text-xs lg:text-sm mt-1">Pending</p>
        </div>
    </div>

    {{-- Charts & Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Activity Chart --}}
        <div class="lg:col-span-2 activity-card rounded-2xl p-5 lg:p-6 animate-fade-in-up">
            <h2 class="text-lg font-semibold text-white mb-4">Aktivitas Sertifikat</h2>
            <div class="h-64">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        {{-- Aktivitas Terbaru --}}
        <div class="activity-card rounded-2xl p-5 lg:p-6 animate-fade-in-up">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Aktivitas Terbaru</h2>
                <button class="text-white/50 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>
            </div>
            <div class="space-y-4">
                @forelse($recentActivity as $activity)
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 rounded-full mt-2
                                @if($activity['type'] === 'new') bg-blue-400
                                @elseif($activity['type'] === 'view') bg-yellow-400
                                @else bg-purple-400 @endif
                            "></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-medium">{{ $activity['title'] }}</p>
                            <p class="text-white/50 text-xs truncate">{{ $activity['subtitle'] }}</p>
                            <p class="text-white/40 text-xs mt-1">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-white/50 text-sm text-center py-4">Belum ada aktivitas</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Sertifikat Terbaru --}}
    <div class="glass-card rounded-2xl p-5 lg:p-6 animate-fade-in-up">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-white">Sertifikat Terbaru</h2>
                <p class="text-white/50 text-sm">{{ $recentCertificates->count() }} sertifikat terakhir yang Anda
                    dapatkan</p>
            </div>
            <a href="{{ route('user.sertifikat') }}" class="text-blue-400 text-sm hover:text-blue-300">
                Lihat Semua →
            </a>
        </div>

        @if($recentCertificates->count() > 0)
            <div class="grid gap-4">
                @foreach($recentCertificates as $cert)
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/10">
                        <div
                            class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-medium truncate">{{ $cert->title ?? 'Sertifikat' }}</p>
                            <p class="text-white/50 text-sm">{{ $cert->issuer->name ?? 'Lembaga' }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs
                                    @if($cert->status === 'active') bg-green-500/20 text-green-400
                                    @elseif($cert->status === 'pending') bg-yellow-500/20 text-yellow-400
                                    @else bg-red-500/20 text-red-400 @endif
                                ">
                            @if($cert->pdf_url)
                                <a href="{{ $cert->pdf_url }}" target="_blank" class="text-white/40 hover:text-white transition"
                                    title="Download PDF">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-white/5 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <p class="text-white/50">Belum ada sertifikat</p>
                <p class="text-white/30 text-sm mt-1">Sertifikat akan muncul di sini setelah diterbitkan oleh lembaga</p>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            // Activity Chart
            const ctx = document.getElementById('activityChart').getContext('2d');
            const chartData = @json($chartData);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.map(d => d.month),
                    datasets: [{
                        label: 'Sertifikat',
                        data: chartData.map(d => d.count),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#3B82F6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(255,255,255,0.05)' },
                            ticks: { color: 'rgba(255,255,255,0.5)' }
                        },
                        y: {
                            grid: { color: 'rgba(255,255,255,0.05)' },
                            ticks: { color: 'rgba(255,255,255,0.5)', stepSize: 1 },
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endpush

</x-layouts.user>