@extends('layouts.admin')

@section('title', 'Meja Kerja Diagnosa Unit - SerComp')
@section('page-title', 'Meja Kerja Diagnosa & Pelaporan')
@section('page-subtitle', 'Pemeriksaan fisik unit, penetapan estimasi biaya di muka, dokumentasi foto, dan aktivasi SLA.')

@section('page-actions')
<a href="{{ url('/teknisi/servis') }}" class="btn btn-outline-secondary btn-sm fw-semibold d-flex align-items-center gap-1">
  <i class="bi bi-arrow-left"></i> Kembali ke Daftar Servis
</a>
@endsection

@section('content')

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

<!-- 2 KOLOM WORKSPACE DIAGNOSA -->
<div class="row g-4">
  
  <!-- KOLOM KIRI: DETAIL INPUT USER & KRONOLOGI (5 Kolom) -->
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
      <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
        <div>
          <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Tiket di Meja Kerja</span>
          <h5 id="tekTicketNo" class="fw-bold text-primary font-monospace mb-0">#SC-2026-9042</h5>
        </div>
        <span id="tekBadgeStatus" class="badge badge-warning-accessible px-2.5 py-1.5 fw-bold">
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
    <!-- Form Input Lembar Persetujuan -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
      <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
        <div>
          <h6 class="fw-bold text-dark mb-0"><i class="bi bi-clipboard-check me-2 text-primary"></i>Form Lembar Persetujuan Teknisi</h6>
          <span class="text-muted small">Data akan tampil pada portal pelanggan bersama rincian biaya terkunci & pop-up QRIS.</span>
        </div>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Step 2 - 3</span>
      </div>

      <div class="row g-3 small">
        <div class="col-12">
          <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Hasil Diagnosa Kerusakan Fisik:</label>
          <textarea id="inputDiagnosis" rows="2" class="form-control bg-light small"></textarea>
        </div>
        <div class="col-12">
          <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Rencana Tindakan Perbaikan:</label>
          <textarea id="inputActionPlan" rows="2" class="form-control bg-light small"></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Estimasi Jasa Teknisi (Rp):</label>
          <input type="number" id="inputLabor" class="form-control bg-light small">
        </div>
        <div class="col-md-6">
          <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Estimasi Suku Cadang Baru (Rp):</label>
          <input type="number" id="inputParts" class="form-control bg-light small">
        </div>
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

    <!-- Dokumentasi & Penyelesaian -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
      <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
        <div>
          <h6 class="fw-bold text-dark mb-0"><i class="bi bi-camera me-2 text-success"></i>Lampiran Bukti Selesai Servis & Cetak Invoice</h6>
          <span class="text-muted small">Wajib melampirkan foto laptop menyala normal dan suku cadang lama.</span>
        </div>
        <span class="badge bg-success-subtle text-success border border-success-subtle">Step 5 - 6</span>
      </div>

      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <div class="p-3 rounded-3 bg-light border text-center">
            <span class="text-primary fw-bold text-uppercase d-block mb-2" style="font-size: 10px;">Bukti 1: Unit Nyala (Running Test)</span>
            <div class="rounded-2 overflow-hidden bg-secondary-subtle mb-2 border" style="height: 130px;">
              <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&auto=format&fit=crop&q=80" class="w-100 h-100 object-fit-cover" alt="Running Test">
            </div>
            <span class="badge bg-success-subtle text-success small">Suhu Stabil 68°C</span>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-3 rounded-3 bg-light border text-center">
            <span class="text-primary fw-bold text-uppercase d-block mb-2" style="font-size: 10px;">Bukti 2: Modul Fan Rusak Lama</span>
            <div class="rounded-2 overflow-hidden bg-secondary-subtle mb-2 border" style="height: 130px;">
              <img src="https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=600&auto=format&fit=crop&q=80" class="w-100 h-100 object-fit-cover" alt="Part Lama">
            </div>
            <span class="badge bg-secondary-subtle text-dark small">Diserahkan ke Konsumen</span>
          </div>
        </div>
      </div>

      <button id="btnComplete" onclick="completeServiceOrder()" class="btn btn-success w-100 fw-bold py-2.5 shadow-sm">
        <i class="bi bi-check2-circle me-1"></i> Selesaikan Servis, Lampirkan Bukti & Terbitkan Invoice
      </button>
    </div>
  </div>
</div>

@endsection

