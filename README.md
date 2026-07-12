# SanitaCHECK - Kampus Sehat Terintegrasi

Aplikasi **SanitaCHECK** adalah platform monitoring sanitasi terintegrasi untuk kampus yang dibangun menggunakan [Laravel 11](https://laravel.com) dan dikelola oleh **Kelompok 6**. Panel admin telah terintegrasi dengan template CoreUI untuk tampilan yang lebih modern, bersih, dan responsif.

---

## ✨ Fitur Utama

- **Manajemen Fasilitas Sanitasi**: Pendataan dan pemantauan kondisi fasilitas kebersihan di area kampus.
- **Penjadwalan Inspeksi**: Pembuatan dan pengelolaan jadwal rutin untuk pengecekan kebersihan oleh petugas.
- **Manajemen Pengguna (Multi-role)**: Sistem autentikasi untuk dua peran utama, yaitu **Admin** (pengelola sistem) dan **Petugas** (pelaksana inspeksi).
- **Pelaporan & Monitoring**: Pencatatan riwayat kondisi fasilitas secara terpusat.
- **Dashboard Modern**: Antarmuka admin interaktif yang dibangun dengan menggunakan template CoreUI.

---

## 🛠 Persyaratan Sistem

Sebelum menjalankan proyek ini, pastikan sistem Anda telah menginstal beberapa perangkat lunak berikut:
- **PHP** (Versi 8.2 atau lebih baru)
- **Composer** (Manajer paket untuk PHP)
- **Node.js & npm** (Untuk manajemen aset frontend)
- **Git** (Untuk version control)
- **MySQL / MariaDB** (Untuk database server)

---

## 🚀 Cara Instalasi & Menjalankan Proyek

Ikuti langkah-langkah di bawah ini untuk mengkloning dan menjalankan website di komputer lokal Anda:

### 1. Clone Repository
Buka terminal/command prompt dan jalankan perintah:
```bash
git clone https://github.com/Oscurend-1205/SanitaCHECK-version1-kelompok-6.git
cd SanitaCHECK-version1-kelompok-6
```

### 2. Instalasi Dependensi PHP (Composer)
Instal semua package Laravel yang dibutuhkan:
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file konfigurasi contoh (environment) lalu buat file `.env` yang baru:
```bash
cp .env.example .env
```
*(Pengguna Windows CMD: jalankan `copy .env.example .env`)*

Setelah file `.env` terbuat, jalankan perintah ini untuk men-generate App Key:
```bash
php artisan key:generate
```

### 4. Konfigurasi Database & Seeding (MySQL)
Proyek ini menggunakan **MySQL** untuk database. 
1. Buka XAMPP/Laragon Anda dan pastikan MySQL berjalan.
2. Buat database baru bernama **`sanitacheck`** (contoh: melalui phpMyAdmin).
3. Buka file `.env` dan pastikan konfigurasi database sudah benar:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sanitacheck
   DB_USERNAME=root
   DB_PASSWORD=
   ```

Jalankan perintah berikut untuk membuat tabel dan mengisi data awal (dummy data):
```bash
php artisan migrate:fresh --seed
```

### 5. Instalasi Dependensi Frontend (NPM)
Instal library frontend dan CoreUI dependencies, lalu build aset:
```bash
npm install
npm run build
```

### 6. Jalankan Server Lokal
Setelah semuanya siap, jalankan local development server Laravel:
```bash
php artisan serve
```

🌐 Buka browser Anda dan akses: **`http://localhost:8000`**

---

## 🔐 Akun Demo (Testing)

Setelah menjalankan perintah migrasi dan seeder (`php artisan migrate:fresh --seed`), Anda dapat login menggunakan akun demo berikut yang otomatis dibuat:

**1. Akun Admin (Full Access)**
- **Username:** `admin`
- **Password:** `admin123`

**2. Akun Petugas**
Login petugas menggunakan *username*:
- **Username:** `budisantoso` (alternatif: `sitiaisyah`, `ahmadrizky`, `dewilestari`, `ekoprasetyo`)
- **Password:** `petugas123`

---

## 💡 Informasi Tambahan

- **Tema Admin**: Tema admin terletak di `resources/views/layouts/admin.blade.php`. Aset dari template bawaan ada di folder `public/assets/admin`.
- **Git Ignore**: Folder yang memuat file berukuran besar seperti `vendor/`, `node_modules/`, `public/assets/admin/node_modules/`, file database, serta *deployment intermediates* sudah otomatis diabaikan oleh `.gitignore`.

## Kontributor
- Kelompok 6
