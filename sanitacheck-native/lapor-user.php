<?php
require_once 'services/api.php';

// Ambil semua fasilitas untuk dropdown
$response = json_decode(fetchApi('/api/fasilitas'), true);
$fasilitas = $response['status'] == 'success' ? $response['data'] : [];

// Ambil aktivitas terakhir dari API (keluhan)
$response_keluhan = json_decode(fetchApi('/api/keluhan'), true);
$aktivitas_terakhir = [];
$riwayat = [];
if ($response_keluhan && $response_keluhan['status'] == 'success') {
    $data_keluhan = $response_keluhan['data'];
    $no = 1;
    foreach ($data_keluhan as $k) {
        $status = $k['status'] ?? 'Diajukan';
        $status_class = 'status-diajukan';
        if (stripos($status, 'proses') !== false) $status_class = 'status-proses';
        if (stripos($status, 'selesai') !== false) $status_class = 'status-selesai';
        $aktivitas_terakhir[] = [
            'no' => $no++,
            'lokasi' => $k['fasilitas_nama'] ?? ($k['nama'] ?? '-'),
            'masalah' => $k['catatan'] ?? '-',
            'tanggal' => date('d M Y', strtotime($k['created_at'] ?? $k['tanggal'] ?? 'now')),
            'status' => $status,
            'icon' => 'bi-building',
            'color' => 'success'
        ];
        $riwayat[] = [
            'id' => $k['id_keluhan'] ?? ('ID: ' . ($k['id'] ?? 'XXX')),
            'tanggal' => date('d M Y', strtotime($k['created_at'] ?? $k['tanggal'] ?? 'now')),
            'status' => $status,
            'masalah' => $k['fasilitas_nama'] ?? ($k['nama'] ?? '-'),
            'resolution' => $k['resolution'] ?? ($status === 'Selesai' ? 'Selesai Penanganan' : 'Diajukan Berinisiatif')
        ];
    }
}

// Fallback mock jika API kosong
if (empty($aktivitas_terakhir)) {
    $aktivitas_terakhir = [
        ['no' => 1, 'lokasi' => 'Lobby Rektorat ITSK Soepraoen', 'masalah' => '-', 'tanggal' => date('d M Y'), 'status' => 'Diajukan', 'icon' => 'bi-building', 'color' => 'success'],
        ['no' => 2, 'lokasi' => 'Berantakan Sabun Habis', 'masalah' => '-', 'tanggal' => date('d M Y'), 'status' => 'Sedang Diproses', 'icon' => 'bi-droplet-half', 'color' => 'danger'],
        ['no' => 3, 'lokasi' => 'Wastafel Cuci Malang', 'masalah' => '-', 'tanggal' => date('d M Y'), 'status' => 'Sedang Diproses', 'icon' => 'bi-trash', 'color' => 'secondary'],
        ['no' => 4, 'lokasi' => 'Peralatan Rusak Malang', 'masalah' => '-', 'tanggal' => date('d M Y'), 'status' => 'Selesai', 'icon' => 'bi-tools', 'color' => 'primary'],
        ['no' => 5, 'lokasi' => 'Toilet Lantai 2 FST Malang', 'masalah' => '-', 'tanggal' => date('d M Y'), 'status' => 'Selesai', 'icon' => 'bi-gender-ambiguous', 'color' => 'success'],
    ];
}
if (empty($riwayat)) {
    $riwayat = [
        ['id' => 'ID: 1025373000000303', 'tanggal' => date('d M Y'), 'status' => 'Diajukan', 'masalah' => 'Lobby Rektorat ITSK Soepraoen', 'resolution' => 'Diajukan Berinisiatif'],
        ['id' => 'ID: 1025373000000260', 'tanggal' => date('d M Y'), 'status' => 'Sedang Diproses', 'masalah' => 'Berantakan Sabun Habis', 'resolution' => 'Selesai Penanganan'],
        ['id' => 'ID: 1025373000000267', 'tanggal' => date('d M Y'), 'status' => 'Diajukan', 'masalah' => 'Berantakan Sabun Malang', 'resolution' => 'Berantakan Sabun Kamar'],
        ['id' => 'ID: 1025373000000267', 'tanggal' => date('d M Y'), 'status' => 'Selesai', 'masalah' => 'Wastafel Lantai 2 FST Malang', 'resolution' => 'Selesai Penanganan'],
    ];
}

// Ambil peralatan dari API
$response_peralatan = json_decode(fetchApi('/api/peralatan'), true);
$peralatan = ($response_peralatan && $response_peralatan['status'] == 'success') ? $response_peralatan['data'] : [];
if (empty($peralatan)) {
    $peralatan = [
        ['nama' => 'Vacuum', 'lokasi' => 'Lobby Rektorat', 'kondisi' => 'Baik', 'stok' => 'Tersedia', 'icon' => 'bi-wind'],
        ['nama' => 'Mop', 'lokasi' => 'Lobby Rektorat ITSK Soepraoen', 'kondisi' => 'Rusak', 'stok' => 'Tersedia', 'icon' => 'bi-magic'],
        ['nama' => 'Cart', 'lokasi' => 'Lobby Rektorat Malang', 'kondisi' => 'Rusak', 'stok' => 'Menipis', 'icon' => 'bi-cart'],
        ['nama' => 'Chemical Dispenser', 'lokasi' => 'Lobby Rektorat', 'kondisi' => 'Baik', 'stok' => 'Tersedia', 'icon' => 'bi-box'],
        ['nama' => 'Chemical Dispenser', 'lokasi' => 'Lobby Rektorat', 'kondisi' => 'Rusak', 'stok' => 'Tersedia', 'icon' => 'bi-box-seam'],
    ];
}

// Hitung summary
$total_laporan = count($riwayat);
$proses_count = 0;
$selesai_count = 0;
foreach ($riwayat as $r) {
    if (stripos($r['status'], 'proses') !== false) $proses_count++;
    if (stripos($r['status'], 'selesai') !== false) $selesai_count++;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Keluhan - SanitaCheck</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
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
        }
        @media (max-width: 576px) {
            .hero-section { min-height: 180px; padding-top: 60px; }
            .container-fluid { padding-left: 12px !important; padding-right: 12px !important; }
            .dashboard-card { padding: 10px; }
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
                                <?php if(empty($fasilitas)): ?>
                                    <option value="1">Lobby Rektorat ITSK Soepraoen Malang</option>
                                    <option value="2">Toilet Lantai 2 FST</option>
                                    <option value="3">Kantin Mami ITSK Soepraoen Malang</option>
                                <?php endif; ?>
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
                            <th>Tanggal Laporan</th>
                            <th>Status</th>
                            <th>Jenis Masalah</th>
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
                            <td><?= $item['tanggal'] ?></td>
                            <td class="<?= $status_class ?>"><?= $item['status'] ?></td>
                            <td class="fw-medium"><?= htmlspecialchars($item['masalah']) ?></td>
                            <td><?= htmlspecialchars($item['resolution']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/app.js"></script>
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
</body>
</html>