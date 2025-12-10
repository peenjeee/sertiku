<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password - SertiKu</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f4f4f7; margin: 0; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #1E3A8F 0%, #3B82F6 100%); padding: 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .content p { color: #4B5563; line-height: 1.6; margin-bottom: 20px; }
        .button { display: inline-block; background: linear-gradient(135deg, #1E3A8F 0%, #3B82F6 100%); color: #fff !important; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 600; }
        .button:hover { opacity: 0.9; }
        .footer { background: #F9FAFB; padding: 20px; text-align: center; border-top: 1px solid #E5E7EB; }
        .footer p { color: #9CA3AF; font-size: 12px; margin: 0; }
        .warning { background: #FEF3C7; border: 1px solid #F59E0B; border-radius: 8px; padding: 12px; margin: 20px 0; }
        .warning p { color: #92400E; font-size: 13px; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Reset Password</h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $user->name }}</strong>,</p>
            
            <p>Kami menerima permintaan untuk mereset password akun SertiKu Anda. Klik tombol di bawah untuk membuat password baru:</p>
            
            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ $resetUrl }}" class="button">Reset Password</a>
            </p>
            
            <div class="warning">
                <p>⚠️ Link ini hanya berlaku selama <strong>1 jam</strong>. Jika Anda tidak meminta reset password, abaikan email ini.</p>
            </div>
            
            <p>Jika tombol di atas tidak berfungsi, salin dan tempel link berikut ke browser Anda:</p>
            <p style="word-break: break-all; font-size: 12px; color: #6B7280; background: #F3F4F6; padding: 10px; border-radius: 6px;">
                {{ $resetUrl }}
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} SertiKu. All rights reserved.</p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas.</p>
        </div>
    </div>
</body>
</html>
