<x-layouts.app title="Kebijakan Privasi – SertiKu">
    <main class="py-16">
        <div class="max-w-4xl mx-auto px-4">
            {{-- Header --}}
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-[#1E3A8F] to-[#3B82F6] mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">Kebijakan Privasi</h1>
                <p class="text-[#BEDBFF]">Terakhir diperbarui: 9 Desember 2025</p>
            </div>

            {{-- Content Card --}}
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl border border-white/20 p-8 md:p-12 text-[#DBEAFE] space-y-8">
                
                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">1. Informasi yang Kami Kumpulkan</h2>
                    <p class="leading-relaxed">Kami mengumpulkan informasi yang Anda berikan secara langsung kepada kami, termasuk:</p>
                    <ul class="list-disc list-inside mt-3 space-y-2 text-[#BEDBFF]">
                        <li>Nama lengkap dan alamat email</li>
                        <li>Informasi akun Google (jika login via Google)</li>
                        <li>Alamat wallet blockchain (jika login via wallet)</li>
                        <li>Data sertifikat yang Anda upload atau verifikasi</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">2. Penggunaan Informasi</h2>
                    <p class="leading-relaxed">Informasi yang kami kumpulkan digunakan untuk:</p>
                    <ul class="list-disc list-inside mt-3 space-y-2 text-[#BEDBFF]">
                        <li>Menyediakan layanan verifikasi sertifikat</li>
                        <li>Mengelola akun dan preferensi Anda</li>
                        <li>Mengirim notifikasi terkait layanan</li>
                        <li>Meningkatkan kualitas layanan kami</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">3. Keamanan Data</h2>
                    <p class="leading-relaxed">Kami menggunakan enkripsi dan protokol keamanan standar industri untuk melindungi data Anda. Data sertifikat disimpan dengan teknologi blockchain yang memastikan integritas dan keamanan.</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">4. Berbagi Informasi</h2>
                    <p class="leading-relaxed">Kami tidak menjual atau membagikan informasi pribadi Anda kepada pihak ketiga, kecuali:</p>
                    <ul class="list-disc list-inside mt-3 space-y-2 text-[#BEDBFF]">
                        <li>Dengan persetujuan Anda</li>
                        <li>Untuk memenuhi kewajiban hukum</li>
                        <li>Untuk melindungi hak dan keamanan kami</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">5. Hak Anda</h2>
                    <p class="leading-relaxed">Anda memiliki hak untuk mengakses, memperbarui, atau menghapus data pribadi Anda. Hubungi kami di <a href="mailto:privacy@sertiku.com" class="text-[#3B82F6] hover:underline">privacy@sertiku.com</a> untuk permintaan terkait privasi.</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">6. Kontak</h2>
                    <p class="leading-relaxed">Jika Anda memiliki pertanyaan tentang kebijakan privasi ini, silakan hubungi kami melalui email di <a href="mailto:support@sertiku.com" class="text-[#3B82F6] hover:underline">support@sertiku.com</a>.</p>
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
