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
                    <input type="text" id="searchHelp" placeholder="Cari bantuan..."
                        oninput="searchFAQ(this.value)"
                        class="w-full rounded-xl bg-white/10 border border-white/20 px-5 py-4 pl-12 text-white placeholder:text-white/50 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-white/50" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <button type="button" id="clearSearch" onclick="clearSearch()" class="hidden absolute right-4 top-1/2 -translate-y-1/2 text-white/50 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <p id="searchResult" class="mt-2 text-sm text-[#BEDBFF]/60 hidden"></p>
            </div>
        </div>
    </section>

    {{-- FAQ Categories --}}
    <section class="py-12 px-4" id="categoriesSection">
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
                        <li><a href="{{ route('register') }}" class="hover:text-white transition flex items-center gap-2">
                            <span class="text-[#3B82F6]">→</span> Cara membuat akun
                        </a></li>
                        <li><a href="#faq-membuat-sertifikat" class="hover:text-white transition flex items-center gap-2" onclick="openFAQ('membuat-sertifikat')">
                            <span class="text-[#3B82F6]">→</span> Membuat sertifikat pertama
                        </a></li>
                        <li><a href="{{ route('verifikasi') }}" class="hover:text-white transition flex items-center gap-2">
                            <span class="text-[#3B82F6]">→</span> Verifikasi sertifikat
                        </a></li>
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
                        <li><a href="#faq-ubah-password" class="hover:text-white transition flex items-center gap-2" onclick="openFAQ('ubah-password')">
                            <span class="text-[#10B981]">→</span> Mengubah password
                        </a></li>
                        <li><a href="#faq-login-google" class="hover:text-white transition flex items-center gap-2" onclick="openFAQ('login-google')">
                            <span class="text-[#10B981]">→</span> Login dengan Google/Wallet
                        </a></li>
                        <li><a href="#faq-hapus-akun" class="hover:text-white transition flex items-center gap-2" onclick="openFAQ('hapus-akun')">
                            <span class="text-[#10B981]">→</span> Menghapus akun
                        </a></li>
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
                        <li><a href="#faq-paket" class="hover:text-white transition flex items-center gap-2" onclick="openFAQ('paket')">
                            <span class="text-[#F59E0B]">→</span> Paket berlangganan
                        </a></li>
                        <li><a href="#faq-metode-bayar" class="hover:text-white transition flex items-center gap-2" onclick="openFAQ('metode-bayar')">
                            <span class="text-[#F59E0B]">→</span> Metode pembayaran
                        </a></li>
                        <li><a href="#faq-refund" class="hover:text-white transition flex items-center gap-2" onclick="openFAQ('refund')">
                            <span class="text-[#F59E0B]">→</span> Refund & pembatalan
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="py-12 px-4" id="faqSection">
        <div class="mx-auto max-w-3xl">
            <h2 class="text-2xl font-bold text-white text-center mb-8 animate-fade-in">Pertanyaan Umum</h2>

            <div class="space-y-4" id="faqContainer">
                @php
                    $faqs = [
                        // Memulai
                        ['id' => 'membuat-sertifikat', 'category' => 'memulai', 'q' => 'Bagaimana cara membuat sertifikat?', 'a' => 'Setelah login sebagai Lembaga, masuk ke dashboard dan klik "Buat Sertifikat Baru". Pilih template, isi detail penerima seperti nama dan email, upload file sertifikat PDF/gambar, lalu klik "Terbitkan". Sertifikat akan otomatis dikirim ke email penerima.'],
                        ['id' => 'verifikasi', 'category' => 'memulai', 'q' => 'Bagaimana cara verifikasi sertifikat?', 'a' => 'Buka halaman Verifikasi, kemudian masukkan kode hash yang tertera di sertifikat atau scan QR Code yang ada di sertifikat. Sistem akan menampilkan status keaslian dan detail sertifikat secara real-time.'],
                        ['id' => 'blockchain', 'category' => 'memulai', 'q' => 'Apa itu verifikasi blockchain?', 'a' => 'Verifikasi blockchain adalah teknologi yang menyimpan hash sertifikat ke jaringan blockchain (Polygon). Ini memastikan sertifikat tidak bisa dipalsukan karena setiap perubahan akan terdeteksi. Sertifikat yang sudah tercatat di blockchain bersifat permanen dan dapat diverifikasi oleh siapapun.'],

                        // Akun & Keamanan
                        ['id' => 'ubah-password', 'category' => 'akun', 'q' => 'Bagaimana cara mengubah password?', 'a' => 'Masuk ke halaman Pengaturan > Keamanan. Masukkan password lama Anda, kemudian masukkan password baru minimal 8 karakter dengan kombinasi huruf dan angka. Klik "Simpan Perubahan" untuk menyimpan password baru.'],
                        ['id' => 'login-google', 'category' => 'akun', 'q' => 'Bagaimana cara login dengan Google atau Wallet?', 'a' => 'Di halaman login, klik tombol "Login dengan Google" untuk menggunakan akun Google Anda, atau klik "Connect Wallet" untuk login menggunakan MetaMask. Jika belum punya akun, sistem akan otomatis membuatkan akun baru.'],
                        ['id' => 'hapus-akun', 'category' => 'akun', 'q' => 'Bagaimana cara menghapus akun?', 'a' => 'Masuk ke Pengaturan > Hapus Akun. Masukkan password Anda untuk konfirmasi. Perhatian: Semua data termasuk sertifikat yang Anda terima akan dihapus secara permanen. Sertifikat yang sudah tercatat di blockchain tetap valid.'],

                        // Pembayaran
                        ['id' => 'paket', 'category' => 'pembayaran', 'q' => 'Apa saja paket berlangganan yang tersedia?', 'a' => 'SertiKu menyediakan 3 paket: <br><strong>• Starter (Gratis)</strong> - 50 sertifikat/bulan, cocok untuk personal<br><strong>• Professional (Rp 67.000/bulan)</strong> - 6.700 sertifikat/bulan dengan fitur lengkap<br><strong>• Enterprise (Custom)</strong> - Unlimited dengan dukungan dedicated'],
                        ['id' => 'metode-bayar', 'category' => 'pembayaran', 'q' => 'Metode pembayaran apa saja yang didukung?', 'a' => 'Kami mendukung berbagai metode pembayaran melalui Midtrans: Transfer Bank (BCA, Mandiri, BNI, BRI, dll), E-Wallet (GoPay, OVO, DANA, ShopeePay), Kartu Kredit/Debit, serta QRIS untuk pembayaran dari aplikasi manapun.'],
                        ['id' => 'refund', 'category' => 'pembayaran', 'q' => 'Bagaimana kebijakan refund dan pembatalan?', 'a' => 'Anda dapat membatalkan langganan kapan saja. Untuk refund, hubungi tim support kami dalam 7 hari setelah pembayaran jika layanan tidak sesuai ekspektasi. Refund akan diproses dalam 3-5 hari kerja ke metode pembayaran asal.'],

                        // Lainnya
                        ['id' => 'keamanan', 'category' => 'lainnya', 'q' => 'Apakah sertifikat saya aman?', 'a' => 'Ya, semua sertifikat disimpan dengan enkripsi AES-256 dan dapat diverifikasi melalui teknologi blockchain Polygon. Server kami menggunakan SSL/TLS dan backup harian. Hash sertifikat yang tersimpan di blockchain tidak bisa diubah atau dihapus.'],
                        ['id' => 'support', 'category' => 'lainnya', 'q' => 'Bagaimana cara menghubungi support?', 'a' => 'Anda bisa menghubungi kami melalui: <br><strong>• Email:</strong> support@sertiku.web.id (respon 24 jam)<br><strong>• WhatsApp:</strong> +62 812 3456 7890<br><strong>• Halaman Kontak:</strong> <a href="' . route('kontak') . '" class="text-[#3B82F6] hover:underline">Hubungi Kami</a>'],
                        ['id' => 'template', 'category' => 'lainnya', 'q' => 'Bisakah saya membuat template sertifikat sendiri?', 'a' => 'Ya! Sebagai Lembaga, Anda bisa upload template sertifikat sendiri dalam format DOCX atau PDF. Sistem akan mendeteksi placeholder untuk nama penerima, tanggal, dll. Anda juga bisa menggunakan template default yang sudah kami sediakan.'],
                    ];
                @endphp

                @foreach($faqs as $index => $faq)
                    <details class="group rounded-xl bg-white/5 border border-white/10 faq-item"
                             id="faq-{{ $faq['id'] }}"
                             data-question="{{ strtolower($faq['q']) }}"
                             data-answer="{{ strtolower(strip_tags($faq['a'])) }}"
                             data-category="{{ $faq['category'] }}">
                        <summary class="flex cursor-pointer items-center justify-between p-5 text-white">
                            <span class="font-medium">{{ $faq['q'] }}</span>
                            <svg class="w-5 h-5 text-[#BEDBFF] transition group-open:rotate-180 flex-shrink-0 ml-2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="px-5 pb-5 text-sm text-[#BEDBFF]/80">{!! $faq['a'] !!}</div>
                    </details>
                @endforeach
            </div>

            <p id="noResults" class="hidden text-center text-[#BEDBFF]/60 py-8">
                Tidak ada hasil yang ditemukan. Coba kata kunci lain atau <a href="{{ route('kontak') }}" class="text-[#3B82F6] hover:underline">hubungi kami</a>.
            </p>
        </div>
    </section>

    {{-- Contact CTA --}}
    <section class="py-12 px-4">
        <div class="mx-auto max-w-3xl text-center">
            <div class="rounded-2xl bg-gradient-to-r from-[#1E3A8F]/30 to-[#3B82F6]/30 border border-[#3B82F6]/30 p-8 animate-fade-in-up hover-glow">
                <h3 class="text-xl font-semibold text-white mb-2">Masih butuh bantuan?</h3>
                <p class="text-[#BEDBFF]/80 mb-6">Tim support kami siap membantu Anda 24/7</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('kontak') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-b from-[#1E3A8F] to-[#3B82F6] px-6 py-3 text-sm font-medium text-white shadow-lg hover:brightness-110 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Hubungi via Email
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#25D366]/20 border border-[#25D366]/30 px-6 py-3 text-sm font-medium text-[#25D366] hover:bg-[#25D366]/30 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Chat WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Search & FAQ Script --}}
    <script>
        function searchFAQ(query) {
            const items = document.querySelectorAll('.faq-item');
            const noResults = document.getElementById('noResults');
            const searchResult = document.getElementById('searchResult');
            const clearBtn = document.getElementById('clearSearch');
            const categoriesSection = document.getElementById('categoriesSection');

            query = query.toLowerCase().trim();

            if (query.length === 0) {
                clearSearch();
                return;
            }

            clearBtn.classList.remove('hidden');
            categoriesSection.classList.add('hidden');

            let found = 0;
            items.forEach(item => {
                const question = item.dataset.question;
                const answer = item.dataset.answer;

                if (question.includes(query) || answer.includes(query)) {
                    item.classList.remove('hidden');
                    found++;
                } else {
                    item.classList.add('hidden');
                }
            });

            if (found === 0) {
                noResults.classList.remove('hidden');
                searchResult.classList.add('hidden');
            } else {
                noResults.classList.add('hidden');
                searchResult.textContent = `Ditemukan ${found} hasil untuk "${query}"`;
                searchResult.classList.remove('hidden');
            }
        }

        function clearSearch() {
            document.getElementById('searchHelp').value = '';
            document.getElementById('clearSearch').classList.add('hidden');
            document.getElementById('searchResult').classList.add('hidden');
            document.getElementById('noResults').classList.add('hidden');
            document.getElementById('categoriesSection').classList.remove('hidden');

            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('hidden');
                item.removeAttribute('open');
            });
        }

        function openFAQ(id) {
            // Scroll to FAQ section
            const faqSection = document.getElementById('faqSection');
            faqSection.scrollIntoView({ behavior: 'smooth' });

            // Open the specific FAQ
            setTimeout(() => {
                const faq = document.getElementById('faq-' + id);
                if (faq) {
                    faq.setAttribute('open', '');
                    faq.scrollIntoView({ behavior: 'smooth', block: 'center' });

                    // Highlight briefly
                    faq.classList.add('ring-2', 'ring-[#3B82F6]');
                    setTimeout(() => {
                        faq.classList.remove('ring-2', 'ring-[#3B82F6]');
                    }, 2000);
                }
            }, 500);
        }
    </script>


</x-layouts.app>
