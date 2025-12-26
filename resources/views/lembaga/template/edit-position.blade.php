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

            @extends('layouts.app')

            @section('content')
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Edit Posisi Template</h1>
                            <p class="text-sm text-gray-500 mt-1">Atur posisi Nama dan QR Code dengan drag & drop</p>
                        </div>
                        <a href="{{ route('lembaga.template.index') }}"
                            class="text-gray-600 hover:text-gray-900 transition mb-4 inline-block">
                            &larr; Kembali
                        </a>
                    </div>

                    {{-- Load Google Fonts --}}
                    <link
                        href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Dancing+Script&family=Playfair+Display&family=Roboto&family=Montserrat&family=Pinyon+Script&display=swap"
                        rel="stylesheet">

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
                                    /* Font size will be set by JS relative to container width */
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
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=Preview"
                                            alt="QR" class="w-full h-full object-contain pointer-events-none">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-2 text-center">Tarik elemen (Nama / QR) untuk memindahkan
                                    posisi</p>
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

                                        {{-- Font Family --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Font</label>
                                            <select name="font_family" id="font-family"
                                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                <option value="'Great Vibes', cursive">Great Vibes (Script)</option>
                                                <option value="'Dancing Script', cursive">Dancing Script (Script)</option>
                                                <option value="'Pinyon Script', cursive">Pinyon Script (Elegant)</option>
                                                <option value="'Playfair Display', serif">Playfair Display (Serif)</option>
                                                <option value="'Roboto', sans-serif">Roboto (Sans)</option>
                                                <option value="'Montserrat', sans-serif">Montserrat (Modern)</option>
                                            </select>
                                            {{-- We assume backend might not support storing font family yet, but user asked
                                            for preview.
                                            Ideally we should store this column in DB. For now I will add it to the view
                                            interaction. --}}
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
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran QR Code
                                                (px)</label>
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
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        // Elements
                        const previewArea = document.getElementById('preview-area');
                        const dragName = document.getElementById('drag-name');
                        const dragQr = document.getElementById('drag-qr');
                        const previewNameText = document.getElementById('preview-name-text');

                        // Inputs
                        const inputSampleName = document.getElementById('sample-name');
                        const inputFontFamily = document.getElementById('font-family');
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

                            // Font Family
                            dragName.style.fontFamily = inputFontFamily.value;

                            // Font Size (CQW logic: Input px -> convert to approx cqw relative to A4 794px width)
                            // But here raw pixels might be easier, but we need it to scale.
                            // We want visual feedback. 
                            // The value entered is in "pixels" (reference to full size).
                            // So if user enters 50, they expect 50px on standard size.
                            // We can treat the input as "px relative to 800px width".
                            // So 50px / 800px * 100 = 6.25 cqw.
                            const sizeVal = inputFontSize.value;
                            const sizeCqw = (sizeVal / 7.94);
                            dragName.style.fontSize = `${sizeCqw}cqw`;

                            // Font Color
                            dragName.style.color = inputColorPicker.value;

                            // QR Size
                            const qrSizeVal = inputQrSize.value;
                            const qrSizeCqw = (qrSizeVal / 7.94);
                            dragQr.style.width = `${qrSizeCqw}cqw`;
                            dragQr.style.height = `${qrSizeCqw}cqw`; // Keep aspect ratio
                        }

                        // --- Init Listeners ---

                        // Text Changes
                        inputSampleName.addEventListener('input', updatePreview);
                        inputFontFamily.addEventListener('change', updatePreview);

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
                            e.preventDefault(); // Prevent default image dragging
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
            @endsection