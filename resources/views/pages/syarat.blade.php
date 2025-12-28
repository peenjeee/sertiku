<x-layouts.app title="Syarat & Ketentuan – SertiKu">
    <main class="py-16">
        <div class="max-w-4xl mx-auto px-4">
            {{-- Header --}}
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-[#1E3A8F] to-[#3B82F6] mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">Syarat & Ketentuan</h1>
                <p class="text-[#BEDBFF]">Terakhir diperbarui: 29 Desember 2025</p>
            </div>

            {{-- Content Card --}}
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl border border-white/20 p-8 md:p-12 text-[#DBEAFE] space-y-8">
                
                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">1. Penerimaan Syarat</h2>
                    <p class="leading-relaxed">Dengan menggunakan layanan SertiKu, Anda menyetujui untuk terikat dengan syarat dan ketentuan ini. Jika Anda tidak setuju, mohon untuk tidak menggunakan layanan kami.</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">2. Deskripsi Layanan</h2>
                    <p class="leading-relaxed">SertiKu menyediakan platform untuk:</p>
                    <ul class="list-disc list-inside mt-3 space-y-2 text-[#BEDBFF]">
                        <li>Penerbitan sertifikat digital berbasis blockchain</li>
                        <li>Verifikasi keaslian sertifikat melalui QR code</li>
                        <li>Manajemen dan penyimpanan sertifikat digital</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">3. Akun Pengguna</h2>
                    <p class="leading-relaxed">Anda bertanggung jawab untuk:</p>
                    <ul class="list-disc list-inside mt-3 space-y-2 text-[#BEDBFF]">
                        <li>Menjaga kerahasiaan kredensial akun</li>
                        <li>Semua aktivitas yang terjadi di akun Anda</li>
                        <li>Memberikan informasi yang akurat dan terkini</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">4. Penggunaan yang Dilarang</h2>
                    <p class="leading-relaxed">Anda tidak diperbolehkan untuk:</p>
                    <ul class="list-disc list-inside mt-3 space-y-2 text-[#BEDBFF]">
                        <li>Membuat sertifikat palsu atau menyesatkan</li>
                        <li>Menggunakan layanan untuk aktivitas ilegal</li>
                        <li>Mencoba meretas atau mengganggu sistem</li>
                        <li>Menyalahgunakan data pengguna lain</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">5. Hak Kekayaan Intelektual</h2>
                    <p class="leading-relaxed">Semua konten, logo, dan materi pada platform SertiKu adalah milik kami dan dilindungi oleh hukum hak cipta. Anda tidak boleh menyalin atau mendistribusikan tanpa izin tertulis.</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">6. Batasan Tanggung Jawab</h2>
                    <p class="leading-relaxed">SertiKu tidak bertanggung jawab atas kerugian yang timbul dari penggunaan layanan, termasuk tetapi tidak terbatas pada kehilangan data atau gangguan bisnis.</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">7. Perubahan Syarat</h2>
                    <p class="leading-relaxed">Kami berhak mengubah syarat dan ketentuan ini kapan saja. Perubahan akan berlaku segera setelah dipublikasikan di platform.</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">8. Kontak</h2>
                    <p class="leading-relaxed">Untuk pertanyaan tentang syarat dan ketentuan, hubungi <a href="mailto:legal@sertiku.com" class="text-[#3B82F6] hover:underline">legal@sertiku.com</a>.</p>
                </section>
            </div>

            {{-- Back Button --}}
            <div class="mt-8 text-center">
                <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-[#BEDBFF] hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </main>
</x-layouts.app>
