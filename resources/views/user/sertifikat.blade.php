{{-- resources/views/user/sertifikat.blade.php --}}
<x-layouts.user title="Sertifikat Saya – SertiKu">

    {{-- Header --}}
    <div class="mb-6 animate-fade-in-up pl-10 lg:pl-0">
        <h1
            class="text-2xl lg:text-3xl font-bold text-[#8EC5FF]">
            Sertifikat Saya
        </h1>
        <p class="text-white/60 mt-1">Lihat dan kelola semua sertifikat digital Anda</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-4 mb-6">
        {{-- Total Sertifikat --}}
        <div class="stat-card-blue rounded-xl lg:rounded-2xl p-3 sm:p-4 lg:p-5 animate-fade-in-up stagger-1">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-white/60 text-[10px] sm:text-xs lg:text-sm">Total Sertifikat</p>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-white mt-1">{{ $stats['total'] }}</p>
                </div>
                <div
                    class="hidden sm:flex w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-blue-500/20 items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Sertifikat Aktif --}}
        <div class="stat-card-green rounded-xl lg:rounded-2xl p-3 sm:p-4 lg:p-5 animate-fade-in-up stagger-2">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-white/60 text-[10px] sm:text-xs lg:text-sm">Sertifikat Aktif</p>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-white mt-1">{{ $stats['aktif'] }}</p>
                </div>
                <div
                    class="hidden sm:flex w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-green-500/20 items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Kadaluarsa --}}
        <div class="stat-card rounded-xl lg:rounded-2xl p-3 sm:p-4 lg:p-5 animate-fade-in-up stagger-3"
            style="background: rgba(217, 119, 6, 0.1); border: 1px solid rgba(217, 119, 6, 0.3);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-white/60 text-[10px] sm:text-xs lg:text-sm">Kadaluarsa</p>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-white mt-1">{{ $stats['kadaluarsa'] ?? 0 }}
                    </p>
                </div>
                <div
                    class="hidden sm:flex w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-amber-500/20 items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-amber-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Dicabut --}}
        <div class="stat-card rounded-xl lg:rounded-2xl p-3 sm:p-4 lg:p-5 animate-fade-in-up stagger-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-white/60 text-[10px] sm:text-xs lg:text-sm">Dicabut</p>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-white mt-1">{{ $stats['dicabut'] }}</p>
                </div>
                <div
                    class="hidden sm:flex w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-red-500/20 items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Search Section --}}
    <div class="glass-card rounded-2xl p-4 sm:p-5 lg:p-6 mb-6 animate-fade-in-up">
        <h2 class="text-white font-medium mb-4 text-sm sm:text-base">Cari Sertifikat berdasarkan Nama Kursus atau ID
        </h2>
        <form action="{{ route('user.sertifikat') }}" method="GET" class="space-y-3 sm:space-y-4">
            {{-- Search Input --}}
            <div class="relative">
                <svg class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-white/40"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full rounded-xl bg-white/5 border border-white/10 pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3 text-sm sm:text-base text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                    placeholder="Ketik untuk mencari...">
            </div>

            {{-- ID Input --}}
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <input type="text" name="id"
                    class="flex-1 rounded-xl bg-white/5 border border-white/10 px-4 py-2.5 sm:py-3 text-sm sm:text-base text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                    placeholder="Atau masukkan ID Sertifikat...">
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 sm:py-3 rounded-xl bg-[#3B82F6] text-white font-medium shadow-md shadow-blue-500/20 hover:bg-[#2563EB] transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Cari
                </button>
            </div>
        </form>
    </div>

    {{-- Certificates List --}}
    <div class="glass-card rounded-2xl p-5 lg:p-6 animate-fade-in-up">
        @if($certificates->count() > 0)
            <div class="space-y-4">
                @foreach($certificates as $cert)
                    <div
                        class="flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition">
                        <div
                            class="w-12 h-12 rounded-lg bg-[#3B82F6] flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-medium truncate">{{ $cert->course_name ?? 'Sertifikat' }}</p>
                            <p class="text-white/50 text-sm">{{ $cert->issuer->name ?? 'Lembaga' }} •
                                {{ $cert->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs flex-shrink-0
                                                        @if($cert->status === 'revoked') bg-red-500/20 text-red-400
                                                        @elseif($cert->expire_date && $cert->expire_date < now()) bg-amber-500/20 text-amber-400
                                                        @elseif($cert->status === 'active') bg-green-500/20 text-green-400
                                                        @else bg-gray-500/20 text-gray-400 @endif
                                                    ">
                            @if($cert->status === 'revoked') Dicabut
                            @elseif($cert->expire_date && $cert->expire_date < now()) Kadaluarsa
                            @elseif($cert->status === 'active') Aktif
                            @else {{ ucfirst($cert->status) }} @endif
                        </span>
                        <a href="{{ route('verifikasi.show', $cert->hash) }}"
                            class="text-blue-400 hover:text-blue-300 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                        @if($cert->pdf_url)
                            <a href="{{ $cert->pdf_url }}" target="_blank" class="text-green-400 hover:text-green-300 flex-shrink-0"
                                title="Download PDF">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $certificates->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-16">
                <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-white/5 flex items-center justify-center">
                    <svg class="w-10 h-10 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Belum ada sertifikat</h3>
                <p class="text-white/50">Sertifikat Anda akan muncul di sini setelah diterbitkan oleh lembaga</p>
            </div>
        @endif
    </div>

</x-layouts.user>
