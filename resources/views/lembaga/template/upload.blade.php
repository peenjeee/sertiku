<x-layouts.lembaga title="Upload Template Sertifikat">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-white text-xl lg:text-2xl font-bold">Upload Template Sertifikat</h1>
                <p class="text-white/70 text-sm lg:text-base mt-1">Upload dan atur posisi elemen sertifikat Anda.</p>
            </div>
            <a href="{{ route('lembaga.template.index') }}" class="text-white text-sm hover:underline">
                &larr; Kembali
            </a>
        </div>

        {{-- Font loaded dynamically via JS --}}

        <style>
            .preview-container {
                container-type: inline-size;
                user-select: none;
                /* Prevent text selection while dragging */
            }

            .draggable-element {
                position: absolute;
                transform: translate(-50%, -50%);
                /* Always center anchor */
                cursor: move;
                border: 1px dashed transparent;
                transition: border-color 0.2s;
            }

            .draggable-element:hover,
            .draggable-element.active {
                border-color: #3b82f6;
                z-index: 50;
            }
        </style>



        <!-- Upload Form -->
        <form action="{{ route('lembaga.template.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6" id="uploadForm">
            @csrf

            <!-- Template Info Card -->
            <div class="glass-card rounded-2xl p-6 space-y-6">
                <!-- Same Info Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-gray-700 text-sm font-bold">Nama Sertifikat<span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            placeholder="Contoh: Template Sertifikat Workshop"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="space-y-2">
                        <label class="text-gray-700 text-sm font-bold">Orientasi<span
                                class="text-red-500">*</span></label>
                        <select name="orientation" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="landscape" {{ old('orientation') == 'landscape' ? 'selected' : '' }}>Landscape
                            </option>
                            <option value="portrait" {{ old('orientation') == 'portrait' ? 'selected' : '' }}>Portrait
                            </option>
                        </select>
                    </div>
                </div>
                <!-- Description -->
                <div class="space-y-2">
                    <label class="text-gray-700 text-sm font-bold">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi singkat..."
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Upload Area -->
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center gap-2 pb-3 border-b border-gray-200 mb-6">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span class="text-gray-800 text-base font-bold">File Sertifikat</span>
                </div>

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
                    <p class="text-gray-500 text-sm mb-4">Klik atau drag & drop file (JPG/PNG)</p>

                    <input type="file" name="template_file" id="template-file" accept=".png,.jpg,.jpeg" class="hidden"
                        onchange="handleFileSelect(this)">
                    <p id="file-name" class="text-blue-600 text-sm font-medium mb-4 hidden"></p>
                </div>
            </div>

            <!-- Editor UI (Hidden until file selected) -->
            <div id="position-editor" class="glass-card rounded-2xl p-6 hidden">
                <div class="flex items-center justify-between pb-3 border-b border-gray-200 mb-6">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.67"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="text-gray-800 text-base font-bold">Edit Tampilan & Posisi</span>
                    </div>
                    <span id="coordinates-display" class="text-xs text-gray-500">Posisi: -</span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Preview Area -->
                    <div class="lg:col-span-2">
                        <div class="bg-gray-100 p-4 rounded-xl border border-gray-200">
                            <div class="relative w-full rounded-lg overflow-hidden border border-gray-300 bg-white preview-container"
                                id="preview-area">
                                <img id="template-preview" src="" alt="Preview"
                                    class="w-full h-auto block pointer-events-none select-none">

                                <!-- Draggable Name -->
                                <div id="drag-name" class="draggable-element whitespace-nowrap text-center"
                                    style="left: 50%; top: 45%; color: #1a1a1a;">
                                    <span id="preview-name-text">Nama Penerima</span>
                                </div>

                                <!-- Draggable QR -->
                                <div id="drag-qr" class="draggable-element" style="left: 90%; top: 85%;">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=Preview"
                                        alt="QR" class="w-full h-full object-contain pointer-events-none">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 text-center">Drag elemen untuk memindahkan posisi</p>
                        </div>
                    </div>

                    <!-- Controls -->
                    <div class="space-y-6">
                        <!-- Hidden Inputs -->
                        <input type="hidden" name="name_position_x" id="input_name_x" value="50">
                        <input type="hidden" name="name_position_y" id="input_name_y" value="45">
                        <input type="hidden" name="qr_position_x" id="input_qr_x" value="90">
                        <input type="hidden" name="qr_position_y" id="input_qr_y" value="85">
                        <input type="hidden" name="name_font_family" id="input_font_family" value="Great Vibes">

                        <!-- Name Settings -->
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <div class="w-1 h-4 bg-red-500 rounded-full"></div> Pengaturan Nama
                            </h3>
                            <div class="space-y-4">
                                <!-- Font Family -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Font</label>
                                    <div class="relative">
                                        <input type="text" id="input-google-font" placeholder="Cari font..."
                                            class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                                            value="Great Vibes" autocomplete="off">
                                        <ul id="font-suggestions-list"
                                            class="absolute z-50 w-full bg-white border border-gray-300 rounded-b-lg shadow-lg max-h-60 overflow-y-auto hidden">
                                        </ul>
                                        <p id="font-status" class="text-xs text-gray-500 mt-1">Google Fonts Autocomplete
                                        </p>
                                    </div>
                                </div>

                                <!-- Font Size -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran Font</label>
                                    <div class="flex items-center gap-2">
                                        <input type="range" id="range-font-size" min="20" max="150" value="52"
                                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                        <input type="number" name="name_font_size" id="input_name_font_size" value="52"
                                            class="w-16 rounded-lg border-gray-300 text-sm text-center">
                                    </div>
                                </div>

                                <!-- Font Color -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Warna Font</label>
                                    <div class="flex items-center gap-2">
                                        <input type="color" name="name_font_color" id="input_color_picker"
                                            value="#1a1a1a"
                                            class="h-10 w-10 p-1 rounded border border-gray-300 cursor-pointer">
                                        <input type="text" id="input_color_hex" value="#1A1A1A"
                                            class="flex-1 rounded-lg border-gray-300 text-sm uppercase"
                                            placeholder="#000000">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        <!-- QR Settings -->
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <div class="w-1 h-4 bg-blue-500 rounded-full"></div> Pengaturan QR Code
                            </h3>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran QR (px)</label>
                                <div class="flex items-center gap-2">
                                    <input type="range" id="range-qr-size" min="40" max="200" value="80"
                                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                    <input type="number" name="qr_size" id="input_qr_size" value="80"
                                        class="w-16 rounded-lg border-gray-300 text-sm text-center">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl text-white text-lg font-bold hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/30">
                Upload & Simpan Template
            </button>
        </form>
    </div>

    <!-- JS Logic -->
    <script>
        // File Handling
        function handleFileSelect(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Show file name
                const nameDisplay = document.getElementById('file-name');
                const dropZone = document.getElementById('drop-zone');
                nameDisplay.textContent = `File terpilih: ${file.name}`;
                nameDisplay.classList.remove('hidden');
                dropZone.classList.add('border-blue-500', 'bg-blue-50');

                // Read and show preview
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.getElementById('template-preview');
                    img.src = e.target.result;

                    // Show Editor
                    document.getElementById('position-editor').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Curated Google Fonts List (Same as Edit Position)
            const googleFontsList = [
                "Roboto", "Open Sans", "Lato", "Montserrat", "Oswald", "Source Sans Pro",
                "Slabo 27px", "Raleway", "PT Sans", "Merriweather", "Roboto Condensed",
                "Aleo", "Cinzel", "Playfair Display", "Nunito", "Libre Baskerville",
                "Muli", "PT Serif", "Lora", "Ubuntu", "Titillium Web", "Visby Round",
                "Mukta", "Oxygen", "Indie Flower", "Anton", "Cabin", "Fjalla One",
                "Inconsolata", "Bitter", "Hind", "Dosis", "Josefin Sans", "Arvo",
                "Dancing Script", "Pacifico", "Shadows Into Light", "Lobster",
                "Great Vibes", "Sacramento", "Cookie", "Parisienne", "Allura",
                "Pinyon Script", "Tangerine", "Satisfy", "Courgette", "Playball",
                "Yellowtail", "Kaushan Script", "Marck Script", "Mr Dafoe",
                "Norican", "Rochester", "Arizonia", "Bilbo", "Qwigley",
                "Alex Brush", "Black Jack"
            ];

            // Elements
            const previewArea = document.getElementById('preview-area');
            const dragName = document.getElementById('drag-name');
            const dragQr = document.getElementById('drag-qr');
            const previewNameText = document.getElementById('preview-name-text');
            const suggestionsList = document.getElementById('font-suggestions-list');

            // Inputs
            const inputGoogleFont = document.getElementById('input-google-font');
            const fontStatus = document.getElementById('font-status');
            const inputFontFamily = document.getElementById('input_font_family');
            const rangeFontSize = document.getElementById('range-font-size');
            const inputFontSize = document.getElementById('input_name_font_size');
            const inputColorPicker = document.getElementById('input_color_picker');
            const inputColorHex = document.getElementById('input_color_hex');
            const rangeQrSize = document.getElementById('range-qr-size');
            const inputQrSize = document.getElementById('input_qr_size');
            const coordDisplay = document.getElementById('coordinates-display');

            // Hidden Coordinates
            const hiddenNameX = document.getElementById('input_name_x');
            const hiddenNameY = document.getElementById('input_name_y');
            const hiddenQrX = document.getElementById('input_qr_x');
            const hiddenQrY = document.getElementById('input_qr_y');

            let activeDrag = null;
            let isDragging = false;

            // --- Font Logic ---
            function loadGoogleFont(fontName) {
                if (!fontName) return;

                // Avoid empty requests
                if (fontName.length < 2) return;

                const fontUrlName = fontName.replace(/ /g, '+');
                const linkHref = `https://fonts.googleapis.com/css2?family=${fontUrlName}&display=swap`;

                const applyFont = () => {
                    const cssValue = `'${fontName}', cursive, sans-serif`;
                    dragName.style.fontFamily = cssValue;
                    if (previewNameText) previewNameText.style.fontFamily = cssValue;

                    inputFontFamily.value = fontName;
                    fontStatus.textContent = `Font '${fontName}' diterapkan.`;
                    fontStatus.className = "text-xs text-green-500 mt-1";
                };

                // Check if already loaded
                if (!document.querySelector(`link[href^="${linkHref}"]`)) {
                    const link = document.createElement('link');
                    link.href = linkHref;
                    link.rel = 'stylesheet';
                    link.onload = applyFont;
                    link.onerror = () => {
                        fontStatus.textContent = `Gagal memuat '${fontName}'.`;
                        fontStatus.className = "text-xs text-red-500 mt-1";
                    };
                    document.head.appendChild(link);
                } else {
                    applyFont();
                }
            }

            // Suggestions
            function showSuggestions(query) {
                const matches = googleFontsList.filter(f => f.toLowerCase().includes(query.toLowerCase()));
                suggestionsList.innerHTML = '';
                if (matches.length > 0) {
                    suggestionsList.classList.remove('hidden');
                    matches.forEach(font => {
                        const li = document.createElement('li');
                        li.textContent = font;
                        li.className = "px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm font-medium text-gray-700";
                        li.onclick = () => {
                            inputGoogleFont.value = font;
                            suggestionsList.classList.add('hidden');
                            loadGoogleFont(font);
                        };
                        suggestionsList.appendChild(li);
                    });
                } else {
                    suggestionsList.classList.add('hidden');
                }
            }

            // Input Listener with Debounce
            function debounce(func, wait) {
                let timeout;
                return function (...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }

            inputGoogleFont.addEventListener('input', debounce((e) => {
                const val = e.target.value.trim();
                if (val.length >= 1) showSuggestions(val);
                else suggestionsList.classList.add('hidden');

                const exactMatch = googleFontsList.find(f => f.toLowerCase() === val.toLowerCase());
                if (exactMatch) loadGoogleFont(exactMatch);
                else if (val.length >= 4) loadGoogleFont(val);
            }, 300));

            // Hide suggestions click outside
            document.addEventListener('click', (e) => {
                if (!inputGoogleFont.contains(e.target) && !suggestionsList.contains(e.target)) {
                    suggestionsList.classList.add('hidden');
                }
            });

            // --- Sync & Update ---
            function syncInputs(source, target) {
                source.addEventListener('input', (e) => {
                    target.value = e.target.value;
                    updatePreview();
                });
            }

            function updatePreview() {
                // Font Size (CQW logic)
                const sizeVal = inputFontSize.value;
                dragName.style.fontSize = `${sizeVal / 7.94}cqw`;

                // Color
                dragName.style.color = inputColorPicker.value;

                // QR Size
                const qrVal = inputQrSize.value;
                dragQr.style.width = `${qrVal / 7.94}cqw`;
                dragQr.style.height = `${qrVal / 7.94}cqw`;
            }

            // Init Listeners
            syncInputs(rangeFontSize, inputFontSize);
            syncInputs(inputFontSize, rangeFontSize);
            syncInputs(rangeQrSize, inputQrSize);
            syncInputs(inputQrSize, rangeQrSize);

            inputColorPicker.addEventListener('input', (e) => {
                inputColorHex.value = e.target.value.toUpperCase();
                updatePreview();
            });
            inputColorHex.addEventListener('input', (e) => {
                let val = e.target.value;
                if (!val.startsWith('#')) val = '#' + val;
                if (/^#[0-9A-F]{6}$/i.test(val)) {
                    inputColorPicker.value = val;
                    updatePreview();
                }
            });

            // --- Drag Logic ---
            function handleDragStart(e, element, type) {
                e.preventDefault(); // Prevent image drag default
                activeDrag = { element, type };
                isDragging = true;
                element.classList.add('active');
            }

            function handleDragEnd() {
                if (activeDrag) {
                    activeDrag.element.classList.remove('active');
                    activeDrag = null;
                    isDragging = false;
                }
            }

            function handleDragMove(e) {
                if (!isDragging || !activeDrag) return;

                const rect = previewArea.getBoundingClientRect();
                let clientX = e.clientX;
                let clientY = e.clientY;

                if (e.touches && e.touches.length > 0) {
                    clientX = e.touches[0].clientX;
                    clientY = e.touches[0].clientY;
                }

                let x = clientX - rect.left;
                let y = clientY - rect.top;

                // Clamp
                x = Math.max(0, Math.min(x, rect.width));
                y = Math.max(0, Math.min(y, rect.height));

                // Percent
                const xPct = Math.round((x / rect.width) * 100);
                const yPct = Math.round((y / rect.height) * 100);

                activeDrag.element.style.left = `${xPct}%`;
                activeDrag.element.style.top = `${yPct}%`;

                if (activeDrag.type === 'name') {
                    hiddenNameX.value = xPct;
                    hiddenNameY.value = yPct;
                } else {
                    hiddenQrX.value = xPct;
                    hiddenQrY.value = yPct;
                }

                coordDisplay.textContent = `Posisi: ${xPct}%, ${yPct}%`;
            }

            // Attach Drag Events
            dragName.addEventListener('mousedown', (e) => handleDragStart(e, dragName, 'name'));
            dragQr.addEventListener('mousedown', (e) => handleDragStart(e, dragQr, 'qr'));

            window.addEventListener('mouseup', handleDragEnd);
            window.addEventListener('mousemove', handleDragMove);

            // Initial load
            loadGoogleFont("Great Vibes");
            updatePreview();
        });
    </script>
</x-layouts.lembaga>