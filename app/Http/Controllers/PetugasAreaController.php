<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Inspeksi;
use App\Models\Fasilitas;
use App\Models\Jadwal;
use App\Models\InspeksiPhoto;
use App\Models\Petugas;

class PetugasAreaController extends Controller
{
    /**
     * 1. Menampilkan ringkasan data milik petugas yang sedang login
     */
    public function dashboard()
    {
        $userId = Auth::id();
        
        // Get petugas record
        $petugas = Petugas::where('user_id', $userId)->first();
        
        if (!$petugas) {
            return redirect()->route('login')->with('error', 'Data petugas tidak ditemukan.');
        }
        
        // Mengambil jumlah jadwal aktif (sedang berlangsung hari ini)
        $jadwalHariIni = Jadwal::where('petugas_id', $petugas->id)
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->count();

        // Total inspeksi yang sudah diselesaikan oleh petugas ini
        $inspeksiSelesai = Inspeksi::where('petugas_id', $petugas->id)->count();

        // Jumlah jadwal yang sudah selesai
        $jadwalSelesai = Jadwal::where('petugas_id', $petugas->id)
            ->where('status', 'Selesai')
            ->count();

        // Jumlah jadwal menunggu/tertunda
        $jadwalMenunggu = Jadwal::where('petugas_id', $petugas->id)
            ->whereIn('status', ['Menunggu', 'Berlangsung'])
            ->count();

        // Jadwal terbaru (max 5, exclude yang dibatalkan)
        $jadwalTerbaru = Jadwal::with('fasilitas')
            ->where('petugas_id', $petugas->id)
            ->where('status', '!=', 'Dibatalkan')
            ->orderBy('tanggal_mulai', 'desc')
            ->limit(5)
            ->get();

        return view('petugas.dashboard', compact(
            'petugas', 'jadwalHariIni', 'inspeksiSelesai', 
            'jadwalSelesai', 'jadwalMenunggu', 'jadwalTerbaru'
        ));

    /**
     * 2. Menampilkan daftar jadwal inspeksi yang ditugaskan kepada petugas
     */
    public function jadwal()
    {
        $userId = Auth::id();
        
        // Get petugas record
        $petugas = Petugas::where('user_id', $userId)->first();
        
        if (!$petugas) {
            return redirect()->route('login')->with('error', 'Data petugas tidak ditemukan.');
        }
        
        // Get assigned schedules with photos (exclude Dibatalkan, show active first)
        $jadwalDitugaskan = Jadwal::with(['fasilitas', 'photos'])
            ->where('petugas_id', $petugas->id)
            ->where('status', '!=', 'Dibatalkan')
            ->orderByRaw("CASE WHEN status = 'Menunggu' THEN 0 WHEN status = 'Berlangsung' THEN 1 WHEN status = 'Selesai' THEN 2 ELSE 3 END")
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // Fasilitas list for creating inspeksi directly
        $fasilitasList = Fasilitas::where('status_aktif', true)->get();

        return view('petugas.jadwal', compact('jadwalDitugaskan', 'fasilitasList'));
    }

    /**
     * 3. Menampilkan halaman form input untuk fasilitas yang dipilih
     */
    public function create($fasilitas_id)
    {
        // Menggunakan Eloquent ORM: akan otomatis throw 404 jika fasilitas tidak ada
        $fasilitas = Fasilitas::findOrFail($fasilitas_id);
        
        return view('petugas.inspeksi.create', compact('fasilitas'));
    }

    /**
     * 4. Memproses simpan data ke tabel 'inspeksi_sanitasi'
     */
    public function store(Request $request)
    {
        // Validasi input yang ketat dan aman dari injeksi
        $validated = $request->validate([
            'fasilitas_id'         => 'required|exists:fasilitas,id',
            'kondisi_kebersihan'   => 'required|in:baik,cukup,buruk',
            'ketersediaan_air'     => 'required|boolean', // 1/0
            'ketersediaan_sabun'   => 'required|boolean', // 1/0
            'bau_tidak_sedap'      => 'required|boolean', // 1/0
            'catatan'              => 'nullable|string|max:1000',
            'status_tindak_lanjut' => 'required|in:aman,perlu dibersihkan,perlu perbaikan',
        ]);

        try {
            // Get petugas record
            $userId = Auth::id();
            $petugas = Petugas::where('user_id', $userId)->first();
            
            if (!$petugas) {
                return back()->withInput()->with('error', 'Data petugas tidak ditemukan.');
            }
            
            // Proses simpan data (Aman dari SQL Injection karena menggunakan Eloquent)
            $inspeksi = new Inspeksi();
            $inspeksi->petugas_id = $petugas->id; // Using petugas table ID
            $inspeksi->fasilitas_id = $validated['fasilitas_id'];
            $inspeksi->kondisi_kebersihan = $validated['kondisi_kebersihan'];
            $inspeksi->ketersediaan_air = $validated['ketersediaan_air'];
            $inspeksi->ketersediaan_sabun = $validated['ketersediaan_sabun'];
            $inspeksi->bau_tidak_sedap = $validated['bau_tidak_sedap'];
            $inspeksi->catatan = $validated['catatan'];
            $inspeksi->status_tindak_lanjut = $validated['status_tindak_lanjut'];
            $inspeksi->save();

            // Flash message sukses
            return redirect()->route('petugas.riwayat')->with('success', 'Data inspeksi berhasil disimpan. Mantap, kerja bagus!');
        } catch (\Exception $e) {
            // Flash message error
            return back()->withInput()->with('error', 'Gagal menyimpan data inspeksi: ' . $e->getMessage());
        }
    }

    /**
     * 5. Menampilkan riwayat semua inspeksi yang pernah dikirim oleh petugas
     */
    public function riwayat()
    {
        $userId = Auth::id();
        
        // Get petugas record
        $petugas = Petugas::where('user_id', $userId)->first();
        
        if (!$petugas) {
            return redirect()->route('login')->with('error', 'Data petugas tidak ditemukan.');
        }
        
        // Urut dari yang paling baru, dengan paginasi
        $riwayatInspeksi = Inspeksi::with('fasilitas')
            ->where('petugas_id', $petugas->id)
            ->latest()
            ->paginate(10);

        // Statistik ringkasan
        $totalInspeksi = Inspeksi::where('petugas_id', $petugas->id)->count();
        $inspeksiBulanIni = Inspeksi::where('petugas_id', $petugas->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('petugas.inspeksi.riwayat', compact('riwayatInspeksi', 'totalInspeksi', 'inspeksiBulanIni'));
    }

    /**
     * 6. Menampilkan data diri profil petugas
     */
    public function profil()
    {
        $user = Auth::user();
        
        // Ambil data extended dari tabel petugas
        $petugasData = Petugas::where('user_id', $user->id)->first();
        
        // Statistik petugas
        $totalInspeksi = 0;
        $totalJadwal = 0;
        $jadwalSelesai = 0;
        
        if ($petugasData) {
            $totalInspeksi = Inspeksi::where('petugas_id', $petugasData->id)->count();
            $totalJadwal = Jadwal::where('petugas_id', $petugasData->id)->count();
            $jadwalSelesai = Jadwal::where('petugas_id', $petugasData->id)->where('status', 'Selesai')->count();
        }

        return view('petugas.profil', compact('user', 'petugasData', 'totalInspeksi', 'totalJadwal', 'jadwalSelesai'));
    }

    /**
     * 7. Memproses perubahan password petugas
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:8|confirmed', // otomatis butuh input password_baru_confirmation
        ]);

        $user = Auth::user();

        // Pengecekan password lama
        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai, bro!');
        }

        // Update password baru yang sudah di-hash
        $user->update([
            'password' => Hash::make($request->password_baru)
        ]);

        return back()->with('success', 'Password berhasil diperbarui dengan aman.');
    }

    /**
     * 8. Menampilkan halaman upload foto untuk jadwal tertentu
     */
    public function uploadFoto($jadwal_id)
    {
        $petugasId = Auth::id();
        $petugas = Petugas::where('user_id', $petugasId)->first();
        
        if (!$petugas) {
            return back()->with('error', 'Data petugas tidak ditemukan.');
        }

        $jadwal = Jadwal::with(['fasilitas', 'photos'])
            ->where('id', $jadwal_id)
            ->where('petugas_id', $petugas->id)
            ->firstOrFail();

        return view('petugas.upload-foto', compact('jadwal'));
    }

    /**
     * 9. Memproses upload foto inspeksi
     */
    public function storeFoto(Request $request, $jadwal_id)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'keterangan' => 'nullable|string|max:500'
        ]);

