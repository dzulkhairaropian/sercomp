<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice Resmi #{{ $ticket?->invoice_no ?? 'INV-SC-2026-8812' }} - SerComp</title>
  
  <!-- Bootstrap 5.3 CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  
  <!-- Font Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
  
  <!-- SerComp Theme -->
  <link rel="stylesheet" href="{{ asset('css/sercomp-theme.css') }}">
  
  <style>
    :root {
      --inv-border: #e2e8f0;
      --inv-bg-subtle: #f8fafc;
      --inv-text-main: #0f172a;
      --inv-text-muted: #64748b;
    }

    body {
      background-color: #f1f5f9;
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: var(--inv-text-main);
      -webkit-font-smoothing: antialiased;
    }

    .font-mono {
      font-family: 'JetBrains Mono', SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
    }

    /* A4 Paper Proportion & Clean Shadow */
    .invoice-sheet {
      max-width: 860px;
      margin: 32px auto 60px auto;
      background: #ffffff;
      border: 1px solid var(--inv-border);
      border-radius: 16px;
      box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.08), 0 0 1px rgba(15, 23, 42, 0.1);
      padding: 48px;
      position: relative;
      overflow: hidden;
    }

    /* Subtle Technical Blueprint Top Border */
    .invoice-sheet::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--sc-brand) 0%, #38bdf8 100%);
    }

    /* Elegant Stamp Paid */
    .stamp-paid-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #ecfdf5;
      color: #065f46;
      border: 1.5px solid #a7f3d0;
      padding: 6px 14px;
      border-radius: 9999px;
      font-weight: 700;
      font-size: 13px;
      letter-spacing: 0.04em;
      text-transform: uppercase;
    }

    .meta-box {
      background: var(--inv-bg-subtle);
      border: 1px solid var(--inv-border);
      border-radius: 12px;
      padding: 16px 20px;
    }

    .invoice-table thead th {
      background-color: var(--inv-bg-subtle);
      color: #475569;
      font-weight: 700;
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      border-bottom: 2px solid var(--inv-border);
      padding: 12px 16px;
    }

    .invoice-table tbody td {
      padding: 16px;
      border-bottom: 1px solid var(--inv-border);
      vertical-align: middle;
    }

    .invoice-table tfoot td {
      padding: 14px 16px;
      border: none;
    }

    .qr-box {
      width: 72px;
      height: 72px;
      background: #ffffff;
      border: 1px solid var(--inv-border);
      border-radius: 8px;
      padding: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    @media print {
      body {
        background-color: #ffffff !important;
        color: #000000 !important;
      }
      .no-print {
        display: none !important;
      }
      .invoice-sheet {
        margin: 0 !important;
        padding: 24px 30px !important;
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        max-width: 100% !important;
      }
      .invoice-sheet::before {
        display: none;
      }
    }
  </style>
</head>
<body>

  <!-- Top Action Bar (Screen Only) -->
  <div class="container no-print mt-4 mb-2" style="max-width: 860px;">
    <div class="d-flex flex-wrap justify-content-between align-items-center bg-white px-4 py-3 rounded-4 border shadow-sm gap-3">
      <div class="d-flex align-items-center gap-2">
        <a href="{{ url('/user-panel') }}" class="btn btn-outline-secondary btn-sm fw-semibold d-inline-flex align-items-center gap-1 px-3 py-1.5">
          <i class="bi bi-arrow-left"></i>
          <span>Kembali ke Portal</span>
        </a>
        <div class="d-none d-sm-flex align-items-center text-success small fw-bold px-2 py-1 bg-success-subtle rounded-3">
          <i class="bi bi-shield-fill-check me-1"></i> Transaksi Terverifikasi Lunas
        </div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <button onclick="window.print()" class="btn btn-primary btn-sm fw-semibold d-inline-flex align-items-center gap-2 px-3.5 py-1.5 shadow-sm">
          <i class="bi bi-printer-fill"></i>
          <span>Cetak / Unduh PDF</span>
        </button>
      </div>
    </div>
  </div>

  <!-- INVOICE SHEET -->
  <main class="invoice-sheet">
    
    <!-- Top Header: Brand & Document Meta -->
    <div class="row align-items-start justify-content-between g-4 pb-4 mb-4 border-bottom">
      <div class="col-sm-7">
        <div class="d-flex align-items-center gap-3 mb-2">
          <div class="brand-box" style="width: 44px; height: 44px; font-size: 1.25rem;">SC</div>
          <div>
            <h3 class="fw-bold mb-0 text-dark tracking-tight" style="letter-spacing: -0.03em;">
              Ser<span class="text-primary">Comp</span>
            </h3>
            <span class="text-secondary small fw-bold text-uppercase" style="font-size: 10px; letter-spacing: 0.05em;">
              PT SerComp Digital Solusindo
            </span>
          </div>
        </div>
        <p class="text-secondary small mb-0 lh-base" style="max-width: 420px; font-size: 12px;">
          Sentra Hardware & Workshop Reparasi Laptop &bull; Jakarta Barat<br>
          WhatsApp: <span class="fw-semibold text-dark">0812-9876-5432</span> &bull; Surel: <span class="fw-semibold text-dark">billing@sercomp.id</span>
        </p>
      </div>

      <div class="col-sm-5 text-sm-end">
        <div class="d-inline-block text-start text-sm-end">
          <div class="stamp-paid-badge mb-2">
            <i class="bi bi-check-circle-fill"></i> LUNAS (PAID)
          </div>
          <h4 class="fw-bold text-primary font-mono mb-1" id="invNo" style="letter-spacing: -0.02em;">
            {{ $ticket?->invoice_no ?? 'INV-SC-2026-8812' }}
          </h4>
          <div class="text-secondary small" style="font-size: 12px;">
            Tanggal: <strong class="text-dark" id="invDate">27 Oktober 2026</strong>
          </div>
          <div class="text-secondary small" style="font-size: 12px;">
            No. Tiket: <strong class="text-dark font-mono" id="invTicket">#{{ $ticket?->ticket_no ?? 'SC-2026-9042' }}</strong>
          </div>
        </div>
      </div>
    </div>

    <!-- Informasi Pelanggan & Perangkat Servis (Bento Grid) -->
    <div class="meta-box mb-4">
      <div class="row g-3">
        <div class="col-6 col-md-3">
          <span class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.05em;">Pelanggan:</span>
          <span class="fw-bold text-dark d-block" id="invCustomer">{{ $ticket?->customer_name ?? 'Dzul Khair Aropian' }}</span>
          @if($ticket && ($ticket->company_name || $ticket->customer?->company_name))
            <span class="text-primary small fw-semibold"><i class="bi bi-building me-1"></i>{{ $ticket->company_name ?? $ticket->customer?->company_name }}</span>
          @endif
        </div>
        <div class="col-6 col-md-3">
          <span class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.05em;">Kontak WhatsApp:</span>
          <span class="fw-bold text-dark font-mono" id="invPhone">{{ $ticket?->customer_phone ?? '0812-9876-5432' }}</span>
        </div>
        <div class="col-6 col-md-3">
          <span class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.05em;">Unit Perangkat:</span>
          <span class="fw-bold text-dark" id="invDevice">Laptop Asus ROG Strix G15</span>
        </div>
        <div class="col-6 col-md-3">
          <span class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.05em;">Metode Penyerahan:</span>
          <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fw-bold" id="invDelivery">
            {{ $ticket?->delivery_method ?? 'Diantar ke Workshop' }}
          </span>
        </div>
      </div>
    </div>

    <!-- Tabel Rincian Biaya Servis & Part -->
    <div class="table-responsive mb-4">
      <table class="table invoice-table mb-0">
        <thead>
          <tr>
            <th scope="col" style="width: 5%;">No</th>
            <th scope="col" style="width: 55%;">Deskripsi Pekerjaan & Suku Cadang</th>
            <th scope="col" class="text-center" style="width: 8%;">Qty</th>
            <th scope="col" class="text-end" style="width: 16%;">Biaya Satuan</th>
            <th scope="col" class="text-end" style="width: 16%;">Subtotal</th>
          </tr>
        </thead>
        <tbody class="small">
          <tr>
            <td class="text-center fw-bold text-secondary">1</td>
            <td>
              <div class="fw-bold text-dark">Jasa Diagnosa & Reparasi Hardware Meja Kerja</div>
              <div class="text-secondary" style="font-size: 11px;">Bongkar pasang modul, thermal test 24h, dan sertifikasi teknisi SerComp</div>
            </td>
            <td class="text-center fw-semibold text-secondary">1</td>
            <td class="text-end font-mono" id="invLaborCost">Rp 150.000</td>
            <td class="text-end font-mono fw-bold text-dark" id="invLaborSubtotal">Rp 150.000</td>
          </tr>
          <tr>
            <td class="text-center fw-bold text-secondary">2</td>
            <td>
              <div class="fw-bold text-dark">Modul Fan Cooler Original DC 5V Brushless</div>
              <div class="text-secondary" style="font-size: 11px;">Penggantian modul baru grade OEM resmi (Garansi Terproteksi 90 Hari)</div>
            </td>
            <td class="text-center fw-semibold text-secondary">1</td>
            <td class="text-end font-mono" id="invPartsCost">Rp 220.000</td>
            <td class="text-end font-mono fw-bold text-dark" id="invPartsSubtotal">Rp 220.000</td>
          </tr>
          <tr id="invCleaningRow">
            <td class="text-center fw-bold text-secondary">3</td>
            <td>
              <div class="fw-bold text-dark">Deep Cleaning & Dust Heatsink Removal</div>
              <div class="text-secondary" style="font-size: 11px;">Pembersihan total residu debu kisi-kisi pembuangan & motherboard</div>
            </td>
            <td class="text-center fw-semibold text-secondary">1</td>
            <td class="text-end font-mono" id="invCleaningCost">Rp 50.000</td>
            <td class="text-end font-mono fw-bold text-dark" id="invCleaningSubtotal">Rp 50.000</td>
          </tr>
          <tr id="invRepastaRow">
            <td class="text-center fw-bold text-secondary">4</td>
            <td>
              <div class="fw-bold text-dark">Repasta Thermal Arctic MX-4 Premium Grade</div>
              <div class="text-secondary" style="font-size: 11px;">Penggantian pasta konduksi panas CPU & GPU micro-level</div>
            </td>
            <td class="text-center fw-semibold text-secondary">1</td>
            <td class="text-end font-mono" id="invRepastaCost">Rp 75.000</td>
            <td class="text-end font-mono fw-bold text-dark" id="invRepastaSubtotal">Rp 75.000</td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" class="border-0"></td>
            <td class="text-end text-secondary small fw-bold text-uppercase border-top">Subtotal:</td>
            <td class="text-end font-mono fw-semibold border-top text-dark" id="invSummarySubtotal">Rp 495.000</td>
          </tr>
          <tr>
            <td colspan="3" class="border-0"></td>
            <td class="text-end text-secondary small fw-bold text-uppercase">Pajak / PPN (0%):</td>
            <td class="text-end font-mono text-secondary">Rp 0</td>
          </tr>
          <tr class="bg-light rounded-3">
            <td colspan="3" class="border-0"></td>
            <td class="text-end fw-bold text-dark text-uppercase fs-6">Total Tagihan:</td>
            <td class="text-end fw-bold text-primary fs-5 font-mono" id="invGrandTotal">Rp 495.000</td>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- Garansi & Verifikasi Pembayaran (Bento Two Columns) -->
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <div class="p-3 bg-white border rounded-3 h-100">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-shield-check text-success fs-5"></i>
            <strong class="text-dark small">Verifikasi Transaksi QRIS Dinamis</strong>
          </div>
          <div class="small text-secondary lh-base">
            <div class="d-flex justify-content-between py-1 border-bottom">
              <span>Waktu Pembayaran:</span>
              <strong class="text-dark" id="invPaidAt">27 Okt 2026, 14:22 WIB</strong>
            </div>
            <div class="d-flex justify-content-between py-1 border-bottom">
              <span>Status Settlement:</span>
              <span class="badge bg-success-subtle text-success border border-success-subtle">SETTLED (LUNAS)</span>
            </div>
            <div class="d-flex justify-content-between pt-1">
              <span>Nomor RRN / Bukti:</span>
              <span class="font-mono text-dark">QRIS-20261027-9941</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="p-3 bg-white border rounded-3 h-100">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-patch-check-fill text-primary fs-5"></i>
            <strong class="text-dark small">Sertifikat e-Warranty Resmi (90 Hari)</strong>
          </div>
          <div class="small text-secondary lh-base">
            <div class="d-flex justify-content-between py-1 border-bottom">
              <span>Kode Garansi Digital:</span>
              <strong class="text-primary font-mono" id="invWarrantyCode">#WAR-2026-9904</strong>
            </div>
            <div class="d-flex justify-content-between py-1 border-bottom">
              <span>Masa Berlaku:</span>
              <strong class="text-dark">Hingga 25 Jan 2027</strong>
            </div>
            <div class="d-flex justify-content-between pt-1">
              <span>Metode Klaim:</span>
              <span class="text-dark">Paperless via Portal SerComp</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Catatan & Pengesahan Digital -->
    <div class="border-top pt-4">
      <div class="row align-items-end g-4">
        <div class="col-sm-7 small text-secondary">
          <strong class="d-block text-dark mb-1">Ketentuan Layanan & Purnajual:</strong>
          <ol class="ps-3 mb-0" style="font-size: 11px; line-height: 1.6;">
            <li>Suku cadang lama yang telah diganti wajib disertakan kembali ke pelanggan saat unit diserahkan.</li>
            <li>Masa uji kepuasan 24 jam berlaku sejak serah terima. Bila tidak ada keluhan, status tiket otomatis tertutup (Closed).</li>
            <li>Faktur ini sah dan diterbitkan secara digital oleh sistem verifikasi transaksi SerComp.</li>
          </ol>
        </div>

        <div class="col-sm-5 text-sm-end">
          <div class="d-inline-flex align-items-center gap-3 text-start">
            <div class="qr-box">
              <i class="bi bi-qr-code text-dark fs-2"></i>
            </div>
            <div>
              <span class="d-block text-muted text-uppercase" style="font-size: 10px; font-weight: 700;">Pengesahan Digital:</span>
              <strong class="d-block text-dark small">Finance Workshop SerComp</strong>
              <span class="badge bg-success-subtle text-success border border-success-subtle mt-1" style="font-size: 10px;">
                <i class="bi bi-patch-check-fill me-1"></i> Digitally Signed & Sealed
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </main>

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
        if (order.ticketNo) document.getElementById('invTicket').innerText = '#' + order.ticketNo;
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
        document.getElementById('invSummarySubtotal').innerText = "Rp " + grand.toLocaleString('id-ID');
        document.getElementById('invGrandTotal').innerText = "Rp " + grand.toLocaleString('id-ID');

        if (serverTicket && serverTicket.invoice && serverTicket.invoice.warranty) {
          document.getElementById('invWarrantyCode').innerText = serverTicket.invoice.warranty.warranty_code;
        }
      }
    }
    window.onload = loadInvoiceData;
  </script>
</body>
</html>