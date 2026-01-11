# Aplikasi Laporan Keuangan

Aplikasi manajemen keuangan pribadi/perusahaan sederhana yang dibangun menggunakan Framework Laravel. Aplikasi ini dirancang untuk memudahkan pelacakan pemasukan, pengeluaran, dan manajemen dompet (wallets) dengan tampilan yang modern dan responsif.

## 🚀 Teknologi yang Digunakan

### Backend
- **Laravel 12**: Framework PHP modern yang kuat dan ekspresif.
- **PHP 8.2+**: Menggunakan fitur terbaru untuk performa maksimal.
- **MySQL**: Database relational untuk penyimpanan data yang aman.
- **Laravel Breeze**: Fondasi sistem autentikasi.

### Frontend
- **Tailwind CSS**: Framework CSS utility-first untuk desain UI yang premium.
- **Alpine.js**: Framework JavaScript minimalis untuk interaktivitas komponen.
- **Vite**: Build tool modern untuk frontend yang super cepat.
- **Chart.js**: Visualisasi data statistik keuangan dalam bentuk grafik.
- **Flatpickr**: Library pemilihan tanggal yang user-friendly.

### Fitur Tambahan
- **DomPDF**: Untuk pembuatan laporan dalam format PDF.
- **Maatwebsite Excel**: Untuk ekspor/impor data ke format Excel (XLSX).

## 🛠 Panduan Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan project ini di lingkungan lokal Anda:

### 1. Prasyarat
Pastikan Anda sudah menginstal:
- PHP >= 8.2
- Composer
- Node.js & NPM
- Database Server (MySQL/MariaDB)

### 2. Clone Repositori
```bash
git clone <url-repositori-anda>
cd laporan-keuangan
```

### 3. Instal Dependensi PHP
```bash
composer install
```

### 4. Instal Dependensi Frontend
```bash
npm install
```

### 5. Konfigurasi Lingkungan (.env)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan konfigurasi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=username_anda
DB_PASSWORD=password_anda
```

### 6. Generate Application Key
```bash
php artisan key:generate
```

### 7. Jalankan Migrasi & Seeder
```bash
php artisan migrate --seed
```

### 8. Build Aset Frontend
Untuk pengembangan (development):
```bash
npm run dev
```
Untuk produksi (production/deploy):
```bash
npm run build
```

### 9. Jalankan Server Lokal
```bash
php artisan serve
```
Aplikasi sekarang dapat diakses di `http://127.0.0.1:8000`.

## 🌐 Catatan Deployment (Hostinger/Shared Hosting)
Jika Anda melakukan deploy ke Hostinger atau layanan shared hosting lainnya:
1. Jalankan `npm run build` secara lokal di komputer Anda.
2. Unggah seluruh isi file proyek, termasuk folder `public/build` yang berisi aset yang sudah dikompilasi.
3. Pastikan konfigurasi `.env` di server sudah mengarah ke database produksi.

---
Dibuat dengan ❤️ menggunakan Laravel.
