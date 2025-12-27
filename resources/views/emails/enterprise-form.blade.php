<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Enterprise Baru - SertiKu</title>
</head>

<body
    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f5f5f5;">
    <div
        style="background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%); padding: 30px; border-radius: 12px 12px 0 0; text-align: center;">
        <h1 style="color: #ffffff; margin: 0; font-size: 24px;">🏢 Lead Enterprise Baru!</h1>
        <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0; font-size: 14px;">Permintaan Paket Enterprise dari
            {{ $data['institution'] }}
        </p>
    </div>

    <div
        style="background-color: #ffffff; padding: 30px; border-radius: 0 0 12px 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div
            style="background-color: #faf5ff; border-left: 4px solid #8B5CF6; padding: 16px; border-radius: 0 8px 8px 0; margin-bottom: 24px;">
            <p style="margin: 0; color: #6b21a8; font-weight: 600; font-size: 14px;">🔥 Prospek Potensial!</p>
            <p style="margin: 5px 0 0 0; color: #7c3aed; font-size: 13px;">Lead ini memerlukan follow-up dalam 1x24 jam.
            </p>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td
                    style="padding: 12px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-size: 13px; width: 130px;">
                    Nama Lengkap</td>
                <td style="padding: 12px 0; border-bottom: 1px solid #e5e7eb; font-weight: 600;">{{ $data['name'] }}
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-size: 13px;">Email
                </td>
                <td style="padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                    <a href="mailto:{{ $data['email'] }}"
                        style="color: #8B5CF6; text-decoration: none;">{{ $data['email'] }}</a>
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-size: 13px;">No.
                    Telepon</td>
                <td style="padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                    <a href="tel:{{ $data['phone'] }}"
                        style="color: #8B5CF6; text-decoration: none;">{{ $data['phone'] }}</a>
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-size: 13px;">
                    Institusi</td>
                <td style="padding: 12px 0; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #8B5CF6;">
                    {{ $data['institution'] }}
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 0; color: #6b7280; font-size: 13px; vertical-align: top;">Kebutuhan</td>
                <td style="padding: 12px 0;">
                    <div
                        style="background-color: #f8fafc; padding: 16px; border-radius: 8px; border-left: 4px solid #8B5CF6;">
                        {!! nl2br(e($data['message'])) !!}
                    </div>
                </td>
            </tr>
        </table>

        <div style="margin-top: 24px; text-align: center;">
            <a href="mailto:{{ $data['email'] }}?subject=Re: Permintaan Paket Enterprise SertiKu"
                style="display: inline-block; background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%); color: #ffffff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 500; margin-right: 12px; margin-bottom: 8px;">
                ↩️ Balas Email
            </a>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $data['phone']) }}"
                style="display: inline-block; background: #25D366; color: #ffffff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 500;">
                WhatsApp
            </a>
        </div>

        <div
            style="margin-top: 24px; padding: 16px; background-color: #f0f9ff; border-radius: 8px; text-align: center;">
            <p style="margin: 0; color: #0369a1; font-size: 13px;">
                Lead ini dikirim dari halaman <strong>Paket Enterprise</strong>
            </p>
            <p style="margin: 8px 0 0 0; color: #6b7280; font-size: 12px;">
                {{ now()->format('d M Y, H:i:s') }} WIB
            </p>
        </div>
    </div>

    <div style="text-align: center; padding: 20px; color: #9ca3af; font-size: 12px;">
        <p style="margin: 0;">© {{ date('Y') }} SertiKu - Platform Sertifikat Digital Terpercaya</p>
        <p style="margin: 5px 0 0 0;">
            <a href="https://sertiku.web.id" style="color: #8B5CF6; text-decoration: none;">sertiku.web.id</a>
        </p>
    </div>
</body>

</html>