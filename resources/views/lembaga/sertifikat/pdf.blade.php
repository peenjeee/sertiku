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

        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', serif;
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
            z-index: -1;
        }

        /* Header info at top - horizontal layout */
        .header-info {
            position: absolute;
            top: 20px;
            left: 0;
            width: 100%;
            text-align: center;
            z-index: 10;
        }

        .header-text {
            display: inline-block;
            background-color: rgba(0, 0, 0, 0.6);
            color: #ffffff;
            font-size: 14px;
            font-weight: bold;
            padding: 8px 20px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }

        .qr-code {
            position: absolute;
            bottom: 50px;
            right: 50px;
            width: 100px;
            height: 100px;
        }

        .qr-code img {
            width: 100%;
            height: 100%;
        }

        .number {
            position: absolute;
            bottom: 20px;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 11px;
            color: #ffffff;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 8px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        @if($certificate->template && $certificate->template->file_path)
            {{-- Use absolute path for dompdf --}}
            <img src="{{ storage_path('app/public/' . $certificate->template->file_path) }}" class="background">
        @endif

        {{-- Header info at top: Nama • Kursus • Tanggal --}}
        <div class="header-info">
            <span class="header-text">
                {{ $certificate->recipient_name }} &nbsp;•&nbsp; {{ $certificate->course_name }} &nbsp;•&nbsp;
                {{ \Carbon\Carbon::parse($certificate->issue_date)->isoFormat('D MMMM Y') }}
            </span>
        </div>

        <div class="qr-code">
            {{-- Generate QR Code on the fly or use stored path --}}
            @if($certificate->qr_code_path)
                <img src="{{ storage_path('app/public/' . $certificate->qr_code_path) }}">
            @endif
        </div>

        <div class="number">
            No: {{ $certificate->certificate_number }} | Verifikasi: {{ route('verifikasi.show', $certificate->hash) }}
        </div>
    </div>
</body>

</html>