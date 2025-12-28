{{-- resources/views/master/dashboard.blade.php --}}
<x-layouts.master title="Dashboard – Master SertiKu">

    {{-- Welcome Banner --}}
    <div class="rounded-2xl p-6 mb-6 animate-fade-in-up"
        style="background: linear-gradient(135deg, #4c1d95 0%, #7c3aed 50%, #a855f7 100%); border: 1px solid rgba(139, 92, 246, 0.3);">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">
                    Selamat Datang, Master! 👑
                </h1>
                <p class="text-white/70">Akses penuh ke seluruh sistem SertiKu</p>
            </div>
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('master.admins') }}"
                    class="px-4 py-2 rounded-lg bg-white/10 border border-white/20 text-white text-sm hover:bg-white/20 transition">
                    Kelola Admin
                </a>
                <a href="{{ route('master.settings') }}"
                    class="px-4 py-2 rounded-lg bg-white text-purple-700 font-medium text-sm hover:bg-white/90 transition">
                    Pengaturan Sistem
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stat-card-purple rounded-xl p-5 animate-fade-in-up stagger-1">
            <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-white">{{ $stats['total_users'] }}</p>
            <p class="text-white/60 text-sm mt-1">Total User</p>
        </div>

        <div class="stat-card-blue rounded-xl p-5 animate-fade-in-up stagger-2">
            <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-white">{{ $stats['total_admins'] }}</p>
            <p class="text-white/60 text-sm mt-1">Total Admin</p>
        </div>

        <div class="stat-card-green rounded-xl p-5 animate-fade-in-up stagger-3">
            <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-white">{{ $stats['total_lembaga'] }}</p>
            <p class="text-white/60 text-sm mt-1">Total Lembaga</p>
        </div>

        <div class="stat-card-yellow rounded-xl p-5 animate-fade-in-up stagger-4">
            <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-white">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
            <p class="text-white/60 text-sm mt-1">Total Pendapatan</p>
        </div>
    </div>

    {{-- Admin List & Recent Users --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="glass-card rounded-2xl p-6 animate-fade-in-up">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Daftar Admin</h2>
                <a href="{{ route('master.admins') }}" class="text-purple-400 text-sm hover:text-purple-300">Kelola
                    →</a>
            </div>
            <div class="space-y-3">
                @forelse($admins as $admin)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden
                                            {{ $admin->is_master ? 'bg-gradient-to-br from-purple-500 to-pink-500' : 'bg-gradient-to-br from-blue-500 to-indigo-600' }}">
                            @if($admin->avatar && (str_starts_with($admin->avatar, '/storage/') || str_starts_with($admin->avatar, 'http')))
                                <img src="{{ $admin->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->name) }}&email={{ urlencode($admin->email) }}&background={{ $admin->is_master ? 'A855F7' : '3B82F6' }}&color=fff&bold=true&size=40"
                                    alt="Avatar" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-medium text-sm truncate">{{ $admin->name }}</p>
                            <p class="text-white/50 text-xs">{{ $admin->email }}</p>
                        </div>
                        @if($admin->is_master)
                            <span class="px-2 py-1 rounded-full bg-purple-500/20 text-purple-400 text-xs">Master</span>
                        @else
                            <span class="px-2 py-1 rounded-full bg-blue-500/20 text-blue-400 text-xs">Admin</span>
                        @endif
                    </div>
                @empty
                    <p class="text-white/50 text-sm text-center py-4">Belum ada admin</p>
                @endforelse
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 animate-fade-in-up">
            <h2 class="text-lg font-semibold text-white mb-4">User Terbaru</h2>
            <div class="space-y-3">
                @forelse($recentUsers as $user)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5">
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center overflow-hidden">
                            @if($user->avatar && (str_starts_with($user->avatar, '/storage/') || str_starts_with($user->avatar, 'http')))
                                <img src="{{ $user->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'U') }}&email={{ urlencode($user->email) }}&background=6B7280&color=fff&bold=true&size=40"
                                    alt="Avatar" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-medium text-sm truncate">{{ $user->name ?? 'Unnamed' }}</p>
                            <p class="text-white/50 text-xs">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                        @php
                            $accountType = $user->account_type ?? 'user';
                            $displayType = in_array($accountType, ['lembaga', 'institution']) ? 'Lembaga' : ($accountType === 'admin' ? 'Admin' : 'Pengguna');
                            $isLembaga = in_array($accountType, ['lembaga', 'institution']);
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs
                                @if($accountType === 'admin') bg-blue-500/20 text-blue-400
                                @elseif($isLembaga) bg-green-500/20 text-green-400
                                @else bg-gray-500/20 text-gray-400 @endif
                            ">{{ $displayType }}</span>
                    </div>
                @empty
                    <p class="text-white/50 text-sm text-center py-4">Belum ada user</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="glass-card rounded-xl p-4 text-center animate-fade-in-up">
            <p class="text-2xl font-bold text-purple-400">{{ $stats['total_masters'] }}</p>
            <p class="text-white/50 text-sm">Masters</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center animate-fade-in-up">
            <p class="text-2xl font-bold text-blue-400">{{ $stats['total_pengguna'] }}</p>
            <p class="text-white/50 text-sm">Pengguna</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center animate-fade-in-up">
            <p class="text-2xl font-bold text-green-400">{{ $stats['total_certificates'] }}</p>
            <p class="text-white/50 text-sm">Sertifikat</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center animate-fade-in-up">
            <p class="text-2xl font-bold text-yellow-400">{{ $stats['total_orders'] }}</p>
            <p class="text-white/50 text-sm">Transaksi</p>
        </div>
    </div>

</x-layouts.master>