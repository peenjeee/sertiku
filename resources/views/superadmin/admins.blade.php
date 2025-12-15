{{-- resources/views/superadmin/admins.blade.php --}}
<x-layouts.superadmin title="Kelola Admin – Super Admin SertiKu">

    {{-- Header --}}
    <div class="mb-6 animate-fade-in-up">
        <h1 class="text-2xl font-bold text-white">Kelola Admin</h1>
        <p class="text-white/60 mt-1">Promote user menjadi admin atau demote admin</p>
    </div>

    {{-- Messages --}}
    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-green-500/20 border border-green-500/30 text-green-400 text-sm animate-fade-in-up">
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
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold
                    {{ $admin->is_superadmin ? 'bg-gradient-to-br from-purple-500 to-pink-500' : 'bg-gradient-to-br from-blue-500 to-indigo-600' }}">
                    {{ strtoupper(substr($admin->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white font-medium">{{ $admin->name }}</p>
                    <p class="text-white/50 text-sm">{{ $admin->email }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if($admin->is_superadmin)
                    <span class="px-3 py-1 rounded-full bg-purple-500/20 text-purple-400 text-sm font-medium">👑 Super Admin</span>
                    @else
                    <span class="px-3 py-1 rounded-full bg-blue-500/20 text-blue-400 text-sm">Admin</span>
                    <form action="{{ route('superadmin.demote', $admin) }}" method="POST" class="inline"
                          onsubmit="return confirm('Yakin ingin menurunkan {{ $admin->name }} dari Admin?')">
                        @csrf
                        <button type="submit" class="px-3 py-1 rounded-lg bg-red-500/20 text-red-400 text-sm hover:bg-red-500/30 transition">
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
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white font-medium text-sm">{{ $user->name ?? 'Unnamed' }}</p>
                    <p class="text-white/50 text-xs">{{ $user->email }}</p>
                </div>
                <span class="px-2 py-1 rounded-full text-xs
                    @if($user->account_type === 'lembaga') bg-green-500/20 text-green-400
                    @else bg-gray-500/20 text-gray-400 @endif
                ">{{ ucfirst($user->account_type ?? 'user') }}</span>
                <form action="{{ route('superadmin.promote', $user) }}" method="POST" class="inline"
                      onsubmit="return confirm('Yakin ingin menjadikan {{ $user->name }} sebagai Admin?')">
                    @csrf
                    <button type="submit" class="px-3 py-1 rounded-lg bg-blue-500/20 text-blue-400 text-sm hover:bg-blue-500/30 transition">
                        Jadikan Admin
                    </button>
                </form>
            </div>
            @empty
            <p class="text-white/50 text-sm text-center py-4">Tidak ada user yang bisa dijadikan admin</p>
            @endforelse
        </div>
    </div>

</x-layouts.superadmin>
