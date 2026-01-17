{{-- resources/views/master/settings.blade.php --}}
<x-layouts.master title="Pengaturan Sistem – Master SertiKu">

    {{-- Header --}}
    <div class="mb-6 animate-fade-in-up">
        <h1 class="text-2xl lg:text-3xl font-bold text-white">Pengaturan Sistem</h1>
        <p class="text-white/60 mt-1">Kelola konfigurasi sistem SertiKu</p>
    </div>

    @if(session('success'))
        <div
            class="mb-6 p-4 rounded-xl bg-green-500/20 border border-green-500/30 text-green-400 text-sm animate-fade-in-up">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- General Settings --}}
        <div class="glass-card rounded-2xl p-6 animate-fade-in-up">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Pengaturan Umum
            </h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-white/70 text-sm mb-2">Nama Aplikasi</label>
                    <input type="text" value="SertiKu" disabled
                        class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white/50 text-sm">
                </div>
                <div>
                    <label class="block text-white/70 text-sm mb-2">URL Aplikasi</label>
                    <input type="text" value="{{ config('app.url') }}" disabled
                        class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white/50 text-sm">
                </div>
                <div>
                    <label class="block text-white/70 text-sm mb-2">Environment</label>
                    <span class="inline-flex px-3 py-1 rounded-lg text-sm
                        @if(config('app.env') === 'production') bg-green-500/20 text-green-400
                        @elseif(config('app.env') === 'staging') bg-yellow-500/20 text-yellow-400
                        @else bg-blue-500/20 text-blue-400 @endif">
                        {{ ucfirst(config('app.env')) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Blockchain Settings --}}
        <div class="glass-card rounded-2xl p-6 animate-fade-in-up">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                Blockchain
            </h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between py-2 border-b border-white/10">
                    <span class="text-white/70 text-sm">Status</span>
                    @if(config('blockchain.enabled'))
                        <span class="px-2 py-1 rounded-lg bg-green-500/20 text-green-400 text-sm">Aktif</span>
                    @else
                        <span class="px-2 py-1 rounded-lg bg-red-500/20 text-red-400 text-sm">Tidak Aktif</span>
                    @endif
                </div>
                <div class="flex items-center justify-between py-2 border-b border-white/10">
                    <span class="text-white/70 text-sm">Network</span>
                    <span
                        class="text-white text-sm">{{ config('blockchain.chain_id') == '80002' ? 'Polygon Amoy (Testnet)' : 'Polygon Mainnet' }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-white/70 text-sm">Wallet</span>
                    <span class="text-white/50 text-xs font-mono" title="{{ config('blockchain.wallet_address') }}">
                        @if(config('blockchain.wallet_address'))
                            {{ substr(config('blockchain.wallet_address'), 0, 6) }}...{{ substr(config('blockchain.wallet_address'), -4) }}
                        @else
                            Belum dikonfigurasi
                        @endif
                    </span>
                </div>
                <a href="{{ route('master.blockchain') }}"
                    class="block text-center mt-4 px-4 py-2 rounded-xl bg-blue-500/20 text-blue-400 text-sm hover:bg-blue-500/30 transition">
                    Kelola Blockchain Wallet →
                </a>
            </div>
        </div>

        {{-- Email Settings --}}
        <div class="glass-card rounded-2xl p-6 animate-fade-in-up">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Email
            </h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between py-2 border-b border-white/10">
                    <span class="text-white/70 text-sm">Driver</span>
                    <span class="text-white text-sm">{{ ucfirst(config('mail.default')) }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-white/10">
                    <span class="text-white/70 text-sm">From Address</span>
                    <span
                        class="text-white/50 text-sm">{{ config('mail.from.address') ?: 'Belum dikonfigurasi' }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-white/70 text-sm">From Name</span>
                    <span class="text-white text-sm">{{ config('mail.from.name') ?: 'SertiKu' }}</span>
                </div>
            </div>
        </div>

        {{-- Storage Info --}}
        <div class="glass-card rounded-2xl p-6 animate-fade-in-up">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                </svg>
                Penyimpanan
            </h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between py-2 border-b border-white/10">
                    <span class="text-white/70 text-sm">Default Disk</span>
                    <span class="text-white text-sm">{{ ucfirst(config('filesystems.default')) }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-white/10">
                    <span class="text-white/70 text-sm">Total Sertifikat</span>
                    <span class="text-white text-sm">{{ \App\Models\Certificate::count() }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-white/70 text-sm">Total User</span>
                    <span class="text-white text-sm">{{ \App\Models\User::count() }}</span>
                </div>
            </div>
        </div>

        {{-- System Status --}}
        <div class="glass-card rounded-2xl p-6 animate-fade-in-up lg:col-span-2">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Status Sistem
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-center">
                    <p class="text-green-400 text-2xl font-bold"></p>
                    <p class="text-white/70 text-sm mt-1">Database</p>
                </div>
                <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-center">
                    <p class="text-green-400 text-2xl font-bold"></p>
                    <p class="text-white/70 text-sm mt-1">Cache</p>
                </div>
                <div
                    class="p-4 rounded-xl bg-{{ config('blockchain.enabled') ? 'green' : 'yellow' }}-500/10 border border-{{ config('blockchain.enabled') ? 'green' : 'yellow' }}-500/20 text-center">
                    <p class="text-{{ config('blockchain.enabled') ? 'green' : 'yellow' }}-400 text-2xl font-bold">
                        {{ config('blockchain.enabled') ? '' : '!' }}
                    </p>
                    <p class="text-white/70 text-sm mt-1">Blockchain</p>
                </div>
                <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-center">
                    <p class="text-green-400 text-2xl font-bold"></p>
                    <p class="text-white/70 text-sm mt-1">Storage</p>
                </div>
            </div>
            <p class="text-white/40 text-xs mt-4 text-center">
                Laravel v{{ app()->version() }} • PHP {{ phpversion() }}
            </p>
        </div>
    </div>

</x-layouts.master>