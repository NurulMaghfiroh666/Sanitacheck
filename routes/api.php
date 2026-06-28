<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Fasilitas;
use App\Models\Inspeksi;
use App\Models\Laporan;

// Apply CORS middleware to all API routes
Route::middleware('cors')->group(function () {

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Endpoint untuk statistik
Route::get('/statistik', function () {
    $totalFasilitas = Fasilitas::where('status_aktif', true)->count();
    $totalInspeksiHariIni = Inspeksi::whereDate('tanggal_inspeksi', now()->today())->count();
    
    $fasilitas = Fasilitas::with(['inspeksis' => function($q) {
        $q->latest('tanggal_inspeksi')->limit(1);
    }])->where('status_aktif', true)->get();
    
    $bersihCount = 0;
    $perluPerhatianCount = 0;
    $burukCount = 0;
    
    foreach ($fasilitas as $item) {
        $lastInspeksi = $item->inspeksis->first();
        
        if ($lastInspeksi) {
            $kebersihan = strtolower($lastInspeksi->kondisi_kebersihan);
            if ($kebersihan == 'baik' && $lastInspeksi->ketersediaan_air == 'Ada' && $lastInspeksi->ketersediaan_sabun == 'Ada' && $lastInspeksi->bau_tidak_sedap == 'Tidak') {
                $bersihCount++;
            } elseif ($kebersihan == 'buruk' || $lastInspeksi->ketersediaan_air == 'Tidak Ada' || $lastInspeksi->bau_tidak_sedap == 'Ya') {
                $burukCount++;
            } else {
                $perluPerhatianCount++;
            }
        }
    }
    
    $persentase = $totalFasilitas > 0 ? round(($bersihCount / $totalFasilitas) * 100) : 0;
    
    return response()->json([
        'status' => 'success',
        'data' => [
            'persentase_kebersihan' => $persentase,
            'inspeksi_hari_ini' => $totalInspeksiHariIni,
            'perlu_tindak_lanjut' => $perluPerhatianCount + $burukCount,
            'keluhan_diproses' => 0
        ]
    ]);
});

// API Endpoint untuk daftar fasilitas beserta status terakhir
Route::get('/fasilitas', function () {
    $fasilitas = Fasilitas::with(['inspeksis' => function($q) {
        $q->latest('tanggal_inspeksi')->limit(1);
    }])->where('status_aktif', 1)->get();

    $data = $fasilitas->map(function ($item) {
        $lastInspeksi = $item->inspeksis->first();
        
        $status = 'Belum Diinspeksi';
        $terakhir_inspeksi = date('Y-m-d H:i:s');
        if ($lastInspeksi) {
            $terakhir_inspeksi = $lastInspeksi->tanggal_inspeksi;
            $kebersihan = strtolower($lastInspeksi->kondisi_kebersihan);
            if ($kebersihan == 'baik' && $lastInspeksi->ketersediaan_air == 'Ada' && $lastInspeksi->ketersediaan_sabun == 'Ada' && $lastInspeksi->bau_tidak_sedap == 'Tidak') {
                $status = 'Bersih';
            } elseif ($kebersihan == 'buruk' || $lastInspeksi->ketersediaan_air == 'Tidak Ada' || $lastInspeksi->bau_tidak_sedap == 'Ya') {
                $status = 'Buruk';
            } else {
                $status = 'Perlu Perhatian';
            }
        }

        return [
            'id' => $item->id,
            'nama' => $item->nama_fasilitas,
            'jenis' => $item->jenis_fasilitas,
            'lokasi' => $item->lokasi,
            'status' => $status,
            'terakhir_inspeksi' => $terakhir_inspeksi,
            'petugas' => $item->penanggung_jawab
        ];
    });

    return response()->json(['status' => 'success', 'data' => $data]);
});

// API Endpoint untuk daftar fasilitas berdasarkan status
Route::get('/fasilitas/status/{status}', function ($status) {
    $fasilitas = Fasilitas::with(['inspeksis' => function($q) {
        $q->latest('tanggal_inspeksi')->limit(1);
    }])->where('status_aktif', true)->get();

    $data = $fasilitas->map(function ($item) {
        $lastInspeksi = $item->inspeksis->first();
        
        $itemStatus = 'Belum Diinspeksi';
        $terakhir_inspeksi = date('Y-m-d H:i:s');
        if ($lastInspeksi) {
            $terakhir_inspeksi = $lastInspeksi->tanggal_inspeksi;
            $kebersihan = strtolower($lastInspeksi->kondisi_kebersihan);
            if ($kebersihan == 'baik' && $lastInspeksi->ketersediaan_air == 'Ada' && $lastInspeksi->ketersediaan_sabun == 'Ada' && $lastInspeksi->bau_tidak_sedap == 'Tidak') {
                $itemStatus = 'Bersih';
            } elseif ($kebersihan == 'buruk' || $lastInspeksi->ketersediaan_air == 'Tidak Ada' || $lastInspeksi->bau_tidak_sedap == 'Ya') {
                $itemStatus = 'Buruk';
            } else {
                $itemStatus = 'Perlu Perhatian';
            }
        }

        return [
            'id' => $item->id,
            'nama' => $item->nama_fasilitas,
            'jenis' => $item->jenis_fasilitas,
            'lokasi' => $item->lokasi,
            'status' => $itemStatus,
            'terakhir_inspeksi' => $terakhir_inspeksi,
            'petugas' => $item->penanggung_jawab
        ];
    });

    // Filter berdasarkan status yang diminta
    $filteredData = $data->filter(function($item) use ($status) {
        return strtolower($item['status']) == strtolower(urldecode($status));
    })->values();

    return response()->json(['status' => 'success', 'data' => $filteredData]);
});

// API Endpoint untuk detail fasilitas tunggal
Route::get('/fasilitas/{id}', function ($id) {
    $item = Fasilitas::with(['inspeksis' => function($q) {
        $q->latest('tanggal_inspeksi')->limit(1);
    }])->find($id);

    if (!$item) {
        return response()->json(['status' => 'error', 'message' => 'Not Found'], 404);
    }

    $lastInspeksi = $item->inspeksis->first();
    $status = 'Belum Diinspeksi';
    $terakhir_inspeksi = date('Y-m-d H:i:s');
    
    if ($lastInspeksi) {
        $terakhir_inspeksi = $lastInspeksi->tanggal_inspeksi;
        $kebersihan = strtolower($lastInspeksi->kondisi_kebersihan);
        if ($kebersihan == 'baik' && $lastInspeksi->ketersediaan_air == 'Ada' && $lastInspeksi->ketersediaan_sabun == 'Ada' && $lastInspeksi->bau_tidak_sedap == 'Tidak') {
            $status = 'Bersih';
        } elseif ($kebersihan == 'buruk' || $lastInspeksi->ketersediaan_air == 'Tidak Ada' || $lastInspeksi->bau_tidak_sedap == 'Ya') {
            $status = 'Buruk';
        } else {
            $status = 'Perlu Perhatian';
        }
    }

    return response()->json([
        'status' => 'success',
        'data' => [
            'id' => $item->id,
            'nama' => $item->nama_fasilitas,
            'jenis' => $item->jenis_fasilitas,
            'lokasi' => $item->lokasi,
            'status' => $status,
            'terakhir_inspeksi' => $terakhir_inspeksi,
            'petugas' => $item->penanggung_jawab
        ]
    ]);
});

// API Endpoint untuk riwayat inspeksi
Route::get('/fasilitas/{id}/inspeksi', function ($id) {
    $item = Fasilitas::find($id);
    if (!$item) {
        return response()->json(['status' => 'error', 'message' => 'Not Found'], 404);
    }

    $riwayat = $item->inspeksis()->with('petugas')->latest('tanggal_inspeksi')->get();

    $riwayatList = $riwayat->map(function ($r) {
        $kebersihan = strtolower($r->kondisi_kebersihan);
        $s = 'Perlu Perhatian';
        if ($kebersihan == 'baik' && $r->ketersediaan_air == 'Ada' && $r->ketersediaan_sabun == 'Ada' && $r->bau_tidak_sedap == 'Tidak') $s = 'Bersih';
        elseif ($kebersihan == 'buruk' || $r->ketersediaan_air == 'Tidak Ada' || $r->bau_tidak_sedap == 'Ya') $s = 'Buruk';

        return [
            'tanggal' => date('Y-m-d H:i:s', strtotime($r->tanggal_inspeksi)),
            'petugas' => $r->petugas ? $r->petugas->nama_petugas : 'Anonim',
            'status' => $s,
            'kondisi' => $r->catatan ?: $r->kondisi_kebersihan
        ];
    });

    return response()->json([
        'status' => 'success',
        'data' => $riwayatList
    ]);
});

// API Endpoint untuk keluhan (laporan)
Route::get('/keluhan', function () {
    $laporans = Laporan::with('fasilitas')->latest()->get();
    
    $data = $laporans->map(function ($item) {
        return [
            'id' => $item->id,
            'id_keluhan' => 'KLP-' . date('Y', strtotime($item->created_at)) . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
            'fasilitas_id' => $item->fasilitas_id,
            'fasilitas_nama' => $item->fasilitas ? $item->fasilitas->nama_fasilitas : '-',
            'nama_pelapor' => $item->nama_pelapor ?? 'Anonim',
            'catatan' => $item->catatan,
            'status' => $item->status,
            'created_at' => $item->created_at->format('Y-m-d H:i:s')
        ];
    });

    return response()->json(['status' => 'success', 'data' => $data]);
});

Route::post('/keluhan', function (Request $request) {
    $request->validate([
        'fasilitas_id' => 'required|exists:fasilitas,id',
        'catatan' => 'required|string',
        'nama_pelapor' => 'nullable|string',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $fotoPath = null;
    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/keluhan'), $filename);
        $fotoPath = 'uploads/keluhan/' . $filename;
    }

    $laporan = Laporan::create([
        'fasilitas_id' => $request->fasilitas_id,
        'nama_pelapor' => $request->nama_pelapor ?? 'Anonim',
        'catatan' => $request->catatan,
        'foto' => $fotoPath,
        'status' => 'Menunggu'
    ]);

    return response()->json([
        'status' => 'success', 
        'message' => 'Laporan berhasil dikirim.',
        'data' => $laporan
    ]);
});

}); // Close CORS middleware group
