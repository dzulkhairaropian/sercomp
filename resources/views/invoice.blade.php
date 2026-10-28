<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice Resmi - SerComp Service Computer</title>
  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Font Plus Jakarta Sans -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- SerComp Theme -->
  <link rel="stylesheet" href="{{ asset('css/sercomp-theme.css') }}">
  <style>
    body {
      background-color: var(--sc-slate-50);
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: #212529;
    }
    .invoice-card {
      max-width: 850px;
      margin: 30px auto;
      background: #ffffff;
      border: 1px solid var(--sc-slate-200);
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.06);
      padding: 40px;
      position: relative;
    }
    .stamp-paid {
      position: absolute;
      top: 130px;
      right: 50px;
      border: 3px dashed var(--sc-emerald);
      color: var(--sc-emerald);
      padding: 8px 24px;
      font-weight: 900;
      font-size: 24px;
      text-transform: uppercase;
      letter-spacing: 2px;
      transform: rotate(-10deg);
      border-radius: 8px;
      opacity: 0.85;
      pointer-events: none;
    }
    .brand-logo-box {
      width: 44px;
      height: 44px;
      background-color: var(--sc-navy);
      color: #38bdf8;
      font-weight: 900;
      font-size: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
    }
    @media print {
      body {
        background-color: #ffffff;
      }
      .no-print {
        display: none !important;
      }
      .invoice-card {
        margin: 0;
        padding: 20px;
        border: none;
        box-shadow: none;
        max-width: 100%;
      }
    }
  </style>
