<x-layouts.lembaga>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-white text-xl lg:text-2xl font-bold">Galeri Template Sertifikat</h1>
                <p class="text-white/70 text-sm lg:text-base mt-1">
                    Kelola template sertifikat yang telah diupload ({{ $stats['total'] ?? 0 }} template)
                </p>
            </div>
            <a href="{{ route('lembaga.template.upload') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg text-white text-sm font-bold hover:from-blue-700 hover:to-indigo-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Upload Template
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500/30 rounded-lg p-4 text-emerald-400">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4 text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-blue-500/20 border border-blue-500/30 rounded-xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-500/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-white text-xl font-bold">{{ $stats['total'] ?? 0 }}</p>
                    <p class="text-white/60 text-xs">Total Template</p>
                </div>
            </div>
            <div class="bg-emerald-500/20 border border-emerald-500/30 rounded-xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-500/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-white text-xl font-bold">{{ $stats['active'] ?? 0 }}</p>
                    <p class="text-white/60 text-xs">Template Aktif</p>
                </div>
            </div>
        </div>

        <!-- Template Grid -->
        @if(isset($templates) && $templates->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($templates as $template)
                    <div class="glass-card rounded-2xl overflow-hidden group hover:shadow-lg transition">
                        <!-- Template Preview -->
                        <div
                            class="relative aspect-[4/3] bg-gradient-to-br from-slate-700 to-slate-800 flex items-center justify-center">
                            @if($template->thumbnail_path)
                                <img src="{{ asset('storage/' . $template->thumbnail_path) }}" alt="{{ $template->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="text-center p-4">
                                    <svg class="w-16 h-16 text-white/30 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-white/50 text-sm">
                                        {{ strtoupper(pathinfo($template->file_path, PATHINFO_EXTENSION)) }}</p>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="absolute top-3 left-3">
                                @if($template->is_active)
                                    <span class="px-2 py-1 bg-emerald-500 text-white text-xs font-bold rounded">Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-500 text-white text-xs font-bold rounded">Nonaktif</span>
                                @endif
                            </div>

                            <!-- Hover Actions -->
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-3">
                                <a href="{{ asset('storage/' . $template->file_path) }}" target="_blank"
                                    class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-800 hover:bg-gray-100 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <form action="{{ route('lembaga.template.toggle', $template) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white hover:bg-amber-600 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </button>
                                </form>
                                <form action="{{ route('lembaga.template.destroy', $template) }}" method="POST" class="inline"
                                    onsubmit="return confirmAction(event, 'Hapus template ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center text-white hover:bg-red-600 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Template Info -->
                        <div class="p-4">
                            <h3 class="text-gray-800 font-bold truncate">{{ $template->name }}</h3>
                            <p class="text-gray-500 text-sm mt-1">
                                {{ $template->orientation == 'landscape' ? 'Landscape' : 'Portrait' }}</p>
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-200">
                                <span class="text-gray-400 text-xs">Digunakan {{ $template->usage_count }}x</span>
                                <span class="text-gray-400 text-xs">{{ $template->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $templates->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="glass-card rounded-2xl p-12 text-center">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-gray-800 text-xl font-bold mb-2">Belum Ada Template</h3>
                <p class="text-gray-500 mb-6">Upload template sertifikat pertama Anda untuk mulai menerbitkan sertifikat</p>
                <a href="{{ route('lembaga.template.upload') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Upload Template
                </a>
            </div>
        @endif
    </div>
</x-layouts.lembaga>