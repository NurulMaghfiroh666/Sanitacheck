<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TugasInspeksi;
use App\Models\Fasilitas;

class TugasInspeksiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $tugasQuery = TugasInspeksi::with(['fasilitas', 'users']);
        
        if ($search) {
            $tugasQuery->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhereHas('fasilitas', function($q2) use ($search) {
                      $q2->where('nama_fasilitas', 'like', "%{$search}%");
                  });
            });
        }
        
        $tugasInspeksi = $tugasQuery->latest()->paginate(10);
        $fasilitasList = Fasilitas::where('status_aktif', true)->get();
        
        return view('admin.tugas-inspeksi', compact('tugasInspeksi', 'fasilitasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kuota_tim' => 'required|integer|min:1|max:10',
            'tanggal_tugas' => 'required|date|after:now',
        ]);

        TugasInspeksi::create([
            'fasilitas_id' => $request->fasilitas_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kuota_tim' => $request->kuota_tim,
            'tanggal_tugas' => $request->tanggal_tugas,
            'status' => 'tersedia',
        ]);

        return redirect()->back()->with('success', 'Tugas inspeksi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kuota_tim' => 'required|integer|min:1|max:10',
            'tanggal_tugas' => 'required|date',
            'status' => 'required|in:tersedia,penuh,selesai',
        ]);

        $tugas = TugasInspeksi::findOrFail($id);
        
        // Check if new kuota is less than current joined users
        $currentJoined = $tugas->users()->count();
        if ($request->kuota_tim < $currentJoined) {
            return redirect()->back()->with('error', 'Kuota tidak boleh kurang dari jumlah petugas yang sudah bergabung.');
        }
        
        $tugas->update([
            'fasilitas_id' => $request->fasilitas_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kuota_tim' => $request->kuota_tim,
            'tanggal_tugas' => $request->tanggal_tugas,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Tugas inspeksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tugas = TugasInspeksi::findOrFail($id);
        $tugas->delete();

        return redirect()->back()->with('success', 'Tugas inspeksi berhasil dihapus.');
    }
}
