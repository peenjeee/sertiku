<x-layouts.lembaga>
    <div class="space-y-6">
        <!-- Header -->
        <div class="info-box rounded-2xl p-6">
            <h1 class="text-[#1E3A8A] text-2xl font-bold mb-2">Daftar Sertifikat Terbit</h1>
            <p class="text-[#3B82F6] text-base">Kelola semua sertifikat yang telah diterbitkan oleh lembaga Anda</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-4 gap-4">
            <!-- Total Sertifikat -->
            <div class="glass-card rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#1E40AF] text-sm font-bold">Total Sertifikat</p>
                        <p class="text-[#1E3A8A] text-2xl font-bold mt-1">127</p>
                    </div>
                    <div class="stat-icon-blue w-12 h-12 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Aktif -->
            <div class="glass-card rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#166534] text-sm font-bold">Aktif</p>
                        <p class="text-[#15803D] text-2xl font-bold mt-1">124</p>
                    </div>
                    <div class="stat-icon-green w-12 h-12 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#22C55E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Dicabut -->
            <div class="glass-card rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#DC2626] text-sm font-bold">Dicabut</p>
                        <p class="text-[#B91C1C] text-2xl font-bold mt-1">3</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-red-100 border border-red-200">
                        <svg class="w-6 h-6 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Verifikasi -->
            <div class="glass-card rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#7C3AED] text-sm font-bold">Total Verifikasi</p>
                        <p class="text-[#6D28D9] text-2xl font-bold mt-1">1,847</p>
                    </div>
                    <div class="stat-icon-purple w-12 h-12 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#A855F7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Update Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border-2 border-[#22C55E] rounded-xl shadow-sm">
            <div class="w-2.5 h-2.5 bg-[#22C55E] rounded-full animate-pulse"></div>
            <span class="text-[#15803D] text-sm font-bold">Live Update - Data diperbarui otomatis</span>
        </div>

        <!-- Search & Action Card -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center gap-4">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" placeholder="Cari berdasarkan nama, course, atau ID kredensial..."
                           class="w-full pl-10 pr-4 py-3 bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg text-sm text-[#1E293B] placeholder-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <a href="{{ route('lembaga.sertifikat.create') }}" class="flex items-center gap-2 px-5 py-3 btn-primary-gradient rounded-lg text-white text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Terbitkan Sertifikat Baru
                </a>
            </div>
        </div>

        <!-- Certificate Cards Grid -->
        <div class="grid grid-cols-3 gap-4">
            <!-- Card 1 -->
            <div class="glass-card rounded-2xl overflow-hidden hover:shadow-lg transition group">
                <div class="p-5">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">AS</div>
                            <div>
                                <h3 class="text-[#1E293B] font-bold">Andi Setiawan</h3>
                                <p class="text-[#94A3B8] text-sm">andi.setiawan@email.com</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#ECFDF5] text-[#059669] text-xs rounded-full font-medium">
                            <span class="w-1.5 h-1.5 bg-[#059669] rounded-full"></span>
                            Aktif
                        </span>
                    </div>

                    <!-- Program -->
                    <div class="mb-4">
                        <p class="text-[#64748B] text-xs mb-1">Program</p>
                        <p class="text-[#1E293B] font-medium">Web Development Bootcamp</p>
                    </div>

                    <!-- Details -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-[#64748B] text-xs mb-1">ID Kredensial</p>
                            <code class="text-[#3B82F6] bg-[#EFF6FF] px-2 py-1 rounded text-xs">CERT-2025-001</code>
                        </div>
                        <div>
                            <p class="text-[#64748B] text-xs mb-1">Tanggal Terbit</p>
                            <p class="text-[#1E293B] text-sm font-medium">10 Des 2025</p>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center gap-2 text-sm text-[#64748B] mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Diverifikasi 45 kali</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 pt-4 border-t border-[#E2E8F0]">
                        <button class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-[#EFF6FF] text-[#3B82F6] text-sm font-medium rounded-lg hover:bg-[#DBEAFE]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat
                        </button>
                        <button class="p-2 text-[#F97316] hover:bg-[#FFF7ED] rounded-lg" title="Download">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </button>
                        <button class="p-2 text-[#DC2626] hover:bg-[#FEF2F2] rounded-lg" title="Cabut">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="glass-card rounded-2xl overflow-hidden hover:shadow-lg transition group">
                <div class="p-5">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold">SP</div>
                            <div>
                                <h3 class="text-[#1E293B] font-bold">Siti Putri</h3>
                                <p class="text-[#94A3B8] text-sm">siti.putri@email.com</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#ECFDF5] text-[#059669] text-xs rounded-full font-medium">
                            <span class="w-1.5 h-1.5 bg-[#059669] rounded-full"></span>
                            Aktif
                        </span>
                    </div>

                    <!-- Program -->
                    <div class="mb-4">
                        <p class="text-[#64748B] text-xs mb-1">Program</p>
                        <p class="text-[#1E293B] font-medium">UI/UX Design Fundamentals</p>
                    </div>

                    <!-- Details -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-[#64748B] text-xs mb-1">ID Kredensial</p>
                            <code class="text-[#3B82F6] bg-[#EFF6FF] px-2 py-1 rounded text-xs">CERT-2025-002</code>
                        </div>
                        <div>
                            <p class="text-[#64748B] text-xs mb-1">Tanggal Terbit</p>
                            <p class="text-[#1E293B] text-sm font-medium">9 Des 2025</p>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center gap-2 text-sm text-[#64748B] mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Diverifikasi 32 kali</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 pt-4 border-t border-[#E2E8F0]">
                        <button class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-[#EFF6FF] text-[#3B82F6] text-sm font-medium rounded-lg hover:bg-[#DBEAFE]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat
                        </button>
                        <button class="p-2 text-[#F97316] hover:bg-[#FFF7ED] rounded-lg" title="Download">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </button>
                        <button class="p-2 text-[#DC2626] hover:bg-[#FEF2F2] rounded-lg" title="Cabut">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="glass-card rounded-2xl overflow-hidden hover:shadow-lg transition group">
                <div class="p-5">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-bold">BR</div>
                            <div>
                                <h3 class="text-[#1E293B] font-bold">Budi Raharjo</h3>
                                <p class="text-[#94A3B8] text-sm">budi.raharjo@email.com</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#ECFDF5] text-[#059669] text-xs rounded-full font-medium">
                            <span class="w-1.5 h-1.5 bg-[#059669] rounded-full"></span>
                            Aktif
                        </span>
                    </div>

                    <!-- Program -->
                    <div class="mb-4">
                        <p class="text-[#64748B] text-xs mb-1">Program</p>
                        <p class="text-[#1E293B] font-medium">Data Science Workshop</p>
                    </div>

                    <!-- Details -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-[#64748B] text-xs mb-1">ID Kredensial</p>
                            <code class="text-[#3B82F6] bg-[#EFF6FF] px-2 py-1 rounded text-xs">CERT-2025-003</code>
                        </div>
                        <div>
                            <p class="text-[#64748B] text-xs mb-1">Tanggal Terbit</p>
                            <p class="text-[#1E293B] text-sm font-medium">8 Des 2025</p>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center gap-2 text-sm text-[#64748B] mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Diverifikasi 28 kali</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 pt-4 border-t border-[#E2E8F0]">
                        <button class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-[#EFF6FF] text-[#3B82F6] text-sm font-medium rounded-lg hover:bg-[#DBEAFE]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat
                        </button>
                        <button class="p-2 text-[#F97316] hover:bg-[#FFF7ED] rounded-lg" title="Download">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </button>
                        <button class="p-2 text-[#DC2626] hover:bg-[#FEF2F2] rounded-lg" title="Cabut">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 4 - Dicabut -->
            <div class="glass-card rounded-2xl overflow-hidden hover:shadow-lg transition group border-2 border-red-200">
                <div class="p-5 bg-red-50/30">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center text-white font-bold">DW</div>
                            <div>
                                <h3 class="text-[#1E293B] font-bold">Dewi Wulandari</h3>
                                <p class="text-[#94A3B8] text-sm">dewi.wulandari@email.com</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#FEF2F2] text-[#DC2626] text-xs rounded-full font-medium">
                            <span class="w-1.5 h-1.5 bg-[#DC2626] rounded-full"></span>
                            Dicabut
                        </span>
                    </div>

                    <!-- Program -->
                    <div class="mb-4">
                        <p class="text-[#64748B] text-xs mb-1">Program</p>
                        <p class="text-[#94A3B8] font-medium line-through">Digital Marketing Certification</p>
                    </div>

                    <!-- Details -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-[#64748B] text-xs mb-1">ID Kredensial</p>
                            <code class="text-[#94A3B8] bg-[#F1F5F9] px-2 py-1 rounded text-xs line-through">CERT-2025-004</code>
                        </div>
                        <div>
                            <p class="text-[#64748B] text-xs mb-1">Tanggal Terbit</p>
                            <p class="text-[#94A3B8] text-sm font-medium">7 Des 2025</p>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center gap-2 text-sm text-[#94A3B8] mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Sertifikat dicabut</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 pt-4 border-t border-red-200">
                        <button class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-[#F1F5F9] text-[#64748B] text-sm font-medium rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="glass-card rounded-2xl overflow-hidden hover:shadow-lg transition group">
                <div class="p-5">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold">MH</div>
                            <div>
                                <h3 class="text-[#1E293B] font-bold">Muhammad Hasan</h3>
                                <p class="text-[#94A3B8] text-sm">m.hasan@email.com</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#ECFDF5] text-[#059669] text-xs rounded-full font-medium">
                            <span class="w-1.5 h-1.5 bg-[#059669] rounded-full"></span>
                            Aktif
                        </span>
                    </div>

                    <!-- Program -->
                    <div class="mb-4">
                        <p class="text-[#64748B] text-xs mb-1">Program</p>
                        <p class="text-[#1E293B] font-medium">Cloud Computing Essentials</p>
                    </div>

                    <!-- Details -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-[#64748B] text-xs mb-1">ID Kredensial</p>
                            <code class="text-[#3B82F6] bg-[#EFF6FF] px-2 py-1 rounded text-xs">CERT-2025-005</code>
                        </div>
                        <div>
                            <p class="text-[#64748B] text-xs mb-1">Tanggal Terbit</p>
                            <p class="text-[#1E293B] text-sm font-medium">6 Des 2025</p>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center gap-2 text-sm text-[#64748B] mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Diverifikasi 19 kali</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 pt-4 border-t border-[#E2E8F0]">
                        <button class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-[#EFF6FF] text-[#3B82F6] text-sm font-medium rounded-lg hover:bg-[#DBEAFE]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat
                        </button>
                        <button class="p-2 text-[#F97316] hover:bg-[#FFF7ED] rounded-lg" title="Download">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </button>
                        <button class="p-2 text-[#DC2626] hover:bg-[#FEF2F2] rounded-lg" title="Cabut">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="glass-card rounded-2xl overflow-hidden hover:shadow-lg transition group">
                <div class="p-5">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white font-bold">RA</div>
                            <div>
                                <h3 class="text-[#1E293B] font-bold">Rina Anggraini</h3>
                                <p class="text-[#94A3B8] text-sm">rina.anggraini@email.com</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#ECFDF5] text-[#059669] text-xs rounded-full font-medium">
                            <span class="w-1.5 h-1.5 bg-[#059669] rounded-full"></span>
                            Aktif
                        </span>
                    </div>

                    <!-- Program -->
                    <div class="mb-4">
                        <p class="text-[#64748B] text-xs mb-1">Program</p>
                        <p class="text-[#1E293B] font-medium">Project Management Professional</p>
                    </div>

                    <!-- Details -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-[#64748B] text-xs mb-1">ID Kredensial</p>
                            <code class="text-[#3B82F6] bg-[#EFF6FF] px-2 py-1 rounded text-xs">CERT-2025-006</code>
                        </div>
                        <div>
                            <p class="text-[#64748B] text-xs mb-1">Tanggal Terbit</p>
                            <p class="text-[#1E293B] text-sm font-medium">5 Des 2025</p>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center gap-2 text-sm text-[#64748B] mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Diverifikasi 15 kali</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 pt-4 border-t border-[#E2E8F0]">
                        <button class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-[#EFF6FF] text-[#3B82F6] text-sm font-medium rounded-lg hover:bg-[#DBEAFE]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat
                        </button>
                        <button class="p-2 text-[#F97316] hover:bg-[#FFF7ED] rounded-lg" title="Download">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </button>
                        <button class="p-2 text-[#DC2626] hover:bg-[#FEF2F2] rounded-lg" title="Cabut">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="glass-card rounded-2xl p-4 flex items-center justify-between">
            <p class="text-[#64748B] text-sm">Menampilkan 1-6 dari 127 sertifikat</p>
            <div class="flex items-center gap-2">
                <button class="px-4 py-2 text-[#64748B] text-sm hover:bg-[#F1F5F9] rounded-lg border border-[#E2E8F0]">Sebelumnya</button>
                <button class="px-4 py-2 bg-[#1E3A8F] text-white text-sm rounded-lg font-medium">1</button>
                <button class="px-4 py-2 text-[#64748B] text-sm hover:bg-[#F1F5F9] rounded-lg">2</button>
                <button class="px-4 py-2 text-[#64748B] text-sm hover:bg-[#F1F5F9] rounded-lg">3</button>
                <span class="text-[#64748B]">...</span>
                <button class="px-4 py-2 text-[#64748B] text-sm hover:bg-[#F1F5F9] rounded-lg">22</button>
                <button class="px-4 py-2 text-[#64748B] text-sm hover:bg-[#F1F5F9] rounded-lg border border-[#E2E8F0]">Selanjutnya</button>
            </div>
        </div>
    </div>
</x-layouts.lembaga>
