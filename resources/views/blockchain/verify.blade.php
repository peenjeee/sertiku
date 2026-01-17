{{-- resources/views/blockchain/verify.blade.php --}}
<x-layouts.app title="Verifikasi Blockchain – SertiKu">

    {{-- KONTEN UTAMA --}}
    <section class="mx-auto flex max-w-4xl flex-col gap-8 px-4 pb-20 pt-16 lg:px-0 lg:pt-20">

        {{-- Title --}}
        <div class="text-center animate-fade-in-up">
            <div class="inline-flex h-[34px] items-center gap-2 rounded-[8px]
                       border border-[rgba(255,255,255,0.2)]
                       bg-[rgba(255,255,255,0.1)] px-4 text-[12px] text-white mb-4">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
                <span>Blockchain Verification</span>
            </div>
            <h1 class="text-[32px] leading-[40px] font-semibold text-white md:text-[40px] md:leading-[50px]">
                Verifikasi Sertifikat
            </h1>
            <h2 class="text-[32px] leading-[40px] font-semibold text-[#A855F7]
                       md:text-[40px] md:leading-[50px]">
                Blockchain
            </h2>
            <p class="mt-4 text-[15px] text-[#BEDBFF]">
                Cek keaslian sertifikat yang tersimpan di jaringan Polygon
            </p>
        </div>

        {{-- Search Form --}}
        <div class="rounded-[16px] border border-[rgba(255,255,255,0.14)]
                   bg-[rgba(15,23,42,0.9)]
                   px-6 py-6 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.25)]
                   animate-fade-in-up stagger-2">
            <form action="{{ route('blockchain.verify') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <input type="text" name="q" value="{{ $query ?? '' }}"
                        placeholder="Masukkan nomor sertifikat atau hash blockchain..."
                        class="w-full px-4 py-4 pl-12 rounded-[8px] bg-white/5 border border-white/20 text-white placeholder-white/40 focus:outline-none focus:border-purple-500 transition">
                    <svg class="w-5 h-5 text-white/40 absolute left-4 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-[8px]
                                           bg-[#7C3AED]
                                           px-6 py-4 text-sm font-medium text-white
                                           shadow-[0_10px_15px_-3px_rgba(168,85,247,0.5)]
                                           hover:brightness-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Verifikasi</span>
                </button>
            </form>
        </div>

        @if($query ?? false)
            {{-- Result Section --}}
            <div class="space-y-6 animate-fade-in-up stagger-3">
                @php
                    $isRevoked = ($certificate ?? false) && $certificate->status === 'revoked';
                    $isExpired = ($certificate ?? false) && $certificate->expire_date && \Carbon\Carbon::parse($certificate->expire_date)->isPast();
                @endphp

                @if($isRevoked)
                    {{-- REVOKED Certificate Warning --}}
                    <div
                        class="rounded-[16px] overflow-hidden border-2 border-red-500/50 bg-[rgba(15,23,42,0.9)]">
                        <div class="p-4 bg-red-500/20 border-b border-red-500/30 flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-red-400 font-bold text-lg flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg> SERTIFIKAT DICABUT</h2>
                                <p class="text-red-300/70 text-sm">Sertifikat ini telah DICABUT oleh penerbit dan tidak lagi
                                    valid</p>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-white/70 mb-4">Sertifikat <strong>{{ $certificate->certificate_number }}</strong>
                                telah dicabut pada
                                {{ $certificate->revoked_at?->format('d F Y') ?? 'tanggal tidak diketahui' }}.
                            </p>
                            @if($certificate->revoked_reason)
                                <p class="text-white/50 text-sm">Alasan: {{ $certificate->revoked_reason }}</p>
                            @endif
                        </div>
                    </div>

                @elseif($isExpired && ($result ?? '') === 'found' && ($onChainData ?? false))
                    {{-- EXPIRED Certificate Warning --}}
                    <div
                        class="rounded-[16px] overflow-hidden border-2 border-amber-500/50 bg-[rgba(15,23,42,0.9)]">
                        <div class="p-4 bg-amber-500/20 border-b border-amber-500/30 flex items-center gap-3">
                            <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-amber-400 font-bold text-lg flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    SERTIFIKAT KADALUARSA
                                </h2>
                                <p class="text-amber-300/70 text-sm">Sertifikat ini telah melewati masa berlaku</p>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-white/70 mb-4">Sertifikat <strong>{{ $certificate->certificate_number }}</strong>
                                telah kadaluarsa pada {{ \Carbon\Carbon::parse($certificate->expire_date)->format('d F Y') }}.
                            </p>
                            <p class="text-white/50 text-sm mb-4">Data blockchain tetap valid, namun sertifikat tidak lagi berlaku.</p>

                            {{-- Show blockchain data for expired certificate --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                @if(isset($onChainData['certificateNumber']) && $onChainData['certificateNumber'])
                                    <div class="bg-white/5 rounded-xl p-4">
                                        <p class="text-white/50 text-xs mb-1">Nomor Sertifikat</p>
                                        <p class="text-white font-bold">{{ $onChainData['certificateNumber'] }}</p>
                                    </div>
                                @endif
                                @if(isset($onChainData['recipientName']) && $onChainData['recipientName'])
                                    <div class="bg-white/5 rounded-xl p-4">
                                        <p class="text-white/50 text-xs mb-1">Nama Penerima</p>
                                        <p class="text-white font-bold">{{ $onChainData['recipientName'] }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                @elseif(($result ?? '') === 'found' && ($onChainData ?? false))
                    {{-- Success: Found on Blockchain --}}
                    <div
                        class="rounded-[16px] overflow-hidden border-2 border-green-500/50 bg-[rgba(15,23,42,0.9)]">
                        <div class="p-4 bg-green-500/20 border-b border-green-500/30 flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-green-400 font-bold text-lg">Terverifikasi di Blockchain</h2>
                                <p class="text-green-300/70 text-sm">Sertifikat ini tersimpan permanen di jaringan Polygon</p>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            {{-- On-Chain Data --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if(isset($onChainData['certificateNumber']) && $onChainData['certificateNumber'])
                                    <div class="bg-white/5 rounded-xl p-4">
                                        <p class="text-white/50 text-xs mb-1">Nomor Sertifikat</p>
                                        <p class="text-white font-bold">{{ $onChainData['certificateNumber'] }}</p>
                                    </div>
                                @endif

                                @if(isset($onChainData['recipientName']) && $onChainData['recipientName'])
                                    <div class="bg-white/5 rounded-xl p-4">
                                        <p class="text-white/50 text-xs mb-1">Nama Penerima</p>
                                        <p class="text-white font-bold">{{ $onChainData['recipientName'] }}</p>
                                    </div>
                                @endif

                                @if(isset($onChainData['courseName']) && $onChainData['courseName'])
                                    <div class="bg-white/5 rounded-xl p-4">
                                        <p class="text-white/50 text-xs mb-1">Nama Kursus</p>
                                        <p class="text-white font-bold">{{ $onChainData['courseName'] }}</p>
                                    </div>
                                @endif

                                @if(isset($onChainData['issueDate']) && $onChainData['issueDate'])
                                    <div class="bg-white/5 rounded-xl p-4">
                                        <p class="text-white/50 text-xs mb-1">Tanggal Terbit</p>
                                        <p class="text-white font-bold">{{ $onChainData['issueDate'] }}</p>
                                    </div>
                                @endif

                                @if(isset($onChainData['issuerName']) && $onChainData['issuerName'])
                                    @php
                                        // Try to parse as JSON, fallback to raw string
                                        $issuerRaw = $onChainData['issuerName'];
                                        $parsedData = json_decode($issuerRaw, true);
                                        $isJson = json_last_error() === JSON_ERROR_NONE && is_array($parsedData);
                                    @endphp
                                    <div class="bg-white/5 rounded-xl p-4">
                                        <p class="text-white/50 text-xs mb-1">Data</p>
                                        @if($isJson)
                                            <pre class="text-white text-xs font-mono whitespace-pre-wrap break-all overflow-x-auto max-h-64 overflow-y-auto scrollbar-hide" style="-ms-overflow-style: none; scrollbar-width: none;">{{ json_encode($parsedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                                            <style>.scrollbar-hide::-webkit-scrollbar { display: none; }</style>
                                        @else
                                            {{-- Fallback: extract issuer name only (before the hash separator | ) --}}
                                            @php $issuerDisplay = explode(' | ', $issuerRaw)[0]; @endphp
                                            <p class="text-white font-bold">{{ $issuerDisplay }}</p>
                                        @endif
                                    </div>
                                @endif

                                @if(isset($onChainData['timestamp']) || isset($onChainData['date']))
                                    <div class="bg-white/5 rounded-xl p-4">
                                        <p class="text-white/50 text-xs mb-1">Waktu Penyimpanan</p>
                                        <p class="text-white font-bold">
                                            @if(isset($onChainData['timestamp']))
                                                {{ \Carbon\Carbon::createFromTimestamp($onChainData['timestamp'])->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i:s') }} WIB
                                            @else
                                                {{ \Carbon\Carbon::parse($onChainData['date'])->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i:s') }} WIB
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            </div>

                            {{-- File Integrity Section --}}
                            @if(($certificate ?? false) && ($certificate->certificate_sha256 || $certificate->certificate_md5))
                                <div class="border-t border-white/10 pt-4 mt-4">
                                    <h3 class="text-white/70 text-sm font-bold mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        File Integrity (Integritas File)
                                    </h3>

                                    <div class="space-y-3">
                                        {{-- PDF Hashes --}}
                                        @if($certificate->certificate_sha256)
                                            <div class="bg-cyan-500/10 rounded-xl p-3">
                                                <p class="text-cyan-400 text-xs font-semibold mb-2 flex items-center gap-1"><svg
                                                        class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg> PDF File</p>
                                                <div class="space-y-2">
                                                    <div class="flex flex-col">
                                                        <span class="text-white/50 text-xs">SHA256:</span>
                                                        <span
                                                            class="text-cyan-300 text-xs font-mono break-all">{{ $certificate->certificate_sha256 }}</span>
                                                    </div>
                                                    @if($certificate->certificate_md5)
                                                        <div class="flex flex-col">
                                                            <span class="text-white/50 text-xs">MD5:</span>
                                                            <span
                                                                class="text-cyan-300 text-xs font-mono break-all">{{ $certificate->certificate_md5 }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        {{-- QR Code Hashes --}}
                                        @if($certificate->qr_sha256)
                                            <div class="bg-green-500/10 rounded-xl p-3">
                                                <p class="text-green-400 text-xs font-semibold mb-2 flex items-center gap-1"><svg
                                                        class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                                    </svg> QR Code</p>
                                                <div class="space-y-2">
                                                    <div class="flex flex-col">
                                                        <span class="text-white/50 text-xs">SHA256:</span>
                                                        <span
                                                            class="text-green-300 text-xs font-mono break-all">{{ $certificate->qr_sha256 }}</span>
                                                    </div>
                                                    @if($certificate->qr_md5)
                                                        <div class="flex flex-col">
                                                            <span class="text-white/50 text-xs">MD5:</span>
                                                            <span
                                                                class="text-green-300 text-xs font-mono break-all">{{ $certificate->qr_md5 }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Template Hashes --}}
                                        @if($certificate->template && $certificate->template->sha256)
                                            <div class="bg-purple-500/10 rounded-xl p-3">
                                                <p class="text-purple-400 text-xs font-semibold mb-2 flex items-center gap-1"><svg
                                                        class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                                    </svg> Template</p>
                                                <div class="space-y-2">
                                                    <div class="flex flex-col">
                                                        <span class="text-white/50 text-xs">SHA256:</span>
                                                        <span
                                                            class="text-purple-300 text-xs font-mono break-all">{{ $certificate->template->sha256 }}</span>
                                                    </div>
                                                    @if($certificate->template->md5)
                                                        <div class="flex flex-col">
                                                            <span class="text-white/50 text-xs">MD5:</span>
                                                            <span
                                                                class="text-purple-300 text-xs font-mono break-all">{{ $certificate->template->md5 }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Blockchain Details --}}
                            <div class="border-t border-white/10 pt-4 mt-4">
                                <h3 class="text-white/70 text-sm font-bold mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                    Detail Blockchain
                                </h3>

                                <div class="space-y-3">
                                    @if(isset($onChainData['hash']))
                                        <div class="flex items-start gap-3 bg-white/5 rounded-xl p-3">
                                            <span class="text-white/50 text-xs w-24 flex-shrink-0">Data Hash:</span>
                                            <span
                                                class="text-purple-400 text-xs font-mono break-all">{{ $onChainData['hash'] }}</span>
                                        </div>
                                    @endif

                                    @if(isset($onChainData['issuerAddress']))
                                        <div class="flex items-start gap-3 bg-white/5 rounded-xl p-3">
                                            <span class="text-white/50 text-xs w-24 flex-shrink-0">Issuer Wallet:</span>
                                            <a href="{{ config('blockchain.explorer_url') }}/address/{{ $onChainData['issuerAddress'] }}"
                                                target="_blank" class="text-blue-400 text-xs font-mono break-all hover:underline">
                                                {{ $onChainData['issuerAddress'] }}
                                            </a>
                                        </div>
                                    @endif

                                    @if(($certificate ?? false) && $certificate->blockchain_tx_hash)
                                        <div class="flex items-start gap-3 bg-white/5 rounded-xl p-3">
                                            <span class="text-white/50 text-xs w-24 flex-shrink-0">TX Hash:</span>
                                            <a href="{{ config('blockchain.explorer_url') }}/tx/{{ $certificate->blockchain_tx_hash }}"
                                                target="_blank"
                                                class="text-blue-400 text-xs font-mono break-all hover:underline flex items-center gap-1">
                                                {{ $certificate->blockchain_tx_hash }}
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex flex-wrap gap-3 pt-4">
                                @if(($certificate ?? false) && $certificate->blockchain_tx_hash)
                                    <a href="{{ config('blockchain.explorer_url') }}/tx/{{ $certificate->blockchain_tx_hash }}"
                                        target="_blank"
                                        class="px-4 py-2 bg-purple-500/20 border border-purple-500/30 text-purple-400 rounded-lg text-sm font-medium hover:bg-purple-500/30 transition flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        Lihat di PolygonScan
                                    </a>
                                @endif

                                @if(($certificate ?? false) && $certificate->ipfs_cid)
                                    <a href="{{ $certificate->ipfs_url ?? 'https://gateway.pinata.cloud/ipfs/' . $certificate->ipfs_cid }}"
                                        target="_blank"
                                        class="px-4 py-2 bg-cyan-500/20 border border-cyan-500/30 text-cyan-400 rounded-lg text-sm font-medium hover:bg-cyan-500/30 transition flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                        </svg>
                                        Lihat di IPFS
                                    </a>
                                @endif

                                @if(($certificate ?? false) && $certificate->id)
                                    <a href="{{ route('verifikasi.show', $certificate->hash ?? $certificate->certificate_number) }}"
                                        class="px-4 py-2 bg-blue-500/20 border border-blue-500/30 text-blue-400 rounded-lg text-sm font-medium hover:bg-blue-500/30 transition flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Lihat Detail Sertifikat
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                @elseif(($result ?? '') === 'not_on_chain' && ($certificate ?? false))
                    {{-- Certificate exists but not on blockchain --}}
                    <div
                        class="rounded-[16px] overflow-hidden border-2 border-yellow-500/50 bg-[rgba(15,23,42,0.9)]">
                        <div class="p-4 bg-yellow-500/20 border-b border-yellow-500/30 flex items-center gap-3">
                            <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-yellow-400 font-bold text-lg">Tidak Ditemukan di Blockchain</h2>
                                <p class="text-yellow-300/70 text-sm">Sertifikat ada di database tapi belum terverifikasi di
                                    blockchain</p>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <p class="text-white/70 mb-4">Sertifikat <strong>{{ $certificate->certificate_number }}</strong>
                                ditemukan di database tetapi hash-nya tidak tersimpan di blockchain.</p>
                            <p class="text-white/50 text-sm">Status: {{ $certificate->blockchain_status ?? 'confirmed' }}</p>

                            {{-- Certificate Details --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div class="bg-white/5 rounded-xl p-4">
                                    <p class="text-white/50 text-xs mb-1">Nomor Sertifikat</p>
                                    <p class="text-white font-bold">{{ $certificate->certificate_number }}</p>
                                </div>
                                @if($certificate->recipient_name)
                                    <div class="bg-white/5 rounded-xl p-4">
                                        <p class="text-white/50 text-xs mb-1">Nama Penerima</p>
                                        <p class="text-white font-bold">{{ $certificate->recipient_name }}</p>
                                    </div>
                                @endif
                                @if($certificate->course_name)
                                    <div class="bg-white/5 rounded-xl p-4">
                                        <p class="text-white/50 text-xs mb-1">Nama Kursus</p>
                                        <p class="text-white font-bold">{{ $certificate->course_name }}</p>
                                    </div>
                                @endif
                                @if($certificate->issue_date)
                                    <div class="bg-white/5 rounded-xl p-4">
                                        <p class="text-white/50 text-xs mb-1">Tanggal Terbit</p>
                                        <p class="text-white font-bold">{{ \Carbon\Carbon::parse($certificate->issue_date)->format('d F Y') }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Action Button --}}
                            @if(($certificate->id ?? false) && ($certificate->hash ?? false))
                                <div class="flex flex-wrap gap-3 pt-4">
                                    <a href="{{ route('verifikasi.show', $certificate->hash) }}"
                                        class="px-4 py-2 bg-blue-500/20 border border-blue-500/30 text-blue-400 rounded-lg text-sm font-medium hover:bg-blue-500/30 transition flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Lihat Detail Sertifikat
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                @elseif(($result ?? '') === 'no_blockchain' && ($certificate ?? false))
                    {{-- Certificate without blockchain --}}
                    <div
                        class="rounded-[16px] overflow-hidden border-2 border-gray-500/50 bg-[rgba(15,23,42,0.9)]">
                        <div class="p-4 bg-gray-500/20 border-b border-gray-500/30 flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-gray-300 font-bold text-lg">Sertifikat Tanpa Blockchain</h2>
                                <p class="text-gray-400 text-sm">Sertifikat ini tidak menggunakan verifikasi blockchain</p>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-white/70">Sertifikat <strong>{{ $certificate->certificate_number }}</strong> valid
                                tetapi tidak disimpan di blockchain.</p>
                        </div>
                    </div>

                @else
                    {{-- Not Found --}}
                    <div
                        class="rounded-[16px] overflow-hidden border-2 border-red-500/50 bg-[rgba(15,23,42,0.9)]">
                        <div class="p-4 bg-red-500/20 border-b border-red-500/30 flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-red-400 font-bold text-lg">Tidak Ditemukan</h2>
                                <p class="text-red-300/70 text-sm">Sertifikat tidak ditemukan di database maupun blockchain</p>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-white/70">Query <strong class="font-mono">{{ $query }}</strong> tidak cocok dengan
                                sertifikat manapun.</p>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Contract Info --}}
        @if(isset($walletInfo) && $walletInfo)
            <div class="rounded-[16px] border border-[rgba(255,255,255,0.14)]
                                   bg-[rgba(15,23,42,0.9)]
                                   p-6 animate-fade-in-up stagger-2">
                <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    Informasi Smart Contract
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Row 1: Jaringan (Full Width) --}}
                    <div class="bg-white/5 rounded-xl p-4 text-center md:col-span-2">
                        <p class="text-white/50 text-xs mb-1">Jaringan (On-Chain)</p>
                        <p class="text-white font-bold">{{ $walletInfo['network'] ?? 'Polygon Amoy' }}</p>
                    </div>

                    {{-- Row 2: Wallet & Total Transactions --}}
                    <div class="bg-white/5 rounded-xl p-4 text-center">
                         <p class="text-white/50 text-xs mb-1">Wallet Address</p>
                         <a href="{{ $walletInfo['explorer_url'] ?? 'https://amoy.polygonscan.com' }}/address/{{ $walletInfo['wallet_address'] ?? '' }}"
                            target="_blank" class="text-blue-400 text-xs font-mono hover:underline">
                            {{ Str::limit($walletInfo['wallet_address'] ?? 'N/A', 15) }}...{{ substr($walletInfo['wallet_address'] ?? '', -4) }}
                        </a>
                    </div>

                    @if(isset($totalBlockchainTransactions))
                        <div class="bg-white/5 rounded-xl p-4 text-center">
                            <p class="text-white/50 text-xs mb-1">Total Transaksi Blockchain</p>
                            <p class="text-purple-400 font-bold text-2xl">
                                {{ number_format($totalBlockchainTransactions) }}
                            </p>
                        </div>
                    @endif

                    {{-- Row 3: Smart Contract & Total Onchain --}}
                    <div class="bg-white/5 rounded-xl p-4 text-center">
                        <p class="text-white/50 text-xs mb-1">Contract Address</p>
                        <a href="{{ $walletInfo['explorer_url'] ?? 'https://amoy.polygonscan.com' }}/address/{{ config('blockchain.contract_address') }}"
                            target="_blank" class="text-blue-400 text-xs font-mono hover:underline">
                            {{ Str::limit(config('blockchain.contract_address'), 15) }}...{{ substr(config('blockchain.contract_address'), -4) }}
                        </a>
                    </div>

                    @if(isset($contractStats['totalCertificates']))
                        <div class="bg-white/5 rounded-xl p-4 text-center">
                            <p class="text-white/50 text-xs mb-1">Total Sertifikat On-Chain</p>
                            <p class="text-purple-400 font-bold text-2xl">
                                {{ number_format($contractStats['totalCertificates']) }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Back to Verification --}}
        <div class="text-center">
            <a href="{{ route('verifikasi') }}"
                class="inline-flex items-center gap-2 text-[#BEDBFF] hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Verifikasi Sertifikat
            </a>
        </div>
    </section>

</x-layouts.app>
