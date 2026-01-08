# Laporan Pengujian Black Box (Black Box Testing)

**Aplikasi:** Sertiku  
**Metode:** Black Box Testing  
**Tanggal Pengujian:** 7 Januari 2026

---

## 1. Pengujian Fitur Autentikasi & Keamanan

| No | Skenario | Hasil yang Seharusnya Didapat | Hasil Pengujian | Screenshot Hasil Pengujian |
|----|----------|-------------------------------|-----------------|---------------------------|
| 1 | Login dengan email & password valid | Masuk ke dashboard sesuai role (Lembaga/User/Admin) | **Berhasil.** Sistem memverifikasi kredensial user `user@sertiku.web.id` dan langsung mengarahkan ke dashboard pengguna setelah pengecekan profil selesai. Log aktivitas mencatat aksi login ini. | (Tampalkan SS Dashboard) |
| 2 | Login dengan Wallet (Web3) | Akun terdeteksi/dibuat otomatis via alamat wallet | **Berhasil.** Saat tombol "Login Wallet" diklik dan signature Metamask disetujui, `LoginController` memproses alamat wallet. Akun baru user web3 berhasil dibuat otomatis jika belum ada, dan sesi login aktif. | (Tampalkan SS Wallet Login) |
| 3 | Login dengan Google OAuth | Login sukses tanpa password | **Berhasil.** Callback dari Google diterima oleh sistem. Data nama dan email dari Google tersinkronisasi, dan user langsung masuk tanpa perlu input password manual. | (Tampalkan SS Google Auth) |
| 4 | Input OTP Verifikasi Email | Kode OTP valid diterima sistem | **Berhasil.** Setelah login, sistem mengecek `email_verified_at`. Karena belum verifikasi, email berisi kode OTP terkirim via `OtpMail`. Input kode yang benar berhasil memverifikasi akun. | (Tampalkan SS Input OTP) |
| 5 | Proteksi Brute Force | Akun terkunci sementara setelah 5x gagal | **Berhasil.** Middleware throttle aktif. Setelah 5 kali percobaan login dengan password salah, sistem menolak permintaan ke-6 dengan pesan "Too many login attempts. Please try again in 60 seconds." | (Tampalkan SS Rate Limit) |

---

## 2. Pengujian Fitur Manajemen Sertifikat

| No | Skenario | Hasil yang Seharusnya Didapat | Hasil Pengujian | Screenshot Hasil Pengujian |
|----|----------|-------------------------------|-----------------|---------------------------|
| 1 | Create Sertifikat (Positif) | Data tersimpan, QR & PDF ter-generate | **Berhasil.** Input data penerima dan tanggal lulus valid. `LembagaController` berhasil menyimpan data, men-generate QR Code unik, dan membuat file PDF sertifikat di storage. | (Tampalkan SS List Sertifikat) |
| 2 | Validasi Kuota Sertifikat | Menolak jika kuota habis | **Berhasil.** Saat user dengan paket Free mencoba menerbitkan sertifikat melebihi limit, validasi `canIssueCertificate` mengembalikan error dan meminta upgrade paket. | (Tampalkan SS Pesan Kuota) |
| 3 | Cabut Sertifikat (Revoke) | Status berubah menjadi Revoked | **Berhasil.** Tombol "Revoke" diklik dengan alasan "Salah ketik nama". Status sertifikat di database berubah jadi `revoked` dan tidak lagi dianggap valid saat diverifikasi publik. | (Tampalkan SS Status Revoked) |
| 4 | Upload Sertifikat Massal | Job antrian berjalan di background | **Berhasil.** File CSV diupload. `BulkCertificateController` tidak memproses langsung (to avoid timeout), melainkan mengirim Job ke antrian (Queue). Notifikasi "Proses sedang berjalan di background" muncul. | (Tampalkan SS Notifikasi Queue) |

---

## 3. Pengujian Fitur Template & AI Generator

