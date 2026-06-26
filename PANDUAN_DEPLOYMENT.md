# 📚 PANDUAN DEPLOYMENT SANITACHECK

## Daftar Isi

1. [Overview](#overview)
2. [Persiapan Local](#persiapan-local)
3. [Generate ZIP](#generate-zip)
4. [Upload ke Hosting](#upload-ke-hosting)
5. [Extract & Setup](#extract--setup)
6. [Troubleshooting](#troubleshooting)

---

## Overview

Proses deployment SanitaCHECK dibagi menjadi 3 tahap:

1. **Generate ZIP** - Membuat file project yang terkompresi
2. **Upload** - Upload ke hosting (file dipecah jadi beberapa parts karena limit upload)
3. **Extract & Setup** - Ekstrak, setup database, dan jalankan migrations

### File Yang Penting

| File                         | Fungsi                  | Hapus Setelah?    |
| ---------------------------- | ----------------------- | ----------------- |
| `buat_zip.php`               | Generate ZIP deployment | ✓ Ya (opsional)   |
| `project.zip.001, .002, dst` | Hasil kompresi project  | Setelah ekstrak   |
| `unzip.php`                  | Ekstrak ZIP di hosting  | **HARUS dihapus** |
| `deploy-setup.php`           | Setup final di hosting  | **HARUS dihapus** |

---

## Persiapan Local

### 1. Pastikan Project Sudah Siap

```bash
# Install composer dependencies
composer install

# Install npm dependencies (untuk asset building)
npm install

# Build assets (Vite)
npm run build

# Jalankan migrations (local)
php artisan migrate:fresh --seed
```

### 2. Clear Cache & Temporary Files

```bash
# Clear semua cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Clear storage
rm -rf storage/logs/*
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*
```

---

## Generate ZIP

### Dari Terminal / Command Line

```bash
php buat_zip.php
```

### Atau Via Browser

1. Upload `buat_zip.php` ke server lokal Anda
2. Akses: `http://localhost/buat_zip.php`
3. Script akan otomatis membuat file parts

### Output

```
✅ ZIP berhasil dibuat!
📊 STATISTIK:
   • File ditambahkan: 1250
   • File/folder diskip: 485
   • Ukuran ZIP: 85.45 MB
   • Waktu proses: 45 detik

📦 Mulai memecah file ZIP menjadi bagian-bagian kecil...
   ✓ Part 1: project.zip.001 (9.00 MB)
   ✓ Part 2: project.zip.002 (9.00 MB)
   ✓ Part 3: project.zip.003 (9.00 MB)
   ... dan seterusnya
```

### File Yang Diexclude

**Folder yang DIEXCLUDE:**

- `.git/` - Version control
- `node_modules/` - Dependencies npm
- `vendor/` - Dependencies composer
- `tests/` - Testing files
- `public/build/` - Compiled assets

**File yang DIEXCLUDE:**

- `.env` - Local environment
- `.editorconfig`, `.npmrc` - Config files
- `buat_zip.php`, `unzip.php` - Deployment scripts
- `*.md`, `*.puml`, `*.py` - Documentation & scripts

**Folder yang DIINCLUDE (Important!):**

- `app/` - Controllers, Models, Providers
- `routes/` - Route definitions
- `resources/views/` - Blade templates ✓ SEMUA VIEWS INCLUDED
- `database/migrations/` - Database migrations
- `config/` - Configuration
- `bootstrap/` - Bootstrap files

---

## Upload ke Hosting

### Persiapan di Hosting

1. **Buat folder untuk project**
    - Buat folder baru di `htdocs` (atau public_html)
    - Contoh: `htdocs/sanitacheck`

2. **Upload file parts via FTP**

    **Menggunakan FTP Manager (recommended):**

    ```
    Host: ftp.domain.com
    Username: ftp_username
    Password: ftp_password

    Upload ke: /public_html/  (atau /htdocs/)
    ```

    **File yang diupload:**
    - `project.zip.001`
    - `project.zip.002`
    - `project.zip.003`
    - ... (semua parts)
    - `unzip.php` (upload juga file ini!)

3. **Verifikasi upload**
    - Gunakan File Manager di Cpanel
    - Pastikan semua file ada dan ukuran sesuai

---

## Extract & Setup

### Step 1: Ekstrak Project ZIP

1. Buka browser: `domain.com/unzip.php`
2. Script akan mendeteksi file parts
3. Klik tombol "Mulai Ekstrak"
4. Tunggu sampai selesai (2-5 menit)

**Apa yang terjadi:**

- File parts digabung menjadi 1 file `project.zip`
- ZIP diekstrak ke directory root
- File parts dan project.zip dihapus otomatis

### Step 2: Setup .env File

1. Buka File Manager di Cpanel
2. Navigate ke root project folder
3. Copy file `.env.example` → `.env`
4. Edit file `.env`:

```env
APP_NAME=SanitaCHECK
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain.com

DB_CONNECTION=mysql
DB_HOST=localhost        # (atau IP dari hosting)
DB_PORT=3306
DB_DATABASE=sanitacheck  # (buat database baru di Cpanel)
DB_USERNAME=sanitacheck  # (buat user baru di Cpanel)
DB_PASSWORD=password_yang_kuat

SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

**Cara mendapatkan DB credentials:**

- Buka **Cpanel** → **MySQL Databases**
- Buat Database baru (contoh: `sanitacheck`)
- Buat User MySQL baru
- Grant semua privileges ke user pada database tersebut
- Copy host, username, password ke `.env`

### Step 3: Upload .env dan Deploy Setup

1. Upload file `.env` ke root project
2. Buka browser: `domain.com/deploy-setup.php`
3. Script akan:
    - Check konfigurasi
    - Clear cache
    - Create storage symlink
    - Run migrations
4. Tunggu sampai selesai

**Output akan menampilkan:**

```
✅ Setup Selesai!
   ✓ Config cache cleared
   ✓ View cache cleared
   ✓ Route cache cleared
   ✓ Storage symlink created
```

### Step 4: Hapus File Deployment

**PENTING! Hapus file berikut dari hosting:**

1. File `unzip.php` - Via FTP atau File Manager
2. File `deploy-setup.php` - Via FTP atau File Manager
3. File `buat_zip.php` - Via FTP atau File Manager (jika diupload)

**Mengapa harus dihapus?**

- Security risk - file ini bisa diakses siapa saja
- Tidak lagi diperlukan
- Bisa digunakan untuk exploit

### Step 5: Akses Aplikasi

Aplikasi sudah siap!

**URL:** `https://domain.com`

**Default Login:**

```
Email: admin@sanitacheck.test
Password: password
```

---

## Troubleshooting

### 1. "Tidak ada file parts ditemukan"

**Penyebab:**

- File `project.zip.xxx` tidak terupload
- Nama file salah

**Solusi:**

- Verifikasi semua file sudah terupload via FTP
- Nama file harus exact: `project.zip.001`, `project.zip.002`, dst

### 2. "Gagal membuka file ZIP (Error code: 19)"

**Penyebab:**

- File parts rusak atau tidak lengkap
- File terputus saat upload

**Solusi:**

- Re-upload semua file parts
- Gunakan FTP resume/retry
- Verifikasi ukuran file lokal vs di server

### 3. "Database connection refused"

**Penyebab:**

- Credentials di `.env` salah
- Database belum dibuat
- User MySQL belum di-create

**Solusi:**

- Verifikasi credentials di Cpanel MySQL Databases
- Buat database dan user baru
- Check apakah user punya privilege

### 4. "Permission denied" untuk storage folder

**Penyebab:**

- Folder permissions tidak cocok
- Hosting tidak support chmod

**Solusi:**

1. Via SSH (jika tersedia):

    ```bash
    chmod -R 755 storage bootstrap/cache
    chmod -R 755 public/storage
    ```

2. Via File Manager:
    - Klik kanan folder → Change Permissions
    - Set ke 755

### 5. "Cannot create storage symlink"

**Penyebab:**

- Hosting tidak support symlink
- Permission issue

**Solusi:**

1. Akses SSH dan run:

    ```bash
    php artisan storage:link
    ```

2. Jika SSH tidak tersedia:
    - Buat folder `public/storage` manually
    - Upload file ke `storage/app/public` via FTP

### 6. Views/Pages Hilang atau Tidak Bisa Diklik

**Penyebab:**

- File views tidak terekstrak
- Config cache error
- File permissions

**Solusi:**

1. Check apakah folder `resources/views` ada:
    - Buka File Manager
    - Navigate ke `resources/views`
    - Pastikan semua sub-folder ada (admin, auth, petugas, dll)

2. Clear cache:
    - Run: `php artisan view:clear`
    - Run: `php artisan cache:clear`

3. Check error logs:
    - File: `storage/logs/laravel.log`
    - Download dan analisis error messages

### 7. "Too many files" atau ZIP tidak complete

**Penyebab:**

- Script timeout (lebih dari 10 menit)
- Memory limit

**Solusi:**

1. Coba ulang dari awal
2. Edit di hosting `.htaccess`:
    ```apache
    php_value max_execution_time 600
    php_value memory_limit 256M
    ```

---

## Checklist Deployment

- [ ] Project sudah di-build (npm run build)
- [ ] Cache sudah di-clear
- [ ] Jalankan `php buat_zip.php`
- [ ] Semua file parts sudah terupload
- [ ] File `unzip.php` sudah terupload
- [ ] Jalankan `domain.com/unzip.php` untuk ekstrak
- [ ] Buat database di Cpanel
- [ ] Buat user MySQL di Cpanel
- [ ] Upload/create file `.env` dengan credentials
- [ ] Upload file `deploy-setup.php`
- [ ] Jalankan `domain.com/deploy-setup.php` untuk setup final
- [ ] Hapus file `unzip.php`, `deploy-setup.php`, `buat_zip.php`
- [ ] Test akses `domain.com`
- [ ] Test login dengan credentials default
- [ ] Check `storage/logs/laravel.log` untuk errors

---

## Support

Jika ada masalah:

1. Check error di `storage/logs/laravel.log`
2. Lihat Cpanel error logs
3. Verifikasi file .env credentials
4. Contact hosting support untuk permission issues

---

**Last Updated:** 2026-06-24
