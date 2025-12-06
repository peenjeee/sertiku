{{-- resources/views/verifikasi/invalid.blade.php --}}
<x-layouts.verifikasi title="Hasil Verifikasi – SertiKu">

    <main class="min-h-screen bg-gradient-to-br from-[#F8FAFC] via-[#EFF6FF] to-[#ECFEFF] relative">

        {{-- HEADER ATAS: Kembali ke Verifikasi --}}
        <header
            class="w-full border-b border-[#DBEAFE] bg-[rgba(255,255,255,0.8)] shadow-[0_1px_3px_rgba(0,0,0,0.1),0_1px_2px_-1px_rgba(0,0,0,0.1)]">
            <div class="mx-auto max-w-5xl px-4 py-3">
                <a href="{{ route('verifikasi') }}"
                   class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-normal text-[#1447E6] hover:bg-[#E0ECFF] transition">
                    {{-- Icon panah kiri --}}
                    <span class="relative inline-flex h-5 w-5 items-center justify-center">
                        <span class="absolute inset-0 flex items-center justify-center">
                            <svg width="16" height="16" viewBox="0 0 16 16" class="text-[#1447E6]" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.66667 3.33301L2 7.99967L6.66667 12.6663"
                                      stroke="currentColor" stroke-width="1.333"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 8H2.66667"
                                      stroke="currentColor" stroke-width="1.333"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </span>
                    <span>Kembali ke Verifikasi</span>
                </a>
            </div>
        </header>

        {{-- KONTEN UTAMA --}}
        <section class="mx-auto max-w-5xl px-4 pt-24 pb-16 flex flex-col items-center">

            {{-- ICON BESAR DI ATAS --}}
            <div class="relative mb-10">
                {{-- glow --}}
                <div class="absolute inset-0 rounded-full bg-gradient-to-br from-[#FF6467] to-[#FF8904]
                            opacity-40 blur-[64px]"></div>

                <div class="relative flex h-40 w-40 items-center justify-center rounded-full
                            bg-gradient-to-br from-[#FB2C36] to-[#F54900]
                            shadow-[0_25px_50px_-12px_rgba(0,0,0,0.25)]">
                    <div class="relative flex h-24 w-24 items-center justify-center rounded-full opacity-75">
                        <span class="absolute inset-[8px] rounded-full border-[8px] border-white"></span>
                        <span class="h-8 w-[3px] rotate-45 rounded-full bg-white"></span>
                        <span class="h-8 w-[3px] -rotate-45 rounded-full bg-white"></span>
                    </div>
                </div>
            </div>

            {{-- JUDUL & SUBJUDUL ATAS --}}
            <div class="text-center space-y-2 mb-8">
                <p class="text-base font-normal text-[#E7000B]">
                    Sertifikat Tidak Valid
                </p>
                <p class="text-lg md:text-xl font-normal text-[#C10007]">
                    Sertifikat tidak ditemukan dalam sistem kami
                </p>
            </div>

            {{-- KARTU PUTIH BESAR --}}
            <div
                class="w-full max-w-3xl rounded-[14px] bg-white shadow-[0_25px_50px_-12px_rgba(0,0,0,0.25)] overflow-hidden">

                {{-- strip gradient atas --}}
                <div class="h-3 w-full bg-gradient-to-r from-[#FB2C36] via-[#FF6900] to-[#F0B100]"></div>

                <div class="px-6 sm:px-10 pt-10 pb-10 relative">

                    {{-- Bagian icon & judul tengah --}}
                    <div class="relative mx-auto flex flex-col items-center text-center max-w-xl mb-8">
                        <div class="flex h-20 w-20 items-center justify-center rounded-full bg-[#FFE2E2] mb-5">
                            <div class="relative h-10 w-10">
                                <span class="absolute inset-[4px] rounded-full border-[3px] border-[#E7000B]"></span>
                                <span class="absolute left-1/2 top-1/2 h-4 w-[2.5px] -translate-x-1/2 -translate-y-1/2 rotate-45 rounded-full bg-[#E7000B]"></span>
                                <span class="absolute left-1/2 top-1/2 h-4 w-[2.5px] -translate-x-1/2 -translate-y-1/2 -rotate-45 rounded-full bg-[#E7000B]"></span>
                            </div>
                        </div>

                        <h2 class="text-base font-normal text-[#82181A]">
                            Verifikasi Gagal
                        </h2>
                        <p class="mt-3 text-sm md:text-base font-normal leading-relaxed text-[#C10007]">
                            Kode hash yang Anda masukkan tidak terdaftar dalam database sistem kami.
                        </p>
                    </div>

                    {{-- Garis pemisah --}}
                    <div class="my-6 h-px w-full bg-[#E2E8F0]"></div>

                    {{-- KEMUNGKINAN PENYEBAB --}}
                    <div
                        class="rounded-[16px] border border-[#FFC9C9] bg-gradient-to-br from-[#FEF2F2] to-[#FFF7ED] px-6 py-6 sm:px-7 sm:py-7 mb-8">
                        <div class="flex gap-4">
                            {{-- icon lingkar merah --}}
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-[#FB2C36]">
                                <div class="relative h-6 w-6">
                                    <span class="absolute inset-[3px] rounded-full border-[2px] border-white"></span>
                                    <span
                                        class="absolute left-1/2 top-[42%] h-[7px] w-[2px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-white"></span>
                                    <span
                                        class="absolute left-1/2 top-[65%] h-[7px] w-[2px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-white"></span>
                                </div>
                            </div>

                            <div class="flex-1 space-y-3">
                                <p class="text-base font-normal text-[#82181A]">
                                    Kemungkinan Penyebab
                                </p>

                                <ul class="space-y-2 text-sm md:text-base">
                                    <li class="relative pl-4 text-[#C10007]">
                                        <span
                                            class="absolute left-0 top-[0.7em] h-[6px] w-[6px] -translate-y-1/2 rounded-full bg-[#FB2C36]"></span>
                                        Kode hash salah atau tidak valid
                                    </li>
                                    <li class="relative pl-4 text-[#C10007]">
                                        <span
                                            class="absolute left-0 top-[0.7em] h-[6px] w-[6px] -translate-y-1/2 rounded-full bg-[#FB2C36]"></span>
                                        Sertifikat belum diterbitkan atau sudah dihapus dari sistem
                                    </li>
                                    <li class="relative pl-4 text-[#C10007]">
                                        <span
                                            class="absolute left-0 top-[0.7em] h-[6px] w-[6px] -translate-y-1/2 rounded-full bg-[#FB2C36]"></span>
                                        Kemungkinan sertifikat palsu atau dipalsukan
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- LANGKAH SELANJUTNYA --}}
                    <div>
                        <p class="mb-4 text-base font-normal text-[#1C398E]">
                            Langkah Selanjutnya:
                        </p>

                        {{-- Tombol 1: Coba Verifikasi Lagi --}}
                        <a href="{{ route('verifikasi') }}"
                           class="block rounded-[8px] border border-[#BEDBFF] bg-white px-4 py-4 mb-4 hover:bg-[#F9FBFF] transition">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-[14px] bg-[#DBEAFE]">
                                    <div class="relative h-5 w-5">
                                        {{-- icon "refresh / arrow kiri" --}}
                                        <span
                                            class="absolute left-[22%] top-1/2 h-[1.5px] w-[45%] -translate-y-1/2 rounded-full bg-[#155DFC]"></span>
                                        <span
                                            class="absolute left-[22%] top-[35%] h-[45%] w-[1.5px] rounded-full bg-[#155DFC]"></span>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-sm font-normal text-[#1C398E]">
                                        Coba Verifikasi Lagi
                                    </p>
                                    <p class="text-sm font-normal text-[#155DFC]">
                                        Periksa kembali kode hash Anda
                                    </p>
                                </div>
                            </div>
                        </a>

                        {{-- Tombol 2: Laporkan Sertifikat Palsu --}}
                        <a href="#"
                           class="block rounded-[8px] border border-[#FFC9C9] bg-white px-4 py-4 hover:bg-[#FFF5F5] transition">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-[14px] bg-[#FFE2E2]">
                                    <div class="relative h-5 w-5">
                                        {{-- icon warning --}}
                                        <span
                                            class="absolute left-[44%] top-[25%] h-[40%] w-[1.5px] rounded-full bg-[#E7000B]"></span>
                                        <span
                                            class="absolute left-[44%] top-[70%] h-[1.5px] w-[1.5px] rounded-full bg-[#E7000B]"></span>
                                        <span
                                            class="absolute inset-[3px] border-[1.6px] border-[#E7000B] rounded-full"></span>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-sm font-normal text-[#82181A]">
                                        Laporkan Sertifikat Palsu
                                    </p>
                                    <p class="text-sm font-normal text-[#E7000B]">
                                        Bantu kami mencegah pemalsuan
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- CHAT WIDGET FLOATING --}}
        <button type="button"
                class="fixed bottom-6 right-6 md:bottom-8 md:right-8 flex h-14 w-14 items-center justify-center
                       rounded-full bg-gradient-to-b from-[#1E3A8A] to-[#3B82F6]
                       shadow-[0_20px_25px_-5px_rgba(0,0,0,0.1),0_8px_10px_-6px_rgba(0,0,0,0.1)]">
            <div class="relative h-4 w-4">
                {{-- icon chat sederhana --}}
                <span
                    class="absolute inset-[1px] rounded-[4px] border-[1.3px] border-white border-b-transparent"></span>
                <span
                    class="absolute bottom-[3px] left-[4px] h-[6px] w-[6px] rotate-45 border-b-[1.3px] border-l-[1.3px] border-white"></span>
            </div>
        </button>

    </main>
</x-layouts.app>
