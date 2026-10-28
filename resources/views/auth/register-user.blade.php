<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun Pelanggan Baru - SerComp</title>
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
      max-width: 460px;
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
        <h4 class="fw-bold text-dark mb-1">Daftar Akun Pelanggan</h4>
        <p class="text-muted small mb-0">
          Buat akun untuk kemudahan reservasi servis & garansi digital tersimpan aman.
        </p>
      </div>

      <!-- Register Form -->
      <form onsubmit="handleCustomerRegister(event)">
        <div class="mb-3">
          <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
          <input type="text" id="regName" class="form-control" placeholder="Contoh: Dzul Khair Aropian" required>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-bold text-secondary">Alamat Email</label>
          <input type="email" id="regEmail" class="form-control" placeholder="nama@email.com" required>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-bold text-secondary">Nomor WhatsApp Aktif</label>
          <input type="tel" id="regPhone" class="form-control" placeholder="08xxxxxxxxxx" required>
          <div class="form-text" style="font-size: 11px;">Nomor WA digunakan untuk update berkas invoice & notifikasi servis.</div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-bold text-secondary">Kata Sandi</label>
          <input type="password" id="regPass" class="form-control" placeholder="Minimal 8 karakter" required>
        </div>

        <div class="form-check mb-4 small">
          <input class="form-check-input" type="checkbox" id="agreeTerms" required checked>
          <label class="form-check-label text-muted" for="agreeTerms">
            Saya menyetujui Ketentuan Layanan & SOP Privasi Data SerComp.
          </label>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm mb-3">
          Daftar Akun Sekarang
        </button>
      </form>

      <!-- Footer Action -->
      <div class="text-center small text-muted border-top pt-3">
        Sudah memiliki akun? <a href="{{ url('/login') }}" class="fw-bold text-primary text-decoration-none">Masuk di sini</a>
      </div>

    </div>
  </div>

  <script>
    function handleCustomerRegister(e) {
      e.preventDefault();
      const name = document.getElementById('regName').value;
      sessionStorage.setItem('sercomp_logged_user', name);
      alert('Pendaftaran akun berhasil! Selamat datang di SerComp.');
      window.location.href = "{{ url('/user-panel') }}";
    }
  </script>
</body>
</html>
