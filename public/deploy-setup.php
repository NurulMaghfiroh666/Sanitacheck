<?php
ini_set('max_execution_time', 600);
ini_set('memory_limit', '512M');

$baseDir = dirname(__DIR__);
require $baseDir . '/vendor/autoload.php';

$app = require_once $baseDir . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$message = '';
$status = '';
$action = $_GET['action'] ?? '';

try {
    if ($action === 'clear-cache') {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        $message = "Semua cache berhasil dibersihkan!<br><pre>" . Artisan::output() . "</pre>";
        $status = 'success';
    } elseif ($action === 'storage-link') {
        // Buat symlink manual karena symlink() mungkin tidak berfungsi di hosting
        $publicStorage = $baseDir . '/public/storage';
        $storageAppPublic = $baseDir . '/storage/app/public';
        
        if (!file_exists($publicStorage)) {
            if (function_exists('symlink')) {
                symlink($storageAppPublic, $publicStorage);
                $message = "Storage link berhasil dibuat menggunakan symlink!";
            } else {
                // Fallback: copy folder
                function recurse_copy($src, $dst) {
                    $dir = opendir($src);
                    @mkdir($dst);
                    while(false !== ( $file = readdir($dir)) ) {
                        if (( $file != '.' ) && ( $file != '..' )) {
                            if ( is_dir($src . '/' . $file) ) {
                                recurse_copy($src . '/' . $file,$dst . '/' . $file);
                            } else {
                                copy($src . '/' . $file,$dst . '/' . $file);
                            }
                        }
                    }
                    closedir($dir);
                }
                recurse_copy($storageAppPublic, $publicStorage);
                $message = "Storage link berhasil dibuat dengan menyalin folder (fallback)!";
            }
        } else {
            $message = "Storage link sudah ada!";
        }
        $status = 'success';
    } elseif ($action === 'all') {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        
        $publicStorage = $baseDir . '/public/storage';
        $storageAppPublic = $baseDir . '/storage/app/public';
        
        if (!file_exists($publicStorage)) {
            if (function_exists('symlink')) {
                symlink($storageAppPublic, $publicStorage);
            } else {
                function recurse_copy($src, $dst) {
                    $dir = opendir($src);
                    @mkdir($dst);
                    while(false !== ( $file = readdir($dir)) ) {
                        if (( $file != '.' ) && ( $file != '..' )) {
                            if ( is_dir($src . '/' . $file) ) {
                                recurse_copy($src . '/' . $file,$dst . '/' . $file);
                            } else {
                                copy($src . '/' . $file,$dst . '/' . $file);
                            }
                        }
                    }
                    closedir($dir);
                }
                recurse_copy($storageAppPublic, $publicStorage);
            }
        }
        
        $message = "Semua setup selesai! Cache dibersihkan dan storage link dibuat.<br><pre>" . Artisan::output() . "</pre>";
        $status = 'success';
    }
} catch (Exception $e) {
    $message = "Error: " . $e->getMessage();
    $status = 'error';
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deploy Setup - InfinityFree</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        pre { background: #1e293b; color: #e2e8f0; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; font-size: 0.875rem; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden border border-slate-100">
        <!-- Header -->
        <div class="bg-slate-900 px-6 py-5 text-white">
            <h1 class="text-xl font-bold flex items-center gap-2">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Deploy Setup SanitaCheck
            </h1>
            <p class="text-slate-400 text-sm mt-1">Setup akhir untuk deployment di InfinityFree.</p>
        </div>

        <div class="p-6">
            <?php if ($message): ?>
                <div class="mb-6 p-4 rounded-xl border <?php echo $status === 'success' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700'; ?>">
                    <div class="flex items-start gap-3">
                        <?php if ($status === 'success'): ?>
                            <svg class="w-6 h-6 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <?php else: ?>
                            <svg class="w-6 h-6 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <?php endif; ?>
                        <div>
                            <h3 class="font-bold text-sm <?php echo $status === 'success' ? 'text-green-800' : 'text-red-800'; ?>">
                                <?php echo $status === 'success' ? 'Berhasil!' : 'Terjadi Kesalahan!'; ?>
                            </h3>
                            <div class="text-sm mt-1"><?php echo $message; ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grid gap-4">
                <a href="?action=clear-cache" class="block w-full text-center px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Clear Semua Cache
                </a>
                
                <a href="?action=storage-link" class="block w-full text-center px-4 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Buat Storage Link
                </a>
                
                <a href="?action=all" class="block w-full text-center px-4 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Jalankan Semua (Cache + Storage Link)
                </a>
            </div>

            <div class="mt-6 border-t border-slate-100 pt-5 text-center">
                <p class="text-xs text-slate-500 font-medium bg-yellow-50 text-yellow-700 py-2 px-4 rounded-lg inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Penting: Segera hapus file ini setelah selesai setup!
                </p>
            </div>
        </div>
    </div>
</body>
</html>