# 📋 Daftar 10 Fitur Utama SertiKu & Struktur MVC

Platform sertifikat digital dengan teknologi QR Code dan blockchain.

---

## 📊 Ringkasan 10 Fitur Utama

| No | Fitur | Deskripsi |
|----|-------|-----------|
| 1 | **Autentikasi & Keamanan** | Login (Email/Google/Wallet), Register, OTP, Lupa Password, CAPTCHA |
| 2 | **Manajemen Sertifikat** | CRUD sertifikat digital dengan QR Code |
| 3 | **Template Sertifikat** | Upload & kelola template sertifikat |
| 4 | **Verifikasi Sertifikat Publik** | Verifikasi keaslian sertifikat via hash code/QR tanpa login |
| 5 | **Blockchain Integration** | Hash sertifikat disimpan di Polygon untuk keamanan |
| 6 | **Payment Gateway** | Pembayaran paket premium via Midtrans |
| 7 | **Dashboard Multi-Role** | User, Lembaga, Admin, Master |
| 8 | **Chatbot & Support Ticket** | AI Chatbot (n8n) dan sistem tiket dengan admin |
| 9 | **Activity Logging** | Tracking dan pencatatan aktivitas sistem |
| 10 | **Pengaturan & Profil** | Manajemen profil, onboarding, ganti password, hapus akun |

---

## 🗂️ Detail Fitur & File MVC (Untuk Screenshot)

---

### 1️⃣ FITUR: Autentikasi & Keamanan

> Mencakup: Login, Register, OTP, Lupa Password, Google OAuth, Wallet Login, reCAPTCHA, Turnstile

