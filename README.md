# 🔐 SertiKu

Platform terdepan untuk menerbitkan, mengelola, dan memverifikasi sertifikat digital dengan teknologi QR Code dan blockchain

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat-square&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4.0+-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)

## ✨ Fitur

- 🔑 Login dengan Google dan Address Wallet
- 🎨 UI Modern dengan TailwindCSS
- 📄 Upload dan kelola sertifikat digital
- 🔍 Verifikasi sertifikat dengan QR Code
- 📊 Dashboard analytics dan laporan
- 🔗 Integrasi blockchain untuk keamanan
- 📱 Responsive design untuk mobile
- 🔔 Notifikasi real-time
- 📈 Tracking status sertifikat


## 🚀 Instalasi

```bash
# Clone repository
git clone https://github.com/username/sertiku.git
cd sertiku

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Buat database & jalankan migration
php artisan migrate
```

## ⚙️ Konfigurasi Google OAuth

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru
3. Pergi ke **APIs & Services** > **Credentials**
4. Buat **OAuth client ID** (Web application)
5. Tambahkan redirect URI: `http://127.0.0.1:8000/auth/google/callback`
6. Copy Client ID & Client Secret

Edit `.env`:

```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```

## 🎨 Menjalankan TailwindCSS

```bash
# Install Node.js dependencies
npm install
```

## ▶️ Menjalankan

```bash
# Jalankan TailwindCSS (terminal pertama)
npm run dev

# Jalankan Laravel server (terminal kedua)
php artisan serve
```

Buka: http://127.0.0.1:8000/

## 📁 Struktur Project

```
sertiku/
├── app/
│   ├── Http/
│   │   └── Controllers/ 
│   └── Models/                      
├── config/
│   └── services.php                        
├── database/
│   └── migrations/
├── resources/
│   └── views/          
├── routes/
│   └── web.php                             
├── .env                                    
└── composer.json
```

## 📝 License

MIT License

Copyright (c) 2024 MIT License

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

---

<p align="center">
  Made with ❤️ by SertiKu Team</a>
</p>
