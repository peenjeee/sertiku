{{-- resources/views/verifikasi/invalid.blade.php --}}
<x-layouts.app title="Hasil Verifikasi – SertiKu">

    {{-- KONTEN UTAMA --}}
    <section class="mx-auto max-w-5xl px-4 pt-24 pb-16 flex flex-col items-center">

        {{-- KARTU UTAMA --}}
        <div
            class="w-full mt-20 max-w-3xl rounded-[24px] border border-[rgba(255,255,255,0.14)] bg-[rgba(15,23,42,0.9)] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] overflow-hidden">

            {{-- Strip gradient atas --}}
            <div class="h-3 w-full bg-gradient-to-r from-[#FB2C36] via-[#FF6900] to-[#F0B100]"></div>

            <div class="px-6 sm:px-10 pt-10 pb-10">

                {{-- Bagian icon & judul tengah --}}
                <div class="relative mx-auto flex flex-col items-center text-center max-w-xl mb-8">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-[#FB2C36]/20 mb-5">
                        <div class="relative h-10 w-10">
                            <span class="absolute inset-[4px] rounded-full border-[3px] border-[#FB2C36]"></span>
                            <span
                                class="absolute left-1/2 top-1/2 h-4 w-[2.5px] -translate-x-1/2 -translate-y-1/2 rotate-45 rounded-full bg-[#FB2C36]"></span>
                            <span
                                class="absolute left-1/2 top-1/2 h-4 w-[2.5px] -translate-x-1/2 -translate-y-1/2 -rotate-45 rounded-full bg-[#FB2C36]"></span>
                        </div>
                    </div>

                    <h2 class="text-base font-normal text-[#FB2C36]">
                        Verifikasi Gagal
                    </h2>
                    <p class="mt-3 text-sm md:text-base font-normal leading-relaxed text-[rgba(190,219,255,0.7)]">
                        Kode hash yang Anda masukkan tidak terdaftar dalam database sistem kami.
                    </p>
                </div>

                {{-- Garis pemisah --}}
                <div class="my-6 h-px w-full bg-[rgba(255,255,255,0.1)]"></div>

                {{-- KEMUNGKINAN PENYEBAB --}}
                <div
                    class="rounded-[16px] border border-[#FB2C36]/30 bg-[rgba(15,23,42,0.5)] px-6 py-6 sm:px-7 sm:py-7 mb-8">
                    <div class="flex gap-4">
                        {{-- Icon lingkar merah --}}
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-[#FB2C36]">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M21.7299 18L13.7299 3.99998C13.5555 3.69218 13.3025 3.43617 12.9969 3.25805C12.6912 3.07993 12.3437 2.98608 11.9899 2.98608C11.6361 2.98608 11.2887 3.07993 10.983 3.25805C10.6773 3.43617 10.4244 3.69218 10.2499 3.99998L2.24993 18C2.07361 18.3053 1.98116 18.6519 1.98194 19.0045C1.98272 19.3571 2.07671 19.7032 2.25438 20.0078C2.43204 20.3124 2.68708 20.5646 2.99362 20.7388C3.30017 20.9131 3.64734 21.0032 3.99993 21H19.9999C20.3508 20.9996 20.6955 20.9069 20.9992 20.7313C21.303 20.5556 21.5551 20.3031 21.7304 19.9991C21.9057 19.6951 21.998 19.3504 21.9979 18.9995C21.9978 18.6486 21.9054 18.3039 21.7299 18Z"
                                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 9V13" stroke="white" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M12 17H12.01" stroke="white" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                        </div>

                        <div class="flex-1 space-y-3">
                            <p class="text-base font-normal text-[#FB2C36]">
                                Kemungkinan Penyebab
                            </p>

                            <ul class="space-y-2 text-sm md:text-base">
                                <li class="relative pl-4 text-[rgba(190,219,255,0.7)]">
                                    <span
                                        class="absolute left-0 top-[0.7em] h-[6px] w-[6px] -translate-y-1/2 rounded-full bg-[#FB2C36]"></span>
                                    Kode hash salah atau tidak valid
                                </li>
                                <li class="relative pl-4 text-[rgba(190,219,255,0.7)]">
                                    <span
                                        class="absolute left-0 top-[0.7em] h-[6px] w-[6px] -translate-y-1/2 rounded-full bg-[#FB2C36]"></span>
                                    Sertifikat belum diterbitkan atau sudah dihapus dari sistem
                                </li>
                                <li class="relative pl-4 text-[rgba(190,219,255,0.7)]">
                                    <span
                                        class="absolute left-0 top-[0.7em] h-[6px] w-[6px] -translate-y-1/2 rounded-full bg-[#FB2C36]"></span>
                                    Kemungkinan sertifikat palsu atau dipalsukan
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- LANGKAH SELANJUTNYA --}}
                <div class="space-y-4">
                    <p class="text-base font-normal text-white">
                        Langkah Selanjutnya:
                    </p>

                    {{-- Tombol 1: Coba Verifikasi Lagi --}}
                    <a href="{{ route('verifikasi') }}"
                        class="block rounded-[8px] border border-[rgba(255,255,255,0.14)] bg-[rgba(15,23,42,0.5)] px-4 py-4 hover:bg-[rgba(59,130,246,0.1)] transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-[14px] bg-[#3B82F6]/20">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.99375 15.8234L4.16406 9.99375L9.99375 4.16406" stroke="#3B82F6"
                                        stroke-width="1.66562" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M15.8234 9.99365H4.16406" stroke="#3B82F6" stroke-width="1.66562"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                            </div>

                            <div class="flex-1">
                                <p class="text-sm font-normal text-white">
                                    Coba Verifikasi Lagi
                                </p>
                                <p class="text-sm font-normal text-[#3B82F6]">
                                    Periksa kembali kode hash Anda
                                </p>
                            </div>
                        </div>
                    </a>

                    {{-- Tombol 2: Laporkan Sertifikat Palsu --}}
                    <a href="#"
                        class="block rounded-[8px] border border-[#FB2C36]/30 bg-[rgba(15,23,42,0.5)] px-4 py-4 hover:bg-[rgba(251,44,54,0.1)] transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-[14px] bg-[#FB2C36]/20">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M18.0968 14.9906L11.4343 3.3312C11.289 3.07487 11.0783 2.86165 10.8238 2.71331C10.5692 2.56497 10.2798 2.48682 9.98517 2.48682C9.69054 2.48682 9.40117 2.56497 9.1466 2.71331C8.89202 2.86165 8.68135 3.07487 8.53608 3.3312L1.87358 14.9906C1.72674 15.2449 1.64974 15.5335 1.65039 15.8271C1.65104 16.1208 1.72932 16.4091 1.87728 16.6627C2.02525 16.9164 2.23764 17.1264 2.49294 17.2715C2.74824 17.4166 3.03736 17.4917 3.331 17.489H16.656C16.9482 17.4887 17.2352 17.4115 17.4882 17.2652C17.7412 17.1189 17.9512 16.9086 18.0972 16.6555C18.2432 16.4023 18.32 16.1152 18.3199 15.823C18.3198 15.5307 18.2429 15.2437 18.0968 14.9906Z"
                                        stroke="#FB2C36" stroke-width="1.66562" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M9.99365 7.49536V10.8266" stroke="#FB2C36" stroke-width="1.66562"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.99365 14.1577H10.002" stroke="#FB2C36" stroke-width="1.66562"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                            </div>

                            <div class="flex-1">
                                <p class="text-sm font-normal text-white">
                                    Laporkan Sertifikat Palsu
                                </p>
                                <p class="text-sm font-normal text-[#FB2C36]">
                                    Bantu kami mencegah pemalsuan
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

        </div>

        {{-- TOMBOL KEMBALI KE VERIFIKASI --}}
        <div class="mt-8">
            <a href="{{ route('verifikasi') }}"
                class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-normal text-[white] font-semibold transition-colors duration-200">
                {{-- Icon panah kiri --}}
                <svg class="h-5 w-5 text-[white]" viewBox="0 0 16 16" fill="none">
                    <path d="M6.66667 3.33301L2 7.99967L6.66667 12.6663" stroke="currentColor" stroke-width="1.333"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M14 8H2.66667" stroke="currentColor" stroke-width="1.333" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <span class="font-semibold">Kembali ke Verifikasi</span>
            </a>
        </div>

    </section>

</x-layouts.app>