| Layer | File | Path |
|-------|------|------|
| **Model** | [User.php](file:///c:/laragon/www/sertiku/app/Models/User.php) | [app/Models/User.php](file:///c:/laragon/www/sertiku/app/Models/User.php) |
| **Model** | [EmailVerification.php](file:///c:/laragon/www/sertiku/app/Models/EmailVerification.php) | [app/Models/EmailVerification.php](file:///c:/laragon/www/sertiku/app/Models/EmailVerification.php) |
| **Controller** | [LoginController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/Auth/LoginController.php) | [app/Http/Controllers/Auth/LoginController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/Auth/LoginController.php) |
| **Controller** | [RegisterController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/Auth/RegisterController.php) | [app/Http/Controllers/Auth/RegisterController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/Auth/RegisterController.php) |
| **Controller** | [GoogleController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/Auth/GoogleController.php) | [app/Http/Controllers/Auth/GoogleController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/Auth/GoogleController.php) |
| **Controller** | [EmailVerificationController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/Auth/EmailVerificationController.php) | [app/Http/Controllers/Auth/EmailVerificationController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/Auth/EmailVerificationController.php) |
| **Controller** | [PasswordResetController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/Auth/PasswordResetController.php) | [app/Http/Controllers/Auth/PasswordResetController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/Auth/PasswordResetController.php) |
| **View** | `login.blade.php` | [resources/views/auth/login.blade.php](file:///c:/laragon/www/sertiku/resources/views/auth/login.blade.php) |
| **View** | `register.blade.php` | [resources/views/auth/register.blade.php](file:///c:/laragon/www/sertiku/resources/views/auth/register.blade.php) |
| **View** | `verify-otp.blade.php` | [resources/views/auth/verify-otp.blade.php](file:///c:/laragon/www/sertiku/resources/views/auth/verify-otp.blade.php) |
| **View** | `forgot-password.blade.php` | [resources/views/auth/forgot-password.blade.php](file:///c:/laragon/www/sertiku/resources/views/auth/forgot-password.blade.php) |
| **Route** | [web.php](file:///c:/laragon/www/sertiku/routes/web.php) | [routes/web.php](file:///c:/laragon/www/sertiku/routes/web.php) (Line 30-158) |
| **Migration** | [create_users_table.php](file:///c:/laragon/www/sertiku/database/migrations/0001_01_01_000000_create_users_table.php) | [database/migrations/0001_01_01_000000_create_users_table.php](file:///c:/laragon/www/sertiku/database/migrations/0001_01_01_000000_create_users_table.php) |
| **Migration** | [create_email_verifications_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_16_055558_create_email_verifications_table.php) | [database/migrations/2025_12_16_055558_create_email_verifications_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_16_055558_create_email_verifications_table.php) |
| **Config** | `recaptcha.php` | [config/recaptcha.php](file:///c:/laragon/www/sertiku/config/recaptcha.php) |

**📸 Screenshot Prioritas:**
1. [app/Models/User.php](file:///c:/laragon/www/sertiku/app/Models/User.php)
2. [app/Http/Controllers/Auth/LoginController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/Auth/LoginController.php)
3. [app/Http/Controllers/Auth/RegisterController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/Auth/RegisterController.php)
4. [resources/views/auth/login.blade.php](file:///c:/laragon/www/sertiku/resources/views/auth/login.blade.php)
5. [resources/views/auth/register.blade.php](file:///c:/laragon/www/sertiku/resources/views/auth/register.blade.php)

---

### 2️⃣ FITUR: Manajemen Sertifikat

> Mencakup: CRUD sertifikat, upload file, generate QR Code

| Layer | File | Path |
|-------|------|------|
| **Model** | [Certificate.php](file:///c:/laragon/www/sertiku/app/Models/Certificate.php) | [app/Models/Certificate.php](file:///c:/laragon/www/sertiku/app/Models/Certificate.php) |
| **Controller** | [LembagaController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/LembagaController.php) | [app/Http/Controllers/LembagaController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/LembagaController.php) |
| **View** | [index.blade.php](file:///c:/laragon/www/sertiku/resources/views/verifikasi/index.blade.php) | [resources/views/lembaga/sertifikat/index.blade.php](file:///c:/laragon/www/sertiku/resources/views/lembaga/sertifikat/index.blade.php) |
| **View** | [create.blade.php](file:///c:/laragon/www/sertiku/resources/views/lembaga/sertifikat/create.blade.php) | [resources/views/lembaga/sertifikat/create.blade.php](file:///c:/laragon/www/sertiku/resources/views/lembaga/sertifikat/create.blade.php) |
| **Route** | [web.php](file:///c:/laragon/www/sertiku/routes/web.php) | [routes/web.php](file:///c:/laragon/www/sertiku/routes/web.php) (Line 211-229) |
| **Migration** | [create_certificates_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_13_000002_create_certificates_table.php) | [database/migrations/2025_12_13_000002_create_certificates_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_13_000002_create_certificates_table.php) |

**📸 Screenshot Prioritas:**
1. [app/Models/Certificate.php](file:///c:/laragon/www/sertiku/app/Models/Certificate.php)
2. [app/Http/Controllers/LembagaController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/LembagaController.php)
3. [resources/views/lembaga/sertifikat/create.blade.php](file:///c:/laragon/www/sertiku/resources/views/lembaga/sertifikat/create.blade.php)
4. [database/migrations/2025_12_13_000002_create_certificates_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_13_000002_create_certificates_table.php)

---

### 3️⃣ FITUR: Template Sertifikat

> Mencakup: Upload template, kelola template, toggle aktif/nonaktif

| Layer | File | Path |
|-------|------|------|
| **Model** | [Template.php](file:///c:/laragon/www/sertiku/app/Models/Template.php) | [app/Models/Template.php](file:///c:/laragon/www/sertiku/app/Models/Template.php) |
| **Controller** | [LembagaController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/LembagaController.php) | [app/Http/Controllers/LembagaController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/LembagaController.php) |
| **View** | [index.blade.php](file:///c:/laragon/www/sertiku/resources/views/verifikasi/index.blade.php) | [resources/views/lembaga/template/index.blade.php](file:///c:/laragon/www/sertiku/resources/views/lembaga/template/index.blade.php) |
| **View** | [upload.blade.php](file:///c:/laragon/www/sertiku/resources/views/lembaga/template/upload.blade.php) | [resources/views/lembaga/template/upload.blade.php](file:///c:/laragon/www/sertiku/resources/views/lembaga/template/upload.blade.php) |
| **Route** | [web.php](file:///c:/laragon/www/sertiku/routes/web.php) | [routes/web.php](file:///c:/laragon/www/sertiku/routes/web.php) (Line 223-228) |
| **Migration** | [create_templates_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_13_000001_create_templates_table.php) | [database/migrations/2025_12_13_000001_create_templates_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_13_000001_create_templates_table.php) |

**📸 Screenshot Prioritas:**
1. [app/Models/Template.php](file:///c:/laragon/www/sertiku/app/Models/Template.php)
2. [resources/views/lembaga/template/index.blade.php](file:///c:/laragon/www/sertiku/resources/views/lembaga/template/index.blade.php)
3. [database/migrations/2025_12_13_000001_create_templates_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_13_000001_create_templates_table.php)

---

### 4️⃣ FITUR: Verifikasi Sertifikat Publik

> **Fitur ini dapat diakses oleh siapa saja tanpa login.**
> Mencakup: Input hash code manual, scan QR Code, tampil hasil verifikasi

| Layer | File | Path |
|-------|------|------|
| **Model** | [Certificate.php](file:///c:/laragon/www/sertiku/app/Models/Certificate.php) *(sama dengan Fitur 2)* | [app/Models/Certificate.php](file:///c:/laragon/www/sertiku/app/Models/Certificate.php) |
| **Controller** | [VerifyController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/VerifyController.php) | [app/Http/Controllers/VerifyController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/VerifyController.php) |
| **View** | [index.blade.php](file:///c:/laragon/www/sertiku/resources/views/verifikasi/index.blade.php) | [resources/views/verifikasi/index.blade.php](file:///c:/laragon/www/sertiku/resources/views/verifikasi/index.blade.php) |
| **View** | [valid.blade.php](file:///c:/laragon/www/sertiku/resources/views/verifikasi/valid.blade.php) | [resources/views/verifikasi/valid.blade.php](file:///c:/laragon/www/sertiku/resources/views/verifikasi/valid.blade.php) |
| **View** | [invalid.blade.php](file:///c:/laragon/www/sertiku/resources/views/verifikasi/invalid.blade.php) | [resources/views/verifikasi/invalid.blade.php](file:///c:/laragon/www/sertiku/resources/views/verifikasi/invalid.blade.php) |
| **Route** | [web.php](file:///c:/laragon/www/sertiku/routes/web.php) | [routes/web.php](file:///c:/laragon/www/sertiku/routes/web.php) (Line 269-280) |

> ⚠️ **Catatan:** Model [Certificate.php](file:///c:/laragon/www/sertiku/app/Models/Certificate.php) digunakan bersama dengan Fitur 2 (Manajemen Sertifikat)

**Alur Verifikasi:**
```
User → Input Hash/Scan QR → Cek Database → Tampilkan Hasil (Valid/Invalid)
```

**📸 Screenshot Prioritas:**
1. [app/Http/Controllers/VerifyController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/VerifyController.php)
2. [resources/views/verifikasi/index.blade.php](file:///c:/laragon/www/sertiku/resources/views/verifikasi/index.blade.php)
3. [resources/views/verifikasi/valid.blade.php](file:///c:/laragon/www/sertiku/resources/views/verifikasi/valid.blade.php)

---

### 5️⃣ FITUR: Blockchain Integration (Polygon)

> Mencakup: Simpan hash ke blockchain Polygon, verifikasi on-chain, tracking transaksi

| Layer | File | Path |
|-------|------|------|
| **Model** | [Certificate.php](file:///c:/laragon/www/sertiku/app/Models/Certificate.php) *(field blockchain di model ini)* | [app/Models/Certificate.php](file:///c:/laragon/www/sertiku/app/Models/Certificate.php) |
| **Controller** | [BlockchainController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/BlockchainController.php) | [app/Http/Controllers/BlockchainController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/BlockchainController.php) |
| **View** | `verify.blade.php` | [resources/views/blockchain/verify.blade.php](file:///c:/laragon/www/sertiku/resources/views/blockchain/verify.blade.php) |
| **Route** | [web.php](file:///c:/laragon/www/sertiku/routes/web.php) | [routes/web.php](file:///c:/laragon/www/sertiku/routes/web.php) (Line 282-289) |
| **Migration** | [add_blockchain_fields_to_certificates_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_15_114017_add_blockchain_fields_to_certificates_table.php) | [database/migrations/2025_12_15_114017_add_blockchain_fields_to_certificates_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_15_114017_add_blockchain_fields_to_certificates_table.php) |
| **Config** | [.env](file:///c:/laragon/www/sertiku/.env) | [.env](file:///c:/laragon/www/sertiku/.env) (POLYGON_* variables) |

> ⚠️ **Catatan:** Data blockchain disimpan di tabel `certificates` (field: `tx_hash`, `block_number`, dll). Migration menambahkan field blockchain ke tabel certificates.

**📸 Screenshot Prioritas:**
1. [app/Http/Controllers/BlockchainController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/BlockchainController.php)
2. [resources/views/blockchain/verify.blade.php](file:///c:/laragon/www/sertiku/resources/views/blockchain/verify.blade.php)
3. [database/migrations/2025_12_15_114017_add_blockchain_fields_to_certificates_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_15_114017_add_blockchain_fields_to_certificates_table.php)

---

### 6️⃣ FITUR: Payment Gateway (Midtrans)

> Mencakup: Checkout, pembayaran, callback, paket langganan

| Layer | File | Path |
|-------|------|------|
| **Model** | [Order.php](file:///c:/laragon/www/sertiku/app/Models/Order.php) | [app/Models/Order.php](file:///c:/laragon/www/sertiku/app/Models/Order.php) |
| **Model** | [Package.php](file:///c:/laragon/www/sertiku/app/Models/Package.php) | [app/Models/Package.php](file:///c:/laragon/www/sertiku/app/Models/Package.php) |
| **Controller** | [PaymentController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/PaymentController.php) | [app/Http/Controllers/PaymentController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/PaymentController.php) |
| **View** | `checkout.blade.php` | [resources/views/payment/checkout.blade.php](file:///c:/laragon/www/sertiku/resources/views/payment/checkout.blade.php) |
| **View** | `success.blade.php` | [resources/views/payment/success.blade.php](file:///c:/laragon/www/sertiku/resources/views/payment/success.blade.php) |
| **Route** | [web.php](file:///c:/laragon/www/sertiku/routes/web.php) | [routes/web.php](file:///c:/laragon/www/sertiku/routes/web.php) (Line 291-304) |
| **Migration** | [create_packages_table.php](file:///c:/laragon/www/sertiku/database/migrations/2024_12_09_000001_create_packages_table.php) | [database/migrations/2024_12_09_000001_create_packages_table.php](file:///c:/laragon/www/sertiku/database/migrations/2024_12_09_000001_create_packages_table.php) |
| **Migration** | [create_orders_table.php](file:///c:/laragon/www/sertiku/database/migrations/2024_12_09_000002_create_orders_table.php) | [database/migrations/2024_12_09_000002_create_orders_table.php](file:///c:/laragon/www/sertiku/database/migrations/2024_12_09_000002_create_orders_table.php) |

**📸 Screenshot Prioritas:**
1. [app/Models/Order.php](file:///c:/laragon/www/sertiku/app/Models/Order.php)
2. [app/Models/Package.php](file:///c:/laragon/www/sertiku/app/Models/Package.php)
3. [app/Http/Controllers/PaymentController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/PaymentController.php)
4. [resources/views/payment/checkout.blade.php](file:///c:/laragon/www/sertiku/resources/views/payment/checkout.blade.php)

---

### 7️⃣ FITUR: Dashboard Multi-Role

> Mencakup: Dashboard User, Lembaga, Admin, Master dengan fitur masing-masing

| Layer | File | Path |
|-------|------|------|
| **Model** | [User.php](file:///c:/laragon/www/sertiku/app/Models/User.php) *(field role/account_type)* | [app/Models/User.php](file:///c:/laragon/www/sertiku/app/Models/User.php) |
| **Controller** | [UserController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/UserController.php) | [app/Http/Controllers/UserController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/UserController.php) |
| **Controller** | [LembagaController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/LembagaController.php) | [app/Http/Controllers/LembagaController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/LembagaController.php) |
| **Controller** | [AdminController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/AdminController.php) | [app/Http/Controllers/AdminController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/AdminController.php) |
| **Controller** | [MasterController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/MasterController.php) | [app/Http/Controllers/MasterController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/MasterController.php) |
| **View** | [dashboard.blade.php](file:///c:/laragon/www/sertiku/resources/views/dashboard.blade.php) | [resources/views/dashboard.blade.php](file:///c:/laragon/www/sertiku/resources/views/dashboard.blade.php) |
| **View** | [dashboard.blade.php](file:///c:/laragon/www/sertiku/resources/views/dashboard.blade.php) | [resources/views/lembaga/dashboard.blade.php](file:///c:/laragon/www/sertiku/resources/views/lembaga/dashboard.blade.php) |
| **Middleware** | [EnsureUserIsPengguna.php](file:///c:/laragon/www/sertiku/app/Http/Middleware/EnsureUserIsPengguna.php) | [app/Http/Middleware/EnsureUserIsPengguna.php](file:///c:/laragon/www/sertiku/app/Http/Middleware/EnsureUserIsPengguna.php) |
| **Middleware** | [EnsureUserIsLembaga.php](file:///c:/laragon/www/sertiku/app/Http/Middleware/EnsureUserIsLembaga.php) | [app/Http/Middleware/EnsureUserIsLembaga.php](file:///c:/laragon/www/sertiku/app/Http/Middleware/EnsureUserIsLembaga.php) |
| **Middleware** | [EnsureUserIsAdmin.php](file:///c:/laragon/www/sertiku/app/Http/Middleware/EnsureUserIsAdmin.php) | [app/Http/Middleware/EnsureUserIsAdmin.php](file:///c:/laragon/www/sertiku/app/Http/Middleware/EnsureUserIsAdmin.php) |
| **Middleware** | [EnsureUserIsMaster.php](file:///c:/laragon/www/sertiku/app/Http/Middleware/EnsureUserIsMaster.php) | [app/Http/Middleware/EnsureUserIsMaster.php](file:///c:/laragon/www/sertiku/app/Http/Middleware/EnsureUserIsMaster.php) |
| **Route** | [web.php](file:///c:/laragon/www/sertiku/routes/web.php) | [routes/web.php](file:///c:/laragon/www/sertiku/routes/web.php) (Line 166-251, 350-405) |

> ⚠️ **Catatan:** Model [User.php](file:///c:/laragon/www/sertiku/app/Models/User.php) memiliki field `account_type`, `is_admin`, `is_master` untuk menentukan role

**📸 Screenshot Prioritas:**
1. [app/Http/Controllers/UserController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/UserController.php)
2. [app/Http/Controllers/AdminController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/AdminController.php)
3. [app/Http/Middleware/EnsureUserIsLembaga.php](file:///c:/laragon/www/sertiku/app/Http/Middleware/EnsureUserIsLembaga.php)
4. [resources/views/dashboard.blade.php](file:///c:/laragon/www/sertiku/resources/views/dashboard.blade.php)

---

### 8️⃣ FITUR: Chatbot & Support Ticket

> Mencakup: AI Chatbot (integrasi n8n), buat tiket, chat dengan admin, tracking status

| Layer | File | Path |
|-------|------|------|
| **Model** | [SupportTicket.php](file:///c:/laragon/www/sertiku/app/Models/SupportTicket.php) | [app/Models/SupportTicket.php](file:///c:/laragon/www/sertiku/app/Models/SupportTicket.php) |
| **Model** | [SupportMessage.php](file:///c:/laragon/www/sertiku/app/Models/SupportMessage.php) | [app/Models/SupportMessage.php](file:///c:/laragon/www/sertiku/app/Models/SupportMessage.php) |
| **Controller** | [ChatController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/ChatController.php) | [app/Http/Controllers/ChatController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/ChatController.php) |
| **Controller** | [SupportController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/SupportController.php) | [app/Http/Controllers/SupportController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/SupportController.php) |
| **View** | [index.blade.php](file:///c:/laragon/www/sertiku/resources/views/verifikasi/index.blade.php) | [resources/views/contact-admin/index.blade.php](file:///c:/laragon/www/sertiku/resources/views/contact-admin/index.blade.php) |
| **View** | `show.blade.php` | [resources/views/contact-admin/show.blade.php](file:///c:/laragon/www/sertiku/resources/views/contact-admin/show.blade.php) |
| **Route** | [web.php](file:///c:/laragon/www/sertiku/routes/web.php) | [routes/web.php](file:///c:/laragon/www/sertiku/routes/web.php) (Line 254-265, 407-426) |
| **Migration** | [create_support_tickets_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_15_163741_create_support_tickets_table.php) | [database/migrations/2025_12_15_163741_create_support_tickets_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_15_163741_create_support_tickets_table.php) |
| **Migration** | [create_support_messages_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_15_163854_create_support_messages_table.php) | [database/migrations/2025_12_15_163854_create_support_messages_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_15_163854_create_support_messages_table.php) |
| **Config** | `services.php` | [config/services.php](file:///c:/laragon/www/sertiku/config/services.php) (n8n webhook) |

**Komponen Fitur:**
- **AI Chatbot**: Integrasi dengan n8n webhook untuk respons otomatis
- **Chat Admin**: Sistem tiket untuk komunikasi langsung dengan admin

**📸 Screenshot Prioritas:**
1. [app/Models/SupportTicket.php](file:///c:/laragon/www/sertiku/app/Models/SupportTicket.php)
2. [app/Http/Controllers/ChatController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/ChatController.php)
3. [app/Http/Controllers/SupportController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/SupportController.php)
4. [resources/views/contact-admin/index.blade.php](file:///c:/laragon/www/sertiku/resources/views/contact-admin/index.blade.php)

---

### 9️⃣ FITUR: Activity Logging

> Mencakup: Tracking aktivitas user, pencatatan log sistem, audit trail

| Layer | File | Path |
|-------|------|------|
| **Model** | [ActivityLog.php](file:///c:/laragon/www/sertiku/app/Models/ActivityLog.php) | [app/Models/ActivityLog.php](file:///c:/laragon/www/sertiku/app/Models/ActivityLog.php) |
| **Controller** | [MasterController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/MasterController.php) *(method logs)* | [app/Http/Controllers/MasterController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/MasterController.php) |
| **View** | `logs.blade.php` | [resources/views/master/logs.blade.php](file:///c:/laragon/www/sertiku/resources/views/master/logs.blade.php) |
| **Route** | [web.php](file:///c:/laragon/www/sertiku/routes/web.php) | [routes/web.php](file:///c:/laragon/www/sertiku/routes/web.php) (Line 85, 399) |
| **Migration** | [create_activity_logs_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_15_171548_create_activity_logs_table.php) | [database/migrations/2025_12_15_171548_create_activity_logs_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_15_171548_create_activity_logs_table.php) |

> ⚠️ **Catatan:** Tidak ada controller terpisah, method `logs()` ada di [MasterController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/MasterController.php)

**📸 Screenshot Prioritas:**
1. [app/Models/ActivityLog.php](file:///c:/laragon/www/sertiku/app/Models/ActivityLog.php)
2. [app/Http/Controllers/MasterController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/MasterController.php) (bagian method logs)
3. [database/migrations/2025_12_15_171548_create_activity_logs_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_15_171548_create_activity_logs_table.php)

---

### 🔟 FITUR: Pengaturan & Profil

> Mencakup: Manajemen profil, onboarding user baru, upload avatar, ganti password, hapus akun

| Layer | File | Path |
|-------|------|------|
| **Model** | [User.php](file:///c:/laragon/www/sertiku/app/Models/User.php) *(profile fields)* | [app/Models/User.php](file:///c:/laragon/www/sertiku/app/Models/User.php) |
| **Controller** | [SettingsController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/SettingsController.php) | [app/Http/Controllers/SettingsController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/SettingsController.php) |
| **Controller** | [OnboardingController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/OnboardingController.php) | [app/Http/Controllers/OnboardingController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/OnboardingController.php) |
| **View** | [index.blade.php](file:///c:/laragon/www/sertiku/resources/views/verifikasi/index.blade.php) | [resources/views/settings/index.blade.php](file:///c:/laragon/www/sertiku/resources/views/settings/index.blade.php) |
| **View** | [index.blade.php](file:///c:/laragon/www/sertiku/resources/views/verifikasi/index.blade.php) | [resources/views/onboarding/index.blade.php](file:///c:/laragon/www/sertiku/resources/views/onboarding/index.blade.php) |
| **Route** | [web.php](file:///c:/laragon/www/sertiku/routes/web.php) | [routes/web.php](file:///c:/laragon/www/sertiku/routes/web.php) (Line 167-170, 203-209) |

> ⚠️ **Catatan:** Data profil disimpan di tabel `users`, tidak ada tabel terpisah

**Fungsi Utama:**
- **Settings**: Update profil, upload avatar, ganti password, hapus akun
- **Onboarding**: Lengkapi profil setelah login Google/Wallet

**📸 Screenshot Prioritas:**
1. [app/Http/Controllers/SettingsController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/SettingsController.php)
2. [app/Http/Controllers/OnboardingController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/OnboardingController.php)
3. [resources/views/settings/index.blade.php](file:///c:/laragon/www/sertiku/resources/views/settings/index.blade.php)
4. [resources/views/onboarding/index.blade.php](file:///c:/laragon/www/sertiku/resources/views/onboarding/index.blade.php)

---

## 📁 Struktur Folder Utama

```
sertiku/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                    # Autentikasi (Fitur 1)
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   ├── GoogleController.php
│   │   │   │   ├── EmailVerificationController.php
│   │   │   │   └── PasswordResetController.php
│   │   │   ├── AdminController.php      # Dashboard Admin (Fitur 7)
│   │   │   ├── BlockchainController.php # Blockchain (Fitur 5)
│   │   │   ├── ChatController.php       # Chatbot AI (Fitur 8)
│   │   │   ├── LembagaController.php    # Sertifikat & Template (Fitur 2, 3)
│   │   │   ├── MasterController.php     # Dashboard Master + Logs (Fitur 7, 9)
│   │   │   ├── OnboardingController.php # Onboarding (Fitur 10)
│   │   │   ├── PaymentController.php    # Payment (Fitur 6)
│   │   │   ├── SettingsController.php   # Pengaturan (Fitur 10)
│   │   │   ├── SupportController.php    # Chat Admin (Fitur 8)
│   │   │   ├── UserController.php       # Dashboard User (Fitur 7)
│   │   │   └── VerifyController.php     # Verifikasi Publik (Fitur 4)
│   │   └── Middleware/                  # Role-based access (Fitur 7)
│   │       ├── EnsureUserIsPengguna.php
│   │       ├── EnsureUserIsLembaga.php
│   │       ├── EnsureUserIsAdmin.php
│   │       └── EnsureUserIsMaster.php
│   └── Models/
│       ├── User.php                     # Autentikasi, Dashboard, Profil (Fitur 1, 7, 10)
│       ├── EmailVerification.php        # OTP (Fitur 1)
│       ├── Certificate.php              # Sertifikat, Verifikasi, Blockchain (Fitur 2, 4, 5)
│       ├── Template.php                 # Template (Fitur 3)
│       ├── Order.php                    # Payment (Fitur 6)
│       ├── Package.php                  # Payment (Fitur 6)
│       ├── SupportTicket.php            # Support (Fitur 8)
│       ├── SupportMessage.php           # Support (Fitur 8)
│       └── ActivityLog.php              # Activity Logging (Fitur 9)
├── database/migrations/                 # Database schema
├── resources/views/
│   ├── auth/                            # Autentikasi (Fitur 1)
│   ├── blockchain/                      # Blockchain (Fitur 5)
│   ├── contact-admin/                   # Chatbot & Support (Fitur 8)
│   ├── lembaga/
│   │   ├── sertifikat/                  # Manajemen Sertifikat (Fitur 2)
│   │   ├── template/                    # Template (Fitur 3)
│   │   └── dashboard.blade.php          # Dashboard Lembaga (Fitur 7)
│   ├── master/                          # Dashboard Master + Logs (Fitur 7, 9)
│   ├── onboarding/                      # Onboarding (Fitur 10)
│   ├── payment/                         # Payment (Fitur 6)
│   ├── settings/                        # Pengaturan (Fitur 10)
│   ├── verifikasi/                      # Verifikasi Publik (Fitur 4)
│   ├── user/, admin/                    # Dashboard (Fitur 7)
│   └── dashboard.blade.php              # Dashboard utama
└── routes/web.php                       # Semua routing
```

---

## 📸 Checklist Screenshot untuk Laporan

### ✅ File Wajib Screenshot per Fitur:

| Fitur | Model | Controller | View | Migration |
|-------|-------|------------|------|-----------|
| 1. Autentikasi | [User.php](file:///c:/laragon/www/sertiku/app/Models/User.php), [EmailVerification.php](file:///c:/laragon/www/sertiku/app/Models/EmailVerification.php) | [LoginController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/Auth/LoginController.php), [RegisterController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/Auth/RegisterController.php) | `login.blade.php`, `register.blade.php` | [create_users_table.php](file:///c:/laragon/www/sertiku/database/migrations/0001_01_01_000000_create_users_table.php) |
| 2. Manajemen Sertifikat | [Certificate.php](file:///c:/laragon/www/sertiku/app/Models/Certificate.php) | [LembagaController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/LembagaController.php) | [sertifikat/create.blade.php](file:///c:/laragon/www/sertiku/resources/views/lembaga/sertifikat/create.blade.php) | [create_certificates_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_13_000002_create_certificates_table.php) |
| 3. Template | [Template.php](file:///c:/laragon/www/sertiku/app/Models/Template.php) | [LembagaController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/LembagaController.php) | [template/index.blade.php](file:///c:/laragon/www/sertiku/resources/views/lembaga/template/index.blade.php) | [create_templates_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_13_000001_create_templates_table.php) |
| 4. Verifikasi Publik | *(Certificate.php)* | [VerifyController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/VerifyController.php) | [verifikasi/index.blade.php](file:///c:/laragon/www/sertiku/resources/views/verifikasi/index.blade.php) | - |
| 5. Blockchain | *(Certificate.php)* | [BlockchainController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/BlockchainController.php) | `blockchain/verify.blade.php` | `add_blockchain_fields_*.php` |
| 6. Payment | [Order.php](file:///c:/laragon/www/sertiku/app/Models/Order.php), [Package.php](file:///c:/laragon/www/sertiku/app/Models/Package.php) | [PaymentController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/PaymentController.php) | `payment/checkout.blade.php` | [create_orders_table.php](file:///c:/laragon/www/sertiku/database/migrations/2024_12_09_000002_create_orders_table.php) |
| 7. Dashboard | *(User.php)* | [UserController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/UserController.php), [AdminController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/AdminController.php) | [dashboard.blade.php](file:///c:/laragon/www/sertiku/resources/views/dashboard.blade.php) | - |
| 8. Chatbot & Support | [SupportTicket.php](file:///c:/laragon/www/sertiku/app/Models/SupportTicket.php), [SupportMessage.php](file:///c:/laragon/www/sertiku/app/Models/SupportMessage.php) | [ChatController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/ChatController.php), [SupportController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/SupportController.php) | `contact-admin/index.blade.php` | [create_support_tickets_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_15_163741_create_support_tickets_table.php) |
| 9. Activity Logging | [ActivityLog.php](file:///c:/laragon/www/sertiku/app/Models/ActivityLog.php) | [MasterController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/MasterController.php) | `master/logs.blade.php` | [create_activity_logs_table.php](file:///c:/laragon/www/sertiku/database/migrations/2025_12_15_171548_create_activity_logs_table.php) |
| 10. Pengaturan & Profil | *(User.php)* | [SettingsController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/SettingsController.php), [OnboardingController.php](file:///c:/laragon/www/sertiku/app/Http/Controllers/OnboardingController.php) | `settings/index.blade.php` | - |

> **Keterangan:** *(Nama Model)* = Model dipakai bersama dari fitur lain

### 📌 Tambahan:
- **Route**: Screenshot [routes/web.php](file:///c:/laragon/www/sertiku/routes/web.php) (keseluruhan atau per section)
- **Middleware**: Screenshot 1-2 file middleware di `app/Http/Middleware/`
- **Config**: Screenshot `config/recaptcha.php` (sensor data sensitif di `.env`!)

---

## ✅ Konfirmasi: Semua Fitur Sudah Lengkap!

### Daftar 9 Model yang Ada:
1. `User.php` - Digunakan Fitur 1, 7, 10
2. `EmailVerification.php` - Digunakan Fitur 1
3. `Certificate.php` - Digunakan Fitur 2, 4, 5
4. `Template.php` - Digunakan Fitur 3
5. `Order.php` - Digunakan Fitur 6
6. `Package.php` - Digunakan Fitur 6
7. `SupportTicket.php` - Digunakan Fitur 8
8. `SupportMessage.php` - Digunakan Fitur 8
9. `ActivityLog.php` - Digunakan Fitur 9

### Daftar 15 Controller yang Ada:
1. `LoginController.php` - Fitur 1
2. `RegisterController.php` - Fitur 1
3. `GoogleController.php` - Fitur 1
4. `EmailVerificationController.php` - Fitur 1
5. `PasswordResetController.php` - Fitur 1
6. `LembagaController.php` - Fitur 2, 3
7. `VerifyController.php` - Fitur 4
8. `BlockchainController.php` - Fitur 5
9. `PaymentController.php` - Fitur 6
10. `UserController.php` - Fitur 7
11. `AdminController.php` - Fitur 7
12. `MasterController.php` - Fitur 7, 9
13. `ChatController.php` - Fitur 8
14. `SupportController.php` - Fitur 8
15. `SettingsController.php` - Fitur 10
16. `OnboardingController.php` - Fitur 10

**Semua file sudah terdaftar dan tidak ada yang terlewat!** ✅
