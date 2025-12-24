<x-layouts.admin title="Google Drive Token">
    <div class="max-w-2xl mx-auto">
        <div class="glass-card rounded-2xl p-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-4">Google Drive Berhasil Terhubung!</h2>

            <p class="text-gray-600 mb-6">Copy refresh token di bawah ini dan tambahkan ke file <code
                    class="bg-gray-200 px-2 py-1 rounded">.env</code></p>

            <div class="bg-gray-100 p-4 rounded-lg mb-6">
                <label class="block text-gray-500 text-sm mb-2">Refresh Token:</label>
                <div class="flex gap-2">
                    <input type="text" id="refreshToken" value="{{ $refreshToken }}" readonly
                        class="flex-1 p-3 bg-white border border-gray-300 rounded-lg text-sm font-mono">
                    <button onclick="copyToken()"
                        class="px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Copy
                    </button>
                </div>
            </div>

            <div class="bg-yellow-50 p-4 rounded-lg text-left mb-6">
                <p class="text-yellow-800 text-sm mb-2"><strong>Tambahkan ke .env:</strong></p>
                <pre
                    class="bg-gray-800 text-green-400 p-3 rounded text-xs overflow-x-auto">GOOGLE_DRIVE_REFRESH_TOKEN={{ $refreshToken }}</pre>
            </div>

            <p class="text-gray-500 text-sm mb-4">Setelah menambahkan ke .env, jalankan: <code
                    class="bg-gray-200 px-2 py-1 rounded">php artisan config:clear</code></p>

            <a href="{{ route('admin.backup') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Backup
            </a>
        </div>
    </div>

    <script>
        function copyToken() {
            const input = document.getElementById('refreshToken');
            input.select();
            document.execCommand('copy');
            alert('Token berhasil dicopy!');
        }
    </script>
</x-layouts.admin>