        $petugasId = Auth::id();
        $petugas = Petugas::where('user_id', $petugasId)->first();
        
        if (!$petugas) {
            return back()->with('error', 'Data petugas tidak ditemukan.');
        }

        $jadwal = Jadwal::where('id', $jadwal_id)
            ->where('petugas_id', $petugas->id)
            ->firstOrFail();

        // Upload foto
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('inspeksi-photos', 'public');
            
            InspeksiPhoto::create([
                'jadwal_id' => $jadwal->id,
                'petugas_id' => $petugas->id,
                'foto_path' => $fotoPath,
                'keterangan' => $request->keterangan
            ]);

            // Update jadwal status to Berlangsung if it was Menunggu
            if ($jadwal->status == 'Menunggu') {
                $jadwal->status = 'Berlangsung';
                $jadwal->save();
            }

            return redirect()->route('petugas.jadwal')->with('success', 'Foto berhasil diunggah.');
        }

        return back()->with('error', 'Gagal mengunggah foto.');
    }

    /**
     * 10. Menghapus foto inspeksi
     */
    public function deleteFoto($foto_id)
    {
        $petugasId = Auth::id();
        $petugas = Petugas::where('user_id', $petugasId)->first();
        
        if (!$petugas) {
            return back()->with('error', 'Data petugas tidak ditemukan.');
        }

        $foto = InspeksiPhoto::where('id', $foto_id)
            ->where('petugas_id', $petugas->id)
            ->firstOrFail();

        // Delete file from storage
        if (Storage::disk('public')->exists($foto->foto_path)) {
            Storage::disk('public')->delete($foto->foto_path);
        }

        $jadwal_id = $foto->jadwal_id;
        $foto->delete();

        return redirect()->route('petugas.upload-foto', $jadwal_id)->with('success', 'Foto berhasil dihapus.');
    }
}
