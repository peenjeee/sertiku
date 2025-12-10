<x-layouts.lembaga>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-[#1E293B] text-2xl font-bold">Galeri Template Sertifikat</h1>
                <p class="text-[#64748B] text-base mt-1">Kelola template sertifikat yang telah diupload</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="w-10 h-10 bg-white border border-[#E2E8F0] rounded-lg flex items-center justify-center text-[#64748B] hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
                <button class="w-10 h-10 bg-white border border-[#E2E8F0] rounded-lg flex items-center justify-center text-[#64748B] hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Info Box -->
        <div class="info-box rounded-2xl p-5">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-[#155DFC]/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#155DFC]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-[#1C398E] font-bold mb-1">Tentang Galeri Template</h3>
                    <p class="text-[#193CB8] text-sm">
                        Galeri ini menampilkan template "License Sertifikat". Template ini adalah desain kosong yang siap digunakan untuk menerbitkan sertifikat kepada penerima. Untuk melihat sertifikat yang sudah diterbitkan kepada peserta, kunjungi menu
                        <a href="{{ route('lembaga.sertifikat.index') }}" class="font-bold hover:underline">"Daftar Sertifikat"</a>.
                    </p>
                </div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="glass-card rounded-2xl p-4">
            <div class="relative">
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-[#BEDBFF]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Cari template..."
                       class="w-full pl-12 pr-4 py-3 bg-transparent border-0 text-white text-sm focus:outline-none placeholder-[#BEDBFF]/50">
            </div>
        </div>

        <!-- Empty State Card -->
        <div class="glass-card rounded-2xl p-12">
            <div class="text-center">
                <!-- Empty Icon -->
                <div class="w-20 h-20 bg-[#1E293B]/50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>

                <h2 class="text-[#1E293B] text-xl font-bold mb-2">Belum Ada Template</h2>
                <p class="text-[#64748B] text-base mb-8 max-w-md mx-auto">
                    Anda belum memiliki template sertifikat. Upload template pertama Anda untuk mulai menerbitkan sertifikat.
                </p>

                <a href="{{ route('lembaga.template.upload') }}" class="inline-flex items-center justify-center gap-2 w-full max-w-lg px-6 py-4 btn-primary-gradient rounded-lg text-white font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Upload Template Pertama
                </a>
            </div>
        </div>
    </div>
</x-layouts.lembaga>
