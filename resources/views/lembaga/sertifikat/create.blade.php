<x-layouts.lembaga>
    @php
        $user = Auth::user();
        $isStarterPlan = $user->isStarterPlan();
        $certificateLimit = $user->getCertificateLimit();
        $certificatesUsed = $user->getCertificatesUsedThisMonth();
        $remainingCerts = $user->getRemainingCertificates();
        $canIssue = $user->canIssueCertificate();
        $usagePercentage = $user->getUsagePercentage();
    @endphp

    <div class="space-y-6">
        <!-- Header -->
        <div class="info-box rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-black text-2xl font-bold mb-2">Terbitkan Sertifikat Baru</h1>
                    <p class="text-black/70 text-base">Terbitkan sertifikat digital untuk peserta program Anda</p>
                </div>
                <!-- Usage Badge -->
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-black/60 text-xs">Sisa Kuota Bulan Ini</p>
                        <p class="text-black font-bold">{{ $remainingCerts }} / {{ $certificateLimit }}</p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-full flex items-center justify-center {{ $remainingCerts <= 10 ? 'bg-red-500/20' : 'bg-blue-500/20' }}">
                        <span
                            class="text-lg font-bold {{ $remainingCerts <= 10 ? 'text-red-400' : 'text-blue-400' }}">{{ $remainingCerts }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
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

        @if($errors->any())
            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4">
                <ul class="text-red-400 text-sm list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Limit Reached Block -->
        @if(!$canIssue)
            <div class="bg-red-500/20 border-2 border-red-500/50 rounded-2xl p-8 text-center">
                <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h2 class="text-red-400 text-xl font-bold mb-2">Kuota Sertifikat Habis</h2>
                <p class="text-red-300/80 mb-6">Upgrade paket Anda untuk melanjutkan menerbitkan sertifikat.</p>
                <a href="{{ url('/#harga') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl">
                    Upgrade ke Professional
                </a>
            </div>
        @else

            <!-- Certificate Form -->
            <form id="certificate-form" action="{{ route('lembaga.sertifikat.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Template Selection Section -->
                <div class="glass-card rounded-2xl p-6">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-200 mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-gray-800 text-base font-bold">Pilih Template</span>
                        </div>
                        <a href="{{ route('lembaga.template.upload') }}"
                            class="text-blue-600 text-sm font-medium hover:underline">
                            + Upload Template Baru
                        </a>
                    </div>
                    {{-- Reduced context for brevity, but I need to be careful with replace_file_content --}}
                    {{-- Actually, since start/end lines are far apart (77 vs 447), I should do this in TWO chunks or use
                    multi_replace --}}
                    {{-- Wait, the tool definition says "Do NOT if only editing a single contiguous block". But here I need
                    to edit TWO separate places. --}}
                    {{-- So I MUST use multi_replace_file_content. --}}

                    @if(isset($templates) && $templates->count() > 0)
                        <!-- Template Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                            <!-- No Template Option -->
                            <label class="cursor-pointer">
                                <input type="radio" name="template_id" value="" class="hidden peer" checked>
                                <div
                                    class="border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 rounded-xl p-4 text-center transition hover:border-blue-300">
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-700 text-sm font-medium">Tanpa Template</p>
                                    <p class="text-gray-400 text-xs">Default</p>
                                </div>
                            </label>

                            <!-- Template Cards -->
                            @foreach($templates as $template)
                                <label class="cursor-pointer">
                                    <input type="radio" name="template_id" value="{{ $template->id }}" class="hidden peer">
                                    <div
                                        class="border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 rounded-xl overflow-hidden transition hover:border-blue-300">
                                        <!-- Template Preview -->
                                        <div
                                            class="aspect-[4/3] bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                            @if($template->thumbnail_path)
                                                <img src="{{ asset('storage/' . $template->thumbnail_path) }}"
                                                    alt="{{ $template->name }}" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <!-- Template Info -->
                                        <div class="p-2 text-center">
                                            <p class="text-gray-700 text-xs font-medium truncate">{{ $template->name }}</p>
                                            <p class="text-gray-400 text-xs">
                                                {{ $template->orientation == 'landscape' ? 'Landscape' : 'Portrait' }}
                                            </p>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <!-- No Templates - Empty State -->
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-gray-600 font-medium mb-1">Belum ada template</p>
                            <p class="text-gray-400 text-sm mb-4">Upload template untuk membuat sertifikat lebih menarik</p>
                            <a href="{{ route('lembaga.template.upload') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Upload Template
                            </a>
                        </div>
                        <input type="hidden" name="template_id" value="">
                    @endif
                </div>

                <!-- Form Card - Informasi Program -->
                <div class="glass-card rounded-2xl p-6 space-y-6">
                    <div class="flex items-center gap-2 pb-3 border-b border-gray-200">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="text-gray-800 text-base font-bold">Informasi Program</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-gray-700 text-sm font-bold">
                                Nama Kursus/Program<span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="course_name" value="{{ old('course_name') }}" required
                                placeholder="Contoh: Web Development Bootcamp 2024"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="space-y-2">
                            <label class="text-gray-700 text-sm font-bold">Kategori Event</label>
                            <select name="category"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih kategori</option>
                                <option value="Bootcamp" {{ old('category') == 'Bootcamp' ? 'selected' : '' }}>Bootcamp
                                </option>
                                <option value="Workshop" {{ old('category') == 'Workshop' ? 'selected' : '' }}>Workshop
                                </option>
                                <option value="Seminar" {{ old('category') == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                                <option value="Sertifikasi" {{ old('category') == 'Sertifikasi' ? 'selected' : '' }}>
                                    Sertifikasi</option>
                                <option value="Pelatihan" {{ old('category') == 'Pelatihan' ? 'selected' : '' }}>Pelatihan
                                </option>
                                <option value="Webinar" {{ old('category') == 'Webinar' ? 'selected' : '' }}>Webinar</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-gray-700 text-sm font-bold">Deskripsi (Opsional)</label>
                        <textarea name="description" rows="2" placeholder="Deskripsi singkat tentang program/kursus"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-gray-700 text-sm font-bold">Tanggal Penerbitan<span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="issue_date" value="{{ old('issue_date', now()->format('Y-m-d')) }}"
                                required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="space-y-2">
                            <label class="text-gray-700 text-sm font-bold">Tanggal Kadaluarsa (Opsional)</label>
                            <input type="date" name="expire_date" value="{{ old('expire_date') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Form Card - Informasi Penerima -->
                <div class="glass-card rounded-2xl p-6 space-y-6">
                    <div class="flex items-center gap-2 pb-3 border-b border-gray-200">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-gray-800 text-base font-bold">Informasi Penerima</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-gray-700 text-sm font-bold">
                                Nama Penerima<span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="recipient_name" value="{{ old('recipient_name') }}" required
                                placeholder="Contoh: John Doe"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="space-y-2">
                            <label class="text-gray-700 text-sm font-bold">Email Penerima</label>
                            <input type="email" name="recipient_email" id="recipient_email"
                                value="{{ old('recipient_email') }}" placeholder="Contoh: john@example.com"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Send Email Option -->
                    <div id="send-email-option" class="hidden">
                        <div class="flex items-start gap-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="send_email" id="send_email" value="1" class="sr-only peer" {{ old('send_email', true) ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                </div>
                            </label>
                            <div class="flex-1">
                                <p class="text-gray-800 font-medium text-sm">Kirim email ke penerima</p>
                                <p class="text-gray-500 text-xs mt-1">Penerima akan menerima email berisi informasi
                                    sertifikat, link verifikasi, dan link download.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Blockchain Option -->
                @if(config('blockchain.enabled'))
                    <div class="glass-card rounded-2xl p-6">
                        <div class="flex items-center pb-3 border-b border-gray-200 mb-4">
                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            <span class="text-gray-800 text-base font-bold">Blockchain Verification</span>
                            <span
                                class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-600 text-xs font-medium rounded-full">GRATIS</span>
                        </div>

                        <div class="flex items-start gap-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="blockchain_enabled" value="1" class="sr-only peer" {{ old('blockchain_enabled') ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600">
                                </div>
                            </label>
                            <div class="flex-1">
                                <p class="text-gray-800 font-medium text-sm">Upload ke Blockchain (Polygon)</p>
                                <p class="text-gray-500 text-xs mt-1">Simpan hash sertifikat ke jaringan Polygon untuk
                                    verifikasi tambahan yang tidak dapat dipalsukan.</p>
                                <div class="flex items-center gap-4 mt-3 text-xs">
                                    <span class="flex items-center gap-1 text-emerald-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Immutable
                                    </span>
                                    <span class="flex items-center gap-1 text-emerald-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Terverifikasi
                                    </span>
                                    <span class="flex items-center gap-1 text-purple-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101" />
                                        </svg>
                                        PolygonScan
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- IPFS Option -->
                @if(config('ipfs.enabled'))
                    <div class="glass-card rounded-2xl p-6">
                        <div class="flex items-center pb-3 border-b border-gray-200 mb-4">
                            <svg class="w-5 h-5 text-cyan-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <span class="text-gray-800 text-base font-bold">IPFS Storage</span>
                            <span
                                class="ml-2 px-2 py-0.5 bg-cyan-100 text-cyan-600 text-xs font-medium rounded-full">Web3</span>
                        </div>

                        <div class="flex items-start gap-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="ipfs_enabled" value="1" class="sr-only peer" {{ old('ipfs_enabled') ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-cyan-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-600">
                                </div>
                            </label>
                            <div class="flex-1">
                                <p class="text-gray-800 font-medium text-sm">Upload ke IPFS (Storacha)</p>
                                <p class="text-gray-500 text-xs mt-1">Simpan metadata sertifikat ke jaringan IPFS + Filecoin
                                    untuk
                                    penyimpanan desentralisasi permanen.</p>
                                <div class="flex items-center gap-4 mt-3 text-xs">
                                    <span class="flex items-center gap-1 text-emerald-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Desentralisasi
                                    </span>
                                    <span class="flex items-center gap-1 text-emerald-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Permanen
                                    </span>
                                    <span class="flex items-center gap-1 text-cyan-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9" />
                                        </svg>
                                        IPFS Gateway
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Info Box -->
                <div class="bg-blue-100 border border-blue-300 rounded-xl p-5">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-blue-800 text-sm font-bold mb-1">Informasi Penting:</p>
                            <ul class="text-blue-700 text-sm space-y-1">
                                <li>• ID sertifikat akan dibuat secara otomatis</li>
                                <li>• Sertifikat akan langsung aktif setelah diterbitkan</li>
                                <li>• Penerima dapat memverifikasi sertifikat menggunakan nomor sertifikat</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Progress Bar (hidden by default) --}}
                <div id="upload-progress" class="hidden mb-6">
                    <div class="bg-[#1E3A8A] rounded-xl p-5 border border-blue-500/30 shadow-lg">
                        <div class="flex items-center gap-3 mb-3">
                            <svg class="animate-spin h-5 w-5 text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span class="text-white font-bold text-sm">Menerbitkan Sertifikat...</span>
                        </div>
                        <div class="w-full bg-blue-900/50 rounded-full h-3 overflow-hidden">
                            <div id="progress-bar"
                                class="bg-gradient-to-r from-blue-400 to-cyan-400 h-3 rounded-full transition-all duration-300"
                                style="width: 0%"></div>
                        </div>
                        <div class="flex justify-between mt-2">
                            <span id="progress-text" class="text-blue-200 text-xs">Memproses data...</span>
                            <span id="progress-percent" class="text-cyan-300 text-xs font-bold">0%</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div id="action-buttons" class="flex items-center gap-4">
                    <button type="submit" id="submit-btn"
                        class="flex-1 flex items-center justify-center gap-2 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl text-white text-lg font-bold hover:from-blue-700 hover:to-indigo-700 transition">
                        <svg id="submit-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span id="submit-text">Terbitkan Sertifikat</span>
                    </button>
                    <a href="{{ route('lembaga.sertifikat.index') }}"
                        class="px-8 py-4 bg-white/10 border border-white/20 rounded-xl text-white text-sm font-bold hover:bg-white/20 transition">
                        Batal
                    </a>
                </div>
            </form>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('certificate-form');
            if (!form) return;

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                // Check if blockchain enabled
                const blockchainEnabled = form.querySelector('input[name="blockchain_enabled"]')?.checked;
                const estimatedTime = blockchainEnabled ? 30 : 5;

                // Show SweetAlert with loading
                Swal.fire({
                    title: 'Menerbitkan Sertifikat...',
                    html: `
                        <div class="text-left mb-4">
                            <p class="text-gray-600 mb-2">Mohon tunggu, proses sedang berjalan.</p>
                            ${blockchainEnabled ? '<p class="text-purple-600 text-sm"><strong>Blockchain:</strong> Menyimpan ke Polygon Network</p>' : ''}
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                            <div id="swal-progress" class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <p id="swal-status" class="text-sm text-gray-500">Memvalidasi data...</p>
                        <p class="text-xs text-gray-400 mt-2">Estimasi: <span id="swal-countdown">${estimatedTime}</span> detik</p>
                    `,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();

                        // Animate progress and countdown
                        let progress = 0;
                        let countdown = estimatedTime;
                        const statusEl = document.getElementById('swal-status');
                        const progressEl = document.getElementById('swal-progress');
                        const countdownEl = document.getElementById('swal-countdown');

                        const statuses = [
                            { at: 10, text: 'Membuat sertifikat...' },
                            { at: 25, text: 'Generating QR Code...' },
                            { at: 40, text: 'Menyimpan ke database...' },
                            { at: 55, text: blockchainEnabled ? 'Menghubungi jaringan Polygon...' : 'Memproses...' },
                            { at: 70, text: blockchainEnabled ? 'Menyimpan hash ke blockchain...' : 'Hampir selesai...' },
                            { at: 85, text: blockchainEnabled ? 'Menunggu konfirmasi blockchain...' : 'Menyelesaikan...' },
                            { at: 95, text: 'Selesai!' }
                        ];

                        const progressInterval = setInterval(() => {
                            if (progress < 95) {
                                progress += Math.random() * 8 + 2;
                                progress = Math.min(progress, 95);
                                if (progressEl) progressEl.style.width = progress + '%';

                                for (let i = statuses.length - 1; i >= 0; i--) {
                                    if (progress >= statuses[i].at) {
                                        if (statusEl) statusEl.textContent = statuses[i].text;
                                        break;
                                    }
                                }
                            }
                        }, 400);

                        const countdownInterval = setInterval(() => {
                            countdown--;
                            if (countdownEl) countdownEl.textContent = Math.max(0, countdown);
                            if (countdown <= 0) clearInterval(countdownInterval);
                        }, 1000);

                        // Submit form via AJAX
                        const formData = new FormData(form);

                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                            .then(response => {
                                clearInterval(progressInterval);
                                clearInterval(countdownInterval);

                                if (progressEl) progressEl.style.width = '100%';
                                if (statusEl) statusEl.textContent = 'Selesai!';

                                if (response.ok || response.redirected) {
                                    // Success
                                    setTimeout(() => {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Sertifikat Berhasil Diterbitkan!',
                                            html: blockchainEnabled
                                                ? '<p class="text-gray-600">Sertifikat telah tersimpan di database dan blockchain.</p>'
                                                : '<p class="text-gray-600">Sertifikat telah tersimpan di database.</p>',
                                            confirmButtonText: 'Lihat Daftar Sertifikat',
                                            confirmButtonColor: '#4F46E5',
                                            timer: 3000,
                                            timerProgressBar: true
                                        }).then(() => {
                                            window.location.href = '{{ route("lembaga.sertifikat.index") }}?success=1';
                                        });
                                    }, 500);
                                } else {
                                    // Error
                                    response.json().then(data => {
                                        let errorMessage = 'Terjadi kesalahan saat menerbitkan sertifikat.';
                                        if (data.errors) {
                                            errorMessage = Object.values(data.errors).flat().join('<br>');
                                        } else if (data.message) {
                                            errorMessage = data.message;
                                        }

                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal Menerbitkan Sertifikat',
                                            html: errorMessage,
                                            confirmButtonText: 'Coba Lagi',
                                            confirmButtonColor: '#EF4444'
                                        });
                                    }).catch(() => {
                                        // If not JSON, might be redirect - consider it success
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Sertifikat Berhasil Diterbitkan!',
                                            confirmButtonText: 'Lihat Daftar Sertifikat',
                                            confirmButtonColor: '#4F46E5',
                                            timer: 3000,
                                            timerProgressBar: true
                                        }).then(() => {
                                            window.location.href = '{{ route("lembaga.sertifikat.index") }}?success=1';
                                        });
                                    });
                                }
                            })
                            .catch(error => {
                                clearInterval(progressInterval);
                                clearInterval(countdownInterval);

                                // Network error - but form might have submitted, redirect anyway
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Proses Selesai',
                                    text: 'Silakan cek daftar sertifikat untuk memastikan sertifikat berhasil dibuat.',
                                    confirmButtonText: 'Lihat Daftar Sertifikat',
                                    confirmButtonColor: '#4F46E5'
                                }).then(() => {
                                    window.location.href = '{{ route("lembaga.sertifikat.index") }}';
                                });
                            });
                    }
                });
            });

            // Toggle send email option based on email input
            const recipientEmailInput = document.getElementById('recipient_email');
            const sendEmailOption = document.getElementById('send-email-option');
            const sendEmailCheckbox = document.getElementById('send_email');

            function toggleSendEmailOption() {
                if (recipientEmailInput && sendEmailOption) {
                    const hasEmail = recipientEmailInput.value.trim() !== '';
                    if (hasEmail) {
                        sendEmailOption.classList.remove('hidden');
                        // Auto-check the checkbox when email is first entered
                        if (sendEmailCheckbox && !sendEmailCheckbox.dataset.userChanged) {
                            sendEmailCheckbox.checked = true;
                        }
                    } else {
                        sendEmailOption.classList.add('hidden');
                    }
                }
            }

            // Track if user manually changed the checkbox
            if (sendEmailCheckbox) {
                sendEmailCheckbox.addEventListener('change', function () {
                    this.dataset.userChanged = 'true';
                });
            }

            if (recipientEmailInput) {
                recipientEmailInput.addEventListener('input', toggleSendEmailOption);
                // Check on load in case of old() value
                toggleSendEmailOption();
            }
        });
    </script>
</x-layouts.lembaga>