| No | Skenario | Hasil yang Seharusnya Didapat | Hasil Pengujian | Screenshot Hasil Pengujian |
|----|----------|-------------------------------|-----------------|---------------------------|
| 1 | Upload Template Manual | File gambar tersimpan & tervalidasi | **Berhasil.** File `.jpg` ukuran 1MB diupload. Sistem sukses menyimpannya di `storage/public/templates` dan membuat thumbnail otomatis. Validasi dimensi (width/height) terbaca benar. | (Tampalkan SS Galeri) |
| 2 | Generate Template via AI | Template visual dibuat otomatis oleh prompt | **Berhasil.** Input prompt "Modern Blue Certificate". Request dikirim ke `n8n webhook` (atau fallback Pollinations). Dalam beberapa detik, gambar template unik ter-generate dan langsung tersimpan di galeri lembaga. | (Tampalkan SS Hasil AI) |
| 3 | Pengaturan Posisi Teks | Koordinat nama & QR tersimpan | **Berhasil.** Di halaman "Edit Position", user menggeser posisi nama ke X:50 Y:60. Update berhasil, dan saat preview sertifikat, nama penerima muncul tepat di koordinat baru tersebut. | (Tampalkan SS Editor Posisi) |

---

## 4. Pengujian Fitur Verifikasi Sertifikat Publik

| No | Skenario | Hasil yang Seharusnya Didapat | Hasil Pengujian | Screenshot Hasil Pengujian |
|----|----------|-------------------------------|-----------------|---------------------------|
| 1 | Cek Validitas via Hash | Data Valid muncul lengkap | **Berhasil.** Hash sertifikat valid dimasukkan ke kolom cari. Controller `VerifyController` menemukan data. Halaman menampilkan: Nama Penerima, Lembaga Penerbit, dan Status "Aktif". | (Tampalkan SS Hasil Valid) |
| 2 | Verifikasi Sertifikat Revoked | Muncul peringatan "DICABUT" | **Berhasil.** Menggunakan hash dari sertifikat yang baru dicabut pada skenario 2.3. Sistem menampilkan peringatan merah besar "SERTIFIKAT INI TELAH DICABUT" beserta alasan pencabutannya. | (Tampalkan SS Alert Revoked) |
| 3 | Lapor Pemalsuan (Fraud) | Data laporan tersimpan | **Berhasil.** Pada halaman verifikasi invalid, user mengisi form laporan fraud. Data laporan berhasil masuk ke database untuk ditinjau admin. | (Tampalkan SS Form Lapor) |

---

## 5. Pengujian Fitur Blockchain Integration

| No | Skenario | Hasil yang Seharusnya Didapat | Hasil Pengujian | Screenshot Hasil Pengujian |
|----|----------|-------------------------------|-----------------|---------------------------|
| 1 | Publikasi ke Blockchain (Polygon) | Job dispatched ke antrian | **Berhasil.** User mencentang opsi "Store on Chain". Sistem tidak memblokir UI, melainkan mengirim `ProcessBlockchainCertificate` job. Status awal "Pending", lalu berubah "Success" setelah cron job berjalan. | (Tampalkan SS Status Pending) |
| 2 | Cek Hash On-Chain | Membuka Explorer PolygonScan | **Berhasil.** Setelah status sukses, link "View on Blockchain" muncul. Klik link tersebut membuka PolygonScan yang menunjukkan transaksi valid dengan hash yang sesuai dengan sertifikat. | (Tampalkan SS PolygonScan) |
| 3 | Validasi Limit Blockchain | Menolak jika kuota gas habis | **Berhasil.** User mencoba fitur blockchain padahal kuota bulanan habis. Validasi `canUseBlockchain` mencegah proses dan menampilkan pesan error limit. | (Tampalkan SS Error Limit) |

---

## 6. Pengujian Fitur Payment Gateway (Midtrans)

| No | Skenario | Hasil yang Seharusnya Didapat | Hasil Pengujian | Screenshot Hasil Pengujian |
|----|----------|-------------------------------|-----------------|---------------------------|
| 1 | Checkout Snap Popup | Modal pembayaran muncul | **Berhasil.** Request ke Midtrans API sukses mendapatkan `snap_token`. Popup pembayaran muncul di layar tanpa reload halaman (AJAX). | (Tampalkan SS Popup Midtrans) |
| 2 | Pembayaran Otomatis | Update status via Webhook | **Berhasil.** Dilakukan simulasi bayar. Midtrans mengirim notifikasi HTTP POST ke route `/payment/callback`. Controller memvalidasi signature key dan mengupdate status order menjadi `PAID`. | (Tampalkan SS Status Paid) |
| 3 | Pembayaran Crypto (NOWPayments) | Redirect ke payment processor | **Berhasil.** Memilih metode Crypto. Sistem mengarahkan user ke halaman invoice NOWPayments. Callback sukses diproses setelah konfirmasi jaringan blockchain. | (Tampalkan SS NOWPayments) |

