<x-layouts.lembaga>
    <div class="space-y-6">
        <!-- Header -->
        <div class="info-box rounded-2xl p-6">
            <h1 class="text-white text-xl lg:text-2xl font-bold mb-2">Daftar Sertifikat Terbit</h1>
            <p class="text-white/70 text-sm lg:text-base">Kelola semua sertifikat yang telah diterbitkan oleh lembaga
                Anda</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500/30 rounded-lg p-4 text-emerald-400">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4 text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-blue-500/20 border border-blue-500/30 rounded-xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-500/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <div>
                    <p class="text-white text-xl font-bold">{{ $stats['total'] ?? 0 }}</p>
                    <p class="text-white/60 text-xs">Total</p>
                </div>
            </div>
            <div class="bg-emerald-500/20 border border-emerald-500/30 rounded-xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-500/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-white text-xl font-bold">{{ $stats['active'] ?? 0 }}</p>
                    <p class="text-white/60 text-xs">Aktif</p>
                </div>
            </div>
            <div class="bg-red-500/20 border border-red-500/30 rounded-xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-red-500/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-white text-xl font-bold">{{ $stats['revoked'] ?? 0 }}</p>
                    <p class="text-white/60 text-xs">Dicabut</p>
                </div>
            </div>
        </div>

        <!-- Search & Action -->
        <div class="flex items-center gap-4">
            <div class="relative flex-1">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-white/40" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" placeholder="Cari berdasarkan nama, course, atau ID..."
                    class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/20 rounded-lg text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <a href="{{ route('lembaga.sertifikat.create') }}"
                class="flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg text-white text-sm font-bold hover:from-blue-700 hover:to-indigo-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Terbitkan Baru
            </a>
        </div>

        <!-- Certificate Grid -->
        @if(isset($certificates) && $certificates->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($certificates as $certificate)
                    <div class="glass-card rounded-2xl overflow-hidden hover:shadow-lg transition group">
                        <!-- Certificate Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 relative">
                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3">
                                @if($certificate->status === 'active')
                                    <span class="px-2 py-1 bg-emerald-500 text-white text-xs font-bold rounded">✓ Aktif</span>
                                @elseif($certificate->status === 'revoked')
                                    <span class="px-2 py-1 bg-red-500 text-white text-xs font-bold rounded">✕ Dicabut</span>
                                @else
                                    <span class="px-2 py-1 bg-amber-500 text-white text-xs font-bold rounded">Kadaluarsa</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-3">
                                @php
                                    // Check if recipient is registered in system
                                    $registeredUser = null;
                                    $avatarUrl = null;
                                    
                                    if ($certificate->recipient_email) {
                                        $registeredUser = \App\Models\User::where('email', $certificate->recipient_email)->first();
                                        
                                        if ($registeredUser && $registeredUser->avatar && str_starts_with($registeredUser->avatar, '/storage/')) {
                                            // User is registered and has custom avatar
                                            $avatarUrl = $registeredUser->avatar;
                                        } else {
                                            // Email exists but user not registered or no custom avatar - use UI Avatars
                                            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($certificate->recipient_name) . '&email=' . urlencode($certificate->recipient_email) . '&background=random&color=fff&bold=true';
                                        }
                                    }
                                @endphp
                                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center overflow-hidden">
                                    @if($avatarUrl)
                                        <img src="{{ $avatarUrl }}" alt="Avatar" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-white text-lg font-bold">{{ strtoupper(substr($certificate->recipient_name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-white font-bold truncate">{{ $certificate->recipient_name }}</h3>
                                    <p class="text-white/70 text-sm truncate">{{ $certificate->recipient_email ?? 'No email' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Certificate Body -->
                        <div class="p-4 space-y-3">
                            <div>
                                <p class="text-gray-500 text-xs">Program/Kursus</p>
                                <p class="text-gray-800 font-medium truncate">{{ $certificate->course_name }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-gray-500 text-xs">Nomor</p>
                                    <p class="text-gray-700 text-sm font-mono">
                                        {{ Str::limit($certificate->certificate_number, 15) }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Tanggal</p>
                                    <p class="text-gray-700 text-sm">{{ $certificate->issue_date->format('d M Y') }}</p>
                                </div>
                            </div>

                            {{-- Category Badge --}}
                            @if($certificate->category)
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span
                                        class="px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded">{{ $certificate->category }}</span>
                                </div>
                            @endif

                            {{-- Blockchain Badge - Always show if blockchain_enabled or has tx_hash --}}
                            @if($certificate->blockchain_enabled || $certificate->blockchain_tx_hash)
                                <div class="flex items-center gap-2 mt-2">
                                    @if($certificate->blockchain_tx_hash)
                                        <a href="{{ $certificate->blockchain_explorer_url }}" target="_blank"
                                            class="flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-purple-500/20 to-indigo-500/20 border border-purple-500/30 text-purple-600 text-xs font-medium rounded-lg hover:from-purple-500/30 hover:to-indigo-500/30 transition group">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                            <span>Tersimpan di Blockchain</span>
                                            <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @elseif($certificate->blockchain_status === 'pending')
                                        <span
                                            class="flex items-center gap-1.5 px-3 py-1.5 bg-yellow-500/20 border border-yellow-500/30 text-yellow-600 text-xs font-medium rounded-lg">
                                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                            </svg>
                                            <span>Menunggu Blockchain...</span>
                                        </span>
                                    @elseif($certificate->blockchain_status === 'failed')
                                        <span
                                            class="flex items-center gap-1.5 px-3 py-1.5 bg-red-500/20 border border-red-500/30 text-red-600 text-xs font-medium rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Gagal Upload</span>
                                        </span>
                                    @endif
                                </div>
                            @endif

                            {{-- IPFS Badge - Show if ipfs_cid exists --}}
                            @if($certificate->ipfs_cid)
                                <div class="flex items-center gap-2 mt-2">
                                    <a href="{{ config('ipfs.gateway_url', 'https://gateway.pinata.cloud/ipfs') }}/{{ $certificate->ipfs_cid }}"
                                        target="_blank"
                                        class="flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-teal-500/20 to-cyan-500/20 border border-teal-500/30 text-teal-600 text-xs font-medium rounded-lg hover:from-teal-500/30 hover:to-cyan-500/30 transition group">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                        </svg>
                                        <span>Tersimpan di IPFS</span>
                                        <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Certificate Actions -->
                        <div class="p-4 pt-0 flex items-center gap-2">
                            <a href="{{ route('verifikasi.show', $certificate->hash) }}" target="_blank"
                                class="flex-1 flex items-center justify-center gap-1 py-2 bg-gray-100 rounded-lg text-gray-700 text-xs font-medium hover:bg-gray-200 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat
                            </a>
                            @if($certificate->status === 'active')
                                <form action="{{ route('lembaga.sertifikat.revoke', $certificate) }}" method="POST" class="flex-1"
                                    onsubmit="return confirm('Cabut sertifikat ini?')">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center justify-center gap-1 py-2 bg-red-100 rounded-lg text-red-600 text-xs font-medium hover:bg-red-200 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                        Cabut
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $certificates->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="glass-card rounded-2xl p-12 text-center">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <h3 class="text-gray-800 text-xl font-bold mb-2">Belum Ada Sertifikat</h3>
                <p class="text-gray-500 mb-6">Mulai terbitkan sertifikat digital pertama Anda</p>
                <a href="{{ route('lembaga.sertifikat.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Terbitkan Sertifikat
                </a>
            </div>
        @endif
    </div>
</x-layouts.lembaga>