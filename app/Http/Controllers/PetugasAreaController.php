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
use App\Models\TugasInspeksi;

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
    }

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
        $validated = $request->validate([
            'fasilitas_id'         => 'required|exists:fasilitas,id',
            'kondisi_kebersihan'   => 'required|in:baik,cukup,buruk,Baik,Cukup,Buruk',
            'ketersediaan_air'     => 'required', // 1/0 atau Ada/Tidak Ada
            'ketersediaan_sabun'   => 'required', // 1/0 atau Ada/Tidak Ada
            'bau_tidak_sedap'      => 'required', // 1/0 atau Ya/Tidak
            'catatan'              => 'nullable|string|max:1000',
            'status_tindak_lanjut' => 'required|in:aman,perlu dibersihkan,perlu perbaikan,Aman,Menunggu,Selesai',
            'foto'                 => 'required|image|mimes:jpeg,png,jpg|max:3072', // Max 3MB
        ]);

        try {
            // Get petugas record
            $userId = Auth::id();
            $petugas = Petugas::where('user_id', $userId)->first();
            
            if (!$petugas) {
                return back()->withInput()->with('error', 'Data petugas tidak ditemukan.');
            }
            
            // Normalisasi nilai agar konsisten dengan format database
            $kondisi = ucfirst(strtolower($validated['kondisi_kebersihan']));
            $air = in_array($validated['ketersediaan_air'], ['1', 'Ada', 'ada']) ? 'Ada' : 'Tidak Ada';
            $sabun = in_array($validated['ketersediaan_sabun'], ['1', 'Ada', 'ada']) ? 'Ada' : 'Tidak Ada';
            $bau = in_array($validated['bau_tidak_sedap'], ['1', 'Ya', 'ya']) ? 'Ya' : 'Tidak';
            $statusTL = ucfirst($validated['status_tindak_lanjut']);
            
            // Map status tindak lanjut dari form petugas ke format admin
            $statusMap = [
                'Aman' => 'Aman',
                'Perlu dibersihkan' => 'Menunggu',
                'Perlu perbaikan' => 'Menunggu',
            ];
            $statusTL = $statusMap[$statusTL] ?? $statusTL;

            // Proses simpan data inspeksi
            $inspeksi = new Inspeksi();
            $inspeksi->petugas_id = $petugas->id;
            $inspeksi->fasilitas_id = $validated['fasilitas_id'];
            $inspeksi->tanggal_inspeksi = now();
            $inspeksi->kondisi_kebersihan = $kondisi;
            $inspeksi->ketersediaan_air = $air;
            $inspeksi->ketersediaan_sabun = $sabun;
            $inspeksi->bau_tidak_sedap = $bau;
            $inspeksi->catatan = $validated['catatan'];
            $inspeksi->status_tindak_lanjut = $statusTL;
            $inspeksi->save();

            // Update status jadwal terkait menjadi 'Selesai'
            $jadwal = Jadwal::where('petugas_id', $petugas->id)
                ->where('fasilitas_id', $validated['fasilitas_id'])
                ->whereIn('status', ['Menunggu', 'Berlangsung'])
                ->first();
            
            if ($jadwal) {
                $jadwal->status = 'Selesai';
                $jadwal->save();

                // Proses Upload Foto (hanya jika jadwal ditemukan agar terkait)
                if ($request->hasFile('foto')) {
                    $fotoPath = $request->file('foto')->store('inspeksi-photos', 'public');
                    
                    \App\Models\InspeksiPhoto::create([
                        'jadwal_id' => $jadwal->id,
                        'petugas_id' => $petugas->id,
                        'foto_path' => $fotoPath,
                        'keterangan' => 'Foto dikirim dari form inspeksi.'
                    ]);
                }
            }

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

    /**
     * 11. Menampilkan daftar tugas inspeksi yang tersedia untuk diambil
     */
    public function tugasInspeksi()
    {
        $userId = Auth::id();
        
        // Get petugas record
        $petugas = Petugas::where('user_id', $userId)->first();
        
        if (!$petugas) {
            return redirect()->route('login')->with('error', 'Data petugas tidak ditemukan.');
        }
        
        // Get available tasks (not full and not completed)
        $tugasTersedia = TugasInspeksi::with(['fasilitas', 'users'])
            ->where('status', '!=', 'selesai')
            ->latest()
            ->get();
        
        // Get tasks joined by current user
        $tugasSaya = TugasInspeksi::with(['fasilitas', 'users'])
            ->whereHas('users', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->where('status', '!=', 'selesai')
            ->latest()
            ->get();

        return view('petugas.tugas-inspeksi', compact('tugasTersedia', 'tugasSaya'));
    }

    /**
     * 12. Join/Ambil tugas inspeksi
     */
    public function joinTugas($tugasId)
    {
        $userId = Auth::id();
        
        $tugas = TugasInspeksi::findOrFail($tugasId);
        
        // Check if task is full
        if ($tugas->isFull()) {
            return back()->with('error', 'Tugas ini sudah penuh.');
        }
        
        // Check if user already joined
        if ($tugas->isUserJoined($userId)) {
            return back()->with('error', 'Anda sudah bergabung dalam tugas ini.');
        }
        
        // Join the task
        $tugas->users()->attach($userId);
        
        // Update status to full if quota reached
        if ($tugas->isFull()) {
            $tugas->update(['status' => 'penuh']);
        }
        
        return back()->with('success', 'Berhasil bergabung dalam tugas inspeksi.');
    }

    /**
     * 13. Leave/Keluar dari tugas inspeksi
     */
    public function leaveTugas($tugasId)
    {
        $userId = Auth::id();
        
        $tugas = TugasInspeksi::findOrFail($tugasId);
        
        // Check if user is in the task
        if (!$tugas->isUserJoined($userId)) {
            return back()->with('error', 'Anda tidak bergabung dalam tugas ini.');
        }
        
        // Leave the task
        $tugas->users()->detach($userId);
        
        // Update status back to tersedia if was full
        if ($tugas->status === 'penuh') {
            $tugas->update(['status' => 'tersedia']);
        }
        
        return back()->with('success', 'Berhasil keluar dari tugas inspeksi.');
    }
}
