{{-- resources/views/verifikasi/index.blade.php --}}
<x-layouts.app title="Verifikasi Sertifikat – SertiKu">
    <main
        class="min-h-screen relative overflow-hidden bg-gradient-to-b from-[#0F172A] via-[#1E293B] to-[#0F172A]">

        {{-- GLOW BULAT KIRI ATAS --}}
        <div
            class="pointer-events-none absolute -left-10 top-24 h-[384px] w-[384px] rounded-full
                   bg-[linear-gradient(90deg,rgba(43,127,255,0.3)_0%,rgba(0,184,219,0.3)_100%)]
                   blur-[64px]">
        </div>

        {{-- GLOW BULAT KANAN BAWAH --}}
        <div
            class="pointer-events-none absolute right-10 bottom-10 h-[384px] w-[384px] rounded-full
                   bg-[linear-gradient(90deg,rgba(173,70,255,0.2)_0%,rgba(246,51,154,0.2)_100%)]
                   blur-[64px]">
        </div>
    

        {{-- KONTEN UTAMA --}}
        <section class="mx-auto flex max-w-6xl flex-col gap-16 px-4 pb-20 pt-16 lg:flex-row lg:px-0 lg:pt-20">

            {{-- KIRI: TEKS + FORM VERIFIKASI --}}
            <div class="relative w-full max-w-xl">
                {{-- Badge "Verifikasi instan & aman" --}}
                <div
                    class="inline-flex h-[34px] items-center gap-2 rounded-[8px]
                           border border-[rgba(255,255,255,0.2)]
                           bg-[rgba(255,255,255,0.1)] px-4 text-[12px] text-white">
                    <span class="flex h-3 w-3 items-center justify-center">
                        <span class="h-3 w-3 rounded-full border border-[#FFDF20]"></span>
                    </span>
                    <span>Verifikasi Instan &amp; Aman</span>
                </div>

                {{-- Judul + paragraf --}}
                <div class="mt-6 space-y-4">
                    <div class="space-y-2">
                        <h1
                            class="text-[40px] leading-[50px] font-normal text-white md:text-[52px] md:leading-[64px]">
                            Verifikasi Sertifikat
                        </h1>
                        <h2
                            class="bg-[linear-gradient(90deg,#53EAFD_0%,#8EC5FF_100%)]
                                   bg-clip-text text-[40px] leading-[50px] font-normal text-transparent
                                   md:text-[52px] md:leading-[64px]">
                            Digital Anda
                        </h2>
                    </div>

                    <p class="max-w-xl text-[15px] leading-[24px] text-[#BEDBFF] md:text-[18px] md:leading-[28px]">
                        Platform verifikasi sertifikat berbasis QR Code yang aman dan terpercaya.
                        Pastikan keaslian sertifikat Anda dalam hitungan detik.
                    </p>
                </div>

                {{-- CARD FORM VERIFIKASI --}}
                <div
                    class="mt-10 rounded-[16px] border border-[rgba(255,255,255,0.2)]
                           bg-[rgba(255,255,255,0.10)]
                           px-6 pb-3 pt-6 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.25)]
                           backdrop-blur-xl sm:px-7 sm:pt-7">


                    {{-- FORM --}}
                    <form action="{{ route('verifikasi.check') }}" method="POST" class="space-y-5">
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
                                            stroke="white" stroke-width="1.33333"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <rect x="3.33333" y="7.33334" width="8.66667" height="6"
                                              rx="2" stroke="white" stroke-width="1.33333" />
                                    </svg>
                                </span>
                                <span>Masukkan Kode Hash Sertifikat</span>
                            </div>

                            {{-- Input --}}
                            <div
                                class="mt-2 flex items-center rounded-[8px]
                                       border border-[rgba(255,255,255,0.2)]
                                       bg-[rgba(255,255,255,0.10)]
                                       px-3 py-2">
                                <input
                                    type="text"
                                    name="hash"
                                    value="{{ old('hash') }}"
                                    class="w-full bg-transparent text-sm text-white placeholder:text-[rgba(255,255,255,0.5)] focus:outline-none"
                                    placeholder="Contoh: ABC123XYZ"
                                    required>
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
                            <button type="submit"
                                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-[8px]
                                           bg-[linear-gradient(180deg,#1E3A8F_0%,#3B82F6_100%)]
                                           px-4 py-3 text-sm font-medium text-white
                                           shadow-[0_10px_15px_-3px_rgba(43,127,255,0.5),0_4px_6px_-4px_rgba(43,127,255,0.5)]
                                           hover:brightness-110 transition">
                                <span class="inline-flex h-4 w-4 items-center justify-center">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2.66602 8H13.3327" stroke="white" stroke-width="1.333"
                                              stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M8 3.33331L13.3333 7.99998L8 12.6666" stroke="white"
                                              stroke-width="1.333" stroke-linecap="round"
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
                                        <path d="M8.0013 3.33331L12.668 7.99998L8.0013 12.6666"
                                              stroke="white" stroke-width="1.333" stroke-linecap="round"
                                              stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </button>

                            {{-- Tombol SCAN QR (bisa diarahkan ke fitur lain nanti) --}}
                            <a href="#"
                               class="inline-flex flex-1 items-center justify-center gap-2 rounded-[8px]
                                      border border-[rgba(255,255,255,0.2)]
                                      bg-[rgba(255,255,255,0.05)]
                                      px-4 py-3 text-sm font-medium text-white hover:bg-[rgba(255,255,255,0.08)] transition">
                                <span class="inline-flex h-4 w-4 items-center justify-center">
                                    {{-- icon QR --}}
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <rect x="2.66602" y="2.66669" width="4" height="4"
                                              rx="1.2" stroke="white" stroke-width="1.333" />
                                        <rect x="9.33398" y="2.66669" width="4" height="4"
                                              rx="1.2" stroke="white" stroke-width="1.333" />
                                        <rect x="2.66602" y="9.33331" width="4" height="4"
                                              rx="1.2" stroke="white" stroke-width="1.333" />
                                        <path d="M9.33398 9.33331H13.334" stroke="white" stroke-width="1.333"
                                              stroke-linecap="round" />
                                        <path d="M9.33398 12H10.6673" stroke="white" stroke-width="1.333"
                                              stroke-linecap="round" />
                                        <path d="M12 10.6667V13.3333" stroke="white" stroke-width="1.333"
                                              stroke-linecap="round" />
                                    </svg>
                                </span>
                                <span>Scan QR</span>
                            </a>
                        </div>

                        {{-- Helper text --}}
                        <div class="relative mt-3 flex items-center justify-center text-center">
                            <span class="mr-2 inline-flex h-4 w-4 items-center justify-center">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="8" cy="8" r="6"
                                            stroke="#BEDBFF" stroke-width="1.333" />
                                    <path d="M8 5.33331V8.66665" stroke="#BEDBFF" stroke-width="1.333"
                                          stroke-linecap="round" />
                                    <circle cx="8" cy="10.6667" r=".6667" fill="#BEDBFF" />
                                </svg>
                            </span>
                            <p class="text-[13px] leading-[20px] text-[#BEDBFF]">
                                Kode hash terdapat pada sertifikat digital Anda
                            </p>
                        </div>
                    </form>
                </div>

                {{-- Fitur kecil di bawah card: Terenkripsi / Real-time / 100% Aman --}}
                <div class="mt-6 flex flex-wrap gap-6 text-sm">
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

            {{-- KANAN: KARTU SERTIFIKAT DIGITAL --}}
            <div class="relative flex flex-1 justify-center lg:justify-end">
                {{-- glow belakang --}}
                <div
                    class="pointer-events-none absolute top-16 h-[320px] w-[320px] rounded-full
                           bg-[linear-gradient(135deg,rgba(0,211,242,0.3)_0%,rgba(43,127,255,0.3)_100%)]
                           blur-[64px]">
                </div>

                <div class="relative w-full max-w-sm space-y-4">
                    {{-- Card utama sertifikat --}}
                    <div
                        class="rounded-[24px] border border-[rgba(255,255,255,0.2)]
                               bg-[rgba(255,255,255,0.1)]
                               px-7 pb-6 pt-8 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.25)]
                               backdrop-blur-xl">
                        {{-- Icon besar --}}
                        <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center">
                            <div class="relative h-24 w-24">
                                <div
                                    class="absolute inset-0 rounded-[16px]
                                           bg-[linear-gradient(135deg,#2B7FFF_0%,#00B8DB_100%)]
                                           opacity-50 blur-[24px]">
                                </div>
                                <div
                                    class="absolute inset-0 rounded-[16px]
                                           bg-[linear-gradient(180deg,#1E3A8F_0%,#3B82F6_100%)]
                                           px-4 pt-4">
                                    <div class="relative h-full w-full">
                                        <div
                                            class="absolute bottom-[12%] left-[18%] right-[18%] h-[14px] rounded-full border-[5px] border-white">
                                        </div>
                                        <div
                                            class="absolute top-[18%] left-[25%] right-[25%] bottom-[40%] rounded-full border-[5px] border-white">
                                        </div>
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
                        <div
                            class="mt-5 rounded-[14px] border border-[rgba(255,255,255,0.2)]
                                   bg-[rgba(255,255,255,0.1)] px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <span class="inline-flex h-5 w-5 items-center justify-center">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="10" cy="10" r="7"
                                                stroke="#05DF72" stroke-width="1.6667" />
                                        <path d="M7.5 10.0001L9.16667 11.6668L12.5 8.33342"
                                              stroke="#05DF72" stroke-width="1.6667"
                                              stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <span class="text-[14px] text-[#05DF72]">Verified</span>
                            </div>
                        </div>
                    </div>

                    {{-- Stats 500+ / 50K+ / 99.9% --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div
                            class="rounded-[14px] border border-[rgba(255,255,255,0.2)]
                                   bg-[rgba(255,255,255,0.05)] px-4 py-4 text-center">
                            <div class="text-[24px] leading-[32px] text-white">500+</div>
                            <div class="mt-1 text-[12px] leading-[16px] text-[#BEDBFF]">Lembaga</div>
                        </div>
                        <div
                            class="rounded-[14px] border border-[rgba(255,255,255,0.2)]
                                   bg-[rgba(255,255,255,0.05)] px-4 py-4 text-center">
                            <div class="text-[24px] leading-[32px] text-white">50K+</div>
                            <div class="mt-1 text-[12px] leading-[16px] text-[#BEDBFF]">Sertifikat</div>
                        </div>
                        <div
                            class="rounded-[14px] border border-[rgba(255,255,255,0.2)]
                                   bg-[rgba(255,255,255,0.05)] px-4 py-4 text-center">
                            <div class="text-[24px] leading-[32px] text-white">99.9%</div>
                            <div class="mt-1 text-[12px] leading-[16px] text-[#BEDBFF]">Akurasi</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
