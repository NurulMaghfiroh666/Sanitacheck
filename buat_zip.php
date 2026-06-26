<?php
/**
 * Script untuk membuat ZIP dari project dan memecahnya menjadi beberapa part
 * (Maksimal 9MB per file) agar bisa diupload ke InfinityFree.
 * 
 * Menggunakan PHP ZipArchive (cross-platform, tidak bergantung pada tar.exe)
 * 
 * ✅ FIXED: Memastikan semua file penting termasuk (controllers, views, migrations, dll)
 */

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "⚠️ Warning: $errstr\n";
    return true;
});

ini_set('max_execution_time', 600); // Waktu eksekusi 10 menit
ini_set('memory_limit', '512M');

$zipFileName = 'project_deploy.zip';
$chunkSize = 9 * 1024 * 1024; // 9MB (InfinityFree memiliki limit upload 10MB)

// Bersihkan file ZIP dan part-file yang sudah ada
function cleanUpOldFiles($zipName) {
    // Coba hapus file ZIP utama beberapa kali
    if (file_exists($zipName)) {
        echo "🗑️ Menghapus file $zipName yang sudah ada...\n";
        $maxAttempts = 3;
        for ($i = 0; $i < $maxAttempts; $i++) {
            if (@unlink($zipName)) {
                echo "   ✓ File berhasil dihapus\n";
                break;
            } else {
                if ($i < $maxAttempts - 1) {
                    echo "   ⚠️ Percobaan " . ($i + 1) . " gagal, mencoba lagi...\n";
                    sleep(1);
                } else {
                    echo "   ⚠️ Gagal menghapus file (mungkin sedang dibuka program lain)\n";
                }
            }
        }
    }
    
    // Hapus part files
    $files = glob('project_deploy_*.zip');
    if ($files) {
        foreach ($files as $file) {
            echo "🗑️ Menghapus file $file yang sudah ada...\n";
            @unlink($file);
        }
    }
    
    // Hapus file temporary bawaan PHP ZipArchive (jika ada error)
    $tempFiles = glob('project_deploy_*.zip.*');
    if ($tempFiles) {
        foreach ($tempFiles as $file) {
            echo "🗑️ Menghapus file temporary $file...\n";
            @unlink($file);
        }
    }
}

cleanUpOldFiles($zipFileName);

echo "📦 Memulai proses kompresi...\n\n";

// ============================================================
// FOLDER YANG HARUS SELALU DIMASUKKAN (CRITICAL)
// ============================================================
$alwaysIncludeFolders = [
    'app',                      // Application code (Controllers, Models, Providers, HTTP, Requests)
    'bootstrap',                // Bootstrap files
    'config',                   // Configuration files
    'database/migrations',      // Database migrations (PENTING!)
    'public',                   // Public assets (kecuali build files besar)
    'resources/views',          // Views (PENTING! Jangan di-exclude)
    'resources/lang',           // Language files
    'routes',                   // Route definitions (PENTING!)
    'storage',                  // Storage directories
];

// ============================================================
// FILE/FOLDER YANG TIDAK DIIKUTKAN DALAM ZIP DEPLOYMENT
// ============================================================

// Folder yang harus di-exclude sepenuhnya (cocokkan nama folder)
$excludeFolders = [
    // --- Version Control ---
    '.git',
    '.gitignore',

    // --- IDE / Editor Config ---
    '.cursor',
    '.vscode',
    '.idea',

    // --- Dependencies (dev/build) - BESAR SEKALI! ---
    'node_modules',
    // ⚠️ vendor/ TIDAK di-exclude karena InfinityFree tidak punya SSH/composer

    // --- Testing ---
    'tests',

    // --- Build artifacts ---
    'public/hot',
    'public/build',         // Vite build output (besar!)

    // --- Storage cache/logs (isi-nya saja, folder tetap ada) ---
    'storage/logs',
    'public/storage',       // Symlink/Junction ke storage/app/public

    // --- Admin assets dependencies (besar!) ---
    'public/assets/admin/node_modules',  // npm dependencies (ratusan MB)
    'public/assets/admin/src',          // Source files (hanya dist yang diperlukan)

    // --- Development tools (besar!) ---
    'vendor/laravel/pint/builds',       // Laravel Pint binary (development tool)

    // --- ZIP artifacts (di root saja) ---
];

