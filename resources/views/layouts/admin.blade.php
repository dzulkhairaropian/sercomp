<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Workshop & Teknisi - SerComp')</title>
  
  <!-- Bootstrap 5.3 CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  
  <!-- Font Plus Jakarta Sans -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- SerComp Theme -->
  <link rel="stylesheet" href="{{ asset('css/sercomp-theme.css') }}">

  <style>
    body {
      background-color: var(--sc-slate-50);
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: var(--sc-slate-900);
      min-height: 100vh;
    }
    .admin-wrapper {
      display: flex;
      min-height: calc(100vh - 35px);
    }
    .admin-sidebar {
      width: 260px;
      background: #ffffff;
      border-right: 1px solid var(--sc-slate-200);
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
      position: sticky;
      top: 0;
      height: 100vh;
      overflow-y: auto;
      z-index: 1020;
    }
    .admin-content {
      flex-grow: 1;
      padding: 1.5rem 2rem;
      background-color: var(--sc-slate-50);
      overflow-x: hidden;
    }
    .sidebar-link {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.7rem 1rem;
      border-radius: 0.625rem;
      color: var(--sc-slate-600);
      font-weight: 600;
      font-size: 0.875rem;
      text-decoration: none;
      transition: all 0.15s ease-in-out;
      margin-bottom: 0.25rem;
    }
    .sidebar-link:hover {
      background-color: var(--sc-slate-100);
      color: var(--sc-slate-900);
    }
    .sidebar-link.active {
      background-color: var(--sc-primary-subtle);
      color: var(--sc-primary);
      font-weight: 700;
    }
    .sidebar-link i {
      font-size: 1.15rem;
    }
    .card-kpi {
      background: #ffffff;
      border: 1px solid var(--sc-slate-200);
      border-radius: 1rem;
      padding: 1.25rem;
      box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04);
      transition: transform 0.15s ease;
    }
    .card-kpi:hover {
      transform: translateY(-2px);
    }
    @media (max-width: 991.98px) {
      .admin-wrapper {
        flex-direction: column;
      }
      .admin-sidebar {
        width: 100%;
        height: auto;
        position: relative;
        border-right: none;
        border-bottom: 1px solid var(--sc-slate-200);
      }
      .admin-content {
        padding: 1rem;
      }
    }
  </style>
  @yield('styles')
