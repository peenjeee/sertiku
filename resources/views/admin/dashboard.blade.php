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
        <!-- Total Pengguna -->
        <div class="stat-card-blue rounded-2xl p-5 hover-lift animate-fade-in-up stagger-1">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <p class="text-gray-600 text-sm font-medium">Total Pengguna</p>
                    </div>
                    <p class="text-gray-800 text-3xl font-bold">{{ number_format($stats['total_users']) }}</p>
                    <p class="text-gray-500 text-xs mt-1">Pengguna terdaftar</p>
                </div>
                <div class="w-12 h-12 icon-circle-blue rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Lembaga -->
        <div class="stat-card-green rounded-2xl p-5 hover-lift animate-fade-in-up stagger-2">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <p class="text-gray-600 text-sm font-medium">Total Lembaga</p>
                    </div>
                    <p class="text-gray-800 text-3xl font-bold">{{ number_format($stats['total_lembaga']) }}</p>
                    <p class="text-gray-500 text-xs mt-1">Lembaga penerbit</p>
                </div>
                <div class="w-12 h-12 icon-circle-green rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
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
    </div>

    <!-- Stats Row 2 -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 mb-8">
        <!-- Sertifikat Aktif -->
        <div class="stat-card-green rounded-2xl p-5 hover-lift animate-fade-in-up stagger-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="text-gray-600 text-sm font-medium">Sertifikat Aktif</p>
                    </div>
                    <p class="text-emerald-600 text-3xl font-bold">{{ number_format($stats['sertifikat_aktif']) }}</p>
                    <p class="text-gray-500 text-xs mt-1">
                        {{ $stats['sertifikat_aktif'] > 0 ? 'Aktif dan valid' : 'Belum ada data' }}</p>
                </div>
                <div class="w-12 h-12 icon-circle-green rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sertifikat Dicabut -->
        <div class="stat-card-red rounded-2xl p-5 hover-lift animate-fade-in-up stagger-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        <p class="text-gray-600 text-sm font-medium">Sertifikat Dicabut</p>
                    </div>
                    <p class="text-red-600 text-3xl font-bold">{{ number_format($stats['sertifikat_dicabut']) }}</p>
                    <p class="text-gray-500 text-xs mt-1">
                        {{ $stats['sertifikat_dicabut'] > 0 ? 'Dicabut' : 'Belum ada data' }}</p>
                </div>
                <div class="w-12 h-12 icon-circle-red rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Verifikasi -->
        <div class="stat-card-orange rounded-2xl p-5 hover-lift animate-fade-in-up stagger-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <p class="text-gray-600 text-sm font-medium">Total Verifikasi</p>
                    </div>
                    <p class="text-orange-600 text-3xl font-bold">{{ number_format($stats['total_verifikasi']) }}</p>
                    <p class="text-gray-500 text-xs mt-1">
                        {{ $stats['total_verifikasi'] > 0 ? 'Permintaan verifikasi' : 'Belum ada data' }}</p>
                </div>
                <div class="w-12 h-12 icon-circle-orange rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Sertifikat Terbaru -->
    <div class="glass-card rounded-2xl p-6 mb-8 animate-fade-in">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-gray-800 text-lg font-bold">Sertifikat Terbaru</h2>
            <a href="{{ route('admin.analytics') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Lihat
                Semua</a>
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
                                    @if($cert->status === 'active')
                                        <span
                                            class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full">Aktif</span>
                                    @else
                                        <span
                                            class="px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">Dicabut</span>
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