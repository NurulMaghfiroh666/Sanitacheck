<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PetugasAreaController;
use App\Http\Controllers\AdminInspeksiController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminLaporanController;
use App\Http\Controllers\AdminRiwayatTindakLanjutController;
use App\Http\Controllers\AdminJadwalPetugasController;
use App\Http\Controllers\TugasInspeksiController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route login eksplisit
Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('guest')->name('login.submit');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/admin-dashboard', [AdminDashboardController::class, 'index'])->name('admin-dashboard');

    // Fasilitas CRUD
    Route::get('/fasilitas-umum', [FasilitasController::class, 'index'])->name('fasilitas.index');
    Route::post('/fasilitas-umum', [FasilitasController::class, 'store'])->name('fasilitas.store');
    Route::put('/fasilitas-umum/{id}', [FasilitasController::class, 'updateById'])->name('fasilitas.update');
    Route::delete('/fasilitas-umum/{id}', [FasilitasController::class, 'destroy'])->name('fasilitas.destroy');

    // Inspeksi CRUD
    Route::get('/inspeksi-sanitasi', [AdminInspeksiController::class, 'index'])->name('inspeksi.index');
    Route::post('/inspeksi-sanitasi', [AdminInspeksiController::class, 'store'])->name('inspeksi.store');
    Route::put('/inspeksi-sanitasi/{id}', [AdminInspeksiController::class, 'update'])->name('inspeksi.update');
    Route::delete('/inspeksi-sanitasi/{id}', [AdminInspeksiController::class, 'destroy'])->name('inspeksi.destroy');

    // Petugas CRUD
    Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');
    Route::post('/petugas', [PetugasController::class, 'store'])->name('petugas.store');
    Route::put('/petugas/{id}', [PetugasController::class, 'updateById'])->name('petugas.update');
    Route::delete('/petugas/{id}', [PetugasController::class, 'destroy'])->name('petugas.destroy');

    // Jadwal Petugas
    Route::get('/jadwal-petugas', [AdminJadwalPetugasController::class, 'index'])->name('jadwal-petugas.index');
    Route::post('/jadwal-petugas', [AdminJadwalPetugasController::class, 'store'])->name('jadwal-petugas.store');
    Route::put('/jadwal-petugas/{id}/status', [AdminJadwalPetugasController::class, 'updateStatus'])->name('jadwal-petugas.updateStatus');
    Route::delete('/jadwal-petugas/{id}', [AdminJadwalPetugasController::class, 'destroy'])->name('jadwal-petugas.destroy');
    Route::get('/jadwal-petugas/{id}/progress', [AdminJadwalPetugasController::class, 'viewProgress'])->name('jadwal-petugas.progress');

    // Rekap Laporan
    Route::get('/rekap-laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');

    // Tindak Lanjut Action
    Route::put('/inspeksi-sanitasi/{id}/tindak-lanjut', [AdminInspeksiController::class, 'tindakLanjut'])->name('inspeksi.tindak-lanjut');

    // Riwayat Tindak Lanjut
    Route::get('/riwayat-tindak-lanjut', [AdminRiwayatTindakLanjutController::class, 'index'])->name('riwayat-tindak-lanjut.index');

    // Profil (statis — data user dari auth)
    Route::get('/profil', function () {
        return view('admin.profil');
    })->name('profil');

    // Pengaturan (statis — konfigurasi UI)
    Route::get('/pengaturan', function () {
        return view('admin.pengaturan');
    })->name('pengaturan');

    // Tugas Inspeksi (Admin)
    Route::get('/tugas-inspeksi', [TugasInspeksiController::class, 'index'])->name('tugas-inspeksi.index');
    Route::post('/tugas-inspeksi', [TugasInspeksiController::class, 'store'])->name('tugas-inspeksi.store');
    Route::put('/tugas-inspeksi/{id}', [TugasInspeksiController::class, 'update'])->name('tugas-inspeksi.update');
    Route::delete('/tugas-inspeksi/{id}', [TugasInspeksiController::class, 'destroy'])->name('tugas-inspeksi.destroy');

}); // Tutup middleware auth

// ==========================================
// AREA PETUGAS (Dilindungi middleware auth & custom role:petugas)
// ==========================================
Route::middleware(['auth', 'role:petugas'])->prefix('area-petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasAreaController::class, 'dashboard'])->name('dashboard');
    Route::get('/jadwal', [PetugasAreaController::class, 'jadwal'])->name('jadwal');
    
    // Inspeksi
    Route::get('/inspeksi/create/{fasilitas_id}', [PetugasAreaController::class, 'create'])->name('inspeksi.create');
    Route::post('/inspeksi', [PetugasAreaController::class, 'store'])->name('inspeksi.store');
    Route::get('/inspeksi/riwayat', [PetugasAreaController::class, 'riwayat'])->name('riwayat');
    
    // Foto Inspeksi
    Route::get('/jadwal/{jadwal_id}/upload-foto', [PetugasAreaController::class, 'uploadFoto'])->name('upload-foto');
    Route::post('/jadwal/{jadwal_id}/upload-foto', [PetugasAreaController::class, 'storeFoto'])->name('store-foto');
    Route::delete('/foto/{foto_id}', [PetugasAreaController::class, 'deleteFoto'])->name('delete-foto');
    
    // Profil
    Route::get('/profil', [PetugasAreaController::class, 'profil'])->name('profil');
    Route::put('/profil/password', [PetugasAreaController::class, 'updatePassword'])->name('updatePassword');
    
    // Tugas Inspeksi (Petugas)
    Route::get('/tugas-inspeksi', [PetugasAreaController::class, 'tugasInspeksi'])->name('tugas-inspeksi');
    Route::post('/tugas-inspeksi/{id}/join', [PetugasAreaController::class, 'joinTugas'])->name('tugas-inspeksi.join');
    Route::post('/tugas-inspeksi/{id}/leave', [PetugasAreaController::class, 'leaveTugas'])->name('tugas-inspeksi.leave');
});
