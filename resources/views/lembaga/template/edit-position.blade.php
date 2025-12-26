<x-layouts.lembaga>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-white text-xl lg:text-2xl font-bold">Edit Posisi Nama - {{ $template->name }}</h1>
            <p class="text-white/70 text-sm lg:text-base mt-1">Atur posisi nama penerima pada template sertifikat</p>
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

        <!-- Edit Form -->
        <form action="{{ route('lembaga.template.updatePosition', $template) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Position Editor Card -->
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center gap-2 pb-3 border-b border-gray-200 mb-6">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-gray-800 text-base font-bold">Posisi Nama Penerima</span>
                </div>

                <p class="text-gray-600 text-sm mb-4">
                    <strong>Klik pada gambar</strong> untuk menentukan posisi nama penerima sertifikat.
                </p>

                <!-- Preview Container -->
                <div id="preview-container"
                    class="relative bg-gray-100 rounded-xl overflow-hidden cursor-crosshair mx-auto"
                    style="max-width: 900px;">
                    <img id="template-preview" src="{{ asset('storage/' . $template->file_path) }}" alt="Preview"
                        class="w-full h-auto">

                    <!-- Name Position Marker -->
                    <div id="name-marker"
                        class="absolute bg-red-500 text-white text-xs px-3 py-1.5 rounded-full pointer-events-none shadow-lg font-bold"
                        style="left: {{ $template->name_position_x ?? 50 }}%; top: {{ $template->name_position_y ?? 45 }}%; transform: translate(-50%, -50%);">
                        Nama Penerima
                    </div>

                    <!-- QR Position Marker -->
                    <div id="qr-marker"
                        class="absolute bg-blue-500 text-white text-xs px-3 py-1.5 rounded-full pointer-events-none shadow-lg font-bold"
                        style="left: {{ $template->qr_position_x ?? 90 }}%; top: {{ $template->qr_position_y ?? 85 }}%; transform: translate(-50%, -50%);">
                        QR Code
                    </div>
                </div>

                <!-- Position Info -->
                <div class="mt-4 flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 bg-red-500 rounded"></span>
                        <span class="text-gray-600">Posisi Nama: <strong
                                id="name-pos-display">{{ $template->name_position_x ?? 50 }}%,
                                {{ $template->name_position_y ?? 45 }}%</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 bg-blue-500 rounded"></span>
                        <span class="text-gray-600">Posisi QR: <strong
                                id="qr-pos-display">{{ $template->qr_position_x ?? 90 }}%,
                                {{ $template->qr_position_y ?? 85 }}%</strong></span>
                    </div>
                </div>

                <p class="text-gray-500 text-xs mt-2">
                    Klik kiri = Set posisi nama | Klik kanan = Set posisi QR
                </p>

                <!-- Font Settings -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <label class="text-gray-700 text-sm font-bold">Ukuran Font Nama</label>
                        <input type="number" name="name_font_size" value="{{ $template->name_font_size ?? 52 }}"
                            min="20" max="100"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="space-y-2">
                        <label class="text-gray-700 text-sm font-bold">Warna Font Nama</label>
                        <input type="color" name="name_font_color" value="{{ $template->name_font_color ?? '#1a1a1a' }}"
                            class="w-full h-12 bg-gray-50 border border-gray-300 rounded-lg cursor-pointer">
                    </div>
                    <div class="space-y-2">
                        <label class="text-gray-700 text-sm font-bold">Ukuran QR Code (px)</label>
                        <input type="number" name="qr_size" value="{{ $template->qr_size ?? 80 }}" min="50" max="150"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Hidden inputs for position -->
                <input type="hidden" name="name_position_x" id="name_position_x"
                    value="{{ $template->name_position_x ?? 50 }}">
                <input type="hidden" name="name_position_y" id="name_position_y"
                    value="{{ $template->name_position_y ?? 45 }}">
                <input type="hidden" name="qr_position_x" id="qr_position_x"
                    value="{{ $template->qr_position_x ?? 90 }}">
                <input type="hidden" name="qr_position_y" id="qr_position_y"
                    value="{{ $template->qr_position_y ?? 85 }}">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4">
                <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl text-white text-lg font-bold hover:from-blue-700 hover:to-indigo-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Posisi
                </button>
                <a href="{{ route('lembaga.template.index') }}"
                    class="px-8 py-4 bg-white/10 border border-white/20 rounded-xl text-white text-sm font-bold hover:bg-white/20 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        const previewContainer = document.getElementById('preview-container');
        const nameMarker = document.getElementById('name-marker');
        const qrMarker = document.getElementById('qr-marker');
        const namePosDisplay = document.getElementById('name-pos-display');
        const qrPosDisplay = document.getElementById('qr-pos-display');

        // Handle left click for name position
        previewContainer.addEventListener('click', function (e) {
            e.preventDefault();
            const rect = previewContainer.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;

            // Update marker position
            nameMarker.style.left = x + '%';
            nameMarker.style.top = y + '%';

            // Update hidden inputs
            document.getElementById('name_position_x').value = Math.round(x);
            document.getElementById('name_position_y').value = Math.round(y);

            // Update display
            namePosDisplay.textContent = `${Math.round(x)}%, ${Math.round(y)}%`;
        });

        // Handle right click for QR position
        previewContainer.addEventListener('contextmenu', function (e) {
            e.preventDefault();
            const rect = previewContainer.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;

            // Update marker position
            qrMarker.style.left = x + '%';
            qrMarker.style.top = y + '%';

            // Update hidden inputs
            document.getElementById('qr_position_x').value = Math.round(x);
            document.getElementById('qr_position_y').value = Math.round(y);

            // Update display
            qrPosDisplay.textContent = `${Math.round(x)}%, ${Math.round(y)}%`;
        });
    </script>
</x-layouts.lembaga>