// File spesifik yang harus di-exclude (cocokkan path relatif)
$excludeFiles = [
    // --- Version Control ---
    '.gitignore',
    '.gitattributes',

    // --- IDE / Editor Config ---
    '.editorconfig',
    '.DS_Store',

    // --- Dependencies (dev/build) ---
    '.npmrc',
    'package.json',          // Di-install via npm install
    'package-lock.json',     // Di-install via npm install
    'vite.config.js',        // Build config (tidak perlu di-production)
    'tailwind.config.js',
    'postcss.config.js',

    // --- Environment (lokal) - HARUS DIBUAT MANUAL ---
    '.env',
    '.env.local',

    // --- Testing ---
    'phpunit.xml',

    // --- Temporary / Development script ---
    'buat_zip.php',          // Script ini sendiri
    'extract.php',
    'coreui.zip',
    'ai_usage_log.json',

    // --- Dokumentasi dev ---
    'design.md',
    'PANDUAN_DESAIN.md',
    'README.md',             // Dokumentasi (opsional)

    // --- Old or unused files ---
    'scratch_modal.blade.php',
    'erd_chen.puml',
    'guide.txt',
    'server.php',            // Dev server (tidak perlu di-production)

    // --- ZIP artifacts ---
    'project_deploy.zip',
];

// Pattern wildcard yang harus di-exclude
$excludePatterns = [
    '.py',                    // Python scripts
    '.md',                    // Markdown files
    '.puml',                  // PlantUML files
    '.sqlite',                // SQLite databases (often locked, causes permission denied)
    '.map',                   // Source map files (development only)
];

// Pattern path yang harus di-exclude (match partial path)
$excludePathPatterns = [
    'database/factories/',    // Fixtures (tidak perlu di-production)
    'database/seeders/',      // Seeders (tidak perlu di-production)
    'resources/css/',         // Compiled via Vite ke public/build
    'resources/js/',          // Compiled via Vite ke public/build
    'public/build/',          // Vite compiled assets (besar!)
    'storage/logs/',          // Log files (besar!)
    'storage/framework/cache/',
    'storage/framework/sessions/',
    'storage/framework/views/',
    'storage/app/public/inspeksi-photos/',  // User uploaded photos (tidak perlu di deployment)
];

// ============================================================
// FILE/FOLDER YANG ISI-NYA DI-EXCLUDE TAPI FOLDER HARUS TETAP ADA
// (Akan dibuatkan .gitkeep sebagai placeholder)
// ============================================================
$emptyButRequired = [
    'storage/logs',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache',
];

/**
 * Cek apakah file/folder berada di critical folders yang harus selalu dimasukkan
 */
function isInCriticalFolder($relativePath, $alwaysIncludeFolders) {
    $relativePath = str_replace('\\', '/', $relativePath);
    
    foreach ($alwaysIncludeFolders as $folder) {
        // Exact match atau berada dalam folder
        if ($relativePath === $folder || 
            strpos($relativePath, $folder . '/') === 0) {
            return true;
        }
    }
    
    return false;
}

