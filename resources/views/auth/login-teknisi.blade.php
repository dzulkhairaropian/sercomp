<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Staf & Teknisi Workshop - SerComp</title>
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
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .auth-card {
      background: #ffffff;
      border: 1px solid var(--sc-slate-200);
      border-radius: 1.25rem;
      box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05);
      width: 100%;
      max-width: 440px;
    }
  </style>
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
        <a href="{{ url('/teknisi') }}" class="btn-demo">4. Panel Teknisi</a>
        <a href="{{ url('/invoice') }}" class="btn-demo">5. Faktur Invoice</a>
      </div>
    </div>
  </div>

  <!-- AUTH MAIN CONTAINER -->
  <div class="flex-grow-1 d-flex align-items-center justify-content-center p-4">
    <div class="auth-card p-4 p-sm-5 my-3">
      
      <!-- Brand Logo & Header -->
      <div class="text-center mb-4">
        <a href="{{ url('/') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none mb-3">
          <div class="brand-box">SC</div>
          <span class="fw-bold fs-4 text-dark">Ser<span class="text-primary">Comp</span></span>
        </a>
        <div class="d-inline-flex align-items-center gap-1 badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill mb-2">
          <i class="bi bi-shield-lock-fill"></i>
          <span>Internal Workshop Staf</span>
        </div>
        <h4 class="fw-bold text-dark mb-1">Login Teknisi & Admin</h4>
        <p class="text-muted small mb-0">
          Autentikasi staf teknisi untuk input diagnosa, estimasi biaya, & approval pengerjaan.
        </p>
      </div>

      <!-- Quick Demo Credentials Box -->
      <div class="p-3 bg-light rounded-3 border mb-3 small">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <span class="text-muted fw-bold">Akun Demo Pengujian:</span>
          <button type="button" onclick="fillDemoTech()" class="btn btn-xs py-0.5 px-2 btn-outline-primary" style="font-size: 11px;">
            <i class="bi bi-magic me-1"></i> Isi Otomatis
          </button>
        </div>
        <div class="text-secondary" style="font-size: 11px;">
          <div>ID Teknisi: <code>TEK-2026-01</code> (Budi Santoso)</div>
          <div>Sandi: <code>teknisi123</code></div>
        </div>
      </div>

      <!-- Login Form -->
      <form action="{{ route('teknisi.login.post') }}" method="POST">
        @csrf

        @if(isset($errors) && $errors->any())
          <div class="alert alert-danger py-2 px-3 small mb-3">
            {{ $errors->first() }}
          </div>
        @endif

        <div class="mb-3">
          <label for="techId" class="form-label small fw-bold text-secondary">ID Karyawan / Kode Staf Teknisi</label>
          <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-person-badge"></i></span>
            <input type="text" name="tech_id" id="techId" class="form-control border-start-0" placeholder="TEK-2026-XX" value="{{ old('tech_id', 'TEK-2026-01') }}" required>
          </div>
        </div>

        <div class="mb-3">
          <label for="techPass" class="form-label small fw-bold text-secondary">Kata Sandi Staf</label>
          <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-key"></i></span>
            <input type="password" name="password" id="techPass" class="form-control border-start-0" placeholder="••••••••" value="teknisi123" required>
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm mb-3">
          <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Panel Workshop
        </button>
      </form>

      <!-- Link Pelanggan -->
      <div class="text-center small text-muted border-top pt-3">
        Pelanggan ingin lacak unit servis? <a href="{{ url('/user-panel') }}" class="fw-bold text-primary text-decoration-none">Lacak Nomor Tiket di sini</a>
      </div>

    </div>
  </div>

  <script>
    function fillDemoTech() {
      document.getElementById('techId').value = "TEK-2026-01";
      document.getElementById('techPass').value = "teknisi123";
    }

    function handleTechLogin(e) {
      e.preventDefault();
      sessionStorage.setItem('sercomp_logged_tech', 'Budi Santoso (Hardware Specialist)');
      window.location.href = "{{ url('/teknisi') }}";
    }
  </script>
</body>
</html>
