<x-layouts.app title="Hasil Verifikasi – SertiKu">

    {{-- MAIN --}}
    <section class="mx-auto max-w-5xl px-4 pt-24 pb-16 flex flex-col items-center">
        <div class="mx-auto flex w-full max-w-5xl flex-col items-center gap-10">


            {{-- CARD UTAMA --}}
            <section
                class="mt-20 relative w-full max-w-3xl rounded-[24px] border border-[rgba(255,255,255,0.14)] bg-[rgba(15,23,42,0.9)] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)]">
                {{-- Garis gradien di atas kartu --}}
                <div class="h-3 w-full rounded-t-[24px] bg-gradient-to-r from-[#22C55E] via-[#22C55E] to-[#38BDF8]">
                </div>

                {{-- Icon kecil di tengah atas card --}}
                <div class="absolute left-1/2 top-0 -translate-x-1/2 -translate-y-1/2">
                    <div
                        class="flex h-20 w-20 items-center justify-center rounded-full border-4 border-[rgba(15,23,42,0.9)] bg-[rgba(15,23,42,0.9)] shadow-lg">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#22C55E] to-[#16A34A]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="#FFFFFF" stroke-width="1.8" />
                                <path d="M8 12.5l2.3 2.3L16 9" stroke="#FFFFFF" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="px-12 pt-16 pb-10 max-[640px]:px-6 max-[640px]:pb-8">

                    {{-- Header teks di dalam card --}}
                    <div class="mb-6 flex flex-col items-center gap-2 text-center">
                        <h2 class="text-base font-normal text-[#22C55E]">
                            Verifikasi Berhasil
                        </h2>
                        <p class="max-w-xl text-sm font-normal leading-relaxed text-[rgba(190,219,255,0.7)]">
                            Kode hash yang Anda masukkan cocok dengan data sertifikat yang terdaftar
                            dalam database sistem kami.
                        </p>
                    </div>

                    {{-- Garis pemisah --}}
                    <div class="mb-6 h-px w-full bg-[rgba(255,255,255,0.1)] no-print"></div>

                    {{-- GAMBAR SERTIFIKAT (jika ada template) --}}
                    @if($certificate['template_image'] ?? null)
                    <div class="mb-8 print-only-cert">
                        <p class="text-sm font-normal text-[#8EC5FF] mb-3 no-print text-center">Preview Sertifikat</p>
                        <div class="rounded-xl overflow-hidden border border-[rgba(255,255,255,0.2)] bg-white">
                            <img src="{{ $certificate['template_image'] }}" alt="Sertifikat" class="w-full h-auto">
                        </div>
                    </div>
                    @endif

                    {{-- PANEL DETAIL SERTIFIKAT DENGAN QR CODE --}}
                    <div class="mb-8 rounded-2xl border border-[#22C55E]/30 bg-[rgba(15,23,42,0.5)] px-6 py-6 max-[640px]:px-4 print-info">
                        <div class="flex flex-col lg:flex-row gap-6">
                            {{-- Left: Certificate Details --}}
                            <div class="flex-1">
                                <div class="mb-4 flex flex-col gap-1">
                                    <p class="text-sm font-normal text-[#22C55E]">
                                        Detail Sertifikat
                                    </p>
                                    <p class="text-sm font-normal leading-relaxed text-[rgba(190,219,255,0.7)]">
                                        Berikut informasi utama dari sertifikat yang berhasil diverifikasi.
                                    </p>
                                </div>

                                {{-- List detail --}}
                                <div class="mt-3 grid gap-3 text-sm text-[rgba(190,219,255,0.8)] max-[640px]:gap-2 print-detail-list">
                                    <div class="flex items-start gap-3">
                                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#22C55E] print-bullet"></span>
                                        <p>
                                            <span class="font-semibold text-[#8EC5FF]">Nama Pemilik:</span>
                                            <span class="ml-1">
                                                {{ $certificate['nama'] ?? 'Mr. Ambatukam' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#22C55E] print-bullet"></span>
                                        <p>
                                            <span class="font-semibold text-[#8EC5FF]">Nama Sertifikat / Acara:</span>
                                            <span class="ml-1">
                                                {{ $certificate['judul'] ?? 'Penghargaan' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#22C55E] print-bullet"></span>
                                        <p>
                                            <span class="font-semibold text-[#8EC5FF]">Tanggal Diterbitkan:</span>
                                            <span class="ml-1">
                                                {{ $certificate['tanggal'] ?? '06 Juli 2025' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#22C55E] print-bullet"></span>
                                        <p>
                                            <span class="font-semibold text-[#8EC5FF]">Penerbit Sertifikat:</span>
                                            <span class="ml-1">
                                                {{ $certificate['penerbit'] ?? 'Barbershop Ngawi' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#22C55E] print-bullet"></span>
                                        <p>
                                            <span class="font-semibold text-[#8EC5FF]">Nomor Sertifikat:</span>
                                            <span class="ml-1">
                                                {{ $certificate['nomor'] ?? '-' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#22C55E] print-bullet"></span>
                                        <p>
                                            <span class="font-semibold text-[#8EC5FF]">Status:</span>
                                            <span class="ml-1 text-[#15803D]">
                                                Aktif &amp; Terverifikasi
                                            </span>
                                        </p>
                                    </div>

                                    {{-- Blockchain Verification --}}
                                    @if(isset($certificate['blockchain_tx_hash']) && $certificate['blockchain_tx_hash'])
                                    <div class="flex items-start gap-3 mt-2 pt-2 border-t border-[#3B82F6]/20">
                                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-purple-500 print-bullet"></span>
                                        <div>
                                            <span class="font-semibold text-purple-400">Blockchain Verified:</span>
                                            <a href="{{ config('blockchain.explorer_url', 'https://amoy.polygonscan.com') }}/tx/{{ $certificate['blockchain_tx_hash'] }}"
                                               target="_blank"
                                               class="ml-1 text-purple-300 hover:text-purple-200 underline transition">
                                                Lihat di PolygonScan →
                                            </a>
                                            <p class="text-xs text-purple-400/60 mt-1 font-mono break-all">
                                                TX: {{ Str::limit($certificate['blockchain_tx_hash'], 30) }}
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Right: QR Code --}}
                            @if($certificate['qr_code_url'] ?? null)
                            <div class="lg:w-48 flex-shrink-0">
                                <div class="text-center p-4 bg-[rgba(15,23,42,0.8)] rounded-xl border border-[#3B82F6]/30">
                                    <p class="text-xs font-semibold text-[#8EC5FF] mb-3">Scan untuk Verifikasi</p>
                                    <div class="bg-white p-3 rounded-lg inline-block">
                                        <img src="{{ $certificate['qr_code_url'] }}" alt="QR Code" class="w-28 h-28">
                                    </div>
                                    <p class="text-[10px] text-[rgba(190,219,255,0.5)] mt-2 break-all">
                                        {{ $certificate['nomor'] ?? '' }}
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Verification Badge --}}
                        <div class="mt-6 pt-4 border-t border-[rgba(255,255,255,0.1)] print-verification-badge">
                            <div class="flex items-center justify-center gap-2 bg-[#22C55E]/20 rounded-lg py-3 px-4">
                                <svg class="w-5 h-5 text-[#22C55E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span class="text-[#22C55E] font-semibold text-sm">Terverifikasi di sertiku.web.id</span>
                            </div>

                            {{-- Blockchain Verification Section --}}
                            @if(isset($certificate['blockchain_tx_hash']) && $certificate['blockchain_tx_hash'])
                            <div class="mt-3">
                                <a href="{{ config('blockchain.explorer_url', 'https://amoy.polygonscan.com') }}/tx/{{ $certificate['blockchain_tx_hash'] }}"
                                   target="_blank"
                                   class="flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600/20 to-indigo-600/20 border border-purple-500/40 rounded-lg py-3 px-4 hover:from-purple-600/30 hover:to-indigo-600/30 transition group">
                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                    <span class="text-purple-300 font-semibold text-sm">Tersimpan di Blockchain (Polygon)</span>
                                    <svg class="w-4 h-4 text-purple-400 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                                <p class="text-center text-xs text-purple-400/60 mt-2 font-mono break-all px-4">
                                    TX: {{ $certificate['blockchain_tx_hash'] }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- LANGKAH SELANJUTNYA (layout mirip invalid) --}}
                    <div class="space-y-4">
                        <p class="text-sm font-normal text-white">
                            Langkah Selanjutnya:
                        </p>

                        {{-- Button 1: Unduh Sertifikat --}}
                        <button type="button" onclick="window.print()"
                            class="group relative flex w-full items-center gap-3 rounded-lg border border-[#22C55E]/30 bg-[rgba(15,23,42,0.5)] px-6 py-4 text-left hover:bg-[rgba(34,197,94,0.1)] transition">
                            <div class="flex h-11 w-11 items-center justify-center rounded-[14px] bg-[#22C55E]/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 4v10" stroke="#22C55E" stroke-width="1.8" stroke-linecap="round" />
                                    <path d="M8.5 10.5L12 14l3.5-3.5" stroke="#22C55E" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M6 18h12" stroke="#22C55E" stroke-width="1.8" stroke-linecap="round" />
                                </svg>
                            </div>

                            <div class="flex flex-col">
                                <p class="text-sm font-normal text-white">
                                    Unduh Sertifikat
                                </p>
                                <p class="text-sm font-normal text-[#22C55E]">
                                    Simpan salinan sertifikat digital Anda.
                                </p>
                            </div>
                        </button>

                        {{-- Button 2: Bagikan Sertifikat --}}
                        <button type="button" onclick="shareCertificate()"
                            class="group relative flex w-full items-center gap-3 rounded-lg border border-[rgba(255,255,255,0.14)] bg-[rgba(15,23,42,0.5)] px-6 py-4 text-left hover:bg-[rgba(59,130,246,0.1)] transition">
                            <div class="flex h-11 w-11 items-center justify-center rounded-[14px] bg-[#3B82F6]/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                    <path d="M15 6.5a2.5 2.5 0 11-4.9.7" stroke="#3B82F6" stroke-width="1.7"
                                        stroke-linecap="round" />
                                    <path d="M9 13.5a2.5 2.5 0 10-1.9 2.4" stroke="#3B82F6" stroke-width="1.7"
                                        stroke-linecap="round" />
                                    <path d="M15 17.5a2.5 2.5 0 10.5-4.9" stroke="#3B82F6" stroke-width="1.7"
                                        stroke-linecap="round" />
                                    <path d="M10.5 9L9 11" stroke="#3B82F6" stroke-width="1.7" stroke-linecap="round" />
                                    <path d="M10.5 15L14 16" stroke="#3B82F6" stroke-width="1.7"
                                        stroke-linecap="round" />
                                </svg>
                            </div>

                            <div class="flex flex-col">
                                <p class="text-sm font-normal text-white">
                                    Bagikan Sertifikat
                                </p>
                                <p class="text-sm font-normal text-[#3B82F6]" id="share-text">
                                    Kirim tautan verifikasi kepada pihak lain.
                                </p>
                            </div>
                        </button>

                        <script>
                            function shareCertificate() {
                                const url = window.location.href;
                                const shareText = document.getElementById('share-text');

                                if (navigator.share) {
                                    navigator.share({
                                        title: 'Verifikasi Sertifikat',
                                        text: 'Lihat sertifikat yang terverifikasi ini',
                                        url: url
                                    });
                                } else {
                                    navigator.clipboard.writeText(url).then(() => {
                                        shareText.textContent = '✓ Link berhasil disalin!';
                                        setTimeout(() => {
                                            shareText.textContent = 'Kirim tautan verifikasi kepada pihak lain.';
                                        }, 2000);
                                    });
                                }
                            }
                        </script>

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
                    <path d="M6.66667 3.33301L2 7.99967L6.66667 12.6663" stroke="currentColor" stroke-width="1.333"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M14 8H2.66667" stroke="currentColor" stroke-width="1.333" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <span class="font-semibold">Kembali ke Verifikasi</span>
            </a>
        </div>

    </section>

    {{-- Print-only styles --}}
    <style>
        @media print {
            /* Reset and base print styles */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Hide everything except printable content */
            body * {
                visibility: hidden;
            }

            /* Hide navbar, footer, and no-print elements */
            .no-print,
            nav,
            footer,
            header {
                display: none !important;
            }

            /* Show certificate image and info */
            .print-only-cert,
            .print-only-cert *,
            .print-info,
            .print-info * {
                visibility: visible !important;
            }

            /* Clean background for print */
            body {
                background: white !important;
            }

            /* Position certificate at top center */
            .print-only-cert {
                position: absolute !important;
                left: 50% !important;
                top: 10px !important;
                transform: translateX(-50%) !important;
                width: 90% !important;
                max-width: 650px !important;
            }

            .print-only-cert img {
                width: 100% !important;
                height: auto !important;
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
            }

            /* Position info below certificate */
            .print-info {
                position: absolute !important;
                left: 50% !important;
                top: 420px !important;
                transform: translateX(-50%) !important;
                width: 90% !important;
                max-width: 650px !important;
                background: #f8fafc !important;
                border: 1px solid #e5e7eb !important;
                border-radius: 12px !important;
                padding: 20px !important;
            }

            /* Keep flex layout for print */
            .print-info > .flex {
                display: flex !important;
                flex-direction: row !important;
                gap: 20px !important;
            }

            /* Certificate details styling */
            .print-info .flex-1 {
                flex: 1 !important;
            }

            /* QR code section for print */
            .print-info .lg\\:w-48 {
                width: 140px !important;
                flex-shrink: 0 !important;
            }

            .print-info .lg\\:w-48 > div {
                background: #f1f5f9 !important;
                border: 1px solid #e5e7eb !important;
                padding: 12px !important;
            }

            .print-info .lg\\:w-48 img {
                width: 100px !important;
                height: 100px !important;
            }

            /* Print-friendly text colors */
            .print-info p,
            .print-info span {
                color: #1e293b !important;
            }

            .print-info .text-\[\#8EC5FF\],
            .print-info .font-semibold {
                color: #1e40af !important;
            }

            .print-info .text-\[\#22C55E\] {
                color: #16a34a !important;
            }

            .print-info .text-\[\#15803D\] {
                color: #16a34a !important;
            }

            /* Style bullets for print */
            .print-bullet {
                background: #16a34a !important;
            }

            /* Verification badge print style */
            .print-verification-badge {
                border-top: 1px solid #e5e7eb !important;
                margin-top: 16px !important;
                padding-top: 16px !important;
            }

            .print-verification-badge > div {
                background: #dcfce7 !important;
                border-radius: 8px !important;
                padding: 12px !important;
            }

            .print-verification-badge span {
                color: #16a34a !important;
            }

            .print-verification-badge svg {
                color: #16a34a !important;
            }

            /* Detail list styling */
            .print-detail-list {
                font-size: 11px !important;
            }

            /* Show header text in print */
            .print-info .flex-col.gap-1 p {
                color: #16a34a !important;
            }

            /* Page setup */
            @page {
                size: A4;
                margin: 10mm;
            }
        }
    </style>

</x-layouts.app>
