{{-- resources/views/admin/blockchain.blade.php --}}
<x-layouts.admin title="Blockchain Wallet – Admin SertiKu">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 animate-fade-in-up">
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-white">Blockchain Wallet</h1>
            <p class="text-white/60 mt-1">Kelola dan pantau wallet blockchain SertiKu</p>
        </div>
        <div class="mt-4 lg:mt-0 flex gap-3">
            <a href="{{ $walletInfo['explorer_url'] }}/address/{{ $walletInfo['wallet_address'] }}"
               target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-purple-500/20 text-purple-400 text-sm hover:bg-purple-500/30 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Lihat di PolygonScan
            </a>
        </div>
    </div>

    {{-- Wallet Status Card --}}
    <div class="glass-card rounded-2xl p-6 mb-6 animate-fade-in-up">
        <div class="flex flex-col lg:flex-row lg:items-center gap-6">
            {{-- Wallet Icon --}}
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>

            {{-- Wallet Info --}}
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-xl font-bold text-white">SertiKu Wallet</h2>
                    @if($walletInfo['enabled'])
                    <span class="px-2 py-1 rounded-full bg-green-500/20 text-green-400 text-xs font-medium">
                        ● Terhubung
                    </span>
                    @else
                    <span class="px-2 py-1 rounded-full bg-red-500/20 text-red-400 text-xs font-medium">
                        ● Tidak Aktif
                    </span>
                    @endif
                </div>
                <p class="text-white/50 text-sm font-mono">{{ $walletInfo['wallet_address'] ?: 'Belum dikonfigurasi' }}</p>
                <p class="text-white/40 text-xs mt-1">{{ $walletInfo['network'] }}</p>
            </div>

            {{-- Balance --}}
            <div class="text-right">
                <p class="text-white/50 text-sm">Saldo</p>
                <p class="text-3xl font-bold text-white">
                    {{ $walletInfo['balance']['formatted'] ?? '0 MATIC' }}
                </p>
                @if($walletInfo['balance'])
                <p class="text-white/40 text-xs mt-1">
                    ≈ Rp {{ number_format($walletInfo['balance']['matic'] * 10000, 0, ',', '.') }}
                </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Transactions --}}
        <div class="glass-card rounded-xl p-5 animate-fade-in-up">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/50 text-sm">Total Transaksi</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $walletInfo['transaction_count'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Blockchain Certificates --}}
        <div class="glass-card rounded-xl p-5 animate-fade-in-up">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/50 text-sm">Di Blockchain</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $blockchainStats['total_blockchain'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Remaining Capacity --}}
        <div class="glass-card rounded-xl p-5 animate-fade-in-up">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/50 text-sm">Estimasi Sisa</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ number_format($walletInfo['remaining_certs']) }}</p>
                    <p class="text-white/40 text-xs">sertifikat</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Failed Transactions --}}
        <div class="glass-card rounded-xl p-5 animate-fade-in-up">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/50 text-sm">Gagal</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $blockchainStats['failed'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-red-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Low Balance Warning --}}
    @if($walletInfo['balance'] && $walletInfo['balance']['matic'] < 0.1)
    <div class="mb-6 p-4 rounded-xl bg-yellow-500/20 border border-yellow-500/30 animate-fade-in-up">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="text-yellow-400 font-medium">Saldo Rendah!</p>
                <p class="text-yellow-300/70 text-sm mt-1">
                    Saldo wallet Anda kurang dari 0.1 MATIC. Segera top up untuk melanjutkan penerbitan sertifikat blockchain.
                </p>
                <a href="https://faucet.stakepool.dev.br/amoy" target="_blank"
                   class="inline-flex items-center gap-1 text-yellow-400 text-sm mt-2 hover:underline">
                    Dapatkan Testnet MATIC (Faucet)
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Configuration Info --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Network Config --}}
        <div class="glass-card rounded-2xl p-5 animate-fade-in-up">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Konfigurasi Network
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-white/10">
                    <span class="text-white/60 text-sm">Network</span>
                    <span class="text-white font-medium text-sm">{{ $walletInfo['network'] }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-white/10">
                    <span class="text-white/60 text-sm">RPC URL</span>
                    <span class="text-white/80 text-xs font-mono truncate max-w-[200px]">{{ $walletInfo['rpc_url'] }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-white/10">
                    <span class="text-white/60 text-sm">Explorer</span>
                    <a href="{{ $walletInfo['explorer_url'] }}" target="_blank" class="text-blue-400 text-sm hover:underline">
                        {{ str_replace('https://', '', $walletInfo['explorer_url']) }}
                    </a>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-white/60 text-sm">Status</span>
                    @if($walletInfo['enabled'])
                    <span class="text-green-400 text-sm">● Aktif</span>
                    @else
                    <span class="text-red-400 text-sm">● Tidak Aktif</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="glass-card rounded-2xl p-5 animate-fade-in-up">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Aksi Cepat
            </h3>
            <div class="space-y-3">
                <a href="{{ $walletInfo['explorer_url'] }}/address/{{ $walletInfo['wallet_address'] }}"
                   target="_blank"
                   class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 transition">
                    <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-medium text-sm">Lihat Riwayat Transaksi</p>
                        <p class="text-white/50 text-xs">PolygonScan Explorer</p>
                    </div>
                </a>
                <a href="https://faucet.stakepool.dev.br/amoy"
                   target="_blank"
                   class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 transition">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-medium text-sm">Dapatkan Testnet MATIC</p>
                        <p class="text-white/50 text-xs">Polygon Faucet</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- Recent Blockchain Transactions --}}
    <div class="glass-card rounded-2xl p-5 animate-fade-in-up">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Transaksi Blockchain Terbaru
        </h3>

        @if($recentBlockchainTx->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-left text-white/50 text-xs font-medium py-3 px-2">Nomor Sertifikat</th>
                        <th class="text-left text-white/50 text-xs font-medium py-3 px-2">Penerima</th>
                        <th class="text-left text-white/50 text-xs font-medium py-3 px-2">TX Hash</th>
                        <th class="text-left text-white/50 text-xs font-medium py-3 px-2">Status</th>
                        <th class="text-left text-white/50 text-xs font-medium py-3 px-2">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentBlockchainTx as $cert)
                    <tr class="border-b border-white/5 hover:bg-white/5">
                        <td class="py-3 px-2">
                            <span class="text-white text-sm font-mono">{{ $cert->certificate_number }}</span>
                        </td>
                        <td class="py-3 px-2">
                            <span class="text-white/80 text-sm">{{ $cert->recipient_name }}</span>
                        </td>
                        <td class="py-3 px-2">
                            <a href="{{ $walletInfo['explorer_url'] }}/tx/{{ $cert->blockchain_tx_hash }}"
                               target="_blank"
                               class="text-purple-400 text-xs font-mono hover:underline">
                                {{ substr($cert->blockchain_tx_hash, 0, 10) }}...{{ substr($cert->blockchain_tx_hash, -6) }}
                            </a>
                        </td>
                        <td class="py-3 px-2">
                            @if($cert->blockchain_status === 'confirmed')
                            <span class="px-2 py-1 rounded-full bg-green-500/20 text-green-400 text-xs">Confirmed</span>
                            @elseif($cert->blockchain_status === 'pending')
                            <span class="px-2 py-1 rounded-full bg-yellow-500/20 text-yellow-400 text-xs">Pending</span>
                            @else
                            <span class="px-2 py-1 rounded-full bg-red-500/20 text-red-400 text-xs">Failed</span>
                            @endif
                        </td>
                        <td class="py-3 px-2">
                            <span class="text-white/50 text-xs">{{ $cert->created_at->format('d M Y H:i') }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-white/5 flex items-center justify-center">
                <svg class="w-8 h-8 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            <p class="text-white/50">Belum ada sertifikat blockchain</p>
            <p class="text-white/30 text-sm mt-1">Terbitkan sertifikat dengan opsi blockchain untuk melihat di sini</p>
        </div>
        @endif
    </div>

</x-layouts.admin>
