{{-- resources/views/verifikasi/index.blade.php --}}
<x-layouts.app title="Verifikasi Sertifikat – SertiKu">


    {{-- KONTEN UTAMA --}}
    <section class="mx-auto flex max-w-6xl flex-col gap-16 px-4 pb-20 pt-16 lg:flex-row lg:px-0 lg:pt-20">

        {{-- KIRI: TEKS + FORM VERIFIKASI --}}
        <div class="relative w-full max-w-xl animate-fade-in-up">
            {{-- Badge "Verifikasi instan & aman" --}}
            <div class="inline-flex h-[34px] items-center gap-2 rounded-[8px]
                           border border-[rgba(255,255,255,0.2)]
                           bg-[rgba(255,255,255,0.1)] px-4 text-[12px] text-white">
                <span class="flex h-3 w-3 items-center justify-center">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_3_1795)">
                            <path
                                d="M1.99975 6.99999C1.90513 7.00031 1.81237 6.97378 1.73223 6.92348C1.65209 6.87318 1.58787 6.80117 1.54703 6.71582C1.50618 6.63047 1.4904 6.53528 1.5015 6.44132C1.5126 6.34735 1.55014 6.25847 1.60975 6.18499L6.55975 1.08499C6.59688 1.04213 6.64748 1.01317 6.70324 1.00286C6.759 0.992545 6.81661 1.0015 6.86662 1.02824C6.91662 1.05499 6.95604 1.09794 6.97842 1.15004C7.00079 1.20215 7.00479 1.26031 6.98975 1.31499L6.02975 4.32499C6.00144 4.40075 5.99194 4.48225 6.00205 4.56249C6.01216 4.64274 6.04158 4.71933 6.0878 4.7857C6.13401 4.85208 6.19564 4.90625 6.26739 4.94357C6.33914 4.98089 6.41887 5.00025 6.49975 4.99999H9.99975C10.0944 4.99967 10.1871 5.0262 10.2673 5.0765C10.3474 5.1268 10.4116 5.19881 10.4525 5.28416C10.4933 5.36951 10.5091 5.4647 10.498 5.55866C10.4869 5.65262 10.4494 5.74151 10.3898 5.81499L5.43975 10.915C5.40262 10.9578 5.35202 10.9868 5.29626 10.9971C5.2405 11.0074 5.18289 10.9985 5.13289 10.9717C5.08288 10.945 5.04346 10.902 5.02108 10.8499C4.99871 10.7978 4.99471 10.7397 5.00975 10.685L5.96975 7.67499C5.99806 7.59923 6.00757 7.51773 5.99746 7.43749C5.98735 7.35724 5.95792 7.28065 5.91171 7.21428C5.86549 7.1479 5.80386 7.09373 5.73211 7.05641C5.66036 7.01909 5.58063 6.99973 5.49975 6.99999H1.99975Z"
                                stroke="#FFDF20" stroke-linecap="round" stroke-linejoin="round" />
                        </g>
                        <defs>
                            <clipPath id="clip0_3_1795">
                                <rect width="12" height="12" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>

                </span>
                <span>Verifikasi Instan &amp; Aman</span>
            </div>

            {{-- Judul + paragraf --}}
            <div class="mt-6 space-y-4">
                <div class="space-y-2">
                    <h1 class="text-[40px] leading-[50px] font-semibold text-white md:text-[52px] md:leading-[64px]">
                        Verifikasi Sertifikat
                    </h1>
                    <h2 class="bg-[linear-gradient(90deg,#53EAFD_0%,#8EC5FF_100%)]
                                   bg-clip-text text-[40px] leading-[50px] font-semibold text-transparent
                                   md:text-[52px] md:leading-[64px] pb-4">
                        Digital Anda
                    </h2>
                </div>

                <p class="max-w-xl text-[15px] leading-[24px] text-[#BEDBFF] md:text-[18px] md:leading-[28px]">
                    Platform verifikasi sertifikat berbasis QR Code yang aman dan terpercaya.
                    Pastikan keaslian sertifikat Anda dalam hitungan detik.
                </p>
            </div>

            {{-- CARD FORM VERIFIKASI --}}
            <div class="mt-10 rounded-[16px] border border-[rgba(255,255,255,0.14)]
                           bg-[rgba(15,23,42,0.9)]
                           px-6 pb-3 pt-6 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.25)]
                           backdrop-blur-xl sm:px-7 sm:pt-7 hover-lift animate-fade-in-up stagger-2">


                {{-- FORM --}}
                <form id="verifyForm" class="space-y-5">
                    @csrf

                    {{-- Label --}}
                    <label class="block space-y-2">
                        <div class="relative flex items-center text-[16px] text-white">
                            {{-- icon gembok --}}
                            <span class="mr-2 inline-flex h-4 w-4 items-center justify-center">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M4.66667 7.33333V5.99999C4.66667 4.15904 5.99204 2.66666 7.66667 2.66666C9.3413 2.66666 10.6667 4.15904 10.6667 5.99999V7.33333"
                                        stroke="white" stroke-width="1.33333" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <rect x="3.33333" y="7.33334" width="8.66667" height="6" rx="2" stroke="white"
                                        stroke-width="1.33333" />
                                </svg>
                            </span>
                            <span>Masukkan Kode Hash Sertifikat</span>
                        </div>

                        {{-- Input --}}
                        <div class="mt-2 flex items-center rounded-[8px]
                                       border border-[rgba(255,255,255,0.2)]
                                       bg-[rgba(255,255,255,0.10)]
                                       px-3 py-2">
                            <input type="text" name="hash" id="hashInput" value="{{ old('hash') }}"
                                class="w-full bg-transparent text-sm text-white placeholder:text-[rgba(255,255,255,0.5)] focus:outline-none"
                                placeholder="Contoh: ABC123XYZ" required>
                        </div>
                        @error('hash')
                            <p class="mt-1 text-xs text-[#FCA5A5]">
                                {{ $message }}
                            </p>
                        @enderror
                    </label>

                    {{-- Tombol Verifikasi & Scan QR --}}
                    <div class="relative mt-2 flex flex-col gap-3 sm:flex-row sm:items-center">
                        {{-- Tombol VERIFIKASI --}}
                        <button type="submit" id="verifyBtn" class="inline-flex flex-1 items-center justify-center gap-2 rounded-[8px]
                                           bg-[linear-gradient(180deg,#1E3A8F_0%,#3B82F6_100%)]
                                           px-4 py-3 text-sm font-medium text-white
                                           shadow-[0_10px_15px_-3px_rgba(43,127,255,0.5),0_4px_6px_-4px_rgba(43,127,255,0.5)]
                                           hover:brightness-110 transition">
                            <span class="inline-flex h-4 w-4 items-center justify-center">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.328 8.6633C13.328 11.9953 10.9956 13.6614 8.22336 14.6276C8.07819 14.6768 7.9205 14.6745 7.77686 14.621C4.99795 13.6614 2.66553 11.9953 2.66553 8.6633V3.99846C2.66553 3.82172 2.73574 3.65221 2.86071 3.52724C2.98569 3.40226 3.15519 3.33205 3.33193 3.33205C4.66475 3.33205 6.33076 2.53237 7.49031 1.51943C7.63149 1.39881 7.81109 1.33253 7.99678 1.33253C8.18247 1.33253 8.36206 1.39881 8.50325 1.51943C9.66946 2.53903 11.3288 3.33205 12.6616 3.33205C12.8384 3.33205 13.0079 3.40226 13.1328 3.52724C13.2578 3.65221 13.328 3.82172 13.328 3.99846V8.6633Z"
                                        stroke="white" stroke-width="1.33281" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                            </span>
                            <span>Verifikasi</span>
                            {{-- icon panah kecil di kanan --}}
                            <span class="inline-flex h-4 w-4 items-center justify-center">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.33398 8H12.6673" stroke="white" stroke-width="1.333"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8.0013 3.33331L12.668 7.99998L8.0013 12.6666" stroke="white"
                                        stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </button>

                        {{-- Tombol SCAN QR --}}
                        <button type="button" onclick="openQRScanner()"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-[8px]
                                      border border-[rgba(255,255,255,0.2)]
                                      bg-[rgba(255,255,255,0.05)]
                                      px-4 py-3 text-sm font-medium text-white hover:bg-[rgba(255,255,255,0.08)] transition">
                            <span class="inline-flex h-4 w-4 items-center justify-center">
                                {{-- icon QR --}}
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="2.66602" y="2.66669" width="4" height="4" rx="1.2" stroke="white"
                                        stroke-width="1.333" />
                                    <rect x="9.33398" y="2.66669" width="4" height="4" rx="1.2" stroke="white"
                                        stroke-width="1.333" />
                                    <rect x="2.66602" y="9.33331" width="4" height="4" rx="1.2" stroke="white"
                                        stroke-width="1.333" />
                                    <path d="M9.33398 9.33331H13.334" stroke="white" stroke-width="1.333"
                                        stroke-linecap="round" />
                                    <path d="M9.33398 12H10.6673" stroke="white" stroke-width="1.333"
                                        stroke-linecap="round" />
                                    <path d="M12 10.6667V13.3333" stroke="white" stroke-width="1.333"
                                        stroke-linecap="round" />
                                </svg>
                            </span>
                            <span>Scan QR</span>
                        </button>
                    </div>

                    {{-- Helper text --}}
                    <div class="relative mt-3 flex items-center justify-center text-center">
                        <span class="mr-2 inline-flex h-4 w-4 items-center justify-center">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M14.5284 6.66406C14.8328 8.15767 14.6159 9.71048 13.9139 11.0635C13.2119 12.4166 12.0673 13.4881 10.6709 14.0994C9.27456 14.7106 7.71084 14.8247 6.24053 14.4226C4.77022 14.0205 3.4822 13.1265 2.59127 11.8896C1.70034 10.6528 1.26036 9.14791 1.34468 7.62594C1.42901 6.10397 2.03256 4.6569 3.05467 3.52606C4.07679 2.39523 5.45569 1.64897 6.96142 1.41174C8.46716 1.17452 10.0087 1.46066 11.329 2.22246"
                                    stroke="#BEDBFF" stroke-width="1.33281" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M5.99756 7.33046L7.99678 9.32968L14.6608 2.66562" stroke="#BEDBFF"
                                    stroke-width="1.33281" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </span>
                        <p class="text-[13px] leading-[20px] text-[#BEDBFF]">
                            Kode hash terdapat pada sertifikat digital Anda
                        </p>
                    </div>

                    {{-- Blockchain Verification Link --}}
                    @if(config('blockchain.enabled'))
                        <div class="mt-4 pt-4 border-t border-white/10">
                            <a href="{{ route('blockchain.verify') }}" class="flex items-center justify-center gap-2 w-full py-3 rounded-[8px]
                                               border border-purple-500/30 bg-purple-500/10
                                               text-purple-300 text-sm font-medium
                                               hover:bg-purple-500/20 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                                Cek Blockchain (On-Chain Verification)
                            </a>
                        </div>
                    @endif
                </form>
            </div>


        </div>

        {{-- KANAN: KARTU SERTIFIKAT DIGITAL --}}
        <div class="relative flex flex-1 justify-center lg:justify-end animate-slide-in-right">
            {{-- glow belakang --}}
            <div class="pointer-events-none absolute top-16 h-[320px] w-[320px] rounded-full
                           bg-[linear-gradient(135deg,rgba(0,211,242,0.3)_0%,rgba(43,127,255,0.3)_100%)]
                           blur-[64px]">
            </div>

            <div class="relative w-full max-w-sm space-y-4">
                {{-- Card utama sertifikat --}}
                <div class="rounded-[24px] border border-[rgba(255,255,255,0.2)]
                               bg-[rgba(255,255,255,0.1)]
                               px-7 pb-6 pt-8 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.25)]
                               backdrop-blur-xl">
                    {{-- Icon besar --}}
                    <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center">
                        <div class="relative h-24 w-24">
                            <div class="absolute inset-0 rounded-[16px]
                                           bg-[linear-gradient(135deg,#2B7FFF_0%,#00B8DB_100%)]
                                           opacity-50 blur-[24px]">
                            </div>
                            <div class="absolute inset-0 rounded-[16px]
                                           bg-[linear-gradient(180deg,#1E3A8F_0%,#3B82F6_100%)]
                                           px-4 pt-4">
                                <div class="relative h-full w-full">
                                    <svg width="64" height="64" viewBox="0 0 64 64" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M41.2679 34.37L45.3075 57.1038C45.3528 57.3715 45.3152 57.6466 45.1999 57.8924C45.0845 58.1382 44.8969 58.3429 44.6621 58.4791C44.4272 58.6154 44.1564 58.6767 43.8858 58.6549C43.6152 58.633 43.3577 58.5291 43.1477 58.357L33.602 51.1924C33.1412 50.8481 32.5814 50.6621 32.0061 50.6621C31.4309 50.6621 30.8711 50.8481 30.4103 51.1924L20.8486 58.3543C20.6388 58.5261 20.3816 58.6299 20.1113 58.6518C19.841 58.6736 19.5705 58.6125 19.3358 58.4766C19.1012 58.3408 18.9135 58.1366 18.7979 57.8913C18.6823 57.646 18.6442 57.3713 18.6888 57.1038L22.7257 34.37"
                                            stroke="white" stroke-width="5.33281" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M31.997 37.3297C40.8327 37.3297 47.9954 30.1669 47.9954 21.3312C47.9954 12.4956 40.8327 5.33281 31.997 5.33281C23.1613 5.33281 15.9985 12.4956 15.9985 21.3312C15.9985 30.1669 23.1613 37.3297 31.997 37.3297Z"
                                            stroke="white" stroke-width="5.33281" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Judul kartu --}}
                    <div class="space-y-1 text-center">
                        <p class="text-[20px] leading-[28px] text-white">
                            Sertifikat Digital
                        </p>
                        <p class="text-[14px] leading-[20px] text-[#BEDBFF]">
                            Terverifikasi &amp; Terenkripsi
                        </p>
                    </div>

                    {{-- Status Verifikasi --}}
                    <div class="mt-5 rounded-[14px] border border-[rgba(255,255,255,0.2)]
                                   bg-[rgba(255,255,255,0.1)] px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <span class="inline-flex h-5 w-5 items-center justify-center">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10" cy="10" r="7" stroke="#05DF72" stroke-width="1.6667" />
                                    <path d="M7.5 10.0001L9.16667 11.6668L12.5 8.33342" stroke="#05DF72"
                                        stroke-width="1.6667" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <span class="text-[14px] text-[#05DF72]">Verified</span>
                        </div>
                    </div>
                </div>

                {{-- Stats 500+ / 50K+ / 99.9% --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="rounded-[14px] border border-[rgba(255,255,255,0.2)]
                                   bg-[rgba(255,255,255,0.05)] px-4 py-4 text-center">
                        <div class="text-[24px] leading-[32px] text-white">67+</div>
                        <div class="mt-1 text-[12px] leading-[16px] text-[#BEDBFF]">Lembaga</div>
                    </div>
                    <div class="rounded-[14px] border border-[rgba(255,255,255,0.2)]
                                   bg-[rgba(255,255,255,0.05)] px-4 py-4 text-center">
                        <div class="text-[24px] leading-[32px] text-white">67+</div>
                        <div class="mt-1 text-[12px] leading-[16px] text-[#BEDBFF]">Sertifikat</div>
                    </div>
                    <div class="rounded-[14px] border border-[rgba(255,255,255,0.2)]
                                   bg-[rgba(255,255,255,0.05)] px-4 py-4 text-center">
                        <div class="text-[24px] leading-[32px] text-white">96%</div>
                        <div class="mt-1 text-[12px] leading-[16px] text-[#BEDBFF]">Akurasi</div>
                    </div>
                </div>

                {{-- Fitur kecil di bawah card: Terenkripsi / Real-time / 100% Aman --}}
                <div class="mt-20 flex flex-wrap gap-6 justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-[#05DF72]"></span>
                        <span class="text-[14px] text-[rgba(255,255,255,0.9)]">Terenkripsi</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-[#05DF72]"></span>
                        <span class="text-[14px] text-[rgba(255,255,255,0.9)]">Verifikasi Real-time</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-[#05DF72]"></span>
                        <span class="text-[14px] text-[rgba(255,255,255,0.9)]">100% Aman</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal QR Scanner --}}
    <div id="qrScannerModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
        <div class="relative w-full max-w-md mx-4">
            {{-- Card Scanner --}}
            <div class="rounded-[20px] border border-[rgba(255,255,255,0.2)] bg-[#0a1628] p-6 shadow-2xl">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg width="20" height="20" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="2.66602" y="2.66669" width="4" height="4" rx="1.2" stroke="#3B82F6"
                                stroke-width="1.333" />
                            <rect x="9.33398" y="2.66669" width="4" height="4" rx="1.2" stroke="#3B82F6"
                                stroke-width="1.333" />
                            <rect x="2.66602" y="9.33331" width="4" height="4" rx="1.2" stroke="#3B82F6"
                                stroke-width="1.333" />
                            <path d="M9.33398 9.33331H13.334" stroke="#3B82F6" stroke-width="1.333"
                                stroke-linecap="round" />
                            <path d="M9.33398 12H10.6673" stroke="#3B82F6" stroke-width="1.333"
                                stroke-linecap="round" />
                            <path d="M12 10.6667V13.3333" stroke="#3B82F6" stroke-width="1.333"
                                stroke-linecap="round" />
                        </svg>
                        Scan QR Code
                    </h3>
                    <button onclick="closeQRScanner()" class="text-gray-400 hover:text-white transition">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Scanner Area --}}
                <div id="qr-reader" class="rounded-lg overflow-hidden bg-black"></div>

                {{-- Status --}}
                <div id="scanStatus" class="mt-4 text-center text-sm text-[#BEDBFF]">
                    Arahkan kamera ke QR Code sertifikat
                </div>

                {{-- Tombol Switch Camera --}}
                <div class="mt-4 flex gap-3">
                    <button onclick="switchCamera()"
                        class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-[rgba(255,255,255,0.2)] bg-[rgba(255,255,255,0.05)] px-4 py-2 text-sm text-white hover:bg-[rgba(255,255,255,0.1)] transition">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Ganti Kamera
                    </button>
                    <button onclick="closeQRScanner()"
                        class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-red-500/20 border border-red-500/30 px-4 py-2 text-sm text-red-400 hover:bg-red-500/30 transition">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- HTML5 QR Code Scanner Library --}}
        <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
        <script>
            let html5QrCode = null;
            let currentCamera = 'environment'; // 'environment' = back camera, 'user' = front camera

            function openQRScanner() {
                const modal = document.getElementById('qrScannerModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                startScanner();
            }

            function closeQRScanner() {
                const modal = document.getElementById('qrScannerModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                stopScanner();
            }

            function startScanner() {
                html5QrCode = new Html5Qrcode("qr-reader");

                // Optimized config for laptop webcams
                const config = {
                    fps: 15, // Higher FPS for faster detection
                    qrbox: function (viewfinderWidth, viewfinderHeight) {
                        // Dynamic qrbox - 70% of the smaller dimension for better detection
                        let minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                        let qrboxSize = Math.floor(minEdge * 0.7);
                        return { width: qrboxSize, height: qrboxSize };
                    },
                    aspectRatio: 1.333, // 4:3 ratio works better for most laptop webcams
                    formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE],
                    experimentalFeatures: {
                        useBarCodeDetectorIfSupported: true // Use native detector if available
                    }
                };

                // Update status
                document.getElementById('scanStatus').innerHTML =
                    '<span class="text-yellow-400">Mengaktifkan kamera...</span>';

                // First try to get available cameras
                Html5Qrcode.getCameras().then(cameras => {
                    if (cameras && cameras.length > 0) {
                        console.log("Available cameras:", cameras);

                        // Use first available camera (or specified one)
                        let cameraId = cameras[0].id;

                        // Try to find back camera on mobile, or just use first camera on laptop
                        if (currentCamera === 'environment') {
                            const backCamera = cameras.find(c =>
                                c.label.toLowerCase().includes('back') ||
                                c.label.toLowerCase().includes('rear') ||
                                c.label.toLowerCase().includes('environment')
                            );
                            if (backCamera) cameraId = backCamera.id;
                        } else {
                            const frontCamera = cameras.find(c =>
                                c.label.toLowerCase().includes('front') ||
                                c.label.toLowerCase().includes('user') ||
                                c.label.toLowerCase().includes('facetime')
                            );
                            if (frontCamera) cameraId = frontCamera.id;
                        }

                        console.log("Using camera:", cameraId);

                        // Start with camera ID instead of facingMode
                        html5QrCode.start(
                            cameraId,
                            config,
                            onScanSuccess,
                            onScanFailure
                        ).then(() => {
                            document.getElementById('scanStatus').innerHTML =
                                'Arahkan kamera ke QR Code sertifikat';
                        }).catch((err) => {
                            console.error("Camera start error:", err);
                            document.getElementById('scanStatus').innerHTML =
                                '<span class="text-red-400">Gagal mengakses kamera: ' + err + '</span>';
                        });
                    } else {
                        document.getElementById('scanStatus').innerHTML =
                            '<span class="text-red-400">Tidak ada kamera yang terdeteksi.</span>';
                    }
                }).catch(err => {
                    console.error("Camera enumeration error:", err);
                    // Fallback: try with facingMode
                    html5QrCode.start(
                        { facingMode: "user" },
                        config,
                        onScanSuccess,
                        onScanFailure
                    ).then(() => {
                        document.getElementById('scanStatus').innerHTML =
                            'Arahkan kamera ke QR Code sertifikat';
                    }).catch((err2) => {
                        console.error("Fallback camera error:", err2);
                        document.getElementById('scanStatus').innerHTML =
                            '<span class="text-red-400">Tidak dapat mengakses kamera. Pastikan izin kamera diberikan.</span>';
                    });
                });
            }

            function stopScanner() {
                if (html5QrCode && html5QrCode.isScanning) {
                    html5QrCode.stop().catch(err => console.log("Stop error:", err));
                }
            }

            function switchCamera() {
                stopScanner();
                currentCamera = currentCamera === 'environment' ? 'user' : 'environment';
                setTimeout(() => startScanner(), 500);
            }

            function onScanSuccess(decodedText, decodedResult) {
                // Stop scanner
                stopScanner();

                // Update status
                document.getElementById('scanStatus').innerHTML =
                    '<span class="text-green-400">QR Code terdeteksi! Mengalihkan...</span>';

                // Cek apakah hasil scan adalah URL atau kode hash
                let hashCode = decodedText;

                // Jika hasil scan adalah URL, ekstrak hash dari URL
                if (decodedText.includes('/verifikasi/')) {
                    // Handle format baru: .../verifikasi/HASH
                    const parts = decodedText.split('/verifikasi/');
                    if (parts.length > 1) {
                        // Ambil hash, pastikan bersih dari query/slash
                        hashCode = parts[1].split('/')[0].split('?')[0];
                    }
                } else if (decodedText.includes('hash=')) {
                    // Handle format lama: ...?hash=HASH
                    const urlParts = decodedText.split('?');
                    if (urlParts.length > 1) {
                        const urlParams = new URLSearchParams(urlParts[1]);
                        hashCode = urlParams.get('hash');
                    }
                }
                // Tutup modal
                closeQRScanner();

                if (!hashCode || hashCode === decodedText && decodedText.length > 50) {
                    // Fallback check: if hash implies full URL but wasn't parsed
                    alert("Gagal membaca format QR Code: " + decodedText);
                    return;
                }

                // Force redirect
                const targetUrl = '{{ url("/verifikasi") }}/' + hashCode;
                window.location.href = targetUrl;
            }

            function onScanFailure(error) {
                // Ignore scan failures (normal saat belum ada QR code)
            }

            // Close modal when clicking outside
            document.getElementById('qrScannerModal').addEventListener('click', function (e) {
                if (e.target === this) {
                    closeQRScanner();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeQRScanner();
                }
            });

            // Form submission handler - langsung redirect ke halaman verifikasi
            document.getElementById('verifyForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const hash = document.getElementById('hashInput').value.trim();
                if (hash) {
                    // Langsung redirect ke halaman verifikasi
                    window.location.href = `{{ url('/verifikasi') }}/${hash}`;
                } else {
                    alert('Silakan masukkan kode hash sertifikat');
                }
            });
        </script>
    @endpush

</x-layouts.app>