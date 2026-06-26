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
    if ($action === 'migrate') {
        Artisan::call('migrate:fresh', ['--force' => true]);
        $message = "Migrasi database berhasil!<br><pre>" . Artisan::output() . "</pre>";
        $status = 'success';
    } elseif ($action === 'seed') {
        Artisan::call('db:seed', ['--force' => true]);
        $message = "Seeder database berhasil!<br><pre>" . Artisan::output() . "</pre>";
        $status = 'success';
    } elseif ($action === 'migrate-seed') {
        Artisan::call('migrate:fresh', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
        $message = "Migrasi dan seeder database berhasil!<br><pre>" . Artisan::output() . "</pre>";
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
    <title>Setup Database - InfinityFree</title>
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
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path></svg>
                Setup Database SanitaCheck
            </h1>
            <p class="text-slate-400 text-sm mt-1">Setup database untuk aplikasi SanitaCheck.</p>
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
                <a href="?action=migrate" class="block w-full text-center px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m0 0a3 3 0 11-2.121 2.121L3 13.586A10 10 0 1017.586 3l-1.414-1.414A3 3 0 1113.418 9H15m-7 0v16h14"></path></svg>
                    Jalankan Migrasi
                </a>
                
                <a href="?action=seed" class="block w-full text-center px-4 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Jalankan Seeder
                </a>
                
                <a href="?action=migrate-seed" class="block w-full text-center px-4 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138c.545 1.08.806 1.947.806 3.138a3.42 3.42 0 01-.806 1.946 3.42 3.42 0 00-3.138 3.138 3.42 3.42 0 01-1.946.806 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 01-.806-1.946 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    Migrate + Seed (Semua)
                </a>
            </div>

            <div class="mt-6 border-t border-slate-100 pt-5 text-center">
                <p class="text-xs text-slate-500 font-medium bg-yellow-50 text-yellow-700 py-2 px-4 rounded-lg inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Penting: Pastikan .env sudah dikonfigurasi terlebih dahulu! Segera hapus file ini setelah selesai setup.
                </p>
            </div>
        </div>
    </div>
</body>
</html>