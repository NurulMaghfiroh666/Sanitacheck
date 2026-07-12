<!-- components/navbar.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top shadow-sm transition-all" id="mainNavbar">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="beranda-user.php">
      <!-- 1. Logo ITSK di paling kiri -->
      <img src="/assets/image/logo-itsk.png" alt="ITSK Logo" width="35" height="35" class="me-2" onerror="this.style.display='none'">
      
      <!-- 2. Logo SanitaCheck -->
      <img src="/assets/image/logo-sanitacheck.png" alt="SanitaCheck Logo" width="30" height="30" class="me-2" onerror="this.src='/assets/images/logo.png'">
      
      <!-- 3. Nama Aplikasi & Slogan Kecil di Bawahnya -->
      <div class="d-flex flex-column">
        <span class="brand-name" style="line-height: 1.2;">SanitaCheck</span>
        <span class="brand-slogan text-white-50 fw-normal" style="font-size: 0.65rem; line-height: 1;">Kampus Sehat Terintegrasi</span>
      </div>
    </a>

    <!-- Tombol Hamburger Menu untuk Mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <!-- Fitur Pencarian (Responsif: Margin otomatis menyesuaikan layar) -->
      <div class="nav-search-wrapper d-flex align-items-center me-lg-3 my-3 my-lg-0 w-100 w-lg-auto">
        <div class="input-group input-group-sm nav-search-group w-100">
          <span class="input-group-text bg-transparent border-end-0 text-white-50" id="search-icon"><i class="bi bi-search"></i></span>
          <input class="form-control border-start-0 ps-0 nav-search-input text-white" type="search" id="navbarSearch" placeholder="Cari fasilitas..." aria-label="Search" autocomplete="off">
        </div>
        <div class="search-results-dropdown" id="searchResults"></div>
      </div>

      <!-- Menu Navigasi -->
      <ul class="navbar-nav me-auto mb-3 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'beranda-user.php' || basename($_SERVER['PHP_SELF']) == '' ? 'active' : '' ?>" href="beranda-user.php">Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'fasilitas-user.php' || basename($_SERVER['PHP_SELF']) == 'detail-fasilitas-user.php' ? 'active' : '' ?>" href="<?= basename($_SERVER['PHP_SELF']) == 'beranda-user.php' || basename($_SERVER['PHP_SELF']) == '' ? '#fasilitas-section' : 'beranda-user.php#fasilitas-section' ?>">Fasilitas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'lapor-user.php' ? 'active' : '' ?>" href="lapor-user.php">Lapor</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'faq-user.php' ? 'active' : '' ?>" href="faq-user.php">FAQ</a>
        </li>
      </ul>

      <!-- Aksi / Tombol (Responsif: Sejajar kiri di mobile, sejajar kanan di desktop) -->
      <div class="d-flex align-items-center gap-2 ms-lg-3 my-2 my-lg-0">
        <button class="btn btn-sm btn-outline-light rounded-circle theme-toggle d-flex align-items-center justify-content-center" style="width: 31px; height: 31px;" id="themeToggle" title="Ganti tema" aria-label="Toggle theme">
          <i class="bi bi-moon-fill theme-icon" id="themeIcon"></i>
        </button>
      </div>
    </div>
  </div>
</nav>