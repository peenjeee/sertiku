<x-layouts.lembaga>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-white text-xl lg:text-2xl font-bold">Upload Sertifikat Sertifikat</h1>
            <p class="text-white/70 text-sm lg:text-base mt-1">Upload Sertifikat sertifikat untuk digunakan dalam
                penerbitan</p>
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

        <!-- Upload Form -->
        <form action="{{ route('lembaga.template.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf

            <!-- Template Info Card -->
            <div class="glass-card rounded-2xl p-6 space-y-6">
                <div class="flex items-center gap-2 pb-3 border-b border-gray-200">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-gray-800 text-base font-bold">Informasi Template</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Sertifikat -->
                    <div class="space-y-2">
                        <label class="text-gray-700 text-sm font-bold">
                            Nama Sertifikat<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            placeholder="Contoh: Template Sertifikat Workshop"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Orientasi -->
                    <div class="space-y-2">
                        <label class="text-gray-700 text-sm font-bold">
                            Orientasi<span class="text-red-500">*</span>
                        </label>
                        <select name="orientation" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="landscape" {{ old('orientation') == 'landscape' ? 'selected' : '' }}>Landscape
                                (Horizontal)</option>
                            <option value="portrait" {{ old('orientation') == 'portrait' ? 'selected' : '' }}>Portrait
                                (Vertikal)</option>
                        </select>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="space-y-2">
                    <label class="text-gray-700 text-sm font-bold">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi singkat tentang template ini"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Upload Area Card -->
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center gap-2 pb-3 border-b border-gray-200 mb-6">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span class="text-gray-800 text-base font-bold">File Sertifikat</span>
                </div>

                <!-- File Input -->
                <div id="drop-zone"
                    class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-blue-500 transition-all duration-200 cursor-pointer bg-gray-50"
                    onclick="document.getElementById('template-file').click()">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                    </div>

                    <h3 class="text-gray-800 text-lg font-bold mb-2">Pilih File Sertifikat</h3>
                    <p class="text-gray-500 text-sm mb-4">Klik atau drag & drop file ke area ini</p>

                    <input type="file" name="template_file" id="template-file" required accept=".png,.jpg,.jpeg,.pdf"
                        class="hidden" onchange="updateFileName(this)">

                    <p id="file-name" class="text-blue-600 text-sm font-medium mb-4 hidden"></p>

                    <!-- Supported formats -->
                    <div class="flex items-center justify-center gap-2">
                        <span class="px-3 py-1 bg-gray-200 text-gray-600 text-xs rounded">PNG</span>
                        <span class="px-3 py-1 bg-gray-200 text-gray-600 text-xs rounded">JPG</span>
                        <span class="px-3 py-1 bg-gray-200 text-gray-600 text-xs rounded">PDF</span>
                    </div>

                    <p class="text-gray-400 text-xs mt-4">Maksimal ukuran file: 10MB</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4">
                <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl text-white text-lg font-bold hover:from-blue-700 hover:to-indigo-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Upload Sertifikat
                </button>
                <a href="{{ route('lembaga.template.index') }}"
                    class="px-8 py-4 bg-white/10 border border-white/20 rounded-xl text-white text-sm font-bold hover:bg-white/20 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('template-file');

        // Update file name display
        function updateFileName(input) {
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                const fileNameEl = document.getElementById('file-name');
                fileNameEl.innerHTML = '<svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' + fileName;
                fileNameEl.classList.remove('hidden');
                dropZone.classList.add('border-green-500', 'bg-green-50');
                dropZone.classList.remove('border-gray-300', 'bg-gray-50');
            }
        }

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Highlight drop zone when dragging over
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-blue-500', 'bg-blue-50', 'scale-[1.02]');
            dropZone.classList.remove('border-gray-300', 'bg-gray-50');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'scale-[1.02]');
            if (!fileInput.files || !fileInput.files[0]) {
                dropZone.classList.add('border-gray-300', 'bg-gray-50');
            }
        }

        // Handle dropped files
        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                const file = files[0];
                const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'application/pdf'];

                if (!validTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan PNG, JPG, atau PDF.');
                    return;
                }

                if (file.size > 10 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 10MB.');
                    return;
                }

                // Create a new DataTransfer to set the file
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;

                // Update display
                updateFileName(fileInput);
            }
        }
    </script>
</x-layouts.lembaga>