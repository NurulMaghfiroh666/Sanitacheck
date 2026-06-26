<?php
require_once 'services/api.php';

// Semua data dari API / JSON lokal — tidak ada hardcoded mock
$fasilitas = getFasilitas()['data'];
$data_keluhan = getKeluhan()['data'];
$peralatan_raw = getPeralatan()['data'];

// Map nama peralatan ke Bootstrap Icon sebagai fallback saat API tidak mengembalikan field icon
$peralatan_icon_map = [
    'Sapu'               => 'bi-brush-fill',
    'Pel'                => 'bi-droplet-half',
    'Mop'                => 'bi-droplet-half',
    'Vacuum Cleaner'     => 'bi-wind',
    'Cart'               => 'bi-cart',
    'Troli'              => 'bi-cart',
    'Chemical Dispenser' => 'bi-box-seam',
    'Ember'              => 'bi-bucket',
    'Kain Lap'           => 'bi-layers',
    'Sikat'              => 'bi-brush',
    'Lap'                => 'bi-layers',
    'Selang'             => 'bi-circle',
    'Pompa'              => 'bi-arrow-up-circle',
];

// Pastikan setiap item peralatan memiliki icon
$peralatan = [];
foreach ($peralatan_raw as $item) {
    if (empty($item['icon'])) {
        // Cari berdasarkan nama exact
        $item['icon'] = $peralatan_icon_map[$item['nama'] ?? ''] ?? null;
        // Kalau masih kosong, coba partial match
        if (!$item['icon']) {
            foreach ($peralatan_icon_map as $key => $icon) {
                if (stripos($item['nama'] ?? '', $key) !== false) {
                    $item['icon'] = $icon;
                    break;
                }
            }
        }
        // Ultimate fallback
        if (!$item['icon']) $item['icon'] = 'bi-tools';
    }
    $peralatan[] = $item;
}

// Map facility name ke icon/color berdasarkan jenisnya
$fasilitas_lookup = [];
foreach ($fasilitas as $f) {
    $fasilitas_lookup[$f['nama']] = $f;
}
$icon_jenis_map = [
    'Toilet' => ['icon' => 'bi-gender-ambiguous', 'color' => 'success'],
    'Wastafel' => ['icon' => 'bi-droplet', 'color' => 'info'],
    'Kantin' => ['icon' => 'bi-shop', 'color' => 'warning'],
    'Lobby' => ['icon' => 'bi-building', 'color' => 'primary'],
    'Kantor' => ['icon' => 'bi-person-workspace', 'color' => 'secondary'],
    'Lainnya' => ['icon' => 'bi-chat-dots', 'color' => 'dark'],
];

// Aktivitas terakhir & riwayat dari data keluhan
$aktivitas_terakhir = [];
$riwayat = [];
$no = 1;
foreach ($data_keluhan as $k) {
    $status = $k['status'] ?? 'Diajukan';
    $fas_nama = $k['fasilitas_nama'] ?? ($k['nama'] ?? '');
    $fas_data = $fasilitas_lookup[$fas_nama] ?? null;
    $jenis = $fas_data['jenis'] ?? 'Lainnya';
    $icon_data = $icon_jenis_map[$jenis] ?? $icon_jenis_map['Lainnya'];
    $aktivitas_terakhir[] = [
        'no' => $no++,
        'lokasi' => $fas_nama,
        'masalah' => $k['catatan'] ?? '-',
        'tanggal' => date('d M Y', strtotime($k['created_at'] ?? 'now')),
        'status' => $status,
        'icon' => $icon_data['icon'],
        'color' => $icon_data['color'],
    ];
    $riwayat[] = [
        'id' => $k['id_keluhan'] ?? ('ID#' . ($k['id'] ?? 'XXX')),
        'tanggal' => date('d M Y', strtotime($k['created_at'] ?? 'now')),
        'status' => $status,
        'masalah' => $k['catatan'] ?? '-',
        'fasilitas' => $k['fasilitas_nama'] ?? ($k['nama'] ?? '-'),
        'resolution' => $k['resolution'] ?? ($status === 'Selesai' ? 'Selesai Penanganan' : '-'),
    ];
}

$total_laporan = count($riwayat);
$proses_count = count(array_filter($riwayat, fn($r) => stripos($r['status'], 'proses') !== false));
$selesai_count = count(array_filter($riwayat, fn($r) => stripos($r['status'], 'selesai') !== false));

