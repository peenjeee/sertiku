<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $certificate->recipient_name }} - {{ $certificate->certificate_number }}</title>
    @php
        // Get position values from template or use defaults
        $nameX = $certificate->template->name_position_x ?? 50;
        $nameY = $certificate->template->name_position_y ?? 45;
        $nameFontSize = $certificate->template->name_font_size ?? 52;
        $nameFontColor = $certificate->template->name_font_color ?? '#1a1a1a';
        $fontFamily = $certificate->template->name_font_family ?? 'Great Vibes';
        $fontUrlValue = str_replace(' ', '+', $fontFamily);

        $qrX = $certificate->template->qr_position_x ?? 90;
        $qrY = $certificate->template->qr_position_y ?? 85;
        $qrSize = $certificate->template->qr_size ?? 80;
    @endphp

    {{-- Load Google Font with @import (V1 API for better TTF support) --}}
    <style>
        @import url('https://fonts.googleapis.com/css?family={{ $fontUrlValue }}');

        @page {
            margin: 0;
            size: A4
                {{ $certificate->template->orientation ?? 'landscape' }}
            ;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: '{{ $fontFamily }}', serif;
            width: 100%;
            height: 100%;
        }

        .container {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        /* Name Container - Anchored at specific point */
        .recipient-name-anchor {
            position: absolute;
            width: 0;
            height: 0;
            overflow: visible;
            z-index: 10;
        }

        .recipient-name {
            display: block;
            width: 1000px;
            margin-left: -500px;
            /* Center text on anchor */
            text-align: center;
            font-family: '{{ $fontFamily }}', cursive;
            font-size:
                {{ $nameFontSize }}
                px;
            color:
                {{ $nameFontColor }}
            ;
            line-height: 1;
            white-space: nowrap;
            /* Vertical align tweak */
            /* Vertical align tweak for DOMPDF */
            transform: translateY(-60%);
        }

        /* QR Anchor */
        .qr-code-anchor {
            position: absolute;
            width: 0;
            height: 0;
            overflow: visible;
            z-index: 10;
        }

        .qr-code-box {
            background: #fff;
            padding: 5px;
            border: 1px solid #e2e8f0;
            box-sizing: border-box;
            /* Center box on anchor */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-code-box img {
            width: 100%;
            height: 100%;
            display: block;
        }
    </style>
</head>
@php
    // Calculate precise coordinates using Editor's scaling logic
    // Editor uses cqw (container query width) with formula: value / 7.94
    // We must scale to match this behavior in PDF

    $orientation = $certificate->template->orientation ?? 'landscape';
    // A4 PT Dimensions
    $pageWidth = ($orientation === 'portrait') ? 595.28 : 841.89;
    $pageHeight = ($orientation === 'portrait') ? 841.89 : 595.28;

    // Editor uses: value / 7.94 to get cqw percentage
    // So if qrSize = 80, editor shows: 80 / 7.94 = 10.08% of container width
    // In PDF: 10.08% of page width = 0.1008 * 841.89 = 84.86pt
    // Simplified formula: (qrSize / 100) * pageWidth (treating qrSize as percentage * 7.94)

    // Convert editor px values to percentage of page
    $scaledNameFontSize = ($nameFontSize / 7.94) * ($pageWidth / 100);
    $scaledQrSize = ($qrSize / 7.94) * ($pageWidth / 100);
    $scaledQrHalfSize = $scaledQrSize / 2;

    $nameLeft = ($nameX / 100) * 100; 
@endphp

<body>

    <div class="container">
        {{-- Background (Base64 Encoded for Reliability) --}}
        @if($certificate->template && $certificate->template->file_path)
            @php
                // Templates are stored in 'templates/' directory in 'local' disk (storage/app/templates)
                // The file_path in DB is like 'templates/filename.jpg'
                // So full path is storage_path('app/' . $certificate->template->file_path)
                $bgPath = storage_path('app/' . $certificate->template->file_path);

                // Fallback for legacy public paths if needed (though we moved them)
                if (!file_exists($bgPath) && str_starts_with($certificate->template->file_path, 'public/')) {
                    $bgPath = storage_path('app/' . $certificate->template->file_path);
                }

                $bgType = pathinfo($bgPath, PATHINFO_EXTENSION);
                $bgData = file_exists($bgPath) ? base64_encode(file_get_contents($bgPath)) : '';
            @endphp
            @if($bgData)
                <img src="data:image/{{ $bgType }};base64,{{ $bgData }}" class="background">
            @endif
        @endif

        {{-- Name (offset +1% down for DOMPDF adjustment) --}}
        @if($certificate->template->is_name_visible ?? true)
            <div class="recipient-name-anchor" style="left: {{ $nameX }}%; top: {{ $nameY + 1 }}%;">
                <div class="recipient-name" style="font-size: {{ $scaledNameFontSize }}pt;">
                    {{ $certificate->recipient_name }}
                </div>
            </div>
        @endif

        @if($certificate->qr_code_path && ($certificate->template->is_qr_visible ?? true))
            {{-- QR Code (offset -1% left for DOMPDF adjustment) --}}
            <div class="qr-code-anchor" style="left: {{ $qrX - 1 }}%; top: {{ $qrY }}%;">
                <div class="qr-code-box" style="
                                            width: {{ $scaledQrSize }}pt; 
                                            height: {{ $scaledQrSize }}pt; 
                                            margin-left: -{{ $scaledQrHalfSize }}pt; 
                                            margin-top: -{{ $scaledQrHalfSize }}pt;">
                    <img src="{{ storage_path('app/public/' . $certificate->qr_code_path) }}"
                        style="width: 100%; height: 100%;">
                </div>
            </div>
        @endif
    </div>
</body>

</html>