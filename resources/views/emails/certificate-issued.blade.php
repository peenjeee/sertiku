<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Diterbitkan - SertiKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body
    style="margin: 0; padding: 0; font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #050C1F;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #050C1F; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background: #0F172A; border-radius: 16px; border: 1px solid rgba(59, 130, 246, 0.2); overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td
                            style="padding: 40px 40px 30px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <h1 style="margin: 0; color: #FFFFFF; font-size: 28px; font-weight: 700;">
                                SertiKu
                            </h1>
                            <p style="margin: 10px 0 0; color: #94A3B8; font-size: 14px;">
                                Platform Verifikasi Sertifikat Digital
                            </p>
                        </td>
                    </tr>

                    <!-- Celebration Banner -->
                    <tr>
                        <td style="padding: 30px 40px 20px; text-align: center;">
                            <div style="background: rgba(16, 185, 129, 0.1); border-radius: 12px; padding: 20px;">
                                <p style="margin: 0; font-size: 48px;"></p>
                                <h2 style="margin: 10px 0 0; color: #10B981; font-size: 24px; font-weight: 700;">
                                    Selamat!
                                </h2>
                                <p style="margin: 5px 0 0; color: #DBEAFE; font-size: 16px;">
                                    Sertifikat Anda telah berhasil diterbitkan
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 20px 40px 40px;">
                            <p style="margin: 0 0 20px; color: #DBEAFE; font-size: 16px;">
                                Halo <strong>{{ $recipientName }}</strong>,
                            </p>

                            <p style="margin: 0 0 25px; color: #94A3B8; font-size: 15px; line-height: 1.6;">
                                Anda telah menerima sertifikat dari <strong
                                    style="color: #3B82F6;">{{ $issuerName }}</strong>. Berikut adalah detail
                                sertifikat Anda:
                            </p>

                            <!-- Certificate Details Card -->
                            <div
                                style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 12px; padding: 25px; margin: 20px 0;">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="padding: 8px 0;">
                                            <p
                                                style="margin: 0; color: #64748B; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                                                Nama Program
                                            </p>
                                            <p
                                                style="margin: 5px 0 0; color: #FFFFFF; font-size: 18px; font-weight: 600;">
                                                {{ $courseName }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 15px 0 8px; border-top: 1px solid rgba(255,255,255,0.1);">
                                            <p
                                                style="margin: 0; color: #64748B; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                                                Nomor Sertifikat
                                            </p>
                                            <p
                                                style="margin: 5px 0 0; color: #3B82F6; font-size: 16px; font-weight: 600; font-family: 'Courier New', monospace;">
                                                {{ $certificateNumber }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 15px 0 8px; border-top: 1px solid rgba(255,255,255,0.1);">
                                            <p
                                                style="margin: 0; color: #64748B; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                                                Penerbit
                                            </p>
                                            <p
                                                style="margin: 5px 0 0; color: #FFFFFF; font-size: 16px; font-weight: 500;">
                                                {{ $issuerName }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 15px 0 8px; border-top: 1px solid rgba(255,255,255,0.1);">
                                            <p
                                                style="margin: 0; color: #64748B; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                                                Tanggal Diterbitkan
                                            </p>
                                            <p
                                                style="margin: 5px 0 0; color: #FFFFFF; font-size: 16px; font-weight: 500;">
                                                {{ $issueDate }}
                                            </p>
                                        </td>
                                    </tr>
                                    @if($expireDate)
                                        <tr>
                                            <td style="padding: 15px 0 0; border-top: 1px solid rgba(255,255,255,0.1);">
                                                <p
                                                    style="margin: 0; color: #64748B; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                                                    Berlaku Hingga
                                                </p>
                                                <p
                                                    style="margin: 5px 0 0; color: #FBBF24; font-size: 16px; font-weight: 500;">
                                                    {{ $expireDate }}
                                                </p>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>

                            <!-- Action Buttons -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center" style="padding: 5px;">
                                        <a href="{{ $verificationUrl }}"
                                            style="display: inline-block; padding: 14px 28px; background: #3B82F6; color: #FFFFFF; text-decoration: none; font-size: 15px; font-weight: 600; border-radius: 8px;">
                                            Verifikasi Sertifikat
                                        </a>
                                    </td>
                                </tr>
                                @if($downloadUrl)
                                    <tr>
                                        <td align="center" style="padding: 10px 5px 5px;">
                                            <a href="{{ $downloadUrl }}"
                                                style="display: inline-block; padding: 12px 24px; background: transparent; border: 2px solid #3B82F6; color: #3B82F6; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 8px;">
                                                Download PDF
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </table>

                            <!-- Info Box -->
                            <div
                                style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 8px; padding: 15px; margin: 20px 0;">
                                <p style="margin: 0; color: #6EE7B7; font-size: 13px; line-height: 1.6;">
                                    <strong>Sertifikat ini dapat diverifikasi</strong> oleh siapa saja melalui
                                    link verifikasi atau dengan memindai QR code pada sertifikat.
                                </p>
                            </div>

                            <p style="margin: 30px 0 0; color: #64748B; font-size: 13px; line-height: 1.6;">
                                Jika Anda memiliki pertanyaan tentang sertifikat ini, silakan hubungi penerbit
                                sertifikat atau <a href="mailto:support@sertiku.web.id"
                                    style="color: #3B82F6; text-decoration: none;">hubungi kami</a>.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="padding: 30px 40px; background: rgba(0,0,0,0.2); border-top: 1px solid rgba(255,255,255,0.05); text-align: center;">
                            <p style="margin: 0 0 10px; color: #64748B; font-size: 12px;">
                                © {{ date('Y') }} SertiKu. All rights reserved.
                            </p>
                            <p style="margin: 0; color: #475569; font-size: 11px;">
                                Email ini dikirim secara otomatis, mohon tidak membalas.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>