// Proses pengerjaan oleh petugas (hanya status proses/selesai)
$proses_pengerjaan = [];
foreach ($data_keluhan as $k) {
    $status = $k['status'] ?? 'Diajukan';
    if (stripos($status, 'proses') === false && stripos($status, 'selesai') === false) continue;
    $proses_pengerjaan[] = [
        'id' => $k['id_keluhan'] ?? ('ID#' . ($k['id'] ?? 'XXX')),
        'lokasi' => $k['fasilitas_nama'] ?? ($k['nama'] ?? '-'),
        'masalah' => $k['catatan'] ?? '-',
        'status' => $status,
        'tahapan' => [
            'Pengajuan laporan' => true,
            'Diverifikasi petugas' => stripos($status, 'proses') !== false || stripos($status, 'selesai') !== false,
            'Pengerjaan di lokasi' => stripos($status, 'selesai') !== false,
        ],
        'petugas' => $k['petugas'] ?? 'Tim Sanitasi',
        'catatan_petugas' => $k['catatan_petugas'] ?? ($status === 'Selesai' ? 'Pengerjaan telah selesai.' : 'Sedang dalam penanganan.'),
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Keluhan - SanitaCheck</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css?v=12">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .navbar { background-color: #1a5c38 !important; padding-top: 1rem; padding-bottom: 1rem; }
        .navbar form.nav-search-form { display: none !important; }
        .navbar-nav .nav-link { color: rgba(255,255,255,0.7) !important; font-weight: 500; margin-left: 1rem; font-size: 0.95rem; }
        .navbar-nav .nav-link.active, .navbar-nav .nav-link:hover { color: #fff !important; font-weight: 600; }
        
        .hero-section {
            background-color: #247547;
            min-height: 250px;
            color: white;
            position: relative;
            padding-bottom: 40px;
            padding-top: 80px;
        }
        .main-background {
            background-image: url('/assets/image/background-1.png');
            background-size: cover;
            background-position: center top;
            background-attachment: fixed;
            min-height: 100vh;
            position: relative;
            padding-bottom: 80px;
        }
        .main-background::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(248,249,250,0.92);
            z-index: 0;
        }
        .content-container {
            position: relative;
            z-index: 1;
            margin-top: -60px;
        }
        .section-title {
            color: #1a5c38;
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 15px;
        }
        .dashboard-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
            padding: 20px;
            height: 100%;
        }
        .camera-box {
            background: #f0f7f3;
            border: 2px dashed #247547;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 180px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        .camera-box:hover { background: #e6f2eb; transform: translateY(-2px); }
        .camera-box.has-image { border-style: solid; }
        .camera-box img {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }
        .camera-box .camera-placeholder { position: relative; z-index: 1; }
        .camera-box.has-image .camera-placeholder { display: none; }

        .table-custom { margin-bottom: 0; font-size: 13px; }
        .table-custom thead { background-color: #f0ebe1; }
        .table-custom th {
            color: #1a5c38;
            font-weight: 700;
            border-bottom: none;
            padding: 12px 10px;
            background-color: #f0ebe1;
        }
        .table-custom td {
            padding: 12px 10px;
            vertical-align: middle;
            color: #495057;
            border-bottom: 1px solid #f4f5f6;
            font-weight: 500;
        }
        .status-diajukan { color: #856404; font-weight: 600; }
        .status-proses { color: #d39e00; font-weight: 600; }
        .status-selesai { color: #28a745; font-weight: 600; }
        .text-rusak { color: #dc3545; font-weight: 600; }
        .text-baik { color: #495057; }

        .summary-bar {
            background-color: #e9ecef;
            border-radius: 8px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .btn-lapor {
            background-color: #1a5c38;
            color: white;
            font-weight: 600;
            border-radius: 6px;
            padding: 8px 20px;
            border: none;
            transition: all 0.2s;
        }
        .btn-lapor:hover { background-color: #13462a; color: white; }

        .image-preview-box {
            width: 100%;
            height: 200px;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            background-color: #e9ecef;
        }
        .image-preview-box img { width: 100%; height: 100%; object-fit: cover; }

        .form-section { scroll-margin-top: 90px; }
        #success-alert { scroll-margin-top: 90px; }

        .proses-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .proses-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
        .proses-steps { padding-left: 4px; }
        .step-circle {
            width: 22px; height: 22px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; flex-shrink: 0;
        }
        .step-done {
            background-color: #28a745; color: #fff;
        }
        .step-pending {
            background-color: #e9ecef; color: #6c757d;
        }

        @media (max-width: 768px) {
            .hero-section { min-height: 200px; padding-top: 70px; }
            .content-container { margin-top: -40px; }
            .section-title { font-size: 1.2rem; }
            .dashboard-card { padding: 14px; }
            .camera-box { min-height: 140px; }
            .camera-box i { font-size: 2.5rem !important; }
            .camera-box h4 { font-size: 1rem; }
            .summary-bar { flex-direction: column; align-items: stretch; }
            .table-custom { font-size: 11px; }
            .table-custom th,
            .table-custom td { padding: 8px 6px; }
            .image-preview-box { height: 150px; }
            .proses-card { padding: 14px !important; }
            .proses-card h6 { font-size: 14px !important; }
        }
        @media (max-width: 576px) {
            .hero-section { min-height: 180px; padding-top: 60px; }
            .container-fluid { padding-left: 12px !important; padding-right: 12px !important; }
            .dashboard-card { padding: 10px; }
            .proses-card .d-flex.justify-content-between:last-child {
                flex-direction: column; align-items: flex-start !important; gap: 6px;
            }
        }
    </style>
</head>
<body>

<div class="hero-section">
    <?php include 'components/navbar.php'; ?>
    <div class="container text-center text-white" style="margin-top: 40px;">
        <h2 class="fw-bold" style="font-size: 2.5rem; letter-spacing: -0.5px;">Pusat Laporan</h2>
        <p style="opacity: 0.9;">Pantau aktivitas dan buat laporan masalah fasilitas baru.</p>
    </div>
</div>

<div class="main-background">
    <div class="container-fluid px-4 px-md-5 content-container">

        <!-- Success Alert -->
        <div class="alert alert-success alert-dismissible fade shadow-sm border-0" role="alert" id="success-alert" style="display: none;">
            <h4 class="alert-heading fw-bold"><i class="bi bi-check-circle-fill me-2"></i>Laporan Berhasil Dikirim!</h4>
            <p>Terima kasih atas partisipasi Anda dalam menjaga kebersihan lingkungan kampus. Laporan Anda telah kami terima dan akan segera diproses oleh petugas.</p>
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-success btn-sm" onclick="document.getElementById('success-alert').style.display='none';document.getElementById('form-lapor').reset();resetCameraBox();">Kirim Laporan Lagi</button>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        <!-- Formulir Laporan Baru -->
        <h3 class="section-title form-section" id="form-section">Formulir Laporan Baru</h3>
        <form id="form-lapor">
            <div class="row g-4 mb-4">
                <div class="col-lg-5">
                    <div class="dashboard-card p-3">
                        <label class="fw-bold mb-2" style="font-size: 14px; color: #1a5c38;">Foto Lokasi <span class="text-muted fw-normal">(Opsional)</span></label>
                        <div class="camera-box" id="camera-box" onclick="document.getElementById('foto').click();">
                            <div class="camera-placeholder">
                                <i class="bi bi-camera-fill mb-2" style="font-size: 3.5rem; color: #247547;"></i>
                                <h4 class="fw-bold mb-2" style="color: #1a5c38;">Ambil / Upload Foto</h4>
                                <p class="text-muted small mb-0 px-md-3" style="font-weight: 500;">Klik untuk mengambil atau memilih foto kondisi fasilitas.</p>
                            </div>
                        </div>
                        <input type="file" id="foto" accept=".jpg,.jpeg,.png" style="display: none;" onchange="previewFoto(event)">
                        <div class="mt-3">
                            <label class="fw-bold mb-1" style="font-size: 14px; color: #1a5c38;">Pilih Fasilitas <span class="text-danger">*</span></label>
                            <select class="form-select" id="fasilitas" required>
                                <option value="" selected disabled>-- Pilih fasilitas --</option>
                                <?php foreach($fasilitas as $fas): ?>
                                    <option value="<?= $fas['id'] ?>"><?= htmlspecialchars($fas['nama']) ?> (<?= htmlspecialchars($fas['lokasi'] ?? '') ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="dashboard-card">
                        <div class="mb-3">
                            <label for="nama_pelapor" class="form-label fw-bold" style="color: #1a5c38;">Nama Pelapor <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="text" class="form-control" id="nama_pelapor" placeholder="Masukkan nama Anda (atau biarkan kosong untuk anonim)">
                        </div>
                        <div class="mb-3">
                            <label for="catatan" class="form-label fw-bold" style="color: #1a5c38;">Catatan Keluhan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="catatan" rows="4" placeholder="Jelaskan kondisi fasilitas (contoh: Air wastafel mati dan sabun habis, lantai sangat kotor...)" required></textarea>
                            <div class="form-text">Minimal 10 karakter.</div>
                        </div>
                        <div class="mb-3 form-check bg-light p-3 rounded">
                            <input type="checkbox" class="form-check-input" id="persetujuan" required>
                            <label class="form-check-label" for="persetujuan">
                                Saya menyatakan bahwa laporan ini sesuai dengan kondisi sebenarnya di lapangan.
                            </label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg fw-bold" id="btn-submit">
                                <i class="bi bi-send-fill me-2"></i>Kirim Laporan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Dua Tabel: Aktivitas Terakhir & Peralatan Terdaftar -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <h5 class="fw-bold mb-3" style="color: #1a5c38;">Aktivitas Terakhir</h5>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Lokasi</th>
                                    <th>Jenis Masalah</th>
                                    <th>Tanggal Laporan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($aktivitas_terakhir as $item): 
                                    $status_class = 'status-diajukan';
                                    if (stripos($item['status'], 'proses') !== false) $status_class = 'status-proses';
                                    if (stripos($item['status'], 'selesai') !== false) $status_class = 'status-selesai';
                                ?>
                                <tr>
                                    <td class="fw-bold text-center"><?= $item['no'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi <?= $item['icon'] ?> text-<?= $item['color'] ?> fs-5 me-2"></i>
                                            <span style="line-height: 1.2;"><?= htmlspecialchars($item['lokasi']) ?></span>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($item['masalah']) ?></td>
                                    <td><?= $item['tanggal'] ?></td>
                                    <td class="<?= $status_class ?>"><?= $item['status'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <h5 class="fw-bold mb-3" style="color: #1a5c38;">Peralatan Terdaftar</h5>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle">
                            <thead>
                                <tr>
                                    <th>Peralatan</th>
                                    <th>Lokasi Simpan</th>
                                    <th>Kondisi</th>
                                    <th>Stok Sabun/Cairan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($peralatan as $item): 
                                    $kondisi_class = (strtolower($item['kondisi'] ?? '') == 'rusak') ? 'text-rusak' : 'text-baik';
                                    $stok_class = (strtolower($item['stok'] ?? '') == 'menipis') ? 'text-rusak' : 'text-baik';
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi <?= $item['icon'] ?? 'bi-box' ?> text-secondary fs-5 me-2"></i>
                                            <span class="fw-bold"><?= htmlspecialchars($item['nama'] ?? '') ?></span>
                                        </div>
                                    </td>
                                    <td><span style="line-height: 1.2; display: inline-block;"><?= htmlspecialchars($item['lokasi'] ?? '') ?></span></td>
                                    <td class="<?= $kondisi_class ?>"><?= htmlspecialchars($item['kondisi'] ?? '') ?></td>
                                    <td class="<?= $stok_class ?>"><?= htmlspecialchars($item['stok'] ?? '') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proses Pengerjaan oleh Petugas -->
        <h3 class="section-title mt-4">Proses Pengerjaan Petugas</h3>
        <p class="text-muted mb-3" style="font-size: 13px; margin-top: -10px;">Detail tahapan pengerjaan setiap laporan yang sedang atau sudah ditangani.</p>
        <div class="row g-3 mb-5">
            <?php foreach ($proses_pengerjaan as $proses):
                $is_proses = stripos($proses['status'], 'proses') !== false;
                $is_selesai = stripos($proses['status'], 'selesai') !== false;
                $badge_color = $is_proses ? 'warning' : 'success';
                $badge_text = $is_proses ? 'Sedang Diproses' : 'Selesai';
                $border_color = $is_proses ? '#ffc107' : '#28a745';
            ?>
            <div class="col-lg-6">
                <div class="dashboard-card proses-card" style="border-left: 4px solid <?= $border_color ?>; padding: 18px;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-muted" style="font-size: 11px;"><?= htmlspecialchars($proses['id']) ?></span>
                        <span class="badge bg-<?= $badge_color ?> text-dark fw-bold" style="font-size: 11px; padding: 5px 12px;">
                            <?php if ($is_proses): ?><i class="bi bi-arrow-repeat me-1"></i><?php endif; ?>
                            <?php if ($is_selesai): ?><i class="bi bi-check-circle me-1"></i><?php endif; ?>
                            <?= $badge_text ?>
                        </span>
                    </div>
                    <h6 class="fw-bold mb-1" style="color: #1a5c38; font-size: 15px;"><?= htmlspecialchars($proses['lokasi']) ?></h6>
                    <p class="mb-2 text-muted" style="font-size: 12px; line-height: 1.4;"><?= htmlspecialchars($proses['masalah']) ?></p>
                    
                    <!-- Progress Steps -->
                    <div class="proses-steps mb-2">
                        <?php 
                        $step_labels = ['Pengajuan laporan', 'Diverifikasi petugas', 'Pengerjaan di lokasi'];
                        foreach ($step_labels as $i => $label):
                            $done = isset($proses['tahapan'][$label]) && $proses['tahapan'][$label];
                            $step_num = $i + 1;
                        ?>
                        <div class="d-flex align-items-center mb-1">
                            <span class="step-circle <?= $done ? 'step-done' : 'step-pending' ?>">
                                <?php if ($done): ?><i class="bi bi-check"></i><?php else: ?><?= $step_num ?><?php endif; ?>
                            </span>
                            <span class="ms-2 <?= $done ? 'text-dark fw-medium' : 'text-muted' ?>" style="font-size: 12px;"><?= $label ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top" style="border-color: #f0f0f0 !important;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-badge text-secondary me-1"></i>
                            <span style="font-size: 11px; color: #6c757d;"><?= htmlspecialchars($proses['petugas']) ?></span>
                        </div>
                        <small class="text-muted fst-italic" style="font-size: 11px;">
                            <i class="bi bi-chat-dots me-1"></i><?= htmlspecialchars($proses['catatan_petugas']) ?>
                        </small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Ringkasan Laporan Saya -->
        <h3 class="section-title mt-2">Ringkasan Laporan Saya</h3>
        <div class="dashboard-card mb-5">
            <div class="summary-bar">
                <div class="d-flex flex-wrap gap-4 align-items-center">
                    <div style="font-size: 14px;"><span class="fw-bold text-dark">Total Laporan:</span> <?= $total_laporan ?></div>
                    <div style="font-size: 14px;"><span class="fw-bold text-dark">Sedang Diproses:</span> <span class="text-warning fw-bold"><?= $proses_count ?></span></div>
                    <div style="font-size: 14px;"><span class="fw-bold text-dark">Selesai:</span> <span class="text-success fw-bold"><?= $selesai_count ?></span></div>
                </div>
                <a href="#form-section" class="btn-lapor text-decoration-none d-inline-block text-center">Lapor Masalah Baru</a>
            </div>
            <h6 class="fw-bold mb-3" style="color: #343a40;">Riwayat Laporan Detail</h6>
            <div class="table-responsive">
                <table class="table table-custom align-middle">
                    <thead>
                        <tr>
                            <th class="ps-3">No. ID</th>
                            <th>Fasilitas</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Catatan Keluhan</th>
                            <th>Resolution</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($riwayat as $item): 
                            $status_class = 'status-diajukan';
                            if (stripos($item['status'], 'proses') !== false) $status_class = 'status-proses';
                            if (stripos($item['status'], 'selesai') !== false) $status_class = 'status-selesai';
                        ?>
                        <tr>
                            <td class="ps-3 text-muted" style="font-size: 12px;"><?= htmlspecialchars($item['id']) ?></td>
                            <td class="fw-medium"><?= htmlspecialchars($item['fasilitas']) ?></td>
                            <td><?= $item['tanggal'] ?></td>
                            <td class="<?= $status_class ?>"><?= $item['status'] ?></td>
                            <td style="max-width: 200px;"><?= htmlspecialchars($item['masalah']) ?></td>
                            <td><?= htmlspecialchars($item['resolution']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script id="facility-data-store" type="application/json"><?= json_encode($fasilitas, JSON_UNESCAPED_UNICODE) ?></script>
<script>
function previewFoto(event) {
    var file = event.target.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var box = document.getElementById('camera-box');
        var existing = box.querySelector('img');
        if (existing) existing.remove();
        var img = document.createElement('img');
        img.src = e.target.result;
        box.appendChild(img);
        box.classList.add('has-image');
        box.querySelector('.camera-placeholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
}
function resetCameraBox() {
    var box = document.getElementById('camera-box');
    var img = box.querySelector('img');
    if (img) img.remove();
    box.classList.remove('has-image');
    var placeholder = box.querySelector('.camera-placeholder');
    if (placeholder) placeholder.style.display = '';
    document.getElementById('foto').value = '';
}
</script>
<?php include 'components/footer.php'; ?>