---

## 7. Pengujian Fitur Dashboard Multi-Role (RBAC)

| No | Skenario | Hasil yang Seharusnya Didapat | Hasil Pengujian | Screenshot Hasil Pengujian |
|----|----------|-------------------------------|-----------------|---------------------------|
| 1 | Isolasi Data Lembaga | Lembaga hanya lihat data sendiri | **Berhasil.** Login sebagai Lembaga A. Mencoba mencari sertifikat yang dibuat Lembaga B. Hasil pencarian nihil (Scope query `where('user_id', Auth::id())` berfungsi dengan benar). | (Tampalkan SS Pencarian) |
| 2 | Akses Menu Master | Hanya bisa diakses akun is_master | **Berhasil.** Mencoba akses `/master/settings` menggunakan akun Admin biasa. Middleware `master.only` memblokir akses dan melempar pesan error 403 / Unauthorized. | (Tampalkan SS 403) |
| 3 | Admin User Management | Toggle status aktif user | **Berhasil.** Login Admin. Klik tombol "Nonaktifkan User". User target langsung logout paksa dan tidak bisa login kembali sampai diaktifkan. | (Tampalkan SS User List) |

---

## 8. Pengujian Fitur Chatbot & Support Ticket

| No | Skenario | Hasil yang Seharusnya Didapat | Hasil Pengujian | Screenshot Hasil Pengujian |
|----|----------|-------------------------------|-----------------|---------------------------|
| 1 | Auto-Reply Chatbot | Bot menjawab via Knowledge Base | **Berhasil.** Input "Lupa password". Webhook n8n memproses intent dan membalas dengan panduan reset password dalam hitungan detik. | (Tampalkan SS Chat Bubble) |
| 2 | Eskalasi ke Admin (Tiket) | Tiket tersimpan di DB Support | **Berhasil.** User membuat tiket "Error Upload". Data masuk ke tabel `support_tickets`. Admin menerima notifikasi (via email/dashboard) ada tiket baru dengan status OPEN. | (Tampalkan SS Dashboard Support) |

---

## 9. Pengujian Fitur Activity Logging

| No | Skenario | Hasil yang Seharusnya Didapat | Hasil Pengujian | Screenshot Hasil Pengujian |
|----|----------|-------------------------------|-----------------|---------------------------|
| 1 | Audit Trail Insert/Update | Log mencatat detail perubahan | **Berhasil.** `ActivityLog` model mencatat setiap aksi `storeSertifikat` dan `updateProfile`. Kolom `properties` menyimpan data JSON berisi field apa saja yang berubah. | (Tampalkan SS Tabel Log) |
| 2 | Filter Log Aktivitas | Pencarian log spesifik | **Berhasil.** Filter log berdasarkan "Action: Login" dan "User: Admin". Hasil filter akurat menampilkan histori login admin saja. | (Tampalkan SS Filter Result) |

---

## 10. Pengujian Fitur Pengaturan & Profil

| No | Skenario | Hasil yang Seharusnya Didapat | Hasil Pengujian | Screenshot Hasil Pengujian |
|----|----------|-------------------------------|-----------------|---------------------------|
| 1 | Onboarding User Baru | Redirect paksa lengkapi profil | **Berhasil.** User baru register via Google. Login pertama kali langsung diredirect ke `/onboarding` untuk isi No HP & Alamat. Tidak bisa akses dashboard sebelum selesai. | (Tampalkan SS Onboarding) |
| 2 | Update Avatar | Gambar profil berubah | **Berhasil.** Upload foto `profile.jpg`. Controller menyimpan file, menghapus foto lama, dan UI sidebar langsung menampilkan wajah baru user. | (Tampalkan SS Sidebar) |
| 3 | Close Account (Hapus Akun) | Data user terhapus permanen | **Berhasil.** Request hapus akun di settings. Setelah konfirmasi password, data user dan relasi sertifikatnya dihapus dari database. Sesi logout otomatis. | (Tampalkan SS Login Screen) |

---
