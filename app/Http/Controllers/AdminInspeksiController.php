<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspeksi;
use App\Models\Fasilitas;
use App\Models\Petugas;
use App\Models\Jadwal;
use App\Models\TugasInspeksi;

class AdminInspeksiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');
            $status = $request->input('status');

            $jadwalQuery = Jadwal::with(['fasilitas', 'petugas'])->where('status', '!=', 'Dibatalkan');
            $tugasQuery = TugasInspeksi::with(['fasilitas', 'users'])
                ->where(function($q) {
                    $q->whereIn('status', ['tersedia', 'penuh'])
                      ->orWhere(function($sq) {
                          $sq->where('status', 'selesai')
                             ->whereIn('approval_status', ['pending', 'rejected']);
                      });
                });

            if ($search) {
                $jadwalQuery->where(function($q) use ($search) {
                    $q->whereHas('fasilitas', function($q2) use ($search) {
                        $q2->where('nama_fasilitas', 'like', "%{$search}%");
                    })->orWhereHas('petugas', function($q2) use ($search) {
                        $q2->where('nama_petugas', 'like', "%{$search}%");
                    });
                });

                $tugasQuery->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhereHas('fasilitas', function($q2) use ($search) {
                          $q2->where('nama_fasilitas', 'like', "%{$search}%");
                      });
                });
            }

            if ($status) {
                if ($status == 'Tertunda') {
                    $jadwalQuery->where('status', 'Menunggu');
                } elseif ($status == 'Dalam Proses') {
                    $jadwalQuery->where('status', 'Berlangsung');
                } elseif ($status == 'Selesai') {
                    $jadwalQuery->where('status', 'Selesai');
                }
            }

            $jadwals = $jadwalQuery->latest()->paginate(5, ['*'], 'jadwal_page');
            $tugasInspeksi = $tugasQuery->latest()->paginate(10, ['*'], 'tugas_page');
            
            $fasilitasList = Fasilitas::where('status_aktif', true)->get();
            $petugasList = Petugas::with('user')->where('status_aktif', true)->get();

            return view('admin.inspeksi', compact('jadwals', 'tugasInspeksi', 'fasilitasList', 'petugasList'));
        } catch (\Exception $e) {
            \Log::error('Error in AdminInspeksiController@index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'kondisi_kebersihan' => 'required|string',
            'ketersediaan_air' => 'required|string',
            'ketersediaan_sabun' => 'required|string',
            'bau_tidak_sedap' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        Inspeksi::create([
            'fasilitas_id' => $request->fasilitas_id,
            'petugas_id' => auth()->id(), // jika admin yang menambah
            'tanggal_inspeksi' => now(),
            'kondisi_kebersihan' => $request->kondisi_kebersihan,
            'ketersediaan_air' => $request->ketersediaan_air,
            'ketersediaan_sabun' => $request->ketersediaan_sabun,
            'bau_tidak_sedap' => $request->bau_tidak_sedap,
            'catatan' => $request->catatan,
            'status_tindak_lanjut' => 'Menunggu' // Default
        ]);

        return redirect()->back()->with('success', 'Inspeksi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'kondisi_kebersihan' => 'required|string',
            'ketersediaan_air' => 'required|string',
            'ketersediaan_sabun' => 'required|string',
            'bau_tidak_sedap' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $inspeksi = Inspeksi::findOrFail($id);
        $inspeksi->update([
            'fasilitas_id' => $request->fasilitas_id,
            'kondisi_kebersihan' => $request->kondisi_kebersihan,
            'ketersediaan_air' => $request->ketersediaan_air,
            'ketersediaan_sabun' => $request->ketersediaan_sabun,
            'bau_tidak_sedap' => $request->bau_tidak_sedap,
            'catatan' => $request->catatan,
        ]);

        return redirect()->back()->with('success', 'Data inspeksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $inspeksi = Inspeksi::findOrFail($id);
        $inspeksi->delete();

        return redirect()->back()->with('success', 'Data inspeksi berhasil dihapus.');
    }

    public function tindakLanjut(Request $request, $id)
    {
        $request->validate([
            'tindakan' => 'required|string',
            'skor' => 'nullable|integer|min:0|max:100',
            'action' => 'required|in:selesai,batal'
        ]);

        $inspeksi = Inspeksi::findOrFail($id);
        
        $statusBaru = $request->action === 'batal' ? 'Dibatalkan' : 'Selesai';
        $prefixCatatan = $request->action === 'batal' ? '[Dibatalkan]' : '[Tindak Lanjut]';
        
        // Tambahkan tindakan ke catatan
        $catatanBaru = $inspeksi->catatan 
            ? $inspeksi->catatan . "\n\n{$prefixCatatan}: " . $request->tindakan 
            : "{$prefixCatatan}: " . $request->tindakan;

        $inspeksi->update([
            'status_tindak_lanjut' => $statusBaru,
            'catatan' => $catatanBaru,
            'skor' => $request->skor
        ]);

        $pesan = $request->action === 'batal' ? 'Tindak lanjut dibatalkan.' : 'Tindak lanjut berhasil disimpan dan status menjadi Selesai.';
        return redirect()->back()->with('success', $pesan);
    }
}
