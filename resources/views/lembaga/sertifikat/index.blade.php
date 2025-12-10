<x-layouts.lembaga>
    <div class="space-y-6">
        <!-- Header -->
        <div class="info-box rounded-2xl p-6">
            <h1 class="text-[#1C398E] text-2xl font-bold mb-2">Daftar Sertifikat Terbit</h1>
            <p class="text-[#193CB8] text-base">Kelola semua sertifikat yang telah diterbitkan oleh lembaga Anda</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-4 gap-4">
            <!-- Total Sertifikat -->
            <div class="glass-card rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#193CB8] text-sm font-bold">Total Sertifikat</p>
                        <p class="text-[#1C398E] text-lg font-bold mt-1">0</p>
                    </div>
                    <div class="stat-icon-blue w-12 h-12 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#193CB8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Aktif -->
            <div class="glass-card rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#016630] text-sm font-bold">Aktif</p>
                        <p class="text-[#008236] text-lg font-bold mt-1">0</p>
                    </div>
                    <div class="stat-icon-green w-12 h-12 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#016630]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Dicabut -->
            <div class="glass-card rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9F0712] text-sm font-bold">Dicabut</p>
                        <p class="text-[#C10007] text-lg font-bold mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(255, 100, 103, 0.4) 0%, rgba(231, 0, 11, 0.4) 100%); border: 1px solid rgba(255, 255, 255, 0.4);">
                        <svg class="w-6 h-6 text-[#9F0712]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Verifikasi -->
            <div class="glass-card rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#6E11B0] text-sm font-bold">Total Verifikasi</p>
                        <p class="text-[#8200DB] text-lg font-bold mt-1">0</p>
                    </div>
                    <div class="stat-icon-purple w-12 h-12 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#6E11B0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Update Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#00C950]/10 border border-[#7BF1A8]/30 rounded-xl">
            <div class="w-2 h-2 bg-[#00A63E] rounded-full animate-pulse"></div>
            <span class="text-[#0D542B] text-sm font-bold">Live Update - Data diperbarui otomatis</span>
        </div>

        <!-- Search & Action Card -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center gap-4">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-[#155DFC]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" placeholder="Cari berdasarkan nama, course, atau ID kredensial..." 
                           class="w-full pl-10 pr-4 py-3 bg-[#F8FAFC] border border-[#8EC5FF] rounded-lg text-sm text-[#155DFC]/60 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <a href="{{ route('lembaga.sertifikat.create') }}" class="flex items-center gap-2 px-5 py-3 btn-primary-gradient rounded-lg text-white text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    Terbitkan Sertifikat Baru
                </a>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <!-- Table Header -->
            <div class="header-gradient">
                <div class="grid grid-cols-7 gap-4 px-4 py-3">
                    <div class="text-[#1C398E] text-sm font-bold">Penerima</div>
                    <div class="text-[#1C398E] text-sm font-bold">Course/Program</div>
                    <div class="text-[#1C398E] text-sm font-bold">ID Kredensial</div>
                    <div class="text-[#1C398E] text-sm font-bold">Tanggal Terbit</div>
                    <div class="text-[#1C398E] text-sm font-bold">Status</div>
                    <div class="text-[#1C398E] text-sm font-bold">Verifikasi</div>
                    <div class="text-[#1C398E] text-sm font-bold text-right">Aksi</div>
                </div>
            </div>

            <!-- Empty State -->
            <div class="py-16 text-center">
                <h3 class="text-[#1C398E] text-lg font-bold mb-4">Belum ada sertifikat yang diterbitkan</h3>
                <a href="{{ route('lembaga.sertifikat.create') }}" class="inline-flex items-center justify-center px-6 py-3 btn-primary-gradient rounded-lg text-white text-sm font-bold">
                    Terbitkan Sertifikat Pertama
                </a>
            </div>
        </div>
    </div>
</x-layouts.lembaga>