</head>
<body>

  <!-- TOP PROTOTYPE DEMO BAR (Screen Only) -->
  <div class="no-print sc-demo-bar">
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
        <a href="{{ url('/teknisi') }}" class="btn-demo">4. Panel Teknisi</a>
        <a href="{{ url('/invoice') }}" class="btn-demo active">5. Faktur Invoice</a>
      </div>
    </div>
  </div>

  <!-- Top Action Bar (Screen Only) -->
  <div class="container no-print mt-3 mb-2" style="max-width: 850px;">
    <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 border shadow-sm">
      <div class="d-flex align-items-center gap-2">
        <a href="/user-panel" class="btn btn-sm btn-outline-secondary">
          <i class="bi bi-arrow-left"></i> Kembali ke Portal User
        </a>
        <span class="badge text-bg-success px-2.5 py-1.5 fs-7">
          <i class="bi bi-check-circle-fill me-1"></i> Transaksi Selesai & Lunas
        </span>
      </div>
      <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-sm btn-primary">
          <i class="bi bi-printer me-1"></i> Cetak / Unduh PDF (A4)
        </button>
      </div>
    </div>
  </div>

  <!-- INVOICE SHEET -->
  <div class="invoice-card">
    <!-- Watermark Stempel Lunas -->
    <div class="stamp-paid">
      LUNAS (PAID)
    </div>

    <!-- Header Perusahaan & No Invoice -->
    <div class="row align-items-start border-bottom pb-4 mb-4">
      <div class="col-sm-7">
        <div class="d-flex align-items-center gap-3 mb-2">
          <div class="brand-logo-box">SC</div>
          <div>
            <h4 class="fw-bold mb-0 text-dark tracking-tight">Ser<span class="text-primary">Comp</span></h4>
            <span class="text-muted small fw-semibold">PT SerComp Digital Solusindo</span>
          </div>
        </div>
        <p class="text-muted small mb-0">
          Workshop Hub Jakarta Barat • Sentra Bisnis Teknologi UNM<br>
          WhatsApp Hotline: 0812-9876-5432 • Surel: finance@sercomp.id
        </p>
      </div>
      <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
        <span class="badge text-bg-dark text-uppercase px-2.5 py-1 mb-1">Faktur Resmi Penjualan & Jasa</span>
        <h5 class="fw-bold text-primary font-monospace mb-0" id="invNo">INV-SC-2026-8812</h5>
        <div class="text-muted small mt-1">Tanggal: <span id="invDate" class="fw-semibold">27 Oktober 2026</span></div>
        <div class="text-muted small">No. Tiket: <span id="invTicket" class="fw-bold text-dark">#SC-2026-9042</span></div>
      </div>
    </div>

    <!-- Informasi Pelanggan & Perangkat -->
    <div class="bg-light p-3 rounded-3 border mb-4">
      <div class="row g-3 small">
        <div class="col-6 col-md-3">
          <span class="text-muted d-block text-uppercase fw-bold" style="font-size: 11px;">Pelanggan:</span>
          <span class="fw-bold text-dark" id="invCustomer">Dzul Khair Aropian</span>
        </div>
        <div class="col-6 col-md-3">
          <span class="text-muted d-block text-uppercase fw-bold" style="font-size: 11px;">Kontak WhatsApp:</span>
          <span class="fw-bold text-dark" id="invPhone">0812-9876-5432</span>
        </div>
        <div class="col-6 col-md-3">
          <span class="text-muted d-block text-uppercase fw-bold" style="font-size: 11px;">Perangkat Servis:</span>
          <span class="fw-bold text-dark" id="invDevice">Laptop Asus ROG Strix G15</span>
        </div>
        <div class="col-6 col-md-3">
          <span class="text-muted d-block text-uppercase fw-bold" style="font-size: 11px;">Metode Serah Terima:</span>
          <span class="fw-bold text-primary" id="invDelivery">Diantar Sendiri ke Workshop</span>
        </div>
      </div>
    </div>

    <!-- Tabel Rincian Biaya -->
    <div class="table-responsive mb-4">
      <table class="table table-bordered align-middle small mb-0">
        <thead class="table-light">
          <tr>
            <th scope="col" style="width: 5%;">No</th>
            <th scope="col" style="width: 55%;">Deskripsi Layanan & Suku Cadang</th>
            <th scope="col" class="text-center" style="width: 10%;">Qty</th>
            <th scope="col" class="text-end" style="width: 15%;">Harga Satuan</th>
            <th scope="col" class="text-end" style="width: 15%;">Subtotal (Rp)</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-center fw-bold">1</td>
            <td>
              <div class="fw-bold text-dark">Jasa Servis & Bongkar Pasang Heatsink Modul</div>
              <div class="text-muted" style="font-size: 11px;">Penanganan teknisi bersertifikat, diagnosa hardware & thermal stress test</div>
            </td>
            <td class="text-center">1</td>
            <td class="text-end font-monospace" id="invLaborCost">Rp 150.000</td>
            <td class="text-end font-monospace fw-semibold" id="invLaborSubtotal">Rp 150.000</td>
          </tr>
          <tr>
            <td class="text-center fw-bold">2</td>
            <td>
              <div class="fw-bold text-dark">Modul Fan Cooler Original DC 5V Brushless</div>
              <div class="text-muted" style="font-size: 11px;">Penggantian part baru original (Garansi Resmi 90 Hari)</div>
            </td>
            <td class="text-center">1</td>
            <td class="text-end font-monospace" id="invPartsCost">Rp 220.000</td>
            <td class="text-end font-monospace fw-semibold" id="invPartsSubtotal">Rp 220.000</td>
          </tr>
          <tr id="invCleaningRow">
            <td class="text-center fw-bold">3</td>
            <td>
              <div class="fw-bold text-dark">Additional: Deep Cleaning & Dust Heatsink Removal</div>
              <div class="text-muted" style="font-size: 11px;">Pembersihan total debu kipas, kisi-kisi pembuangan, dan motherboard</div>
            </td>
            <td class="text-center">1</td>
            <td class="text-end font-monospace" id="invCleaningCost">Rp 50.000</td>
            <td class="text-end font-monospace fw-semibold" id="invCleaningSubtotal">Rp 50.000</td>
          </tr>
          <tr id="invRepastaRow">
            <td class="text-center fw-bold">4</td>
            <td>
              <div class="fw-bold text-dark">Additional: Repasta Thermal Arctic MX-4 Premium</div>
              <div class="text-muted" style="font-size: 11px;">Penggantian pasta konduktor panas CPU & GPU grade tinggi</div>
            </td>
            <td class="text-center">1</td>
            <td class="text-end font-monospace" id="invRepastaCost">Rp 75.000</td>
            <td class="text-end font-monospace fw-semibold" id="invRepastaSubtotal">Rp 75.000</td>
          </tr>
        </tbody>
        <tfoot>
          <tr class="table-light">
            <td colspan="4" class="text-end fw-bold text-uppercase fs-6">Total Tagihan Bersih (Net Total):</td>
            <td class="text-end fw-bold text-primary fs-6 font-monospace" id="invGrandTotal">Rp 495.000</td>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- Informasi Pembayaran & E-Warranty -->
    <div class="row g-3 small mb-4">
      <div class="col-md-6">
        <div class="border rounded-3 p-3 bg-light h-100">
          <span class="fw-bold text-dark d-block mb-1"><i class="bi bi-shield-check text-success me-1"></i> Data Verifikasi Pembayaran</span>
          <table class="table table-borderless table-sm mb-0 text-muted" style="font-size: 12px;">
            <tr>
              <td style="width: 45%;">Metode Pembayaran</td>
              <td>: <span class="fw-bold text-dark">QRIS Dinamis</span></td>
            </tr>
            <tr>
              <td>Waktu Pembayaran</td>
              <td>: <span class="fw-bold text-dark" id="invPaidAt">27 Okt 2026, 14:22 WIB</span></td>
            </tr>
            <tr>
              <td>Status Transaksi</td>
              <td>: <span class="badge text-bg-success">PAID & SETTLED</span></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="col-md-6">
        <div class="border rounded-3 p-3 bg-light h-100">
          <span class="fw-bold text-dark d-block mb-1"><i class="bi bi-patch-check text-primary me-1"></i> Sertifikat Garansi Elektronik (E-Warranty)</span>
          <table class="table table-borderless table-sm mb-0 text-muted" style="font-size: 12px;">
            <tr>
              <td style="width: 45%;">Kode Garansi</td>
              <td>: <span class="fw-bold font-monospace text-primary" id="invWarrantyCode">#WAR-2026-9904</span></td>
            </tr>
            <tr>
              <td>Masa Berlaku</td>
              <td>: <span class="fw-bold text-dark">90 Hari Kalender (s.d. 25 Jan 2027)</span></td>
            </tr>
            <tr>
              <td>Ketentuan Klaim</td>
              <td>: <span class="text-dark">Klaim tanpa nota kertas via portal SerComp</span></td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <!-- Bukti Pengerjaan Dokumentasi Part -->
    <div class="border-top pt-3 small text-muted">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <span class="fw-bold text-dark d-block">Catatan Pengembalian Unit:</span>
          <span>1. Suku cadang lama yang rusak disertakan dalam dus pengembalian kepada pelanggan.</span><br>
          <span>2. Tiket otomatis diselesaikan (Closed) melalui SLA 1x24 jam setelah unit diserah-terimakan.</span>
        </div>
        <div class="text-end" style="width: 200px;">
          <p class="mb-4">Disahkan secara sistem oleh,<br><strong>Finance SerComp</strong></p>
          <div class="text-muted small">[ Digital Verified ]</div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function loadInvoiceData() {
      const serverTicket = @json($ticket ?? null);
      let order = null;

      if (serverTicket) {
        order = {
          invoiceNo: serverTicket.invoice_no,
          ticketNo: serverTicket.ticket_no,
          customerName: serverTicket.customer_name,
          customerPhone: serverTicket.customer_phone,
          deviceType: serverTicket.device_type,
          deviceBrand: serverTicket.device_brand,
          deviceModel: serverTicket.device_model || '',
          deliveryMethod: serverTicket.delivery_method,
          costLabor: serverTicket.cost_labor || 150000,
          costParts: serverTicket.cost_parts || 220000,
          addonCleaning: serverTicket.cost_cleaning > 0,
          costCleaning: serverTicket.cost_cleaning || 0,
          addonRepasta: serverTicket.cost_repasta > 0,
          costRepasta: serverTicket.cost_repasta || 0
        };
      } else {
        const stored = localStorage.getItem('sercomp_active_order');
        if (stored) {
          order = JSON.parse(stored);
        }
      }

      if (order) {
        if (order.invoiceNo) document.getElementById('invNo').innerText = order.invoiceNo;
        if (order.ticketNo) document.getElementById('invTicket').innerText = order.ticketNo;
        if (order.customerName) document.getElementById('invCustomer').innerText = order.customerName;
        if (order.customerPhone) document.getElementById('invPhone').innerText = order.customerPhone;
        if (order.deviceType && order.deviceBrand) {
          document.getElementById('invDevice').innerText = `${order.deviceType} ${order.deviceBrand} ${order.deviceModel}`;
        }
        if (order.deliveryMethod) document.getElementById('invDelivery').innerText = order.deliveryMethod;

        const labor = order.costLabor || 150000;
        const parts = order.costParts || 220000;
        const clean = order.addonCleaning ? (order.costCleaning || 50000) : 0;
        const repasta = order.addonRepasta ? (order.costRepasta || 75000) : 0;

        document.getElementById('invLaborCost').innerText = "Rp " + labor.toLocaleString('id-ID');
        document.getElementById('invLaborSubtotal').innerText = "Rp " + labor.toLocaleString('id-ID');
        document.getElementById('invPartsCost').innerText = "Rp " + parts.toLocaleString('id-ID');
        document.getElementById('invPartsSubtotal').innerText = "Rp " + parts.toLocaleString('id-ID');

        if (!order.addonCleaning) {
          document.getElementById('invCleaningRow').style.display = 'none';
        } else {
          document.getElementById('invCleaningCost').innerText = "Rp " + clean.toLocaleString('id-ID');
          document.getElementById('invCleaningSubtotal').innerText = "Rp " + clean.toLocaleString('id-ID');
        }

        if (!order.addonRepasta) {
          document.getElementById('invRepastaRow').style.display = 'none';
        } else {
          document.getElementById('invRepastaCost').innerText = "Rp " + repasta.toLocaleString('id-ID');
          document.getElementById('invRepastaSubtotal').innerText = "Rp " + repasta.toLocaleString('id-ID');
        }

        const grand = labor + parts + clean + repasta;
        document.getElementById('invGrandTotal').innerText = "Rp " + grand.toLocaleString('id-ID');
      }
    }
    window.onload = loadInvoiceData;
  </script>
</body>
</html>
