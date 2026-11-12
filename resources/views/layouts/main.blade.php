<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'SerComp - Servis Laptop & PC Desktop Transparan & Bergaransi')</title>
  
  <!-- Bootstrap 5.3 CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  
  <!-- Font Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- SerComp Modern Design System -->
  <link rel="stylesheet" href="{{ asset('css/sercomp-theme.css') }}">

  @yield('styles')
</head>
<body class="d-flex flex-column min-vh-100">

  <!-- NAVBAR UTAMA (MODERN, CLEAN, TRUSTED) -->
  <header class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top shadow-sm py-2.5">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2.5" href="{{ url('/') }}">
        <div class="brand-box">SC</div>
        <div>
          <span class="fw-bold fs-5 text-dark" style="letter-spacing: -0.02em;">Ser<span class="text-primary">Comp</span></span>
          <span class="d-block text-muted text-uppercase" style="font-size: 9px; font-weight: 700; letter-spacing: 0.05em; margin-top: -2px;">Hardware & Service Hub</span>
        </div>
      </a>

      <button class="navbar-toggler border-0 p-1" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navMenu">
        <ul class="navbar-nav align-items-lg-center gap-1 gap-lg-3 mt-2 mt-lg-0 small">
          <li class="nav-item">
            <a class="nav-link fw-semibold {{ request()->is('/') ? 'active text-primary' : 'text-secondary' }}" href="{{ url('/') }}">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-semibold text-secondary" href="{{ url('/#layanan') }}">Layanan & Estimasi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-semibold text-secondary" href="{{ url('/#alur-kerja') }}">Alur Transparan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-semibold text-secondary" href="{{ url('/#garansi') }}">Garansi 90 Hari</a>
          </li>
          <li class="nav-item ms-lg-2">
            <a class="btn btn-outline-primary btn-sm px-3.5 py-2 d-inline-flex align-items-center gap-2" href="{{ url('/user-panel') }}">
              <i data-lucide="search" style="width: 16px; height: 16px;"></i>
              <span>Lacak Tiket</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="btn btn-primary btn-sm px-4 py-2 d-inline-flex align-items-center gap-2 shadow-sm" href="{{ url('/booking') }}">
              <i data-lucide="calendar-plus" style="width: 16px; height: 16px;"></i>
              <span>Reservasi Servis</span>
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
  <footer class="py-5 mt-auto" style="background-color: #090d16; color: #94a3b8; border-top: 1px solid #1e293b;">
    <div class="container">
      <div class="row g-4 justify-content-between">
        <div class="col-lg-4">
          <div class="d-flex align-items-center gap-2 mb-3">
            <div class="brand-box">SC</div>
            <span class="fw-bold fs-5 text-white" style="letter-spacing: -0.02em;">Ser<span class="text-primary">Comp</span></span>
          </div>
          <p class="small text-light text-opacity-75 mb-3 lh-base">
            Pusat perbaikan laptop dan PC desktop dengan estimasi biaya di muka, persetujuan wajib pelanggan, bukti foto suku cadang, dan garansi digital 90 hari.
          </p>
          <div class="d-flex flex-wrap gap-2">
            <span class="badge-status badge-status-info small d-inline-flex align-items-center gap-1.5">
              <i data-lucide="shield-check" style="width: 14px; height: 14px;"></i>
              <span>Estimasi Terkunci</span>
            </span>
            <span class="badge-status badge-status-success small d-inline-flex align-items-center gap-1.5">
              <i data-lucide="badge-check" style="width: 14px; height: 14px;"></i>
              <span>Garansi 90 Hari</span>
            </span>
            <span class="badge-status badge-status-neutral small d-inline-flex align-items-center gap-1.5">
              <i data-lucide="lock" style="width: 14px; height: 14px;"></i>
              <span>Perlindungan Data</span>
            </span>
          </div>
        </div>

        <div class="col-6 col-lg-2">
          <h6 class="text-white fw-bold mb-3 small text-uppercase" style="letter-spacing: 0.05em;">Layanan</h6>
          <ul class="list-unstyled small mb-0 space-y-2">
            <li class="mb-2"><a href="{{ url('/') }}" class="text-decoration-none text-light text-opacity-75 hover-white">Beranda</a></li>
            <li class="mb-2"><a href="{{ url('/#layanan') }}" class="text-decoration-none text-light text-opacity-75 hover-white">Katalog Tarif Servis</a></li>
            <li class="mb-2"><a href="{{ url('/booking') }}" class="text-decoration-none text-light text-opacity-75 hover-white">Formulir Reservasi</a></li>
            <li class="mb-2"><a href="{{ url('/user-panel') }}" class="text-decoration-none text-light text-opacity-75 hover-white">Portal Pelanggan</a></li>
          </ul>
        </div>

        <div class="col-6 col-lg-3">
          <h6 class="text-white fw-bold mb-3 small text-uppercase" style="letter-spacing: 0.05em;">Keamanan & Transparansi</h6>
          <ul class="list-unstyled small mb-0 space-y-2">
            <li class="mb-2">
              <a href="{{ url('/#garansi') }}" class="text-decoration-none text-light text-opacity-75 hover-white d-flex align-items-center gap-1.5">
                <i data-lucide="shield-check" class="text-primary" style="width: 15px; height: 15px;"></i> e-Warranty 90 Hari
              </a>
            </li>
            <li class="mb-2">
              <a href="{{ url('/#alur-kerja') }}" class="text-decoration-none text-light text-opacity-75 hover-white d-flex align-items-center gap-1.5">
                <i data-lucide="git-branch" class="text-primary" style="width: 15px; height: 15px;"></i> 4 Alur Kerja Transparan
              </a>
            </li>
            <li class="mb-2">
              <a href="{{ url('/invoice') }}" class="text-decoration-none text-light text-opacity-75 hover-white d-flex align-items-center gap-1.5">
                <i data-lucide="receipt" class="text-primary" style="width: 15px; height: 15px;"></i> Standar Faktur Resmi
              </a>
            </li>
          </ul>
        </div>

        <div class="col-lg-3">
          <h6 class="text-white fw-bold mb-3 small text-uppercase" style="letter-spacing: 0.05em;">Workshop & Kontak</h6>
          <p class="small text-light text-opacity-75 mb-2 d-flex align-items-center gap-1.5">
            <i data-lucide="map-pin" class="text-primary flex-shrink-0" style="width: 15px; height: 15px;"></i> <span><strong>Workshop:</strong> Service Hub & Hardware Center</span>
          </p>
          <p class="small text-light text-opacity-75 mb-2 d-flex align-items-center gap-1.5">
            <i data-lucide="clock" class="text-primary flex-shrink-0" style="width: 15px; height: 15px;"></i> <span><strong>Jam Kerja:</strong> Senin – Sabtu: 09.00 – 20.00</span>
          </p>
          <p class="small text-light text-opacity-75 mb-0 d-flex align-items-center gap-1.5">
            <i data-lucide="phone-call" class="text-success flex-shrink-0" style="width: 15px; height: 15px;"></i> <span><strong>Hotline:</strong> 0812-9876-5432</span>
          </p>
        </div>
      </div>

      <div class="mt-4 pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small" style="border-top: 1px solid #1e293b;">
        <div class="text-light text-opacity-50">&copy; {{ date('Y') }} SerComp Indonesia. Servis Laptop & PC Terstandarisasi.</div>
        <div class="text-light text-opacity-50">Teknisi Tersertifikasi &bull; Suku Cadang Original &bull; Sistem Terintegrasi</div>
      </div>
    </div>
  </footer>

  <!-- DISCREET FLOATING DEV / TEST SHORTCUT (TIDAK MENGOTORI TAMPILAN KONSUMEN) -->
  <div class="sc-dev-float dropdown">
    <button class="dev-toggle-btn dropdown-toggle d-inline-flex align-items-center gap-1.5" type="button" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="bi bi-compass"></i>
      <span>Pintas Halaman</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-secondary p-2" style="font-size: 13px; min-width: 220px; background: #0f172a;">
      <li class="px-2 py-1 text-white-50 fw-bold text-uppercase" style="font-size: 10px;">Navigasi Sistem:</li>
      <li><a class="dropdown-item text-light rounded py-1.5" href="{{ url('/') }}"><i class="bi bi-house me-2"></i>1. Landing Page</a></li>
      <li><a class="dropdown-item text-light rounded py-1.5" href="{{ url('/booking') }}"><i class="bi bi-calendar2-plus me-2"></i>2. Form Reservasi</a></li>
      <li><a class="dropdown-item text-light rounded py-1.5" href="{{ url('/user-panel') }}"><i class="bi bi-person-badge me-2"></i>3. Portal Pelanggan</a></li>
      <li><a class="dropdown-item text-light rounded py-1.5" href="{{ url('/invoice') }}"><i class="bi bi-receipt me-2"></i>4. Faktur Invoice</a></li>
      <li><hr class="dropdown-divider border-secondary my-1"></li>
      <li class="px-2 py-1 text-white-50 fw-bold text-uppercase" style="font-size: 10px;">Internal Teknisi:</li>
      <li><a class="dropdown-item text-light rounded py-1.5" href="{{ url('/teknisi') }}"><i class="bi bi-tools me-2"></i>Dashboard Teknisi</a></li>
      <li><a class="dropdown-item text-light rounded py-1.5" href="{{ url('/admin') }}"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</a></li>
    </ul>
  </div>

  <!-- Bootstrap 5.3 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      if (window.lucide) {
        lucide.createIcons();
      }
    });
  </script>
  
  @yield('scripts')
  @stack('scripts')
</body>
</html>
