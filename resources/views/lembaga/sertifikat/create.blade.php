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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h2 class="text-red-400 text-xl font-bold mb-2">Kuota Sertifikat Habis</h2>
            <p class="text-red-300/80 mb-6">Upgrade paket Anda untuk melanjutkan menerbitkan sertifikat.</p>
            <a href="{{ url('/#harga') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl">
                Upgrade ke Professional
            </a>
        </div>
        @else

        <!-- Certificate Form -->
        <form action="{{ route('lembaga.sertifikat.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Template Selection Section -->
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between pb-3 border-b border-gray-200 mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-800 text-base font-bold">Pilih Template</span>
                    </div>
                    <a href="{{ route('lembaga.template.upload') }}" class="text-blue-600 text-sm font-medium hover:underline">
                        + Upload Template Baru
                    </a>
                </div>

                @if(isset($templates) && $templates->count() > 0)
                <!-- Template Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- No Template Option -->
                    <label class="cursor-pointer">
                        <input type="radio" name="template_id" value="" class="hidden peer" checked>
                        <div class="border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 rounded-xl p-4 text-center transition hover:border-blue-300">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
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
                        <div class="border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 rounded-xl overflow-hidden transition hover:border-blue-300">
                            <!-- Template Preview -->
                            <div class="aspect-[4/3] bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                @if($template->thumbnail_path)
                                <img src="{{ asset('storage/' . $template->thumbnail_path) }}" alt="{{ $template->name }}" class="w-full h-full object-cover">
                                @else
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                @endif
                            </div>
                            <!-- Template Info -->
                            <div class="p-2 text-center">
                                <p class="text-gray-700 text-xs font-medium truncate">{{ $template->name }}</p>
                                <p class="text-gray-400 text-xs">{{ $template->orientation == 'landscape' ? 'Landscape' : 'Portrait' }}</p>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-gray-600 font-medium mb-1">Belum ada template</p>
                    <p class="text-gray-400 text-sm mb-4">Upload template untuk membuat sertifikat lebih menarik</p>
                    <a href="{{ route('lembaga.template.upload') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
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
                        <select name="category" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
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

                <div class="space-y-2">
                    <label class="text-gray-700 text-sm font-bold">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi singkat tentang program/kursus"
                              class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-gray-700 text-sm font-bold">Tanggal Penerbitan<span class="text-red-500">*</span></label>
                        <input type="date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
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
                        <label class="text-gray-700 text-sm font-bold">Email Penerima (Opsional)</label>
                        <input type="email" name="recipient_email" value="{{ old('recipient_email') }}"
                               placeholder="Contoh: john@example.com"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-100 border border-blue-300 rounded-xl p-5">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                <div class="bg-gray-200 rounded-xl p-5 border border-gray-300">
                    <div class="flex items-center gap-3 mb-3">
                        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-gray-800 font-bold text-sm">Menerbitkan Sertifikat...</span>
                    </div>
                    <div class="w-full bg-gray-300 rounded-full h-3 overflow-hidden">
                        <div id="progress-bar" class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <div class="flex justify-between mt-2">
                        <span id="progress-text" class="text-gray-600 text-xs">Memproses data...</span>
                        <span id="progress-percent" class="text-blue-600 text-xs font-bold">0%</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div id="action-buttons" class="flex items-center gap-4">
                <button type="submit" id="submit-btn" class="flex-1 flex items-center justify-center gap-2 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl text-white text-lg font-bold hover:from-blue-700 hover:to-indigo-700 transition">
                    <svg id="submit-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="submit-text">Terbitkan Sertifikat</span>
                </button>
                <a href="{{ route('lembaga.sertifikat.index') }}" class="px-8 py-4 bg-white/10 border border-white/20 rounded-xl text-white text-sm font-bold hover:bg-white/20 transition">
                    Batal
                </a>
            </div>
        </form>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                // Show progress bar
                const progressContainer = document.getElementById('upload-progress');
                const actionButtons = document.getElementById('action-buttons');
                const progressBar = document.getElementById('progress-bar');
                const progressText = document.getElementById('progress-text');
                const progressPercent = document.getElementById('progress-percent');
                const submitBtn = document.getElementById('submit-btn');

                // Disable submit button
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

                // Show progress bar
                progressContainer.classList.remove('hidden');

                // Animate progress bar
                let progress = 0;
                const messages = [
                    { at: 0, text: 'Memvalidasi data...' },
                    { at: 20, text: 'Membuat sertifikat...' },
                    { at: 40, text: 'Generating QR Code...' },
                    { at: 60, text: 'Menyimpan ke database...' },
                    { at: 80, text: 'Memproses selesai...' },
                    { at: 95, text: 'Menyelesaikan...' }
                ];

                const interval = setInterval(() => {
                    if (progress < 95) {
                        progress += Math.random() * 10 + 2;
                        progress = Math.min(progress, 95);

                        progressBar.style.width = progress + '%';
                        progressPercent.textContent = Math.round(progress) + '%';

                        // Update message based on progress
                        for (let i = messages.length - 1; i >= 0; i--) {
                            if (progress >= messages[i].at) {
                                progressText.textContent = messages[i].text;
                                break;
                            }
                        }
                    }
                }, 200);

                // Store interval ID to clear later
                form.dataset.intervalId = interval;
            });
        });
    </script>
</x-layouts.lembaga>
