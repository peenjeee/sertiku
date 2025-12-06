{{-- resources/views/verifikasi/valid.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Verifikasi Sertifikat - Valid</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tailwind (Laravel + Vite) --}}
    @vite('resources/css/app.css')

    {{-- Font Arimo --}}
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-[#F8FAFC] via-[#EFF6FF] to-[#ECFEFF]" style="font-family: 'Arimo', sans-serif;">

    {{-- TOP BAR: Kembali ke Verifikasi --}}
    <header class="fixed inset-x-0 top-0 z-20 border-b border-[#DBEAFE] bg-white/80 backdrop-blur shadow-sm">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-8 py-3">
            <a href="{{ route('verifikasi') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-[#CBD5F5] bg-white px-4 py-2 text-sm font-normal text-[#1447E6] hover:bg-[#EFF6FF] transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                    <path d="M15 19l-7-7 7-7" stroke="#1447E6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Kembali ke Verifikasi</span>
            </a>

            {{-- Placeholder kanan (bisa dikosongkan / nanti untuk logo/chat) --}}
            <div class="h-6 w-6 opacity-0"></div>
        </div>
    </header>

    {{-- MAIN --}}
    <main class="flex min-h-screen items-center justify-center px-4 pt-[96px] pb-16">
        <div class="mx-auto flex w-full max-w-5xl flex-col items-center gap-10">

            {{-- ICON BESAR DI ATAS --}}
            <div class="relative h-40 w-40">
                <div class="absolute inset-0 rounded-full bg-gradient-to-br from-[#00C950]/60 to-[#00BC7D]/60 blur-3xl"></div>

                <div class="relative flex h-40 w-40 items-center justify-center rounded-full bg-gradient-to-br from-[#00C950] to-[#00BC7D] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.25)]">
                    <div class="flex h-24 w-24 items-center justify-center rounded-full border-[8px] border-white/95 bg-white/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="11" stroke="#FFFFFF" stroke-width="2.5"/>
                            <path d="M8 12.5l2.5 2.5L16 9" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- TEKS JUDUL DI BAWAH ICON --}}
            <div class="flex flex-col items-center gap-2 text-center">
                <p class="text-sm font-normal text-[#15803D]">Sertifikat Valid</p>
                <p class="text-lg font-normal text-[#166534]">
                    Sertifikat berhasil diverifikasi dalam sistem kami
                </p>
            </div>

            {{-- CARD UTAMA --}}
            <section class="relative w-full max-w-3xl rounded-2xl bg-white shadow-[0_25px_50px_-12px_rgba(15,23,42,0.25)]">
                {{-- Garis gradien di atas kartu --}}
                <div class="h-1 w-full rounded-t-2xl bg-gradient-to-r from-[#22C55E] via-[#22C55E] to-[#38BDF8]"></div>

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
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-[#22C55E] to-[#16A34A]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="10" stroke="#FFFFFF" stroke-width="2"/>
                                    <path d="M8 12.5l2.3 2.3L16 9" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>

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
                                        {{ $certificate['nama'] ?? 'Nama Peserta Contoh' }}
                                    </span>
                                </p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#22C55E]"></span>
                                <p>
                                    <span class="font-semibold text-[#1C398E]">Nama Sertifikat / Acara:</span>
                                    <span class="ml-1">
                                        {{ $certificate['judul'] ?? 'Pelatihan & Workshop Contoh' }}
                                    </span>
                                </p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#22C55E]"></span>
                                <p>
                                    <span class="font-semibold text-[#1C398E]">Tanggal Diterbitkan:</span>
                                    <span class="ml-1">
                                        {{ $certificate['tanggal'] ?? '01 Januari 2024' }}
                                    </span>
                                </p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#22C55E]"></span>
                                <p>
                                    <span class="font-semibold text-[#1C398E]">Penerbit Sertifikat:</span>
                                    <span class="ml-1">
                                        {{ $certificate['penerbit'] ?? 'Nama Lembaga Contoh' }}
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
                        <div class="mt-2 flex items-center justify-center gap-2 text-xs text-[#64748B]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="#155DFC" stroke-width="1.5"/>
                                <path d="M12 8v1.5M12 11v5" stroke="#155DFC" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <span>Kode hash terdapat pada sertifikat digital Anda.</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    {{-- CHAT WIDGET BULAT DI KANAN BAWAH (mirip invalid) --}}
    <button
        class="fixed bottom-8 right-8 flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-b from-[#1E3A8A] to-[#3B82F6] shadow-[0_20px_25px_-5px_rgba(0,0,0,0.1),0_10px_10px_-5px_rgba(0,0,0,0.1)]">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none">
            <path d="M5 18l.4-2.8A7 7 0 015 11.5 7.5 7.5 0 1112.5 19a7 7 0 01-3.1-.4L7 19z"
                  stroke="#FFFFFF" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="10" cy="11" r="0.75" fill="#FFFFFF"/>
            <circle cx="13.5" cy="11" r="0.75" fill="#FFFFFF"/>
            <circle cx="17" cy="11" r="0.75" fill="#FFFFFF"/>
        </svg>
    </button>

</body>
</html>
