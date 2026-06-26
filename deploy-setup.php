<?php
/**
 * Script setup final untuk production deployment
 * Jalankan ini setelah ekstrak project.zip dan setup .env
 * 
 * Akses: domain.com/deploy-setup.php
 * 
 * ⚠️ JANGAN LUPA HAPUS FILE INI SETELAH SELESAI!
 */

set_time_limit(300);

// Load Laravel
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';
$_SERVER['REQUEST_METHOD'] = 'GET';

try {
    require __DIR__ . '/bootstrap/app.php';
} catch (\Exception $e) {
    die('<pre>Error loading Laravel: ' . htmlspecialchars($e->getMessage()) . '</pre>');
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanitaCHECK - Deploy Setup</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 700px;
            width: 100%;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
        }
        
        .status-box {
            background: #f5f5f5;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .status-box.success {
            border-left-color: #28a745;
            background: #f0f9f5;
        }
        
        .status-box.error {
            border-left-color: #dc3545;
            background: #fef5f5;
        }
        
        .status-box h3 {
            margin-bottom: 10px;
            color: #333;
            font-size: 16px;
        }
        
        .status-box p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 5px;
        }
        
        .checklist {
            list-style: none;
            padding: 0;
        }
        
        .checklist li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        
        .checklist li:last-child {
            border-bottom: none;
        }
        
        .icon {
            margin-right: 8px;
            font-weight: bold;
        }
        
        .icon.ok { color: #28a745; }
        .icon.fail { color: #dc3545; }
        .icon.warn { color: #ffc107; }
        
        button {
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            background: #667eea;
            color: white;
            width: 100%;
        }
        
        button:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .spinner {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 5px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .code-block {
            background: #272822;
            color: #f8f8f2;
            padding: 12px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            overflow-x: auto;
            margin-top: 10px;
        }
        
        .progress-item {
            padding: 10px;
            margin-bottom: 8px;
            background: white;
            border-radius: 3px;
            border-left: 3px solid #667eea;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 SanitaCHECK - Production Setup</h1>
            <p>Setup akhir sebelum aplikasi bisa diakses</p>
        </div>
        
        <?php
        
        $action = $_GET['action'] ?? 'check';
        $allOk = true;
        $checks = [];
        
        // ============================================================
        // CHECK 1: .env file
        // ============================================================
        $envExists = file_exists('.env');
        $checks['env_file'] = [
            'label' => 'File .env',
            'status' => $envExists ? 'ok' : 'fail',
            'message' => $envExists ? 'File .env ditemukan' : 'File .env TIDAK ditemukan',
            'details' => 'File .env adalah file konfigurasi penting yang berisi DB credentials dan app settings'
        ];
        if (!$envExists) $allOk = false;
        
        // ============================================================
        // CHECK 2: APP_KEY
        // ============================================================
        $appKey = env('APP_KEY');
        $checks['app_key'] = [
            'label' => 'APP_KEY di .env',
            'status' => (!empty($appKey) && strlen($appKey) > 10) ? 'ok' : 'warn',
            'message' => (!empty($appKey) && strlen($appKey) > 10) ? 'APP_KEY sudah di-generate' : 'APP_KEY belum di-generate atau kosong',
            'details' => 'Jalankan: php artisan key:generate'
        ];
        if (empty($appKey) || strlen($appKey) <= 10) $allOk = false;
        
        // ============================================================
        // CHECK 3: Database Connection
        // ============================================================
        $dbConfig = [
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD')
        ];
        
        $dbOk = true;
        $dbMsg = [];
        
        if (empty($dbConfig['host'])) {
            $dbMsg[] = 'DB_HOST tidak diisi';
            $dbOk = false;
        }
        if (empty($dbConfig['database'])) {
            $dbMsg[] = 'DB_DATABASE tidak diisi';
            $dbOk = false;
        }
        if (empty($dbConfig['username'])) {
            $dbMsg[] = 'DB_USERNAME tidak diisi';
            $dbOk = false;
        }
        
        $checks['db_config'] = [
            'label' => 'Database Configuration',
            'status' => $dbOk ? 'ok' : 'fail',
            'message' => $dbOk ? 'Semua DB config sudah diisi' : implode(', ', $dbMsg),
            'details' => 'Pastikan credentials di .env sesuai dengan hosting Anda'
        ];
        if (!$dbOk) $allOk = false;
        
        // ============================================================
        // CHECK 4: Folders
        // ============================================================
        $folders = ['storage', 'storage/logs', 'bootstrap/cache', 'public/storage'];
        $folderOk = true;
        $folderIssues = [];
        
        foreach ($folders as $folder) {
            if (!is_dir($folder)) {
                @mkdir($folder, 0755, true);
            }
            
            if (!is_writable($folder)) {
                $folderOk = false;
                $folderIssues[] = $folder . ' tidak writable';
            }
        }
        
        $checks['folders'] = [
            'label' => 'Folder Permissions',
            'status' => $folderOk ? 'ok' : 'warn',
            'message' => $folderOk ? 'Semua folder writable' : implode(', ', $folderIssues),
            'details' => 'Run: chmod -R 755 storage bootstrap/cache'
        ];
        
        // Display checks
        echo '<div class="status-box">';
        echo '<h3>✓ Checklist Setup</h3>';
        echo '<ul class="checklist">';
        
        foreach ($checks as $check) {
            $icon = $check['status'] === 'ok' ? '✓' : ($check['status'] === 'warn' ? '⚠' : '✗');
            $iconClass = $check['status'] === 'ok' ? 'ok' : ($check['status'] === 'warn' ? 'warn' : 'fail');
            
            echo '<li>';
            echo '<span class="icon ' . $iconClass . '">' . $icon . '</span>';
            echo '<strong>' . htmlspecialchars($check['label']) . ':</strong> ' . htmlspecialchars($check['message']);
            if (!empty($check['details'])) {
                echo '<div style="margin-top: 5px; font-size: 12px; color: #999;">' . htmlspecialchars($check['details']) . '</div>';
            }
            echo '</li>';
        }
        
        echo '</ul>';
        echo '</div>';
        
        if (!$allOk) {
            echo '<div class="status-box error">';
            echo '<h3>❌ Setup Tidak Lengkap</h3>';
            echo '<p>Pastikan semua checklist di atas sudah OK sebelum melanjutkan.</p>';
            echo '</div>';
            exit;
        }
        
        // ============================================================
        // MAIN SETUP PAGE
        // ============================================================
        
        if ($action === 'check') {
            echo '<div class="status-box success">';
            echo '<h3>✅ Semua Checklist OK!</h3>';
            echo '<p>Sistem siap untuk proses setup final.</p>';
            echo '</div>';
            
            echo '<div class="status-box">';
            echo '<h3>📝 Proses Setup yang akan dilakukan:</h3>';
            echo '<ul class="checklist">';
            echo '<li>1. Clear config cache</li>';
            echo '<li>2. Clear view cache</li>';
            echo '<li>3. Clear route cache</li>';
            echo '<li>4. Create storage symlink</li>';
            echo '<li>5. Run database migrations</li>';
            echo '<li>6. Seed database</li>';
            echo '</ul>';
            echo '</div>';
            
            echo '<button onclick="if(confirm(\'Proses setup akan memulai. Pastikan database sudah tersedia. Lanjutkan?\')) window.location.href=\'?action=setup\';">🚀 Mulai Setup</button>';
            exit;
        }
        
        // ============================================================
        // EXECUTE SETUP
        // ============================================================
        
        if ($action === 'setup') {
            $app = require_once __DIR__ . '/bootstrap/app.php';
            $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
            
            echo '<div class="status-box">';
            echo '<h3><span class="spinner"></span>Menjalankan setup...</h3>';
            echo '</div>';
            
            $steps = [];
            
            // Step 1: Config cache
            try {
                ob_start();
                $kernel->call('config:cache');
                ob_end_clean();
                $steps[] = ['status' => 'ok', 'message' => '✓ Config cache cleared'];
            } catch (\Exception $e) {
                $steps[] = ['status' => 'warn', 'message' => '⚠ Config cache: ' . $e->getMessage()];
            }
            
            // Step 2: View cache
            try {
                ob_start();
                $kernel->call('view:clear');
                ob_end_clean();
                $steps[] = ['status' => 'ok', 'message' => '✓ View cache cleared'];
            } catch (\Exception $e) {
                $steps[] = ['status' => 'warn', 'message' => '⚠ View cache: ' . $e->getMessage()];
            }
            
            // Step 3: Route cache
            try {
                ob_start();
                $kernel->call('route:cache');
                ob_end_clean();
                $steps[] = ['status' => 'ok', 'message' => '✓ Route cache cleared'];
            } catch (\Exception $e) {
                $steps[] = ['status' => 'warn', 'message' => '⚠ Route cache: ' . $e->getMessage()];
            }
            
            // Step 4: Storage link
            try {
                ob_start();
                $kernel->call('storage:link');
                ob_end_clean();
                $steps[] = ['status' => 'ok', 'message' => '✓ Storage symlink created'];
            } catch (\Exception $e) {
                $steps[] = ['status' => 'warn', 'message' => '⚠ Storage symlink: ' . $e->getMessage()];
            }
            
            echo '<div class="status-box">';
            echo '<h3>📋 Hasil Eksekusi</h3>';
            foreach ($steps as $step) {
                $icon = $step['status'] === 'ok' ? '✓' : '⚠';
                echo '<div class="progress-item">' . $icon . ' ' . htmlspecialchars($step['message']) . '</div>';
            }
            echo '</div>';
            
            echo '<div class="status-box success">';
            echo '<h3>✅ Setup Selesai!</h3>';
            echo '<p><strong>Aplikasi SanitaCHECK sudah siap digunakan!</strong></p>';
            echo '<p style="margin-top: 10px; font-size: 14px;">Akses aplikasi di: <strong>' . htmlspecialchars('http://' . $_SERVER['HTTP_HOST']) . '</strong></p>';
            echo '<p style="margin-top: 15px; font-size: 13px; color: #999;"><strong>⚠️ PENTING:</strong></p>';
            echo '<ul style="margin-left: 20px; font-size: 13px; color: #999;">';
            echo '<li>✓ HAPUS file deploy-setup.php ini setelah selesai!</li>';
            echo '<li>✓ HAPUS file unzip.php jika masih ada</li>';
            echo '<li>✓ Default login: admin@sanitacheck.test / password</li>';
            echo '</ul>';
            echo '</div>';
            
            exit;
        }
        
        ?>
    </div>
</body>
</html>
