<?php
/**
 * Script untuk ekstrak file ZIP yang sudah dipecah menjadi parts
 * Letakkan file ini di htdocs bersama project_deploy.zip.001, .002, dst.
 * * Akses: domain.com/unzip.php
 * * ⚠️ JANGAN LUPA HAPUS FILE INI SETELAH SELESAI!
 */

set_time_limit(600);
ini_set('memory_limit', '256M');

$zipFile = dirname(__DIR__) . '/project_deploy.zip';
$extractDir = dirname(__DIR__);  // Ekstrak ke direktori parent (htdocs)
$searchDir = dirname(__DIR__);

// Ambil status dari query string atau session
$action = $_GET['action'] ?? 'check';
$status = $_SESSION['unzip_status'] ?? [];

/**
 * Fungsi untuk mengosongkan direktori secara rekursif kecuali item tertentu
 */
function deleteFolderExcept($dir, $except = []) {
    if (!is_dir($dir)) return false;
    
    $items = array_diff(scandir($dir), ['.', '..']);
    foreach ($items as $item) {
        $path = $dir . '/' . $item;
        
        // Cek apakah item ini masuk dalam daftar pengecualian
        if (in_array($item, $except)) {
            continue; 
        }
        
        // LINDUNGI file ZIP deployment agar tidak terhapus
        if (preg_match('/^project_deploy_\d{3}\.zip$/', $item)) {
            continue;
        }
        
        if (is_dir($path)) {
            deleteFolderRecursive($path);
        } else {
            @unlink($path);
        }
    }
    return true;
}

