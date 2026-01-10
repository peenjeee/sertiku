<x-layouts.admin>
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8 animate-fade-in">
        <div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold">Pengaturan</h1>
            <p class="text-white/60 text-sm mt-1">Konfigurasi pengaturan admin panel</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- General Settings -->
        <div class="glass-card rounded-2xl p-6 animate-fade-in-up stagger-1">
            <h3 class="text-gray-800 font-bold text-lg mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Pengaturan Umum
            </h3>

            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-gray-600 text-sm font-medium mb-2">Nama Aplikasi</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] }}" readonly
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-600 text-sm font-medium mb-2">URL Aplikasi</label>
                    <input type="url" name="site_url" value="{{ $settings['site_url'] }}" readonly
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-600 text-sm font-medium mb-2">Email Admin</label>
                    <input type="email" name="admin_email" value="{{ $settings['admin_email'] }}" readonly
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-500">
                </div>
                <button disabled type="submit"
                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    Simpan Pengaturan
                </button>
            </form>
        </div>

        <!-- System Info -->
        <div class="glass-card rounded-2xl p-6 animate-fade-in-up stagger-2">
            <h3 class="text-gray-800 font-bold text-lg mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Informasi Sistem
            </h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <span class="text-gray-600 text-sm">Versi PHP</span>
                    <span class="text-gray-800 font-mono text-sm">{{ phpversion() }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <span class="text-gray-600 text-sm">Versi Laravel</span>
                    <span class="text-gray-800 font-mono text-sm">{{ app()->version() }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <span class="text-gray-600 text-sm">Environment</span>
                    <span
                        class="px-2 py-1 {{ app()->environment('production') ? 'bg-emerald-100 text-emerald-700' : 'bg-yellow-100 text-yellow-700' }} text-xs font-medium rounded-full">
                        {{ app()->environment() }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <span class="text-gray-600 text-sm">Debug Mode</span>
                    <span
                        class="px-2 py-1 {{ config('app.debug') ? 'bg-yellow-100 text-yellow-700' : 'bg-emerald-100 text-emerald-700' }} text-xs font-medium rounded-full">
                        {{ config('app.debug') ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <span class="text-gray-600 text-sm">Timezone</span>
                    <span class="text-gray-800 font-mono text-sm">{{ config('app.timezone') }}</span>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="lg:col-span-2 glass-card rounded-2xl p-6 border-2 border-red-200 animate-fade-in-up stagger-3">
            <h3 class="text-red-600 font-bold text-lg mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Zona Berbahaya
            </h3>
            <p class="text-gray-600 text-sm mb-4">Tindakan ini tidak dapat dibatalkan. Harap berhati-hati.</p>
            <div class="flex flex-col sm:flex-row gap-4">
                <form action="{{ route('admin.settings.clear-cache') }}" method="POST"
                    onsubmit="return confirm('Yakin ingin membersihkan cache?')">
                    @csrf
                    <button type="submit"
                        class="px-6 py-3 bg-red-100 hover:bg-red-200 text-red-700 font-medium rounded-lg transition">
                        Clear Cache
                    </button>
                </form>
                <button type="button" onclick="openDeleteModal()"
                    class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition">
                    Hapus Akun Admin
                </button>
            </div>
        </div>
    </div>

    {{-- Delete Account Modal --}}
    <div id="delete-modal" class="hidden fixed inset-0 bg-black/70 z-[100] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Hapus Akun Admin?</h3>
            <p class="text-gray-600 text-sm mb-4">
                Tindakan ini tidak dapat dibatalkan. Akun admin Anda akan dihapus secara permanen.
            </p>

            <form action="{{ route('admin.settings.delete') }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="space-y-2 mb-4">
                    <label class="text-sm text-gray-700">Ketik <strong class="text-red-600">HAPUS</strong> untuk
                        konfirmasi</label>
                    <input type="text" name="confirm_delete" required
                        class="w-full rounded-lg bg-gray-50 border border-gray-300 px-4 py-3 text-gray-800 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500"
                        placeholder="HAPUS">
                    @error('confirm_delete')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
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

    @if(session('success'))
        <div
            class="fixed bottom-6 right-6 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg animate-fade-in-up z-50">
            {{ session('success') }}
        </div>
    @endif

    <script>
        function openDeleteModal() {
            document.getElementById('delete-modal').classList.remove('hidden');
        }
        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
        }
    </script>
</x-layouts.admin>