</head>
<body>

  <!-- TOP PROTOTYPE DEMO BAR -->
  <div class="sc-demo-bar">
    <div class="container-fluid d-flex flex-wrap align-items-center justify-content-between gap-2">
      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-warning text-dark fw-bold" style="font-size: 10px;">PROTOTIPE AKADEMIK</span>
        <span class="text-white-50 small d-none d-md-inline">Rintisan Bisnis Digital & Proyek SI 2</span>
      </div>
      <div class="d-flex align-items-center gap-1">
        <a href="{{ url('/') }}" class="btn-demo">1. Landing</a>
        <a href="{{ url('/booking') }}" class="btn-demo">2. Form Reservasi</a>
        <a href="{{ url('/user-panel') }}" class="btn-demo">3. Portal Pelanggan</a>
        <a href="{{ url('/teknisi') }}" class="btn-demo active">4. Panel Teknisi</a>
        <a href="{{ url('/invoice') }}" class="btn-demo">5. Faktur Invoice</a>
      </div>
    </div>
  </div>

  <div class="admin-wrapper">
    
    <!-- SIDEBAR TEKNISI / ADMIN -->
    <aside class="admin-sidebar p-3">
      
      <!-- Brand Logo -->
      <div class="d-flex align-items-center gap-2 pb-3 mb-3 border-bottom">
        <div class="brand-box">SC</div>
        <div>
          <span class="fw-bold fs-5 text-dark">Ser<span class="text-primary">Comp</span></span>
          <span class="d-block text-muted text-uppercase" style="font-size: 9px; font-weight: 700; letter-spacing: 0.5px;">Panel Teknisi & Admin</span>
        </div>
      </div>

      <!-- Navigation Menus -->
      <nav class="flex-grow-1">
        <div class="small text-muted text-uppercase fw-bold px-2 mb-2" style="font-size: 10px; letter-spacing: 0.5px;">
          Menu Utama
        </div>

        <a href="{{ url('/teknisi') }}" class="sidebar-link {{ request()->is('teknisi') ? 'active' : '' }}">
          <i class="bi bi-speedometer2"></i>
          <span>Dashboard KPI</span>
        </a>

        <a href="{{ url('/teknisi/servis') }}" class="sidebar-link {{ request()->is('teknisi/servis*') ? 'active' : '' }}">
          <i class="bi bi-laptop"></i>
          <span>Daftar Servis Unit</span>
          <span class="badge bg-primary text-white rounded-pill ms-auto" style="font-size: 10px;">14</span>
        </a>

        <a href="{{ url('/teknisi/meja-kerja') }}" class="sidebar-link {{ request()->is('teknisi/meja-kerja*') ? 'active' : '' }}">
          <i class="bi bi-tools"></i>
          <span>Meja Kerja Diagnosa</span>
        </a>

        <div class="small text-muted text-uppercase fw-bold px-2 mt-3 mb-2" style="font-size: 10px; letter-spacing: 0.5px;">
          Operasional & Inventori
        </div>

        <a href="{{ url('/teknisi/sparepart') }}" class="sidebar-link {{ request()->is('teknisi/sparepart*') ? 'active' : '' }}">
          <i class="bi bi-box-seam"></i>
          <span>Stok Suku Cadang</span>
        </a>

        <a href="{{ url('/invoice') }}" target="_blank" class="sidebar-link">
          <i class="bi bi-receipt"></i>
          <span>Faktur Invoice A4</span>
          <i class="bi bi-box-arrow-up-right ms-auto text-muted" style="font-size: 11px;"></i>
        </a>

        <a href="{{ url('/') }}" target="_blank" class="sidebar-link">
          <i class="bi bi-globe"></i>
          <span>Lihat Website Utama</span>
          <i class="bi bi-box-arrow-up-right ms-auto text-muted" style="font-size: 11px;"></i>
        </a>
      </nav>

      <!-- Bottom Profile & Logout -->
      <div class="pt-3 border-top mt-auto">
        <div class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border mb-2">
          <div class="badge bg-primary text-white p-2 rounded-circle">
            <i class="bi bi-person-fill"></i>
          </div>
          <div class="lh-1 overflow-hidden">
            <strong class="d-block text-dark text-truncate small">Budi Santoso</strong>
            <span class="text-muted" style="font-size: 11px;">Hardware Specialist</span>
          </div>
        </div>

        <div class="d-grid gap-1">
          <a href="{{ url('/user-panel') }}" class="btn btn-sm btn-outline-primary py-1.5 fw-semibold" style="font-size: 12px;">
            <i class="bi bi-person-circle me-1"></i> Mode Pelanggan
          </a>
          <a href="{{ url('/teknisi/login') }}" class="btn btn-sm btn-outline-danger py-1.5 fw-semibold" style="font-size: 12px;">
            <i class="bi bi-box-arrow-right me-1"></i> Keluar Sistem
          </a>
        </div>
      </div>

    </aside>

    <!-- MAIN CONTENT VIEW -->
    <main class="admin-content">
      
      <!-- Top Title Bar -->
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
          <h3 class="fw-bold text-dark mb-1">@yield('page-title', 'Dashboard')</h3>
          <p class="text-muted small mb-0">@yield('page-subtitle', 'Sistem Manajemen Operasional Servis Laptop & PC Desktop.')</p>
        </div>

        <div class="d-flex align-items-center gap-2">
          <span class="badge bg-white text-secondary border px-3 py-2 rounded-3 shadow-sm small">
            <i class="bi bi-calendar3 me-1 text-primary"></i> {{ date('d F Y') }}
          </span>
          @yield('page-actions')
        </div>
      </div>

      <!-- Page Content -->
      @yield('content')

    </main>

  </div>

  <!-- Bootstrap 5.3 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  @yield('scripts')
  @stack('scripts')
</body>
</html>
