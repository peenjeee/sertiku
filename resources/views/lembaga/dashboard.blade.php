<x-layouts.lembaga>
    <div class="space-y-6 lg:space-y-8">
        <!-- Welcome Banner -->
        <div class="welcome-banner relative rounded-2xl lg:rounded-3xl p-5 lg:p-8 overflow-hidden animate-fade-in">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl text-white font-bold mb-1 lg:mb-2">Selamat Datang,
                        {{ Auth::user()->institution_name ?? Auth::user()->name }}!
                    </h1>
                    <p class="text-[#DBEAFE] text-sm lg:text-lg">Kelola dan terbitkan sertifikat digital dengan mudah
                    </p>
                </div>
                <div class="opacity-30 hidden sm:block">
                    <svg class="w-16 h-16 lg:w-24 lg:h-24" fill="none" stroke="white" viewBox="0 0 24 24"
                        stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
            </div>
        </div>

        @php
            $user = Auth::user();
            $isStarterPlan = $user->isStarterPlan();
            $certificateLimit = $user->getCertificateLimit();
            $certificatesUsed = $user->getCertificatesUsedThisMonth();
            $usagePercentage = $user->getUsagePercentage();
            $remainingCerts = $user->getRemainingCertificates();
        @endphp

        <!-- Upgrade Banner for Starter Plan -->
        @if($isStarterPlan)
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-2 border-amber-300 rounded-xl p-3 lg:p-6">
                <div class="flex flex-col gap-3">
                    <!-- Header -->
                    <div class="flex items-start gap-3">
                        <div
                            class="hidden sm:flex w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg lg:rounded-xl items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-[#92400E] font-bold text-sm lg:text-lg">Paket Starter</h3>
                            <p class="text-[#B45309] text-xs lg:text-sm">
                                Upgrade ke <strong>Professional</strong> untuk mendapatkan hingga 5.000 sertifikat/bulan
                            </p>
                        </div>
                    </div>

                    <!-- Usage Progress -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[#92400E] text-xs lg:text-sm font-medium">Penggunaan</span>
                            <span class="text-[#B45309] text-xs lg:text-sm font-bold">{{ $certificatesUsed }} /
                                {{ $certificateLimit }} sertifikat</span>
                        </div>
                        <div class="w-full h-2 lg:h-3 bg-amber-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 rounded-full"
                                style="width: {{ $usagePercentage }}%"></div>
                        </div>
                        @if($remainingCerts <= 10)
                            <p class="text-red-600 text-xs font-bold mt-1 flex items-center gap-1"><svg class="w-3 h-3"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg> Sisa {{ $remainingCerts }} sertifikat!</p>
                        @endif
                    </div>

                    {{-- Upgrade Button --}}
                    <a href="{{ route('checkout', 'professional') }}"
                        class="flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-2 lg:py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-bold rounded-lg hover:from-amber-600 hover:to-orange-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        Upgrade ke Professional
                    </a>
                </div>
            </div>
        @endif

        <!-- Quick Action Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Terbitkan Sertifikat -->
            @if($user->canIssueCertificate())
                <a href="{{ route('lembaga.sertifikat.create') }}"
                    class="gradient-card-blue rounded-xl lg:rounded-2xl p-4 lg:p-6 hover:scale-[1.02] transition cursor-pointer hover-lift animate-fade-in-up stagger-1">
                    <div class="flex items-center gap-3 lg:gap-4">
                        <div
                            class="icon-circle-blue w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[#1E3A8A] font-bold text-sm lg:text-lg">Terbitkan Sertifikat</h3>
                            <p class="text-[#3B82F6] text-xs lg:text-sm">Sisa kuota:
                                {{ $remainingCerts }}/{{ $certificateLimit }}
                            </p>
                        </div>
                    </div>
                </a>
            @else
                <div
                    class="relative rounded-xl lg:rounded-2xl p-4 lg:p-6 bg-gradient-to-br from-red-50 to-red-100 border-2 border-red-200 opacity-80 cursor-not-allowed animate-fade-in-up stagger-1">
                    <div class="flex items-center gap-3 lg:gap-4">
                        <div
                            class="bg-red-400 w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-red-700 font-bold text-sm lg:text-lg">Kuota Habis</h3>
                            <p class="text-red-500 text-xs lg:text-sm">{{ $certificatesUsed }}/{{ $certificateLimit }}
                                terpakai</p>
                        </div>
                    </div>
                    <a href="{{ url('/#harga') }}"
                        class="absolute inset-0 flex items-center justify-center bg-red-900/80 rounded-xl lg:rounded-2xl opacity-0 hover:opacity-100 transition-opacity">
                        <span class="text-white font-bold text-sm">Upgrade Paket →</span>
                    </a>
                </div>
            @endif

            <!-- Galeri Template -->
            <a href="{{ route('lembaga.template.index') }}"
                class="gradient-card-green rounded-xl lg:rounded-2xl p-4 lg:p-6 hover:scale-[1.02] transition cursor-pointer hover-lift animate-fade-in-up stagger-2">
                <div class="flex items-center gap-3 lg:gap-4">
                    <div
                        class="icon-circle-green w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[#166534] font-bold text-sm lg:text-lg">Galeri Sertifikat</h3>
                        <p class="text-[#22C55E] text-xs lg:text-sm">{{ $stats['total_templates'] ?? 0 }} template
                            tersimpan</p>
                    </div>
                </div>
            </a>

            <!-- Daftar Sertifikat -->
            <a href="{{ route('lembaga.sertifikat.index') }}"
                class="gradient-card-purple rounded-xl lg:rounded-2xl p-4 lg:p-6 hover:scale-[1.02] transition cursor-pointer hover-lift animate-fade-in-up stagger-3">
                <div class="flex items-center gap-3 lg:gap-4">
                    <div
                        class="icon-circle-purple w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[#7C3AED] font-bold text-sm lg:text-lg">Daftar Sertifikat</h3>
                        <p class="text-[#A855F7] text-xs lg:text-sm">Kelola sertifikat</p>
                    </div>
                </div>
            </a>

            @php
                $activePackage = $user->getActivePackage();
                $canAccessApi = $activePackage && in_array($activePackage->slug, ['professional', 'enterprise']);
            @endphp

            @if($canAccessApi)
                <!-- API Tokens - Only for Professional/Enterprise -->
                <a href="{{ route('lembaga.api-tokens.index') }}"
                    class="bg-gradient-to-br from-cyan-50 to-sky-100 border border-cyan-200 rounded-xl lg:rounded-2xl p-4 lg:p-6 hover:scale-[1.02] transition cursor-pointer hover-lift animate-fade-in-up stagger-4">
                    <div class="flex items-center gap-3 lg:gap-4">
                        <div
                            class="bg-gradient-to-br from-cyan-500 to-sky-600 w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[#0E7490] font-bold text-sm lg:text-lg">API Tokens</h3>
                            <p class="text-[#0891B2] text-xs lg:text-sm">Integrasi API</p>
                        </div>
                    </div>
                </a>
            @endif

            @if($canAccessApi)
                <!-- Buat Template (System) -->
                <a href="{{ route('lembaga.template.create') }}"
                    class="bg-gradient-to-br from-pink-50 to-rose-100 border border-pink-200 rounded-xl lg:rounded-2xl p-4 lg:p-6 hover:scale-[1.02] transition cursor-pointer hover-lift animate-fade-in-up stagger-5">
                    <div class="flex items-center gap-3 lg:gap-4">
                        <div
                            class="bg-gradient-to-br from-pink-500 to-rose-600 w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[#BE185D] font-bold text-sm lg:text-lg">Buat Template</h3>
                            <p class="text-[#DB2777] text-xs lg:text-sm">Auto-generate desain</p>
                        </div>
                    </div>
                </a>

                <!-- Import Data (Bulk) -->
                <a href="{{ route('lembaga.sertifikat.bulk') }}"
                    class="bg-gradient-to-br from-indigo-50 to-violet-100 border border-indigo-200 rounded-xl lg:rounded-2xl p-4 lg:p-6 hover:scale-[1.02] transition cursor-pointer hover-lift animate-fade-in-up stagger-6">
                    <div class="flex items-center gap-3 lg:gap-4">
                        <div
                            class="bg-gradient-to-br from-indigo-500 to-violet-600 w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[#4338CA] font-bold text-sm lg:text-lg">Import Data</h3>
                            <p class="text-[#6366F1] text-xs lg:text-sm">Upload Excel/CSV</p>
                        </div>
                    </div>
                </a>
            @endif
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mt-6">
            <!-- Total Sertifikat -->
            <div class="stat-card-blue rounded-xl lg:rounded-2xl p-4 lg:p-6 hover-lift animate-fade-in-up stagger-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#1E40AF] text-xs lg:text-sm font-medium">Total Sertifikat</p>
                        <p class="text-[#1E3A8A] text-xl lg:text-3xl font-bold mt-1">
                            {{ number_format($stats['total_certificates'] ?? 0) }}
                        </p>
                    </div>
                    <div
                        class="stat-icon-blue w-10 h-10 lg:w-12 lg:h-12 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#3B82F6]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Sertifikat Aktif -->
            <div class="stat-card-green rounded-xl lg:rounded-2xl p-4 lg:p-6 hover-lift animate-fade-in-up stagger-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#166534] text-xs lg:text-sm font-medium">Sertifikat Aktif</p>
                        <p class="text-[#15803D] text-xl lg:text-3xl font-bold mt-1">
                            {{ number_format($stats['active_certificates'] ?? 0) }}
                        </p>
                    </div>
                    <div
                        class="stat-icon-green w-10 h-10 lg:w-12 lg:h-12 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#22C55E]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Bulan Ini -->
            <div class="stat-card-orange rounded-xl lg:rounded-2xl p-4 lg:p-6 hover-lift animate-fade-in-up stagger-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9A3412] text-xs lg:text-sm font-medium">Bulan Ini</p>
                        <p class="text-[#C2410C] text-xl lg:text-3xl font-bold mt-1">
                            {{ number_format($stats['certificates_this_month'] ?? 0) }}
                        </p>
                    </div>
                    <div
                        class="stat-icon-orange w-10 h-10 lg:w-12 lg:h-12 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#F97316]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Verifikasi -->
            <div class="stat-card-purple rounded-xl lg:rounded-2xl p-4 lg:p-6 hover-lift animate-fade-in-up stagger-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#6B21A8] text-xs lg:text-sm font-medium">Total Verifikasi</p>
                        <p class="text-[#7C3AED] text-xl lg:text-3xl font-bold mt-1">
                            {{ number_format($stats['total_verifications'] ?? 0) }}
                        </p>
                    </div>
                    <div
                        class="stat-icon-purple w-10 h-10 lg:w-12 lg:h-12 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#A855F7]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Certificates Card -->
        <div class="glass-card rounded-xl lg:rounded-2xl overflow-hidden animate-fade-in">
            <!-- Header -->
            <div class="header-gradient p-4 lg:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#3B82F6]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <div>
                        <h2 class="text-[#1E293B] text-base lg:text-xl font-bold">Sertifikat Terbaru</h2>
                        <p class="text-[#64748B] text-xs lg:text-sm">{{ $stats['recent_certificates']->count() }}
                            sertifikat yang baru diterbitkan</p>
                    </div>
                </div>
                <a href="{{ route('lembaga.sertifikat.index') }}"
                    class="flex items-center justify-center gap-2 px-3 lg:px-4 py-2 lg:py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-[#1E293B] text-xs lg:text-sm hover:bg-gray-50 transition shadow-sm">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>

            <!-- Content -->
            <div class="p-4 lg:p-6">
                <!-- Search & Filter -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 mb-4 lg:mb-6">
                    <div class="relative flex-1 lg:max-w-xl">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8]" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="search-input" placeholder="Cari nama atau ID..." onkeyup="filterTable()"
                            class="w-full pl-10 pr-4 py-2.5 lg:py-3 bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg text-sm text-[#1E293B] placeholder-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="flex items-center gap-2 overflow-x-auto" id="filter-buttons">
                        <button onclick="setFilter('all')" data-filter="all"
                            class="filter-btn active px-3 lg:px-4 py-2 lg:py-3 bg-[#1E3A8F] text-white text-xs lg:text-sm rounded-lg font-medium whitespace-nowrap">Semua</button>
                        <button onclick="setFilter('active')" data-filter="active"
                            class="filter-btn px-3 lg:px-4 py-2 lg:py-3 bg-white border border-[#E2E8F0] text-[#1E293B] text-xs lg:text-sm rounded-lg hover:bg-gray-50 whitespace-nowrap">Aktif</button>
                        <button onclick="setFilter('expired')" data-filter="expired"
                            class="filter-btn px-3 lg:px-4 py-2 lg:py-3 bg-white border border-[#E2E8F0] text-[#1E293B] text-xs lg:text-sm rounded-lg hover:bg-gray-50 whitespace-nowrap">Kadaluarsa</button>
                        <button onclick="setFilter('revoked')" data-filter="revoked"
                            class="filter-btn px-3 lg:px-4 py-2 lg:py-3 bg-white border border-[#E2E8F0] text-[#1E293B] text-xs lg:text-sm rounded-lg hover:bg-gray-50 whitespace-nowrap">Dicabut</button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#E2E8F0]">
                                <th class="text-left py-3 px-4 text-[#64748B] text-sm font-medium">Penerima</th>
                                <th class="text-left py-3 px-4 text-[#64748B] text-sm font-medium">Program</th>
                                <th class="text-left py-3 px-4 text-[#64748B] text-sm font-medium">ID Kredensial</th>
                                <th class="text-left py-3 px-4 text-[#64748B] text-sm font-medium">Tanggal</th>
                                <th class="text-left py-3 px-4 text-[#64748B] text-sm font-medium">Status</th>
                                <th class="text-right py-3 px-4 text-[#64748B] text-sm font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="certificate-tbody">
                            @forelse($stats['recent_certificates'] ?? [] as $cert)
                                @php
                                    $colors = ['from-blue-500 to-indigo-600', 'from-emerald-500 to-teal-600', 'from-purple-500 to-pink-600', 'from-orange-500 to-red-600', 'from-cyan-500 to-blue-600'];
                                    $colorIndex = $loop->index % count($colors);
                                    $initials = collect(explode(' ', $cert->recipient_name))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->join('');

                                    // Check if recipient is registered in system
                                    $registeredUser = null;
                                    $avatarUrl = null;

                                    if ($cert->recipient_email) {
                                        $registeredUser = \App\Models\User::where('email', $cert->recipient_email)->first();

                                        if ($registeredUser && $registeredUser->avatar && (str_starts_with($registeredUser->avatar, '/storage/') || str_starts_with($registeredUser->avatar, 'http'))) {
                                            // User is registered and has custom or Google avatar
                                            $avatarUrl = $registeredUser->avatar;
                                        } else {
                                            // User not registered or no avatar - use UI Avatars
                                            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($cert->recipient_name) . '&email=' . urlencode($cert->recipient_email) . '&background=random&color=fff&bold=true&size=40';
                                        }
                                    } else {
                                        // No email - use UI Avatars with name only
                                        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($cert->recipient_name) . '&background=random&color=fff&bold=true&size=40';
                                    }

                                    // Calculate virtual status for filter
                                    $virtualStatus = $cert->status;
                                    if ($cert->status !== 'revoked' && $cert->expire_date && $cert->expire_date < now()) {
                                        $virtualStatus = 'expired';
                                    }
                                @endphp
                                <tr class="border-b border-[#F1F5F9] hover:bg-[#F8FAFC] cert-row"
                                    data-status="{{ $virtualStatus }}" data-name="{{ strtolower($cert->recipient_name) }}"
                                    data-cert="{{ strtolower($cert->certificate_number) }}">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-gradient-to-br {{ $colors[$colorIndex] }} flex items-center justify-center overflow-hidden">
                                                @if($avatarUrl)
                                                    <img src="{{ $avatarUrl }}" alt="Avatar" class="w-full h-full object-cover">
                                                @else
                                                    <span class="text-white font-bold text-sm">{{ $initials }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-[#1E293B] font-medium">{{ $cert->recipient_name }}</p>
                                                <p class="text-[#94A3B8] text-sm">
                                                    {{ $cert->recipient_email ?? 'Email tidak tersedia' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-[#1E293B]">{{ $cert->course_name }}</td>
                                    <td class="py-4 px-4">
                                        <code
                                            class="text-[#3B82F6] bg-[#EFF6FF] px-2 py-1 rounded text-sm">{{ Str::limit($cert->certificate_number, 14) }}</code>
                                    </td>
                                    <td class="py-4 px-4 text-[#64748B]">{{ $cert->issue_date->format('d M Y') }}</td>
                                    <td class="py-4 px-4">
                                        @if($cert->status === 'revoked')
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#FEF2F2] text-[#DC2626] text-sm rounded-full font-medium">
                                                <span class="w-1.5 h-1.5 bg-[#DC2626] rounded-full"></span>
                                                Dicabut
                                            </span>
                                        @elseif($cert->expire_date && $cert->expire_date < now())
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#FEF3C7] text-[#D97706] text-sm rounded-full font-medium">
                                                <span class="w-1.5 h-1.5 bg-[#D97706] rounded-full"></span>
                                                Kadaluarsa
                                            </span>
                                        @elseif($cert->status === 'active')
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#ECFDF5] text-[#059669] text-sm rounded-full font-medium">
                                                <span class="w-1.5 h-1.5 bg-[#059669] rounded-full"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#F1F5F9] text-[#475569] text-sm rounded-full font-medium">
                                                <span class="w-1.5 h-1.5 bg-[#475569] rounded-full"></span>
                                                {{ ucfirst($cert->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <a href="{{ route('verifikasi.show', $cert->hash) }}" target="_blank"
                                            class="text-[#3B82F6] hover:text-[#1D4ED8] text-sm font-medium">Lihat</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-gray-600 font-medium mb-1">Belum ada sertifikat</p>
                                            <p class="text-gray-400 text-sm mb-4">Mulai terbitkan sertifikat pertama Anda
                                            </p>
                                            <a href="{{ route('lembaga.sertifikat.create') }}"
                                                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">Terbitkan
                                                Sekarang</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter and Search JavaScript --}}
    <script>
        let currentFilter = 'all';

        function setFilter(filter) {
            currentFilter = filter;

            // Update button styles
            document.querySelectorAll('.filter-btn').forEach(btn => {
                if (btn.dataset.filter === filter) {
                    btn.classList.remove('bg-white', 'border', 'border-[#E2E8F0]', 'text-[#1E293B]');
                    btn.classList.add('bg-[#1E3A8F]', 'text-white');
                } else {
                    btn.classList.remove('bg-[#1E3A8F]', 'text-white');
                    btn.classList.add('bg-white', 'border', 'border-[#E2E8F0]', 'text-[#1E293B]');
                }
            });

            filterTable();
        }

        function filterTable() {
            const searchTerm = document.getElementById('search-input')?.value.toLowerCase() || '';
            const rows = document.querySelectorAll('.cert-row');

            rows.forEach(row => {
                const status = row.dataset.status;
                const name = row.dataset.name || '';
                const cert = row.dataset.cert || '';

                // Check filter
                let matchesFilter = currentFilter === 'all' || status === currentFilter;

                // Check search
                let matchesSearch = searchTerm === '' ||
                    name.includes(searchTerm) ||
                    cert.includes(searchTerm);

                // Show/hide row
                if (matchesFilter && matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>

</x-layouts.lembaga>