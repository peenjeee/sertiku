<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Blockchain - SertiKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #0F172A 0%, #1E293B 50%, #0F172A 100%);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .chain-icon {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>
<body class="text-white">
    <!-- Header -->
    <header class="py-6 px-4 border-b border-white/10">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold">SertiKu</span>
            </a>
            <span class="px-3 py-1 bg-purple-500/20 text-purple-400 text-sm font-medium rounded-full flex items-center gap-2">
                <svg class="w-4 h-4 chain-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                Blockchain Verification
            </span>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8">
        <!-- Title -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2">Verifikasi Sertifikat Blockchain</h1>
            <p class="text-white/60">Cek keaslian sertifikat yang tersimpan di jaringan Polygon</p>
        </div>

        <!-- Search Form -->
        <div class="glass-card rounded-2xl p-6 mb-8">
            <form action="{{ route('blockchain.verify') }}" method="GET" class="flex gap-3">
                <div class="flex-1 relative">
                    <input type="text" name="q" value="{{ $query ?? '' }}"
                        placeholder="Masukkan nomor sertifikat atau hash blockchain..."
                        class="w-full px-4 py-4 pl-12 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/40 focus:outline-none focus:border-purple-500 transition">
                    <svg class="w-5 h-5 text-white/40 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button type="submit" class="px-6 py-4 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl font-bold hover:opacity-90 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Verifikasi
                </button>
            </form>
        </div>

        @if($query)
        <!-- Result Section -->
        <div class="space-y-6">
            @if($result === 'found' && $onChainData)
            <!-- Success: Found on Blockchain -->
            <div class="glass-card rounded-2xl overflow-hidden border-2 border-green-500/50">
                <div class="p-4 bg-green-500/20 border-b border-green-500/30 flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-green-400 font-bold text-lg">Terverifikasi di Blockchain</h2>
                        <p class="text-green-300/70 text-sm">Sertifikat ini tersimpan permanen di jaringan Polygon</p>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <!-- On-Chain Data -->
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
                        <div class="bg-white/5 rounded-xl p-4">
                            <p class="text-white/50 text-xs mb-1">Penerbit</p>
                            <p class="text-white font-bold">{{ $onChainData['issuerName'] }}</p>
                        </div>
                        @endif

                        @if(isset($onChainData['timestamp']))
                        <div class="bg-white/5 rounded-xl p-4">
                            <p class="text-white/50 text-xs mb-1">Waktu Penyimpanan Blockchain</p>
                            <p class="text-white font-bold">{{ isset($onChainData['date']) ? $onChainData['date'] : date('Y-m-d H:i:s', $onChainData['timestamp']) }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Blockchain Details -->
                    <div class="border-t border-white/10 pt-4 mt-4">
                        <h3 class="text-white/70 text-sm font-bold mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            Detail Blockchain
                        </h3>

                        <div class="space-y-3">
                            @if(isset($onChainData['hash']))
                            <div class="flex items-start gap-3 bg-white/5 rounded-xl p-3">
                                <span class="text-white/50 text-xs w-24 flex-shrink-0">Data Hash:</span>
                                <span class="text-purple-400 text-xs font-mono break-all">{{ $onChainData['hash'] }}</span>
                            </div>
                            @endif

                            @if(isset($onChainData['issuerAddress']))
                            <div class="flex items-start gap-3 bg-white/5 rounded-xl p-3">
                                <span class="text-white/50 text-xs w-24 flex-shrink-0">Issuer Wallet:</span>
                                <a href="{{ config('blockchain.explorer_url') }}/address/{{ $onChainData['issuerAddress'] }}" target="_blank"
                                    class="text-blue-400 text-xs font-mono break-all hover:underline">
                                    {{ $onChainData['issuerAddress'] }}
                                </a>
                            </div>
                            @endif

                            @if($certificate && $certificate->blockchain_tx_hash)
                            <div class="flex items-start gap-3 bg-white/5 rounded-xl p-3">
                                <span class="text-white/50 text-xs w-24 flex-shrink-0">TX Hash:</span>
                                <a href="{{ config('blockchain.explorer_url') }}/tx/{{ $certificate->blockchain_tx_hash }}" target="_blank"
                                    class="text-blue-400 text-xs font-mono break-all hover:underline flex items-center gap-1">
                                    {{ $certificate->blockchain_tx_hash }}
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 pt-4">
                        @if($certificate && $certificate->blockchain_tx_hash)
                        <a href="{{ config('blockchain.explorer_url') }}/tx/{{ $certificate->blockchain_tx_hash }}" target="_blank"
                            class="px-4 py-2 bg-purple-500/20 border border-purple-500/30 text-purple-400 rounded-lg text-sm font-medium hover:bg-purple-500/30 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Lihat di PolygonScan
                        </a>
                        @endif

                        @if($certificate)
                        <a href="{{ route('verify') }}?q={{ $certificate->certificate_number }}"
                            class="px-4 py-2 bg-blue-500/20 border border-blue-500/30 text-blue-400 rounded-lg text-sm font-medium hover:bg-blue-500/30 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Lihat Detail Sertifikat
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            @elseif($result === 'not_on_chain' && $certificate)
            <!-- Certificate exists but not on blockchain -->
            <div class="glass-card rounded-2xl overflow-hidden border-2 border-yellow-500/50">
                <div class="p-4 bg-yellow-500/20 border-b border-yellow-500/30 flex items-center gap-3">
                    <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-yellow-400 font-bold text-lg">Tidak Ditemukan di Blockchain</h2>
                        <p class="text-yellow-300/70 text-sm">Sertifikat ada di database tapi belum terverifikasi di blockchain</p>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-white/70 mb-4">Sertifikat <strong>{{ $certificate->certificate_number }}</strong> ditemukan di database tetapi hash-nya tidak tersimpan di blockchain.</p>
                    <p class="text-white/50 text-sm">Status: {{ $certificate->blockchain_status ?? 'Tidak ada' }}</p>
                </div>
            </div>

            @elseif($result === 'no_blockchain' && $certificate)
            <!-- Certificate without blockchain -->
            <div class="glass-card rounded-2xl overflow-hidden border-2 border-gray-500/50">
                <div class="p-4 bg-gray-500/20 border-b border-gray-500/30 flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-gray-300 font-bold text-lg">Sertifikat Tanpa Blockchain</h2>
                        <p class="text-gray-400 text-sm">Sertifikat ini tidak menggunakan verifikasi blockchain</p>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-white/70">Sertifikat <strong>{{ $certificate->certificate_number }}</strong> valid tetapi tidak disimpan di blockchain.</p>
                </div>
            </div>

            @else
            <!-- Not Found -->
            <div class="glass-card rounded-2xl overflow-hidden border-2 border-red-500/50">
                <div class="p-4 bg-red-500/20 border-b border-red-500/30 flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-red-400 font-bold text-lg">Tidak Ditemukan</h2>
                        <p class="text-red-300/70 text-sm">Sertifikat tidak ditemukan di database maupun blockchain</p>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-white/70">Query <strong class="font-mono">{{ $query }}</strong> tidak cocok dengan sertifikat manapun.</p>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Contract Info -->
        @if(isset($walletInfo) && $walletInfo)
        <div class="glass-card rounded-2xl p-6 mt-8">
            <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                Informasi Smart Contract
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white/5 rounded-xl p-4 text-center">
                    <p class="text-white/50 text-xs mb-1">Jaringan</p>
                    <p class="text-white font-bold">{{ $walletInfo['network'] ?? 'Polygon Amoy' }}</p>
                </div>
                @if(isset($contractStats['totalCertificates']))
                <div class="bg-white/5 rounded-xl p-4 text-center">
                    <p class="text-white/50 text-xs mb-1">Total Sertifikat On-Chain</p>
                    <p class="text-purple-400 font-bold text-2xl">{{ number_format($contractStats['totalCertificates']) }}</p>
                </div>
                @endif
                <div class="bg-white/5 rounded-xl p-4 text-center">
                    <p class="text-white/50 text-xs mb-1">Contract Address</p>
                    <a href="{{ $walletInfo['explorer_url'] ?? 'https://amoy.polygonscan.com' }}/address/{{ config('blockchain.contract_address') }}"
                        target="_blank" class="text-blue-400 text-xs font-mono hover:underline">
                        {{ Str::limit(config('blockchain.contract_address'), 20) }}
                    </a>
                </div>
            </div>
        </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="py-6 px-4 border-t border-white/10 mt-8">
        <div class="max-w-4xl mx-auto text-center text-white/50 text-sm">
            <p>© {{ date('Y') }} SertiKu. Blockchain verification powered by Polygon.</p>
        </div>
    </footer>
</body>
</html>
