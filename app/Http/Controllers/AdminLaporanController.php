<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspeksi;
use App\Models\Fasilitas;
use App\Models\Petugas;
use App\Models\InspeksiPhoto;
use App\Models\Laporan;
use App\Models\TugasInspeksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class AdminLaporanController extends Controller
{
    public function index()
    {
        // Regular inspections
        $totalInspeksi = Inspeksi::count();
        $kondisiBaik = Inspeksi::where('kondisi_kebersihan', 'Baik')->count();
        $kondisiCukup = Inspeksi::where('kondisi_kebersihan', 'Cukup')->count();
        $kondisiBuruk = Inspeksi::where('kondisi_kebersihan', 'Buruk')->count();
        
        // Add approved task reports to the counts
        $approvedTasks = TugasInspeksi::where('approval_status', 'approved')
            ->whereNotNull('kondisi_kebersihan')
            ->get();
        
        $totalInspeksi += $approvedTasks->count();
        $kondisiBaik += $approvedTasks->where('kondisi_kebersihan', 'Baik')->count();
        $kondisiCukup += $approvedTasks->where('kondisi_kebersihan', 'Cukup')->count();
        $kondisiBuruk += $approvedTasks->where('kondisi_kebersihan', 'Buruk')->count();
        
        $baikP = $totalInspeksi > 0 ? round(($kondisiBaik / $totalInspeksi) * 100) : 0;
        $cukupP = $totalInspeksi > 0 ? round(($kondisiCukup / $totalInspeksi) * 100) : 0;
        $burukP = $totalInspeksi > 0 ? round(($kondisiBuruk / $totalInspeksi) * 100) : 0;

        $kpis = [
            ['Total Inspeksi', $totalInspeksi, 'Semua inspeksi', 'success', 'M334.627 16H48v480h424V153.373ZM440 166.627V168H320V48h1.373ZM80 464V48h208v152h152v264Z'],
            ['Kondisi Baik', $kondisiBaik, $baikP . '% dari total inspeksi', 'success', 'M256 48C141.31 48 48 141.31 48 256s93.31 208 208 208 208-93.31 208-208S370.69 48 256 48Zm108.25 138.29-134.4 160a16 16 0 0 1-23.35 1.14l-80-80a16 16 0 0 1 22.62-22.62l67.24 67.24 123.36-146.85a16 16 0 0 1 24.53 21.09Z'],
            ['Kondisi Cukup', $kondisiCukup, $cukupP . '% dari total inspeksi', 'warning', 'M256 48C141.31 48 48 141.31 48 256s93.31 208 208 208 208-93.31 208-208S370.69 48 256 48Zm96 224H160a16 16 0 0 1 0-32h192a16 16 0 0 1 0 32Z'],
            ['Kondisi Buruk', $kondisiBuruk, $burukP . '% dari total inspeksi', 'danger', 'M256 48C141.31 48 48 141.31 48 256s93.31 208 208 208 208-93.31 208-208S370.69 48 256 48m0 384A176 176 0 1 1 432 256 176.176 0 0 1 256 432Z'],
        ];

        // Tren Inspeksi (Chart Data) - 6 bulan terakhir
        $trendLabels = [];
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $trendLabels[] = $month->format('M Y');
            $trendData[] = Inspeksi::whereYear('tanggal_inspeksi', $month->year)
                                    ->whereMonth('tanggal_inspeksi', $month->month)
                                    ->count();
        }

        // Data Fasilitas Rekap
        $fasilitasList = Fasilitas::with(['inspeksis'])->get();
        $rekapFasilitas = [];
        foreach ($fasilitasList as $f) {
            $tIns = $f->inspeksis->count();
            if ($tIns > 0) {
                $b = $f->inspeksis->where('kondisi_kebersihan', 'Baik')->count();
                $c = $f->inspeksis->where('kondisi_kebersihan', 'Cukup')->count();
                $bu = $f->inspeksis->where('kondisi_kebersihan', 'Buruk')->count();
                $skor = $tIns > 0 ? round((($b * 100) + ($c * 60) + ($bu * 20)) / $tIns) : 0;
                
                $statusBadge = 'success';
                $statusText = 'Baik';
                if ($skor < 50) { $statusBadge = 'danger'; $statusText = 'Buruk'; }
                elseif ($skor < 80) { $statusBadge = 'warning'; $statusText = 'Cukup'; }

                $rekapFasilitas[] = [
                    $f->nama_fasilitas,
                    $f->lokasi,
                    $tIns,
                    $b,
                    $c,
                    $bu,
                    $skor,
                    $statusBadge,
                    $statusText
                ];
            }
        }

        // Top 5 Petugas Teraktif (based on total_score)
        $topPetugas = Petugas::with('user')
            ->where('status_aktif', true)
            ->orderBy('total_score', 'desc')
            ->limit(5)
            ->get()
            ->map(function($petugas) {
                $inspeksiCount = Inspeksi::where('petugas_id', $petugas->id)->count();
                return [
                    'nama' => $petugas->nama_petugas,
                    'total_score' => $petugas->total_score,
                    'inspeksi_count' => $inspeksiCount,
                    'avg_score' => $inspeksiCount > 0 ? round($petugas->total_score / $inspeksiCount) : 0
                ];
            });

        // Top 5 Fasilitas Bermasalah (based on lowest average scores)
        $fasilitasBermasalah = Fasilitas::with(['inspeksis' => function($query) {
            $query->latest();
        }])
        ->get()
        ->filter(function($fasilitas) {
            return $fasilitas->inspeksis->count() > 0;
        })
        ->map(function($fasilitas) {
            $inspeksis = $fasilitas->inspeksis;
            $totalScore = 0;
            $problemCount = 0;
            
            foreach ($inspeksis as $inspeksi) {
                $totalScore += $inspeksi->skor ?? 0;
                // Count problems: buruk condition, no air, no soap, bad smell
                if ($inspeksi->kondisi_kebersihan === 'Buruk') $problemCount++;
                if (!$inspeksi->ketersediaan_air) $problemCount++;
                if (!$inspeksi->ketersediaan_sabun) $problemCount++;
                if ($inspeksi->bau_tidak_sedap) $problemCount++;
            }
            
            $avgScore = $inspeksis->count() > 0 ? round($totalScore / $inspeksis->count()) : 0;
            
            return [
                'nama_fasilitas' => $fasilitas->nama_fasilitas,
                'lokasi' => $fasilitas->lokasi,
                'total_inspeksi' => $inspeksis->count(),
                'avg_score' => $avgScore,
                'problem_count' => $problemCount,
                'latest_score' => $inspeksis->first()->skor ?? 0
            ];
        })
        ->sortBy('avg_score')
        ->take(5)
        ->values();

        // Latest inspection reports with photos (regular inspections + approved tasks)
        $regularInspeksi = Inspeksi::with(['fasilitas', 'petugas'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function($inspeksi) {
                // Get photos from jadwal related to this inspection
                $jadwal = \App\Models\Jadwal::where('petugas_id', $inspeksi->petugas_id)
                    ->where('fasilitas_id', $inspeksi->fasilitas_id)
                    ->where('status', 'Selesai')
                    ->first();
                
                $photos = [];
                if ($jadwal) {
                    $photos = \App\Models\InspeksiPhoto::where('jadwal_id', $jadwal->id)
                        ->get()
                        ->map(function($photo) {
                            return [
                                'foto_path' => $photo->foto_path,
                                'keterangan' => $photo->keterangan
                            ];
                        })->toArray();
                }
                
                return [
                    'id' => $inspeksi->id,
                    'fasilitas' => $inspeksi->fasilitas->nama_fasilitas ?? '-',
                    'lokasi' => $inspeksi->fasilitas->lokasi ?? '-',
                    'petugas' => $inspeksi->petugas->nama_petugas ?? '-',
                    'tanggal' => $inspeksi->tanggal_inspeksi->format('d M Y H:i'),
                    'kondisi_kebersihan' => $inspeksi->kondisi_kebersihan,
                    'skor' => $inspeksi->skor ?? 0,
                    'status_tindak_lanjut' => $inspeksi->status_tindak_lanjut,
                    'catatan' => $inspeksi->catatan,
                    'photos' => $photos,
                    'type' => 'inspeksi'
                ];
            });
        
        $approvedTaskReports = TugasInspeksi::with(['fasilitas', 'ketua'])
            ->where('approval_status', 'approved')
            ->whereNotNull('kondisi_kebersihan')
            ->latest('laporan_submitted_at')
            ->take(10)
            ->get()
            ->map(function($tugas) {
                $photos = [];
                if ($tugas->laporan_foto) {
                    $photos[] = [
                        'foto_path' => $tugas->laporan_foto,
                        'keterangan' => 'Foto Laporan Tugas'
                    ];
                }
                
                return [
                    'id' => 'T-' . $tugas->id,
                    'fasilitas' => $tugas->fasilitas->nama_fasilitas ?? '-',
                    'lokasi' => $tugas->fasilitas->lokasi ?? '-',
                    'petugas' => $tugas->ketua ? $tugas->ketua->name : 'Ketua Tim',
                    'tanggal' => $tugas->laporan_submitted_at->format('d M Y H:i'),
                    'kondisi_kebersihan' => $tugas->kondisi_kebersihan,
                    'skor' => 0,
                    'status_tindak_lanjut' => 'Selesai',
                    'catatan' => $tugas->laporan_catatan,
                    'photos' => $photos,
                    'type' => 'tugas'
                ];
            });
        
        // Merge and sort by date
        $latestInspeksi = $regularInspeksi->concat($approvedTaskReports)
            ->sortByDesc(function($item) {
                return strtotime(str_replace(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 
                    ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'], 
                    $item['tanggal']));
            })
            ->take(10)
            ->values();

        // Keluhan list for the sidebar
        $keluhanList = Laporan::with('fasilitas')
            ->latest()
            ->take(10)
            ->get()
            ->map(function($item) {
                return [
                    'id_keluhan' => 'KLP-' . date('Y', strtotime($item->created_at)) . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
                    'fasilitas_nama' => $item->fasilitas ? $item->fasilitas->nama_fasilitas : '-',
                    'lokasi' => $item->fasilitas ? $item->fasilitas->lokasi : '-',
                    'nama_pelapor' => $item->nama_pelapor ?? 'Anonim',
                    'catatan' => $item->catatan,
                    'status' => $item->status,
                    'tanggal' => $item->created_at->format('d M Y H:i'),
                    'foto' => $item->foto ?? null,
                    'resolution' => $item->resolution ?? null
                ];
            });

        return view('admin.laporan', compact(
            'kpis',
            'trendLabels',
            'trendData',
            'baikP', 'cukupP', 'burukP',
            'kondisiBaik', 'kondisiCukup', 'kondisiBuruk',
            'rekapFasilitas',
            'topPetugas',
            'fasilitasBermasalah',
            'latestInspeksi',
            'keluhanList'
        ));
    }

    public function exportKeluhanPdf()
    {
        $laporans = Laporan::with('fasilitas')->latest()->get();
        
        $data = $laporans->map(function ($item) {
            return [
                'ID' => 'KLP-' . date('Y', strtotime($item->created_at)) . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
                'Fasilitas' => $item->fasilitas ? $item->fasilitas->nama_fasilitas : '-',
                'Lokasi' => $item->fasilitas ? $item->fasilitas->lokasi : '-',
                'Pelapor' => $item->nama_pelapor ?? 'Anonim',
                'Catatan' => $item->catatan,
                'Status' => $item->status,
                'Tanggal' => $item->created_at->format('d M Y H:i'),
            ];
        });

        $html = view('admin.laporan-keluhan-pdf', compact('data'))->render();
        
        // For now, return as HTML - in production you'd use dompdf or similar
        return Response::make($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="laporan-keluhan.pdf"');
    }

    public function exportKeluhanExcel()
    {
        $laporans = Laporan::with('fasilitas')->latest()->get();
        
        $headers = ['ID', 'Fasilitas', 'Lokasi', 'Pelapor', 'Catatan', 'Status', 'Tanggal'];
        $rows = [];
        
        foreach ($laporans as $item) {
            $rows[] = [
                'KLP-' . date('Y', strtotime($item->created_at)) . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
                $item->fasilitas ? $item->fasilitas->nama_fasilitas : '-',
                $item->fasilitas ? $item->fasilitas->lokasi : '-',
                $item->nama_pelapor ?? 'Anonim',
                $item->catatan,
                $item->status,
                $item->created_at->format('d M Y H:i'),
            ];
        }

        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM
        $csvContent .= implode(',', $headers) . "\n";
        
        foreach ($rows as $row) {
            $csvRow = [];
            foreach ($row as $cell) {
                $csvRow[] = '"' . str_replace('"', '""', $cell) . '"';
            }
            $csvContent .= implode(',', $csvRow) . "\n";
        }

        return Response::make($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="laporan-keluhan.csv"');
    }

    public function cetakKeluhan()
    {
        $laporans = Laporan::with('fasilitas')->latest()->get();
        
        $data = $laporans->map(function ($item) {
            return [
                'ID' => 'KLP-' . date('Y', strtotime($item->created_at)) . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
                'Fasilitas' => $item->fasilitas ? $item->fasilitas->nama_fasilitas : '-',
                'Lokasi' => $item->fasilitas ? $item->fasilitas->lokasi : '-',
                'Pelapor' => $item->nama_pelapor ?? 'Anonim',
                'Catatan' => $item->catatan,
                'Status' => $item->status,
                'Tanggal' => $item->created_at->format('d M Y H:i'),
            ];
        });

        return view('admin.laporan-keluhan-cetak', compact('data'));
    }
}