function shouldExclude($relativePath, $excludeFolders, $excludeFiles, $excludePatterns, $excludePathPatterns, $alwaysIncludeFolders) {
    // Normalize separator
    $relativePath = str_replace('\\', '/', $relativePath);
    
    // RULE 1: Secara paksa exclude node_modules dan .git di manapun posisinya
    if (preg_match('/(^|\/)(node_modules|\.git)(\/|$)/', $relativePath)) {
        return true;
    }
    
    $basename = basename($relativePath);
    
    // RULE 1.5: Exclude generated zip files
    if (preg_match('/^project_deploy(?:_\d+)?\.zip$/', $basename)) {
        return true;
    }
    
    // RULE 2: Cek folder exclusion
    foreach ($excludeFolders as $folder) {
        if ($relativePath === $folder || strpos($relativePath, $folder . '/') === 0) {
            return true;
        }
    }
    
    // RULE 3: Cek file spesifik
    if (in_array($relativePath, $excludeFiles) || in_array($basename, $excludeFiles)) {
        return true;
    }
    
    // RULE 4: Cek pattern (ekstensi / wildcard)
    foreach ($excludePatterns as $pattern) {
        if (substr($basename, -strlen($pattern)) === $pattern) {
            return true;
        }
    }
    
    // RULE 5: Cek path patterns (partial path match)
    foreach ($excludePathPatterns as $pathPattern) {
        if (strpos($relativePath, $pathPattern) === 0) {
            return true;
        }
    }
    
    // RULE 6: Exclude log files
    if (basename($relativePath) === 'laravel.log' || 
        substr($basename, -4) === '.log') {
        return true;
    }
    
    // RULE 7: Exclude session/cache/view files (tapi keep folder)
    if ((strpos($relativePath, 'storage/framework/sessions/') === 0 ||
         strpos($relativePath, 'storage/framework/views/') === 0 ||
         strpos($relativePath, 'storage/framework/cache/') === 0) &&
        basename($relativePath) !== '.gitkeep') {
        return true;
    }
    
    // Jika tidak masuk aturan exclude manapun, file akan dimasukkan
    return false;
}

// ============================================================
// MULAI PROSES KOMPRESI MENGGUNAKAN PHP ZipArchive (MULTI-PART)
// ============================================================

if (!class_exists('ZipArchive')) {
    die("\n❌ FATAL: Ekstensi PHP ZIP tidak tersedia! Aktifkan ext-zip di php.ini.\n");
}

echo "✅ Memulai proses pembuatan file ZIP...\n";
echo "🔄 Sistem akan memecah file secara otomatis jika ukuran melebihi 8MB...\n\n";

$baseDir = __DIR__;
$fileCount = 0;
$skippedCount = 0;
$startTime = time();

$zipIndex = 1;
$currentUncompressedSize = 0;
$maxUncompressedSize = 8 * 1024 * 1024; // 8MB
$zipName = sprintf("project_deploy_%03d.zip", $zipIndex);
$zip = new ZipArchive();
$zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
$createdZips = [];

function addDirectoryToZipMulti($baseDir, $currentDir, $excludeFolders, $excludeFiles, $excludePatterns, $excludePathPatterns, $alwaysIncludeFolders, &$fileCount, &$skippedCount, &$zip, &$zipName, &$zipIndex, &$currentUncompressedSize, $maxUncompressedSize, &$createdZips) {
    if (!is_dir($currentDir) || !is_readable($currentDir)) return;
    
    $items = @scandir($currentDir);
    if ($items === false) return;
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        $fullPath = $currentDir . DIRECTORY_SEPARATOR . $item;
        $relativePath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $fullPath);
        $relativePath = str_replace('\\', '/', $relativePath);
        
        if (is_link($fullPath)) continue;
        
        if (shouldExclude($relativePath, $excludeFolders, $excludeFiles, $excludePatterns, $excludePathPatterns, $alwaysIncludeFolders)) {
            $skippedCount++;
            continue;
        }
        
        if (is_dir($fullPath)) {
            @$zip->addEmptyDir($relativePath);
            addDirectoryToZipMulti($baseDir, $fullPath, $excludeFolders, $excludeFiles, $excludePatterns, $excludePathPatterns, $alwaysIncludeFolders, $fileCount, $skippedCount, $zip, $zipName, $zipIndex, $currentUncompressedSize, $maxUncompressedSize, $createdZips);
        } else {
            $handle = @fopen($fullPath, 'rb');
            if ($handle === false) {
                $skippedCount++;
                continue;
            }
            fclose($handle);

            $fileSize = filesize($fullPath);
            
            // Jika melebihi limit, tutup ZIP ini dan buat yang baru
            if ($currentUncompressedSize > 0 && ($currentUncompressedSize + $fileSize > $maxUncompressedSize)) {
                $zip->close();
                $createdZips[] = $zipName;
                echo "   💾 Disimpan: $zipName (" . number_format($currentUncompressedSize / 1024 / 1024, 2) . " MB uncompressed)\n";
                
                $zipIndex++;
                $currentUncompressedSize = 0;
                $zipName = sprintf("project_deploy_%03d.zip", $zipIndex);
                $zip = new ZipArchive();
                $zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            }

            if (@$zip->addFile($fullPath, $relativePath)) {
                $fileCount++;
                $currentUncompressedSize += $fileSize;
            } else {
                $skippedCount++;
            }
        }
    }
}

