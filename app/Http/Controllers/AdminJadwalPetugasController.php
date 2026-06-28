<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fasilitas;
use App\Models\Petugas;
use App\Models\Jadwal;
use App\Models\InspeksiPhoto;

class AdminJadwalPetugasController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with(['petugas', 'fasilitas', 'photos'])->where('status', '!=', 'Dibatalkan')->orderBy('created_at', 'desc')->get();
        $petugas = Petugas::all();
        $fasilitas = Fasilitas::all();
        $totalPetugas = $petugas->count();
        $aktifPetugas = $jadwals->where('status', 'Berlangsung')->count();
        $jadwalHariIni = $jadwals->where('tanggal_mulai', '<=', now())->where('tanggal_selesai', '>=', now())->count();
        $totalShift = $jadwals->count();
        
        return view('admin.jadwal-petugas', compact('jadwals', 'petugas', 'fasilitas', 'totalPetugas', 'aktifPetugas', 'jadwalHariIni', 'totalShift'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'petugas_id' => 'required|exists:petugas,id',
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jenis_tugas' => 'required|in:Inspeksi Sanitasi,Pembersihan Rutin,Pemeliharaan',
            'catatan' => 'nullable|string'
        ]);

        Jadwal::create([
            'petugas_id' => $request->petugas_id,
            'fasilitas_id' => $request->fasilitas_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jenis_tugas' => $request->jenis_tugas,
            'status' => 'Menunggu',
            'catatan' => $request->catatan
        ]);

        return redirect()->back()->with('success', 'Jadwal inspeksi berhasil dibuat.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Berlangsung,Selesai,Dibatalkan'
        ]);

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->status = $request->status;
        $jadwal->save();

        return redirect()->back()->with('success', 'Status jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update(['status' => 'Dibatalkan']);

        return redirect()->back()->with('success', 'Jadwal berhasil dibatalkan.');
    }

    public function viewProgress($id)
    {
        $jadwal = Jadwal::with(['petugas', 'fasilitas', 'photos'])->findOrFail($id);
        return view('admin.jadwal-progress', compact('jadwal'));
    }

    public function show($id)
    {
        $jadwal = Jadwal::with(['petugas', 'fasilitas', 'photos'])->findOrFail($id);
        return response()->json($jadwal);
    }
}
