<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SerComp - Portal Pelacakan Pengguna & Lembar Persetujuan</title>
  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Lucide Icons CDN -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <!-- Font Plus Jakarta Sans -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- SerComp Theme -->
  <link rel="stylesheet" href="{{ asset('css/sercomp-theme.css') }}">
  <style>
    .step-node { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 13px; margin: 0 auto; transition: all 0.2s ease; }
    .step-line { height: 3px; flex-grow: 1; background: var(--sc-slate-200); margin-top: 17px; }
    .step-active { background: var(--sc-primary); color: #fff; box-shadow: 0 0 10px rgba(2, 132, 199, 0.4); }
    .step-done { background: var(--sc-emerald); color: #fff; }
    .step-idle { background: var(--sc-slate-200); color: var(--sc-slate-500); }
  </style>
</head>
<body class="d-flex flex-column min-vh-100 justify-content-between">


  <!-- NAVBAR -->
  <header class="navbar navbar-expand-lg bg-white border-bottom shadow-sm sticky-top py-2.5">
    <div class="container-xl">
      <a class="navbar-brand d-flex align-items-center gap-2.5" href="{{ url('/') }}">
        <div class="brand-box">SC</div>
        <div>
          <span class="fw-bold fs-5 text-dark" style="letter-spacing: -0.02em;">Ser<span class="text-primary">Comp</span></span>
          <span class="d-block text-muted text-uppercase" style="font-size: 9px; font-weight: 700; letter-spacing: 0.05em; margin-top: -2px;">Portal Pelacakan Pelanggan</span>
        </div>
      </a>
      
      <div class="d-flex align-items-center gap-2">
        <form action="{{ url('/user-panel') }}" method="GET" class="d-none d-sm-flex align-items-center me-1">
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
            <input type="text" name="ticket" class="form-control border-start-0 font-monospace" placeholder="Cari resi tiket..." value="{{ request('ticket', $ticket?->ticket_no) }}" style="width: 160px;" required>
            <button type="submit" class="btn btn-outline-primary fw-semibold px-2.5">Lacak</button>
          </div>
        </form>
        <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary px-3 py-1.5 d-inline-flex align-items-center gap-1">
          <i class="bi bi-house"></i>
          <span class="d-none d-md-inline">Beranda</span>
        </a>
        <a href="{{ url('/booking') }}" class="btn btn-sm btn-primary fw-semibold px-3 py-1.5 shadow-sm d-inline-flex align-items-center gap-1.5">
          <i class="bi bi-plus-circle"></i>
          <span>Reservasi Baru</span>
        </a>
      </div>
    </div>
  </header>

  <!-- MAIN CONTAINER -->
  <main class="container-xl py-4 flex-grow-1">

    <!-- 1. HEADER STATUS TIKET -->
    <div class="card card-bento p-4 mb-4 bg-white border">
      <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
        <div>
          <div class="d-flex flex-wrap align-items-center gap-2 mb-1.5">
            <span class="text-secondary small fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">Nomor Tiket:</span>
            <span id="displayTicketNo" class="fs-4 fw-bold font-monospace text-primary" style="letter-spacing: -0.02em;">#SC-2026-9042</span>
            <button type="button" onclick="copyCurrentTicket()" class="btn btn-sm btn-outline-secondary py-1 px-2.5 rounded-2 d-inline-flex align-items-center gap-1" title="Salin Nomor Tiket" style="font-size: 11px;">
              <i class="bi bi-clipboard" id="iconCopyUserPanel"></i>
              <span id="textCopyUserPanel">Salin</span>
            </button>
            <span id="badgeStatus" class="badge-status badge-status-warning fw-bold">
              Menunggu Pemeriksaan
            </span>
          </div>
          <div class="text-secondary small d-flex flex-wrap align-items-center gap-2">
            <span>Perangkat: <strong id="displayDevice" class="text-dark">Laptop Asus ROG Strix G15</strong></span>
            <span class="text-muted">&bull;</span>
            <span>Pelanggan: <strong id="displayCustomer" class="text-dark">Dzul Khair</strong></span>
          </div>
        </div>

        <!-- ALERT SLA 1x24 JAM PASCA-PENYERAHAN UNIT -->
        <div id="slaAlertBox" class="p-3 rounded-3 border d-flex align-items-center gap-3 bg-light text-muted" style="min-width: 340px;">
          <div class="text-warning flex-shrink-0">
            <i data-lucide="hourglass" style="width: 28px; height: 28px;"></i>
          </div>
          <div>
            <span class="d-block fw-bold small text-dark" id="slaTitle">SLA Auto-Close (Pasca-Terima Unit)</span>
            <div id="slaCountdown" class="fw-bold font-monospace text-secondary fs-6">Menunggu Unit Diserahkan</div>
            <span class="d-block text-muted" style="font-size: 11px;">Masa toleransi uji 24 jam sebelum tiket resmi diselesaikan</span>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. STEPPER PROGRESS 7 TAHAP -->
    <div class="card card-bento p-4 mb-4 bg-white border">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="text-uppercase text-secondary fw-bold small mb-0" style="letter-spacing: 0.05em; font-size: 11px;">
          Pelacakan Tahapan Servis Real-Time:
        </h6>
        <span class="badge bg-light text-secondary border font-monospace" style="font-size: 11px;">
          7 SIKLUS WORKSHOP
        </span>
      </div>
      <div class="d-flex align-items-start justify-content-between text-center overflow-auto pb-2" style="min-width: 760px;">
          
          <div style="width: 90px;">
            <div id="step1Node" class="step-node step-done"><i class="bi bi-check-lg"></i></div>
            <div class="small fw-bold mt-2 text-dark">1. Booking</div>
            <span class="text-muted" style="font-size: 10px;">Kronologi & Foto</span>
          </div>
          <div id="line1" class="step-line"></div>

          <div style="width: 90px;">
            <div id="step2Node" class="step-node step-idle">2</div>
            <div class="small fw-bold mt-2 text-muted" id="labelStep2">2. Workshop</div>
            <span class="text-muted" style="font-size: 10px;">Cek Fisik</span>
          </div>
          <div id="line2" class="step-line"></div>

          <div style="width: 100px;">
            <div id="step3Node" class="step-node step-idle">3</div>
            <div class="small fw-bold mt-2 text-muted" id="labelStep3">3. Approval</div>
            <span class="text-muted" style="font-size: 10px;">Biaya Terkunci</span>
          </div>
          <div id="line3" class="step-line"></div>

          <div style="width: 90px;">
            <div id="step4Node" class="step-node step-idle">4</div>
            <div class="small fw-bold mt-2 text-muted" id="labelStep4">4. Bayar QRIS</div>
            <span class="text-muted" style="font-size: 10px;">Lunas di Muka</span>
          </div>
          <div id="line4" class="step-line"></div>

          <div style="width: 100px;">
            <div id="step5Node" class="step-node step-idle">5</div>
            <div class="small fw-bold mt-2 text-muted" id="labelStep5">5. Reparasi</div>
            <span class="text-muted" style="font-size: 10px;">Troubleshoot</span>
          </div>
          <div id="line5" class="step-line"></div>

          <div style="width: 100px;">
            <div id="step6Node" class="step-node step-idle">6</div>
            <div class="small fw-bold mt-2 text-muted" id="labelStep6">6. Selesai</div>
            <span class="text-muted" style="font-size: 10px;">Running Test</span>
          </div>
          <div id="line6" class="step-line"></div>

          <div style="width: 110px;">
            <div id="step7Node" class="step-node step-idle">7</div>
            <div class="small fw-bold mt-2 text-muted" id="labelStep7">7. Garansi</div>
            <span class="text-muted" style="font-size: 10px;">SLA 24h & E-Warranty</span>
          </div>

        </div>
      </div>

    <!-- BANNER UNIT DITERIMA USER & TOMBOL TUTUP TIKET (MUNCUL PASCA SERAH TERIMA) -->
    <!-- BANNER UNIT DITERIMA USER & BUKTI SERAH TERIMA KURIR -->
    <div id="handoverNotice" class="card border-success bg-success-subtle mb-4 d-none">
      <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-3">
          <div class="d-flex align-items-center gap-3">
            <div class="fs-1 text-success"><i class="bi bi-box2-check-fill"></i></div>
            <div>
              <h6 class="fw-bold text-success-emphasis mb-1">Unit Telah Diserahkan Oleh Kurir & Diterima!</h6>
              <p class="small text-secondary mb-0">
                Diserahkan oleh kurir: <strong id="handoverCourierText" class="text-dark">Kurir SerComp</strong> • Penerima: <strong id="handoverReceiverText" class="text-dark">Pelanggan</strong>. Sisa waktu toleransi uji: <strong id="handoverTimerText">23:59:59</strong>.
              </p>
            </div>
          </div>
          <button onclick="confirmCloseTicket()" class="btn btn-success fw-bold px-4 py-2 text-nowrap d-inline-flex align-items-center gap-2">
            <i class="bi bi-check2-circle"></i>
            <span>Konfirmasi Terima & Aktifkan e-Warranty</span>
          </button>
        </div>
        <div id="handoverSignatureCard" class="p-3 bg-white rounded-3 border d-flex flex-wrap align-items-center justify-content-between gap-3 d-none">
          <div>
            <span class="text-muted text-uppercase fw-bold d-block" style="font-size: 10px;"><i class="bi bi-pen-fill text-primary me-1"></i> Tanda Tangan Digital Penerima Unit:</span>
            <span class="small text-secondary">Tanda tangan dibubuhkan langsung kepada kurir saat penyerahan unit fisik.</span>
          </div>
          <div>
            <img id="handoverSignatureImg" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='150' height='60' viewBox='0 0 150 60'%3E%3Crect width='100%25' height='100%25' fill='%23f8fafc'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%2394a3b8' font-family='sans-serif' font-size='11'%3ETerverifikasi%3C/text%3E%3C/svg%3E" alt="Tanda Tangan Penerima" class="border rounded bg-light p-1" style="max-height: 70px; max-width: 200px; display: none;">
          </div>
        </div>
      </div>
    </div>

    <!-- 3. LEMBAR PERSETUJUAN TEKNISI -->
    <div id="approvalSection" class="card card-bento mb-4 overflow-hidden bg-white border">
      <!-- Section Header -->
      <div class="px-4 py-3 bg-white border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="icon-box-primary flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.2rem; background: var(--sc-brand-subtle); color: var(--sc-brand); border-radius: 10px; display: inline-flex; align-items: center; justify-content: center;">
            <i class="bi bi-file-earmark-check"></i>
          </div>
          <div>
            <h6 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.01em;">Lembar Persetujuan Kerusakan & Estimasi Biaya</h6>
            <span class="text-secondary small" style="font-size: 12px;">Biaya wajib disetujui di muka sebelum unit dibongkar teknisi</span>
          </div>
        </div>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 fw-bold font-monospace" style="font-size: 11px; letter-spacing: 0.05em; border-radius: 9999px;">
          ZERO HIDDEN COST
        </span>
      </div>

      <div class="p-4">
        <!-- Kronologi vs Analisis (2 Kolom Card) -->
        <div class="row g-3 mb-3">
          
          <!-- Kronologi Keluhan Konsumen -->
          <div class="col-md-6">
            <div class="card h-100 border rounded-3 p-3.5 bg-light-subtle d-flex flex-column justify-content-between shadow-2xs">
              <div>
                <div class="d-flex align-items-center justify-content-between pb-2 mb-2.5 border-bottom">
                  <div class="d-inline-flex align-items-center gap-2">
                    <i class="bi bi-person-lines-fill text-secondary"></i>
                    <span class="fw-bold text-dark text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">
                      Kronologi Keluhan Konsumen
                    </span>
                  </div>
                  <span class="badge bg-white text-secondary border px-2 py-0.5" style="font-size: 10px; font-weight: 600;">
                    User Statement
                  </span>
                </div>
                <div class="p-3 bg-white rounded-2 border text-secondary small lh-base mb-3" style="min-height: 80px;">
                  <span id="displayChronology">Laptop mati mendadak saat rendering 3D, tercium bau sangit dari ventilasi exhaust belakang.</span>
                </div>
              </div>
              <div class="small text-secondary pt-2 border-top d-flex align-items-center justify-content-between" style="font-size: 12px;">
                <span>Metode Penyerahan:</span>
                <span id="displayDelivery" class="fw-semibold text-dark">Diantar Sendiri</span>
              </div>
            </div>
          </div>

          <!-- Hasil Diagnosa Meja Kerja -->
          <div class="col-md-6">
            <div class="card h-100 border rounded-3 p-3.5 bg-light-subtle d-flex flex-column justify-content-between shadow-2xs">
              <div>
                <div class="d-flex align-items-center justify-content-between pb-2 mb-2.5 border-bottom">
                  <div class="d-inline-flex align-items-center gap-2">
                    <i class="bi bi-cpu text-primary"></i>
                    <span class="fw-bold text-dark text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">
                      Hasil Diagnosa Meja Kerja
                    </span>
                  </div>
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5" style="font-size: 10px; font-weight: 600;">
                    Teknisi Hardware
                  </span>
                </div>
                <div class="p-3 bg-white rounded-2 border text-dark small fw-medium lh-base mb-3" style="min-height: 80px;">
                  <span id="displayDiagnosis">Bearing kipas pendingin GPU macet total berkerak debu tebal, thermal paste mengeras jadi bubuk.</span>
                </div>
              </div>
              <div class="small text-secondary pt-2 border-top d-flex align-items-center justify-content-between" style="font-size: 12px;">
                <span>Teknisi Pemeriksa:</span>
                <strong id="displayTechnician" class="text-dark">Budi Santoso</strong>
              </div>
            </div>
          </div>

        </div>

        <!-- Rencana Tindakan Perbaikan (Full Width Card) -->
        <div class="card border rounded-3 p-3.5 bg-light-subtle mb-4 shadow-2xs">
          <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 pb-2 mb-2.5 border-bottom">
            <div class="d-inline-flex align-items-center gap-2">
              <i class="bi bi-wrench-adjustable text-success"></i>
              <span class="fw-bold text-dark text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">
                Rencana Tindakan Perbaikan
              </span>
            </div>
            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 10px; font-weight: 600;">
              SOP Terverifikasi
            </span>
          </div>
          <div class="p-3 bg-white rounded-2 border">
            <p id="displayActionPlan" class="small text-dark fw-semibold mb-0 lh-base">
              Ganti modul dual fan cooler, deep cleaning heatsink, dan repasta thermal Arctic MX-4.
            </p>
          </div>
        </div>

        <!-- Tabel Rincian Biaya -->
        <h6 class="text-uppercase text-muted fw-bold small mb-2">Rincian Estimasi Biaya Terkunci:</h6>
        <div class="table-responsive mb-4">
          <table class="table table-bordered align-middle small mb-0">
            <thead class="table-light">
              <tr>
                <th>Komponen Layanan / Suku Cadang</th>
                <th>Kategori</th>
                <th class="text-end">Biaya (Rp)</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="fw-semibold">Jasa Profesional Teknisi & Uji Performa</td>
                <td class="text-muted">Jasa Perbaikan</td>
                <td id="costLabor" class="text-end font-monospace">Rp 0</td>
              </tr>
              <tr>
                <td class="fw-semibold">Suku Cadang / Modul Komponen Baru (Garansi 90 Hari)</td>
                <td class="text-muted">Hardware Sparepart</td>
                <td id="costParts" class="text-end font-monospace">Rp 0</td>
              </tr>
              <tr id="rowCleaning">
                <td class="fw-semibold">Add-on: Deep Cleaning & Dust Removal Exhaust Heatsink</td>
                <td class="text-muted">Perawatan Tambahan</td>
                <td id="costCleaning" class="text-end font-monospace">Rp 50.000</td>
              </tr>
              <tr id="rowRepasta">
                <td class="fw-semibold">Add-on: Repasta Thermal Compound Arctic MX-4 (CPU & GPU)</td>
                <td class="text-muted">Perawatan Tambahan</td>
                <td id="costRepasta" class="text-end font-monospace">Rp 75.000</td>
              </tr>
            </tbody>
            <tfoot class="table-group-divider">
              <tr class="table-light">
                <th colspan="2" class="text-end fs-6">Total Biaya Terkunci:</th>
                <th id="costTotal" class="text-end fs-5 text-primary font-monospace">Rp 0</th>
              </tr>
            </tfoot>
          </table>
        </div>

        <!-- Catatan Teknisi -->
        <div class="p-3 bg-light rounded-3 border mb-4 small text-secondary">
          <span class="fw-bold text-dark d-block mb-1"><i class="bi bi-shield-lock me-1"></i> Catatan Khusus Teknisi:</span>
          <span id="displayNote">Motherboard bebas korsleting. Seluruh data SSD aman dan tidak disentuh sesuai SOP.</span>
        </div>
        <!-- Notifikasi Status Negosiasi Biaya -->
        <div id="negoStatusNotice" class="mb-3"></div>

        <!-- Dynamic Action Area (Approval / QRIS / Wait Notice) -->
        <div id="approvalActionBtns" class="pt-2">
          <!-- Diisi via renderUI() -->
        </div>

      </div>
    </div>

    <!-- 4. DOKUMENTASI HASIL TROUBLESHOOTING & PART DIGANTI (MUNCUL PASCA SELESAI) -->
    <div id="evidenceSection" class="card border-0 shadow-sm rounded-3 mb-4 d-none bg-white">
      <div class="card-header bg-white border-bottom p-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-camera me-2 text-success"></i>Bukti Foto Dokumentasi Purnajual</h6>
        <span class="badge bg-success-subtle text-success">Transparansi Nyata</span>
      </div>
      <div class="card-body p-4">
        <div class="row g-4">
          <div class="col-md-6">
            <div class="border rounded-3 p-3 text-center bg-light">
              <span class="small fw-bold text-muted text-uppercase d-block mb-2">1. Running Test Unit Menyala Normal</span>
              <img id="imgLaptopFixed" src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&auto=format&fit=crop&q=80" class="img-fluid rounded-2 mb-2 border" style="max-height: 220px;" alt="Laptop Running Test">
              <div class="small text-success fw-semibold"><i class="bi bi-check-circle me-1"></i>Suhu idle 42°C, full load 68°C stabil</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="border rounded-3 p-3 text-center bg-light">
              <span class="small fw-bold text-muted text-uppercase d-block mb-2">2. Suku Cadang Lama Yang Diganti</span>
              <img id="imgPartOld" src="https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=600&auto=format&fit=crop&q=80" class="img-fluid rounded-2 mb-2 border" style="max-height: 220px;" alt="Modul Part Rusak">
              <div class="small text-muted"><i class="bi bi-box-seam me-1"></i>Komponen lama dikembalikan dalam dus kepada Anda</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 5. TIKET SELESAI & AKSES FAKTUR INVOICE RESMI -->
    <div id="invoiceAccessCard" class="card border-0 shadow-sm rounded-3 mb-4 bg-white d-none">
      <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <div>
          <h5 class="fw-bold text-dark mb-1"><i class="bi bi-receipt me-2 text-primary"></i>Faktur Invoice Resmi Telah Diterbitkan</h5>
          <p class="text-secondary small mb-0">
            Nomor Invoice: <strong id="invAccessNo" class="font-monospace text-primary">INV-SC-2026-9042</strong> • Digunakan untuk klaim garansi digital 90 hari.
          </p>
        </div>
        <div>
          <a id="btnOpenInvoice" href="{{ url('/invoice') }}" target="_blank" class="btn btn-primary fw-bold px-4 py-2">
            <i class="bi bi-printer me-1"></i> Buka & Cetak Invoice A4
          </a>
        </div>
      </div>
    </div>

  </main>

  <!-- MODAL PEMBAYARAN QRIS (DP / FULL) -->
  <div class="modal fade" id="qrisModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content border-0 shadow-lg text-center p-3.5 rounded-4">
        <div class="modal-header border-0 pb-0 justify-content-end">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body pt-0">
          <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 mb-2 fw-semibold" style="font-size: 0.7rem;">QRIS Dinamis Otomatis</span>
          <h5 class="fw-bold mb-1">Pilih Skema Bayar</h5>
          <p class="text-muted small mb-3">BCA, Mandiri, GoPay, OVO, Dana, atau ShopeePay</p>

          <!-- Opsi Skema Bayar: DP (50%) vs Lunas (100%) -->
          <div class="btn-group w-100 mb-3" role="group">
            <input type="radio" class="btn-check" name="paymentModeRadio" id="payRadioDp" autocomplete="off" onchange="togglePaymentMode('dp')">
            <label class="btn btn-outline-primary btn-sm py-2 fw-semibold" for="payRadioDp">
              <i class="bi bi-pie-chart me-1"></i> DP (50%)
            </label>

            <input type="radio" class="btn-check" name="paymentModeRadio" id="payRadioFull" autocomplete="off" checked onchange="togglePaymentMode('full')">
            <label class="btn btn-outline-primary btn-sm py-2 fw-semibold" for="payRadioFull">
              <i class="bi bi-check-all me-1"></i> Lunas (100%)
            </label>
          </div>

          <div class="p-3 bg-light rounded-3 border d-inline-block mb-3 shadow-2xs">
            <svg class="img-fluid" style="width: 160px; height: 160px;" viewBox="0 0 100 100" fill="currentColor">
              <path d="M0,0 h30 v30 h-30 z M5,5 v20 h20 v-20 z M10,10 h10 v10 h-10 z" />
              <path d="M70,0 h30 v30 h-30 z M75,5 v20 h20 v-20 z M80,10 h10 v10 h-10 z" />
              <path d="M0,70 h30 v30 h-30 z M5,75 v20 h20 v-20 z M10,80 h10 v10 h-10 z" />
              <rect x="35" y="5" width="8" height="8" />
              <rect x="50" y="10" width="12" height="6" />
              <rect x="38" y="22" width="20" height="8" />
              <rect x="10" y="38" width="15" height="15" />
              <rect x="35" y="38" width="8" height="24" />
              <rect x="52" y="38" width="16" height="8" />
              <rect x="75" y="38" width="20" height="12" />
              <rect x="52" y="52" width="22" height="12" />
              <rect x="78" y="55" width="15" height="15" />
              <rect x="38" y="70" width="12" height="20" />
              <rect x="56" y="70" width="18" height="8" />
              <rect x="56" y="82" width="35" height="10" />
            </svg>
          </div>

          <div class="bg-primary-subtle p-2.5 rounded-3 border border-primary-subtle mb-3 text-start">
            <div class="d-flex justify-content-between mb-1 small">
              <span class="text-secondary" id="labelQrisType">Nominal Ditagihkan:</span>
              <strong id="modalQrisAmount" class="text-primary font-monospace fs-6">Rp 0</strong>
            </div>
            <div class="d-flex justify-content-between small text-muted" id="rowOutstandingInfo">
              <span>Sisa Tagihan Nanti:</span>
              <span id="modalQrisOutstanding" class="font-monospace">Rp 0</span>
            </div>
          </div>

          <button onclick="simulatePaymentSuccess()" class="btn btn-success w-100 fw-bold py-2.5 shadow-sm d-inline-flex justify-content-center align-items-center gap-2">
            <i class="bi bi-check-circle"></i>
            <span id="btnPayQrisText">Simulasikan Bayar via QRIS</span>
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- MODAL VERIFIKASI KEAMANAN PEMILIK TIKET -->
  <div class="modal fade" id="modalVerifyOwner" tabindex="-1" aria-labelledby="modalVerifyOwnerLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content border-0 shadow-lg text-center p-4 rounded-4 bg-white">
        <div class="modal-header border-0 pb-0 justify-content-end p-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body pt-0 px-2">
          <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle mb-3" style="width: 56px; height: 56px;">
            <i data-lucide="shield-check" style="width: 28px; height: 28px;"></i>
          </div>
          <h5 class="fw-bold mb-1 text-dark" id="modalVerifyOwnerLabel">Verifikasi Kode PIN</h5>
          <p class="text-secondary small mb-3">
            Masukkan <strong>4 Karakter PIN Keamanan</strong> tiket Anda untuk membuka akses data:
          </p>
          <div id="verifyAlertError" class="alert alert-danger py-2 px-3 small mb-3 d-none border-0 rounded-3"></div>
          <div class="mb-3">
            <input type="text" id="inputVerifyPin" class="form-control text-center font-monospace fs-3 fw-bold tracking-widest py-2 rounded-3 border-2" placeholder="K#7X" maxlength="8" autofocus onkeydown="if(event.key==='Enter') submitVerificationPin()">
          </div>
          <span class="text-secondary d-block small mb-3" style="font-size: 11px;">
            *Atau gunakan 4 digit terakhir nomor WhatsApp: <span id="verifyPhoneHint" class="font-monospace text-primary fw-bold">08**-****-****</span>
          </span>
          <button type="button" onclick="submitVerificationPin()" class="btn btn-primary w-100 fw-semibold py-2.5 shadow-sm d-inline-flex justify-content-center align-items-center gap-2 rounded-3">
            <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i>
            <span>Verifikasi & Lanjutkan</span>
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- MODAL AJUKAN NEGO BIAYA -->
  <div class="modal fade" id="modalNego" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4 border-0 shadow-lg bg-white p-2">
        <div class="modal-header border-bottom p-3 px-4">
          <h5 class="modal-title fw-bold text-dark d-inline-flex align-items-center gap-2 fs-6">
            <i data-lucide="message-square-quote" class="text-warning" style="width: 20px; height: 20px;"></i>
            <span>Ajukan Negosiasi Biaya Servis</span>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4 small">
          <p class="text-secondary mb-3 lh-base">
            SerComp berkomitmen pada transparansi (Zero Hidden Cost). Jika estimasi biaya suku cadang atau jasa dirasa di luar anggaran, Anda dapat mengajukan tawaran atau berdiskusi langsung dengan Admin Workshop.
          </p>
          <div class="p-3 bg-light rounded-3 border mb-3">
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-secondary">Total Estimasi Saat Ini:</span>
              <strong id="modalNegoCurrentTotal" class="text-primary font-monospace fs-6">Rp 0</strong>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label text-secondary fw-bold text-uppercase" style="font-size: 10px; letter-spacing: 0.05em;">Budget / Penawaran yang Anda Harapkan (Rp):</label>
            <input type="number" id="inputNegoBudget" class="form-control rounded-3" placeholder="Cth: 350000">
            <span class="text-secondary" style="font-size: 11px;">Kosongkan bila ingin mendiskusikan opsi alternatif suku cadang.</span>
          </div>
          <div class="mb-3">
            <label class="form-label text-secondary fw-bold text-uppercase" style="font-size: 10px; letter-spacing: 0.05em;">Alasan / Catatan Negosiasi:</label>
            <textarea id="inputNegoNotes" rows="3" class="form-control rounded-3" placeholder="Cth: Bisakah diskon biaya jasa Rp 50.000? Atau adakah pilihan sparepart second yang bergaransi?"></textarea>
          </div>
          <div class="d-grid gap-2">
            <button type="button" onclick="submitNegotiation()" class="btn btn-primary fw-semibold py-2.5 d-inline-flex justify-content-center align-items-center gap-2 rounded-3 shadow-sm">
              <i data-lucide="send" style="width: 16px; height: 16px;"></i>
              <span>Kirim Permintaan Nego ke Admin</span>
            </button>
            <a id="btnNegoWaChat" href="#" target="_blank" class="btn btn-outline-success fw-semibold py-2.5 d-inline-flex justify-content-center align-items-center gap-2 rounded-3">
              <i data-lucide="message-circle" style="width: 16px; height: 16px;"></i>
              <span>Diskusi Nego via WhatsApp CS</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- FOOTER -->
  <footer class="bg-white text-muted py-3 text-center border-top small">
    SerComp Service Computer • Portal Pelacakan Pengguna Terbuka
  </footer>

  <!-- Bootstrap 5.3 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      if (window.lucide) {
        lucide.createIcons();
      }
    });
  </script>

  <script>
    let currentOrder = null;
    let bsQrisModal = null;
    let bsVerifyModal = null;
    let isOwnerVerified = @json($isVerified ?? false);
    let pendingActionCallback = null;

    function executeWithSecurityCheck(callback) {
      if (isOwnerVerified) {
        callback('');
        return;
      }
      pendingActionCallback = callback;
      if (!bsVerifyModal) {
        bsVerifyModal = new bootstrap.Modal(document.getElementById('modalVerifyOwner'));
      }
      document.getElementById('verifyPhoneHint').innerText = currentOrder.customerPhoneMasked || '08**-****-****';
      document.getElementById('verifyAlertError').classList.add('d-none');
      document.getElementById('inputVerifyPin').value = '';
      bsVerifyModal.show();
    }

    function submitVerificationPin() {
      const pin = document.getElementById('inputVerifyPin').value.trim();
      if (!pin || pin.length < 4) {
        const err = document.getElementById('verifyAlertError');
        err.innerText = "Masukkan 4 kode PIN keamanan tiket.";
        err.classList.remove('d-none');
        return;
      }
      if (pendingActionCallback) {
        pendingActionCallback(pin);
      }
    }
    function searchOtherTicket() {
      const input = document.getElementById('pageTicketInput');
      if (input) {
        input.focus();
        input.select();
        input.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }

    function loadOrder() {
      const serverTicket = @json($ticket ?? null);

      if (serverTicket) {
        currentOrder = {
          ticketNo: serverTicket.ticket_no,
          createdAt: serverTicket.created_at,
          customerName: serverTicket.customer_name,
          customerPhone: isOwnerVerified ? serverTicket.customer_phone : (serverTicket.customer_phone_masked || serverTicket.customer_phone),
          customerPhoneMasked: serverTicket.customer_phone_masked || '08**-****-****',
          deviceType: serverTicket.device_type,
          deviceBrand: serverTicket.device_brand,
          deviceModel: serverTicket.device_model || '',
          issueChronology: serverTicket.issue_chronology,
          addonCleaning: serverTicket.cost_cleaning > 0,
          addonRepasta: serverTicket.cost_repasta > 0,
          deliveryMethod: serverTicket.delivery_method,
          step: serverTicket.status_step || serverTicket.step,
          status: serverTicket.status,
          technician: serverTicket.technician ? serverTicket.technician.name : 'Budi Santoso (Hardware Specialist)',
          diagnosis: serverTicket.diagnosis || "Menunggu pemeriksaan fisik teknisi di meja workshop.",
          actionPlan: serverTicket.action_plan || "Akan ditentukan setelah teknisi memeriksa komponen dan jalur tegangan.",
          technicianNote: serverTicket.technician_notes || "Motherboard normal bebas korsleting. Seluruh data SSD terlindungi 100%.",
          costLabor: serverTicket.cost_labor || 0,
          costParts: serverTicket.cost_parts || 0,
          costCleaning: serverTicket.cost_cleaning || 0,
          costRepasta: serverTicket.cost_repasta || 0,
          totalPaid: serverTicket.total_paid || 0,
          outstanding: (serverTicket.outstanding !== undefined) ? serverTicket.outstanding : null,
          paymentStatus: serverTicket.derived_payment_status || serverTicket.payment_status,
          isApproved: serverTicket.is_approved,
          isCompleted: serverTicket.is_completed,
          unitHandedOver: serverTicket.unit_handed_over,
          unitHandedOverTime: serverTicket.unit_handed_over_at,
          isClosed: serverTicket.is_closed,
          invoiceNo: serverTicket.invoice_no,
          warrantyCode: (serverTicket.invoice && serverTicket.invoice.warranty) ? serverTicket.invoice.warranty.warranty_code : null,
          warrantyValidUntil: (serverTicket.invoice && serverTicket.invoice.warranty) ? serverTicket.invoice.warranty.expires_at : null,
          evidenceRunningPhoto: (serverTicket.diagnostic && serverTicket.diagnostic.evidence_running_test_photo) ? ('/storage/' + serverTicket.diagnostic.evidence_running_test_photo) : null,
          evidenceReplacedPhoto: (serverTicket.diagnostic && serverTicket.diagnostic.evidence_replaced_part_photo) ? ('/storage/' + serverTicket.diagnostic.evidence_replaced_part_photo) : null,
          customerType: serverTicket.customer_type || (serverTicket.customer ? serverTicket.customer.customer_type : 'Personal'),
          companyName: serverTicket.company_name || (serverTicket.customer ? serverTicket.customer.company_name : ''),
          negotiationStatus: serverTicket.negotiation_status || 'None',
          negotiationNotes: serverTicket.negotiation_notes || '',
          negotiationBudget: serverTicket.negotiation_budget || 0,
          courierName: serverTicket.courier_name || 'Kurir Workshop SerComp',
          receiverName: serverTicket.receiver_name || serverTicket.customer_name || 'Pelanggan',
          signatureData: serverTicket.signature_data || '',
          adminNotes: (serverTicket.diagnostic && serverTicket.diagnostic.admin_notes) ? serverTicket.diagnostic.admin_notes : ''
        };
      } else {
        currentOrder = null;
      }
      try { localStorage.removeItem('sercomp_active_order'); } catch(e) {}

      if (!currentOrder) {
        document.getElementById('displayTicketNo').innerText = "Belum Ada Tiket";
        document.getElementById('badgeStatus').innerText = "Menunggu Input Nomor Tiket";
        document.getElementById('displayDevice').innerText = "-";
        document.getElementById('displayCustomer').innerText = "-";
        document.getElementById('approvalSection').innerHTML = `
          <div class="card-body p-5 text-center">
            <div class="badge badge-info-accessible fs-1 p-3 rounded-circle mb-3">
              <i class="bi bi-search" aria-hidden="true"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2">Belum Ada Tiket yang Dilacak</h5>
            <p class="text-muted small mb-4" style="max-width: 480px; margin: 0 auto;">
              Silakan masukkan nomor resi tiket servis Anda atau buat reservasi servis baru.
            </p>
            <form action="{{ url('/user-panel') }}" method="GET" class="d-flex flex-column flex-sm-row justify-content-center gap-2 mx-auto mb-3" style="max-width: 440px;">
              <input type="text" name="ticket" class="form-control font-monospace" placeholder="Cth: SC-2026-9042" required>
              <button type="submit" class="btn btn-primary fw-bold px-3 text-nowrap">
                <i class="bi bi-search me-1"></i> Lacak Tiket
              </button>
            </form>
            <div>
              <a href="{{ url('/booking') }}" class="btn btn-outline-secondary btn-sm fw-semibold">
                <i class="bi bi-plus-circle me-1"></i> Buat Reservasi Servis Baru
              </a>
            </div>
          </div>
        `;
        return;
      }

      renderUI();
    }

    function renderUI() {
      document.getElementById('displayTicketNo').innerText = currentOrder.ticketNo;
      document.getElementById('displayDevice').innerText = `${currentOrder.deviceType} ${currentOrder.deviceBrand} ${currentOrder.deviceModel}`;
      let custTitle = currentOrder.customerName;
      if (currentOrder.customerType === 'Company' && currentOrder.companyName) {
        custTitle += ` [${currentOrder.companyName}]`;
      }
      document.getElementById('displayCustomer').innerText = custTitle;
      document.getElementById('displayChronology').innerText = currentOrder.issueChronology;
      document.getElementById('displayDelivery').innerText = currentOrder.deliveryMethod;
      document.getElementById('displayTechnician').innerText = currentOrder.technician || "Budi Santoso (Hardware Specialist)";

      document.getElementById('displayDiagnosis').innerText = currentOrder.diagnosis;
      document.getElementById('displayActionPlan').innerText = currentOrder.actionPlan;
      document.getElementById('displayNote').innerText = currentOrder.technicianNote;

      document.getElementById('costLabor').innerText = currentOrder.costLabor > 0 
        ? ("Rp " + currentOrder.costLabor.toLocaleString('id-ID')) 
        : "Menunggu Cek Fisik";
      document.getElementById('costParts').innerText = currentOrder.costParts > 0 
        ? ("Rp " + currentOrder.costParts.toLocaleString('id-ID')) 
        : "Menunggu Cek Fisik";

      if (!currentOrder.addonCleaning) {
        document.getElementById('rowCleaning').style.display = 'none';
      } else {
        document.getElementById('rowCleaning').style.display = '';
        document.getElementById('costCleaning').innerText = "Rp " + (currentOrder.costCleaning || 50000).toLocaleString('id-ID');
      }

      if (!currentOrder.addonRepasta) {
        document.getElementById('rowRepasta').style.display = 'none';
      } else {
        document.getElementById('rowRepasta').style.display = '';
        document.getElementById('costRepasta').innerText = "Rp " + (currentOrder.costRepasta || 75000).toLocaleString('id-ID');
      }

      // Dynamic Total & Outstanding breakdown
      const total = (currentOrder.costLabor || 0) + (currentOrder.costParts || 0) +
                    (currentOrder.addonCleaning ? (currentOrder.costCleaning || 50000) : 0) +
                    (currentOrder.addonRepasta ? (currentOrder.costRepasta || 75000) : 0);
      const totalStr = "Rp " + total.toLocaleString('id-ID');
      document.getElementById('costTotal').innerText = totalStr;

      // Outstanding & Total Paid
      const totalPaid = currentOrder.totalPaid || 0;
      const outstanding = (currentOrder.outstanding !== undefined) ? currentOrder.outstanding : Math.max(0, total - totalPaid);
      const isPartialPaid = totalPaid > 0 && outstanding > 0;

      // Update QRIS modal labels
      window.currentPaymentMode = 'full';
      updateQrisModalValues(total, totalPaid, outstanding);

      // Status badge
      document.getElementById('badgeStatus').innerText = currentOrder.status;

      // Reset stepper nodes
      for (let i = 1; i <= 7; i++) {
        const node = document.getElementById(`step${i}Node`);
        if (node) {
          node.className = "step-node step-idle";
          node.innerHTML = i;
        }
        const line = document.getElementById(`line${i}`);
        if (line) line.className = "step-line";
      }

      const step = currentOrder.step || 1;

      // Mark completed steps
      for (let i = 1; i < step; i++) {
        const node = document.getElementById(`step${i}Node`);
        if (node) {
          node.className = "step-node step-done";
          node.innerHTML = '<i class="bi bi-check-lg"></i>';
        }
        const line = document.getElementById(`line${i}`);
        if (line) line.className = "step-line bg-success";
      }

      // Mark active step
      const activeNode = document.getElementById(`step${step}Node`);
      if (activeNode) {
        activeNode.className = "step-node step-active";
      }

      // DYNAMIC ACTION BUTTONS PER STEP
      const actionContainer = document.getElementById('approvalActionBtns');

      if (currentOrder.status === 'Dibatalkan' || step === 0) {
        document.getElementById('badgeStatus').className = "badge bg-danger text-white px-2.5 py-1.5 fw-bold";
        document.getElementById('badgeStatus').innerText = "Servis Dibatalkan";
        actionContainer.innerHTML = `
          <div class="alert alert-danger border border-danger-subtle bg-danger-subtle text-dark d-flex align-items-center gap-3 mb-0 py-3 px-3 small w-100 rounded-3">
            <i class="bi bi-x-circle-fill fs-2 text-danger"></i>
            <div>
              <strong class="d-block text-danger fs-6 mb-1">Servis Resmi Dibatalkan</strong>
              <span>Konsumen menolak rincian biaya estimasi. Sesuai komitmen transparansi SerComp (No Hidden Cost), unit dirakit kembali ke kondisi semula <strong>tanpa dipungut biaya perbaikan</strong> (Rp 0). Silakan ambil unit fisik Anda di meja workshop.</span>
            </div>
          </div>
        `;
        document.getElementById('costTotal').innerText = "Rp 0 (Batal)";
      } else if (step <= 2) {
        actionContainer.innerHTML = `
          <div class="alert alert-info border border-info-subtle bg-primary-subtle text-dark d-flex align-items-center gap-3 mb-0 py-2.5 px-3 small w-100">
            <i class="bi bi-hourglass-split fs-3 text-primary"></i>
            <div>
              <strong class="d-block text-dark">Tahap 1-2: Menunggu Pemeriksaan Fisik di Workshop</strong>
              <span>Unit Anda sedang disiapkan di workshop. Teknisi akan membongkar unit dan menerbitkan rincian biaya resmi di lembar ini sebelum pengerjaan dimulai.</span>
            </div>
          </div>
        `;
        document.getElementById('badgeStatus').className = "badge badge-info-accessible px-2.5 py-1.5 fw-bold";
      } else if (step === 3) {
        actionContainer.innerHTML = `
          <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 w-100">
            <div class="small text-muted d-flex align-items-center gap-2">
              <i class="bi bi-info-circle text-primary"></i>
              <span>Estimasi biaya resmi telah dirilis. Anda dapat menyetujui, mengajukan negosiasi biaya, atau membatalkan.</span>
            </div>
            <div class="d-flex flex-wrap gap-2 text-nowrap">
              <button onclick="handleReject()" class="btn btn-outline-danger fw-semibold px-3 py-2 btn-sm">
                Tolak / Batalkan
              </button>
              <button onclick="openNegoModal()" class="btn btn-warning text-dark fw-bold px-3 py-2 btn-sm d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-chat-quote-fill"></i>
                <span>Ajukan Nego</span>
              </button>
              <button onclick="handleApprove()" class="btn btn-primary fw-bold px-4 py-2 btn-sm shadow-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-check-circle"></i>
                <span>Setujui & Bayar</span>
              </button>
            </div>
          </div>
        `;
        document.getElementById('badgeStatus').className = "badge badge-warning-accessible px-2.5 py-1.5 fw-bold";
      } else if (step === 4) {
        actionContainer.innerHTML = `
          <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 w-100 p-2.5 bg-light rounded-3 border shadow-2xs">
            <div class="small text-dark">
              <div class="fw-bold d-flex align-items-center gap-1.5 text-primary mb-0.5">
                <i class="bi bi-qr-code fs-5"></i>
                <span>Pilihan Pembayaran DP (50%) atau Pelunasan</span>
              </div>
              <span class="text-secondary">Total: <strong>${totalStr}</strong> • DP 50%: <strong>Rp ${(Math.round(total * 0.5)).toLocaleString('id-ID')}</strong></span>
            </div>
            <button onclick="openQrisModal()" class="btn btn-success fw-bold px-4 py-2.5 btn-sm shadow-sm d-inline-flex align-items-center gap-2">
              <i class="bi bi-qr-code-scan"></i>
              <span>Bayar via QRIS (DP / Full)</span>
            </button>
          </div>
        `;
        document.getElementById('badgeStatus').className = "badge badge-warning-accessible px-2.5 py-1.5 fw-bold";
      } else if (step === 5) {
        const paymentLabel = isPartialPaid ? `Uang Muka (DP) Rp ${totalPaid.toLocaleString('id-ID')} Terverifikasi:` : 'Pembayaran QRIS Terverifikasi Lunas:';
        const subNote = isPartialPaid ? `Sisa tagihan Rp ${outstanding.toLocaleString('id-ID')} dilunasi saat serah terima unit.` : 'Seluruh biaya servis telah lunas tanpa tagihan tersisa.';

        actionContainer.innerHTML = `
          <div class="alert alert-success d-flex align-items-center justify-content-between flex-wrap gap-2 mb-0 py-2.5 px-3 small w-100">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-check-circle-fill fs-4 text-success"></i>
              <div>
                <strong class="d-block text-dark">${paymentLabel}</strong>
                <span>Teknisi sedang melakukan proses perbaikan hardware di workshop. ${subNote}</span>
              </div>
            </div>
            ${isPartialPaid ? `
              <button onclick="openQrisModal('full')" class="btn btn-outline-success btn-sm fw-bold">
                <i class="bi bi-wallet2 me-1"></i> Lunasi Sisa (Rp ${outstanding.toLocaleString('id-ID')})
              </button>
            ` : ''}
          </div>
        `;
        document.getElementById('badgeStatus').className = "badge badge-info-accessible px-2.5 py-1.5 fw-bold";
      } else if (step === 6) {
        actionContainer.innerHTML = `
          <div class="alert alert-success d-flex align-items-center gap-2 mb-0 py-2.5 px-3 small w-100">
            <i class="bi bi-box-seam fs-4 text-success"></i>
            <div>
              <strong class="d-block text-dark">Unit Selesai Diperbaiki & Diuji:</strong>
              <span>Perangkat siap diambil atau dalam proses serah terima kurir.</span>
            </div>
          </div>
        `;
        document.getElementById('badgeStatus').className = "badge bg-primary px-2.5 py-1.5 fw-bold text-white";
        document.getElementById('evidenceSection').classList.remove('d-none');
        document.getElementById('invoiceAccessCard').classList.remove('d-none');
        document.getElementById('invAccessNo').innerText = currentOrder.invoiceNo || ('INV-' + currentOrder.ticketNo);
        if (document.getElementById('btnOpenInvoice')) {
          document.getElementById('btnOpenInvoice').href = "{{ url('/invoice') }}?ticket=" + encodeURIComponent(currentOrder.ticketNo);
        }
        if (currentOrder.evidenceRunningPhoto) {
          document.getElementById('imgLaptopFixed').src = currentOrder.evidenceRunningPhoto;
        }
        if (currentOrder.evidenceReplacedPhoto) {
          document.getElementById('imgPartOld').src = currentOrder.evidenceReplacedPhoto;
        }
        actionContainer.innerHTML = `
          <div class="alert alert-success d-flex align-items-center gap-2 mb-0 py-2.5 px-3 small w-100">
            <i class="bi bi-patch-check-fill fs-4 text-success"></i>
            <div>
              <strong class="d-block text-dark">Tiket Selesai & Ditutup Resmi:</strong>
              <span>Garansi digital e-Warranty 90 hari aktif hingga ${currentOrder.warrantyValidUntil || '90 hari ke depan'}.</span>
            </div>
          </div>
        `;
        document.getElementById('badgeStatus').className = "badge badge-success-accessible px-2.5 py-1.5 fw-bold";
        document.getElementById('evidenceSection').classList.remove('d-none');
        document.getElementById('invoiceAccessCard').classList.remove('d-none');
      }

      // Render status negosiasi
      const negoBox = document.getElementById('negoStatusNotice');
      if (negoBox) {
        if (currentOrder.negotiationStatus === 'Requested') {
          const waText = encodeURIComponent(`Halo Admin SerComp, saya pemilik tiket ${currentOrder.ticketNo} ingin follow-up negosiasi estimasi biaya.`);
          negoBox.innerHTML = `
            <div class="alert alert-warning border border-warning-subtle d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3 p-3 rounded-3">
              <div>
                <strong class="d-block text-warning-emphasis"><i class="bi bi-hourglass-split me-1"></i> Pengajuan Negosiasi Biaya Sedang Ditinjau Admin Workshop</strong>
                <span class="small text-muted">Budget yang diajukan: <strong>Rp ${(parseInt(currentOrder.negotiationBudget) || 0).toLocaleString('id-ID')}</strong> • Catatan: "${currentOrder.negotiationNotes || '-'}"</span>
              </div>
              <a href="https://wa.me/6281122334455?text=${waText}" target="_blank" class="btn btn-sm btn-success fw-bold text-nowrap">
                <i class="bi bi-whatsapp me-1"></i> WA Admin CS
              </a>
            </div>
          `;
        } else if (currentOrder.negotiationStatus === 'Approved') {
          negoBox.innerHTML = `
            <div class="alert alert-success border border-success-subtle d-flex align-items-center gap-3 mb-3 p-3 rounded-3">
              <i class="bi bi-check-circle-fill text-success fs-4"></i>
              <div>
                <strong class="d-block text-success">Negosiasi Biaya Disetujui Admin Workshop!</strong>
                <span class="small text-dark">Estimasi biaya telah disesuaikan menjadi <strong>${totalStr}</strong>. Catatan Admin: "${currentOrder.adminNotes || 'Kesepakatan harga baru telah aktif.'}". Silakan setujui untuk lanjut pembayaran QRIS.</span>
              </div>
            </div>
          `;
        } else if (currentOrder.negotiationStatus === 'Rejected') {
          negoBox.innerHTML = `
            <div class="alert alert-secondary border border-secondary-subtle d-flex align-items-center gap-3 mb-3 p-3 rounded-3">
              <i class="bi bi-info-circle-fill text-secondary fs-4"></i>
              <div>
                <strong class="d-block text-dark">Pemberitahuan Negosiasi Biaya</strong>
                <span class="small text-muted">Mohon maaf, penyesuaian harga belum dapat disetujui. Catatan Admin: "${currentOrder.adminNotes || 'Suku cadang original bergaransi resmi 90 hari.'}". Anda dapat menyetujui rincian atau membatalkan tanpa biaya.</span>
              </div>
            </div>
          `;
        } else {
          negoBox.innerHTML = '';
        }
      }

      // Handover Notice & Kurir Tanda Tangan
      if (currentOrder.unitHandedOver && !currentOrder.isClosed) {
        document.getElementById('handoverNotice').classList.remove('d-none');
        document.getElementById('badgeStatus').className = "badge badge-warning-accessible px-2.5 py-1.5 fw-bold";
        const courierEl = document.getElementById('handoverCourierText');
        const receiverEl = document.getElementById('handoverReceiverText');
        if (courierEl) courierEl.innerText = currentOrder.courierName || 'Kurir Workshop';
        if (receiverEl) receiverEl.innerText = currentOrder.receiverName || currentOrder.customerName || 'Pelanggan';
        const sigCard = document.getElementById('handoverSignatureCard');
        const sigImg = document.getElementById('handoverSignatureImg');
        if (sigCard && sigImg) {
          if (currentOrder.signatureData) {
            sigCard.classList.remove('d-none');
            sigImg.src = currentOrder.signatureData;
          } else {
            sigCard.classList.add('d-none');
          }
        }
      }
      if (currentOrder.isClosed) {
        document.getElementById('handoverNotice').classList.add('d-none');
        const node7 = document.getElementById('step7Node');
        if (node7) {
          node7.className = "step-node step-done";
          node7.innerHTML = '<i class="bi bi-check-lg"></i>';
        }
        document.getElementById('badgeStatus').className = "badge badge-success-accessible px-2.5 py-1.5 fw-bold";
        document.getElementById('slaCountdown').innerText = "Tiket Selesai Permanen";
        document.getElementById('slaAlertBox').className = "p-2.5 px-3 rounded-3 border d-flex align-items-center gap-3 badge-success-accessible";
      }
    }

    function openQrisModal() {
      if (!bsQrisModal) {
        bsQrisModal = new bootstrap.Modal(document.getElementById('qrisModal'));
      }
      bsQrisModal.show();
    }

    function handleApprove() {
      executeWithSecurityCheck(function(pin) {
        fetch('/user-panel/' + encodeURIComponent(currentOrder.ticketNo) + '/approve', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          },
          body: JSON.stringify({ security_pin: pin, phone_verification: pin })
        })
        .then(res => {
          if (res.status === 403) {
            throw new Error("Kode PIN Keamanan 4 digit tidak cocok dengan tiket ini.");
          }
          return res.json();
        })
        .then(data => {
          isOwnerVerified = true;
          if (bsVerifyModal) bsVerifyModal.hide();
          currentOrder.isApproved = true;
          currentOrder.step = 4;
          currentOrder.status = "Menunggu Pembayaran QRIS";
          localStorage.setItem('sercomp_active_order', JSON.stringify(currentOrder));
          renderUI();
          openQrisModal();
        })
        .catch(err => {
          const errEl = document.getElementById('verifyAlertError');
          if (errEl && bsVerifyModal) {
            errEl.innerText = err.message || "Verifikasi kepemilikan gagal.";
            errEl.classList.remove('d-none');
          } else {
            alert(err.message);
          }
        });
      });
    }

    function togglePaymentMode(mode) {
      window.currentPaymentMode = mode;
      const total = (currentOrder.costLabor || 0) + (currentOrder.costParts || 0) +
                    (currentOrder.addonCleaning ? (currentOrder.costCleaning || 50000) : 0) +
                    (currentOrder.addonRepasta ? (currentOrder.costRepasta || 75000) : 0);
      const totalPaid = currentOrder.totalPaid || 0;
      const outstanding = (currentOrder.outstanding !== undefined) ? currentOrder.outstanding : Math.max(0, total - totalPaid);
      updateQrisModalValues(total, totalPaid, outstanding);
    }

    function updateQrisModalValues(total, totalPaid, outstanding) {
      const mode = window.currentPaymentMode || 'full';
      let chargeAmount = 0;
      let remainAfter = 0;

      if (mode === 'dp') {
        chargeAmount = Math.round(total * 0.5);
        if (chargeAmount > outstanding) chargeAmount = outstanding;
        remainAfter = Math.max(0, total - chargeAmount);
        document.getElementById('labelQrisType').innerText = "Nominal DP (50%):";
        document.getElementById('btnPayQrisText').innerText = `Bayar DP (Rp ${chargeAmount.toLocaleString('id-ID')})`;
      } else {
        chargeAmount = outstanding > 0 ? outstanding : total;
        remainAfter = 0;
        document.getElementById('labelQrisType').innerText = totalPaid > 0 ? "Pelunasan Sisa:" : "Nominal Lunas:";
        document.getElementById('btnPayQrisText').innerText = `Bayar Lunas (Rp ${chargeAmount.toLocaleString('id-ID')})`;
      }

      document.getElementById('modalQrisAmount').innerText = "Rp " + chargeAmount.toLocaleString('id-ID');
      document.getElementById('modalQrisOutstanding').innerText = "Rp " + remainAfter.toLocaleString('id-ID');
    }

    function simulatePaymentSuccess() {
      const mode = window.currentPaymentMode || 'full';
      fetch('/user-panel/' + encodeURIComponent(currentOrder.ticketNo) + '/pay-qris', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ payment_mode: mode })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          currentOrder.paymentStatus = data.payment_status || (mode === 'dp' ? 'Partial' : 'Paid');
          currentOrder.totalPaid = (currentOrder.totalPaid || 0) + data.amount_paid;
          currentOrder.outstanding = data.outstanding;
          currentOrder.isApproved = true;
          currentOrder.step = 5;
          currentOrder.status = "Sedang Dikerjakan";
          localStorage.setItem('sercomp_active_order', JSON.stringify(currentOrder));

          if (bsQrisModal) bsQrisModal.hide();
          alert(data.message || "Pembayaran QRIS Berhasil Diverifikasi!");
          renderUI();
        }
      })
      .catch(err => {
        if (bsQrisModal) bsQrisModal.hide();
        alert("Pembayaran terverifikasi sistem!");
        location.reload();
      });
    }

    function handleReject() {
      if (confirm("Apakah Anda yakin ingin membatalkan servis? Unit akan dirakit kembali tanpa biaya reparasi.")) {
        executeWithSecurityCheck(function(pin) {
          fetch('/user-panel/' + encodeURIComponent(currentOrder.ticketNo) + '/reject', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            },
            body: JSON.stringify({ security_pin: pin, phone_verification: pin })
          })
          .then(res => {
            if (res.status === 403) {
              throw new Error("Kode PIN Keamanan 4 digit tidak cocok dengan tiket ini.");
            }
            return res.json();
          })
          .then(data => {
            isOwnerVerified = true;
            if (bsVerifyModal) bsVerifyModal.hide();
            currentOrder.status = "Dibatalkan Pelanggan";
            localStorage.setItem('sercomp_active_order', JSON.stringify(currentOrder));
            renderUI();
          })
          .catch(err => {
            const errEl = document.getElementById('verifyAlertError');
            if (errEl && bsVerifyModal) {
              errEl.innerText = err.message || "Verifikasi gagal.";
              errEl.classList.remove('d-none');
            } else {
              alert(err.message);
            }
          });
        });
      }
    }

    function confirmCloseTicket() {
      executeWithSecurityCheck(function(pin) {
        fetch('/user-panel/' + encodeURIComponent(currentOrder.ticketNo) + '/close', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          },
          body: JSON.stringify({ security_pin: pin, phone_verification: pin })
        })
        .then(res => {
          if (res.status === 403) {
            throw new Error("Kode PIN Keamanan 4 digit tidak cocok dengan tiket ini.");
          }
          return res.json();
        })
        .then(data => {
          isOwnerVerified = true;
          if (bsVerifyModal) bsVerifyModal.hide();
          currentOrder.isClosed = true;
          currentOrder.step = 7;
          currentOrder.status = "Closed / Completed";
          localStorage.setItem('sercomp_active_order', JSON.stringify(currentOrder));
          renderUI();
        })
        .catch(err => {
          const errEl = document.getElementById('verifyAlertError');
          if (errEl && bsVerifyModal) {
            errEl.innerText = err.message || "Verifikasi gagal.";
            errEl.classList.remove('d-none');
          } else {
            alert(err.message);
          }
        });
      });
    }

    function openNegoModal() {
      const totalText = document.getElementById('costTotal').innerText;
      document.getElementById('modalNegoCurrentTotal').innerText = totalText;
      const waText = encodeURIComponent(`Halo Admin SerComp, saya pemilik tiket ${currentOrder.ticketNo} ingin mendiskusikan negosiasi estimasi biaya ${totalText}.`);
      document.getElementById('btnNegoWaChat').href = `https://wa.me/6281122334455?text=${waText}`;
      const modal = new bootstrap.Modal(document.getElementById('modalNego'));
      modal.show();
    }

    function submitNegotiation() {
      const notes = document.getElementById('inputNegoNotes').value.trim();
      const budget = document.getElementById('inputNegoBudget').value.trim();

      if (!notes) {
        alert("Harap masukkan alasan atau catatan negosiasi Anda.");
        return;
      }

      executeWithSecurityCheck(function(pin) {
        fetch('/user-panel/' + encodeURIComponent(currentOrder.ticketNo) + '/negotiate', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            security_pin: pin,
            phone_verification: pin,
            negotiation_notes: notes,
            negotiation_budget: budget || null
          })
        })
        .then(res => {
          if (res.status === 403) {
            throw new Error("Kode PIN Keamanan 4 digit tidak cocok dengan tiket ini.");
          }
          return res.json();
        })
        .then(data => {
          isOwnerVerified = true;
          if (bsVerifyModal) bsVerifyModal.hide();
          const modalEl = document.getElementById('modalNego');
          bootstrap.Modal.getInstance(modalEl)?.hide();
          alert("Pengajuan negosiasi Anda berhasil dikirim ke Admin Workshop. Notifikasi otomatis telah dikirim via WA & Email.");
          currentOrder.negotiationStatus = 'Requested';
          currentOrder.negotiationNotes = notes;
          currentOrder.negotiationBudget = budget;
          renderUI();
        })
        .catch(err => {
          const errEl = document.getElementById('verifyAlertError');
          if (errEl && bsVerifyModal) {
            errEl.innerText = err.message || "Verifikasi gagal.";
            errEl.classList.remove('d-none');
          } else {
            alert(err.message);
          }
        });
      });
    }

    // SLA 1x24 jam Pasca-Penyerahan Unit Countdown Timer
    setInterval(() => {
      if (currentOrder && currentOrder.unitHandedOver && !currentOrder.isClosed && currentOrder.unitHandedOverTime) {
        const deadline = new Date(currentOrder.unitHandedOverTime).getTime() + (24 * 60 * 60 * 1000);
        const distance = deadline - new Date().getTime();

        if (distance < 0) {
          currentOrder.isClosed = true;
          currentOrder.step = 7;
          currentOrder.status = "Auto-Closed by SLA (1x24 Jam Lewat Pasca-Terima Unit)";
          localStorage.setItem('sercomp_active_order', JSON.stringify(currentOrder));
          renderUI();
        } else {
          const h = Math.floor((distance / (1000 * 60 * 60)) % 24);
          const m = Math.floor((distance / (1000 * 60)) % 60);
          const s = Math.floor((distance / 1000) % 60);
          const timeStr = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
          document.getElementById('slaCountdown').innerText = timeStr;
          document.getElementById('handoverTimerText').innerText = timeStr;
          document.getElementById('slaAlertBox').className = "p-2.5 px-3 rounded-3 border d-flex align-items-center gap-3 badge-warning-accessible";
        }
      }
    }, 1000);

    function copyCurrentTicket() {
      const text = document.getElementById('displayTicketNo').innerText.trim();
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(() => {
          feedbackCopyUserPanel();
        }).catch(() => {
          fallbackCopyUser(text);
        });
      } else {
        fallbackCopyUser(text);
      }
    }

    function feedbackCopyUserPanel() {
      const text = document.getElementById('textCopyUserPanel');
      const icon = document.getElementById('iconCopyUserPanel');
      const original = text.innerText;
      text.innerText = "Tersalin!";
      icon.className = "bi bi-check2";
      setTimeout(() => {
        text.innerText = original;
        icon.className = "bi bi-clipboard";
      }, 2000);
    }

    function fallbackCopyUser(text) {
      const temp = document.createElement('textarea');
      temp.value = text;
      document.body.appendChild(temp);
      temp.select();
      document.execCommand('copy');
      document.body.removeChild(temp);
      feedbackCopyUserPanel();
    }

    window.onload = loadOrder;
  </script>
</body>
</html>
