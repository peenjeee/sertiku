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
            src: url('https://fonts.gstatic.com/s/greatvibes/v14/RWm84FVPuyJEnN9h_lWzDbO5.ttf') format('truetype');
            /* Fallback to local if available, though CDN in PDF might fail if no internet/allow_url_fopen off. 
               Ideally we serve this locally, but for now we try this or fallback to generic Cursive */
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
            text-align: center;
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        /* 
           Standard Layout Strategy:
           Assuming the background has the border and title (Sertifikat/Certificate).
           We place the dynamic text in the vertical center.
        */

        .content-wrapper {
            position: absolute;
            top: 30%;
            /* Start content roughly 30% down */
            left: 10%;
            right: 10%;
            bottom: 20%;
            display: flex;
            /* dompdf support for flex is limited, use tables or distinct blocks */
        }

        .recipient-section {
            position: absolute;
            top: 40%;
            width: 100%;
            text-align: center;
        }

        .recipient-name {
            font-family: 'Great Vibes', 'Zapfino', 'Segoe Script', cursive;
            font-size: 52px;
            color: #1a1a1a;
            margin-bottom: 20px;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        }

        .course-section {
            position: absolute;
            top: 55%;
            width: 100%;
            text-align: center;
        }

        .course-text {
            font-size: 24px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .description-text {
            font-size: 16px;
            color: #4a5568;
            max-width: 80%;
            margin: 0 auto;
        }

        .date-section {
            position: absolute;
            bottom: 25%;
            width: 100%;
            text-align: center;
            font-size: 16px;
            color: #4a5568;
        }

        /* Footer Elements */
        .footer-info {
            position: absolute;
            bottom: 30px;
            left: 40px;
            text-align: left;
            font-size: 10px;
            color: #718096;
            background: rgba(255, 255, 255, 0.8);
            padding: 5px 10px;
            border-radius: 4px;
        }

        .qr-code {
            position: absolute;
            bottom: 30px;
            right: 40px;
            width: 90px;
            height: 90px;
            padding: 5px;
            background: #fff;
            border: 1px solid #e2e8f0;
        }

        .qr-code img {
            width: 100%;
            height: 100%;
        }

        /* Verification Badge */
        .verification-badge {
            position: absolute;
            top: 40px;
            right: 40px;
            background-color: #f0fff4;
            border: 1px solid #c6f6d5;
            color: #22543d;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        @if($certificate->template && $certificate->template->file_path)
            <img src="{{ storage_path('app/public/' . $certificate->template->file_path) }}" class="background">
        @endif

        {{-- Verification Badge --}}
        <div class="verification-badge">
            VERIFIED: {{ $certificate->hash }}
        </div>

        {{-- Recipient Name --}}
        <div class="recipient-section">
            <div style="font-size: 18px; color: #718096; margin-bottom: 15px;">Diberikan Kepada:</div>
            <div class="recipient-name">{{ $certificate->recipient_name }}</div>
        </div>

        {{-- Course/Event Info --}}
        <div class="course-section">
            <div style="font-size: 16px; color: #718096; margin-bottom: 10px;">Atas partisipasinya dalam:</div>
            <div class="course-text">{{ $certificate->course_name }}</div>
            @if($certificate->description)
                <div class="description-text">{{ $certificate->description }}</div>
            @endif
        </div>

        {{-- Date --}}
        <div class="date-section">
            {{ $certificate->user->institution_name ?? ($certificate->user->city ?? 'Jakarta') }},
            {{ \Carbon\Carbon::parse($certificate->issue_date)->isoFormat('D MMMM Y') }}
        </div>

        {{-- Footer Info --}}
        <div class="footer-info">
            <strong>No. Sertifikat:</strong> {{ $certificate->certificate_number }}<br>
            <strong>ID Verifikasi:</strong> {{ $certificate->id }}<br>
            <strong>Valid Hingga:</strong>
            {{ $certificate->expire_date ? $certificate->expire_date->isoFormat('D MMMM Y') : 'Seumur Hidup' }}
        </div>

        {{-- QR Code --}}
        <div class="qr-code">
            @if($certificate->qr_code_path)
                <img src="{{ storage_path('app/public/' . $certificate->qr_code_path) }}">
            @endif
        </div>
    </div>
</body>

</html>