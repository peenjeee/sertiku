<x-layouts.admin>
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8 animate-fade-in">
        <div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold">Kelola Pengguna</h1>
            <p class="text-white/60 text-sm mt-1">Kelola semua pengguna dan lembaga terdaftar</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="px-4 py-2 bg-blue-500/20 border border-blue-500/30 rounded-lg">
                <span class="text-blue-400 text-sm font-medium">{{ $userStats['total'] }} Total Pengguna</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="glass-card rounded-xl p-3 sm:p-4 min-w-0 animate-fade-in-up stagger-1">
            <p class="text-gray-500 text-xs font-medium mb-1">Total</p>
            <p class="text-gray-800 text-xl sm:text-2xl font-bold">{{ number_format($userStats['total']) }}</p>
        </div>
        <div class="glass-card rounded-xl p-3 sm:p-4 min-w-0 animate-fade-in-up stagger-2">
            <p class="text-gray-500 text-xs font-medium mb-1">Pengguna</p>
            <p class="text-blue-600 text-xl sm:text-2xl font-bold">{{ number_format($userStats['pengguna']) }}</p>
        </div>
        <div class="glass-card rounded-xl p-3 sm:p-4 min-w-0 animate-fade-in-up stagger-3">
            <p class="text-gray-500 text-xs font-medium mb-1">Lembaga</p>
            <p class="text-green-600 text-xl sm:text-2xl font-bold">{{ number_format($userStats['lembaga']) }}</p>
        </div>
        <div class="glass-card rounded-xl p-3 sm:p-4 min-w-0 animate-fade-in-up stagger-4">
            <p class="text-gray-500 text-xs font-medium mb-1">Aktif</p>
            <p class="text-purple-600 text-xl sm:text-2xl font-bold">{{ number_format($userStats['active']) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-2xl p-3 sm:p-4 mb-6 animate-fade-in">
        <form method="GET" class="flex flex-col gap-3 sm:gap-4">
            <div class="w-full">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama, email, atau lembaga..."
                    class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <select name="type"
                    class="w-full sm:w-auto px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Tipe</option>
                    <option value="pengguna" {{ request('type') === 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                    <option value="lembaga" {{ request('type') === 'lembaga' ? 'selected' : '' }}>Lembaga</option>
                </select>
                <select name="status"
                    class="w-full sm:w-auto px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Users Table (Desktop) / Cards (Mobile) -->
    <div class="glass-card rounded-2xl overflow-hidden animate-fade-in">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left text-gray-500 text-xs font-medium py-4 px-4">Pengguna</th>
                        <th class="text-left text-gray-500 text-xs font-medium py-4 px-4">Email</th>
                        <th class="text-left text-gray-500 text-xs font-medium py-4 px-4">Tipe</th>
                        <th class="text-left text-gray-500 text-xs font-medium py-4 px-4">Bergabung</th>
                        <th class="text-left text-gray-500 text-xs font-medium py-4 px-4">Status</th>
                        <th class="text-left text-gray-500 text-xs font-medium py-4 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-[#3B82F6] flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if($user->avatar && (str_starts_with($user->avatar, '/storage/') || str_starts_with($user->avatar, 'http')))
                                            <img src="{{ $user->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&email={{ urlencode($user->email) }}&background=3B82F6&color=fff&bold=true&size=40"
                                                alt="Avatar" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-gray-800 font-medium text-sm">{{ $user->name }}</p>
                                        @if($user->institution_name)
                                            <p class="text-gray-400 text-xs">{{ $user->institution_name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-gray-600 text-sm">{{ $user->email }}</span>
                            </td>
                            <td class="py-4 px-4">
                                @if($user->account_type === 'admin')
                                    <span
                                        class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full whitespace-nowrap">Admin</span>
                                @elseif(in_array($user->account_type, ['lembaga', 'institution']))
                                    <span
                                        class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full whitespace-nowrap">Lembaga</span>
                                @else
                                    <span
                                        class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full whitespace-nowrap">Pengguna</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-gray-500 text-sm">{{ $user->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="py-4 px-4">
                                @if($user->profile_completed)
                                    <span
                                        class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full">Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-500 text-xs font-medium rounded-full">Tidak
                                        Aktif</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                        class="p-2 hover:bg-gray-100 rounded-lg transition" title="Lihat Detail">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2 hover:bg-gray-100 rounded-lg transition"
                                            title="{{ $user->profile_completed ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            @if($user->profile_completed)
                                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="text-gray-400">Tidak ada pengguna ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden divide-y divide-gray-100">
            @forelse($users as $user)
                <div class="p-4 hover:bg-gray-50 transition">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-[#3B82F6] flex items-center justify-center flex-shrink-0 overflow-hidden">
                            @if($user->avatar && (str_starts_with($user->avatar, '/storage/') || str_starts_with($user->avatar, 'http')))
                                <img src="{{ $user->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&email={{ urlencode($user->email) }}&background=3B82F6&color=fff&bold=true&size=40"
                                    alt="Avatar" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-gray-800 font-medium text-sm truncate">{{ $user->name }}</p>
                                    <p class="text-gray-500 text-xs truncate">{{ $user->email }}</p>
                                </div>
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                        class="p-1.5 hover:bg-gray-200 rounded-lg transition">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-1.5 hover:bg-gray-200 rounded-lg transition">
                                            @if($user->profile_completed)
                                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                @if($user->account_type === 'admin')
                                    <span
                                        class="px-2 py-0.5 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">Admin</span>
                                @elseif(in_array($user->account_type, ['lembaga', 'institution']))
                                    <span
                                        class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-medium rounded-full">Lembaga</span>
                                @else
                                    <span
                                        class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">Pengguna</span>
                                @endif
                                @if($user->profile_completed)
                                    <span
                                        class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full">Aktif</span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-xs font-medium rounded-full">Tidak
                                        Aktif</span>
                                @endif
                                <span class="text-gray-400 text-xs">{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p class="text-gray-400">Tidak ada pengguna ditemukan</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>

</x-layouts.admin>