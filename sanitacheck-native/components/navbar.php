<!-- components/navbar.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="beranda-user.php">
      <img src="/assets/images/logo.svg" alt="SanitaCheck Logo" width="30" height="30" class="me-2"> SanitaCheck
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'beranda-user.php' || basename($_SERVER['PHP_SELF']) == '' ? 'active' : '' ?>" href="beranda-user.php">Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'fasilitas-user.php' || basename($_SERVER['PHP_SELF']) == 'detail-fasilitas-user.php' ? 'active' : '' ?>" href="fasilitas-user.php">Fasilitas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'lapor-user.php' ? 'active' : '' ?>" href="lapor-user.php">Lapor</a>
        </li>
      </ul>
      <form class="d-flex" action="fasilitas-user.php" method="GET">
        <input class="form-control me-2" type="search" name="search" placeholder="Cari fasilitas..." aria-label="Search">
        <button class="btn btn-outline-light" type="submit">Cari</button>
      </form>
    </div>
  </div>
</nav>
