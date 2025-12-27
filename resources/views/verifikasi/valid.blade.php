<x-layouts.app title="Hasil Verifikasi â€“ SertiKu">

    @php
        $isRevoked = ($certificate['status'] ?? '') === 'revoked';
        $isValid = $certificate['is_valid'] ?? false;
    @endphp

    {{-- MAIN --}}
    <section class="mx-auto max-w-5xl px-4 pt-24 pb-16 flex flex-col items-center">
        <div class="mx-auto flex w-full max-w-5xl flex-col items-center gap-10">


            {{-- CARD UTAMA --}}
            <section
                class="mt-20 relative w-full max-w-3xl rounded-[24px] border border-[rgba(255,255,255,0.14)] bg-[rgba(15,23,42,0.9)] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)]">
                {{-- Garis gradien di atas kartu - RED for revoked, GREEN for valid --}}
                <div
                    class="h-3 w-full rounded-t-[24px] {{ $isRevoked ? 'bg-gradient-to-r from-[#EF4444] via-[#EF4444] to-[#F97316]' : 'bg-gradient-to-r from-[#22C55E] via-[#22C55E] to-[#38BDF8]' }}">
                </div>

                {{-- Icon kecil di tengah atas card --}}
                <div class="absolute left-1/2 top-0 -translate-x-1/2 -translate-y-1/2">
                    <div
                        class="flex h-20 w-20 items-center justify-center rounded-full border-4 border-[rgba(15,23,42,0.9)] bg-[rgba(15,23,42,0.9)] shadow-lg">
                        @if($isRevoked)
                            {{-- RED X icon for revoked --}}
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#EF4444] to-[#DC2626]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="10" stroke="#FFFFFF" stroke-width="1.8" />
                                    <path d="M15 9L9 15M9 9L15 15" stroke="#FFFFFF" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        @else
                            {{-- GREEN checkmark for valid --}}
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#22C55E] to-[#16A34A]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="10" stroke="#FFFFFF" stroke-width="1.8" />
                                    <path d="M8 12.5l2.3 2.3L16 9" stroke="#FFFFFF" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="px-12 pt-16 pb-10 max-[640px]:px-6 max-[640px]:pb-8">

                    {{-- Header teks di dalam card --}}
                    <div class="mb-6 flex flex-col items-center gap-2 text-center">
                        @if($isRevoked)
                            <h2 class="text-base font-bold text-[#EF4444] flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                SERTIFIKAT DICABUT
                            </h2>
                            <p class="max-w-xl text-sm font-normal leading-relaxed text-[rgba(255,200,200,0.9)]">
                                Sertifikat ini telah <strong>DICABUT</strong> oleh penerbit dan tidak lagi valid.
                                Mohon hubungi lembaga penerbit untuk informasi lebih lanjut.
                            </p>
                        @else
                            <h2 class="text-base font-normal text-[#22C55E]">
                                Verifikasi Berhasil
                            </h2>
                            <p class="max-w-xl text-sm font-normal leading-relaxed text-[rgba(190,219,255,0.7)]">
                                Kode hash yang Anda masukkan cocok dengan data sertifikat yang terdaftar
                                dalam database sistem kami.
                            </p>
                        @endif
                    </div>

                    {{-- Garis pemisah --}}
                    <div class="mb-6 h-px w-full bg-[rgba(255,255,255,0.1)] no-print"></div>

                    {{-- GAMBAR SERTIFIKAT (jika ada template) --}}
                    @if($certificate['template_image'] ?? null)
                        {{-- PDF Preview --}}
                        @php
                            $isPortrait = ($certificate['template_orientation'] ?? 'landscape') === 'portrait';
                            $aspectRatio = $isPortrait ? 'aspect-[794/1123]' : 'aspect-[1123/794]';
                        @endphp

                        {{-- Desktop: PDF Iframe --}}
                        <div
                            class="hidden md:block relative w-full rounded-xl overflow-hidden border border-[rgba(255,255,255,0.2)] bg-gray-100 shadow-2xl {{ $aspectRatio }}">
                            <iframe
                                src="{{ route('verifikasi.pdf', ['hash' => $hash, 'stream' => 1]) }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                                class="w-full h-full border-0" title="Preview Sertifikat" scrolling="no">
                            </iframe>
                        </div>

                        {{-- Mobile: PDF.js Canvas Renderer (karena browser mobile tidak bisa render PDF dalam iframe) --}}
                        <div
                            class="md:hidden relative w-full rounded-xl overflow-hidden border border-[rgba(255,255,255,0.2)] bg-gray-800 shadow-2xl">
                            {{-- Canvas container --}}
                            <div id="pdf-container" class="relative {{ $aspectRatio }} flex items-center justify-center">
                                {{-- Loading indicator --}}
                                <div id="pdf-loading" class="absolute inset-0 flex items-center justify-center bg-gray-800">
                                    <div class="text-center">
                                        <div class="animate-spin rounded-full h-12 w-12 border-4 border-white/20 border-t-green-500 mx-auto mb-3"></div>
                                        <p class="text-white/70 text-sm">Memuat sertifikat...</p>
                                    </div>
                                </div>
                                {{-- Canvas will be inserted here by PDF.js --}}
                                <canvas id="pdf-canvas" class="w-full h-full object-contain hidden"></canvas>
                            </div>

                            {{-- Tombol untuk buka PDF lengkap --}}
                            <a href="{{ route('verifikasi.pdf', $hash) }}"
                                class="block w-full py-3 bg-gradient-to-r from-[#22C55E] to-[#16A34A] text-white text-center font-semibold hover:brightness-110 transition">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Unduh Sertifikat PDF
                            </a>
                        </div>

                        {{-- PDF.js Script for Mobile --}}
                        @push('scripts')
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
                        <script>
                            // Only run on mobile
                            if (window.innerWidth < 768) {
                                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

                                const pdfUrl = "{{ route('verifikasi.pdf', ['hash' => $hash, 'stream' => 1]) }}";
                                const canvas = document.getElementById('pdf-canvas');
                                const container = document.getElementById('pdf-container');
                                const loading = document.getElementById('pdf-loading');
                                const ctx = canvas.getContext('2d');

                                pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
                                    pdf.getPage(1).then(function(page) {
                                        // Calculate scale to fit container
                                        const containerWidth = container.clientWidth;
                                        const viewport = page.getViewport({ scale: 1 });
                                        const scale = containerWidth / viewport.width;
                                        const scaledViewport = page.getViewport({ scale: scale * 2 }); // 2x for retina

                                        canvas.height = scaledViewport.height;
                                        canvas.width = scaledViewport.width;
                                        canvas.style.width = '100%';
                                        canvas.style.height = 'auto';

                                        page.render({
                                            canvasContext: ctx,
                                            viewport: scaledViewport
                                        }).promise.then(function() {
                                            loading.classList.add('hidden');
                                            canvas.classList.remove('hidden');
                                        });
                                    });
                                }).catch(function(error) {
                                    console.error('PDF loading error:', error);
                                    loading.innerHTML = '<p class="text-white/70 text-sm text-center px-4">Gagal memuat preview. Klik tombol di bawah untuk download PDF.</p>';
                                });
                            }
                        </script>
                        @endpush
                    @endif


                    {{-- PANEL DETAIL SERTIFIKAT DENGAN QR CODE --}}
                    <div
                        class="mb-8 mt-8 rounded-2xl border {{ $isRevoked ? 'border-[#EF4444]/30' : 'border-[#22C55E]/30' }} bg-[rgba(15,23,42,0.5)] px-6 py-6 max-[640px]:px-4 print-info">
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
                                <div
                                    class="mt-3 grid gap-3 text-sm text-[rgba(190,219,255,0.8)] max-[640px]:gap-2 print-detail-list">
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
                                                    Lihat di PolygonScan
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
                                    <div
                                        class="text-center p-4 bg-[rgba(30,41,59,0.95)] rounded-xl border border-[#3B82F6]/40">
                                        <p class="text-sm font-bold text-white mb-3">Scan untuk Verifikasi</p>
                                        <div class="bg-white p-3 rounded-lg inline-block">
                                            <img src="{{ $certificate['qr_code_url'] }}" alt="QR Code" class="w-28 h-28">
                                        </div>
                                        <p class="text-xs text-white/90 mt-3 break-all font-mono">
                                            {{ $certificate['nomor'] ?? '' }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Verification Badge --}}
                        <div class="mt-6 pt-4 border-t border-[rgba(255,255,255,0.1)] print-verification-badge">
                            <div class="flex items-center justify-center gap-2 bg-[#22C55E]/20 rounded-lg py-3 px-4">
                                <svg class="w-5 h-5 text-[#22C55E]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span class="text-[#22C55E] font-semibold text-sm">Terverifikasi di
                                    sertiku.web.id</span>
                            </div>

                            {{-- Blockchain Verification Section --}}
                            @if(isset($certificate['blockchain_tx_hash']) && $certificate['blockchain_tx_hash'])
                                <div class="mt-3">
                                    <a href="{{ config('blockchain.explorer_url', 'https://amoy.polygonscan.com') }}/tx/{{ $certificate['blockchain_tx_hash'] }}"
                                        target="_blank"
                                        class="flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600/20 to-indigo-600/20 border border-purple-500/40 rounded-lg py-3 px-4 hover:from-purple-600/30 hover:to-indigo-600/30 transition group">
                                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                        <span class="text-purple-300 font-semibold text-sm">Tersimpan di Blockchain
                                            (Polygon)</span>
                                        <svg class="w-4 h-4 text-purple-400 opacity-0 group-hover:opacity-100 transition"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                    <p class="text-center text-xs text-purple-400/60 mt-2 font-mono break-all px-4">
                                        TX: {{ $certificate['blockchain_tx_hash'] }}
                                    </p>
                                </div>
                            @endif

                            {{-- IPFS Verification Section --}}
                            @if(isset($certificate['ipfs_cid']) && $certificate['ipfs_cid'])
                                <div class="mt-3">
                                    <a href="{{ config('ipfs.gateway_url', 'https://w3s.link/ipfs') }}/{{ $certificate['ipfs_cid'] }}"
                                        target="_blank"
                                        class="flex items-center justify-center gap-2 bg-gradient-to-r from-cyan-600/20 to-teal-600/20 border border-cyan-500/40 rounded-lg py-3 px-4 hover:from-cyan-600/30 hover:to-teal-600/30 transition group">
                                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <span class="text-cyan-300 font-semibold text-sm">Tersimpan di IPFS
                                            (Desentralisasi)</span>
                                        <svg class="w-4 h-4 text-cyan-400 opacity-0 group-hover:opacity-100 transition"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                    <p class="text-center text-xs text-cyan-400/60 mt-2 font-mono break-all px-4">
                                        CID: {{ $certificate['ipfs_cid'] }}
                                    </p>
                                </div>
                            @endif

                            {{-- File Integrity Section --}}
                            @if(!empty($certificate['certificate_sha256']) || !empty($certificate['qr_sha256']) || !empty($certificate['template_sha256']))
                                <div class="mt-4 pt-4 border-t border-[rgba(255,255,255,0.1)]">
                                    <h4 class="text-sm font-bold text-white/80 mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        File Integrity (Integritas File)
                                    </h4>

                                    <div class="space-y-3">
                                        {{-- PDF Hashes --}}
                                        @if(isset($certificate['certificate_sha256']) && $certificate['certificate_sha256'])
                                            <div class="bg-cyan-500/10 rounded-xl p-3">
                                                <p class="text-cyan-400 text-xs font-semibold mb-2 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    PDF File
                                                </p>
                                                <div class="space-y-1">
                                                    <div class="flex flex-col">
                                                        <span class="text-white/50 text-xs">SHA256:</span>
                                                        <span
                                                            class="text-cyan-300 text-xs font-mono break-all">{{ $certificate['certificate_sha256'] }}</span>
                                                    </div>
                                                    @if(isset($certificate['certificate_md5']) && $certificate['certificate_md5'])
                                                        <div class="flex flex-col">
                                                            <span class="text-white/50 text-xs">MD5:</span>
                                                            <span
                                                                class="text-cyan-300 text-xs font-mono break-all">{{ $certificate['certificate_md5'] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        {{-- QR Code Hashes --}}
                                        @if(isset($certificate['qr_sha256']) && $certificate['qr_sha256'])
                                            <div class="bg-green-500/10 rounded-xl p-3">
                                                <p class="text-green-400 text-xs font-semibold mb-2 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                                    </svg>
                                                    QR Code
                                                </p>
                                                <div class="space-y-1">
                                                    <div class="flex flex-col">
                                                        <span class="text-white/50 text-xs">SHA256:</span>
                                                        <span
                                                            class="text-green-300 text-xs font-mono break-all">{{ $certificate['qr_sha256'] }}</span>
                                                    </div>
                                                    @if(isset($certificate['qr_md5']) && $certificate['qr_md5'])
                                                        <div class="flex flex-col">
                                                            <span class="text-white/50 text-xs">MD5:</span>
                                                            <span
                                                                class="text-green-300 text-xs font-mono break-all">{{ $certificate['qr_md5'] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Template Hashes --}}
                                        @if(isset($certificate['template_sha256']) && $certificate['template_sha256'])
                                            <div class="bg-purple-500/10 rounded-xl p-3">
                                                <p class="text-purple-400 text-xs font-semibold mb-2 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                                    </svg>
                                                    Template
                                                </p>
                                                <div class="space-y-1">
                                                    <div class="flex flex-col">
                                                        <span class="text-white/50 text-xs">SHA256:</span>
                                                        <span
                                                            class="text-purple-300 text-xs font-mono break-all">{{ $certificate['template_sha256'] }}</span>
                                                    </div>
                                                    @if(isset($certificate['template_md5']) && $certificate['template_md5'])
                                                        <div class="flex flex-col">
                                                            <span class="text-white/50 text-xs">MD5:</span>
                                                            <span
                                                                class="text-purple-300 text-xs font-mono break-all">{{ $certificate['template_md5'] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
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
                        <a href="{{ route('verifikasi.pdf', $hash) }}"
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
                                    Unduh Sertifikat (PDF)
                                </p>
                                <p class="text-sm font-normal text-[#22C55E]">
                                    Simpan dokumen resmi sertifikat Anda.
                                </p>
                            </div>
                        </a>

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
                                    Tunjukkan pencapaianmu! Buat yang lain tahu SertiKu
                                </p>
                            </div>
                        </button>

                        <script>
                            function shareCertificate() {
                                const url = window.location.href;
                                const shareText = document.getElementById('share-text');

                                if (navigator.share) {
                                    navigator.share({
                                        title: 'Sertifikat Terverifikasi - SertiKu',
                                        text: 'Lihat sertifikat asli saya yang terverifikasi di SertiKu! Platform sertifikat digital terpercaya dengan teknologi blockchain. Yuk cobain SertiKu untuk sertifikatmu!',
                                        url: url
                                    });
                                } else {
                                    navigator.clipboard.writeText(url).then(() => {
                                        // Use SweetAlert2 Toast
                                        Swal.fire({
                                            toast: true,
                                            position: 'top-end',
                                            icon: 'success',
                                            title: 'Link berhasil disalin!',
                                            showConfirmButton: false,
                                            timer: 3000,
                                            timerProgressBar: true,
                                            background: '#151f32',
                                            color: '#fff'
                                        });

                                        shareText.textContent = 'Link berhasil disalin! Share sekarang!';
                                        setTimeout(() => {
                                            shareText.textContent = 'Tunjukkan pencapaianmu! Buat yang lain tahu SertiKu';
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
                display: block !important;
                width: 90% !important;
                max-width: 650px !important;
                margin: 0 auto 20px auto !important;
            }

            /* Page break after certificate image */
            .print-page-break {
                page-break-after: always !important;
                break-after: page !important;
            }

            .print-only-cert img {
                width: 100% !important;
                height: auto !important;
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
            }

            /* Position info on next page */
            .print-info {
                display: block !important;
                width: 90% !important;
                max-width: 650px !important;
                margin: 0 auto !important;
                background: #f8fafc !important;
                border: 1px solid #e5e7eb !important;
                border-radius: 12px !important;
                padding: 20px !important;
                page-break-before: auto !important;
            }

            /* Keep flex layout for print */
            .print-info>.flex {
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
                width: 160px !important;
                flex-shrink: 0 !important;
            }

            .print-info .lg\\:w-48>div {
                background: #f1f5f9 !important;
                border: 1px solid #e5e7eb !important;
                padding: 16px !important;
                border-radius: 12px !important;
            }

            .print-info .lg\\:w-48 img {
                width: 110px !important;
                height: 110px !important;
            }

            /* QR code section text for print */
            .print-info .lg\\:w-48 p {
                color: #1e293b !important;
                font-size: 11px !important;
                font-weight: 600 !important;
            }

            .print-info .lg\\:w-48 .font-mono {
                font-size: 9px !important;
                color: #475569 !important;
                font-weight: 500 !important;
            }

            /* QR Card specific styles - preserve background and white text */
            .print-info .lg\:w-48>div {
                background: #1e293b !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            .print-info .lg\:w-48 .text-white,
            .print-info .lg\:w-48 .text-white\/90 {
                color: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .print-info .lg\:w-48 .font-mono {
                color: #ffffff !important;
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

            .print-verification-badge>div {
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