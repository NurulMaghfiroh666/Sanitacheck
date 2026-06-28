<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TugasInspeksi;
use App\Models\Fasilitas;
use App\Models\User;
use App\Models\Inspeksi;
use Illuminate\Support\Facades\DB;

class TugasInspeksiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $tugasQuery = TugasInspeksi::with(['fasilitas', 'users'])
            ->where('status', '!=', 'dibatalkan')
            ->where(function($q) {
                $q->where('status', '!=', 'selesai')
                  ->orWhere('approval_status', '!=', 'approved');
            });
        
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

    public function show($id)
    {
        $tugas = TugasInspeksi::with(['fasilitas', 'users.petugas'])->findOrFail($id);
        $tugas->petugas_terdaftar = $tugas->users()->count();
        return response()->json($tugas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kuota_tim' => 'required|integer|min:1|max:10',
            'tanggal_tugas' => 'required|date|after:now',
            'ketua_id' => 'nullable|exists:users,id',
        ]);

        TugasInspeksi::create([
            'fasilitas_id' => $request->fasilitas_id,
            'ketua_id' => $request->ketua_id,
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
            'status' => 'required|in:tersedia,penuh,selesai,dibatalkan',
            'ketua_id' => 'nullable|exists:users,id',
        ]);

        $tugas = TugasInspeksi::findOrFail($id);
        
        // Check if new kuota is less than current joined users
        $currentJoined = $tugas->users()->count();
        if ($request->kuota_tim < $currentJoined) {
            return redirect()->back()->with('error', 'Kuota tidak boleh kurang dari jumlah petugas yang sudah bergabung.');
        }
        
        $tugas->update([
            'fasilitas_id' => $request->fasilitas_id,
            'ketua_id' => $request->ketua_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kuota_tim' => $request->kuota_tim,
            'tanggal_tugas' => $request->tanggal_tugas,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Tugas inspeksi berhasil diperbarui.');
    }

    public function complete($id)
    {
        $tugas = TugasInspeksi::findOrFail($id);
        $tugas->update(['status' => 'selesai']);

        return redirect()->back()->with('success', 'Tugas inspeksi berhasil ditandai selesai.');
    }

    public function setKetua(Request $request, $id)
    {
        $request->validate([
            'ketua_id' => 'required|exists:users,id',
        ]);

        $tugas = TugasInspeksi::with('users')->findOrFail($id);
        
        // Check if the selected user is joined to the task
        if (!$tugas->isUserJoined($request->ketua_id)) {
            return redirect()->back()->with('error', 'Petugas yang dipilih harus bergabung ke tugas terlebih dahulu.');
        }

        // Check if current user is joined to the task
        if (!$tugas->isUserJoined(auth()->id())) {
            return redirect()->back()->with('error', 'Anda harus bergabung ke tugas terlebih dahulu.');
        }

        // Record the vote
        $tugas->users()->updateExistingPivot(auth()->id(), [
            'voted_ketua_id' => $request->ketua_id
        ]);

        // Refresh relations to get the latest pivot data
        $tugas->load('users');

        // Check voting status
        $votingStatus = $tugas->voting_status;

        if ($votingStatus['status'] === 'unanimous') {
            // All voted unanimously, set the ketua
            $tugas->update(['ketua_id' => $votingStatus['ketua_id']]);
            return redirect()->back()->with('success', 'Voting selesai! ' . $votingStatus['ketua_name'] . ' telah ditetapkan sebagai ketua tim.');
        }

        return redirect()->back()->with('success', 'Suara Anda telah direkam. ' . $votingStatus['message']);
    }

    public function resetVoting($id)
    {
        $tugas = TugasInspeksi::with('users')->findOrFail($id);
        
        // Check if current user is joined to the task
        if (!$tugas->isUserJoined(auth()->id())) {
            return redirect()->back()->with('error', 'Anda harus bergabung ke tugas terlebih dahulu.');
        }

        // Reset all votes for this task using DB query
        DB::table('tugas_inspeksi_user')
            ->where('tugas_inspeksi_id', $id)
            ->update(['voted_ketua_id' => null]);

        // Also reset the ketua_id if it was set
        $tugas->update(['ketua_id' => null]);

        return redirect()->back()->with('success', 'Voting telah direset. Silakan voting ulang.');
    }

    public function submitReport(Request $request, $id)
    {
        $request->validate([
            'kondisi_kebersihan' => 'required|in:Baik,Cukup,Buruk',
            'ketersediaan_air' => 'required|boolean',
            'ketersediaan_sabun' => 'required|boolean',
            'bau_tidak_sedap' => 'required|boolean',
            'catatan' => 'required|string|min:10',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $tugas = TugasInspeksi::findOrFail($id);
        
        // Check if user is the ketua
        if (!$tugas->isKetua(auth()->id())) {
            return redirect()->back()->with('error', 'Hanya ketua tim yang dapat mengirim laporan.');
        }
        
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/tugas-inspeksi'), $filename);
            $fotoPath = 'uploads/tugas-inspeksi/' . $filename;
        }
        
        $tugas->update([
            'laporan_catatan' => $request->catatan,
            'laporan_foto' => $fotoPath,
            'kondisi_kebersihan' => $request->kondisi_kebersihan,
            'ketersediaan_air' => $request->ketersediaan_air,
            'ketersediaan_sabun' => $request->ketersediaan_sabun,
            'bau_tidak_sedap' => $request->bau_tidak_sedap,
            'laporan_submitted_at' => now(),
            'status' => 'selesai',
            'approval_status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil dikirim dan menunggu persetujuan admin.');
    }

    public function approveReport($id)
    {
        $tugas = TugasInspeksi::findOrFail($id);
        
        if ($tugas->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'Laporan ini sudah diproses.');
        }
        
        $tugas->update(['approval_status' => 'approved']);

        // Create the actual Inspeksi record
        $inspeksi = new Inspeksi([
            'fasilitas_id' => $tugas->fasilitas_id,
            'petugas_id' => null, // The tugas was handled by a team, but we can assign the ketua
            'tanggal_inspeksi' => $tugas->laporan_submitted_at ?? now(),
            'kondisi_kebersihan' => $tugas->kondisi_kebersihan,
            'ketersediaan_air' => $tugas->ketersediaan_air,
            'ketersediaan_sabun' => $tugas->ketersediaan_sabun,
            'bau_tidak_sedap' => $tugas->bau_tidak_sedap,
            'catatan' => $tugas->laporan_catatan,
            'status_tindak_lanjut' => 'Selesai',
        ]);
        
        // Find the petugas_id for the ketua
        $ketuaUser = User::find($tugas->ketua_id);
        if ($ketuaUser && $ketuaUser->petugas) {
            $inspeksi->petugas_id = $ketuaUser->petugas->id;
        }
        
        $inspeksi->skor = $inspeksi->calculateNormalizedScore();
        $inspeksi->save();

        return redirect()->back()->with('success', 'Laporan berhasil disetujui dan data inspeksi telah dicatat.');
    }

    public function rejectReport($id)
    {
        $tugas = TugasInspeksi::findOrFail($id);
        
        if ($tugas->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'Laporan ini sudah diproses.');
        }
        
        $tugas->update(['approval_status' => 'rejected']);

        return redirect()->back()->with('success', 'Laporan ditolak. Ketua tim dapat mengirim ulang laporan.');
    }

    public function destroy($id)
    {
        $tugas = TugasInspeksi::findOrFail($id);
        $tugas->update(['status' => 'dibatalkan']);

        return redirect()->back()->with('success', 'Tugas inspeksi berhasil dibatalkan.');
    }
}
