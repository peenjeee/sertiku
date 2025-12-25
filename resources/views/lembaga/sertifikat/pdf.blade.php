<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $certificate->recipient_name }} - {{ $certificate->certificate_number }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
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

        .content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            text-align: center;
        }

        .recipient-name {
            font-size: 40px;
            font-weight: bold;
            color: #1e1e1e;
            margin-top: 280px;
            /* Adjust based on template */
            text-transform: uppercase;
        }

        .course-name {
            font-size: 24px;
            color: #4a4a4a;
            margin-top: 20px;
        }

        .date {
            font-size: 16px;
            color: #666;
            margin-top: 40px;
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
            bottom: 30px;
            left: 50px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        @if($certificate->template && $certificate->template->background_image)
            {{-- Use absolute path for dompdf --}}
            <img src="{{ public_path('storage/' . $certificate->template->background_image) }}" class="background">
        @endif

        <div class="content">
            <div class="recipient-name">
                {{ $certificate->recipient_name }}
            </div>

            <div class="course-name">
                Running in {{ $certificate->course_name }}
            </div>

            <div class="date">
                Given on {{ \Carbon\Carbon::parse($certificate->issue_date)->isoFormat('D MMMM Y') }}
            </div>
        </div>

        <div class="qr-code">
            {{-- Generate QR Code on the fly or use stored path --}}
            @if($certificate->qr_code_path)
                <img src="{{ public_path('storage/' . $certificate->qr_code_path) }}">
            @endif
        </div>

        <div class="number">
            No: {{ $certificate->certificate_number }} | Verifikasi: {{ route('verifikasi.show', $certificate->hash) }}
        </div>
    </div>
</body>

</html>