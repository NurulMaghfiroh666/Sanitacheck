<?php
require_once 'services/api.php';
$stats = getStatistik();

// Fetch 10 aktivitas inspeksi terbaru (mock)
$response_fasilitas = json_decode(fetchApi('/api/fasilitas'), true);
$semua_fasilitas = $response_fasilitas['status'] == 'success' ? $response_fasilitas['data'] : [];

// Ambil 5 top fasilitas untuk ditampilkan di tabel
$top_5 = array_slice($semua_fasilitas, 0, 5);

// Data mock untuk "Fasilitas dengan Masalah Terbanyak" sesuai desain
$masalah_terbanyak = [
    ['nama' => 'Kantin Mami ITSK', 'kali' => 1, 'badge' => 'dc3545'], // Merah
    ['nama' => 'Wastafel Cuci Tangan', 'kali' => 3, 'badge' => 'fd7e14'], // Orange
    ['nama' => 'Lobby Rektorat ITSK Soepraoen Malang', 'kali' => 2, 'badge' => 'ffc107'], // Kuning
    ['nama' => 'LPPM ITSK Soepraoen Malang', 'kali' => 4, 'badge' => 'ffc107'], // Kuning
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanitaCheck - Sistem Monitoring Sanitasi</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif; /* Using Poppins for a more premium modern look */
        }
        
        /* Override Navbar to ensure it has a solid background when scrolling */
        .navbar {
            background-color: #1a5c38 !important; 
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        /* Hide navbar search on home page to match design */
        .navbar form.nav-search-form {
            display: none !important;
        }
        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.7) !important;
            font-weight: 500;
            margin-left: 1rem;
            font-size: 0.95rem;
        }
        .navbar-nav .nav-link.active, .navbar-nav .nav-link:hover {
            color: #fff !important;
            font-weight: 600;
        }
        
        .hero-section {
            background-color: #247547; /* Slightly lighter green to differentiate from navbar */
            min-height: 480px;
            color: white;
            position: relative;
            padding-bottom: 80px;
            padding-top: 80px; /* Space for fixed navbar */
        }
        
        .main-title {
            font-size: 3.8rem;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 1rem;
            text-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .main-subtitle {
            font-weight: 400; 
            font-size: 1.2rem;
            opacity: 0.95;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        .main-background {
            background-image: url('/assets/image/background-1.png');
            background-size: cover;
            background-position: center top;
            background-attachment: fixed;
            min-height: 100vh;
            position: relative;
        }
        
        /* Add a light overlay to the background image to make content readable */
        .main-background::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.75);
            z-index: 0;
        }

        .content-container {
            position: relative;
            z-index: 1;
            margin-top: -80px; /* Pull cards up to overlap */
        }
        
        .stat-card {
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            height: 100%;
            display: flex;
            align-items: center;
            background-color: #fff;
            position: relative;
            overflow: hidden;
            border: none;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }
        
        /* Specific card styles based on design */
        .card-total {
            background-color: #247547; /* Lighter green matching hero section */
            color: white;
        }
        .card-total .icon-box {
            background-color: rgba(255,255,255,0.15);
            color: white;
        }
        .card-inspeksi {
            border-right: 6px solid #0d6efd;
            border-radius: 12px;
        }
        .card-tindak-lanjut {
            border-right: 6px solid #dc3545; /* Red border */
            border-radius: 12px;
        }
        .card-buruk {
            border-right: 6px solid #ffc107; /* Yellow border */
            border-radius: 12px;
        }

        .icon-box {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-right: 20px;
            flex-shrink: 0;
        }
        .icon-inspeksi { background-color: #e7f1ff; color: #0d6efd; }
        .icon-tindak-lanjut { background-color: #fff3cd; color: #ffc107; }
        .icon-buruk { background-color: #f8d7da; color: #dc3545; }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 0;
            line-height: 1.1;
        }
        .stat-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .stat-desc {
            font-size: 12px;
            margin-bottom: 0;
            font-weight: 500;
        }
        .text-dark-green { color: #1a5c38 !important; }

        .section-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.06);
            border: none;
            backdrop-filter: blur(10px);
        }

        .table-custom th {
            background-color: transparent;
            color: #1a5c38;
            font-weight: 700;
            font-size: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eaebec;
        }
        .table-custom td {
            padding: 18px 10px;
            vertical-align: middle;
            border-bottom: 1px solid #f4f5f6;
        }
        
        .badge-custom {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
            display: inline-block;
            text-align: center;
            min-width: 140px;
        }
        .badge-perhatian { background-color: #ffc107; color: #fff; }
        .badge-dibersihkan { background-color: #fd7e14; color: #fff; }
        .badge-buruk { background-color: #dc3545; color: #fff; }
        .badge-aman { background-color: #28a745; color: #fff; }
        
        .masalah-list {
            list-style: none;
            padding-left: 0;
            counter-reset: my-counter;
        }
        .masalah-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #f4f5f6;
            counter-increment: my-counter;
        }
        .masalah-item:last-child {
            border-bottom: none;
        }
        .masalah-item::before {
            content: counter(my-counter);
            font-size: 15px;
            color: #adb5bd;
            margin-right: 18px;
            font-weight: 600;
        }
        .masalah-name {
            flex-grow: 1;
            font-size: 14px;
            color: #495057;
            font-weight: 500;
        }
        
        .btn-lihat-semua {
            border: 1px solid #6c757d;
            color: #6c757d;
            font-size: 12px;
            border-radius: 6px;
            padding: 6px 16px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-lihat-semua:hover {
            background-color: #6c757d;
            color: #fff;
        }
        
        @media (max-width: 768px) {
            .main-title {
                font-size: 2.2rem !important;
            }
            .main-subtitle {
                font-size: 0.95rem !important;
            }
            .hero-section {
                min-height: 320px;
                padding-top: 70px !important;
                padding-bottom: 50px;
            }
            .stat-value {
                font-size: 24px;
            }
            .stat-card {
                padding: 16px;
            }
            .icon-box {
                width: 48px;
                height: 48px;
                font-size: 24px;
                margin-right: 14px;
            }
            .card-header h4 {
                font-size: 1.1rem;
            }
            .table-custom th,
            .table-custom td {
                font-size: 12px !important;
                padding: 10px 6px !important;
            }
            .badge-custom {
                min-width: 100px;
                font-size: 10px;
                padding: 5px 10px;
            }
            .masalah-name {
                font-size: 12px;
            }
            .content-container {
                margin-top: -50px;
            }
        }

        @media (max-width: 576px) {
            .main-title {
                font-size: 1.8rem !important;
            }
            .hero-section {
                min-height: 280px;
                padding-top: 60px !important;
            }
            .stat-value {
                font-size: 20px;
            }
            .stat-title {
                font-size: 12px;
            }
            .stat-desc {
                font-size: 10px;
            }
            .card-header h4 {
                font-size: 0.95rem;
            }
            .btn-lihat-semua {
                font-size: 10px;
                padding: 4px 12px;
            }
        }

        /* === Fasilitas Section Styles === */
        .section-title {
            color: #1a5c38;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .category-card {
            background: #fff;
            border-radius: 12px;
            padding: 14px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
            text-decoration: none;
            color: inherit;
            border: 1px solid rgba(0,0,0,0.05);
            height: 100%;
        }
        .category-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            border-color: rgba(26, 92, 56, 0.2);
        }

        .cat-icon-box {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .cat-title {
            font-size: 14px;
            font-weight: 700;
            color: #1a5c38;
            margin-bottom: 2px;
        }
        .cat-subtitle {
            font-size: 11px;
            color: #6c757d;
            line-height: 1.3;
        }
        .cat-arrow {
            margin-left: auto;
            color: #247547;
            font-size: 1.1rem;
        }

        .icon-toilet { background-color: #1a5c38; color: white; }
        .icon-wastafel { background-color: #3b82f6; color: white; }
        .icon-kantin { background-color: #86efac; color: #166534; }
        .icon-lobby { background-color: #fef08a; color: #854d0e; }
        .icon-lppm { background-color: #fecaca; color: #991b1b; }
        .icon-lainnya { background-color: #e5e7eb; color: #374151; }

        .table-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.06);
            border: none;
            overflow: hidden;
        }
        .table-card-header {
            padding: 24px;
            background-color: transparent;
            border-bottom: none;
        }
        .table-card-title {
            color: #1a5c38;
            font-weight: 700;
            margin-bottom: 0;
            font-size: 1.6rem;
        }

        .nama-fasilitas-link {
            color: #1a5c38;
            font-weight: 600;
            transition: color 0.2s;
        }
        .nama-fasilitas-link:hover {
            color: #0d3520;
            text-decoration: underline !important;
        }

        .category-card.active-filter {
            border-color: #1a5c38;
            box-shadow: 0 0 0 2px rgba(26, 92, 56, 0.3);
            background-color: #f0faf4;
        }

        #fasilitas-section {
            scroll-margin-top: 80px;
        }

        @media (max-width: 768px) {
            .table-card-title { font-size: 1.2rem; }
            .category-card { padding: 10px; }
            .cat-title { font-size: 12px; }
            .cat-subtitle { font-size: 10px; }
            .cat-icon-box { width: 40px; height: 40px; font-size: 18px; margin-right: 8px; }
            .section-title { font-size: 1.2rem; }
            .table-custom th,
            .table-custom td { font-size: 11px !important; padding: 10px 6px !important; }
            .badge-custom { min-width: 100px; font-size: 10px; padding: 4px 8px; }
        }
        @media (max-width: 576px) {
            .table-card-header { padding: 16px; }
            .table-custom th,
            .table-custom td { font-size: 10px !important; padding: 8px 4px !important; white-space: nowrap; }
            .table-responsive { font-size: 10px; }
        }
    </style>
</head>
<body>

<!-- Hero Section with Navbar integrated -->
<div class="hero-section">
    <?php include 'components/navbar.php'; ?>
    
    <div class="container text-center text-white" style="margin-top: 80px;">
        <h1 class="main-title">SanitaCheck</h1>
        <p class="main-subtitle">Sistem Monitoring Sanitasi dan Kebersihan Fasilitas Umum ITSK RS dr. Soepraoen Kesdam V/BRW Malang</p>
    </div>
</div>

<!-- Main content overlapping hero -->
<div class="main-background">
    <div class="container content-container">
        
        <!-- Top 4 Stats Cards -->
        <div class="row g-4 mb-5">
            <!-- Total Fasilitas -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card card-total">
                    <div class="icon-box">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <div class="stat-title">Total Fasilitas</div>
                        <div class="stat-value"><?= count($semua_fasilitas) > 0 ? count($semua_fasilitas) : '25' ?></div>
                        <div class="stat-desc" style="color: rgba(255,255,255,0.8);">Semua fasilitas aktif</div>
                    </div>
                </div>
            </div>
            
            <!-- Inspeksi Hari Ini -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card card-inspeksi">
                    <div class="icon-box icon-inspeksi">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <div>
                        <div class="stat-title text-dark-green">Inspeksi Hari Ini</div>
                        <div class="stat-value text-dark-green"><?= $stats['inspeksi_hari_ini'] > 0 ? $stats['inspeksi_hari_ini'] : '8' ?></div>
                        <div class="stat-desc text-muted">Dari total 25 fasilitas</div>
                    </div>
                </div>
            </div>
            
            <!-- Tindak Lanjut -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card card-tindak-lanjut">
                    <div class="icon-box icon-tindak-lanjut">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <div class="stat-title text-dark-green">Tindak Lanjut</div>
                        <div class="stat-value text-dark-green"><?= $stats['perlu_tindak_lanjut'] > 0 ? $stats['perlu_tindak_lanjut'] : '6' ?></div>
                        <div class="stat-desc text-muted">Perlu dibersihkan</div>
                    </div>
                </div>
            </div>
            
            <!-- Fasilitas Buruk -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card card-buruk">
                    <div class="icon-box icon-buruk">
                        <i class="bi bi-emoji-frown"></i>
                    </div>
                    <div>
                        <div class="stat-title text-dark-green">Fasilitas Buruk</div>
                        <div class="stat-value text-dark-green"><?= $stats['keluhan_diproses'] > 0 ? $stats['keluhan_diproses'] : '2' ?></div>
                        <div class="stat-desc text-muted">Kondisi sanitasi buruk</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Top 5 Fasilitas -->
            <div class="col-lg-8">
                <div class="card section-card h-100">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-4 pb-2 px-4">
                        <h4 class="mb-0 fw-bold text-dark-green d-flex align-items-center">
                            <i class="bi bi-star me-3" style="color: #f1c40f; font-weight: normal; font-size: 1.8rem;"></i> Top 5 Fasilitas (Prioritas)
                        </h4>
                        <a href="#fasilitas-section" class="btn btn-lihat-semua">Lihat Semua</a>
                    </div>
                    <div class="card-body px-4 pb-4 pt-2">
                        <div class="table-responsive">
                            <table class="table table-custom align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Fasilitas</th>
                                        <th style="width: 30%">Lokasi</th>
                                        <th style="width: 30%">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    // Jika data top_5 kosong (misalnya dari API belum ada data), gunakan data mock untuk visualisasi desain
                                    if(empty($top_5)): 
                                        $mock_top = [
                                            ['nama' => 'LPPM ITSK Soepraoen', 'lokasi' => 'Kampus 2', 'status' => 'Perlu Perhatian', 'badge' => 'badge-perhatian'],
                                            ['nama' => 'Lobby Rektorat ITSK Soepraoen Malang', 'lokasi' => 'Kampus 2', 'status' => 'Perlu Perhatian', 'badge' => 'badge-perhatian'],
                                            ['nama' => 'Wastafel Cuci Tangan', 'lokasi' => 'Kampus 4', 'status' => 'Perlu Dibersihkan', 'badge' => 'badge-dibersihkan'],
                                            ['nama' => 'Kantin Mami ITSK Soepraoen Malang', 'lokasi' => 'Kampus 2', 'status' => 'Buruk', 'badge' => 'badge-buruk'],
                                            ['nama' => 'Toilet Lantai 2 FST', 'lokasi' => 'Kampus 3', 'status' => 'Bersih dan Aman', 'badge' => 'badge-aman'],
                                        ];
                                        foreach($mock_top as $fas):
                                    ?>
                                    <tr>
                                        <td class="fw-medium text-secondary" style="font-size: 14px;"><?= htmlspecialchars($fas['nama']) ?></td>
                                        <td class="text-secondary fw-medium" style="font-size: 14px;"><?= htmlspecialchars($fas['lokasi']) ?></td>
                                        <td><span class="badge-custom <?= $fas['badge'] ?>"><?= $fas['status'] ?></span></td>
                                    </tr>
                                    <?php 
                                        endforeach;
                                    else:
                                        foreach($top_5 as $fas): 
                                            $status_lower = strtolower($fas['status'] ?? '');
                                            $badge_class = 'badge-perhatian';
                                            $status_text = $fas['status'] ?? 'Perlu Perhatian';
                                            
                                            if($status_lower == 'bersih' || $status_lower == 'bersih dan aman') {
                                                $badge_class = 'badge-aman';
                                                $status_text = 'Bersih dan Aman';
                                            } else if($status_lower == 'perlu dibersihkan' || $status_lower == 'perbaikan') {
                                                $badge_class = 'badge-dibersihkan';
                                            } else if($status_lower == 'buruk' || $status_lower == 'rusak') {
                                                $badge_class = 'badge-buruk';
                                            }
                                    ?>
                                    <tr>
                                        <td class="fw-medium text-secondary" style="font-size: 14px;"><?= htmlspecialchars($fas['nama'] ?? '') ?></td>
                                        <td class="text-secondary fw-medium" style="font-size: 14px;"><?= htmlspecialchars($fas['lokasi'] ?? '') ?></td>
                                        <td><span class="badge-custom <?= $badge_class ?>"><?= htmlspecialchars($status_text) ?></span></td>
                                    </tr>
                                    <?php 
                                        endforeach; 
                                    endif;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Fasilitas dengan Masalah Terbanyak -->
            <div class="col-lg-4">
                <div class="card section-card h-100">
                    <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
                        <h5 class="mb-0 fw-bold text-dark-green">Fasilitas dengan Masalah Terbanyak</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <ul class="masalah-list m-0 mt-2">
                            <?php foreach($masalah_terbanyak as $masalah): ?>
                            <li class="masalah-item">
                                <span class="masalah-name"><?= htmlspecialchars($masalah['nama']) ?></span>
                                <span class="badge rounded-pill" style="background-color: #<?= $masalah['badge'] ?>; padding: 6px 14px; font-weight: 600; font-size: 12px;"><?= $masalah['kali'] ?> Kali</span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- === Fasilitas Section (Category + Full Table) === -->
        <div id="fasilitas-section" class="mt-3 pt-2 pb-4">
            <hr class="mb-4 opacity-25">
            <h3 class="section-title">Category</h3>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3 mb-5">
                <div class="col">
                    <a href="#fasilitas-section" class="category-card" data-filter="Toilet">
                        <div class="cat-icon-box icon-toilet"><i class="bi bi-gender-ambiguous"></i></div>
                        <div>
                            <div class="cat-title">Toilet</div>
                            <div class="cat-subtitle">Toilet Lantai 1 FST<br>Kondisi sanitasi buruk</div>
                        </div>
                        <i class="bi bi-arrow-right cat-arrow"></i>
                    </a>
                </div>
                <div class="col">
                    <a href="#fasilitas-section" class="category-card" data-filter="Wastafel">
                        <div class="cat-icon-box icon-wastafel"><i class="bi bi-droplet"></i></div>
                        <div>
                            <div class="cat-title">Wastafel Cuci Tangan</div>
                            <div class="cat-subtitle">Kondisi sanitasi<br>dibersihkan</div>
                        </div>
                        <i class="bi bi-arrow-right cat-arrow"></i>
                    </a>
                </div>
                <div class="col">
                    <a href="#fasilitas-section" class="category-card" data-filter="Kantin">
                        <div class="cat-icon-box icon-kantin"><i class="bi bi-shop"></i></div>
                        <div>
                            <div class="cat-title">Kantin</div>
                            <div class="cat-subtitle">Kantin kebersihan<br>dan buruk</div>
                        </div>
                        <i class="bi bi-arrow-right cat-arrow"></i>
                    </a>
                </div>
                <div class="col">
                    <a href="#fasilitas-section" class="category-card" data-filter="Lobby">
                        <div class="cat-icon-box icon-lobby"><i class="bi bi-building"></i></div>
                        <div>
                            <div class="cat-title">Lobby</div>
                            <div class="cat-subtitle">Lobby Rektorat ITSK<br>Soepraoen Malang</div>
                        </div>
                        <i class="bi bi-arrow-right cat-arrow"></i>
                    </a>
                </div>
                <div class="col">
                    <a href="#fasilitas-section" class="category-card" data-filter="LPPM">
                        <div class="cat-icon-box icon-lppm"><i class="bi bi-person-workspace"></i></div>
                        <div>
                            <div class="cat-title">LPPM Mami ITSK</div>
                            <div class="cat-subtitle">Kantin Mami ITSK<br>Kondisi sanitasi buruk</div>
                        </div>
                        <i class="bi bi-arrow-right cat-arrow"></i>
                    </a>
                </div>
                <div class="col">
                    <a href="#fasilitas-section" class="category-card" data-filter="status:buruk">
                        <div class="cat-icon-box icon-toilet"><i class="bi bi-gender-ambiguous"></i></div>
                        <div>
                            <div class="cat-title">Kondisi Buruk</div>
                            <div class="cat-subtitle">Toilet Lantai 1 FST<br>Kondisi sanitasi buruk</div>
                        </div>
                        <i class="bi bi-arrow-right cat-arrow"></i>
                    </a>
                </div>
                <div class="col">
                    <a href="#fasilitas-section" class="category-card" data-filter="Wastafel">
                        <div class="cat-icon-box icon-wastafel"><i class="bi bi-droplet"></i></div>
                        <div>
                            <div class="cat-title">Wastafel Cuci Tangan</div>
                            <div class="cat-subtitle">Kondisi sanitasi<br>dibersihkan</div>
                        </div>
                        <i class="bi bi-arrow-right cat-arrow"></i>
                    </a>
                </div>
                <div class="col">
                    <a href="#fasilitas-section" class="category-card" data-filter="Kantin">
                        <div class="cat-icon-box icon-kantin"><i class="bi bi-shop"></i></div>
                        <div>
                            <div class="cat-title">Kantin</div>
                            <div class="cat-subtitle">Kantin Mami ITSK<br>Soepraoen Malang</div>
                        </div>
                        <i class="bi bi-arrow-right cat-arrow"></i>
                    </a>
                </div>
                <div class="col">
                    <a href="#fasilitas-section" class="category-card" data-filter="status:bersih">
                        <div class="cat-icon-box icon-lobby"><i class="bi bi-building"></i></div>
                        <div>
                            <div class="cat-title">Kondisi Bersih</div>
                            <div class="cat-subtitle">Lobby Rektorat ITSK<br>Soepraoen Malang</div>
                        </div>
                        <i class="bi bi-arrow-right cat-arrow"></i>
                    </a>
                </div>
                <div class="col">
                    <a href="#fasilitas-section" class="category-card" data-filter="all">
                        <div class="cat-icon-box icon-lainnya"><i class="bi bi-three-dots"></i></div>
                        <div>
                            <div class="cat-title">Semua Fasilitas</div>
                            <div class="cat-subtitle">Lihat semua fasilitas<br>yang tersedia</div>
                        </div>
                        <i class="bi bi-arrow-right cat-arrow"></i>
                    </a>
                </div>
            </div>

            <!-- Table Section -->
            <div class="table-card mt-4">
                <div class="table-card-header">
                    <h3 class="table-card-title">Daftar Lengkap Fasilitas</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom align-middle" id="fasilitas-table">
                        <thead>
                            <tr>
                                <th class="ps-4">Nama Fasilitas</th>
                                <th>Lokasi</th>
                                <th class="text-center">Status Kebersihan<br><span style="font-weight: 500; font-size: 11px; opacity: 0.8;">(Bersih/Sedang/Kurang)</span></th>
                                <th class="text-center pe-4">Tanggal Inspeksi<br><span style="font-weight: 500; font-size: 11px; opacity: 0.8;">Terakhir</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $data_table = !empty($semua_fasilitas) ? $semua_fasilitas : [
                                ['id' => 1, 'nama' => 'LPPM ITSK Soepraoen', 'lokasi' => 'Kampus 2', 'status' => 'Perlu Perhatian', 'terakhir_inspeksi' => '2023-08-29'],
                                ['id' => 2, 'nama' => 'Lobby Rektorat ITSK Soepraoen Malang', 'lokasi' => 'Kampus 2', 'status' => 'Perlu Perhatian', 'terakhir_inspeksi' => '2023-08-29'],
                                ['id' => 3, 'nama' => 'Wastafel Cuci Tangan', 'lokasi' => 'Kampus 4', 'status' => 'Perlu Dibersihkan', 'terakhir_inspeksi' => '2023-08-28'],
                                ['id' => 4, 'nama' => 'Kantin Mami ITSK Soepraoen Malang', 'lokasi' => 'Kampus 3', 'status' => 'Buruk', 'terakhir_inspeksi' => '2023-08-28'],
                                ['id' => 5, 'nama' => 'Toilet Lantai 2 FST', 'lokasi' => 'Kampus 3', 'status' => 'Bersih dan Aman', 'terakhir_inspeksi' => '2023-08-28'],
                            ];
                            foreach ($data_table as $item):
                                $sl = strtolower($item['status'] ?? '');
                                $bc = 'badge-perhatian';
                                $st = $item['status'] ?? 'Perlu Perhatian';
                                if ($sl == 'bersih' || $sl == 'bersih dan aman') { $bc = 'badge-aman'; $st = 'Bersih dan Aman'; }
                                elseif ($sl == 'perlu dibersihkan' || $sl == 'perbaikan') { $bc = 'badge-dibersihkan'; }
                                elseif ($sl == 'buruk' || $sl == 'rusak') { $bc = 'badge-buruk'; }
                            ?>
                            <tr>
                                <td class="ps-4 fw-medium"><a href="detail-fasilitas-user.php?id=<?= $item['id'] ?? '1' ?>" class="text-decoration-none nama-fasilitas-link"><?= htmlspecialchars($item['nama'] ?? '') ?></a></td>
                                <td><?= htmlspecialchars($item['lokasi'] ?? '') ?></td>
                                <td class="text-center"><span class="badge-custom <?= $bc ?>"><?= htmlspecialchars($st) ?></span></td>
                                <td class="text-center"><?= date('d/m/Y', strtotime($item['terakhir_inspeksi'] ?? 'now')) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</div>

<?php include 'components/footer.php'; ?>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tutup navbar collapse saat klik link (mobile)
    var navCollapse = document.getElementById('navbarNav');
    if (navCollapse) {
        document.querySelectorAll('.navbar-nav .nav-link').forEach(function(link) {
            link.addEventListener('click', function() {
                var bsCollapse = bootstrap.Collapse.getInstance(navCollapse);
                if (bsCollapse) bsCollapse.hide();
            });
        });
    }

    // === Filter Fasilitas via Category Cards ===
    var filterForm = document.getElementById('filter-form');
    var filterInput = document.getElementById('filter-input');
    var tableBody = document.querySelector('#fasilitas-table tbody');
    var allRows = tableBody ? Array.from(tableBody.querySelectorAll('tr')) : [];
    var activeFilter = null;

    function filterTable(keyword, type) {
        if (!tableBody) return;
        var hasResults = false;
        allRows.forEach(function(row) {
            var match = false;
            if (keyword === 'all') {
                match = true;
            } else if (type === 'status') {
                var badge = row.querySelector('.badge-custom');
                if (badge) {
                    var text = badge.textContent.toLowerCase();
                    match = text.indexOf(keyword) !== -1;
                }
            } else {
                var nameCell = row.querySelector('td:first-child');
                if (nameCell) {
                    match = nameCell.textContent.toLowerCase().indexOf(keyword) !== -1;
                }
            }
            row.style.display = match ? '' : 'none';
            if (match) hasResults = true;
        });

        // Tampilkan pesan jika tidak ada hasil
        var noResult = document.getElementById('filter-no-result');
        if (!hasResults && keyword !== 'all') {
            if (!noResult) {
                noResult = document.createElement('tr');
                noResult.id = 'filter-no-result';
                noResult.innerHTML = '<td colspan="4" class="text-center py-4 text-muted fw-medium">Tidak ada fasilitas yang cocok dengan filter ini.</td>';
                tableBody.appendChild(noResult);
            }
            noResult.style.display = '';
        } else if (noResult) {
            noResult.style.display = 'none';
        }
    }

    document.querySelectorAll('.category-card[data-filter]').forEach(function(card) {
        card.addEventListener('click', function(e) {
            e.preventDefault();
            var filter = this.getAttribute('data-filter');
            var keyword = filter;
            var type = 'nama';

            if (filter.indexOf('status:') === 0) {
                type = 'status';
                keyword = filter.replace('status:', '');
            }

            // Update active state
            document.querySelectorAll('.category-card').forEach(function(c) {
                c.classList.remove('active-filter');
            });
            if (filter !== 'all') {
                this.classList.add('active-filter');
            }

            filterTable(keyword.toLowerCase(), type);
            document.getElementById('fasilitas-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    // Smooth scroll anchor
    document.querySelectorAll('a[href^="#"]:not([data-filter])').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
</script>
</body>
</html>
