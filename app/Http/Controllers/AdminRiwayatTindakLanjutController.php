<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspeksi;

class AdminRiwayatTindakLanjutController extends Controller
{
    public function index()
    {
        // Inspeksi yang kondisinya perlu ditindaklanjuti dan SUDAH selesai / dibatalkan
        $dataTindakLanjut = Inspeksi::with(['fasilitas', 'petugas'])
            ->whereIn('status_tindak_lanjut', ['Selesai', 'Dibatalkan'])
            ->latest('updated_at')
            ->paginate(10);
            
        $totalSelesai = Inspeksi::where('status_tindak_lanjut', 'Selesai')->count();
        $totalBatal = Inspeksi::where('status_tindak_lanjut', 'Dibatalkan')->count();
        $totalRiwayat = $totalSelesai + $totalBatal;
            
        return view('admin.riwayat-tindak-lanjut', compact('dataTindakLanjut', 'totalSelesai', 'totalBatal', 'totalRiwayat'));
    }
}
