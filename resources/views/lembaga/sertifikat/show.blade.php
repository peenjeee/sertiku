<x-layouts.lembaga>
    <div class="space-y-6">
        <!-- Header -->
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('lembaga.sertifikat.index') }}"
                    class="text-gray-400 hover:text-white mb-2 inline-flex items-center gap-1 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <h1 class="text-white text-2xl font-bold">Detail Sertifikat</h1>
            </div>
            <div class="flex flex-wrap gap-3">
                @if($certificate->pdf_path)
                    <div class="flex flex-wrap gap-4 mt-4">
                        <a href="{{ route('verifikasi.pdf', $certificate->hash) }}" target="_blank"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download PDF
                        </a>
                    </div>
                @endif
                <div class="flex flex-wrap gap-4 mt-4">
                    <a href="{{ route('verifikasi.show', $certificate->hash) }}" target="_blank"
                        class="px-4 py-2 bg-gray-700 rounded-lg text-white font-medium hover:bg-gray-600 transition flex items-center gap-2 text-sm sm:text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Halaman Publik
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Details -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status Card -->
                <div class="glass-card rounded-2xl p-6">
                    <h3 class="text-gray-900 font-bold mb-4">Status</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Status Sertifikat</span>
                            @php
                                $isExpired = $certificate->expire_date && \Carbon\Carbon::parse($certificate->expire_date)->isPast();
                            @endphp

                            @if($certificate->status === 'revoked')
                                <span
                                    class="px-3 py-1 bg-red-500/20 text-red-400 border border-red-500/30 rounded-lg text-sm font-medium">Dicabut</span>
                            @elseif($isExpired)
                                <span
                                    class="px-3 py-1 bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-lg text-sm font-medium">Expired</span>
                            @elseif($certificate->status === 'active')
                                <span
                                    class="px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-lg text-sm font-medium">Aktif</span>
                            @endif
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="text-gray-900 text-sm font-medium mb-3">Blockchain & IPFS</h4>

                            <div class="space-y-3">
                                <!-- Blockchain Status -->
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-gray-600 text-xs">Blockchain</span>
                                        @if($certificate->blockchain_tx_hash)
                                            <span class="text-purple-400 text-xs font-mono">Verified</span>
                                        @elseif($certificate->blockchain_status === 'pending')
                                            <span class="text-yellow-400 text-xs">Pending</span>
                                        @else
                                            <span class="text-gray-500 text-xs">Not Enabled</span>
                                        @endif
                                    </div>
                                    @if($certificate->blockchain_tx_hash)
                                        <a href="{{ $certificate->blockchain_explorer_url }}" target="_blank"
                                            class="text-xs text-blue-400 hover:text-blue-300 break-all underline">
                                            {{ Str::limit($certificate->blockchain_tx_hash, 30) }}
                                        </a>
                                    @endif
                                </div>

                                <!-- IPFS Status -->
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-gray-600 text-xs">IPFS</span>
                                        @if($certificate->ipfs_cid)
                                            <span class="text-teal-400 text-xs font-mono">Stored</span>
                                        @elseif($certificate->ipfs_status === 'pending')
                                            <span class="text-yellow-400 text-xs">Pending</span>
                                        @else
                                            <span class="text-gray-500 text-xs">Not Enabled</span>
                                        @endif
                                    </div>
                                    @if($certificate->ipfs_cid)
                                        <a href="{{ config('ipfs.gateway_url', 'https://gateway.pinata.cloud/ipfs') }}/{{ $certificate->ipfs_cid }}"
                                            target="_blank"
                                            class="text-xs text-blue-400 hover:text-blue-300 break-all underline">
                                            {{ Str::limit($certificate->ipfs_cid, 30) }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recipient Card -->
                <div class="glass-card rounded-2xl p-6">
                    <h3 class="text-gray-900 font-bold mb-4">Penerima</h3>
                    <div class="text-center mb-6">
                        @php
                            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($certificate->recipient_name) . '&background=random&color=fff&bold=true';
                            if ($certificate->recipient_email) {
                                $registeredUser = \App\Models\User::where('email', $certificate->recipient_email)->first();
                                if ($registeredUser && $registeredUser->avatar) {
                                    $avatarUrl = $registeredUser->avatar;
                                }
                            }
                        @endphp
                        <img src="{{ $avatarUrl }}" class="w-20 h-20 rounded-full mx-auto mb-3 object-cover bg-gray-100"
                            alt="{{ $certificate->recipient_name }}">
                        <h4 class="text-gray-900 font-bold text-lg">{{ $certificate->recipient_name }}</h4>
                        <p class="text-gray-500 text-sm">{{ $certificate->recipient_email ?? '-' }}</p>
                    </div>
                </div>

                <!-- Actions -->
                @if($certificate->status === 'active')
                    <div class="glass-card rounded-2xl p-6">
                        <h3 class="text-gray-900 font-bold mb-4">Tidakan</h3>
                        <form id="form-revoke" action="{{ route('lembaga.sertifikat.revoke', $certificate->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="block text-gray-600 text-xs mb-1">Alasan Pencabutan</label>
                                <textarea name="reason" rows="2"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-gray-900 text-sm focus:outline-none focus:border-red-500"
                                    placeholder="Contoh: Kesalahan penulisan nama"></textarea>
                            </div>
                            <button type="button" onclick="confirmRevoke()"
                                class="w-full py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 transition font-medium">
                                Cabut Sertifikat
                            </button>
                        </form>
                    </div>
                @elseif($certificate->status === 'revoked')
                    <div class="glass-card rounded-2xl p-6">
                        <h3 class="text-gray-900 font-bold mb-4">Tidakan</h3>
                        <div class="mb-4">
                            <span
                                class="text-xs font-semibold text-red-500 bg-red-50 px-2 py-1 rounded border border-red-100">
                                Sertifikat Dicabut
                            </span>
                            @if($certificate->revoked_reason)
                                <p class="text-gray-500 text-xs mt-2">
                                    <span class="font-medium">Alasan:</span> {{ $certificate->revoked_reason }}
                                </p>
                            @endif
                        </div>
                        <form id="form-reactivate" action="{{ route('lembaga.sertifikat.reactivate', $certificate->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <button type="button" onclick="confirmReactivate()"
                                class="w-full py-2 bg-green-50 text-green-600 border border-green-200 rounded-lg hover:bg-green-100 transition font-medium">
                                Aktifkan Sertifikat
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Right Column: Certificate Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="glass-card rounded-2xl p-6">
                    <h3 class="text-gray-900 font-bold mb-6">Informasi Sertifikat</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Nomor Sertifikat</p>
                            <p class="text-gray-900 font-mono font-medium">{{ $certificate->certificate_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Program / Kursus</p>
                            <p class="text-gray-900 font-medium">{{ $certificate->course_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Kategori</p>
                            <span class="px-2 py-1 bg-blue-500/20 text-blue-300 rounded text-sm inline-block mt-1">
                                {{ $certificate->category ?? 'Umum' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Tanggal Terbit</p>
                            <p class="text-gray-900 font-medium">{{ $certificate->issue_date->format('d F Y') }}</p>
                        </div>
                        @if($certificate->expire_date)
                            <div>
                                <p class="text-gray-500 text-sm mb-1">Tanggal Kadaluarsa</p>
                                <p class="text-gray-900 font-medium">
                                    {{ \Carbon\Carbon::parse($certificate->expire_date)->format('d F Y') }}
                                </p>
                            </div>
                        @endif
                        <div class="md:col-span-2">
                            <p class="text-gray-500 text-sm mb-1">Deskripsi</p>
                            <p class="text-gray-700 text-sm leading-relaxed">{{ $certificate->description ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Preview PDF -->
                @if($certificate->pdf_path)
                    <div class="glass-card rounded-2xl p-6">
                        <h3 class="text-gray-900 font-bold mb-4">Preview Sertifikat</h3>

                        @php
                            $isPortrait = ($certificate->template && ($certificate->template->orientation ?? 'landscape') === 'portrait');
                            $aspectRatio = $isPortrait ? 'aspect-[794/1123]' : 'aspect-[1123/794]';
                        @endphp

                        {{-- Desktop: PDF Iframe --}}
                        <div class="hidden md:block w-full bg-gray-800 rounded-lg overflow-hidden {{ $aspectRatio }}">
                            <iframe
                                src="{{ route('verifikasi.pdf', ['hash' => $certificate->hash, 'stream' => 1]) }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                                class="w-full h-full border-0"></iframe>
                        </div>

                        {{-- Mobile: PDF.js Canvas Renderer --}}
                        <div
                            class="md:hidden relative w-full rounded-xl overflow-hidden border border-[rgba(255,255,255,0.2)] bg-gray-800 shadow-2xl">
                            <div id="pdf-container" class="relative {{ $aspectRatio }} flex items-center justify-center">
                                <div id="pdf-loading" class="absolute inset-0 flex items-center justify-center bg-gray-800">
                                    <div class="text-center">
                                        <div
                                            class="animate-spin rounded-full h-12 w-12 border-4 border-white/20 border-t-blue-500 mx-auto mb-3">
                                        </div>
                                        <p class="text-white/70 text-sm">Memuat sertifikat...</p>
                                    </div>
                                </div>
                                <canvas id="pdf-canvas" class="w-full h-full object-contain hidden"></canvas>
                            </div>
                        </div>
                    </div>

                    @push('scripts')
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
                        <script>
                            if (window.innerWidth < 768) {
                                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                                const pdfUrl = "{{ route('verifikasi.pdf', ['hash' => $certificate->hash, 'stream' => 1]) }}";
                                const canvas = document.getElementById('pdf-canvas');
                                const container = document.getElementById('pdf-container');
                                const loading = document.getElementById('pdf-loading');
                                const ctx = canvas.getContext('2d');

                                pdfjsLib.getDocument(pdfUrl).promise.then(function (pdf) {
                                    pdf.getPage(1).then(function (page) {
                                        const containerWidth = container.clientWidth;
                                        const viewport = page.getViewport({ scale: 1 });
                                        const scale = containerWidth / viewport.width;
                                        const scaledViewport = page.getViewport({ scale: scale * 2 }); // Retina ready
                                        canvas.height = scaledViewport.height;
                                        canvas.width = scaledViewport.width;
                                        canvas.style.width = '100%';
                                        canvas.style.height = 'auto';
                                        page.render({
                                            canvasContext: ctx,
                                            viewport: scaledViewport
                                        }).promise.then(function () {
                                            loading.classList.add('hidden');
                                            canvas.classList.remove('hidden');
                                        });
                                    });
                                }).catch(function (error) {
                                    console.error('PDF error:', error);
                                    loading.innerHTML = '<p class="text-white/70 text-sm p-4">Preview gagal dimuat.</p>';
                                });
                            }
                        </script>
                    @endpush
                @else
                    <div class="glass-card rounded-2xl p-12 text-center">
                        <div class="w-16 h-16 bg-gray-700/50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-gray-900 font-medium mb-1">File PDF Belum Tersedia</h3>
                        <p class="text-gray-500 text-sm">PDF untuk sertifikat ini belum digenerate atau sedang dalam proses.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script>
        function confirmRevoke() {
            const reasonInput = document.querySelector('#form-revoke textarea[name="reason"]');
            const reason = reasonInput.value.trim();

            if (!reason) {
                Swal.fire({
                    icon: 'error',
                    title: 'Wajib Diisi',
                    text: 'Mohon isi alasan pencabutan sertifikat terlebih dahulu.',
                    background: '#1f2937',
                    color: '#fff',
                    confirmButtonColor: '#3B82F6'
                });
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Sertifikat akan dicabut dan tidak valid lagi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Cabut!',
                cancelButtonText: 'Batal',
                background: '#1f2937',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-revoke').submit();
                }
            })
        }

        function confirmReactivate() {
            Swal.fire({
                title: 'Aktifkan Kembali?',
                text: "Sertifikat akan kembali valid dan status dicabut akan dihapus.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Aktifkan!',
                cancelButtonText: 'Batal',
                background: '#1f2937',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-reactivate').submit();
                }
            })
        }
    </script>
</x-layouts.lembaga>