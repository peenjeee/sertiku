# SertiKu

Platform terdepan untuk menerbitkan, mengelola, dan memverifikasi sertifikat digital dengan teknologi QR Code dan blockchain.

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4.1+-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com/)
[![Vite](https://img.shields.io/badge/Vite-7.0+-646CFF?style=flat-square&logo=vite&logoColor=white)](https://vitejs.dev/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Blade](https://img.shields.io/badge/Blade-Template-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com/docs/blade)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](https://opensource.org/licenses/MIT)

[![Cloudflare](https://img.shields.io/badge/Cloudflare-Protected-F38020?style=flat-square&logo=cloudflare&logoColor=white)](https://www.cloudflare.com/)
[![Turnstile](https://img.shields.io/badge/Turnstile-CAPTCHA-F38020?style=flat-square&logo=cloudflare&logoColor=white)](https://www.cloudflare.com/products/turnstile/)
[![reCAPTCHA](https://img.shields.io/badge/reCAPTCHA-v2-4285F4?style=flat-square&logo=google&logoColor=white)](https://www.google.com/recaptcha/)
[![Google OAuth](https://img.shields.io/badge/Google-OAuth-4285F4?style=flat-square&logo=google&logoColor=white)](https://developers.google.com/identity)

[![Ethereum](https://img.shields.io/badge/Ethereum-Web3-3C3C3D?style=flat-square&logo=ethereum&logoColor=white)](https://ethereum.org/)
[![Polygon](https://img.shields.io/badge/Polygon-Blockchain-8247E5?style=flat-square&logo=polygon&logoColor=white)](https://polygon.technology/)
[![IPFS](https://img.shields.io/badge/IPFS-Pinata-65C2CB?style=flat-square&logo=ipfs&logoColor=white)](https://pinata.cloud/)
[![Ethers.js](https://img.shields.io/badge/Ethers.js-6.0-3C3C3D?style=flat-square&logo=ethereum&logoColor=white)](https://docs.ethers.org/)
[![WalletConnect](https://img.shields.io/badge/WalletConnect-Web3Modal-3B99FC?style=flat-square&logo=walletconnect&logoColor=white)](https://walletconnect.com/)

[![Midtrans](https://img.shields.io/badge/Midtrans-Payment-00A9E0?style=flat-square&logo=money&logoColor=white)](https://midtrans.com/)
[![NOWPayments](https://img.shields.io/badge/NOWPayments-Crypto-00C26F?style=flat-square&logo=bitcoin&logoColor=white)](https://nowpayments.io/)
[![QR Code](https://img.shields.io/badge/QR_Code-Generator-000000?style=flat-square&logo=qrcode&logoColor=white)](https://github.com/SimpleSoftwareIO/simple-qrcode)
[![PWA](https://img.shields.io/badge/PWA-Ready-5A0FC8?style=flat-square&logo=pwa&logoColor=white)](https://web.dev/progressive-web-apps/)
[![n8n](https://img.shields.io/badge/n8n-Automation-EA4B71?style=flat-square&logo=n8n&logoColor=white)](https://n8n.io/)

## Fitur

### Autentikasi dan Keamanan
- Multi-method Login: Email/Password, Google OAuth, Wallet (MetaMask/WalletConnect)
- Verifikasi Email OTP - Kode 6 digit dikirim ke email untuk aktivasi akun
- Google reCAPTCHA v2 - Proteksi bot pada form login dan register
- Cloudflare Turnstile - CAPTCHA tambahan untuk keamanan berlapis
- Reset Password via Email - Link reset dengan token yang aman
- Cookie Consent Banner - Kepatuhan regulasi privasi

### Sertifikat Digital
- Upload dan kelola sertifikat digital
- Interactive Template Editor - Drag and drop positioning, Google Fonts, color picker
- Bulk Upload - Import data dari CSV/Excel untuk penerbitan massal
- Verifikasi sertifikat dengan QR Code
- Blockchain Integration - Simpan hash sertifikat ke Polygon Network
- IPFS Storage - Penyimpanan desentralisasi via Pinata Network
- File Integrity Verification - Verifikasi keaslian file dengan SHA-256 hash dan MD5
- Tracking status sertifikat

### Dashboard dan Manajemen
- Dashboard analytics dengan chart interaktif dan filter tahun
- Multi-role: User, Lembaga, Admin, Master
- Notifikasi real-time
- Responsive design untuk mobile
- Support Ticket System

### PWA dan SEO
- Progressive Web App - Install ke home screen dengan banner prompt
- Push Notifications - Notifikasi browser
- SEO Optimized - Meta tags, Open Graph, JSON-LD structured data
- Sitemap dan robots.txt - Auto-generated untuk search engines
- Google Search Console dan Bing Webmaster Tools verified

## Instalasi

```bash
# Clone repository
git clone https://github.com/peenjeee/sertiku.git
cd sertiku

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Buat database dan jalankan migration
php artisan migrate

# Jalankan seeder untuk membuat data awal
php artisan db:seed

# Clear cache
php artisan optimize:clear
```

### Akun Demo (dari Seeder)

Setelah menjalankan `php artisan db:seed`, akun-akun berikut akan tersedia:

| Role | Email | Password |
|------|-------|----------|
| Master Admin | master@sertiku.web.id | Master123 |
| Admin | admin@sertiku.web.id | Admin123 |
| Lembaga | lembaga@sertiku.web.id | lembaga123 |
| User | user@sertiku.web.id | user123 |

## Konfigurasi

### Google OAuth

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru
3. Pergi ke **APIs & Services** > **Credentials**
4. Buat **OAuth client ID** (Web application)
5. Tambahkan redirect URI: `http://127.0.0.1:8000/auth/google/callback`
6. Copy Client ID dan Client Secret

```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```

### Gmail SMTP (untuk OTP dan Reset Password)

1. Buka [Google App Passwords](https://myaccount.google.com/apppasswords)
2. Buat App Password baru (pilih Mail)
3. Copy password 16 karakter

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@sertiku.web.id"
MAIL_FROM_NAME="${APP_NAME}"
```

### Google reCAPTCHA v2

1. Buka [reCAPTCHA Admin](https://www.google.com/recaptcha/admin)
2. Daftarkan site baru (pilih reCAPTCHA v2 "I'm not a robot")
3. Tambahkan domain: `localhost`, `127.0.0.1`, dan domain production

```env
RECAPTCHA_ENABLED=true
RECAPTCHA_SITE_KEY=your-site-key
RECAPTCHA_SECRET_KEY=your-secret-key
```

### Cloudflare Turnstile (Opsional - CAPTCHA Tambahan)

1. Buka [Cloudflare Dashboard](https://dash.cloudflare.com) lalu Turnstile
2. Klik **Add site**
3. Konfigurasi:
   - **Site name**: `SertiKu`
   - **Domain**: `localhost`, `127.0.0.1`, `sertiku.web.id`
   - **Widget Type**: **Managed** (recommended)
4. Copy Site Key dan Secret Key

```env
TURNSTILE_ENABLED=true
TURNSTILE_SITE_KEY=your-site-key
TURNSTILE_SECRET_KEY=your-secret-key
```

> **Note:** Turnstile dapat digunakan bersamaan dengan reCAPTCHA untuk keamanan berlapis.

### Polygon Blockchain (untuk Sertifikat On-Chain)

SertiKu menggunakan Polygon (Amoy Testnet) untuk menyimpan hash sertifikat di blockchain.

1. Buat wallet di [MetaMask](https://metamask.io/)
2. Dapatkan MATIC testnet dari [Polygon Faucet](https://faucet.stakepool.dev.br/amoy/)
3. Deploy smart contract atau gunakan contract yang sudah ada

```env
BLOCKCHAIN_ENABLED=true
POLYGON_RPC_URL=https://rpc-amoy.polygon.technology/
POLYGON_CHAIN_ID=80002
POLYGON_PRIVATE_KEY=your-wallet-private-key
POLYGON_WALLET_ADDRESS=your-wallet-address
POLYGON_CONTRACT_ADDRESS=your-contract-address
POLYGON_EXPLORER_URL=https://amoy.polygonscan.com
```

### Midtrans Payment Gateway (Opsional)

Untuk fitur pembayaran paket premium:

```env
MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_3DS=true
```

### NOWPayments Crypto Gateway (Opsional)

Untuk pembayaran menggunakan cryptocurrency:

1. Daftar di [NOWPayments](https://nowpayments.io/)
2. Buat API Key di Dashboard
3. Set IPN Secret untuk callback verification

```env
NOWPAYMENTS_API_KEY=your-api-key
NOWPAYMENTS_IPN_SECRET=your-ipn-secret
NOWPAYMENTS_SANDBOX=true
```

### IPFS Storage via Pinata (Opsional)

Untuk penyimpanan desentralisasi sertifikat:

1. Daftar di [Pinata Cloud](https://pinata.cloud/)
2. Buat API Key di Dashboard
3. Copy JWT Token dan Gateway URL

```env
IPFS_ENABLED=true
PINATA_JWT=your-pinata-jwt-token
PINATA_GATEWAY_URL=your-gateway.mypinata.cloud
```

## Deployment

Deploy manual via cPanel Terminal:

```bash
cd ~/sertiku
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Menjalankan

```bash
# Terminal 1: Jalankan Vite/TailwindCSS
npm run dev

# Terminal 2: Jalankan Laravel server
php artisan serve
```

Buka: http://127.0.0.1:8000/

## Akun Demo

| Role | Email | Password |
|------|-------|----------|
| User | `user@sertiku.web.id` | `user123` |
| Lembaga | `lembaga@sertiku.web.id` | `lembaga123` |
| Admin | `admin@sertiku.web.id` | `Admin123` |
| Master | `master@sertiku.web.id` | `Master123` |

> **Note:** Akun dengan domain `@sertiku.web.id` tidak memerlukan verifikasi OTP.

## Struktur Project

```
sertiku/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/           # Login, Register, OTP, Password Reset
│   │   │   └── SitemapController.php  # Sitemap generation
│   │   └── Middleware/
│   ├── Mail/                   # Mailable classes (OTP, dll)
│   ├── Models/
│   └── Rules/                  # Custom validation (reCAPTCHA)
├── config/
│   ├── recaptcha.php           # Konfigurasi reCAPTCHA
│   └── services.php            # Google OAuth config
├── database/
│   └── migrations/
├── public/
│   ├── manifest.json           # PWA manifest
│   ├── sw.js                   # Service Worker
│   └── robots.txt              # SEO robots
├── resources/
│   └── views/
│       ├── auth/               # Login, Register, OTP, Reset Password
│       ├── emails/             # Email templates
│       ├── sitemap/            # Sitemap template
│       └── components/         # Blade components (SEO, PWA)
├── routes/
│   └── web.php
└── .env
```

## Alur Autentikasi

### Register
```
Form Register --> Kirim OTP --> Verifikasi OTP --> Onboarding (Lengkapi Profil) --> Dashboard
```

### Login (Email belum terverifikasi)
```
Login --> Kirim OTP --> Verifikasi OTP --> Onboarding (jika belum) --> Dashboard
```

### Login (Email sudah terverifikasi)
```
Login --> Onboarding (jika belum) --> Dashboard
```

### Wallet Login
```
Connect Wallet --> Input Email --> Kirim OTP --> Verifikasi --> Onboarding --> Dashboard
```

### Wallet Login (Sudah Terverifikasi)
```
Connect Wallet --> Onboarding (jika belum) --> Dashboard
```

### Reset Password
```
Forgot Password --> Kirim Link --> Klik Link --> Input Password Baru --> Login
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

<p align="center">
  SertiKu Team
</p>
