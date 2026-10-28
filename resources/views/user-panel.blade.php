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

  <!-- TOP PROTOTYPE DEMO BAR -->
  <div class="sc-demo-bar">
    <div class="container-fluid d-flex flex-wrap align-items-center justify-content-between gap-2">
      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-warning text-dark fw-bold" style="font-size: 10px;">PROTOTIPE AKADEMIK</span>
        <span class="text-white-50 small d-none d-md-inline">Rintisan Bisnis Digital & Proyek SI 2</span>
      </div>
      <div class="d-flex align-items-center gap-1">
        <span class="text-white-50 me-1 small d-none d-lg-inline">Pintas Halaman:</span>
        <a href="{{ url('/') }}" class="btn-demo">1. Landing</a>
        <a href="{{ url('/booking') }}" class="btn-demo">2. Form Reservasi</a>
        <a href="{{ url('/user-panel') }}" class="btn-demo active">3. Portal Pelanggan</a>
        <a href="{{ url('/teknisi') }}" class="btn-demo">4. Panel Teknisi</a>
        <a href="{{ url('/invoice') }}" class="btn-demo">5. Faktur Invoice</a>
      </div>
    </div>
  </div>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm sticky-top">
    <div class="container-xl">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
        <div class="brand-box">SC</div>
        <div>
          <span class="fw-bold fs-5 text-dark">Ser<span class="text-primary">Comp</span></span>
          <span class="d-block text-muted small fw-semibold" style="font-size: 10px; margin-top:-3px;">PORTAL PELANGGAN</span>
        </div>
      </a>
      <div class="d-flex align-items-center gap-2">
        <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary">
          <i class="bi bi-house me-1"></i> Beranda
        </a>
        <button onclick="searchOtherTicket()" class="btn btn-sm btn-outline-primary fw-semibold">
          <i class="bi bi-search me-1"></i> Lacak No. Lain
        </button>
        <a href="{{ url('/booking') }}" class="btn btn-sm btn-primary fw-semibold">
          <i class="bi bi-plus-circle me-1"></i> Reservasi Baru
        </a>
      </div>
    </div>
  </nav>

  <!-- MAIN CONTAINER -->
  <main class="container-xl py-4 flex-grow-1">

    <!-- 1. HEADER STATUS TIKET -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
      <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="text-muted small fw-bold text-uppercase">Nomor Tiket:</span>
            <span id="displayTicketNo" class="fs-5 fw-bold font-monospace text-primary">#SC-2026-9042</span>
            <span id="badgeStatus" class="badge badge-warning-accessible px-2.5 py-1.5 fw-bold">
              Menunggu Pemeriksaan
            </span>
          </div>
          <p class="mb-0 text-secondary small">
            Perangkat: <strong id="displayDevice" class="text-dark">Laptop Asus ROG Strix G15</strong> • Pelanggan: <strong id="displayCustomer" class="text-dark">Dzul Khair</strong>
          </p>
        </div>

        <!-- ALERT SLA 1x24 JAM PASCA-PENYERAHAN UNIT -->
        <div id="slaAlertBox" class="p-2.5 px-3 rounded-3 border d-flex align-items-center gap-3 bg-light text-muted" style="min-width: 320px;">
          <div class="fs-3 text-warning">
            <i class="bi bi-hourglass-split"></i>
          </div>
          <div>
            <span class="d-block fw-bold small text-dark" id="slaTitle">SLA Auto-Close (Pasca-Terima Unit):</span>
            <span id="slaCountdown" class="fw-bold font-monospace text-secondary fs-6">Menunggu Unit Diserahkan</span>
            <span class="d-block text-muted" style="font-size: 11px;">Masa uji 24 jam sebelum tiket resmi diselesaikan</span>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. STEPPER PROGRESS 7 TAHAP -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
      <div class="card-body p-4">
        <h6 class="text-uppercase text-muted fw-bold small mb-3">Pelacakan Tahapan Servis Real-Time:</h6>
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
    </div>

    <!-- BANNER UNIT DITERIMA USER & TOMBOL TUTUP TIKET (MUNCUL PASCA SERAH TERIMA) -->
    <div id="handoverNotice" class="card border-success bg-success-subtle mb-4 d-none">
      <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="fs-1 text-success"><i class="bi bi-box2-check-fill"></i></div>
          <div>
            <h6 class="fw-bold text-success-emphasis mb-1">Unit Laptop / PC Telah Anda Terima!</h6>
            <p class="small text-secondary mb-0">
              Silakan tes perangkat Anda. Jika sudah normal, klik <strong>Konfirmasi Terima & Tutup Tiket</strong>. Bila Anda lupa, sistem otomatis menyelesaikannya dalam <strong id="handoverTimerText">23:59:59</strong>.
            </p>
          </div>
        </div>
        <button onclick="confirmCloseTicket()" class="btn btn-success fw-bold px-4 py-2 text-nowrap">
          <i class="bi bi-check2-circle me-1"></i> Konfirmasi Terima & Tutup Tiket
        </button>
      </div>
    </div>

    <!-- 3. LEMBAR PERSETUJUAN TEKNISI -->
    <div id="approvalSection" class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden bg-white">
      <div class="card-header bg-dark text-white p-3 px-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
          <i class="bi bi-file-earmark-text text-warning fs-5"></i>
          <div>
            <h6 class="fw-bold mb-0">Lembar Persetujuan Kerusakan & Estimasi Biaya</h6>
            <span class="text-light-50 small" style="font-size: 11px;">Estimasi biaya disetujui di muka sebelum teknisi membongkar unit</span>
          </div>
        </div>
        <span class="badge bg-primary text-uppercase px-2.5 py-1">Zero Hidden Cost</span>
      </div>

      <div class="card-body p-4">
        <!-- Kronologi vs Analisis -->
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <div class="p-3 rounded-3 bg-light border h-100">
              <span class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">
                <i class="bi bi-chat-left-text me-1"></i> Kronologi Gejala Dari Anda:
              </span>
              <p id="displayChronology" class="small text-dark fst-italic mb-2">
                -
              </p>
              <div class="small text-muted">
                Metode Serah Terima: <strong id="displayDelivery" class="text-dark">Diantar Sendiri</strong>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="p-3 rounded-3 bg-primary-subtle border border-primary-subtle h-100">
              <span class="text-primary fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">
                <i class="bi bi-search me-1"></i> Hasil Analisis & Diagnosa Teknisi:
              </span>
              <p id="displayDiagnosis" class="small text-dark fw-semibold mb-2">
                Menunggu unit tiba dan diperiksa fisik di meja workshop teknisi.
              </p>
              <div class="small text-secondary">
                Teknisi Pemeriksa: <strong id="displayTechnician" class="text-dark">Budi Santoso (Certified Hardware)</strong>
              </div>
            </div>
          </div>
        </div>

        <!-- Rencana Tindakan -->
        <div class="p-3 rounded-3 bg-light border mb-4">
          <span class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">
            <i class="bi bi-tools me-1"></i> Rencana Tindakan Perbaikan:
          </span>
          <p id="displayActionPlan" class="small text-dark fw-semibold mb-0">
            Akan ditentukan setelah teknisi memeriksa komponen dan jalur tegangan.
          </p>
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
          <a href="{{ url('/invoice') }}" target="_blank" class="btn btn-primary fw-bold px-4 py-2">
            <i class="bi bi-printer me-1"></i> Buka & Cetak Invoice A4
          </a>
        </div>
      </div>
    </div>

  </main>

  <!-- MODAL PEMBAYARAN QRIS -->
  <div class="modal fade" id="qrisModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content border-0 shadow-lg text-center p-3 rounded-4">
        <div class="modal-header border-0 pb-0 justify-content-end">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body pt-0">
          <span class="badge bg-danger mb-2">QRIS Terstandarisasi</span>
          <h5 class="fw-bold mb-1">Scan QRIS</h5>
          <p class="text-muted small mb-3">BCA, Mandiri, GoPay, OVO, Dana, atau ShopeePay</p>

          <div class="p-3 bg-light rounded-3 border d-inline-block mb-3">
            <svg class="img-fluid" style="width: 170px; height: 170px;" viewBox="0 0 100 100" fill="currentColor">
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

          <div class="bg-primary-subtle p-2.5 rounded-3 border border-primary-subtle mb-3">
            <span class="text-muted d-block small">Total Terkunci:</span>
            <span id="modalQrisAmount" class="fs-5 fw-bold font-monospace text-primary">Rp 0</span>
          </div>

          <button onclick="simulatePaymentSuccess()" class="btn btn-success w-100 fw-bold py-2 shadow-sm">
            <i class="bi bi-check-circle me-1"></i> Simulasikan Pembayaran Sukses
          </button>
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
    let currentOrder = null;
    let bsQrisModal = null;

    function searchOtherTicket() {
      const no = prompt("Masukkan Nomor Tiket Servis Anda (Contoh: #SC-2026-0982):", currentOrder ? currentOrder.ticketNo : "#SC-2026-9042");
      if (no && no.trim()) {
        window.location.href = "{{ url('/user-panel') }}?ticket=" + encodeURIComponent(no.trim());
      }
    }

    function loadOrder() {
      const serverTicket = @json($ticket ?? null);

      if (serverTicket) {
        currentOrder = {
          ticketNo: serverTicket.ticket_no,
          createdAt: serverTicket.created_at,
          customerName: serverTicket.customer_name,
          customerPhone: serverTicket.customer_phone,
          deviceType: serverTicket.device_type,
          deviceBrand: serverTicket.device_brand,
          deviceModel: serverTicket.device_model || '',
          issueChronology: serverTicket.issue_chronology,
          addonCleaning: serverTicket.cost_cleaning > 0,
          addonRepasta: serverTicket.cost_repasta > 0,
          deliveryMethod: serverTicket.delivery_method,
          step: serverTicket.step,
          status: serverTicket.status,
          technician: serverTicket.technician ? serverTicket.technician.name : 'Budi Santoso (Hardware Specialist)',
          diagnosis: serverTicket.diagnosis || "Menunggu pemeriksaan fisik teknisi di meja workshop.",
          actionPlan: serverTicket.action_plan || "Akan ditentukan setelah teknisi memeriksa komponen dan jalur tegangan.",
          technicianNote: serverTicket.technician_notes || "Motherboard normal bebas korsleting. Seluruh data SSD terlindungi 100%.",
          costLabor: serverTicket.cost_labor || 0,
          costParts: serverTicket.cost_parts || 0,
          costCleaning: serverTicket.cost_cleaning || 0,
          costRepasta: serverTicket.cost_repasta || 0,
          paymentStatus: serverTicket.payment_status,
          isApproved: serverTicket.is_approved,
          isCompleted: serverTicket.is_completed,
          unitHandedOver: serverTicket.unit_handed_over,
          unitHandedOverTime: serverTicket.unit_handed_over_at,
          isClosed: serverTicket.is_closed,
          invoiceNo: serverTicket.invoice_no
        };
        localStorage.setItem('sercomp_active_order', JSON.stringify(currentOrder));
      } else {
        const stored = localStorage.getItem('sercomp_active_order');
        if (stored) {
          currentOrder = JSON.parse(stored);
        } else {
          currentOrder = {
            ticketNo: "#SC-2026-9042",
            createdAt: new Date().toISOString(),
            customerName: "Dzul Khair Aropian",
            customerPhone: "081298765432",
            deviceType: "Laptop",
            deviceBrand: "Asus",
            deviceModel: "ROG Strix G15",
            issueChronology: "Layar tiba-tiba mati saat rendering tugas, lampu indikator menyala tapi kipas berisik sekali dan laptop panas.",
            addonCleaning: true,
            addonRepasta: true,
            deliveryMethod: "Diantar Sendiri",
            step: 1,
            status: "Menunggu Unit Tiba di Workshop",
            technician: "Budi Santoso (Hardware Specialist)",
            diagnosis: "Menunggu pemeriksaan fisik teknisi di meja workshop.",
            actionPlan: "Akan ditentukan setelah teknisi memeriksa komponen dan jalur tegangan.",
            technicianNote: "Motherboard normal bebas korsleting. Seluruh data SSD terlindungi 100%.",
            costLabor: 0,
            costParts: 0,
            costCleaning: 50000,
            costRepasta: 75000,
            paymentStatus: "Unpaid",
            isApproved: false,
            isCompleted: false,
            unitHandedOver: false,
            unitHandedOverTime: null,
            isClosed: false,
            invoiceNo: "INV-SC-2026-9042"
          };
          localStorage.setItem('sercomp_active_order', JSON.stringify(currentOrder));
        }
      }

      renderUI();
    }

    function renderUI() {
      document.getElementById('displayTicketNo').innerText = currentOrder.ticketNo;
      document.getElementById('displayDevice').innerText = `${currentOrder.deviceType} ${currentOrder.deviceBrand} ${currentOrder.deviceModel}`;
      document.getElementById('displayCustomer').innerText = currentOrder.customerName;
      document.getElementById('displayChronology').innerText = `"${currentOrder.issueChronology}"`;
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

      const total = (currentOrder.costLabor || 0) + (currentOrder.costParts || 0) +
                    (currentOrder.addonCleaning ? (currentOrder.costCleaning || 50000) : 0) +
                    (currentOrder.addonRepasta ? (currentOrder.costRepasta || 75000) : 0);
      const totalStr = "Rp " + total.toLocaleString('id-ID');
      document.getElementById('costTotal').innerText = totalStr;
      document.getElementById('modalQrisAmount').innerText = totalStr;

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

      if (step <= 2) {
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
            <div class="small text-muted">
              <i class="bi bi-info-circle me-1"></i> Teknisi telah merilis rincian biaya. Klik setujui untuk melanjutkan ke pembayaran QRIS.
            </div>
            <div class="d-flex gap-2 text-nowrap">
              <button onclick="handleReject()" class="btn btn-outline-danger fw-semibold px-3 py-2 btn-sm">
                Tolak / Batalkan
              </button>
              <button onclick="handleApprove()" class="btn btn-primary fw-bold px-4 py-2 btn-sm shadow-sm">
                <i class="bi bi-check-circle me-1"></i> Setujui Rincian Biaya
              </button>
            </div>
          </div>
        `;
        document.getElementById('badgeStatus').className = "badge badge-warning-accessible px-2.5 py-1.5 fw-bold";
      } else if (step === 4) {
        actionContainer.innerHTML = `
          <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 w-100 p-2.5 bg-light rounded-3 border">
            <div class="small text-dark fw-semibold">
              <i class="bi bi-qr-code text-primary fs-5 me-1"></i> Rincian biaya telah disetujui. Silakan selesaikan pembayaran:
            </div>
            <button onclick="openQrisModal()" class="btn btn-success fw-bold px-4 py-2 btn-sm shadow-sm">
              <i class="bi bi-qr-code-scan me-1"></i> Bayar Sekarang via QRIS (${totalStr})
            </button>
          </div>
        `;
        document.getElementById('badgeStatus').className = "badge badge-warning-accessible px-2.5 py-1.5 fw-bold";
      } else if (step === 5) {
        actionContainer.innerHTML = `
          <div class="alert alert-success d-flex align-items-center gap-2 mb-0 py-2.5 px-3 small w-100">
            <i class="bi bi-check-circle-fill fs-4 text-success"></i>
            <div>
              <strong class="d-block text-dark">Pembayaran QRIS Terverifikasi Lunas:</strong>
              <span>Teknisi sedang melakukan proses perbaikan hardware di workshop.</span>
            </div>
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
        document.getElementById('invAccessNo').innerText = currentOrder.invoiceNo || "INV-SC-2026-8812";
      } else if (step === 7 || currentOrder.isClosed) {
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

      // Handover Notice & Timer
      if (currentOrder.unitHandedOver && !currentOrder.isClosed) {
        document.getElementById('handoverNotice').classList.remove('d-none');
        document.getElementById('badgeStatus').className = "badge badge-warning-accessible px-2.5 py-1.5 fw-bold";
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
      fetch('/user-panel/' + encodeURIComponent(currentOrder.ticketNo) + '/approve', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(data => {
        currentOrder.isApproved = true;
        currentOrder.step = 4;
        currentOrder.status = "Menunggu Pembayaran QRIS";
        localStorage.setItem('sercomp_active_order', JSON.stringify(currentOrder));
        alert("Estimasi biaya disetujui! Silakan lanjutkan pembayaran via QRIS.");
        renderUI();
        openQrisModal();
      })
      .catch(err => {
        currentOrder.isApproved = true;
        currentOrder.step = 4;
        currentOrder.status = "Menunggu Pembayaran QRIS";
        localStorage.setItem('sercomp_active_order', JSON.stringify(currentOrder));
        renderUI();
        openQrisModal();
      });
    }

    function simulatePaymentSuccess() {
      fetch('/user-panel/' + encodeURIComponent(currentOrder.ticketNo) + '/pay-qris', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(data => {
        currentOrder.paymentStatus = "Paid";
        currentOrder.isApproved = true;
        currentOrder.step = 5;
        currentOrder.status = "Sedang Dikerjakan";
        localStorage.setItem('sercomp_active_order', JSON.stringify(currentOrder));

        if (bsQrisModal) bsQrisModal.hide();
        alert("Pembayaran QRIS Berhasil Diverifikasi! Teknisi segera memulai proses pengerjaan hardware.");
        renderUI();
      })
      .catch(err => {
        currentOrder.paymentStatus = "Paid";
        currentOrder.isApproved = true;
        currentOrder.step = 5;
        currentOrder.status = "Sedang Dikerjakan";
        localStorage.setItem('sercomp_active_order', JSON.stringify(currentOrder));

        if (bsQrisModal) bsQrisModal.hide();
        alert("Pembayaran QRIS Berhasil Diverifikasi! Teknisi segera memulai proses pengerjaan hardware.");
        renderUI();
      });
    }

    function handleReject() {
      if (confirm("Apakah Anda yakin ingin membatalkan servis? Unit akan dirakit kembali tanpa biaya reparasi.")) {
        fetch('/user-panel/' + encodeURIComponent(currentOrder.ticketNo) + '/reject', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          }
        });
        currentOrder.status = "Dibatalkan Pelanggan";
        localStorage.setItem('sercomp_active_order', JSON.stringify(currentOrder));
        alert("Pengerjaan dibatalkan.");
        renderUI();
      }
    }

    function confirmCloseTicket() {
      fetch('/user-panel/' + encodeURIComponent(currentOrder.ticketNo) + '/close', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(data => {
        currentOrder.isClosed = true;
        currentOrder.step = 7;
        currentOrder.status = "Closed / Completed";
        localStorage.setItem('sercomp_active_order', JSON.stringify(currentOrder));
        alert("Terima kasih! Tiket servis resmi ditutup. Garansi e-warranty 90 hari aktif!");
        renderUI();
      })
      .catch(err => {
        currentOrder.isClosed = true;
        currentOrder.step = 7;
        currentOrder.status = "Closed / Completed";
        localStorage.setItem('sercomp_active_order', JSON.stringify(currentOrder));
        alert("Terima kasih! Tiket servis resmi ditutup. Garansi e-warranty 90 hari aktif!");
        renderUI();
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

    window.onload = loadOrder;
  </script>
</body>
</html>
