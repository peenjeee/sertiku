<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $certificate->recipient_name }} - {{ $certificate->certificate_number }}</title>
    <style>
        @page {
            margin: 0;
            size: A4
                {{ $certificate->template->orientation ?? 'landscape' }}
            ;
        }

        @font-face {
            font-family: 'Great Vibes';
            src: url('{{ storage_path("app/public/fonts/GreatVibes-Regular.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', serif;
            width: 100%;
            height: 100%;
            color: #333;
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

        /* Dynamic positioned recipient name - centered using width and text-align */
        .recipient-name {
            position: absolute;
            width: 100%;
            text-align: center;
            font-family: 'Great Vibes', 'Zapfino', 'Segoe Script', cursive;
            z-index: 10;
        }

        /* Dynamic positioned QR Code */
        .qr-code {
            position: absolute;
            padding: 5px;
            background: #fff;
            border: 1px solid #e2e8f0;
            z-index: 10;
        }

        .qr-code img {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>
    @php
        // Get position values from template or use defaults
        $nameX = $certificate->template->name_position_x ?? 50;
        $nameY = $certificate->template->name_position_y ?? 45;
        $nameFontSize = $certificate->template->name_font_size ?? 52;
        $nameFontColor = $certificate->template->name_font_color ?? '#1a1a1a';

        $qrX = $certificate->template->qr_position_x ?? 90;
        $qrY = $certificate->template->qr_position_y ?? 85;
        $qrSize = $certificate->template->qr_size ?? 80;
    @endphp

    <div class="container">
        {{-- Background Template Image --}}
        @if($certificate->template && $certificate->template->file_path)
            <img src="{{ storage_path('app/public/' . $certificate->template->file_path) }}" class="background">
        @endif

        {{-- Recipient Name - Full width centered at Y position --}}
        {{-- Use margin-top to simulate vertical centering based on font size --}}
        <div class="recipient-name" style="
            left: 0;
            top: {{ $nameY }}%;
            font-size: {{ $nameFontSize }}px;
            color: {{ $nameFontColor }};
            margin-top: -{{ $nameFontSize / 2 }}px; 
            line-height: 1;
        ">{{ $certificate->recipient_name }}</div>

        {{-- QR Code - Positioned at X,Y --}}
        {{-- Use negative margins to center using fixed pixel size --}}
        @if($certificate->qr_code_path)
            <div class="qr-code" style="
                                left: {{ $qrX }}%;
                                top: {{ $qrY }}%;
                                width: {{ $qrSize }}px;
                                height: {{ $qrSize }}px;
                                margin-left: -{{ $qrSize / 2 }}px;
                                margin-top: -{{ $qrSize / 2 }}px;
                            ">
                <img src="{{ storage_path('app/public/' . $certificate->qr_code_path) }}">
            </div>
        @endif
    </div>
</body>

</html>