try {
    addDirectoryToZipMulti($baseDir, $baseDir, $excludeFolders, $excludeFiles, $excludePatterns, $excludePathPatterns, $alwaysIncludeFolders, $fileCount, $skippedCount, $zip, $zipName, $zipIndex, $currentUncompressedSize, $maxUncompressedSize, $createdZips);
} catch (Exception $e) {
    $zip->close();
    die("\n❌ FATAL: Error saat memproses file: " . $e->getMessage() . "\n");
}

// Tambahkan folder kosong wajib
foreach ($emptyButRequired as $dir) {
    @$zip->addEmptyDir($dir);
    @$zip->addFromString($dir . '/.gitkeep', '');
}

if (file_exists('.env.example')) @$zip->addFile('.env.example', '.env.example');
if (file_exists('README.md')) @$zip->addFile('README.md', 'README.md');

// Tutup zip terakhir
$zip->close();
$createdZips[] = $zipName;
echo "   💾 Disimpan: $zipName (" . number_format($currentUncompressedSize / 1024 / 1024, 2) . " MB uncompressed)\n";

$elapsedTime = time() - $startTime;

echo "\n" . str_repeat("=", 70) . "\n";
echo "✅ SEMUA FILE ZIP BERHASIL DIBUAT!\n";
echo str_repeat("=", 70) . "\n";
echo "📊 STATISTIK:\n";
echo "   • File ditambahkan: $fileCount\n";
echo "   • File/folder diskip: $skippedCount\n";
echo "   • Total Part ZIP: " . count($createdZips) . "\n";
echo "   • Waktu proses: $elapsedTime detik\n\n";

echo "🚀 LANGKAH DEPLOY KE INFINITYFREE:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "1️⃣ Upload semua file project_deploy_xxx.zip ke folder 'htdocs'\n";
echo "   (Gunakan FTP Manager atau File Manager hosting)\n\n";

echo "2️⃣ Buat folder 'public' di htdocs, lalu buat file 'unzip.php' di dalamnya dengan kode berikut:\n";
echo "   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo file_get_contents(__DIR__ . '/public/unzip.php') ?: "   (Lihat template di github)\n";
echo "   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "3️⃣ Akses browser: domain.com/unzip.php untuk mulai ekstrak\n\n";

echo "4️⃣ Setelah ekstrak selesai, MANUAL SETUP di hosting:\n";
echo "   A. Buat file .env di folder root:\n";
echo "      • Copy dari .env.example\n";
echo "      • Isi: DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD\n";
echo "      • Format: DB_HOST=localhost (atau IP hosting)\n\n";

echo "5️⃣ Konfigurasi Database (via phpMyAdmin):\n";
echo "   • Buat database baru di panel InfinityFree\n";
echo "   • Buka phpMyAdmin, import file SQL dari lokal Anda\n\n";

echo "6️⃣ Setup .env & APP_KEY (via File Manager):\n";
echo "   • Salin nilai APP_KEY=... dari file .env lokal Anda\n";
echo "   • Paste ke dalam file .env di htdocs hosting Anda\n\n";

echo "7️⃣ Test aplikasi di browser: domain.com\n\n";

echo "⚠️  PENTING:\n";
echo "   • HAPUS file unzip.php setelah selesai!\n";
echo "   • JANGAN upload project.zip yang utuh (akan ditolak)\n";
echo "   • Pastikan DB credentials benar di .env\n";
echo "   • Check storage/logs untuk error messages\n\n";

echo "✅ Siap untuk deployment!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
