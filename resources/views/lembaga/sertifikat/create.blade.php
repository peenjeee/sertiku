<x-layouts.lembaga>
    @php
        $user = Auth::user();
        $isStarterPlan = $user->isStarterPlan();
        $certificateLimit = $user->getCertificateLimit();
        $certificatesUsed = $user->getCertificatesUsedThisMonth();
        $remainingCerts = $user->getRemainingCertificates();
        $canIssue = $user->canIssueCertificate();
        $usagePercentage = $user->getUsagePercentage();
    @endphp

    <div class="space-y-6">
        <!-- Header -->
        <div class="info-box rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-[#1C398E] text-2xl font-bold mb-2">Terbitkan Sertifikat Baru</h1>
                    <p class="text-[#193CB8] text-base">Terbitkan sertifikat digital untuk peserta program Anda</p>
                </div>
                <!-- Usage Badge -->
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[#64748B] text-xs">Sisa Kuota Bulan Ini</p>
                        <p class="text-[#1E3A8A] font-bold">{{ $remainingCerts }} / {{ $certificateLimit }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $remainingCerts <= 10 ? 'bg-red-100' : 'bg-blue-100' }}">
                        <span class="text-lg font-bold {{ $remainingCerts <= 10 ? 'text-red-600' : 'text-blue-600' }}">{{ $remainingCerts }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Limit Warning for Starter Plan -->
        @if($isStarterPlan)
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-300 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[#92400E] font-medium">
                            @if($remainingCerts <= 0)
                                <span class="text-red-700 font-bold">❌ Kuota sertifikat bulan ini sudah habis!</span>
                            @elseif($remainingCerts <= 10)
                                <span class="text-red-600">⚠️ Sisa {{ $remainingCerts }} sertifikat! Kuota hampir habis.</span>
                            @else
                                Paket Starter: {{ $certificatesUsed }}/{{ $certificateLimit }} sertifikat terpakai bulan ini
                            @endif
                        </p>
                        <p class="text-[#B45309] text-sm">Upgrade ke Professional untuk 6.700 sertifikat/bulan</p>
                    </div>
                </div>
                <a href="{{ url('/#harga') }}" class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-bold rounded-lg hover:from-amber-600 hover:to-orange-600 transition">
                    Upgrade
                </a>
            </div>
            <!-- Progress Bar -->
            <div class="mt-3">
                <div class="w-full h-2 bg-amber-200 rounded-full overflow-hidden">
                    <div class="h-full {{ $usagePercentage >= 90 ? 'bg-red-500' : 'bg-gradient-to-r from-amber-400 to-orange-500' }} rounded-full transition-all" style="width: {{ $usagePercentage }}%"></div>
                </div>
            </div>
        </div>
        @endif

        <!-- Limit Reached Block -->
        @if(!$canIssue)
        <div class="bg-red-50 border-2 border-red-300 rounded-2xl p-8 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h2 class="text-red-800 text-xl font-bold mb-2">Kuota Sertifikat Habis</h2>
            <p class="text-red-600 mb-6">Anda telah mencapai batas {{ $certificateLimit }} sertifikat untuk bulan ini.<br>Upgrade paket Anda untuk melanjutkan menerbitkan sertifikat.</p>
            <a href="{{ url('/#harga') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                Upgrade ke Professional
            </a>
        </div>
        @else

        <!-- Tab List -->
        <div class="bg-[#F1F5F9] border border-[#8EC5FF]/30 rounded-lg p-1.5 flex items-center justify-center gap-2 overflow-x-auto">
            <button class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#155DFC] to-[#4F39F6] text-white text-sm font-bold rounded-md shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                Form Penerbitan
            </button>
            <button class="flex items-center gap-2 px-6 py-3 text-[#62748E] text-sm font-bold rounded-md hover:bg-white/50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Template Saya (0)
            </button>
        </div>

        <!-- Mode Penerbitan Card -->
        <div class="glass-card rounded-xl lg:rounded-2xl p-4 lg:p-6">
            <div class="space-y-4">
                <label class="text-[#1C398E] text-sm font-bold">Mode Penerbitan</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 lg:gap-4">
                    <button class="flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-[#155DFC] to-[#4F39F6] text-white font-bold rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2zM12 11v4"/>
                        </svg>
                        Single Upload
                    </button>
                    <button class="flex items-center justify-center gap-2 px-6 py-3 bg-white border border-[#8EC5FF] text-[#1C398E] font-bold rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                        </svg>
                        Batch Upload
                    </button>
                </div>
            </div>
        </div>

        <!-- Form Card - Informasi Program -->
        <div class="glass-card rounded-xl lg:rounded-2xl p-4 lg:p-6 space-y-6 lg:space-y-10">
            <!-- Section Header -->
            <div class="flex items-center gap-2 pb-3 border-b border-[#8EC5FF]">
                <svg class="w-5 h-5 text-[#1C398E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span class="text-[#1C398E] text-base font-bold">Informasi Program</span>
            </div>

            <!-- Form Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                <!-- Nama Kursus -->
                <div class="space-y-2">
                    <label class="text-[#1C398E] text-sm font-bold">
                        Nama Kursus/Program<span class="text-[#E7000B]">*</span>
                    </label>
                    <input type="text" placeholder="Contoh: Web Development Bootcamp 2024"
                           class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#8EC5FF] rounded-lg text-sm text-[#64748B] focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Kategori Event -->
                <div class="space-y-2">
                    <label class="text-[#1C398E] text-sm font-bold">
                        Kategori Event<span class="text-[#E7000B]">*</span>
                    </label>
                    <select class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#8EC5FF] rounded-lg text-sm text-[#64748B] focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Pilih kategori event</option>
                        <option>Bootcamp</option>
                        <option>Workshop</option>
                        <option>Seminar</option>
                        <option>Sertifikasi</option>
                        <option>Pelatihan</option>
                    </select>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="space-y-2">
                <label class="text-[#1C398E] text-sm font-bold">Deskripsi</label>
                <textarea placeholder="Deskripsi singkat tentang program/kursus (opsional)" rows="2"
                          class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#8EC5FF] rounded-lg text-sm text-[#64748B] focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <!-- Tanggal -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                <div class="space-y-2">
                    <label class="text-[#1C398E] text-sm font-bold">Tanggal Penerbitan</label>
                    <input type="date" class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#8EC5FF] rounded-lg text-sm text-[#64748B] focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="space-y-2">
                    <label class="text-[#1C398E] text-sm font-bold">Tanggal Kadaluarsa (Opsional)</label>
                    <input type="date" class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#8EC5FF] rounded-lg text-sm text-[#64748B] focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Form Card - Informasi Penerima -->
        <div class="glass-card rounded-2xl p-6 space-y-10">
            <!-- Section Header -->
            <div class="flex items-center gap-2 pb-3 border-b border-[#8EC5FF]">
                <svg class="w-5 h-5 text-[#1C398E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span class="text-[#1C398E] text-base font-bold">Informasi Penerima</span>
            </div>

            <!-- Form Fields -->
            <div class="grid grid-cols-2 gap-6">
                <!-- Nama Penerima -->
                <div class="space-y-2">
                    <label class="text-[#1C398E] text-sm font-bold">
                        Nama Penerima<span class="text-[#E7000B]">*</span>
                    </label>
                    <input type="text" placeholder="Contoh: John Doe"
                           class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#8EC5FF] rounded-lg text-sm text-[#64748B] focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Email Penerima -->
                <div class="space-y-2">
                    <label class="text-[#1C398E] text-sm font-bold">Email Penerima</label>
                    <input type="email" placeholder="Contoh: john@example.com"
                           class="w-full px-4 py-3 bg-[#F8FAFC] border border-[#8EC5FF] rounded-lg text-sm text-[#64748B] focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- ID Sertifikat -->
            <div class="pt-4 border-t border-[#BEDBFF]">
                <div class="flex items-center justify-between">
                    <label class="text-[#1C398E] text-sm font-bold">ID Sertifikat</label>
                    <button class="px-4 py-2.5 bg-white border border-[#8EC5FF] rounded-lg text-[#1C398E] text-sm font-bold hover:bg-gray-50 transition">
                        Auto Generate
                    </button>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-[#2B7FFF]/20 border-l-4 border-[#155DFC] border-y border-r border-[#155DFC] rounded-r-md p-5">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-[#1447E6] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-[#1C398E] text-sm font-bold mb-1">Informasi Penting:</p>
                    <ul class="text-[#1C398E] text-sm space-y-1">
                        <li>• ID sertifikat akan dibuat secara otomatis</li>
                        <li>• Sertifikat akan langsung aktif setelah diterbitkan</li>
                        <li>• Penerima dapat memverifikasi sertifikat menggunakan QR code</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-4 pt-4">
            <button class="flex-1 flex items-center justify-center gap-2 py-3.5 btn-primary-gradient rounded-lg text-white text-lg font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                </svg>
                Terbitkan Sertifikat
            </button>
            <button class="px-8 py-3.5 bg-white border-2 border-[#8EC5FF] rounded-lg text-[#1C398E] text-sm font-bold hover:bg-gray-50 transition">
                Batal
            </button>
        </div>
        @endif
    </div>
</x-layouts.lembaga>
