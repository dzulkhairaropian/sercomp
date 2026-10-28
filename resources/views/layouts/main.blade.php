<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'SerComp - Servis Laptop & PC Pasti, Transparan, Bergaransi')</title>
  
  <!-- Bootstrap 5.3 CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  
  <!-- Font Plus Jakarta Sans -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- SerComp Unified Design System -->
  <link rel="stylesheet" href="{{ asset('css/sercomp-theme.css') }}">

  @yield('styles')
</head>
<body class="d-flex flex-column min-vh-100">

  <!-- TOP PROTOTYPE DEMO BAR (Untuk Sidang / Pengujian Dosen) -->
  <div class="sc-demo-bar">
    <div class="container-fluid d-flex flex-wrap align-items-center justify-content-between gap-2">
      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-warning text-dark fw-bold" style="font-size: 10px;">PROTOTIPE AKADEMIK</span>
        <span class="text-white-50 small d-none d-md-inline">Rintisan Bisnis Digital & Proyek SI 2</span>
      </div>
      <div class="d-flex align-items-center gap-1">
        <span class="text-white-50 me-1 small d-none d-lg-inline">Pintas Halaman:</span>
        <a href="{{ url('/') }}" class="btn-demo {{ request()->is('/') ? 'active' : '' }}">
          1. Landing Page
        </a>
        <a href="{{ url('/booking') }}" class="btn-demo {{ request()->is('booking') ? 'active' : '' }}">
          2. Form Reservasi
        </a>
        <a href="{{ url('/user-panel') }}" class="btn-demo {{ request()->is('user-panel') ? 'active' : '' }}">
          3. Portal Pelanggan
        </a>
        <a href="{{ url('/teknisi') }}" class="btn-demo {{ request()->is('teknisi') ? 'active' : '' }}">
          4. Panel Teknisi
        </a>
        <a href="{{ url('/invoice') }}" class="btn-demo {{ request()->is('invoice') ? 'active' : '' }}">
          5. Faktur Invoice
        </a>
      </div>
    </div>
  </div>

  <!-- NAVBAR UTAMA (NON-REDUNDANT & CLEAN) -->
  <header class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top shadow-sm py-2">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
        <div class="brand-box">SC</div>
        <div>
          <span class="fw-bold fs-5 text-dark">Ser<span class="text-primary">Comp</span></span>
          <span class="d-block text-muted text-uppercase" style="font-size: 9px; font-weight: 700; letter-spacing: 0.5px;">Service Laptop & PC</span>
        </div>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navMenu">
        <ul class="navbar-nav align-items-lg-center gap-1 gap-lg-3 mt-2 mt-lg-0 small">
          <li class="nav-item">
            <a class="nav-link fw-semibold {{ request()->is('/') ? 'active text-primary' : '' }}" href="{{ url('/') }}">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-semibold" href="{{ url('/#layanan') }}">Layanan & Biaya</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-semibold" href="{{ url('/#alur-kerja') }}">Alur Transparan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-semibold" href="{{ url('/#garansi') }}">Garansi 90 Hari</a>
          </li>
          <li class="nav-item ms-lg-2">
            <a class="btn btn-outline-primary btn-sm fw-semibold px-3 py-2 d-flex align-items-center gap-1" href="{{ url('/user-panel') }}">
              <i class="bi bi-search"></i> Lacak Tiket
            </a>
          </li>
          <li class="nav-item">
            <a class="btn btn-primary btn-sm fw-bold px-3 py-2 d-flex align-items-center gap-1 shadow-sm" href="{{ url('/booking') }}">
              <i class="bi bi-calendar-plus"></i> Reservasi Servis
            </a>
          </li>
        </ul>
      </div>
    </div>
  </header>

  <!-- CONTENT -->
  <main class="flex-grow-1">
    @yield('content')
  </main>

  <!-- FOOTER -->
  <footer class="py-5 mt-auto border-top" style="background-color: var(--sc-navy-dark); color: #94a3b8; border-color: #1e293b !important;">
    <div class="container">
      <div class="row g-4 justify-content-between">
        <div class="col-lg-4">
          <div class="d-flex align-items-center gap-2 mb-3">
            <div class="brand-box">SC</div>
            <span class="fw-bold fs-5 text-white">Ser<span class="text-primary">Comp</span></span>
          </div>
          <p class="small text-light text-opacity-75 mb-3">
            Platform servis laptop dan PC transparan berbasis estimasi di muka, approval wajib konsumen, pembayaran digital QRIS, dan jaminan purnajual digital.
          </p>
          <div class="d-flex flex-wrap gap-2">
            <span class="badge bg-primary-subtle border border-primary-subtle">No Hidden Cost</span>
            <span class="badge bg-success-subtle text-success border border-success-subtle">Garansi 90 Hari</span>
            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">Privasi Terjaga</span>
          </div>
        </div>

        <div class="col-6 col-lg-2">
          <h6 class="text-white fw-bold mb-3 small text-uppercase">Navigasi</h6>
          <ul class="list-unstyled small mb-0">
            <li class="mb-2"><a href="{{ url('/') }}" class="text-decoration-none text-light text-opacity-75">Beranda</a></li>
            <li class="mb-2"><a href="{{ url('/#layanan') }}" class="text-decoration-none text-light text-opacity-75">Katalog Servis</a></li>
            <li class="mb-2"><a href="{{ url('/booking') }}" class="text-decoration-none text-light text-opacity-75">Form Reservasi</a></li>
            <li class="mb-2"><a href="{{ url('/user-panel') }}" class="text-decoration-none text-light text-opacity-75">Portal Pelanggan</a></li>
          </ul>
        </div>

        <div class="col-6 col-lg-3">
          <h6 class="text-white fw-bold mb-3 small text-uppercase">Internal & Teknisi</h6>
          <ul class="list-unstyled small mb-0">
            <li class="mb-2">
              <a href="{{ url('/teknisi/login') }}" class="text-decoration-none text-info d-flex align-items-center gap-1">
                <i class="bi bi-tools"></i> Login Workshop Teknisi
              </a>
            </li>
            <li class="mb-2">
              <a href="{{ url('/invoice') }}" class="text-decoration-none text-light text-opacity-75 d-flex align-items-center gap-1">
                <i class="bi bi-receipt"></i> Sampel Faktur Invoice
              </a>
            </li>
            <li class="mb-2">
              <a href="{{ url('/portal') }}" class="text-decoration-none text-light text-opacity-75 d-flex align-items-center gap-1">
                <i class="bi bi-layout-split"></i> Navigasi Prototipe
              </a>
            </li>
          </ul>
        </div>

        <div class="col-lg-3">
          <h6 class="text-white fw-bold mb-3 small text-uppercase">Akademik & Pengembang</h6>
          <p class="small text-light text-opacity-75 mb-1">
            <strong>Proyek:</strong> Rintisan Bisnis Digital & Proyek SI 2
          </p>
          <p class="small text-light text-opacity-75 mb-0">
            <strong>Institusi:</strong> Program Studi S1 Sistem Informasi
          </p>
        </div>
      </div>

      <div class="mt-4 pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small" style="border-top: 1px solid #1e293b;">
        <div class="text-light text-opacity-50">&copy; {{ date('Y') }} SerComp Indonesia. Seluruh hak cipta dilindungi.</div>
        <div class="text-light text-opacity-50">Standardized Laptop & PC Desktop Workshop Environment</div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap 5.3 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  @yield('scripts')
  @stack('scripts')
</body>
</html>
