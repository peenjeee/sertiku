{{-- resources/views/pages/bantuan.blade.php --}}
<x-layouts.app title="SertiKu – Bantuan">


    {{-- Hero --}}
    <section class=" verflow-hidden py-16 md:py-24">
        <div
            class="pointer-events-none absolute -left-32 top-0 h-96 w-96 rounded-full bg-gradient-to-r from-[#2B7FFF4D] to-[#00B8DB4D] blur-3xl opacity-60">
        </div>
        <div class="mx-auto max-w-4xl px-4 text-center relative z-10">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 animate-fade-in-up">Pusat Bantuan</h1>
            <p class="text-lg text-[#BEDBFF]/80 max-w-2xl mx-auto animate-fade-in-up stagger-1">
                Temukan jawaban untuk pertanyaan Anda atau hubungi tim support kami
            </p>

            {{-- Search --}}
            <div class="mt-8 max-w-xl mx-auto">
                <div class="relative">
                    <input type="text" placeholder="Cari bantuan..."
                        class="w-full rounded-xl bg-white/10 border border-white/20 px-5 py-4 pl-12 text-white placeholder:text-white/50 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-white/50" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ Categories --}}
    <section class="py-12 px-4">
        <div class="mx-auto max-w-5xl">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Getting Started --}}
                <div class="rounded-2xl bg-white/5 border border-white/10 p-6 hover:border-[#3B82F6]/50 transition hover-lift animate-fade-in-up stagger-1">
                    <div class="w-12 h-12 rounded-xl bg-[#3B82F6]/20 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Memulai</h3>
                    <p class="text-sm text-[#BEDBFF]/70 mb-4">Panduan dasar untuk memulai menggunakan SertiKu</p>
                    <ul class="space-y-2 text-sm text-[#BEDBFF]/80">
                        <li><a href="#" class="hover:text-white transition">• Cara membuat akun</a></li>
                        <li><a href="#" class="hover:text-white transition">• Membuat sertifikat pertama</a></li>
                        <li><a href="#" class="hover:text-white transition">• Verifikasi sertifikat</a></li>
                    </ul>
                </div>

                {{-- Account --}}
                <div class="rounded-2xl bg-white/5 border border-white/10 p-6 hover:border-[#3B82F6]/50 transition hover-lift animate-fade-in-up stagger-2">
                    <div class="w-12 h-12 rounded-xl bg-[#10B981]/20 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Akun & Keamanan</h3>
                    <p class="text-sm text-[#BEDBFF]/70 mb-4">Kelola profil dan keamanan akun Anda</p>
                    <ul class="space-y-2 text-sm text-[#BEDBFF]/80">
                        <li><a href="#" class="hover:text-white transition">• Mengubah password</a></li>
                        <li><a href="#" class="hover:text-white transition">• Login dengan Google/Wallet</a></li>
                        <li><a href="#" class="hover:text-white transition">• Menghapus akun</a></li>
                    </ul>
                </div>

                {{-- Billing --}}
                <div class="rounded-2xl bg-white/5 border border-white/10 p-6 hover:border-[#3B82F6]/50 transition hover-lift animate-fade-in-up stagger-3">
                    <div class="w-12 h-12 rounded-xl bg-[#F59E0B]/20 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Pembayaran</h3>
                    <p class="text-sm text-[#BEDBFF]/70 mb-4">Informasi tentang paket dan pembayaran</p>
                    <ul class="space-y-2 text-sm text-[#BEDBFF]/80">
                        <li><a href="#" class="hover:text-white transition">• Paket berlangganan</a></li>
                        <li><a href="#" class="hover:text-white transition">• Metode pembayaran</a></li>
                        <li><a href="#" class="hover:text-white transition">• Refund & pembatalan</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="py-12 px-4">
        <div class="mx-auto max-w-3xl">
            <h2 class="text-2xl font-bold text-white text-center mb-8 animate-fade-in">Pertanyaan Umum</h2>

            <div class="space-y-4">
                @php
                    $faqs = [
                        ['q' => 'Bagaimana cara membuat sertifikat?', 'a' => 'Setelah login, masuk ke dashboard dan klik "Buat Sertifikat Baru". Isi detail sertifikat, Upload Sertifikat, dan klik simpan.'],
                        ['q' => 'Apakah sertifikat saya aman?', 'a' => 'Ya, semua sertifikat disimpan dengan enkripsi dan dapat diverifikasi melalui teknologi blockchain.'],
                        ['q' => 'Berapa biaya untuk menggunakan SertiKu?', 'a' => 'Kami menyediakan paket gratis untuk pengguna personal. Untuk institusi, silakan lihat halaman harga.'],
                        ['q' => 'Bagaimana cara menghubungi support?', 'a' => 'Anda bisa menghubungi kami melalui halaman Hubungi Kami atau email ke support@sertiku.my.id'],
                    ];
                @endphp

                @foreach($faqs as $index => $faq)
                    <details class="group rounded-xl bg-white/5 border border-white/10">
                        <summary class="flex cursor-pointer items-center justify-between p-5 text-white">
                            <span class="font-medium">{{ $faq['q'] }}</span>
                            <svg class="w-5 h-5 text-[#BEDBFF] transition group-open:rotate-180" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <p class="px-5 pb-5 text-sm text-[#BEDBFF]/80">{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Contact CTA --}}
    <section class="py-12 px-4">
        <div class="mx-auto max-w-3xl text-center">
            <div class="rounded-2xl bg-gradient-to-r from-[#1E3A8F]/30 to-[#3B82F6]/30 border border-[#3B82F6]/30 p-8 animate-fade-in-up hover-glow">
                <h3 class="text-xl font-semibold text-white mb-2">Masih butuh bantuan?</h3>
                <p class="text-[#BEDBFF]/80 mb-6">Tim support kami siap membantu Anda</p>
                <a href="{{ route('kontak') }}"
                    class="inline-flex items-center rounded-lg bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6] px-6 py-3 text-sm font-medium text-white shadow-lg hover:brightness-110 transition">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </section>


</x-layouts.app>
