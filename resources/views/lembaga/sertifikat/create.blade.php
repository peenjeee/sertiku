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
                    <h1 class="text-white text-2xl font-bold mb-2">Terbitkan Sertifikat Baru</h1>
                    <p class="text-white/70 text-base">Terbitkan sertifikat digital untuk peserta program Anda</p>
                </div>
                <!-- Usage Badge -->
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-white/60 text-xs">Sisa Kuota Bulan Ini</p>
                        <p class="text-white font-bold">{{ $remainingCerts }} / {{ $certificateLimit }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $remainingCerts <= 10 ? 'bg-red-500/20' : 'bg-blue-500/20' }}">
                        <span class="text-lg font-bold {{ $remainingCerts <= 10 ? 'text-red-400' : 'text-blue-400' }}">{{ $remainingCerts }}</span>
                    </div>
                </div>
            </div>
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

        @if($errors->any())
        <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4">
            <p class="text-red-400 font-bold mb-2">Ada kesalahan:</p>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h2 class="text-red-400 text-xl font-bold mb-2">Kuota Sertifikat Habis</h2>
            <p class="text-red-300/80 mb-6">Anda telah mencapai batas {{ $certificateLimit }} sertifikat untuk bulan ini.<br>Upgrade paket Anda untuk melanjutkan menerbitkan sertifikat.</p>
            <a href="{{ url('/#harga') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                Upgrade ke Professional
            </a>
        </div>
        @else

        <!-- Certificate Form -->
        <form action="{{ route('lembaga.sertifikat.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Template Selection (if available) -->
            @if(isset($templates) && $templates->count() > 0)
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center gap-2 pb-3 border-b border-white/10 mb-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-white text-base font-bold">Pilih Template (Opsional)</span>
                </div>
                <select name="template_id" class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tanpa Template</option>
                    @foreach($templates as $template)
                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Form Card - Informasi Program -->
            <div class="glass-card rounded-2xl p-6 space-y-6">
                <div class="flex items-center gap-2 pb-3 border-b border-white/10">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span class="text-white text-base font-bold">Informasi Program</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Kursus -->
                    <div class="space-y-2">
                        <label class="text-white text-sm font-bold">
                            Nama Kursus/Program<span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="course_name" value="{{ old('course_name') }}" required
                               placeholder="Contoh: Web Development Bootcamp 2024"
                               class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Kategori Event -->
                    <div class="space-y-2">
                        <label class="text-white text-sm font-bold">Kategori Event</label>
                        <select name="category" class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih kategori</option>
                            <option value="Bootcamp" {{ old('category') == 'Bootcamp' ? 'selected' : '' }}>Bootcamp</option>
                            <option value="Workshop" {{ old('category') == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                            <option value="Seminar" {{ old('category') == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                            <option value="Sertifikasi" {{ old('category') == 'Sertifikasi' ? 'selected' : '' }}>Sertifikasi</option>
                            <option value="Pelatihan" {{ old('category') == 'Pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                            <option value="Webinar" {{ old('category') == 'Webinar' ? 'selected' : '' }}>Webinar</option>
                        </select>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="space-y-2">
                    <label class="text-white text-sm font-bold">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi singkat tentang program/kursus"
                              class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>

                <!-- Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-white text-sm font-bold">Tanggal Penerbitan<span class="text-red-400">*</span></label>
                        <input type="date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required
                               class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="space-y-2">
                        <label class="text-white text-sm font-bold">Tanggal Kadaluarsa (Opsional)</label>
                        <input type="date" name="expire_date" value="{{ old('expire_date') }}"
                               class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Form Card - Informasi Penerima -->
            <div class="glass-card rounded-2xl p-6 space-y-6">
                <div class="flex items-center gap-2 pb-3 border-b border-white/10">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-white text-base font-bold">Informasi Penerima</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Penerima -->
                    <div class="space-y-2">
                        <label class="text-white text-sm font-bold">
                            Nama Penerima<span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="recipient_name" value="{{ old('recipient_name') }}" required
                               placeholder="Contoh: John Doe"
                               class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Email Penerima -->
                    <div class="space-y-2">
                        <label class="text-white text-sm font-bold">Email Penerima (Opsional)</label>
                        <input type="email" name="recipient_email" value="{{ old('recipient_email') }}"
                               placeholder="Contoh: john@example.com"
                               class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-white text-sm placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-500/20 border border-blue-500/30 rounded-xl p-5">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-white text-sm font-bold mb-1">Informasi Penting:</p>
                        <ul class="text-white/80 text-sm space-y-1">
                            <li>• ID sertifikat akan dibuat secara otomatis</li>
                            <li>• Sertifikat akan langsung aktif setelah diterbitkan</li>
                            <li>• Penerima dapat memverifikasi sertifikat menggunakan nomor sertifikat</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4">
                <button type="submit" class="flex-1 flex items-center justify-center gap-2 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl text-white text-lg font-bold hover:from-blue-700 hover:to-indigo-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Terbitkan Sertifikat
                </button>
                <a href="{{ route('lembaga.sertifikat.index') }}" class="px-8 py-4 bg-white/10 border border-white/20 rounded-xl text-white text-sm font-bold hover:bg-white/20 transition">
                    Batal
                </a>
            </div>
        </form>
        @endif
    </div>
</x-layouts.lembaga>
