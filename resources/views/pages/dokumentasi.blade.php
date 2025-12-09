{{-- resources/views/pages/dokumentasi.blade.php --}}
<x-layouts.app title="SertiKu – Dokumentasi">

    <main class="min-h-screen bg-gradient-to-b from-[#0F172A] via-[#1E293B] to-[#0F172A]">
        {{-- Hero --}}
        <section class="relative overflow-hidden py-16 md:py-20">
            <div class="pointer-events-none absolute -right-32 top-0 h-96 w-96 rounded-full bg-gradient-to-r from-[#AD46FF33] to-[#F6339A33] blur-3xl opacity-60"></div>
            <div class="mx-auto max-w-4xl px-4 text-center relative z-10">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Dokumentasi</h1>
                <p class="text-lg text-[#BEDBFF]/80 max-w-2xl mx-auto">
                    Pelajari cara menggunakan SertiKu dengan panduan lengkap
                </p>
            </div>
        </section>

        {{-- Content --}}
        <section class="py-12 px-4">
            <div class="mx-auto max-w-5xl">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    {{-- Sidebar --}}
                    <aside class="lg:col-span-1">
                        <nav class="sticky top-24 space-y-2">
                            <a href="#getting-started" class="block px-4 py-2 rounded-lg bg-[#3B82F6]/20 text-white text-sm font-medium">Memulai</a>
                            <a href="#certificates" class="block px-4 py-2 rounded-lg text-[#BEDBFF]/70 text-sm hover:bg-white/5 transition">Sertifikat</a>
                            <a href="#verification" class="block px-4 py-2 rounded-lg text-[#BEDBFF]/70 text-sm hover:bg-white/5 transition">Verifikasi</a>
                            <a href="#api" class="block px-4 py-2 rounded-lg text-[#BEDBFF]/70 text-sm hover:bg-white/5 transition">API</a>
                            <a href="#integration" class="block px-4 py-2 rounded-lg text-[#BEDBFF]/70 text-sm hover:bg-white/5 transition">Integrasi</a>
                        </nav>
                    </aside>

                    {{-- Main Content --}}
                    <div class="lg:col-span-3 space-y-12">
                        {{-- Getting Started --}}
                        <article id="getting-started" class="prose prose-invert max-w-none">
                            <h2 class="text-2xl font-bold text-white mb-4">Memulai dengan SertiKu</h2>
                            <div class="rounded-xl bg-white/5 border border-white/10 p-6 space-y-4">
                                <h3 class="text-lg font-semibold text-white">1. Buat Akun</h3>
                                <p class="text-[#BEDBFF]/80">
                                    Daftar akun SertiKu melalui halaman registrasi. Anda bisa mendaftar sebagai Pengguna individual 
                                    atau Lembaga/Institusi.
                                </p>
                                
                                <h3 class="text-lg font-semibold text-white">2. Lengkapi Profil</h3>
                                <p class="text-[#BEDBFF]/80">
                                    Setelah login, lengkapi profil Anda dengan informasi yang diperlukan untuk mulai menggunakan platform.
                                </p>
                                
                                <h3 class="text-lg font-semibold text-white">3. Mulai Buat Sertifikat</h3>
                                <p class="text-[#BEDBFF]/80">
                                    Akses dashboard dan mulai membuat sertifikat pertama Anda dengan template yang tersedia.
                                </p>
                            </div>
                        </article>

                        {{-- Certificates --}}
                        <article id="certificates" class="prose prose-invert max-w-none">
                            <h2 class="text-2xl font-bold text-white mb-4">Membuat Sertifikat</h2>
                            <div class="rounded-xl bg-white/5 border border-white/10 p-6 space-y-4">
                                <p class="text-[#BEDBFF]/80">
                                    SertiKu menyediakan berbagai template sertifikat yang dapat dikustomisasi sesuai kebutuhan Anda.
                                </p>
                                
                                <div class="bg-[#1E293B] rounded-lg p-4 mt-4">
                                    <p class="text-xs text-[#BEDBFF]/60 mb-2">Contoh penggunaan API</p>
                                    <pre class="text-sm text-[#10B981] overflow-x-auto"><code>POST /api/certificates
{
  "recipient": "John Doe",
  "title": "Certificate of Completion",
  "date": "2025-01-01"
}</code></pre>
                                </div>
                            </div>
                        </article>

                        {{-- Verification --}}
                        <article id="verification" class="prose prose-invert max-w-none">
                            <h2 class="text-2xl font-bold text-white mb-4">Verifikasi Sertifikat</h2>
                            <div class="rounded-xl bg-white/5 border border-white/10 p-6 space-y-4">
                                <p class="text-[#BEDBFF]/80">
                                    Setiap sertifikat memiliki kode unik yang dapat diverifikasi melalui halaman verifikasi atau API.
                                </p>
                                <ul class="space-y-2 text-[#BEDBFF]/80">
                                    <li>• Scan QR code pada sertifikat</li>
                                    <li>• Masukkan kode hash di halaman verifikasi</li>
                                    <li>• Gunakan API untuk verifikasi otomatis</li>
                                </ul>
                            </div>
                        </article>

                        {{-- API --}}
                        <article id="api" class="prose prose-invert max-w-none">
                            <h2 class="text-2xl font-bold text-white mb-4">API Reference</h2>
                            <div class="rounded-xl bg-white/5 border border-white/10 p-6 space-y-4">
                                <p class="text-[#BEDBFF]/80">
                                    Dokumentasi API lengkap untuk integrasi dengan sistem Anda.
                                </p>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <span class="px-2 py-1 rounded bg-[#10B981]/20 text-[#10B981] text-xs font-mono">GET</span>
                                        <code class="text-sm text-white">/api/certificates</code>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="px-2 py-1 rounded bg-[#3B82F6]/20 text-[#3B82F6] text-xs font-mono">POST</span>
                                        <code class="text-sm text-white">/api/certificates</code>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="px-2 py-1 rounded bg-[#F59E0B]/20 text-[#F59E0B] text-xs font-mono">GET</span>
                                        <code class="text-sm text-white">/api/verify/{hash}</code>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </main>

</x-layouts.app>
