<x-layouts.app title="Hasil Verifikasi – SertiKu">

    {{-- MAIN --}}
    <section class="mx-auto max-w-5xl px-4 pt-24 pb-16 flex flex-col items-center">
        <div class="mx-auto flex w-full max-w-5xl flex-col items-center gap-10">

            
            {{-- CARD UTAMA --}}
            <section class="mt-20 relative w-full max-w-3xl rounded-2xl bg-white shadow-[0_25px_50px_-12px_rgba(15,23,42,0.25)]">
                {{-- Garis gradien di atas kartu --}}
                <div class="h-3 w-full  rounded-t-2xl bg-gradient-to-r from-[#22C55E] via-[#22C55E] to-[#38BDF8]"></div>

                {{-- Icon kecil di tengah atas card --}}
                <div class="absolute left-1/2 top-0 -translate-x-1/2 -translate-y-1/2">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full border-4 border-white bg-[#ECFDF5] shadow-lg">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#22C55E] to-[#16A34A]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="#FFFFFF" stroke-width="1.8"/>
                                <path d="M8 12.5l2.3 2.3L16 9" stroke="#FFFFFF" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="px-12 pt-16 pb-10 max-[640px]:px-6 max-[640px]:pb-8">

                    {{-- Header teks di dalam card --}}
                    <div class="mb-6 flex flex-col items-center gap-2 text-center">
                        <h2 class="text-base font-normal text-[#166534]">
                            Verifikasi Berhasil
                        </h2>
                        <p class="max-w-xl text-sm font-normal leading-relaxed text-[#1E293B]">
                            Kode hash yang Anda masukkan cocok dengan data sertifikat yang terdaftar
                            dalam database sistem kami.
                        </p>
                    </div>

                    {{-- Garis pemisah --}}
                    <div class="mb-6 h-px w-full bg-[#E2E8F0]"></div>

                    {{-- PANEL DETAIL SERTIFIKAT (mirip box penyebab di halaman invalid) --}}
                    <div class="mb-8 rounded-2xl border border-[#BBF7D0] bg-gradient-to-br from-[#ECFDF5] via-[#F0FDF4] to-[#E0F2FE] px-6 py-6 max-[640px]:px-4">
                        <div class="mb-4 flex items-start gap-4">
                            {{-- Icon lingkaran hijau --}}
                            <!-- <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-[#22C55E] to-[#16A34A]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="10" stroke="#FFFFFF" stroke-width="2"/>
                                    <path d="M8 12.5l2.3 2.3L16 9" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div> -->

                            <div class="flex flex-col gap-1">
                                <p class="text-sm font-normal text-[#166534]">
                                    Detail Sertifikat
                                </p>
                                <p class="text-sm font-normal leading-relaxed text-[#1E293B]">
                                    Berikut informasi utama dari sertifikat yang berhasil diverifikasi.
                                </p>
                            </div>
                        </div>

                        {{-- List detail (dummy / dari controller bisa diisi dinamis) --}}
                        <div class="mt-3 grid gap-3 text-sm text-[#1E293B] max-[640px]:gap-2">
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#22C55E]"></span>
                                <p>
                                    <span class="font-semibold text-[#1C398E]">Nama Pemilik:</span>
                                    <span class="ml-1">
                                        {{ $certificate['nama'] ?? 'Mr. Ambatukam' }}
                                    </span>
                                </p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#22C55E]"></span>
                                <p>
                                    <span class="font-semibold text-[#1C398E]">Nama Sertifikat / Acara:</span>
                                    <span class="ml-1">
                                        {{ $certificate['judul'] ?? 'Penghargaan' }}
                                    </span>
                                </p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#22C55E]"></span>
                                <p>
                                    <span class="font-semibold text-[#1C398E]">Tanggal Diterbitkan:</span>
                                    <span class="ml-1">
                                        {{ $certificate['tanggal'] ?? '06 Juli 2025' }}
                                    </span>
                                </p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#22C55E]"></span>
                                <p>
                                    <span class="font-semibold text-[#1C398E]">Penerbit Sertifikat:</span>
                                    <span class="ml-1">
                                        {{ $certificate['penerbit'] ?? 'Barbershop Ngawi' }}
                                    </span>
                                </p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#22C55E]"></span>
                                <p>
                                    <span class="font-semibold text-[#1C398E]">Status:</span>
                                    <span class="ml-1 text-[#15803D]">
                                        Aktif &amp; Terverifikasi
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- LANGKAH SELANJUTNYA (layout mirip invalid) --}}
                    <div class="space-y-4">
                        <p class="text-sm font-normal text-[#1C398E]">
                            Langkah Selanjutnya:
                        </p>

                        {{-- Button 1: Unduh Sertifikat --}}
                        <button type="button"
                                class="group relative flex w-full items-center gap-3 rounded-lg border border-[#BBF7D0] bg-white px-6 py-4 text-left hover:bg-[#ECFDF5] transition">
                            <div class="flex h-11 w-11 items-center justify-center rounded-[14px] bg-[#DCFCE7]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 4v10" stroke="#16A34A" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M8.5 10.5L12 14l3.5-3.5" stroke="#16A34A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M6 18h12" stroke="#16A34A" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </div>

                            <div class="flex flex-col">
                                <p class="text-sm font-normal text-[#1C398E]">
                                    Unduh Sertifikat
                                </p>
                                <p class="text-sm font-normal text-[#15803D]">
                                    Simpan salinan sertifikat digital Anda.
                                </p>
                            </div>
                        </button>

                        {{-- Button 2: Bagikan Sertifikat --}}
                        <button type="button"
                                class="group relative flex w-full items-center gap-3 rounded-lg border border-[#BEDBFF] bg-white px-6 py-4 text-left hover:bg-[#EFF6FF] transition">
                            <div class="flex h-11 w-11 items-center justify-center rounded-[14px] bg-[#DBEAFE]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                    <path d="M15 6.5a2.5 2.5 0 11-4.9.7" stroke="#155DFC" stroke-width="1.7" stroke-linecap="round"/>
                                    <path d="M9 13.5a2.5 2.5 0 10-1.9 2.4" stroke="#155DFC" stroke-width="1.7" stroke-linecap="round"/>
                                    <path d="M15 17.5a2.5 2.5 0 10.5-4.9" stroke="#155DFC" stroke-width="1.7" stroke-linecap="round"/>
                                    <path d="M10.5 9L9 11" stroke="#155DFC" stroke-width="1.7" stroke-linecap="round"/>
                                    <path d="M10.5 15L14 16" stroke="#155DFC" stroke-width="1.7" stroke-linecap="round"/>
                                </svg>
                            </div>

                            <div class="flex flex-col">
                                <p class="text-sm font-normal text-[#1C398E]">
                                    Bagikan Sertifikat
                                </p>
                                <p class="text-sm font-normal text-[#155DFC]">
                                    Kirim tautan verifikasi kepada pihak lain.
                                </p>
                            </div>
                        </button>

                        {{-- Catatan kecil di bawah --}}
                        <!-- <div class="mt-2 flex items-center justify-center gap-2 text-xs text-[#64748B]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="#155DFC" stroke-width="1.5"/>
                                <path d="M12 8v1.5M12 11v5" stroke="#155DFC" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <span>Kode hash terdapat pada sertifikat digital Anda.</span>
                        </div> -->
                    </div>
                </div>
            </section>
        </div>
        
        {{-- TOMBOL KEMBALI KE VERIFIKASI --}}
        <div class="mt-8">
            <a href="{{ route('verifikasi') }}"
               class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-normal text-[white] font-semibold transition-colors duration-200">
                {{-- Icon panah kiri --}}
                <svg class="h-5 w-5 text-[white]" viewBox="0 0 16 16" fill="none">
                    <path d="M6.66667 3.33301L2 7.99967L6.66667 12.6663" stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14 8H2.66667" stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="font-semibold">Kembali ke Verifikasi</span>
            </a>
        </div>

    </section>

</x-layouts.app>