@section('scripts')
  <script>
    let order = null;

    function initTeknisi() {
      const serverTicket = @json($ticket ?? null);

      if (serverTicket) {
        order = {
          ticketNo: serverTicket.ticket_no,
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
          diagnosis: serverTicket.diagnosis || "Menunggu pemeriksaan fisik teknisi di workshop.",
          actionPlan: serverTicket.action_plan || "",
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
          evidenceLaptopFixed: "https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&auto=format&fit=crop&q=80",
          evidenceReplacedPart: "https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=600&auto=format&fit=crop&q=80",
          invoiceNo: serverTicket.invoice_no
        };
        localStorage.setItem('sercomp_active_order', JSON.stringify(order));
      } else {
        const stored = localStorage.getItem('sercomp_active_order');
        if (stored) {
          try {
            order = JSON.parse(stored);
          } catch(e) {
            order = null;
          }
        } else {
          order = null;
        }
      }

      if (!order) {
        document.getElementById('tekTicketNo').innerText = "-";
        document.getElementById('tekBadgeStatus').innerText = "Belum Ada Unit Dipilih";
        document.getElementById('tekDevice').innerText = "-";
        document.getElementById('tekCustomer').innerText = "-";
        document.getElementById('tekChronology').innerText = "Silakan buka menu Daftar Servis Unit dan pilih unit yang ingin dikerjakan di Meja Kerja.";
        return;
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

    document.getElementById('inputDiagnosis').value = order.diagnosis || "";
    document.getElementById('inputActionPlan').value = order.actionPlan || "";
    document.getElementById('inputLabor').value = order.costLabor || 0;
    document.getElementById('inputParts').value = order.costParts || 0;
    document.getElementById('inputNote').value = order.technicianNote || "";

    const payBox = document.getElementById('tekPaymentBox');
    const payText = document.getElementById('tekPaymentText');

    if (order.paymentStatus === 'Paid') {
      payBox.className = "p-3 rounded-3 border border-success-subtle bg-success-subtle text-dark small mb-3";
      payText.innerHTML = '<i class="bi bi-check-circle-fill text-success fs-5"></i> <span class="fw-bold text-success">Lunas QRIS - Teknisi Diizinkan Bongkar & Selesaikan Unit</span>';
    } else {
      payBox.className = "p-3 rounded-3 border border-warning-subtle bg-warning-subtle text-dark small mb-3";
      payText.innerHTML = '<span class="spinner-grow spinner-grow-sm text-warning" role="status"></span> <span>Menunggu Pelanggan Menyetujui Biaya & Bayar QRIS</span>';
    }

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
    const diagnosis = document.getElementById('inputDiagnosis').value;
    const actionPlan = document.getElementById('inputActionPlan').value;
    const labor = parseInt(document.getElementById('inputLabor').value) || 0;
    const parts = parseInt(document.getElementById('inputParts').value) || 0;
    const note = document.getElementById('inputNote').value;

    order.diagnosis = diagnosis;
    order.actionPlan = actionPlan;
    order.costLabor = labor;
    order.costParts = parts;
    order.technicianNote = note;
    order.step = 3;
    order.status = "Menunggu Persetujuan Biaya";

    // Kirim pembaruan diagnosa ke database MySQL
    fetch('/teknisi/meja-kerja/' + encodeURIComponent(order.ticketNo) + '/diagnosis', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        diagnosis: diagnosis,
        action_plan: actionPlan,
        cost_labor: labor,
        cost_parts: parts,
        technician_notes: note
      })
    })
    .then(res => res.json())
    .then(data => {
      localStorage.setItem('sercomp_active_order', JSON.stringify(order));
      alert("Lembar Persetujuan berhasil diperbarui dan dikirim ke portal pelanggan!");
      renderTeknisi();
    })
    .catch(err => {
      localStorage.setItem('sercomp_active_order', JSON.stringify(order));
      alert("Lembar Persetujuan berhasil diperbarui (disimpan di portal lokal).");
      renderTeknisi();
    });
  }

  function handoverUnitToCustomer() {
    if (confirm("Konfirmasi bahwa unit fisik telah diserahterimakan kepada pelanggan? Timer masa uji SLA 1x24 jam akan langsung berjalan.")) {
      order.unitHandedOver = true;
      order.unitHandedOverTime = new Date().toISOString();
      order.step = 6;
      order.status = "Unit Diterima - Menunggu Tutup Tiket";

      fetch('/teknisi/meja-kerja/' + encodeURIComponent(order.ticketNo) + '/handover', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(data => {
        localStorage.setItem('sercomp_active_order', JSON.stringify(order));
        alert("Unit ditandai telah diserahkan. Timer SLA 1x24 jam aktif di portal pelanggan!");
        renderTeknisi();
      })
      .catch(err => {
        localStorage.setItem('sercomp_active_order', JSON.stringify(order));
        alert("Unit ditandai telah diserahkan. Timer SLA 1x24 jam aktif!");
        renderTeknisi();
      });
    }
  }

  function completeServiceOrder() {
    order.isCompleted = true;
    order.step = 6;
    order.status = "Selesai Dikerjakan - Siap Diserahkan";

    fetch('/teknisi/meja-kerja/' + encodeURIComponent(order.ticketNo) + '/complete', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      }
    })
    .then(res => res.json())
    .then(data => {
      localStorage.setItem('sercomp_active_order', JSON.stringify(order));
      alert("Pengerjaan reparasi selesai! Bukti terlampir dan invoice resmi siap dicetak.");
      renderTeknisi();
    })
    .catch(err => {
      localStorage.setItem('sercomp_active_order', JSON.stringify(order));
      alert("Pengerjaan reparasi selesai!");
      renderTeknisi();
    });
  }

  function triggerSlaTimeout() {
    if (confirm("Simulasikan waktu pengujian 1x24 jam pasca-penyerahan telah habis tanpa komplain pelanggan?")) {
      order.unitHandedOver = true;
      order.unitHandedOverTime = new Date(Date.now() - (25 * 3600 * 1000)).toISOString();
      order.isClosed = true;
      order.step = 7;
      order.status = "Auto-Closed by SLA (1x24 Jam Lewat Pasca-Terima Unit)";

      fetch('/teknisi/meja-kerja/' + encodeURIComponent(order.ticketNo) + '/trigger-sla', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(data => {
        localStorage.setItem('sercomp_active_order', JSON.stringify(order));
        alert("SLA 1x24 Jam Lewat: Tiket otomatis ditutup (Auto-Closed) dan e-Warranty 90 hari resmi diaktifkan!");
        renderTeknisi();
      })
      .catch(err => {
        localStorage.setItem('sercomp_active_order', JSON.stringify(order));
        alert("SLA 1x24 Jam Lewat: Tiket otomatis ditutup!");
        renderTeknisi();
      });
    }
  }

  window.onload = initTeknisi;
</script>
@endsection
