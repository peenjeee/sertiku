<x-layouts.lembaga title="Edit Posisi Template">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Edit Posisi Template</h1>
                <p class="text-sm text-gray-300 mt-1">Atur posisi Nama dan QR Code dengan drag & drop</p>
            </div>
            <a href="{{ route('lembaga.template.index') }}" class="text-white mb-4 inline-block">
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Preview Area --}}
            <div class="lg:col-span-2">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                    <div class="mb-4 flex justify-between items-center text-sm text-gray-500">
                        <span>Preview Sertifikat</span>
                        <span id="coordinates-display">Posisi: -</span>
                    </div>

                    <div class="relative w-full rounded-lg overflow-hidden border border-gray-300 bg-gray-50 preview-container"
                        id="preview-area">
                        {{-- Background Image --}}
                        <img src="{{ asset('storage/' . $template->file_path) }}" alt="Preview"
                            class="w-full h-auto block pointer-events-none select-none">

                        {{-- Draggable Name --}}
                        <div id="drag-name" class="draggable-element whitespace-nowrap text-center" style="
                                left: {{ $template->name_position_x ?? 50 }}%; 
                                top: {{ $template->name_position_y ?? 45 }}%;
                                color: {{ $template->name_font_color ?? '#1a1a1a' }};
                                /* Font size set by JS */
                              ">
                            <span id="preview-name-text">Nama Penerima</span>
                        </div>

                        {{-- Draggable QR --}}
                        <div id="drag-qr" class="draggable-element" style="
                                left: {{ $template->qr_position_x ?? 90 }}%; 
                                top: {{ $template->qr_position_y ?? 85 }}%;
                                /* Size set by JS */
                             ">
                            {{-- Dummy QR Image --}}
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=Preview" alt="QR"
                                class="w-full h-full object-contain pointer-events-none">
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-center">Tarik elemen (Nama / QR) untuk memindahkan posisi
                    </p>
                </div>
            </div>

            {{-- Controls Area --}}
            <div>
                <form action="{{ route('lembaga.template.updatePosition', $template) }}" method="POST"
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Hidden Inputs for Coordinates --}}
                    <input type="hidden" name="name_position_x" id="input_name_x"
                        value="{{ $template->name_position_x ?? 50 }}">
                    <input type="hidden" name="name_position_y" id="input_name_y"
                        value="{{ $template->name_position_y ?? 45 }}">
                    <input type="hidden" name="qr_position_x" id="input_qr_x"
                        value="{{ $template->qr_position_x ?? 90 }}">
                    <input type="hidden" name="qr_position_y" id="input_qr_y"
                        value="{{ $template->qr_position_y ?? 85 }}">

                    {{-- Hidden Input for Font Family (updated by JS) --}}
                    <input type="hidden" name="name_font_family" id="input_font_family"
                        value="{{ $template->name_font_family ?? 'Great Vibes' }}">

                    {{-- Recipient Name Settings --}}
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <div class="w-1 h-4 bg-red-500 rounded-full"></div> Pengaturan Nama
                        </h3>

                        <div class="space-y-4">
                            {{-- Sample Text Input --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Contoh Nama</label>
                                <input type="text" id="sample-name" value="Nama Penerima"
                                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Font (Google
                                    Fonts)</label>
                                <div class="space-y-2 relative">
                                    <input type="text" id="input-google-font"
                                        placeholder="Ketik nama font... (cth: Pinyon Script)"
                                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                                        value="{{ $template->name_font_family ?? 'Great Vibes' }}" autocomplete="off">

                                    {{-- Suggestions Dropdown --}}
                                    <ul id="font-suggestions-list"
                                        class="absolute z-50 w-full bg-white border border-gray-300 rounded-b-lg shadow-lg max-h-60 overflow-y-auto hidden">
                                        {{-- JS Injected --}}
                                    </ul>

                                    <p id="font-status" class="text-xs text-gray-500">Ketik untuk mencari di Google
                                        Fonts (realtime)</p>
                                </div>
                            </div>

                            {{-- Font Size --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran Font</label>
                                <div class="flex items-center gap-2">
                                    <input type="range" id="range-font-size" min="20" max="150"
                                        value="{{ $template->name_font_size ?? 52 }}"
                                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                    <input type="number" name="name_font_size" id="input_name_font_size"
                                        value="{{ $template->name_font_size ?? 52 }}"
                                        class="w-16 rounded-lg border-gray-300 text-sm text-center">
                                </div>
                            </div>

                            {{-- Font Color --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Warna Font</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="name_font_color" id="input_color_picker"
                                        value="{{ $template->name_font_color ?? '#1a1a1a' }}"
                                        class="h-10 w-10 p-1 rounded border border-gray-300 cursor-pointer">
                                    <input type="text" id="input_color_hex"
                                        value="{{ $template->name_font_color ?? '#1a1a1a' }}"
                                        class="flex-1 rounded-lg border-gray-300 text-sm uppercase"
                                        placeholder="#000000">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    {{-- QR Code Settings --}}
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <div class="w-1 h-4 bg-blue-500 rounded-full"></div> Pengaturan QR Code
                        </h3>

                        <div class="space-y-4">
                            {{-- QR Size --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran QR Code (px)</label>
                                <div class="flex items-center gap-2">
                                    <input type="range" id="range-qr-size" min="40" max="200"
                                        value="{{ $template->qr_size ?? 80 }}"
                                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                    <input type="number" name="qr_size" id="input_qr_size"
                                        value="{{ $template->qr_size ?? 80 }}"
                                        class="w-16 rounded-lg border-gray-300 text-sm text-center">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white rounded-lg px-4 py-3 font-medium hover:bg-blue-700 transition shadow-sm">
                            Simpan Perubahan
                        </button>
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            Lokasi disimpan dalam persentase (%) agar responsif.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Curated Google Fonts List
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
            const inputSampleName = document.getElementById('sample-name');
            // Removed obsolete elements
            const inputGoogleFont = document.getElementById('input-google-font');
            const fontStatus = document.getElementById('font-status');
            const inputFontFamily = document.getElementById('input_font_family'); // HIDDEN

            const rangeFontSize = document.getElementById('range-font-size');
            const inputFontSize = document.getElementById('input_name_font_size');
            const inputColorPicker = document.getElementById('input_color_picker');
            const inputColorHex = document.getElementById('input_color_hex');
            const rangeQrSize = document.getElementById('range-qr-size');
            const inputQrSize = document.getElementById('input_qr_size');

            // Hidden Coordinates Inputs
            const hiddenNameX = document.getElementById('input_name_x');
            const hiddenNameY = document.getElementById('input_name_y');
            const hiddenQrX = document.getElementById('input_qr_x');
            const hiddenQrY = document.getElementById('input_qr_y');

            const coordDisplay = document.getElementById('coordinates-display');

            // State
            let activeDrag = null;
            let isDragging = false;

            // --- Font Logic ---
            function loadGoogleFont(fontName) {
                if (!fontName) return;

                // Show status
                if (fontStatus) {
                    fontStatus.textContent = `Memuat '${fontName}'...`;
                    fontStatus.className = "text-xs text-blue-500";
                }

                // Format for URL: "Open Sans" -> "Open+Sans"
                const fontUrlName = fontName.replace(/ /g, '+');
                const linkHref = `https://fonts.googleapis.com/css2?family=${fontUrlName}&display=swap`;

                const applyFont = () => {
                    // Use single quotes for CSS to be safe
                    const cssValue = `'${fontName}', cursive, sans-serif`;
                    dragName.style.fontFamily = cssValue;
                    // Apply to child span explicitly
                    if (previewNameText) previewNameText.style.fontFamily = cssValue;

                    inputFontFamily.value = fontName;
                    if (fontStatus) {
                        fontStatus.textContent = `Font '${fontName}' diterapkan.`;
                        fontStatus.className = "text-xs text-green-500";
                    }
                    console.log('Applied font:', cssValue);
                };

                const handleLoadError = () => {
                    if (fontStatus) {
                        fontStatus.textContent = `Gagal memuat '${fontName}' (Tidak ditemukan di Google Fonts).`;
                        fontStatus.className = "text-xs text-red-500";
                    }
                };

                // Check if already connected
                if (!document.querySelector(`link[href^="${linkHref}"]`)) {
                    const link = document.createElement('link');
                    link.href = linkHref;
                    link.rel = 'stylesheet';
                    link.onload = applyFont;
                    link.onerror = handleLoadError;
                    document.head.appendChild(link);
                } else {
                    applyFont();
                }
            }

            // Suggestion Logic
            function showSuggestions(query) {
                const matches = googleFontsList.filter(font => font.toLowerCase().includes(query.toLowerCase()));
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

            // Hide suggestions on click outside
            document.addEventListener('click', (e) => {
                if (!inputGoogleFont.contains(e.target) && !suggestionsList.contains(e.target)) {
                    suggestionsList.classList.add('hidden');
                }
            });

            // Debounce Function
            function debounce(func, wait) {
                let timeout;
                return function (...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }

            // Real-time Input Listener
            inputGoogleFont.addEventListener('input', debounce((e) => {
                const val = e.target.value.trim();
                
                if (val.length >= 1) {
                    showSuggestions(val);
                } else {
                    suggestionsList.classList.add('hidden');
                }

                // Check for exact match in our list to load immediately
                const exactMatch = googleFontsList.find(f => f.toLowerCase() === val.toLowerCase());
                if(exactMatch) {
                    loadGoogleFont(exactMatch);
                } else if (val.length >= 4) {
                    // Fallback: Try loading custom font if length > 4 (assuming user knows what they are doing)
                    loadGoogleFont(val);
                }
            }, 300));

            // Initialize Font from DB value
            const initialFont = inputFontFamily.value;
            if (initialFont) {
                // Ensure input box has the value if it wasn't populated
                if (!inputGoogleFont.value) inputGoogleFont.value = initialFont;
                loadGoogleFont(initialFont);
            }

            // --- Sync Inputs Function ---
            function syncInputs(source, target) {
                source.addEventListener('input', (e) => {
                    target.value = e.target.value;
                    updatePreview();
                });
            }

            // --- Update Preview ---
            function updatePreview() {
                // Text Content
                previewNameText.textContent = inputSampleName.value || 'Nama Penerima';

                // Font Size (CQW logic: Input px -> convert to approx cqw relative to A4 794px width)
                const sizeVal = inputFontSize.value;
                const sizeCqw = (sizeVal / 7.94);
                dragName.style.fontSize = `${sizeCqw}cqw`;

                // Font Color
                dragName.style.color = inputColorPicker.value;

                // QR Size
                const qrSizeVal = inputQrSize.value;
                const qrSizeCqw = (qrSizeVal / 7.94);
                dragQr.style.width = `${qrSizeCqw}cqw`;
                dragQr.style.height = `${qrSizeCqw}cqw`;
            }

            // --- Init Listeners ---

            // Text Changes
            inputSampleName.addEventListener('input', updatePreview);

            // Font Size Sync
            syncInputs(rangeFontSize, inputFontSize);
            syncInputs(inputFontSize, rangeFontSize);

            // Color Sync
            inputColorPicker.addEventListener('input', (e) => {
                inputColorHex.value = e.target.value.toUpperCase();
                updatePreview();
            });
            inputColorHex.addEventListener('input', (e) => {
                let userHex = e.target.value;
                if (!userHex.startsWith('#')) userHex = '#' + userHex;
                if (/^#[0-9A-F]{6}$/i.test(userHex)) {
                    inputColorPicker.value = userHex;
                    updatePreview();
                }
            });

            // QR Size Sync
            syncInputs(rangeQrSize, inputQrSize);
            syncInputs(inputQrSize, rangeQrSize);

            // --- Dragging Logic ---
            function handleDragStart(e, element, type) {
                e.preventDefault();
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

                // Calculate mouse position relative to container
                // We want the element's CENTER to be at mouse position
                let clientX = e.clientX;
                let clientY = e.clientY;

                // Touch support
                if (e.touches && e.touches.length > 0) {
                    clientX = e.touches[0].clientX;
                    clientY = e.touches[0].clientY;
                }

                let x = clientX - rect.left;
                let y = clientY - rect.top;

                // Clamp values
                x = Math.max(0, Math.min(x, rect.width));
                y = Math.max(0, Math.min(y, rect.height));

                // Convert to percentage
                const xPercent = Math.round((x / rect.width) * 100);
                const yPercent = Math.round((y / rect.height) * 100);

                // Update visible position
                activeDrag.element.style.left = `${xPercent}%`;
                activeDrag.element.style.top = `${yPercent}%`;

                // Update inputs
                if (activeDrag.type === 'name') {
                    hiddenNameX.value = xPercent;
                    hiddenNameY.value = yPercent;
                } else {
                    hiddenQrX.value = xPercent;
                    hiddenQrY.value = yPercent;
                }

                coordDisplay.textContent = `Posisi: ${xPercent}%, ${yPercent}%`;
            }

            // Mouse Events
            dragName.addEventListener('mousedown', (e) => handleDragStart(e, dragName, 'name'));
            dragQr.addEventListener('mousedown', (e) => handleDragStart(e, dragQr, 'qr'));

            window.addEventListener('mouseup', handleDragEnd);
            window.addEventListener('mousemove', handleDragMove);

            // Initial Run
            updatePreview();
        });
    </script>
</x-layouts.lembaga>