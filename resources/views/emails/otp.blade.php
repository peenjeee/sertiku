<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP - SertiKu</title>
</head>

<body
    style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #050C1F;">
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

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <p style="margin: 0 0 20px; color: #DBEAFE; font-size: 16px;">
                                Halo <strong>{{ $userName }}</strong>,
                            </p>

                            <p style="margin: 0 0 30px; color: #94A3B8; font-size: 15px; line-height: 1.6;">
                                Anda menerima email ini karena ada permintaan verifikasi email untuk akun SertiKu Anda.
                                Gunakan kode OTP berikut untuk menyelesaikan verifikasi:
                            </p>

                            <!-- OTP Code -->
                            <div
                                style="background: rgba(59, 130, 246, 0.1); border: 2px solid rgba(59, 130, 246, 0.3); border-radius: 12px; padding: 30px; text-align: center; margin: 30px 0;">
                                <p
                                    style="margin: 0 0 10px; color: #94A3B8; font-size: 14px; text-transform: uppercase; letter-spacing: 2px;">
                                    Kode Verifikasi
                                </p>
                                <p
                                    style="margin: 0; color: #FFFFFF; font-size: 42px; font-weight: 700; letter-spacing: 8px; font-family: 'Courier New', monospace;">
                                    {{ $otp }}
                                </p>
                            </div>

                            <p style="margin: 0 0 20px; color: #94A3B8; font-size: 14px; line-height: 1.6;">
                                ⏱️ Kode ini berlaku selama <strong style="color: #FBBF24;">10 menit</strong>.
                            </p>

                            <div
                                style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; padding: 15px; margin: 20px 0;">
                                <p style="margin: 0; color: #FCA5A5; font-size: 13px;">
                                    <strong>Penting:</strong> Jangan bagikan kode ini kepada siapapun.
                                    Tim SertiKu tidak akan pernah meminta kode OTP Anda.
                                </p>
                            </div>

                            <p style="margin: 30px 0 0; color: #64748B; font-size: 13px; line-height: 1.6;">
                                Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini atau
                                <a href="mailto:support@sertiku.web.id"
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