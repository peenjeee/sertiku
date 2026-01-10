<x-layouts.lembaga>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-white text-xl lg:text-2xl font-bold">Buat Template Sertifikat</h1>
            <p class="text-white/70 text-sm lg:text-base mt-1">Pilih desain profesional dari sistem kami untuk
                sertifikat Anda.</p>
        </div>

        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: '{{ session('error') }}',
                        confirmButtonColor: '#3085d6',
                    });
                });
            </script>
        @endif

        <form action="{{ route('lembaga.template.storeSystem') }}" method="POST" class="space-y-8"
            id="createTemplateForm" novalidate>
            @csrf

            <!-- Nama & Orientasi -->
            <div class="glass-card rounded-2xl p-6 space-y-6">
                <!-- Nama Template -->
                <div>
                    <label class="block text-gray-800 text-sm font-bold mb-2">Nama Template Baru</label>
                    <input type="text" name="name" placeholder="Contoh: Sertifikat Webinar 2024"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Orientasi -->
                <div>
                    <label class="block text-gray-800 text-sm font-bold mb-2">Orientasi</label>
                    <div class="flex gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="orientation" value="landscape" class="peer sr-only" checked>
                            <div
                                class="px-4 py-2 rounded-lg border border-gray-300 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition hover:bg-gray-100 peer-checked:hover:bg-blue-700">
                                Landscape (Horizontal)
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="orientation" value="portrait" class="peer sr-only">
                            <div
                                class="px-4 py-2 rounded-lg border border-gray-300 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition hover:bg-gray-100 peer-checked:hover:bg-blue-700">
                                Portrait (Vertikal)
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Pilihan Desain -->
            <div>
                <h3 class="text-white text-lg font-bold mb-4">Pilih Desain</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($presets as $preset)
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="preset_id" value="{{ $preset['id'] }}" class="peer sr-only">

                            <div
                                class="glass-card rounded-2xl overflow-hidden transition-all duration-300 ring-4 ring-transparent peer-checked:ring-blue-500 peer-checked:scale-[1.02] hover:ring-2 hover:ring-blue-300/50">
                                <!-- Preview Box -->
                                <div class="h-48 {{ $preset['color'] }} flex items-center justify-center relative">
                                    <div class="bg-white/90 backdrop-blur-sm px-6 py-3 rounded-lg shadow-lg">
                                        <span class="text-gray-900 font-serif text-xl font-bold">Sertifikat</span>
                                    </div>
                                    <!-- Orientation Badge -->
                                    <div class="absolute bottom-2 right-2">
                                        <span
                                            class="text-[10px] bg-black/20 text-white px-2 py-1 rounded backdrop-blur-sm orientation-badge">Landscape</span>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $preset['name'] }}</h3>
                                    <p class="text-sm text-gray-500">{{ $preset['description'] }}</p>
                                </div>

                                <!-- Checkmark Overlay -->
                                <div
                                    class="absolute top-4 right-4 w-8 h-8 bg-blue-500 rounded-full items-center justify-center hidden peer-checked:flex shadow-lg animate-bounce">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4 pt-4">
                <button type="submit"
                    class="px-8 py-4 bg-[#3B82F6] rounded-xl text-white text-lg font-bold hover:bg-[#2563EB] transition flex items-center gap-2 transform active:scale-95 duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Buat Template Sekarang
                </button>
                <a href="{{ route('lembaga.template.index') }}"
                    class="px-8 py-4 bg-white/10 border border-white/20 rounded-xl text-white text-sm font-bold hover:bg-white/20 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Update orientation badges based on selection
        const orientationInputs = document.querySelectorAll('input[name="orientation"]');
        const badges = document.querySelectorAll('.orientation-badge');

        orientationInputs.forEach(input => {
            input.addEventListener('change', function () {
                const text = this.value === 'landscape' ? 'Landscape' : 'Portrait';
                badges.forEach(badge => badge.textContent = text);
            });
        });

        // Submit confirmation with validation
        document.getElementById('createTemplateForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;

            // Validate required fields
            const nameInput = form.querySelector('input[name="name"]');
            const presetInput = form.querySelector('input[name="preset_id"]:checked');

            // Check template name
            if (!nameInput || !nameInput.value.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nama Template Kosong',
                    text: 'Silakan masukkan nama untuk template baru Anda.',
                    confirmButtonColor: '#3B82F6'
                });
                nameInput.focus();
                return;
            }

            // Check preset selection
            if (!presetInput) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Desain Belum Dipilih',
                    text: 'Silakan pilih salah satu desain sertifikat terlebih dahulu.',
                    confirmButtonColor: '#3B82F6'
                });
                return;
            }

            // Show processing loader
            Swal.fire({
                title: 'Sedang Membuat Template...',
                text: 'Mohon tunggu sebentar, sistem sedang men-generate sertifikat Anda.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            form.submit();
        });
    </script>
</x-layouts.lembaga>