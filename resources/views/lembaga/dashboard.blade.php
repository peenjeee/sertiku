<x-layouts.lembaga>
    <div class="space-y-6 lg:space-y-8">
        <!-- Welcome Banner -->
        <div class="welcome-banner relative rounded-2xl lg:rounded-3xl p-5 lg:p-8 overflow-hidden">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl text-white font-bold mb-1 lg:mb-2">Selamat Datang, {{ Auth::user()->institution_name ?? Auth::user()->name }}! 👋</h1>
                    <p class="text-[#DBEAFE] text-sm lg:text-lg">Kelola dan terbitkan sertifikat digital dengan mudah</p>
                </div>
                <div class="opacity-30 hidden sm:block">
                    <svg class="w-16 h-16 lg:w-24 lg:h-24" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
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
                    <div class="hidden sm:flex w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg lg:rounded-xl items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-[#92400E] font-bold text-sm lg:text-lg">Paket Starter</h3>
                        <p class="text-[#B45309] text-xs lg:text-sm">
                            Upgrade ke <strong>Professional</strong> untuk mendapatkan hingga 6.700 sertifikat/bulan
                        </p>
                    </div>
                </div>

                <!-- Usage Progress -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[#92400E] text-xs lg:text-sm font-medium">Penggunaan</span>
                        <span class="text-[#B45309] text-xs lg:text-sm font-bold">{{ $certificatesUsed }} / {{ $certificateLimit }} sertifikat</span>
                    </div>
                    <div class="w-full h-2 lg:h-3 bg-amber-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 rounded-full" style="width: {{ $usagePercentage }}%"></div>
                    </div>
                    @if($remainingCerts <= 10)
                    <p class="text-red-600 text-xs font-bold mt-1">⚠️ Sisa {{ $remainingCerts }} sertifikat!</p>
                    @endif
                </div>

                <!-- Upgrade Button -->
                <button type="button" 
                        id="btn-upgrade" 
                        onclick="initiateUpgrade()"
                        class="flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-2 lg:py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-bold rounded-lg hover:from-amber-600 hover:to-orange-600 transition disabled:opacity-50">
                    <span id="btn-upgrade-text" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                        Upgrade ke Professional
                    </span>
                    <span id="btn-upgrade-loading" class="hidden flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </div>
        @endif

        <!-- Quick Action Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Terbitkan Sertifikat -->
            <a href="{{ route('lembaga.sertifikat.create') }}" class="gradient-card-blue rounded-xl lg:rounded-2xl p-4 lg:p-6 hover:scale-[1.02] transition cursor-pointer">
                <div class="flex items-center gap-3 lg:gap-4">
                    <div class="icon-circle-blue w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[#1E3A8A] font-bold text-sm lg:text-lg">Terbitkan Sertifikat</h3>
                        <p class="text-[#3B82F6] text-xs lg:text-sm">Buat sertifikat baru</p>
                    </div>
                </div>
            </a>

            <!-- Galeri Template -->
            <a href="{{ route('lembaga.template.index') }}" class="gradient-card-green rounded-xl lg:rounded-2xl p-4 lg:p-6 hover:scale-[1.02] transition cursor-pointer">
                <div class="flex items-center gap-3 lg:gap-4">
                    <div class="icon-circle-green w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[#166534] font-bold text-sm lg:text-lg">Galeri Template</h3>
                        <p class="text-[#22C55E] text-xs lg:text-sm">3 template tersimpan</p>
                    </div>
                </div>
            </a>

            <!-- Daftar Sertifikat -->
            <a href="{{ route('lembaga.sertifikat.index') }}" class="gradient-card-purple rounded-xl lg:rounded-2xl p-4 lg:p-6 hover:scale-[1.02] transition cursor-pointer">
                <div class="flex items-center gap-3 lg:gap-4">
                    <div class="icon-circle-purple w-10 h-10 lg:w-14 lg:h-14 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[#7C3AED] font-bold text-sm lg:text-lg">Daftar Sertifikat</h3>
                        <p class="text-[#A855F7] text-xs lg:text-sm">Kelola sertifikat</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <!-- Total Sertifikat -->
            <div class="stat-card-blue rounded-xl lg:rounded-2xl p-4 lg:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#1E40AF] text-xs lg:text-sm font-medium">Total Sertifikat</p>
                        <p class="text-[#1E3A8A] text-xl lg:text-3xl font-bold mt-1">127</p>
                    </div>
                    <div class="stat-icon-blue w-10 h-10 lg:w-12 lg:h-12 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Sertifikat Aktif -->
            <div class="stat-card-green rounded-xl lg:rounded-2xl p-4 lg:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#166534] text-xs lg:text-sm font-medium">Sertifikat Aktif</p>
                        <p class="text-[#15803D] text-xl lg:text-3xl font-bold mt-1">124</p>
                    </div>
                    <div class="stat-icon-green w-10 h-10 lg:w-12 lg:h-12 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#22C55E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Bulan Ini -->
            <div class="stat-card-orange rounded-xl lg:rounded-2xl p-4 lg:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9A3412] text-xs lg:text-sm font-medium">Bulan Ini</p>
                        <p class="text-[#C2410C] text-xl lg:text-3xl font-bold mt-1">28</p>
                    </div>
                    <div class="stat-icon-orange w-10 h-10 lg:w-12 lg:h-12 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#F97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Verifikasi -->
            <div class="stat-card-purple rounded-xl lg:rounded-2xl p-4 lg:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#6B21A8] text-xs lg:text-sm font-medium">Total Verifikasi</p>
                        <p class="text-[#7C3AED] text-xl lg:text-3xl font-bold mt-1">1,847</p>
                    </div>
                    <div class="stat-icon-purple w-10 h-10 lg:w-12 lg:h-12 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#A855F7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Certificates Card -->
        <div class="glass-card rounded-xl lg:rounded-2xl overflow-hidden">
            <!-- Header -->
            <div class="header-gradient p-4 lg:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    <div>
                        <h2 class="text-[#1E293B] text-base lg:text-xl font-bold">Sertifikat Terbaru</h2>
                        <p class="text-[#64748B] text-xs lg:text-sm">5 sertifikat yang baru diterbitkan</p>
                    </div>
                </div>
                <a href="{{ route('lembaga.sertifikat.index') }}" class="flex items-center justify-center gap-2 px-3 lg:px-4 py-2 lg:py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-[#1E293B] text-xs lg:text-sm hover:bg-gray-50 transition shadow-sm">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>

            <!-- Content -->
            <div class="p-4 lg:p-6">
                <!-- Search & Filter -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 mb-4 lg:mb-6">
                    <div class="relative flex-1 lg:max-w-xl">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" placeholder="Cari nama atau ID..." class="w-full pl-10 pr-4 py-2.5 lg:py-3 bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg text-sm text-[#1E293B] placeholder-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="flex items-center gap-2 overflow-x-auto">
                        <button class="px-3 lg:px-4 py-2 lg:py-3 bg-[#1E3A8F] text-white text-xs lg:text-sm rounded-lg font-medium whitespace-nowrap">Semua</button>
                        <button class="px-3 lg:px-4 py-2 lg:py-3 bg-white border border-[#E2E8F0] text-[#1E293B] text-xs lg:text-sm rounded-lg hover:bg-gray-50 whitespace-nowrap">Aktif</button>
                        <button class="px-3 lg:px-4 py-2 lg:py-3 bg-white border border-[#E2E8F0] text-[#1E293B] text-xs lg:text-sm rounded-lg hover:bg-gray-50 whitespace-nowrap">Dicabut</button>
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
                        <tbody>
                            <!-- Row 1 -->
                            <tr class="border-b border-[#F1F5F9] hover:bg-[#F8FAFC]">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">AS</div>
                                        <div>
                                            <p class="text-[#1E293B] font-medium">Andi Setiawan</p>
                                            <p class="text-[#94A3B8] text-sm">andi.setiawan@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-[#1E293B]">Web Development Bootcamp</td>
                                <td class="py-4 px-4">
                                    <code class="text-[#3B82F6] bg-[#EFF6FF] px-2 py-1 rounded text-sm">CERT-2025-001</code>
                                </td>
                                <td class="py-4 px-4 text-[#64748B]">10 Des 2025</td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#ECFDF5] text-[#059669] text-sm rounded-full font-medium">
                                        <span class="w-1.5 h-1.5 bg-[#059669] rounded-full"></span>
                                        Aktif
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <button class="text-[#3B82F6] hover:text-[#1D4ED8] text-sm font-medium">Lihat</button>
                                </td>
                            </tr>
                            <!-- Row 2 -->
                            <tr class="border-b border-[#F1F5F9] hover:bg-[#F8FAFC]">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm">SP</div>
                                        <div>
                                            <p class="text-[#1E293B] font-medium">Siti Putri</p>
                                            <p class="text-[#94A3B8] text-sm">siti.putri@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-[#1E293B]">UI/UX Design Fundamentals</td>
                                <td class="py-4 px-4">
                                    <code class="text-[#3B82F6] bg-[#EFF6FF] px-2 py-1 rounded text-sm">CERT-2025-002</code>
                                </td>
                                <td class="py-4 px-4 text-[#64748B]">9 Des 2025</td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#ECFDF5] text-[#059669] text-sm rounded-full font-medium">
                                        <span class="w-1.5 h-1.5 bg-[#059669] rounded-full"></span>
                                        Aktif
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <button class="text-[#3B82F6] hover:text-[#1D4ED8] text-sm font-medium">Lihat</button>
                                </td>
                            </tr>
                            <!-- Row 3 -->
                            <tr class="border-b border-[#F1F5F9] hover:bg-[#F8FAFC]">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-bold text-sm">BR</div>
                                        <div>
                                            <p class="text-[#1E293B] font-medium">Budi Raharjo</p>
                                            <p class="text-[#94A3B8] text-sm">budi.raharjo@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-[#1E293B]">Data Science Workshop</td>
                                <td class="py-4 px-4">
                                    <code class="text-[#3B82F6] bg-[#EFF6FF] px-2 py-1 rounded text-sm">CERT-2025-003</code>
                                </td>
                                <td class="py-4 px-4 text-[#64748B]">8 Des 2025</td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#ECFDF5] text-[#059669] text-sm rounded-full font-medium">
                                        <span class="w-1.5 h-1.5 bg-[#059669] rounded-full"></span>
                                        Aktif
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <button class="text-[#3B82F6] hover:text-[#1D4ED8] text-sm font-medium">Lihat</button>
                                </td>
                            </tr>
                            <!-- Row 4 -->
                            <tr class="border-b border-[#F1F5F9] hover:bg-[#F8FAFC]">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center text-white font-bold text-sm">DW</div>
                                        <div>
                                            <p class="text-[#1E293B] font-medium">Dewi Wulandari</p>
                                            <p class="text-[#94A3B8] text-sm">dewi.wulandari@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-[#1E293B]">Digital Marketing Certification</td>
                                <td class="py-4 px-4">
                                    <code class="text-[#3B82F6] bg-[#EFF6FF] px-2 py-1 rounded text-sm">CERT-2025-004</code>
                                </td>
                                <td class="py-4 px-4 text-[#64748B]">7 Des 2025</td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#FEF2F2] text-[#DC2626] text-sm rounded-full font-medium">
                                        <span class="w-1.5 h-1.5 bg-[#DC2626] rounded-full"></span>
                                        Dicabut
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <button class="text-[#3B82F6] hover:text-[#1D4ED8] text-sm font-medium">Lihat</button>
                                </td>
                            </tr>
                            <!-- Row 5 -->
                            <tr class="hover:bg-[#F8FAFC]">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm">MH</div>
                                        <div>
                                            <p class="text-[#1E293B] font-medium">Muhammad Hasan</p>
                                            <p class="text-[#94A3B8] text-sm">m.hasan@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-[#1E293B]">Cloud Computing Essentials</td>
                                <td class="py-4 px-4">
                                    <code class="text-[#3B82F6] bg-[#EFF6FF] px-2 py-1 rounded text-sm">CERT-2025-005</code>
                                </td>
                                <td class="py-4 px-4 text-[#64748B]">6 Des 2025</td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#ECFDF5] text-[#059669] text-sm rounded-full font-medium">
                                        <span class="w-1.5 h-1.5 bg-[#059669] rounded-full"></span>
                                        Aktif
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <button class="text-[#3B82F6] hover:text-[#1D4ED8] text-sm font-medium">Lihat</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($isStarterPlan)
    <!-- Midtrans Snap.js -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <script>
        function initiateUpgrade() {
            const btn = document.getElementById('btn-upgrade');
            const btnText = document.getElementById('btn-upgrade-text');
            const btnLoading = document.getElementById('btn-upgrade-loading');
            
            // Show loading state
            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
            
            // Request snap token from server
            fetch('{{ route("payment.quick-upgrade") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    package_slug: 'professional'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    resetButton();
                    return;
                }
                
                if (data.snap_token) {
                    // Open Midtrans Snap popup
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            // Payment success - redirect to dashboard
                            alert('Pembayaran berhasil! Kuota sertifikat Anda telah ditingkatkan.');
                            window.location.reload();
                        },
                        onPending: function(result) {
                            // Payment pending
                            alert('Pembayaran dalam proses. Silakan selesaikan pembayaran Anda.');
                            resetButton();
                        },
                        onError: function(result) {
                            // Payment failed
                            alert('Pembayaran gagal. Silakan coba lagi.');
                            resetButton();
                        },
                        onClose: function() {
                            // User closed popup without finishing
                            resetButton();
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                resetButton();
            });
        }
        
        function resetButton() {
            const btn = document.getElementById('btn-upgrade');
            const btnText = document.getElementById('btn-upgrade-text');
            const btnLoading = document.getElementById('btn-upgrade-loading');
            
            btn.disabled = false;
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
        }
    </script>
    @endif
</x-layouts.lembaga>
