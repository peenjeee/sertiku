<x-layouts.app title="Pengaturan Akun - SertiKu">
    <main class="min-h-screen bg-[#0F172A] px-4 py-10">
        <div class="mx-auto max-w-3xl">
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-white">Pengaturan Akun</h1>
                <p class="text-[#BEDBFF]/70 mt-2">Kelola informasi profil dan keamanan akun Anda</p>
            </div>

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="mb-6 rounded-lg bg-emerald-500/20 border border-emerald-500/30 p-4 text-emerald-400">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-lg bg-red-500/20 border border-red-500/30 p-4 text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-6">
                {{-- Profile Section --}}
                <div class="rounded-2xl bg-white/5 border border-white/10 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-white">Informasi Profil</h2>
                            <p class="text-sm text-[#BEDBFF]/60">Perbarui foto dan informasi dasar Anda</p>
                        </div>
                    </div>

                    <form action="{{ route('settings.profile') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        {{-- Avatar Upload --}}
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-6 pb-6 border-b border-white/10">
                            <div class="relative group">
                                <img id="avatar-preview"
                                    src="{{ ($user->avatar && (str_starts_with($user->avatar, '/storage/') || str_starts_with($user->avatar, 'http'))) ? $user->avatar : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'U') . '&email=' . urlencode($user->email) . '&background=3B82F6&color=fff&bold=true&size=80' }}"
                                    alt="Profile" class="w-20 h-20 rounded-full border-2 border-[#3B82F6] object-cover">
                                <label for="avatar-input"
                                    class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </label>
                            </div>
                            <div class="flex-1">
                                <p class="text-white font-medium">{{ $user->name }}</p>
                                <p class="text-[#BEDBFF]/60 text-sm mb-3">{{ $user->email }}</p>

                                <div class="flex flex-wrap gap-2">
                                    {{-- Upload Form --}}
                                    <form id="avatar-form" action="{{ route('settings.avatar') }}" method="POST"
                                        enctype="multipart/form-data" class="inline">
                                        @csrf
                                        <input type="file" name="avatar" id="avatar-input" accept="image/*"
                                            class="hidden" onchange="previewAvatar(this)">
                                        <button type="button" onclick="document.getElementById('avatar-input').click()"
                                            class="px-3 py-1.5 bg-white/10 border border-white/20 text-white text-xs font-medium rounded-lg hover:bg-white/20 transition">
                                            📷 Ganti Foto
                                        </button>
                                        <button type="submit" id="upload-btn"
                                            class="hidden px-3 py-1.5 bg-emerald-500 text-white text-xs font-medium rounded-lg hover:bg-emerald-600 transition">
                                            ✓ Upload
                                        </button>
                                    </form>

                                    {{-- Remove Avatar --}}
                                    @if($user->avatar)
                                        <form action="{{ route('settings.avatar.remove') }}" method="POST" class="inline"
                                            onsubmit="return confirmAction(event, 'Hapus foto profil?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1.5 bg-red-500/20 border border-red-500/30 text-red-400 text-xs font-medium rounded-lg hover:bg-red-500/30 transition">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <p class="text-[#BEDBFF]/50 text-xs mt-2">JPG, PNG, GIF, WebP. Max 2MB.</p>
                                @error('avatar')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm text-white">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full rounded-lg bg-white/5 border border-white/20 px-4 py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                                @error('name')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm text-white">No. Telepon</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="w-full rounded-lg bg-white/5 border border-white/20 px-4 py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
                                    placeholder="08123456789">
                                @error('phone')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm text-white">Pekerjaan</label>
                                <input type="text" name="occupation" value="{{ old('occupation', $user->occupation) }}"
                                    class="w-full rounded-lg bg-white/5 border border-white/20 px-4 py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
                                    placeholder="Contoh: Software Engineer">
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm text-white">Kota</label>
                                <input type="text" name="city" value="{{ old('city', $user->city) }}"
                                    class="w-full rounded-lg bg-white/5 border border-white/20 px-4 py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
                                    placeholder="Contoh: Jakarta">
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit"
                                class="px-6 py-2.5 bg-[#2563EB] text-white text-sm font-medium rounded-lg shadow-md shadow-blue-500/20 hover:bg-[#3B82F6] transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Password Section (Hidden for Google login users) --}}
                @if($user->password && !$user->google_id)
                    <div class="rounded-2xl bg-white/5 border border-white/10 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-white">Ubah Password</h2>
                                <p class="text-sm text-[#BEDBFF]/60">Pastikan password baru minimal 8 karakter</p>
                            </div>
                        </div>

                        <form action="{{ route('settings.password') }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div class="space-y-2">
                                <label class="text-sm text-white">Password Saat Ini</label>
                                <input type="password" name="current_password" required
                                    class="w-full rounded-lg bg-white/5 border border-white/20 px-4 py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
                                    placeholder="Masukkan password saat ini">
                                @error('current_password')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-sm text-white">Password Baru</label>
                                    <input type="password" name="password" required
                                        class="w-full rounded-lg bg-white/5 border border-white/20 px-4 py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
                                        placeholder="Minimal 8 karakter">
                                    @error('password')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm text-white">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full rounded-lg bg-white/5 border border-white/20 px-4 py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
                                        placeholder="Ulangi password baru">
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit"
                                    class="px-6 py-2.5 bg-[#F59E0B] text-white text-sm font-medium rounded-lg shadow-md shadow-amber-500/20 hover:bg-[#D97706] transition">
                                    Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                {{-- Danger Zone --}}
                <div class="rounded-2xl bg-red-500/10 border border-red-500/30 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-red-400">Zona Berbahaya</h2>
                            <p class="text-sm text-red-300/60">Tindakan ini tidak dapat dibatalkan</p>
                        </div>
                    </div>

                    <p class="text-sm text-red-300/80 mb-4">
                        Menghapus akun akan menghapus semua data Anda secara permanen termasuk sertifikat yang telah
                        diterbitkan.
                    </p>

                    <button type="button" onclick="openDeleteModal()"
                        class="px-4 py-2 bg-red-500/20 border border-red-500/50 text-red-400 text-sm font-medium rounded-lg hover:bg-red-500/30 transition">
                        Hapus Akun Saya
                    </button>
                </div>
            </div>

            {{-- Back Link --}}
            <div class="mt-8">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 text-[#BEDBFF]/70 hover:text-white text-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </main>

    {{-- Delete Account Modal --}}
    <div id="delete-modal" class="hidden fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
        <div class="bg-[#1E293B] rounded-2xl p-6 max-w-md w-full border border-white/10">
            <h3 class="text-xl font-bold text-white mb-2">Hapus Akun?</h3>
            <p class="text-[#BEDBFF]/70 text-sm mb-4">
                Tindakan ini tidak dapat dibatalkan. Semua data Anda akan dihapus secara permanen.
            </p>

            <form action="{{ route('settings.delete') }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="space-y-2 mb-4">
                    <label class="text-sm text-white">Ketik <strong class="text-red-400">HAPUS</strong> untuk
                        konfirmasi</label>
                    <input type="text" name="confirm_delete" required
                        class="w-full rounded-lg bg-white/5 border border-white/20 px-4 py-3 text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-red-500"
                        placeholder="HAPUS">
                    @error('confirm_delete')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2.5 bg-white/10 text-white text-sm font-medium rounded-lg hover:bg-white/20 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                        Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDeleteModal() {
            document.getElementById('delete-modal').classList.remove('hidden');
        }
        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
        }

        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Check file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    input.value = '';
                    return;
                }

                // Preview image
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                    document.getElementById('upload-btn').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-layouts.app>
