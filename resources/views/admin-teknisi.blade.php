<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SerComp - Panel Kerja Teknisi & Administrasi Workshop</title>
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
    }
    .card-tech {
      background-color: #ffffff;
      border: 1px solid var(--sc-slate-200);
      border-radius: 12px;
      box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.05);
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">

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
        <a href="{{ url('/user-panel') }}" class="btn-demo">3. Portal Pelanggan</a>
        <a href="{{ url('/teknisi') }}" class="btn-demo active">4. Panel Teknisi</a>
        <a href="{{ url('/invoice') }}" class="btn-demo">5. Faktur Invoice</a>
      </div>
    </div>
  </div>

  <!-- TOP BAR (LIGHT THEME) -->
  <header class="navbar navbar-expand-lg bg-white border-bottom sticky-top px-3 py-2 shadow-sm">
    <div class="container-fluid d-flex flex-wrap align-items-center justify-content-between gap-2">
      <div class="d-flex align-items-center gap-2">
        <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none">
          <div class="brand-box">SC</div>
          <div>
            <span class="fw-bold fs-5 text-dark">Ser<span class="text-primary">Comp</span></span>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle text-uppercase ms-1" style="font-size: 10px;">Workshop & Teknisi</span>
          </div>
        </a>
      </div>

      <div class="d-flex align-items-center gap-2">
        <span class="small text-muted me-2 d-none d-md-inline">
          <i class="bi bi-person-badge me-1"></i> Teknisi: <strong>Budi Santoso (Hardware)</strong>
        </span>
        <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary">
          <i class="bi bi-house me-1"></i> Beranda
        </a>
        <a href="{{ url('/user-panel') }}" class="btn btn-sm btn-primary fw-semibold">
          <i class="bi bi-person-circle me-1"></i> Buka Tampilan User
        </a>
        <a href="{{ url('/teknisi/login') }}" class="btn btn-sm btn-outline-danger">
          <i class="bi bi-box-arrow-right"></i> Keluar
        </a>
      </div>
    </div>
  </header>

  <!-- MAIN DASHBOARD -->
  <main class="container-fluid px-4 py-4 flex-grow-1">

    <!-- 1. STATS SUMMARY (LIGHT) -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md">
        <div class="card card-tech p-3 h-100">
          <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Antrean Masuk</span>
          <div class="fs-4 fw-bold text-dark mt-1">12 Unit</div>
          <span class="text-primary small fw-semibold">Laptop & PC</span>
        </div>
      </div>
      <div class="col-6 col-md">
        <div class="card card-tech p-3 h-100">
          <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Tahap Analisa</span>
          <div class="fs-4 fw-bold text-warning mt-1">3 Unit</div>
          <span class="text-muted small">Di Meja Workshop</span>
        </div>
      </div>
      <div class="col-6 col-md">
        <div class="card card-tech p-3 h-100">
          <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Menunggu QRIS</span>
          <div class="fs-4 fw-bold text-primary mt-1">4 Unit</div>
          <span class="text-muted small">Approval Pelanggan</span>
        </div>
      </div>
      <div class="col-6 col-md">
        <div class="card card-tech p-3 h-100">
          <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Troubleshooting</span>
          <div class="fs-4 fw-bold text-success mt-1">3 Unit</div>
          <span class="text-success small fw-semibold">Lunas QRIS</span>
        </div>
      </div>
      <div class="col-12 col-md">
        <div class="card card-tech p-3 h-100">
          <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Selesai / SLA Pasca-Terima</span>
          <div class="fs-4 fw-bold text-info mt-1">2 Unit</div>
          <span class="text-muted small">Timer 1x24 Jam</span>
        </div>
      </div>
    </div>

    <!-- BANNER OTOMASI SLA 1X24 JAM PASCA-TERIMA -->
    <div class="alert alert-warning border border-warning-subtle bg-warning-subtle text-dark rounded-4 p-3 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 shadow-sm">
      <div class="d-flex align-items-center gap-3">
        <div class="badge bg-warning text-dark p-2 rounded-circle fs-4">
          <i class="bi bi-clock-history"></i>
        </div>
        <div>
          <strong class="d-block small text-uppercase text-dark">Logika SLA 1x24 Jam Pasca-Penyerahan Unit:</strong>
          <span class="small text-secondary">
            Setelah unit diserahkan teknisi ke pelanggan, sistem menjalankan hitung mundur 1x24 jam. Apabila pelanggan lupa menutup tiket secara manual, scheduler sistem otomatis mengubah tiket menjadi <b>Closed / Completed</b>.
          </span>
        </div>
      </div>
      <button onclick="triggerSlaTimeout()" class="btn btn-warning btn-sm fw-bold text-nowrap px-3 py-2 shadow-sm">
        <i class="bi bi-fast-forward me-1"></i> Simulasi 1x24 Jam Lewat (Auto-Close)
      </button>
    </div>

    <!-- 2. WORKSPACE TIKET TERPILIH (2 KOLOM) -->
    <div class="row g-4">

      <!-- KOLOM KIRI: DETAIL INPUT USER & KRONOLOGI (5 Kolom) -->
      <div class="col-lg-5">
        <div class="card card-tech rounded-4 p-4 shadow-sm mb-4">
          <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
            <div>
              <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Detail Tiket Aktif</span>
              <h5 id="tekTicketNo" class="fw-bold text-primary font-monospace mb-0">#SC-2026-9042</h5>
            </div>
            <span id="tekBadgeStatus" class="badge bg-warning text-dark px-2.5 py-1.5 fw-bold">
              Menunggu Approval Biaya
            </span>
          </div>

          <!-- Perangkat & Pelanggan -->
          <div class="row g-2 mb-3">
            <div class="col-6">
              <div class="p-2.5 rounded-3 bg-light border">
                <span class="text-muted text-uppercase fw-bold d-block" style="font-size: 10px;">Perangkat:</span>
                <strong id="tekDevice" class="small text-dark">Laptop Asus ROG</strong>
              </div>
            </div>
            <div class="col-6">
              <div class="p-2.5 rounded-3 bg-light border">
                <span class="text-muted text-uppercase fw-bold d-block" style="font-size: 10px;">Pemilik Unit:</span>
                <strong id="tekCustomer" class="small text-dark">Dzul Khair (081298765432)</strong>
              </div>
            </div>
          </div>

          <!-- Kronologi dari User -->
          <div class="p-3 rounded-3 bg-light border mb-3">
            <span class="text-primary text-uppercase fw-bold d-block mb-1" style="font-size: 10px;">
              <i class="bi bi-card-text me-1"></i> Kronologi Kerusakan Dari Konsumen:
            </span>
            <p id="tekChronology" class="small text-secondary fst-italic mb-0">
              "Layar tiba-tiba mati saat rendering tugas, lampu indikator menyala tapi kipas berisik sekali dan laptop panas."
            </p>
          </div>

          <!-- Pengantaran & Add-on -->
          <div class="small mb-3 space-y-1">
            <div class="d-flex justify-content-between p-2 rounded-2 bg-light border mb-1">
              <span class="text-muted">Metode Pengantaran:</span>
              <strong id="tekDelivery" class="text-dark">Diantar Sendiri</strong>
            </div>
            <div class="d-flex justify-content-between p-2 rounded-2 bg-light border mb-1">
              <span class="text-muted">Add-on Deep Cleaning:</span>
              <strong id="tekAddonCleaning" class="text-primary">Ya (+Rp 50.000)</strong>
            </div>
            <div class="d-flex justify-content-between p-2 rounded-2 bg-light border">
              <span class="text-muted">Add-on Repasta Arctic:</span>
              <strong id="tekAddonRepasta" class="text-primary">Ya (+Rp 75.000)</strong>
            </div>
          </div>

          <!-- Status Pembayaran QRIS User -->
          <div id="tekPaymentBox" class="p-3 rounded-3 border border-warning-subtle bg-warning-subtle text-dark small mb-3">
            <span class="text-uppercase fw-bold d-block mb-1 text-muted" style="font-size: 10px;">Status Pembayaran QRIS Konsumen:</span>
            <div id="tekPaymentText" class="fw-bold d-flex align-items-center gap-2">
              <span class="spinner-grow spinner-grow-sm text-warning" role="status"></span>
              <span>Menunggu Pelanggan Menyetujui Biaya & Bayar QRIS</span>
            </div>
            <span class="text-secondary d-block mt-1" style="font-size: 11px;">SOP Workshop: Teknisi dilarang membongkar motherboard sebelum pembayaran QRIS diverifikasi.</span>
          </div>

          <!-- Serah Terima Fisik Button -->
          <div class="p-3 rounded-3 bg-light border">
            <span class="text-muted text-uppercase fw-bold d-block mb-2" style="font-size: 10px;">
              Penyerahan Fisik Unit ke Pelanggan:
            </span>
            <button id="btnHandover" onclick="handoverUnitToCustomer()" class="btn btn-outline-primary w-100 btn-sm fw-bold">
              <i class="bi bi-box-seam me-1"></i> Tandai Unit Telah Diserahkan (Mulai Timer SLA 1x24 Jam)
            </button>
            <div id="handoverStatusText" class="small text-muted mt-2 text-center" style="font-size: 11px;">
              Unit belum diserahkan ke pelanggan.
            </div>
          </div>
        </div>
      </div>

      <!-- KOLOM KANAN: FORM LEMBAR PERSETUJUAN & DOKUMENTASI (7 Kolom) -->
      <div class="col-lg-7">

        <!-- FORM 1: UPDATE LEMBAR PERSETUJUAN -->
        <div class="card card-tech rounded-4 p-4 shadow-sm mb-4">
          <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
            <div>
              <h6 class="fw-bold text-dark mb-0"><i class="bi bi-clipboard-check me-2 text-primary"></i>Form Lembar Persetujuan Teknisi</h6>
              <span class="text-muted small">Data akan tampil pada portal pelanggan bersama rincian biaya terkunci & pop-up QRIS.</span>
            </div>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Step 2 - 3</span>
          </div>

          <div class="row g-3 small">
            <!-- Kerusakan -->
            <div class="col-12">
              <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Hasil Diagnosa Kerusakan Fisik:</label>
              <textarea id="inputDiagnosis" rows="2" class="form-control bg-light small"></textarea>
            </div>

            <!-- Tindakan Perbaikan -->
            <div class="col-12">
              <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Rencana Tindakan Perbaikan Yang Akan Diambil:</label>
              <textarea id="inputActionPlan" rows="2" class="form-control bg-light small"></textarea>
            </div>

            <!-- Biaya Jasa & Sparepart -->
            <div class="col-md-6">
              <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Estimasi Jasa Teknisi (Rp):</label>
              <input type="number" id="inputLabor" class="form-control bg-light small">
            </div>
            <div class="col-md-6">
              <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Estimasi Suku Cadang Baru (Rp):</label>
              <input type="number" id="inputParts" class="form-control bg-light small">
            </div>

            <!-- Catatan Khusus Teknisi (Note) -->
            <div class="col-12">
              <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Catatan Khusus Teknisi (Note):</label>
              <input type="text" id="inputNote" class="form-control bg-light small">
            </div>

            <div class="col-12">
              <button onclick="saveApprovalSheet()" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                <i class="bi bi-save me-1"></i> Perbarui & Kirim Lembar Persetujuan ke Pengguna
              </button>
            </div>
          </div>
        </div>

        <!-- FORM 2: PELAPORAN HASIL TROUBLESHOOTING & LAMPIRAN BUKTI PART DIGANTI -->
        <div class="card card-tech rounded-4 p-4 shadow-sm">
          <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
            <div>
              <h6 class="fw-bold text-dark mb-0"><i class="bi bi-camera me-2 text-success"></i>Lampiran Bukti Selesai Servis & Cetak Invoice</h6>
              <span class="text-muted small">Wajib melampirkan foto laptop menyala normal dan foto suku cadang lama yang diganti.</span>
            </div>
            <span class="badge bg-success-subtle text-success border border-success-subtle">Step 5 - 6</span>
          </div>

          <div class="row g-3 mb-3">
            <!-- Bukti Foto Laptop Nyala Normal -->
            <div class="col-md-6">
              <div class="p-3 rounded-3 bg-light border text-center">
                <span class="text-primary fw-bold text-uppercase d-block mb-2" style="font-size: 10px;">Bukti 1: Unit Nyala Normal (Running Test)</span>
                <div class="rounded-2 overflow-hidden bg-secondary-subtle mb-2 border" style="height: 140px;">
                  <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&auto=format&fit=crop&q=80" class="w-100 h-100 object-fit-cover" alt="Laptop Running Test">
                </div>
                <span class="badge bg-success-subtle text-success small">Uji Bebas Throttling 68°C</span>
              </div>
            </div>

            <!-- Bukti Foto Part Lama Yang Diganti -->
            <div class="col-md-6">
              <div class="p-3 rounded-3 bg-light border text-center">
                <span class="text-primary fw-bold text-uppercase d-block mb-2" style="font-size: 10px;">Bukti 2: Modul Fan Rusak Lama</span>
                <div class="rounded-2 overflow-hidden bg-secondary-subtle mb-2 border" style="height: 140px;">
                  <img src="https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=600&auto=format&fit=crop&q=80" class="w-100 h-100 object-fit-cover" alt="Fan Rusak">
                </div>
                <span class="badge bg-secondary-subtle text-dark small">Part Diserahkan ke User</span>
              </div>
            </div>
          </div>

          <!-- Tombol Selesaikan Servis & Terbitkan Invoice -->
          <button id="btnComplete" onclick="completeServiceOrder()" class="btn btn-success w-100 fw-bold py-2.5 shadow-sm">
            <i class="bi bi-check2-circle me-1"></i> Selesaikan Servis, Lampirkan Bukti & Terbitkan Invoice
          </button>
        </div>

      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <footer class="bg-white text-muted py-3 text-center border-top small">
    SerComp Workshop Control Panel • Standard Operating Procedure (SOP) Perbaikan Terpadu • UNM 2026
  </footer>

  <!-- Bootstrap 5.3 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    let order = null;

    function initTeknisi() {
      const stored = localStorage.getItem('sercomp_active_order');
      if (stored) {
        order = JSON.parse(stored);
      } else {
        order = {
          ticketNo: "#SC-2026-9042",
          customerName: "Dzul Khair Aropian",
          customerPhone: "081298765432",
          deviceType: "Laptop",
          deviceBrand: "Asus",
          deviceModel: "ROG Strix G15 (Core i7 / RTX 3060)",
          issueChronology: "Layar tiba-tiba mati saat rendering tugas, lampu indikator menyala tapi kipas berisik sekali dan laptop panas.",
          addonCleaning: true,
          addonRepasta: true,
          deliveryMethod: "Diantar Sendiri",
          step: 3,
          status: "Menunggu Persetujuan Biaya",
          diagnosis: "Kipas pendingin (fan cooler) aus & bearing macet, menyebabkan CPU thermal throttling darurat.",
          actionPlan: "1) Penggantian unit modul fan cooler original baru; 2) Deep cleaning debu heatsink; 3) Repasta thermal Arctic MX-4; 4) Running test suhu 60 menit.",
          technicianNote: "Motherboard normal bebas korsleting. Seluruh data SSD terlindungi 100%.",
          costLabor: 150000,
          costParts: 220000,
          costCleaning: 50000,
          costRepasta: 75000,
          paymentStatus: "Unpaid",
          isApproved: false,
          isCompleted: false,
          unitHandedOver: false,
          unitHandedOverTime: null,
          isClosed: false,
          evidenceLaptopFixed: "https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&auto=format&fit=crop&q=80",
          evidenceReplacedPart: "https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=600&auto=format&fit=crop&q=80",
          invoiceNo: "INV-SC-2026-8812"
        };
        localStorage.setItem('sercomp_active_order', JSON.stringify(order));
      }

      renderTeknisi();
    }

    function renderTeknisi() {
      document.getElementById('tekTicketNo').innerText = order.ticketNo;
      document.getElementById('tekBadgeStatus').innerText = order.status;
      document.getElementById('tekDevice').innerText = `${order.deviceType} ${order.deviceBrand} ${order.deviceModel}`;
      document.getElementById('tekCustomer').innerText = `${order.customerName} (${order.customerPhone})`;
      document.getElementById('tekChronology').innerText = `"${order.issueChronology}"`;
      document.getElementById('tekDelivery').innerText = order.deliveryMethod;

      document.getElementById('tekAddonCleaning').innerText = order.addonCleaning ? "Ya (+Rp 50.000)" : "Tidak";
      document.getElementById('tekAddonRepasta').innerText = order.addonRepasta ? "Ya (+Rp 75.000)" : "Tidak";

      // Form 1 Input Values
      document.getElementById('inputDiagnosis').value = order.diagnosis || "";
      document.getElementById('inputActionPlan').value = order.actionPlan || "";
      document.getElementById('inputLabor').value = order.costLabor || 0;
      document.getElementById('inputParts').value = order.costParts || 0;
      document.getElementById('inputNote').value = order.technicianNote || "";

      // Pembayaran Status
      const payBox = document.getElementById('tekPaymentBox');
      const payText = document.getElementById('tekPaymentText');

      if (order.paymentStatus === 'Paid') {
        payBox.className = "p-3 rounded-3 border border-success-subtle bg-success-subtle text-dark small mb-3";
        payText.innerHTML = '<i class="bi bi-check-circle-fill text-success fs-5"></i> <span class="fw-bold text-success">Lunas QRIS - Teknisi Diizinkan Bongkar & Selesaikan Unit</span>';
      } else {
        payBox.className = "p-3 rounded-3 border border-warning-subtle bg-warning-subtle text-dark small mb-3";
        payText.innerHTML = '<span class="spinner-grow spinner-grow-sm text-warning" role="status"></span> <span>Menunggu Pelanggan Menyetujui Biaya & Bayar QRIS</span>';
      }

      // Handover status
      const btnHandover = document.getElementById('btnHandover');
      const textHandover = document.getElementById('handoverStatusText');
      if (order.unitHandedOver) {
        btnHandover.disabled = true;
        btnHandover.className = "btn btn-secondary w-100 btn-sm";
        btnHandover.innerHTML = '<i class="bi bi-check-all me-1"></i> Unit Telah Diserahkan Fisik ke Pelanggan';
        textHandover.innerHTML = `<span class="text-success fw-bold"><i class="bi bi-check-circle me-1"></i>Diserahkan pada: ${new Date(order.unitHandedOverTime).toLocaleTimeString('id-ID')}</span>`;
      } else {
        btnHandover.disabled = false;
        btnHandover.className = "btn btn-outline-primary w-100 btn-sm fw-bold";
        btnHandover.innerHTML = '<i class="bi bi-box-seam me-1"></i> Tandai Unit Telah Diserahkan (Mulai Timer SLA 1x24 Jam)';
        textHandover.innerText = "Unit belum diserahkan ke pelanggan.";
      }
    }

    function saveApprovalSheet() {
      order.diagnosis = document.getElementById('inputDiagnosis').value;
      order.actionPlan = document.getElementById('inputActionPlan').value;
      order.costLabor = parseInt(document.getElementById('inputLabor').value) || 0;
      order.costParts = parseInt(document.getElementById('inputParts').value) || 0;
      order.technicianNote = document.getElementById('inputNote').value;
      order.step = 3;
      order.status = "Menunggu Persetujuan Biaya";

      localStorage.setItem('sercomp_active_order', JSON.stringify(order));
      alert("Lembar Persetujuan berhasil diperbarui dan disinkronkan ke Portal Pelanggan!");
      renderTeknisi();
    }

    function handoverUnitToCustomer() {
      if (confirm("Konfirmasi bahwa unit fisik telah diserahterimakan kepada pelanggan? Timer masa uji SLA 1x24 jam akan langsung berjalan.")) {
        order.unitHandedOver = true;
        order.unitHandedOverTime = new Date().toISOString();
        order.step = 6;
        order.status = "Unit Diterima - Menunggu Tutup Tiket";
        localStorage.setItem('sercomp_active_order', JSON.stringify(order));
        alert("Unit ditandai telah diserahkan. Timer SLA 1x24 jam aktif di portal pelanggan!");
        renderTeknisi();
      }
    }

    function completeServiceOrder() {
      order.isCompleted = true;
      order.step = 6;
      order.status = "Selesai Dikerjakan - Siap Diserahkan";
      localStorage.setItem('sercomp_active_order', JSON.stringify(order));
      alert("Pengerjaan reparasi selesai! Bukti terlampir dan invoice resmi siap dicetak.");
      renderTeknisi();
    }

    function triggerSlaTimeout() {
      if (confirm("Simulasikan waktu pengujian 1x24 jam pasca-penyerahan telah habis tanpa komplain pelanggan?")) {
        order.unitHandedOver = true;
        order.unitHandedOverTime = new Date(Date.now() - (25 * 3600 * 1000)).toISOString(); // 25 jam yang lalu
        order.isClosed = true;
        order.status = "Auto-Closed by SLA (1x24 Jam Lewat Pasca-Terima Unit)";
        localStorage.setItem('sercomp_active_order', JSON.stringify(order));
        alert("SLA 1x24 Jam Lewat: Tiket otomatis ditutup (Auto-Closed) dan e-Warranty 90 hari resmi diaktifkan!");
        renderTeknisi();
      }
    }

    window.onload = initTeknisi;
  </script>
</body>
</html>
