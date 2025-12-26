<x-layouts.admin>
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8 animate-fade-in">
        <div>
            <nav class="flex items-center gap-2 text-sm mb-2">
                <a href="{{ route('admin.users') }}" class="text-white/60 hover:text-white transition">Kelola
                    Pengguna</a>
                <svg class="w-4 h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-white">Detail Pengguna</span>
            </nav>
            <h1 class="text-white text-2xl lg:text-3xl font-bold">{{ $user->name }}</h1>
            <p class="text-white/60 text-sm mt-1">{{ $user->email }}</p>
        </div>
        <a href="{{ route('admin.users') }}"
            class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Info -->
        <div class="glass-card rounded-2xl p-6 animate-fade-in-up">
            <div class="flex items-center gap-4 mb-6">
                <div
                    class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center overflow-hidden">
                    @if($user->avatar && (str_starts_with($user->avatar, '/storage/') || str_starts_with($user->avatar, 'http')))
                        <img src="{{ $user->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&email={{ urlencode($user->email) }}&background=3B82F6&color=fff&bold=true&size=64"
                            alt="Avatar" class="w-full h-full object-cover">
                    @endif
                </div>
                <div>
                    <h2 class="text-gray-800 font-bold text-lg">{{ $user->name }}</h2>
                    <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <p class="text-gray-500 text-xs font-medium mb-1">Tipe Akun</p>
                    @if($user->account_type === 'lembaga')
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">Lembaga</span>
                    @else
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full">Pengguna</span>
                    @endif
                </div>

                @if($user->institution_name)
                    <div>
                        <p class="text-gray-500 text-xs font-medium mb-1">Nama Lembaga</p>
                        <p class="text-gray-800 text-sm">{{ $user->institution_name }}</p>
                    </div>
                @endif

                @if($user->phone)
                    <div>
                        <p class="text-gray-500 text-xs font-medium mb-1">Telepon</p>
                        <p class="text-gray-800 text-sm">{{ $user->phone }}</p>
                    </div>
                @endif

                <div>
                    <p class="text-gray-500 text-xs font-medium mb-1">Bergabung</p>
                    <p class="text-gray-800 text-sm">{{ $user->created_at->format('d F Y, H:i') }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-xs font-medium mb-1">Status</p>
                    @if($user->profile_completed)
                        <span
                            class="px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-full">Aktif</span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-500 text-sm font-medium rounded-full">Tidak
                            Aktif</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Certificates -->
        <div class="lg:col-span-2 glass-card rounded-2xl p-6 animate-fade-in-up stagger-2">
            <h3 class="text-gray-800 font-bold text-lg mb-4">Sertifikat Diterbitkan</h3>

            @if($user->certificates && $user->certificates->count() > 0)
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($user->certificates as $cert)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-800 text-sm font-medium">{{ $cert->recipient_name }}</p>
                                    <p class="text-gray-500 text-xs font-mono">{{ $cert->certificate_number }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-500 text-xs">{{ $cert->created_at->format('d M Y') }}</p>
                                @if($cert->status === 'active')
                                    <span class="text-emerald-600 text-xs font-medium">Aktif</span>
                                @else
                                    <span class="text-red-600 text-xs font-medium">Dicabut</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-400">Belum ada sertifikat</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>