<x-layouts.lembaga>
    <div class="space-y-4 lg:space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-white text-lg sm:text-xl lg:text-2xl font-normal">Upload Sertifikat</h1>
            <p class="text-[#BEDBFF]/70 text-sm lg:text-base mt-1">Upload dan proses sertifikat secara massal atau individual</p>
        </div>

        <!-- Upload Mode Tabs -->
        <div class="flex items-center gap-2">
            <button class="px-5 py-2.5 bg-[#F59E0B] text-white text-sm font-bold rounded-lg">
                Upload Tunggal
            </button>
            <button class="px-5 py-2.5 bg-white/10 text-white/70 text-sm font-bold rounded-lg hover:bg-white/20 transition">
                Upload Batch
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 lg:gap-4">
            <!-- Total File -->
            <div class="bg-[#3B82F6] rounded-xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white text-xl font-bold">0</p>
                    <p class="text-white/70 text-xs">Total File</p>
                </div>
            </div>

            <!-- Berhasil -->
            <div class="bg-[#10B981] rounded-xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white text-xl font-bold">0</p>
                    <p class="text-white/70 text-xs">Berhasil</p>
                </div>
            </div>

            <!-- Proses -->
            <div class="bg-[#F59E0B] rounded-xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white text-xl font-bold">0</p>
                    <p class="text-white/70 text-xs">Proses</p>
                </div>
            </div>

            <!-- Gagal -->
            <div class="bg-[#EF4444] rounded-xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white text-xl font-bold">0</p>
                    <p class="text-white/70 text-xs">Gagal</p>
                </div>
            </div>

            <!-- Peserta -->
            <div class="bg-[#A855F7] rounded-xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white text-xl font-bold">0</p>
                    <p class="text-white/70 text-xs">Peserta</p>
                </div>
            </div>
        </div>

        <!-- Upload Area Card -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <!-- Card Header -->
            <div class="header-gradient p-5 flex items-center justify-between">
                <div>
                    <h2 class="text-[#1E293B] text-lg font-bold">Area Upload</h2>
                    <p class="text-[#64748B] text-sm">Upload satu file sertifikat</p>
                </div>
                <button class="w-10 h-10 bg-white/50 rounded-lg flex items-center justify-center text-[#155DFC] hover:bg-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </button>
            </div>

            <!-- Upload Zone -->
            <div class="p-4 lg:p-8">
                <div class="border-2 border-dashed border-[#64748B]/30 rounded-xl lg:rounded-2xl p-6 lg:p-12 text-center bg-[#1E293B]/30 hover:bg-[#1E293B]/50 transition cursor-pointer"
                     id="dropzone"
                     ondragover="event.preventDefault(); this.classList.add('border-[#155DFC]')"
                     ondragleave="this.classList.remove('border-[#155DFC]')"
                     ondrop="event.preventDefault(); this.classList.remove('border-[#155DFC]')">
                    <!-- Upload Icon -->
                    <div class="w-20 h-20 bg-[#155DFC]/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-[#155DFC]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </div>

                    <h3 class="text-white text-lg font-bold mb-2">Drag & Drop file Anda</h3>
                    <p class="text-[#64748B] text-sm mb-6">atau</p>

                    <label class="inline-flex items-center gap-2 px-6 py-3 bg-[#155DFC] text-white text-sm font-bold rounded-lg cursor-pointer hover:bg-[#1447E6] transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Pilih File
                        <input type="file" class="hidden" accept=".pdf,.jpg,.jpeg,.png,.xlsx,.csv">
                    </label>

                    <!-- Supported formats -->
                    <div class="flex items-center justify-center gap-2 mt-6">
                        <span class="px-3 py-1 bg-white/10 text-white/70 text-xs rounded">PDF</span>
                        <span class="px-3 py-1 bg-white/10 text-white/70 text-xs rounded">JPG/PNG</span>
                        <span class="px-3 py-1 bg-white/10 text-white/70 text-xs rounded">Excel</span>
                        <span class="px-3 py-1 bg-white/10 text-white/70 text-xs rounded">CSV</span>
                    </div>

                    <p class="text-[#64748B] text-xs mt-4">Maksimal ukuran file: 10MB per file</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.lembaga>
