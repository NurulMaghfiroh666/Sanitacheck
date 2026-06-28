<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspeksi;

class AdminRiwayatInspeksiController extends Controller
{
    public function index()
    {
        // Jadwal Rutin (Semua termasuk yang dibatalkan)
        $jadwalRutin = \App\Models\Jadwal::with(['fasilitas', 'petugas'])
            ->where('jenis_tugas', 'Pembersihan Rutin')
            ->latest('tanggal_mulai')
            ->paginate(10, ['*'], 'jadwal_page');

        // Tugas Inspeksi (Hanya yang di-ACC atau dibatalkan)
        $tugasInspeksi = \App\Models\TugasInspeksi::with(['fasilitas'])
            ->where(function($q) {
                $q->where('status', 'dibatalkan')
                  ->orWhere(function($sq) {
                      $sq->where('status', 'selesai')
                         ->where('approval_status', 'approved');
                  });
            })
            ->latest('tanggal_tugas')
            ->paginate(10, ['*'], 'tugas_page');
            
        $totalJadwalRutin = \App\Models\Jadwal::where('jenis_tugas', 'Pembersihan Rutin')->count();
        $totalTugasInspeksi = \App\Models\TugasInspeksi::count();
            
        return view('admin.riwayat-inspeksi', compact('jadwalRutin', 'tugasInspeksi', 'totalJadwalRutin', 'totalTugasInspeksi'));
    }
}
