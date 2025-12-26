<x-layouts.admin title="Backup & Restore">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-100">Backup & Restore</h1>
        <p class="text-gray-400">Kelola backup database dan file storage ke Google Drive</p>
    </div>

    @if(isset($driveError) && $driveError)
        <div class="mb-6 p-4 bg-red-500/20 border border-red-500 rounded-xl text-red-400">
            <strong>Error:</strong> {{ $driveError }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="rounded-2xl p-4 md:p-5 text-center bg-slate-800/80 border border-slate-700">
            <p class="text-xl md:text-2xl font-bold text-blue-400">{{ $storageStats['storage_size'] }}</p>
            <p class="text-gray-400 text-xs md:text-sm">Storage Size</p>
        </div>
        <div class="rounded-2xl p-4 md:p-5 text-center bg-slate-800/80 border border-slate-700">
            <p class="text-xl md:text-2xl font-bold text-green-400">{{ $storageStats['storage_files'] }}</p>
            <p class="text-gray-400 text-xs md:text-sm">Storage Files</p>
        </div>
        <div class="rounded-2xl p-4 md:p-5 text-center bg-slate-800/80 border border-slate-700">
            <p class="text-xl md:text-2xl font-bold text-purple-400">{{ $storageStats['database_size'] }}</p>
            <p class="text-gray-400 text-xs md:text-sm">Database Size</p>
        </div>
        <div class="rounded-2xl p-4 md:p-5 text-center bg-slate-800/80 border border-slate-700">
            <p class="text-xl md:text-2xl font-bold text-orange-400">{{ $storageStats['total_tables'] }}</p>
            <p class="text-gray-400 text-xs md:text-sm">Total Tables</p>
        </div>
    </div>

    @if($driveConfigured)
        <!-- Connected State -->
        <div class="bg-slate-800/80 border border-slate-700 rounded-2xl p-4 md:p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 md:w-14 md:h-14 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-base md:text-lg">Google Drive Terhubung</h3>
                        <p class="text-gray-300 text-sm">{{ $driveEmail ?? 'sertikuofficial@gmail.com' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.backup.drive.disconnect') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium"
                        onclick="return confirmAction(event, 'Putuskan koneksi Google Drive?')">
                        Putuskan Koneksi
                    </button>
                </form>
            </div>
        </div>

        <!-- Backup Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <form method="POST" action="{{ route('admin.backup.create') }}">
                @csrf
                <input type="hidden" name="upload_to_drive" value="1">
                <button type="submit" name="type" value="full"
                    class="w-full p-4 md:p-6 bg-slate-800/80 border border-slate-700 rounded-2xl hover:bg-blue-600/20 border-2 border-transparent hover:border-blue-500 transition group text-left md:text-center">
                    <div class="flex md:flex-col items-center md:items-center gap-4 md:gap-0">
                        <div
                            class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center md:mx-auto md:mb-4 group-hover:scale-110 transition flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-base md:text-lg md:mb-1">Full Backup</h3>
                            <p class="text-gray-400 text-sm">Database + Storage</p>
                        </div>
                    </div>
                </button>
            </form>

            <form method="POST" action="{{ route('admin.backup.create') }}">
                @csrf
                <input type="hidden" name="upload_to_drive" value="1">
                <button type="submit" name="type" value="database"
                    class="w-full p-4 md:p-6 bg-slate-800/80 border border-slate-700 rounded-2xl hover:bg-purple-600/20 border-2 border-transparent hover:border-purple-500 transition group text-left md:text-center">
                    <div class="flex md:flex-col items-center md:items-center gap-4 md:gap-0">
                        <div
                            class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center md:mx-auto md:mb-4 group-hover:scale-110 transition flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-base md:text-lg md:mb-1">Database Only</h3>
                            <p class="text-gray-400 text-sm">Backup database saja</p>
                        </div>
                    </div>
                </button>
            </form>

            <form method="POST" action="{{ route('admin.backup.create') }}">
                @csrf
                <input type="hidden" name="upload_to_drive" value="1">
                <button type="submit" name="type" value="storage"
                    class="w-full p-4 md:p-6 bg-slate-800/80 border border-slate-700 rounded-2xl hover:bg-green-600/20 border-2 border-transparent hover:border-green-500 transition group text-left md:text-center">
                    <div class="flex md:flex-col items-center md:items-center gap-4 md:gap-0">
                        <div
                            class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center md:mx-auto md:mb-4 group-hover:scale-110 transition flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-base md:text-lg md:mb-1">Storage Only</h3>
                            <p class="text-gray-400 text-sm">Backup file saja</p>
                        </div>
                    </div>
                </button>
            </form>
        </div>

        <!-- Backup List / Restore -->
        <div class="bg-slate-800/80 border border-slate-700 rounded-2xl p-4 md:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-6">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-blue-400" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12.545 10.239v3.821h5.445c-.712 2.315-2.647 3.972-5.445 3.972a6.033 6.033 0 110-12.064c1.498 0 2.866.549 3.921 1.453l2.814-2.814A9.969 9.969 0 0012.545 2C6.783 2 2 6.783 2 12.545c0 5.762 4.783 10.545 10.545 10.545 6.108 0 10.162-4.287 10.162-10.326 0-.692-.055-1.347-.173-1.978l-9.989-.047z" />
                    </svg>
                    <h3 class="text-white font-bold text-base md:text-lg">Daftar Backup</h3>
                </div>
                <span class="text-gray-400 text-sm">Folder: Backup SertiKu</span>
            </div>

            @if(count($driveBackups) > 0)
                <div class="space-y-3">
                    @foreach($driveBackups as $file)
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 bg-gray-800/50 rounded-xl hover:bg-gray-700/50 transition">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 md:w-12 md:h-12 rounded-xl flex items-center justify-center flex-shrink-0
                                                                                                {{ str_contains($file['name'], 'database') ? 'bg-purple-600/30' : 'bg-green-600/30' }}">
                                    @if(str_contains($file['name'], 'database'))
                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-purple-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-green-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-white font-medium text-sm md:text-base truncate">{{ $file['name'] }}</p>
                                    <p class="text-gray-400 text-xs md:text-sm">
                                        {{ isset($file['size']) ? number_format($file['size'] / 1024 / 1024, 2) . ' MB' : '-' }}
                                        •
                                        {{ isset($file['createdTime']) ? \Carbon\Carbon::parse($file['createdTime'])->format('d M Y, H:i') : '-' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 ml-14 sm:ml-0">
                                <form method="POST" action="{{ route('admin.backup.drive.restore') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="file_id" value="{{ $file['id'] }}">
                                    <input type="hidden" name="file_name" value="{{ $file['name'] }}">
                                    <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium"
                                        onclick="return confirmAction(event, 'Restore backup ini? Data saat ini akan ditimpa.')">
                                        Restore
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.backup.drive.delete') }}" class="inline"
                                    onsubmit="return confirmAction(event, 'Hapus backup ini dari Google Drive?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="file_id" value="{{ $file['id'] }}">
                                    <button type="submit"
                                        class="p-2 bg-red-600/20 text-red-400 hover:bg-red-600/40 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 md:py-16">
                    <svg class="w-16 h-16 md:w-20 md:h-20 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                    <p class="text-gray-300 text-base md:text-lg">Belum ada backup</p>
                    <p class="text-gray-500 text-sm mt-1">Klik salah satu tombol di atas untuk membuat backup pertama</p>
                </div>
            @endif
        </div>
    @else
        <!-- Not Connected State -->
        <div class="bg-slate-800/80 border border-slate-700 rounded-2xl p-8 md:p-16 text-center">
            <svg class="w-16 h-16 md:w-24 md:h-24 text-gray-500 mx-auto mb-6" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M12.545 10.239v3.821h5.445c-.712 2.315-2.647 3.972-5.445 3.972a6.033 6.033 0 110-12.064c1.498 0 2.866.549 3.921 1.453l2.814-2.814A9.969 9.969 0 0012.545 2C6.783 2 2 6.783 2 12.545c0 5.762 4.783 10.545 10.545 10.545 6.108 0 10.162-4.287 10.162-10.326 0-.692-.055-1.347-.173-1.978l-9.989-.047z" />
            </svg>
            <h3 class="text-white font-bold text-xl md:text-2xl mb-3">Hubungkan Google Drive</h3>
            <p class="text-gray-400 mb-8 max-w-md mx-auto text-sm md:text-base">
                Backup database dan file storage langsung ke Google Drive.
                Aman, otomatis, dan mudah di-restore kapan saja.
            </p>

            <a href="{{ route('admin.backup.drive.auth') }}"
                class="inline-flex items-center gap-3 md:gap-4 px-6 md:px-8 py-3 md:py-4 bg-white rounded-xl hover:bg-gray-100 transition shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                <svg class="w-6 h-6 md:w-8 md:h-8" viewBox="0 0 24 24">
                    <path fill="#4285F4"
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853"
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05"
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                    <path fill="#EA4335"
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                </svg>
                <span class="font-semibold text-gray-700 text-base md:text-lg">Hubungkan Google Drive</span>
            </a>
        </div>
    @endif
</x-layouts.admin>