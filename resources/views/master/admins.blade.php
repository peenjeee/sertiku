{{-- resources/views/master/admins.blade.php --}}
<x-layouts.master title="Kelola Admin – Master SertiKu">

    <div class="mb-6 animate-fade-in-up">
        <h1 class="text-2xl font-bold text-white">Kelola Admin</h1>
        <p class="text-white/60 mt-1">Promote user menjadi admin atau demote admin</p>
    </div>

    @if(session('success'))
        <div
            class="mb-6 p-4 rounded-xl bg-green-500/20 border border-green-500/30 text-green-400 text-sm animate-fade-in-up">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400 text-sm animate-fade-in-up">
            {{ session('error') }}
        </div>
    @endif

    {{-- Current Admins --}}
    <div class="glass-card rounded-2xl p-6 mb-6 animate-fade-in-up">
        <h2 class="text-lg font-semibold text-white mb-4">Admin Saat Ini ({{ $admins->count() }})</h2>
        <div class="space-y-3">
            @forelse($admins as $admin)
                <div class="flex items-center gap-4 p-4 rounded-xl bg-white/5">
                    <div
                        class="w-12 h-12 rounded-full flex items-center justify-center overflow-hidden
                                                {{ $admin->is_master ? 'bg-[#A855F7]' : 'bg-[#3B82F6]' }}">
                        @if($admin->avatar && (str_starts_with($admin->avatar, '/storage/') || str_starts_with($admin->avatar, 'http')))
                            <img src="{{ $admin->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->name) }}&email={{ urlencode($admin->email) }}&background={{ $admin->is_master ? 'A855F7' : '3B82F6' }}&color=fff&bold=true&size=48"
                                alt="Avatar" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-medium">{{ $admin->name }}</p>
                        <p class="text-white/50 text-sm">{{ $admin->email }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($admin->is_master)
                            <span class="px-3 py-1 rounded-full bg-purple-500/20 text-purple-400 text-sm font-medium">Master</span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-blue-500/20 text-blue-400 text-sm">Admin</span>
                            <form action="{{ route('master.admins.demote', $admin) }}" method="POST" class="inline"
                                onsubmit="return confirmAction(event, 'Yakin ingin menurunkan {{ $admin->name }} dari Admin?')">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-1 rounded-lg bg-red-500/20 text-red-400 text-sm hover:bg-red-500/30 transition">
                                    Demote
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-white/50 text-sm text-center py-4">Belum ada admin</p>
            @endforelse
        </div>
    </div>

    {{-- Promote Users --}}
    <div class="glass-card rounded-2xl p-6 animate-fade-in-up">
        <h2 class="text-lg font-semibold text-white mb-4">Jadikan Admin Baru</h2>
        <p class="text-white/50 text-sm mb-4">Pilih user untuk dijadikan admin</p>

        <div class="space-y-3 max-h-96 overflow-y-auto">
            @forelse($users->take(20) as $user)
                <div class="flex items-center gap-4 p-4 rounded-xl bg-white/5">
                    <div
                        class="w-10 h-10 rounded-full bg-[#6B7280] flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if($user->avatar && (str_starts_with($user->avatar, '/storage/') || str_starts_with($user->avatar, 'http')))
                            <img src="{{ $user->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'U') }}&email={{ urlencode($user->email) }}&background=6B7280&color=fff&bold=true&size=40"
                                alt="Avatar" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <p class="text-white font-medium text-sm truncate">{{ $user->name ?? 'Unnamed' }}</p>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold flex-shrink-0
                                                   @if($user->account_type === 'lembaga' || $user->account_type === 'institution') bg-green-500/20 text-green-400
                                                @else
                                                            bg-yellow-500/20 text-yellow-400
                                                        @endif
                                                ">{{ ucfirst($user->account_type ?? 'user') }}</span>
                        </div>
                        <p class="text-white/50 text-xs truncate">{{ $user->email }}</p>
                    </div>
                    <form action="{{ route('master.admins.promote', $user) }}" method="POST" class="inline flex-shrink-0"
                        onsubmit="return confirmAction(event, 'Yakin ingin menjadikan {{ $user->name }} sebagai Admin?')">
                        @csrf
                        <button type="submit"
                            class="px-3 py-1.5 rounded-lg bg-blue-500/20 text-blue-400 text-xs font-bold hover:bg-blue-500/30 transition whitespace-nowrap">
                            Jadikan Admin
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-white/50 text-sm text-center py-4">Tidak ada user yang bisa dijadikan admin</p>
            @endforelse
        </div>
    </div>

</x-layouts.master>
