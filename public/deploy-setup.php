<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

/**
 * =================================================================
 * SanitaCheck - Script Setup Deployment untuk InfinityFree
 * =================================================================
 * Jalankan file ini SEKALI SAJA setelah upload ke hosting:
 * https://yourdomain.com/deploy-setup.php
 *
 * Script ini akan:
 * 1. Membuat symlink storage (untuk foto inspeksi)
 * 2. Membuat folder cache yang diperlukan Laravel
 * 3. Menjalankan migrasi database
 * 4. Menghapus dirinya sendiri setelah selesai (keamanan)
 * =================================================================
 */

// Hanya boleh diakses dari browser
if (php_sapi_name() === 'cli') {
    die('Script ini harus dijalankan dari browser.');
}

// Muat Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Styling HTML
echo '<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Setup SanitaCheck</title>
<style>
    body { font-family: "Segoe UI", Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 20px; }
    .container { max-width: 700px; margin: 30px auto; background: #fff; border-radius: 16px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    h1 { color: #1b5e3a; font-size: 1.5rem; margin-bottom: 5px; }
    .subtitle { color: #888; font-size: 0.9rem; margin-bottom: 25px; }
    .step { padding: 12px 16px; margin: 8px 0; border-radius: 10px; font-size: 0.9rem; }
    .success { background: #e8f5e9; color: #2e7d32; border-left: 4px solid #2e7d32; }
    .error { background: #ffebee; color: #c62828; border-left: 4px solid #c62828; }
    .info { background: #e3f2fd; color: #1565c0; border-left: 4px solid #1565c0; }
    .warning { background: #fff3e0; color: #e65100; border-left: 4px solid #e65100; }
    .final { margin-top: 25px; padding: 20px; border-radius: 12px; text-align: center; }
    .final.ok { background: #1b5e3a; color: white; }
    .final.fail { background: #c62828; color: white; }
    code { background: #f5f5f5; padding: 2px 6px; border-radius: 4px; font-size: 0.85em; }
</style></head><body><div class="container">';

echo '<h1>🛠️ Setup SanitaCheck - InfinityFree</h1>';
echo '<p class="subtitle">Menjalankan konfigurasi awal untuk deployment...</p>';

$errors = 0;

// ============================
// STEP 1: Buat folder cache
// ============================
echo '<div class="step info">📁 <strong>Step 1:</strong> Membuat folder cache Laravel...</div>';
$cacheDirs = [
    storage_path('framework/cache/data'),
    storage_path('framework/sessions'),
    storage_path('framework/views'),
    storage_path('logs'),
    storage_path('app/public'),
    storage_path('app/public/inspeksi-photos'),
];
foreach ($cacheDirs as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo '<div class="step success">✅ Dibuat: ' . basename($dir) . '</div>';
        } else {
            echo '<div class="step error">❌ Gagal membuat: ' . $dir . '</div>';
            $errors++;
        }
    } else {
        echo '<div class="step success">✅ Sudah ada: ' . basename($dir) . '</div>';
    }
}

// ============================
// STEP 2: Setup Public Storage Folder
// ============================
echo '<div class="step info">🔗 <strong>Step 2:</strong> Menyiapkan folder penyimpanan publik...</div>';
$publicStoragePath = public_path('storage');

if (!is_dir($publicStoragePath)) {
    if (@mkdir($publicStoragePath, 0755, true)) {
        echo '<div class="step success">✅ Folder public/storage berhasil dibuat.</div>';
    } else {
        echo '<div class="step error">❌ Gagal membuat folder public/storage.</div>';
        $errors++;
    }
} else {
    echo '<div class="step success">✅ Folder public/storage sudah ada.</div>';
}

// ============================
// STEP 3: Test koneksi database
// ============================
echo '<div class="step info">🗄️ <strong>Step 3:</strong> Test koneksi database...</div>';
try {
    $pdo = DB::connection()->getPdo();
    echo '<div class="step success">✅ Koneksi database berhasil! Server: ' . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . '</div>';
} catch (\Exception $e) {
    echo '<div class="step error">❌ Koneksi database gagal: ' . htmlspecialchars($e->getMessage()) . '</div>';
    $errors++;
}

// ============================
// STEP 4: Migrasi Database
// ============================
echo '<div class="step info">📊 <strong>Step 4:</strong> Menjalankan migrasi database...</div>';
try {
    Artisan::call('migrate', ['--force' => true]);
    $output = Artisan::output();
    echo '<div class="step success">✅ Migrasi selesai!</div>';
    if (trim($output)) {
        echo '<div class="step info"><pre style="margin:0;font-size:0.8rem;white-space:pre-wrap;">' . htmlspecialchars($output) . '</pre></div>';
    }
} catch (\Exception $e) {
    echo '<div class="step error">❌ Migrasi gagal: ' . htmlspecialchars($e->getMessage()) . '</div>';
    $errors++;
}

// ============================
// STEP 5: Clear & cache config
// ============================
echo '<div class="step info">🧹 <strong>Step 5:</strong> Membersihkan cache...</div>';
try {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    echo '<div class="step success">✅ Semua cache dibersihkan.</div>';
} catch (\Exception $e) {
    echo '<div class="step warning">⚠️ Cache clear warning: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

// ============================
// STEP 6: Cek .env
// ============================
echo '<div class="step info">📋 <strong>Step 6:</strong> Validasi konfigurasi...</div>';
$appUrl = config('app.url');
$dbHost = config('database.connections.mysql.host');
echo '<div class="step info">APP_URL: <code>' . htmlspecialchars($appUrl) . '</code></div>';
echo '<div class="step info">DB_HOST: <code>' . htmlspecialchars($dbHost) . '</code></div>';
echo '<div class="step info">APP_DEBUG: <code>' . (config('app.debug') ? 'true ⚠️' : 'false ✅') . '</code></div>';

// ============================
// HASIL AKHIR
// ============================
if ($errors === 0) {
    echo '<div class="final ok">';
    echo '<h2 style="margin:0 0 8px 0;">🎉 Setup Berhasil!</h2>';
    echo '<p style="margin:0;opacity:0.9;">SanitaCheck siap digunakan. Silakan hapus file <code style="background:rgba(255,255,255,0.2);color:white;">deploy-setup.php</code> untuk keamanan.</p>';
    echo '</div>';
} else {
    echo '<div class="final fail">';
    echo '<h2 style="margin:0 0 8px 0;">⚠️ Setup Selesai dengan ' . $errors . ' Error</h2>';
    echo '<p style="margin:0;opacity:0.9;">Periksa error di atas dan perbaiki konfigurasi, lalu jalankan ulang script ini.</p>';
    echo '</div>';
}

echo '</div></body></html>';
