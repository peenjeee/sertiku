{{-- resources/views/user/profil.blade.php --}}
<x-layouts.user title="Profil Saya – SertiKu">

    {{-- Header Banner --}}
    <div class="welcome-banner rounded-2xl lg:rounded-3xl p-5 lg:p-8 mb-6 animate-fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl lg:text-2xl font-bold text-white">Profil Saya</h1>
                <p class="text-white/60 mt-1">Kelola informasi profil dan keamanan akun Anda</p>
            </div>
            <button onclick="document.getElementById('nama').focus()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm hover:brightness-110 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Profil
            </button>
        </div>
    </div>

    {{-- Profile Card --}}
    <div class="glass-card rounded-2xl p-5 lg:p-6 mb-6 animate-fade-in-up">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 lg:w-20 lg:h-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-2xl lg:text-3xl">
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg lg:text-xl font-semibold text-white">
                    {{ $user->name ?? 'Nama Belum Diisi' }}
                </h2>
                <span class="inline-flex items-center gap-1 mt-1 px-3 py-1 rounded-full bg-blue-500/20 text-blue-400 text-xs">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    User
                </span>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="glass-card rounded-2xl p-2 mb-6 animate-fade-in-up" x-data="{ tab: 'info' }">
        <div class="flex gap-2">
            <button @click="tab = 'info'"
                    :class="tab === 'info' ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white' : 'text-white/60 hover:text-white hover:bg-white/5'"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informasi Profil
            </button>
            <button @click="tab = 'security'"
                    :class="tab === 'security' ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white' : 'text-white/60 hover:text-white hover:bg-white/5'"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Keamanan
            </button>
        </div>
    </div>

    {{-- Tab Content --}}
    <div x-data="{ tab: 'info' }">
        {{-- Tab Buttons (Hidden, synced with above) --}}
        <div class="hidden">
            <button @click="tab = 'info'" id="tabInfo"></button>
            <button @click="tab = 'security'" id="tabSecurity"></button>
        </div>

        {{-- Info Tab --}}
        <div x-show="tab === 'info'" class="glass-card rounded-2xl p-5 lg:p-6 animate-fade-in-up">
            <h3 class="text-lg font-semibold text-white mb-6">Data Pribadi</h3>

            @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-500/20 border border-green-500/30 text-green-400 text-sm">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('user.profil.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-sm text-white mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
                        <input type="text" name="name" id="nama" value="{{ old('name', $user->name) }}"
                               class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        @error('name')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm text-white mb-2">Email</label>
                        <input type="email" value="{{ $user->email }}" disabled
                               class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white/50 cursor-not-allowed">
                        <p class="mt-1 text-xs text-white/40">Email tidak dapat diubah</p>
                    </div>

                    {{-- No. Telepon --}}
                    <div>
                        <label class="block text-sm text-white mb-2">No. Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                               placeholder="+62...">
                    </div>

                    {{-- Pekerjaan --}}
                    <div>
                        <label class="block text-sm text-white mb-2">Pekerjaan</label>
                        <input type="text" name="occupation" value="{{ old('occupation', $user->occupation) }}"
                               class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                               placeholder="Contoh: Software Developer">
                    </div>

                    {{-- Institusi/Perusahaan --}}
                    <div>
                        <label class="block text-sm text-white mb-2">Institusi/Perusahaan</label>
                        <input type="text" name="institution" value="{{ old('institution', $user->institution) }}"
                               class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                               placeholder="Contoh: PT. Tech Indonesia">
                    </div>

                    {{-- Lokasi --}}
                    <div>
                        <label class="block text-sm text-white mb-2">Lokasi</label>
                        <input type="text" name="country" value="{{ old('country', $user->country) }}"
                               class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                               placeholder="Contoh: Jakarta, Indonesia">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                            class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium hover:brightness-110 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Security Tab --}}
        <div x-show="tab === 'security'" class="glass-card rounded-2xl p-5 lg:p-6 animate-fade-in-up" style="display: none;">
            <h3 class="text-lg font-semibold text-white mb-6">Ubah Password</h3>

            @if($errors->has('current_password'))
            <div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400 text-sm">
                {{ $errors->first('current_password') }}
            </div>
            @endif

            <form action="{{ route('user.profil.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4 max-w-md">
                    {{-- Password Saat Ini --}}
                    <div>
                        <label class="block text-sm text-white mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password"
                               class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                               placeholder="Masukkan password saat ini">
                    </div>

                    {{-- Password Baru --}}
                    <div>
                        <label class="block text-sm text-white mb-2">Password Baru</label>
                        <input type="password" name="password"
                               class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                               placeholder="Minimal 8 karakter">
                        @error('password')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label class="block text-sm text-white mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation"
                               class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                               placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium hover:brightness-110 transition">
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // Sync tab clicks
        document.addEventListener('alpine:init', () => {
            // Wait for Alpine
        });
    </script>
    @endpush

</x-layouts.user>
