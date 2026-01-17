<x-layouts.admin>
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8 animate-fade-in">
        <div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold">Dashboard Admin</h1>
            <p class="text-white/60 text-sm mt-1">Statistik dan monitoring platform SertiKu</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 px-4 py-2 bg-emerald-500/20 border border-emerald-500/30 rounded-lg">
                <span class="w-2 h-2 bg-emerald-500 rounded-full live-dot"></span>
                <span class="text-emerald-400 text-sm font-medium">Live Update</span>
            </div>
        </div>
    </div>

    <!-- Stats Row 1 -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 mb-6">
        <!-- Total Pendapatan -->
        <div class="stat-card-blue rounded-2xl p-5 hover-lift animate-fade-in-up stagger-1">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-600 text-sm font-medium">Total Pendapatan</p>
                    </div>
                    <p class="text-gray-800 text-2xl font-bold">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                    <p class="text-gray-500 text-xs mt-1">Total semua pendapatan</p>
                </div>
                <div class="w-12 h-12 icon-circle-blue rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pendapatan Bulan Ini -->
        <div class="stat-card-green rounded-2xl p-5 hover-lift animate-fade-in-up stagger-2">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="text-gray-600 text-sm font-medium">Pendapatan Bulan Ini</p>
                    </div>
                    <p class="text-gray-800 text-2xl font-bold">Rp {{ number_format($stats['pendapatan_bulan_ini'], 0, ',', '.') }}</p>
                    <p class="text-gray-500 text-xs mt-1">Bulan {{ now()->translatedFormat('F') }}</p>
                </div>
                <div class="w-12 h-12 icon-circle-green rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Sertifikat -->
        <div class="stat-card-purple rounded-2xl p-5 hover-lift animate-fade-in-up stagger-3">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        <p class="text-gray-600 text-sm font-medium">Total Sertifikat</p>
                    </div>
                    <p class="text-gray-800 text-3xl font-bold">{{ number_format($stats['total_sertifikat']) }}</p>
                    <p class="text-gray-500 text-xs mt-1">Sertifikat diterbitkan</p>
                </div>
                <div class="w-12 h-12 icon-circle-purple rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sertifikat Aktif -->
        <div class="stat-card-green rounded-2xl p-5 hover-lift animate-fade-in-up stagger-1">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-600 text-sm font-medium">Sertifikat Aktif</p>
                    </div>
                    <p class="text-gray-800 text-2xl font-bold">{{ number_format($stats['sertifikat_aktif']) }}</p>
                    <p class="text-gray-500 text-xs mt-1">Sertifikat yang masih berlaku</p>
                </div>
                <div class="w-12 h-12 icon-circle-green rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sertifikat Expired -->
        <div class="stat-card-orange rounded-2xl p-5 hover-lift animate-fade-in-up stagger-2">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-600 text-sm font-medium">Sertifikat Kedaluwarsa</p>
                    </div>
                    <p class="text-gray-800 text-2xl font-bold">{{ number_format($stats['sertifikat_kedaluarsa']) }}</p>
                    <p class="text-gray-500 text-xs mt-1">Masa berlaku habis</p>
                </div>
                <div class="w-12 h-12 icon-circle-orange rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sertifikat Dicabut -->
        <div class="stat-card-red rounded-2xl p-5 hover-lift animate-fade-in-up stagger-3">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-600 text-sm font-medium">Sertifikat Dicabut</p>
                    </div>
                    <p class="text-gray-800 text-2xl font-bold">{{ number_format($stats['sertifikat_dicabut']) }}</p>
                    <p class="text-gray-500 text-xs mt-1">Sertifikat tidak valid</p>
                </div>
                <div class="w-12 h-12 icon-circle-red rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Sertifikat Terbaru -->
    <div class="glass-card rounded-2xl p-6 mb-8 animate-fade-in">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-gray-800 text-lg font-bold">Sertifikat Terbaru</h2>
            <!-- <a href="{{ route('admin.analytics') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Lihat
                Semua</a> -->
        </div>

        @if($recentCertificates->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left text-gray-500 text-xs font-medium py-3 px-2">Nomor</th>
                            <th class="text-left text-gray-500 text-xs font-medium py-3 px-2">Penerima</th>
                            <th class="text-left text-gray-500 text-xs font-medium py-3 px-2">Penerbit</th>
                            <th class="text-left text-gray-500 text-xs font-medium py-3 px-2">Tanggal</th>
                            <th class="text-left text-gray-500 text-xs font-medium py-3 px-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentCertificates as $cert)
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                <td class="py-3 px-2">
                                    <span
                                        class="text-gray-800 text-sm font-mono">{{ Str::limit($cert->certificate_number, 18) }}</span>
                                </td>
                                <td class="py-3 px-2">
                                    <span class="text-gray-700 text-sm">{{ $cert->recipient_name }}</span>
                                </td>
                                <td class="py-3 px-2">
                                    <span
                                        class="text-gray-600 text-sm">{{ $cert->user->institution_name ?? $cert->user->name }}</span>
                                </td>
                                <td class="py-3 px-2">
                                    <span class="text-gray-500 text-sm">{{ $cert->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="py-3 px-2">
                                    @if($cert->status === 'revoked')
                                        <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">Dicabut</span>
                                    @elseif($cert->expire_date && $cert->expire_date < now())
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">Kadaluarsa</span>
                                    @elseif($cert->status === 'active')
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full">Aktif</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">{{ ucfirst($cert->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-400 text-sm">Belum ada sertifikat yang diterbitkan</p>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 animate-fade-in">
        <!-- Kelola Pengguna -->
        <a href="{{ route('admin.users') }}"
            class="glass-card rounded-2xl p-5 hover-lift flex items-center gap-4 group">
            <div class="w-12 h-12 icon-circle-blue rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-gray-800 font-bold group-hover:text-blue-600 transition">Kelola Pengguna</h3>
                <p class="text-gray-500 text-sm">Lihat dan kelola semua pengguna</p>
            </div>
        </a>

        <!-- Analytics -->
        <a href="{{ route('admin.analytics') }}"
            class="glass-card rounded-2xl p-5 hover-lift flex items-center gap-4 group">
            <div class="w-12 h-12 icon-circle-purple rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div>
                <h3 class="text-gray-800 font-bold group-hover:text-purple-600 transition">Analytics</h3>
                <p class="text-gray-500 text-sm">Lihat statistik lengkap</p>
            </div>
        </a>

        <!-- Backup & Export -->
        <a href="{{ route('admin.backup') }}"
            class="glass-card rounded-2xl p-5 hover-lift flex items-center gap-4 group">
            <div class="w-12 h-12 icon-circle-orange rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
            </div>
            <div>
                <h3 class="text-gray-800 font-bold group-hover:text-orange-600 transition">Backup & Export</h3>
                <p class="text-gray-500 text-sm">Export data platform</p>
            </div>
        </a>
    </div>

</x-layouts.admin>