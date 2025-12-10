<x-layouts.lembaga>
    <div class="space-y-8">
        <!-- Welcome Banner -->
        <div class="welcome-banner relative rounded-3xl p-8 overflow-hidden">
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl text-white font-bold mb-2">Selamat Datang, Lembaga! 👋</h1>
                    <p class="text-[#DBEAFE] text-lg">Kelola dan terbitkan sertifikat digital dengan mudah</p>
                </div>
                <div class="opacity-30">
                    <svg class="w-24 h-24" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Quick Action Cards -->
        <div class="grid grid-cols-3 gap-4">
            <!-- Terbitkan Sertifikat -->
            <a href="{{ route('lembaga.sertifikat.create') }}" class="gradient-card-blue rounded-2xl p-6 hover:scale-[1.02] transition cursor-pointer">
                <div class="flex items-center gap-4">
                    <div class="icon-circle-blue w-14 h-14 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[#1E3A8A] font-bold text-lg">Terbitkan Sertifikat</h3>
                        <p class="text-[#3B82F6] text-sm">Buat sertifikat baru</p>
                    </div>
                </div>
            </a>

            <!-- Galeri Template -->
            <a href="{{ route('lembaga.template.index') }}" class="gradient-card-green rounded-2xl p-6 hover:scale-[1.02] transition cursor-pointer">
                <div class="flex items-center gap-4">
                    <div class="icon-circle-green w-14 h-14 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[#166534] font-bold text-lg">Galeri Template</h3>
                        <p class="text-[#22C55E] text-sm">Lihat template tersimpan</p>
                    </div>
                </div>
            </a>

            <!-- Daftar Sertifikat -->
            <a href="{{ route('lembaga.sertifikat.index') }}" class="gradient-card-purple rounded-2xl p-6 hover:scale-[1.02] transition cursor-pointer">
                <div class="flex items-center gap-4">
                    <div class="icon-circle-purple w-14 h-14 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[#7C3AED] font-bold text-lg">Daftar Sertifikat</h3>
                        <p class="text-[#A855F7] text-sm">Kelola sertifikat</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-4 gap-6">
            <!-- Total Sertifikat -->
            <div class="stat-card-blue rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#1E40AF] text-sm font-medium">Total Sertifikat</p>
                        <p class="text-[#1E3A8A] text-3xl font-bold mt-1">0</p>
                    </div>
                    <div class="stat-icon-blue w-12 h-12 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Sertifikat Aktif -->
            <div class="stat-card-green rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#166534] text-sm font-medium">Sertifikat Aktif</p>
                        <p class="text-[#15803D] text-3xl font-bold mt-1">0</p>
                    </div>
                    <div class="stat-icon-green w-12 h-12 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#22C55E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Bulan Ini -->
            <div class="stat-card-orange rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9A3412] text-sm font-medium">Bulan Ini</p>
                        <p class="text-[#C2410C] text-3xl font-bold mt-1">0</p>
                    </div>
                    <div class="stat-icon-orange w-12 h-12 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#F97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Verifikasi -->
            <div class="stat-card-purple rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#6B21A8] text-sm font-medium">Total Verifikasi</p>
                        <p class="text-[#7C3AED] text-3xl font-bold mt-1">0</p>
                    </div>
                    <div class="stat-icon-purple w-12 h-12 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#A855F7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Certificates Card -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <!-- Header -->
            <div class="header-gradient p-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    <div>
                        <h2 class="text-[#1E293B] text-xl font-bold">Sertifikat Terbaru</h2>
                        <p class="text-[#64748B] text-sm">5 sertifikat yang baru diterbitkan</p>
                    </div>
                </div>
                <a href="{{ route('lembaga.sertifikat.index') }}" class="flex items-center gap-2 px-4 py-2.5 bg-white border border-[#E2E8F0] rounded-lg text-[#1E293B] text-sm hover:bg-gray-50 transition shadow-sm">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Search & Filter -->
                <div class="flex items-center justify-between mb-6">
                    <div class="relative flex-1 max-w-3xl">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" placeholder="Cari nama, kursus, atau credential ID..." class="w-full pl-10 pr-4 py-3 bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg text-sm text-[#1E293B] placeholder-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="flex items-center gap-2 ml-4">
                        <button class="px-4 py-3 bg-[#1E3A8F] text-white text-sm rounded-lg font-medium">Semua</button>
                        <button class="px-4 py-3 bg-white border border-[#E2E8F0] text-[#1E293B] text-sm rounded-lg hover:bg-gray-50">Aktif</button>
                        <button class="px-4 py-3 bg-white border border-[#E2E8F0] text-[#1E293B] text-sm rounded-lg hover:bg-gray-50">Dicabut</button>
                    </div>
                </div>

                <!-- Empty State -->
                <div class="py-16 text-center">
                    <div class="w-16 h-16 bg-[#F1F5F9] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-[#94A3B8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-[#1E293B] text-lg font-bold mb-2">Belum ada sertifikat yang diterbitkan</h3>
                    <p class="text-[#64748B] text-base mb-6">Mulai terbitkan sertifikat digital pertama Anda!</p>
                    <a href="{{ route('lembaga.sertifikat.create') }}" class="inline-flex items-center gap-2 px-5 py-3 btn-primary-gradient rounded-lg text-white text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Terbitkan Sertifikat
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.lembaga>
