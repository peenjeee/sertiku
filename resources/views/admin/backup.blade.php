<x-layouts.admin>
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8 animate-fade-in">
        <div>
            <h1 class="text-white text-2xl lg:text-3xl font-bold">Backup & Restore</h1>
            <p class="text-white/60 text-sm mt-1">Kelola backup dan export data platform</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Create Backup -->
        <div class="glass-card rounded-2xl p-6 animate-fade-in-up stagger-1 hover-lift">
            <div class="w-12 h-12 icon-circle-blue rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <h3 class="text-gray-800 font-bold text-lg mb-2">Buat Backup</h3>
            <p class="text-gray-500 text-sm mb-4">Buat backup lengkap untuk semua data pengguna dan sertifikat.</p>
            <form method="POST" action="{{ route('admin.backup.create') }}">
                @csrf
                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    Buat Backup Sekarang
                </button>
            </form>
        </div>

        <!-- Export Data -->
        <div class="glass-card rounded-2xl p-6 animate-fade-in-up stagger-2 hover-lift">
            <div class="w-12 h-12 icon-circle-green rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
            </div>
            <h3 class="text-gray-800 font-bold text-lg mb-2">Export Data</h3>
            <p class="text-gray-500 text-sm mb-4">Export data dalam format CSV atau JSON.</p>
            <form method="POST" action="{{ route('admin.backup.export') }}" class="space-y-3">
                @csrf
                <select name="type" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm">
                    <option value="certificates">Sertifikat ({{ $exportStats['certificates'] }})</option>
                    <option value="users">Pengguna ({{ $exportStats['users'] }})</option>
                    <option value="lembaga">Lembaga ({{ $exportStats['lembaga'] }})</option>
                </select>
                <div class="flex gap-2">
                    <button type="submit" name="format" value="csv" class="flex-1 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition">
                        CSV
                    </button>
                    <button type="submit" name="format" value="json" class="flex-1 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition">
                        JSON
                    </button>
                </div>
            </form>
        </div>

        <!-- Stats -->
        <div class="glass-card rounded-2xl p-6 animate-fade-in-up stagger-3 hover-lift">
            <div class="w-12 h-12 icon-circle-purple rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <h3 class="text-gray-800 font-bold text-lg mb-4">Data Tersedia</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 text-sm">Total Pengguna</span>
                    <span class="text-gray-800 font-bold">{{ number_format($exportStats['users']) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 text-sm">Total Sertifikat</span>
                    <span class="text-gray-800 font-bold">{{ number_format($exportStats['certificates']) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 text-sm">Total Lembaga</span>
                    <span class="text-gray-800 font-bold">{{ number_format($exportStats['lembaga']) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Backup History -->
    <div class="glass-card rounded-2xl p-6 animate-fade-in">
        <h3 class="text-gray-800 font-bold text-lg mb-6">Riwayat Backup</h3>

        @if(count($backups) > 0)
        <div class="space-y-3">
            @foreach($backups as $backup)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-800 font-medium text-sm">{{ $backup['name'] }}</p>
                        <p class="text-gray-500 text-xs">{{ $backup['date'] }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-gray-400 text-sm">{{ $backup['size'] }}</span>
                    <a href="{{ asset('storage/backups/' . $backup['name']) }}" download
                       class="p-2 hover:bg-gray-200 rounded-lg transition" title="Download">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
            <p class="text-gray-400 text-sm">Belum ada riwayat backup</p>
            <p class="text-gray-400 text-xs mt-1">Buat backup pertama Anda sekarang</p>
        </div>
        @endif
    </div>

    @if(session('success'))
    <div class="fixed bottom-6 right-6 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg animate-fade-in-up z-50">
        {{ session('success') }}
    </div>
    @endif
</x-layouts.admin>