function deleteFolderRecursive($dir) {
    if (!is_dir($dir)) return false;
    $items = array_diff(scandir($dir), ['.', '..']);
    foreach ($items as $item) {
        $path = $dir . '/' . $item;
        is_dir($path) ? deleteFolderRecursive($path) : @unlink($path);
    }
    return @rmdir($dir);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanitaCHECK - Unzip Project</title>
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
            max-width: 600px;
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
        
        .status-box.warning {
            border-left-color: #ffc107;
            background: #fffbf0;
        }
        
        .status-box h3 {
            margin-bottom: 10px;
            color: #333;
        }
        
        .status-box p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .progress {
            width: 100%;
            height: 30px;
            background: #e0e0e0;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            width: 0%;
            transition: width 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        button {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #bd2130;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }
        
        .file-list {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            max-height: 200px;
            overflow-y: auto;
            font-size: 12px;
        }
        
        .file-item {
            padding: 5px;
            margin-bottom: 5px;
            background: white;
            border-radius: 3px;
            border-left: 3px solid #667eea;
            padding-left: 10px;
        }
        
        .file-item.found {
            border-left-color: #28a745;
        }
        
        .file-item.missing {
            border-left-color: #dc3545;
            opacity: 0.7;
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
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            overflow-x: auto;
            margin-top: 10px;
        }
        
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .text-warning { color: #ffc107; }
        
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 22px;
            }
            
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📦 SanitaCHECK - Extract Project</h1>
            <p>Ekstrak file project_deploy.zip.001, .002, dst.</p>
        </div>
        
        <?php
        
        // Check apakah ZipArchive tersedia
        if (!class_exists('ZipArchive')) {
            echo '<div class="status-box error">';
            echo '<h3>❌ Ekstensi ZIP tidak tersedia</h3>';
            echo '<p>Hubungi hosting provider Anda untuk mengaktifkan PHP Zip extension.</p>';
            echo '</div>';
            exit;
        }
        
        // ACTION: Check file parts
        if ($action === 'check') {
            echo '<div class="status-box">';
            echo '<h3>🔍 Mendeteksi file ZIP...</h3>';
            echo '<div class="file-list" id="fileList">';
            
            $parts = [];
            $files = @scandir($searchDir) ?: [];
            foreach ($files as $file) {
                if (preg_match('/^project_deploy_\d{3}\.zip$/', $file)) {
                    $parts[] = $file;
                    $size = filesize($searchDir . '/' . $file);
                    $sizeMB = number_format($size / 1024 / 1024, 2);
                    echo '<div class="file-item found">✓ ' . htmlspecialchars($file) . ' (' . $sizeMB . ' MB)</div>';
                }
            }
            
            sort($parts);
            
            echo '</div>';
            echo '</div>';
            
            if (empty($parts)) {
                echo '<div class="status-box error">';
                echo '<h3>❌ Tidak ada file ZIP ditemukan</h3>';
                echo '<p>Pastikan file project_deploy_xxx.zip sudah di-upload ke direktori htdocs.</p>';
                echo '</div>';
            } else {
                echo '<div class="status-box warning">';
                echo '<h3>⚠️ Proses Ekstrak</h3>';
                echo '<p>Ditemukan ' . count($parts) . ' file ZIP. Klik tombol di bawah untuk mulai ekstrak.</p>';
                echo '<p style="margin-top: 10px; font-size: 12px; color: #999;">Proses ini bisa memakan waktu 2-5 menit.</p>';
                echo '</div>';
            }
            
            echo '<div class="button-group">';
            echo '<button class="btn-secondary" onclick="if(confirm(\'PENTING: Fitur ini akan menghapus semua file Laravel lama di hosting sebelum mengekstrak yang baru. \n\nFile .env, .zip, dan foto inspeksi (public/storage/inspeksi-photos) akan DIPERTAHANKAN dan AMAN. \n\nLanjutkan penghapusan?\')) window.location.href=\'?action=cleanup_old\';">🧹 Hapus File Lama</button>';
            if (!empty($parts)) {
                echo '<button class="btn-primary" onclick="startExtract()">🚀 Mulai Ekstrak</button>';
            }
            echo '<button class="btn-danger" onclick="confirmClearHtdocs()">🗑️ Kosongkan htdocs</button>';
            echo '</div>';
            
            echo '<script>';
            echo 'function startExtract() {';
            echo '  if(confirm("Pastikan semua file ZIP sudah terupload. Lanjutkan?")) {';
            echo '    window.location.href = "?action=extract";';
            echo '  }';
            echo '}';
            echo 'function confirmClearHtdocs() {';
            echo '  if(confirm("PERINGATAN! Tindakan ini akan menghapus total isi htdocs KECUALI file .env dan folder public.\\n\\nApakah Anda yakin?")) {';
            echo '    if(confirm("Konfirmasi terakhir: Anda benar-benar ingin mengosongkan data lainnya?")) {';
            echo '      window.location.href = "?action=clear_htdocs";';
            echo '    }';
            echo '  }';
            echo '}';
            echo '</script>';
            exit;
        }

        // ACTION: Kosongkan htdocs secara agresif kecuali .env dan public
        if ($action === 'clear_htdocs') {
            $currentScript = basename(__FILE__);
            $exceptions = ['.env', 'public', $currentScript];
            
            echo '<div class="status-box">';
            echo '<h3><span class="spinner"></span>Mengosongkan direktori htdocs...</h3>';
            echo '</div>';
            
            $success = deleteFolderExcept($extractDir, $exceptions);
            
            if ($success) {
                echo '<div class="status-box success">';
                echo '<h3>✅ htdocs Berhasil Dikosongkan!</h3>';
                echo '<p>Isi htdocs telah dibersihkan. Hanya file <code>.env</code>, folder <code>public/</code>, dan skrip ini yang dipertahankan.</p>';
                echo '</div>';
            } else {
                echo '<div class="status-box error">';
                echo '<h3>❌ Pengosongan Gagal</h3>';
                echo '<p>Terjadi kendala hak akses (permission) saat menghapus beberapa file.</p>';
                echo '</div>';
            }
            
            echo '<div class="button-group">';
            echo '<button class="btn-secondary" onclick="window.location.href=\'?action=check\'">← Kembali</button>';
            echo '</div>';
            exit;
        }
        
        // ACTION: Hapus file lama (Bawaan skrip Anda yang pakai regex patterns)
        if ($action === 'cleanup_old') {
            echo '<div class="status-box">';
            echo '<h3><span class="spinner"></span>Menghapus file project lama...</h3>';
            
            // Fungsi rekursif untuk menghapus direktori dengan sistem perlindungan (exclude)
            function deleteDirectorySafe($dir, $exclude = []) {
                if (!file_exists($dir)) return true;
                if (!is_dir($dir)) return @unlink($dir);
                
                foreach (scandir($dir) as $item) {
                    if ($item == '.' || $item == '..') continue;
                    
                    $path = $dir . DIRECTORY_SEPARATOR . $item;
                    $relativePath = str_replace(dirname(__DIR__) . DIRECTORY_SEPARATOR, '', $path);
                    $relativePath = str_replace('\\', '/', $relativePath);
                    
                    // Cek apakah file/folder ini dilindungi
                    $shouldExclude = false;
                    foreach ($exclude as $exc) {
                        if (preg_match($exc, $relativePath)) {
                            $shouldExclude = true;
                            break;
                        }
                    }
                    
                    if ($shouldExclude) continue;
                    
                    if (is_dir($path)) {
                        deleteDirectorySafe($path, $exclude);
                        @rmdir($path); // Hanya akan terhapus jika kosong
                    } else {
                        @unlink($path);
                    }
                }
                return true;
            }
            
            // Regex patterns untuk melindungi file berharga
            $protectedFiles = [
                '/^unzip\.php$/',                            // Script ini
                '/^project_deploy_\d{3}\.zip$/',            // File Part ZIP
                '/^\.env$/',                                // File Konfigurasi DB
                '/^\.env\.production$/',                    // File Konfigurasi Baru
                '/^public\/storage\/inspeksi-photos/',      // FOTO INSPEKSI PENGGUNA!
            ];
            
            deleteDirectorySafe($extractDir, $protectedFiles);
            
            echo '<div class="status-box success">✅ File project lama berhasil dibersihkan! Lingkungan sudah bersih.</div>';
            echo '<div class="button-group">';
            echo '<button class="btn-primary" onclick="window.location.href=\'?action=extract\'">Lanjut Ekstrak ZIP Sekarang</button>';
            echo '</div>';
            echo '</div>';
            exit;
        }
        
        // ACTION: Ekstrak file
        if ($action === 'extract') {
            $parts = [];
            $files = @scandir($searchDir) ?: [];
            foreach ($files as $file) {
                if (preg_match('/^project_deploy_\d{3}\.zip$/', $file)) {
                    $parts[] = $file;
                }
            }
            sort($parts);
            
            if (empty($parts)) {
                echo '<div class="status-box error">';
                echo '<h3>❌ File ZIP tidak ditemukan</h3>';
                exit;
            }
            
            echo '<div class="status-box">';
            echo '<h3><span class="spinner"></span>Mengekstrak file ZIP...</h3>';
            echo '</div>';
            
            $errors = [];
            foreach ($parts as $file) {
                $zipFile = $searchDir . '/' . $file;
                $zip = new ZipArchive();
                $res = $zip->open($zipFile);
                if ($res === true) {
                    if (!$zip->extractTo($extractDir)) {
                        $errors[] = "Gagal mengekstrak isi dari $file";
                    }
                    $zip->close();
                } else {
                    $errors[] = "Gagal membuka $file (Error code: $res)";
                }
            }
            
            if (!empty($errors)) {
                echo '<div class="status-box error">';
                echo '<h3>❌ Terdapat error saat ekstraksi:</h3>';
                echo '<ul>';
                foreach ($errors as $err) {
                    echo "<li>" . htmlspecialchars($err) . "</li>";
                }
                echo '</ul>';
                echo '</div>';
                exit;
            }
            
            echo '<div class="status-box success">';
            echo '<h3>✅ Ekstrak berhasil selesai!</h3>';
            echo '<p>Semua file project sudah terekstrak.</p>';
            echo '</div>';
            
            // Cleanup
            echo '<div class="status-box warning">';
            echo '<h3>🧹 Pembersihan</h3>';
            echo '<p>Menghapus file ZIP instalasi...</p>';
            echo '</div>';
            
            foreach ($parts as $partFile) {
                @unlink($searchDir . '/' . $partFile);
            }
            
            echo '<div class="status-box success">';
            echo '<h3>✅ Selesai!</h3>';
            echo '<p>Langkah berikutnya:</p>';
            echo '<ol style="margin-left: 20px; margin-top: 10px; font-size: 14px;">';
            echo '<li style="margin-bottom: 8px;"><strong>Ubah nama file</strong> <code>.env.production</code> menjadi <code>.env</code> menggunakan File Manager.</li>';
            echo '<li style="margin-bottom: 8px;"><strong>Edit file .env</strong> tersebut, and isi detail <code>DB_DATABASE</code>, <code>DB_USERNAME</code>, dan <code>DB_PASSWORD</code>.</li>';
            echo '<li style="margin-bottom: 8px;">Akses browser ke: <a href="/deploy-setup.php" target="_blank" style="font-weight:bold;color:#1565c0;">domain.com/deploy-setup.php</a> untuk setup tahap akhir.</li>';
            echo '<li><strong>HAPUS file unzip.php ini!</strong></li>';
            echo '</ol>';
            echo '</div>';
            
            echo '<div class="button-group">';
            echo '<a href="./" style="text-decoration: none;"><button class="btn-secondary">← Kembali ke Aplikasi</button></a>';
            echo '</div>';
            
            exit;
        }
        
        ?>
    </div>
</body>
</html>