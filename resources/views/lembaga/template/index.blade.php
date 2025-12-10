<x-layouts.lembaga>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-[#1E293B] text-2xl font-bold">Galeri Template Sertifikat</h1>
                <p class="text-[#64748B] text-base mt-1">Kelola template sertifikat yang telah diupload (3 template)</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="w-10 h-10 bg-[#1E3A8F] rounded-lg flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
                <button class="w-10 h-10 bg-white border border-[#E2E8F0] rounded-lg flex items-center justify-center text-[#64748B] hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </button>
                <a href="{{ route('lembaga.template.upload') }}" class="flex items-center gap-2 px-4 py-2.5 btn-primary-gradient rounded-lg text-white text-sm font-bold ml-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Upload Template
                </a>
            </div>
        </div>

        <!-- Info Box -->
        <div class="info-box rounded-2xl p-5">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-[#3B82F6]/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-[#1E3A8A] font-bold mb-1">Tentang Galeri Template</h3>
                    <p class="text-[#3B82F6] text-sm">
                        Galeri ini menampilkan template "License Sertifikat". Template ini adalah desain kosong yang siap digunakan untuk menerbitkan sertifikat kepada penerima. Untuk melihat sertifikat yang sudah diterbitkan kepada peserta, kunjungi menu
                        <a href="{{ route('lembaga.sertifikat.index') }}" class="font-bold hover:underline">"Daftar Sertifikat"</a>.
                    </p>
                </div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="glass-card rounded-2xl p-4">
            <div class="relative">
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-[#94A3B8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Cari template..."
                       class="w-full pl-12 pr-4 py-3 bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg text-sm text-[#1E293B] placeholder-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- Template Grid -->
        <div class="grid grid-cols-3 gap-6">
            <!-- Template 1 -->
            <div class="glass-card rounded-2xl overflow-hidden group hover:shadow-lg transition">
                <div class="relative aspect-[4/3] bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center">
                    <!-- Certificate Preview -->
                    <div class="w-4/5 h-4/5 bg-white rounded-lg shadow-md border border-gray-200 p-4 flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <div class="w-12 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded"></div>
                            <div class="w-6 h-6 bg-gray-200 rounded-full"></div>
                        </div>
                        <div class="text-center flex-1 flex flex-col justify-center">
                            <div class="w-3/4 h-2 bg-gray-300 rounded mx-auto mb-2"></div>
                            <div class="w-1/2 h-3 bg-blue-500 rounded mx-auto mb-2"></div>
                            <div class="w-2/3 h-2 bg-gray-200 rounded mx-auto"></div>
                        </div>
                        <div class="flex justify-between items-end">
                            <div class="w-12 h-8 border border-gray-300 rounded"></div>
                            <div class="w-8 h-8 bg-gray-100 rounded"></div>
                        </div>
                    </div>
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-3">
                        <button class="p-3 bg-white rounded-full text-[#1E293B] hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        <button class="p-3 bg-[#3B82F6] rounded-full text-white hover:bg-[#2563EB]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button class="p-3 bg-red-500 rounded-full text-white hover:bg-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Badge -->
                    <div class="absolute top-3 left-3">
                        <span class="px-2 py-1 bg-[#3B82F6] text-white text-xs rounded-full font-medium">Default</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-[#1E293B] font-bold mb-1">Template Profesional</h3>
                    <p class="text-[#64748B] text-sm mb-3">Desain elegan untuk sertifikasi profesional</p>
                    <div class="flex items-center justify-between text-xs text-[#94A3B8]">
                        <span>Digunakan: 85 kali</span>
                        <span>25 Nov 2024</span>
                    </div>
                </div>
            </div>

            <!-- Template 2 -->
            <div class="glass-card rounded-2xl overflow-hidden group hover:shadow-lg transition">
                <div class="relative aspect-[4/3] bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center">
                    <!-- Certificate Preview -->
                    <div class="w-4/5 h-4/5 bg-white rounded-lg shadow-md border border-gray-200 p-4 flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <div class="w-12 h-8 bg-gradient-to-r from-emerald-500 to-green-600 rounded"></div>
                            <div class="w-6 h-6 bg-gray-200 rounded-full"></div>
                        </div>
                        <div class="text-center flex-1 flex flex-col justify-center">
                            <div class="w-3/4 h-2 bg-gray-300 rounded mx-auto mb-2"></div>
                            <div class="w-1/2 h-3 bg-emerald-500 rounded mx-auto mb-2"></div>
                            <div class="w-2/3 h-2 bg-gray-200 rounded mx-auto"></div>
                        </div>
                        <div class="flex justify-between items-end">
                            <div class="w-12 h-8 border border-gray-300 rounded"></div>
                            <div class="w-8 h-8 bg-gray-100 rounded"></div>
                        </div>
                    </div>
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-3">
                        <button class="p-3 bg-white rounded-full text-[#1E293B] hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        <button class="p-3 bg-[#3B82F6] rounded-full text-white hover:bg-[#2563EB]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button class="p-3 bg-red-500 rounded-full text-white hover:bg-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-[#1E293B] font-bold mb-1">Template Workshop</h3>
                    <p class="text-[#64748B] text-sm mb-3">Cocok untuk sertifikat workshop & pelatihan</p>
                    <div class="flex items-center justify-between text-xs text-[#94A3B8]">
                        <span>Digunakan: 32 kali</span>
                        <span>20 Nov 2024</span>
                    </div>
                </div>
            </div>

            <!-- Template 3 -->
            <div class="glass-card rounded-2xl overflow-hidden group hover:shadow-lg transition">
                <div class="relative aspect-[4/3] bg-gradient-to-br from-purple-50 to-pink-100 flex items-center justify-center">
                    <!-- Certificate Preview -->
                    <div class="w-4/5 h-4/5 bg-white rounded-lg shadow-md border border-gray-200 p-4 flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <div class="w-12 h-8 bg-gradient-to-r from-purple-500 to-pink-600 rounded"></div>
                            <div class="w-6 h-6 bg-gray-200 rounded-full"></div>
                        </div>
                        <div class="text-center flex-1 flex flex-col justify-center">
                            <div class="w-3/4 h-2 bg-gray-300 rounded mx-auto mb-2"></div>
                            <div class="w-1/2 h-3 bg-purple-500 rounded mx-auto mb-2"></div>
                            <div class="w-2/3 h-2 bg-gray-200 rounded mx-auto"></div>
                        </div>
                        <div class="flex justify-between items-end">
                            <div class="w-12 h-8 border border-gray-300 rounded"></div>
                            <div class="w-8 h-8 bg-gray-100 rounded"></div>
                        </div>
                    </div>
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-3">
                        <button class="p-3 bg-white rounded-full text-[#1E293B] hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        <button class="p-3 bg-[#3B82F6] rounded-full text-white hover:bg-[#2563EB]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button class="p-3 bg-red-500 rounded-full text-white hover:bg-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-[#1E293B] font-bold mb-1">Template Penghargaan</h3>
                    <p class="text-[#64748B] text-sm mb-3">Desain mewah untuk penghargaan khusus</p>
                    <div class="flex items-center justify-between text-xs text-[#94A3B8]">
                        <span>Digunakan: 10 kali</span>
                        <span>15 Nov 2024</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.lembaga>
