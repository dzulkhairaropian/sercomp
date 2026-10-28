<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk Akun Pelanggan - SerComp</title>
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
        <h4 class="fw-bold text-dark mb-1">Masuk Akun Pelanggan</h4>
        <p class="text-muted small mb-0">
          Lacak status tiket servis, setujui biaya, & klaim garansi digital Anda.
        </p>
      </div>

      <!-- Quick Social / OTP Login -->
      <div class="d-flex flex-column gap-2 mb-3">
        <button type="button" onclick="quickCustomerLogin('Google')" class="btn btn-outline-secondary btn-sm py-2 d-flex align-items-center justify-content-center gap-2 fw-semibold">
          <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
          <span class="text-dark small">Lanjutkan dengan Google</span>
        </button>
        <button type="button" onclick="quickCustomerLogin('WhatsApp OTP')" class="btn btn-outline-success btn-sm py-2 d-flex align-items-center justify-content-center gap-2 fw-semibold">
          <i class="bi bi-whatsapp text-success"></i>
          <span class="text-dark small">Masuk Cepat via WhatsApp OTP</span>
        </button>
      </div>

      <!-- Divider -->
      <div class="position-relative text-center my-3">
        <hr class="text-muted">
        <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 small text-muted" style="font-size: 11px;">
          atau email akun
        </span>
      </div>

      <!-- Login Form -->
      <form onsubmit="handleCustomerLogin(event)">
        <div class="mb-3">
          <label class="form-label small fw-bold text-secondary">Email atau Nomor WhatsApp</label>
          <input type="text" id="custEmail" class="form-control" placeholder="nama@email.com / 0812xxxx" value="dzul@student.unm.ac.id" required>
        </div>
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <label class="form-label small fw-bold text-secondary mb-0">Kata Sandi</label>
            <a href="#" class="text-decoration-none small text-primary" style="font-size: 11px;">Lupa Sandi?</a>
          </div>
          <input type="password" id="custPass" class="form-control" value="••••••••" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm mb-3">
          Masuk ke Portal Pelanggan
        </button>
      </form>

      <!-- Footer Action -->
      <div class="text-center small text-muted border-top pt-3">
        Belum punya akun? <a href="{{ url('/register') }}" class="fw-bold text-primary text-decoration-none">Daftar Akun Baru</a>
      </div>

      <!-- Switch to Technician -->
      <div class="text-center mt-3 pt-2">
        <a href="{{ url('/teknisi/login') }}" class="small text-secondary text-decoration-none">
          <i class="bi bi-tools me-1 text-primary"></i> Login Khusus Staf & Teknisi Workshop &rarr;
        </a>
      </div>

    </div>
  </div>

  <script>
    function handleCustomerLogin(e) {
      e.preventDefault();
      sessionStorage.setItem('sercomp_logged_user', 'Dzul Khair Aropian');
      window.location.href = "{{ url('/user-panel') }}";
    }

    function quickCustomerLogin(provider) {
      sessionStorage.setItem('sercomp_logged_user', 'Pengguna ' + provider);
      window.location.href = "{{ url('/user-panel') }}";
    }
  </script>
</body>
</html>
