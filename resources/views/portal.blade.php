<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SerComp - Pusat Navigasi Prototipe Web (Bootstrap 5.3)</title>
  <!-- Bootstrap 5.3 CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- SerComp Theme -->
  <link rel="stylesheet" href="{{ asset('css/sercomp-theme.css') }}">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--sc-slate-900); }
    .iframe-wrapper {
      height: calc(100vh - 62px);
      width: 100%;
    }
  </style>
</head>
<body class="text-white d-flex flex-column min-vh-100" style="background-color: var(--sc-slate-900);">

  <!-- TOP NAV BAR -->
  <header class="navbar navbar-expand-lg border-bottom sticky-top px-3 py-2 shadow-sm" style="background-color: var(--sc-navy-dark); border-color: #1e293b !important;">
    <div class="container-fluid d-flex flex-wrap align-items-center justify-content-between gap-2">
      <!-- Brand -->
      <div class="d-flex align-items-center gap-2">
        <div class="brand-box">SC</div>
        <div>
          <span class="fw-bold fs-6 text-white">Ser<span class="text-primary">Comp</span></span>
          <span class="badge bg-secondary-subtle text-info border border-secondary text-uppercase ms-1" style="font-size: 10px;">Bootstrap 5.3 Prototypes</span>
        </div>
      </div>

      <!-- Tab Switcher -->
      <div class="btn-group p-1 bg-black rounded-3 border border-secondary" role="group">
        <button id="tabLanding" onclick="loadFrame('/', this)" class="btn btn-sm btn-primary fw-bold px-3">
          1. Landing Page
        </button>
        <button id="tabBooking" onclick="loadFrame('/booking', this)" class="btn btn-sm btn-outline-secondary text-white-50 fw-bold px-3">
          2. Form Reservasi
        </button>
        <button id="tabUser" onclick="loadFrame('/user-panel', this)" class="btn btn-sm btn-outline-secondary text-white-50 fw-bold px-3">
          3. Portal User (Approval & QRIS)
        </button>
        <button id="tabTeknisi" onclick="loadFrame('/teknisi', this)" class="btn btn-sm btn-outline-secondary text-white-50 fw-bold px-3">
          4. Panel Teknisi & SLA 24h
        </button>
        <button id="tabInvoice" onclick="loadFrame('/invoice', this)" class="btn btn-sm btn-outline-secondary text-white-50 fw-bold px-3">
          5. Faktur Invoice A4
        </button>
      </div>

      <!-- Direct Link -->
      <div>
        <a id="directLink" href="/" target="_blank" class="btn btn-sm btn-outline-info fw-semibold">
          <i class="bi bi-box-arrow-up-right me-1"></i> Buka di Tab Baru
        </a>
      </div>
    </div>
  </header>

  <!-- IFRAME MAIN VIEW -->
  <div class="flex-grow-1 w-100 bg-secondary-subtle">
    <iframe id="mainFrame" src="/" class="iframe-wrapper border-0"></iframe>
  </div>

  <script>
    function loadFrame(url, btn) {
      document.getElementById('mainFrame').src = url;
      document.getElementById('directLink').href = url;

      // Update active button state
      const buttons = [
        document.getElementById('tabLanding'),
        document.getElementById('tabBooking'),
        document.getElementById('tabUser'),
        document.getElementById('tabTeknisi'),
        document.getElementById('tabInvoice')
      ];
      buttons.forEach(b => {
        if (b) {
          b.className = "btn btn-sm btn-outline-secondary text-white-50 fw-bold px-3";
        }
      });
      btn.className = "btn btn-sm btn-primary fw-bold px-3";
    }
  </script>
</body>
</html>
