{{-- resources/views/user/profil.blade.php --}}
<x-layouts.user title="Profil Saya – SertiKu">

    {{-- Header Banner --}}
    <div class="welcome-banner rounded-2xl lg:rounded-3xl p-5 lg:p-8 mb-6 animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl lg:text-2xl font-bold text-white">Profil Saya</h1>
                <p class="text-white/60 mt-1">Lihat pencapaian dan informasi akun Anda</p>
            </div>
            <a href="{{ route('user.profil.edit') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm hover:brightness-110 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Profil
            </a>
        </div>
    </div>

    {{-- Profile Card with Avatar --}}
    <div class="glass-card rounded-2xl p-5 lg:p-6 mb-6 animate-fade-in-up">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
            {{-- Avatar --}}
            <div
                class="w-20 h-20 lg:w-24 lg:h-24 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-3xl lg:text-4xl flex-shrink-0 overflow-hidden">
                @if($user->avatar && (str_starts_with($user->avatar, '/storage/') || str_starts_with($user->avatar, 'http')))
                    <img src="{{ $user->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'U') }}&email={{ urlencode($user->email) }}&background=3B82F6&color=fff&bold=true&size=96"
                        alt="Avatar" class="w-full h-full object-cover">
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1 text-center sm:text-left">
                <h2 class="text-xl lg:text-2xl font-bold text-white">
                    {{ $user->name ?? 'Nama Belum Diisi' }}
                </h2>
                <p class="text-white/60 mt-1">{{ $user->email }}</p>

                <div class="flex flex-wrap justify-center sm:justify-start gap-2 mt-3">
                    <span
                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-500/20 text-blue-400 text-xs">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Pengguna
                    </span>
                    @if($user->created_at)
                        <span
                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-500/20 text-green-400 text-xs">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Bergabung {{ $user->created_at->format('M Y') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Sertifikat --}}
        <div class="stat-card-blue rounded-xl lg:rounded-2xl p-4 lg:p-5 animate-fade-in-up scroll-delay-1">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl lg:text-3xl font-bold text-white">{{ $stats['total_sertifikat'] ?? 0 }}</p>
            <p class="text-white/60 text-xs lg:text-sm mt-1">Total Sertifikat</p>
        </div>

        {{-- Terverifikasi --}}
        <div class="stat-card-green rounded-xl lg:rounded-2xl p-4 lg:p-5 animate-fade-in-up scroll-delay-2">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl lg:text-3xl font-bold text-white">{{ $stats['terverifikasi'] ?? 0 }}</p>
            <p class="text-white/60 text-xs lg:text-sm mt-1">Terverifikasi</p>
        </div>

        {{-- Lembaga --}}
        <div class="stat-card rounded-xl lg:rounded-2xl p-4 lg:p-5 animate-fade-in-up scroll-delay-3">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl lg:text-3xl font-bold text-white">{{ $stats['total_lembaga'] ?? 0 }}</p>
            <p class="text-white/60 text-xs lg:text-sm mt-1">Lembaga Terkait</p>
        </div>

        {{-- Kategori --}}
        <div class="stat-card-purple rounded-xl lg:rounded-2xl p-4 lg:p-5 animate-fade-in-up scroll-delay-4">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl lg:text-3xl font-bold text-white">{{ $stats['total_kategori'] ?? 0 }}</p>
            <p class="text-white/60 text-xs lg:text-sm mt-1">Kategori</p>
        </div>
    </div>

    {{-- Achievements Section --}}
    <div class="glass-card rounded-2xl p-5 lg:p-6 mb-6 animate-fade-in-up">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                Pencapaian
            </h3>
            <span class="text-white/40 text-sm">{{ count($achievements ?? []) }} badge</span>
        </div>

        @if(count($achievements ?? []) > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($achievements as $achievement)
                    <div class="p-4 rounded-xl bg-white/5 border border-white/10 text-center hover-lift transition group">
                        <div
                            class="w-12 h-12 mx-auto mb-2 rounded-full flex items-center justify-center
                                    {{ $achievement['unlocked'] ? 'bg-gradient-to-br from-yellow-400 to-orange-500' : 'bg-white/10' }}">
                            @if($achievement['unlocked'])
                                <span class="text-2xl">{{ $achievement['icon'] }}</span>
                            @else
                                <svg class="w-6 h-6 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            @endif
                        </div>
                        <p class="text-sm font-medium {{ $achievement['unlocked'] ? 'text-white' : 'text-white/40' }}">
                            {{ $achievement['name'] }}
                        </p>
                        <p class="text-xs {{ $achievement['unlocked'] ? 'text-white/60' : 'text-white/30' }} mt-1">
                            {{ $achievement['description'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-white/5 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <p class="text-white/50">Belum ada pencapaian</p>
                <p class="text-white/30 text-sm mt-1">Kumpulkan sertifikat untuk membuka badge</p>
            </div>
        @endif
    </div>

    {{-- Personal Info (Read-only) --}}
    <div class="glass-card rounded-2xl p-5 lg:p-6 mb-6 animate-fade-in-up">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Informasi Pribadi</h3>
            <a href="{{ route('user.profil.edit') }}" class="text-blue-400 text-sm hover:text-blue-300">
                Edit →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Nama --}}
            <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                <p class="text-white/50 text-xs mb-1">Nama Lengkap</p>
                <p class="text-white font-medium">{{ $user->name ?? '-' }}</p>
            </div>

            {{-- Email --}}
            <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                <p class="text-white/50 text-xs mb-1">Email</p>
                <p class="text-white font-medium">{{ $user->email ?? '-' }}</p>
            </div>

            {{-- Telepon --}}
            <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                <p class="text-white/50 text-xs mb-1">No. Telepon</p>
                <p class="text-white font-medium">{{ $user->phone ?? '-' }}</p>
            </div>

            {{-- Pekerjaan --}}
            <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                <p class="text-white/50 text-xs mb-1">Pekerjaan</p>
                <p class="text-white font-medium">{{ $user->occupation ?? '-' }}</p>
            </div>

            {{-- Institusi --}}
            <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                <p class="text-white/50 text-xs mb-1">Institusi/Perusahaan</p>
                <p class="text-white font-medium">{{ $user->institution ?? '-' }}</p>
            </div>

            {{-- Lokasi --}}
            <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                <p class="text-white/50 text-xs mb-1">Lokasi</p>
                <p class="text-white font-medium">{{ $user->country ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Recent Certificates --}}
    <div class="glass-card rounded-2xl p-5 lg:p-6 animate-fade-in-up">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Sertifikat Terbaru</h3>
            <a href="{{ route('user.sertifikat') }}" class="text-blue-400 text-sm hover:text-blue-300">
                Lihat Semua →
            </a>
        </div>

        @if(isset($recentCertificates) && $recentCertificates->count() > 0)
            <div class="space-y-3">
                @foreach($recentCertificates->take(3) as $cert)
                    <div
                        class="flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition">
                        <div
                            class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-medium truncate">{{ $cert->course_name ?? 'Sertifikat' }}</p>
                            <p class="text-white/50 text-sm">
                                {{ $cert->user->institution_name ?? $cert->user->name ?? 'Lembaga' }} •
                                {{ $cert->issue_date?->format('d M Y') }}
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs
                                    @if($cert->status === 'active') bg-green-500/20 text-green-400
                                    @else bg-red-500/20 text-red-400 @endif">
                            {{ $cert->status === 'active' ? 'Aktif' : 'Dicabut' }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-white/5 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <p class="text-white/50">Belum ada sertifikat</p>
                <p class="text-white/30 text-sm mt-1">Sertifikat akan muncul setelah diterbitkan oleh lembaga</p>
            </div>
        @endif
    </div>

</x-layouts.user>