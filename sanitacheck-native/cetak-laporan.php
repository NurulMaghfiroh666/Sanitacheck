<?php
require_once 'services/api.php';

$fasilitas = getFasilitas()['data'];
$keluhan = getKeluhan()['data'];
$statistik = getStatistik();

$total_fasilitas = count($fasilitas);
$total_keluhan = count($keluhan);
$diajukan = count(array_filter($keluhan, fn($k) => stripos($k['status'] ?? '', 'diajukan') !== false));
$diproses = count(array_filter($keluhan, fn($k) => stripos($k['status'] ?? '', 'proses') !== false));
$selesai = count(array_filter($keluhan, fn($k) => stripos($k['status'] ?? '', 'selesai') !== false));
$kondisi_bersih = count(array_filter($fasilitas, fn($f) => stripos($f['status'] ?? '', 'bersih') !== false));
$fasilitas_bermasalah = count(array_filter($fasilitas, fn($f) => stripos($f['status'] ?? '', 'buruk') !== false || stripos($f['status'] ?? '', 'perhatian') !== false || stripos($f['status'] ?? '', 'dibersihkan') !== false));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - SanitaCheck</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css?v=12">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            padding-top: 0;
        }
        .print-header {
            background: linear-gradient(135deg, #1a5c38, #0d3b1e);
            color: white;
            padding: 40px 0 30px;
            margin-bottom: 30px;
        }
        .print-header h1 {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 4px;
        }
        .print-header .subtitle {
            opacity: 0.8;
            font-size: 0.9rem;
        }
        .print-header .tgl-cetak {
            opacity: 0.7;
            font-size: 0.8rem;
            margin-top: 8px;
        }
        .stat-print-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .stat-print-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1a5c38;
        }
        .stat-print-card .stat-label {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 4px;
        }
        .section-title-print {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a5c38;
            border-left: 4px solid #1a5c38;
            padding-left: 12px;
            margin: 30px 0 16px;
        }
        .table-print {
            font-size: 0.85rem;
        }
        .table-print th {
            background: #1a5c38;
            color: white;
            font-weight: 600;
        }
        .table-print td {
            vertical-align: middle;
        }
        .footer-print {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 0.8rem;
            color: #6c757d;
            text-align: center;
        }
        .badge-print-aman { background: #d4edda; color: #155724; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-print-perhatian { background: #fff3cd; color: #856404; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-print-buruk { background: #f8d7da; color: #721c24; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-print-diajukan { background: #cce5ff; color: #004085; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-print-proses { background: #fff3cd; color: #856404; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-print-selesai { background: #d4edda; color: #155724; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .print-actions {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            display: flex;
            gap: 10px;
        }
        .print-actions .btn {
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }

        @media print {
            body { background: white; padding: 0; }
            .print-header { padding: 30px 0 20px; }
            .print-actions { display: none !important; }
            .stat-print-card { box-shadow: none; border: 1px solid #dee2e6; }
            .table-print th { background: #1a5c38 !important; color: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge-print-aman, .badge-print-perhatian, .badge-print-buruk,
            .badge-print-diajukan, .badge-print-proses, .badge-print-selesai {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .table-print td, .table-print th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print { display: none !important; }
            .footer-print { page-break-after: avoid; }
            @page { margin: 20mm 15mm; }
        }

        @media (max-width: 768px) {
            .print-header { padding: 24px 0 18px; }
            .print-header h1 { font-size: 1.3rem; }
            .stat-print-card { padding: 14px; }
            .stat-print-card .stat-value { font-size: 1.5rem; }
            .table-print { font-size: 0.75rem; }
            .print-actions { bottom: 16px; right: 16px; }
            .print-actions .btn { padding: 8px 16px; font-size: 0.85rem; }
        }

        .page-break { page-break-before: always; }
    </style>
</head>
<body>

    <!-- HEADER CETAK -->
    <div class="print-header">
        <div class="container">
            <div class="d-flex align-items-center gap-3 mb-2">
                <img src="/assets/image/logo-sanitacheck.png" alt="SanitaCheck" width="40" height="40" onerror="this.style.display='none'">
                <div>
                    <h1>Laporan Rekap Sanitasi</h1>
                    <div class="subtitle">SanitaCheck — Sistem Monitoring Kebersihan Fasilitas</div>
                </div>
            </div>
            <div class="tgl-cetak">
                <i class="bi bi-calendar3 me-1"></i> Dicetak: <?= date('d F Y H:i') ?> WIB
            </div>
        </div>
    </div>

    <div class="container mb-5">

        <!-- RINGKASAN STATISTIK -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-print-card">
                    <div class="stat-value"><?= $total_fasilitas ?></div>
                    <div class="stat-label">Total Fasilitas</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-print-card">
                    <div class="stat-value"><?= $total_keluhan ?></div>
                    <div class="stat-label">Total Laporan</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-print-card">
                    <div class="stat-value"><?= $diproses + $diajukan ?></div>
                    <div class="stat-label">Belum Selesai</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-print-card">
                    <div class="stat-value"><?= $selesai ?></div>
                    <div class="stat-label">Selesai Ditangani</div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-print-card">
                    <div class="stat-value"><?= $statistik['persentase_kebersihan'] ?? 0 ?>%</div>
                    <div class="stat-label">Persentase Kebersihan</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-print-card">
                    <div class="stat-value"><?= $kondisi_bersih ?></div>
                    <div class="stat-label">Fasilitas Bersih</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-print-card">
                    <div class="stat-value"><?= $fasilitas_bermasalah ?></div>
                    <div class="stat-label">Fasilitas Bermasalah</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-print-card">
                    <div class="stat-value"><?= count(array_unique(array_column($fasilitas, 'jenis'))) ?></div>
                    <div class="stat-label">Jenis Fasilitas</div>
                </div>
            </div>
        </div>

        <!-- DAFTAR LAPORAN KELUHAN -->
        <div class="section-title-print">Daftar Laporan Keluhan</div>
        <div class="table-responsive">
            <table class="table table-bordered table-print">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Laporan</th>
                        <th>Fasilitas</th>
                        <th>Pelapor</th>
                        <th>Catatan</th>
                        <th>Status</th>
                        <th>Petugas</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($keluhan)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada laporan.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; ?>
                        <?php foreach ($keluhan as $k): 
                            $status = $k['status'] ?? '';
                            $badge = 'badge-print-diajukan';
                            if (stripos($status, 'proses') !== false) $badge = 'badge-print-proses';
                            if (stripos($status, 'selesai') !== false) $badge = 'badge-print-selesai';
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= htmlspecialchars($k['id_keluhan'] ?? 'KLP-'.$k['id']) ?></strong></td>
                            <td><?= htmlspecialchars($k['fasilitas_nama'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($k['nama_pelapor'] ?? 'Anonim') ?></td>
                            <td><?= htmlspecialchars($k['catatan'] ?? '-') ?></td>
                            <td><span class="<?= $badge ?>"><?= htmlspecialchars($status) ?></span></td>
                            <td><?= htmlspecialchars($k['petugas'] ?? '-') ?></td>
                            <td><?= date('d/m/Y', strtotime($k['created_at'] ?? 'now')) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- DAFTAR FASILITAS -->
        <div class="section-title-print">Daftar Fasilitas</div>
        <div class="table-responsive">
            <table class="table table-bordered table-print">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Fasilitas</th>
                        <th>Lokasi</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Terakhir Inspeksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($fasilitas)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data fasilitas.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; ?>
                        <?php foreach ($fasilitas as $f): 
                            $status = $f['status'] ?? '';
                            $badge = 'badge-print-aman';
                            if (stripos($status, 'buruk') !== false || stripos($status, 'rusak') !== false) $badge = 'badge-print-buruk';
                            elseif (stripos($status, 'perhatian') !== false || stripos($status, 'dibersihkan') !== false) $badge = 'badge-print-perhatian';
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= htmlspecialchars($f['nama'] ?? '') ?></strong></td>
                            <td><?= htmlspecialchars($f['lokasi'] ?? '') ?></td>
                            <td><?= htmlspecialchars($f['jenis'] ?? '') ?></td>
                            <td><span class="<?= $badge ?>"><?= htmlspecialchars($status) ?></span></td>
                            <td><?= date('d/m/Y', strtotime($f['terakhir_inspeksi'] ?? 'now')) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- TANDA TANGAN -->
        <div class="row mt-5 pt-3">
            <div class="col-6 text-center">
                <div style="margin-bottom: 60px;">Mengetahui,</div>
                <div style="font-weight: 600;">Kepala Sanitasi</div>
                <div style="font-size: 0.8rem; color: #6c757d;">( _________________________ )</div>
            </div>
            <div class="col-6 text-center">
                <div style="margin-bottom: 60px;">Dicetak oleh,</div>
                <div style="font-weight: 600;">Petugas</div>
                <div style="font-size: 0.8rem; color: #6c757d;">( _________________________ )</div>
            </div>
        </div>

        <div class="footer-print">
            <i class="bi bi-printer me-1"></i> Dokumen ini dicetak secara otomatis dari sistem SanitaCheck &mdash; <?= date('Y') ?>
        </div>

    </div>

    <!-- TOMBOL CETAK -->
    <div class="print-actions no-print">
        <button class="btn btn-success" onclick="window.print()">
            <i class="bi bi-printer-fill me-2"></i>Cetak / Simpan PDF
        </button>
        <button class="btn btn-outline-secondary" onclick="window.close()">
            <i class="bi bi-x-lg me-2"></i>Tutup
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
