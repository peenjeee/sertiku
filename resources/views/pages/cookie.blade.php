<x-layouts.app title="Kebijakan Cookie – SertiKu">
    <main class="py-16">
        <div class="max-w-4xl mx-auto px-4">
            {{-- Header --}}
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#3B82F6] mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">Kebijakan Cookie</h1>
                <p class="text-[#BEDBFF]">Terakhir diperbarui: 10 Januari 2026</p>
            </div>

            {{-- Content Card --}}
            <div class="bg-white/10 rounded-2xl border border-white/20 p-8 md:p-12 text-[#DBEAFE] space-y-8">

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">Apa itu Cookie?</h2>
                    <p class="leading-relaxed">Cookie adalah file teks kecil yang disimpan di perangkat Anda saat
                        mengunjungi website. Cookie membantu website mengingat preferensi dan aktivitas Anda.</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">Cookie yang Kami Gunakan</h2>
                    <div class="space-y-4">
                        <div class="bg-white/5 rounded-lg p-4">
                            <h3 class="font-semibold text-white mb-2">Cookie Esensial</h3>
                            <p class="text-sm text-[#BEDBFF]">Diperlukan untuk fungsi dasar website seperti login dan
                                keamanan. Tidak dapat dinonaktifkan.</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-4">
                            <h3 class="font-semibold text-white mb-2">Cookie Analitik</h3>
                            <p class="text-sm text-[#BEDBFF]">Membantu kami memahami bagaimana pengunjung menggunakan
                                website untuk meningkatkan pengalaman pengguna.</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-4">
                            <h3 class="font-semibold text-white mb-2">⚙️ Cookie Fungsional</h3>
                            <p class="text-sm text-[#BEDBFF]">Mengingat preferensi Anda seperti bahasa dan tema
                                tampilan.</p>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">Mengelola Cookie</h2>
                    <p class="leading-relaxed">Anda dapat mengontrol dan menghapus cookie melalui pengaturan browser
                        Anda. Namun, menonaktifkan cookie tertentu dapat mempengaruhi fungsionalitas website.</p>
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
                        <a href="https://support.google.com/chrome/answer/95647" target="_blank"
                            class="bg-white/5 rounded-lg p-3 text-center text-sm hover:bg-white/10 transition">Chrome</a>
                        <a href="https://support.mozilla.org/kb/cookies" target="_blank"
                            class="bg-white/5 rounded-lg p-3 text-center text-sm hover:bg-white/10 transition">Firefox</a>
                        <a href="https://support.apple.com/guide/safari/manage-cookies" target="_blank"
                            class="bg-white/5 rounded-lg p-3 text-center text-sm hover:bg-white/10 transition">Safari</a>
                        <a href="https://support.microsoft.com/microsoft-edge/cookies" target="_blank"
                            class="bg-white/5 rounded-lg p-3 text-center text-sm hover:bg-white/10 transition">Edge</a>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">Cookie Pihak Ketiga</h2>
                    <p class="leading-relaxed">Kami menggunakan layanan pihak ketiga yang mungkin mengatur cookie mereka
                        sendiri:</p>
                    <ul class="list-disc list-inside mt-3 space-y-2 text-[#BEDBFF]">
                        <li>Google Analytics - untuk analisis penggunaan</li>
                        <li>Midtrans - untuk pemrosesan pembayaran</li>
                        <li>WalletConnect - untuk koneksi wallet</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">Perubahan Kebijakan</h2>
                    <p class="leading-relaxed">Kami dapat memperbarui kebijakan cookie ini dari waktu ke waktu.
                        Perubahan akan dipublikasikan di halaman ini dengan tanggal pembaruan terbaru.</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">Kontak</h2>
                    <p class="leading-relaxed">Jika Anda memiliki pertanyaan tentang penggunaan cookie, hubungi <a
                            href="mailto:privacy@sertiku.com"
                            class="text-[#3B82F6] hover:underline">privacy@sertiku.com</a>.</p>
                </section>
            </div>

            {{-- Back Button --}}
            <div class="mt-8 text-center">
                <a href="{{ url()->previous() }}"
                    class="inline-flex items-center gap-2 text-[#BEDBFF] hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </main>
</x-layouts.app>