@extends('layouts.main')

@section('title', 'Formulir Reservasi Servis - SerComp')

@section('styles')
<style>
  .sticky-summary {
    position: sticky;
    top: 90px;
  }
  .form-section-title {
    font-size: 0.85rem;
    letter-spacing: 0.5px;
  }
</style>
@endsection

@section('content')
<div class="py-5 bg-light">
  <div class="container">
    
    <!-- Breadcrumb & Title -->
    <div class="mb-4">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-2 small">
          <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Beranda</a></li>
          <li class="breadcrumb-item active" aria-current="page">Reservasi Servis</li>
        </ol>
      </nav>
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
          <h2 class="fw-bold text-dark mb-1">Formulir Reservasi Tiket Servis</h2>
          <p class="text-muted mb-0 small">
            Lengkapi kronologi kendala unit dan tentukan metode serah-terima unit Anda secara transparan.
          </p>
        </div>
        <span class="badge badge-info-accessible px-3 py-2 rounded-pill fw-semibold">
          <i class="bi bi-clock-history me-1" aria-hidden="true"></i> Respon Pemeriksaan Awal &lt; 15 Menit
        </span>
      </div>
    </div>

    <div class="row g-4">
      
      <!-- LEFT: MAIN FORM -->
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
          
          <div class="bg-gradient-hero p-4 text-white d-flex justify-content-between align-items-center">
            <div>
              <h5 class="fw-bold mb-1"><i class="bi bi-clipboard2-check me-2" aria-hidden="true"></i>Tiket Reservasi Baru</h5>
              <span class="text-light small">Informasi ini menjadi dasar diagnosa fisik awal teknisi di workshop.</span>
            </div>
            <div class="badge badge-info-accessible px-2.5 py-1.5 fw-bold">
              Formulir Servis
            </div>
          </div>

          <form id="bookingForm" onsubmit="handleFormSubmit(event)" action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data" class="card-body p-4 p-md-5">
            @csrf

            <!-- 1. Pilihan Perangkat -->
            <div class="mb-4">
              <label class="form-label fw-bold small text-uppercase text-secondary form-section-title">
                1. Jenis Perangkat <span class="text-danger">* (Spesialis Laptop & PC Desktop)</span>
              </label>
              <div class="row g-3">
                <div class="col-sm-6">
                  <input type="radio" name="device_type" id="dev_laptop" value="Laptop" checked class="d-none device-card-input" onchange="updateSummary()">
                  <label for="dev_laptop" class="device-card-label card p-3 border-2 text-center rounded-3 cursor-pointer h-100" tabindex="0">
                    <i class="bi bi-laptop fs-1 mb-1 text-primary d-block" aria-hidden="true"></i>
                    <strong class="text-dark small">Laptop / Notebook</strong>
                    <span class="text-muted d-block" style="font-size: 11px;">Asus, Lenovo, Acer, HP, Dell, MSI, ThinkPad</span>
                  </label>
                </div>
                <div class="col-sm-6">
                  <input type="radio" name="device_type" id="dev_pc" value="PC Desktop" class="d-none device-card-input" onchange="updateSummary()">
                  <label for="dev_pc" class="device-card-label card p-3 border-2 text-center rounded-3 cursor-pointer h-100" tabindex="0">
                    <i class="bi bi-pc-display fs-1 mb-1 text-primary d-block" aria-hidden="true"></i>
                    <strong class="text-dark small">PC Desktop / Tower / AIO</strong>
                    <span class="text-muted d-block" style="font-size: 11px;">Custom Rakitan, All-In-One, Mini PC Office</span>
                  </label>
                </div>
              </div>
              <div class="form-text small text-muted mt-2">
                <i class="bi bi-info-circle me-1" aria-hidden="true"></i>Khusus arsitektur Windows & Linux untuk menjamin ketersediaan suku cadang resmi dan harga transparan.
              </div>
            </div>

            <!-- 2. Merek & Seri Perangkat -->
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label for="device_brand" class="form-label fw-bold small text-uppercase text-secondary form-section-title">
                  Merek / Brand <span class="text-danger">*</span>
                </label>
                <input type="text" name="device_brand" id="device_brand" required class="form-control" placeholder="Contoh: Asus / Lenovo / Rakitan PC" oninput="updateSummary()">
              </div>
              <div class="col-md-6">
                <label for="device_model" class="form-label fw-bold small text-uppercase text-secondary form-section-title">
                  Model / Seri Perangkat <span class="text-danger">*</span>
                </label>
                <input type="text" name="device_model" id="device_model" required class="form-control" placeholder="Contoh: ROG Strix G15 / Core i5 Gen 12" oninput="updateSummary()">
              </div>
            </div>

            <!-- 3. Kronologi & Gejala Kerusakan -->
            <div class="mb-4">
              <label for="issue_chronology" class="form-label fw-bold small text-uppercase text-secondary form-section-title">
                2. Uraian Kerusakan & Kronologi Kejadian <span class="text-danger">*</span>
              </label>
              <textarea name="issue_chronology" id="issue_chronology" rows="3" required class="form-control"
                placeholder="Contoh: Layar tiba-tiba freeze lalu blank hitam saat rendering video. Kipas berputar kencang dan bodi bawah panas..."></textarea>
              <div class="form-text small text-muted">
                Catatan ini dikunci pada berita acara pengerjaan teknisi.
              </div>
            </div>

            <!-- 4. Lampiran Bukti Foto Kerusakan (Aksesibel Keyboard) -->
            <div class="mb-4">
              <label class="form-label fw-bold small text-uppercase text-secondary form-section-title">
                Foto Fisik Kendala / Layar Error (Opsional)
              </label>
              
              <input type="file" name="photo" id="photoInput" accept="image/*" class="visually-hidden" onchange="previewImage(this)">
              
              <label for="photoInput" class="w-100 border border-2 border-dashed rounded-3 p-3 text-center bg-light cursor-pointer d-block" tabindex="0" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault(); document.getElementById('photoInput').click();}">
                <div id="uploadPlaceholder">
                  <i class="bi bi-cloud-arrow-up fs-2 text-secondary" aria-hidden="true"></i>
                  <div class="fw-semibold small text-dark mt-1">Klik atau tekan Enter untuk melampirkan foto fisik / layar error</div>
                  <span class="text-muted" style="font-size: 11px;">Format JPG, PNG maksimal 5MB</span>
                </div>
                <div id="imagePreview" class="d-none text-success fw-bold small">
                  <i class="bi bi-check-circle-fill me-1" aria-hidden="true"></i> Berkas terpilih: <span id="fileName"></span>
                </div>
              </label>
            </div>

            <!-- 5. Layanan Tambahan (Add-ons) -->
            <div class="p-3 bg-light rounded-3 border mb-4">
              <label class="form-label fw-bold small text-uppercase text-secondary mb-2 form-section-title">
                3. Layanan Tambahan Perawatan (Opsional)
              </label>
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="addon_cleaning" id="addon_cleaning" value="1" onchange="updateSummary()">
                <label class="form-check-label small" for="addon_cleaning">
                  <strong class="text-dark">Deep Cleaning & Dust Removal (+Rp 50.000)</strong>
                  <span class="d-block text-muted" style="font-size: 11px;">Pembersihan debu komprehensif pada baling-baling fan heatsink, exhaust, dan slot motherboard.</span>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="addon_repasta" id="addon_repasta" value="1" onchange="updateSummary()">
                <label class="form-check-label small" for="addon_repasta">
                  <strong class="text-dark">Repasta Thermal Arctic MX-4 (+Rp 75.000)</strong>
                  <span class="d-block text-muted" style="font-size: 11px;">Pemberian thermal paste baru kualitas tinggi pada prosesor (CPU) & chip grafis (GPU) cegah overheat.</span>
                </label>
              </div>
            </div>

            <!-- 6. Metode Penyerahan Unit -->
            <div class="mb-4">
              <label class="form-label fw-bold small text-uppercase text-secondary form-section-title">
                4. Metode Penyerahan Unit Perangkat <span class="text-danger">*</span>
              </label>
              <div class="row g-3">
                <div class="col-sm-6">
                  <input type="radio" name="delivery_method" id="deliv_self" value="Diantar Sendiri" checked class="d-none device-card-input" onchange="toggleAddressField(false)">
                  <label for="deliv_self" class="device-card-label card p-3 border-2 rounded-3 cursor-pointer h-100" tabindex="0">
                    <strong class="text-dark small"><i class="bi bi-building me-1 text-primary" aria-hidden="true"></i> Diantar Sendiri ke Workshop</strong>
                    <span class="text-muted mt-1 d-block" style="font-size: 11px;">Bawa unit langsung ke Service Hub SerComp / Kampus UNM. Tanpa ongkos tambahan.</span>
                  </label>
                </div>
                <div class="col-sm-6">
                  <input type="radio" name="delivery_method" id="deliv_pickup" value="Ambil oleh Teknisi" class="d-none device-card-input" onchange="toggleAddressField(true)">
                  <label for="deliv_pickup" class="device-card-label card p-3 border-2 rounded-3 cursor-pointer h-100" tabindex="0">
                    <strong class="text-dark small"><i class="bi bi-scooter me-1 text-primary" aria-hidden="true"></i> Jemput Teknisi (Pick-up)</strong>
                    <span class="text-muted mt-1 d-block" style="font-size: 11px;">Mitra teknisi menjemput unit ke alamat Anda dengan berita acara serah terima resmi.</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Alamat Penjemputan (Kondisional) -->
            <div id="addressContainer" class="mb-4 d-none">
              <label for="pickup_address" class="form-label fw-bold small text-uppercase text-secondary form-section-title">
                Alamat Lengkap Penjemputan Unit <span class="text-danger">*</span>
              </label>
              <textarea name="pickup_address" id="pickup_address" rows="2" class="form-control" placeholder="Tuliskan alamat lengkap penjemputan, nomor rumah/kost, jalan, dan patokan..."></textarea>
            </div>

            <!-- 7. Identitas Pelanggan -->
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label for="customer_name" class="form-label fw-bold small text-uppercase text-secondary form-section-title">
                  Nama Lengkap Pelanggan <span class="text-danger">*</span>
                </label>
                <input type="text" name="customer_name" id="customer_name" required class="form-control" placeholder="Nama Anda" oninput="updateSummary()">
              </div>
              <div class="col-md-6">
                <label for="customer_phone" class="form-label fw-bold small text-uppercase text-secondary form-section-title">
                  Nomor WhatsApp Aktif <span class="text-danger">*</span>
                </label>
                <input type="tel" name="customer_phone" id="customer_phone" required class="form-control" placeholder="08xxxxxxxxxx">
              </div>
            </div>

            <!-- Ketentuan SLA 1x24 Jam -->
            <div class="alert alert-warning border border-warning-subtle bg-warning-subtle text-dark d-flex align-items-start gap-2 small mb-4">
              <i class="bi bi-shield-exclamation fs-5 text-warning-emphasis" aria-hidden="true"></i>
              <div>
                <strong class="d-block text-dark">Ketentuan SLA 1x24 Jam Pasca-Penyerahan Unit:</strong>
                Setelah unit selesai diperbaiki dan diserahkan kembali, sistem memberikan waktu masa uji 24 jam. Jika pelanggan tidak mengajukan keluhan tambahan, sistem menutup tiket secara otomatis (Auto-Closed) untuk mengaktifkan garansi digital 90 hari.
              </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-6 shadow">
              <i class="bi bi-send-check me-2" aria-hidden="true"></i> Terbitkan Tiket Reservasi & Dapatkan Nomor Resi
            </button>
          </form>

        </div>
      </div>

      <!-- RIGHT: LIVE SUMMARY & TRUST CARD -->
      <div class="col-lg-4">
        <div class="sticky-summary">
          
          <!-- Summary Card -->
          <div class="card border-0 shadow-sm rounded-4 p-4 mb-3 bg-white">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom d-flex align-items-center justify-content-between">
              <span>Ringkasan Estimasi</span>
              <span class="badge bg-light text-secondary border">Pra-Diagnosa</span>
            </h6>

            <div class="small mb-3">
              <div class="d-flex justify-content-between text-muted mb-1">
                <span>Perangkat:</span>
                <strong id="sumDevice" class="text-dark">Laptop</strong>
              </div>
              <div class="d-flex justify-content-between text-muted mb-1">
                <span>Merek / Seri:</span>
                <span id="sumBrand" class="text-dark fw-semibold">-</span>
              </div>
              <div class="d-flex justify-content-between text-muted mb-1">
                <span>Pelanggan:</span>
                <span id="sumCustomer" class="text-dark fw-semibold">-</span>
              </div>
              <div class="d-flex justify-content-between text-muted">
                <span>Metode:</span>
                <span id="sumDelivery" class="text-dark fw-semibold">Diantar Sendiri</span>
              </div>
            </div>

            <div class="p-3 bg-light rounded-3 mb-3 small">
              <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Biaya Pemeriksaan Awal:</span>
                <span class="fw-bold text-success">GRATIS</span>
              </div>
              <div id="rowAddonCleaning" class="d-none justify-content-between mb-1">
                <span class="text-muted">Deep Cleaning:</span>
                <span class="fw-bold text-dark">Rp 50.000</span>
              </div>
              <div id="rowAddonRepasta" class="d-none justify-content-between mb-1">
                <span class="text-muted">Repasta MX-4:</span>
                <span class="fw-bold text-dark">Rp 75.000</span>
              </div>
              <hr class="my-2">
              <div class="d-flex justify-content-between align-items-center">
                <strong class="text-dark">Estimasi Awal:</strong>
                <span id="sumTotal" class="fs-6 fw-bold text-primary">Rp 0</span>
              </div>
              <span class="text-muted d-block mt-1" style="font-size: 10px;">
                *Biaya penggantian suku cadang dan jasa utama dikunci melalui Lembar Persetujuan Biaya pasca cek fisik.
              </span>
            </div>

            <div class="d-flex flex-column gap-2 small">
              <div class="d-flex align-items-center gap-2 text-secondary">
                <i class="bi bi-shield-check text-success fs-5" aria-hidden="true"></i>
                <span>Garansi 90 hari suku cadang & jasa</span>
              </div>
              <div class="d-flex align-items-center gap-2 text-secondary">
                <i class="bi bi-lock-fill text-primary fs-5" aria-hidden="true"></i>
                <span>Data storage tidak dibuka tanpa izin</span>
              </div>
              <div class="d-flex align-items-center gap-2 text-secondary">
                <i class="bi bi-qr-code text-dark fs-5" aria-hidden="true"></i>
                <span>Pembayaran QRIS setelah biaya disetujui</span>
              </div>
            </div>
          </div>

          <!-- Bantuan Hubungi Kami -->
          <div class="card border-0 shadow-sm rounded-4 p-3 badge-info-accessible">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-headset fs-3" aria-hidden="true"></i>
              <div class="small">
                <strong>Pusat Bantuan WhatsApp:</strong>
                <div class="text-muted" style="font-size: 11px;">Tim teknisi siaga melayani konfirmasi serah-terima unit.</div>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>

  </div>
</div>

<!-- MODAL SUKSES TIKET TERBIT -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg text-center p-4 rounded-4">
      <div class="modal-body">
        <div class="badge badge-success-accessible fs-1 p-3 rounded-circle mb-3">
          <i class="bi bi-check-lg" aria-hidden="true"></i>
        </div>
        <h4 class="fw-bold mb-1" id="successModalLabel">Tiket Reservasi Terbit</h4>
        <p class="text-muted small mb-3">Nomor resi pelacakan unit servis Anda:</p>
        
        <div class="p-3 bg-light border rounded-3 mb-3">
          <span id="createdTicketNo" class="fs-4 fw-bold font-monospace text-primary">#SC-2026-9042</span>
        </div>

        <p class="small text-secondary mb-4">
          Data tersimpan di sistem. Anda dapat memantau diagnosa teknisi, menyetujui rincian biaya, dan membayar via QRIS di <strong>Portal Pelanggan</strong>.
        </p>

        <div class="d-grid gap-2">
          <a href="{{ url('/user-panel') }}" class="btn btn-primary fw-bold py-2.5">
            <i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i> Buka Portal Pelanggan Sekarang
          </a>
          <a href="{{ url('/') }}" class="btn btn-outline-secondary fw-semibold">
            Kembali ke Beranda
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  let bsSuccessModal = null;

  function toggleAddressField(show) {
    const container = document.getElementById('addressContainer');
    const input = document.getElementById('pickup_address');
    if (show) {
      container.classList.remove('d-none');
      input.setAttribute('required', 'required');
      document.getElementById('sumDelivery').innerText = "Ambil oleh Teknisi";
    } else {
      container.classList.add('d-none');
      input.removeAttribute('required');
      document.getElementById('sumDelivery').innerText = "Diantar Sendiri";
    }
  }

  function previewImage(input) {
    if (input.files && input.files[0]) {
      document.getElementById('uploadPlaceholder').classList.add('d-none');
      document.getElementById('imagePreview').classList.remove('d-none');
      document.getElementById('fileName').innerText = input.files[0].name;
    }
  }

  function updateSummary() {
    const devChecked = document.querySelector('input[name="device_type"]:checked');
    document.getElementById('sumDevice').innerText = devChecked ? devChecked.value : 'Laptop';

    const brand = document.getElementById('device_brand').value;
    const model = document.getElementById('device_model').value;
    document.getElementById('sumBrand').innerText = (brand || model) ? `${brand} ${model}`.trim() : '-';

    const cust = document.getElementById('customer_name').value;
    document.getElementById('sumCustomer').innerText = cust || '-';

    let total = 0;
    const cln = document.getElementById('addon_cleaning').checked;
    const rep = document.getElementById('addon_repasta').checked;

    const rowCln = document.getElementById('rowAddonCleaning');
    const rowRep = document.getElementById('rowAddonRepasta');

    if (cln) {
      total += 50000;
      rowCln.classList.remove('d-none');
      rowCln.classList.add('d-flex');
    } else {
      rowCln.classList.add('d-none');
      rowCln.classList.remove('d-flex');
    }

    if (rep) {
      total += 75000;
      rowRep.classList.remove('d-none');
      rowRep.classList.add('d-flex');
    } else {
      rowRep.classList.add('d-none');
      rowRep.classList.remove('d-flex');
    }

    document.getElementById('sumTotal').innerText = "Rp " + total.toLocaleString('id-ID');
  }

  function handleFormSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Menyimpan Tiket...';

    fetch("{{ route('booking.store') }}", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Accept": "application/json"
      }
    })
    .then(res => res.json())
    .then(data => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalBtnText;

      const serverTicket = data.ticket;
      const ticketNo = serverTicket ? serverTicket.ticket_no : (data.ticket_no || ("#SC-" + new Date().getFullYear() + "-" + Math.floor(1000 + Math.random() * 9000)));

      const orderData = {
        ticketNo: ticketNo,
        createdAt: serverTicket ? serverTicket.created_at : new Date().toISOString(),
        customerName: formData.get('customer_name'),
        customerPhone: formData.get('customer_phone'),
        deviceType: formData.get('device_type'),
        deviceBrand: formData.get('device_brand'),
        deviceModel: formData.get('device_model'),
        issueChronology: formData.get('issue_chronology'),
        addonCleaning: formData.has('addon_cleaning'),
        addonRepasta: formData.has('addon_repasta'),
        deliveryMethod: formData.get('delivery_method'),
        pickupAddress: formData.get('pickup_address') || '',
        step: 1,
        status: serverTicket ? serverTicket.status : (formData.get('delivery_method') === 'Diantar Sendiri' ? 'Menunggu Unit Tiba di Workshop' : 'Menunggu Penjemputan Unit oleh Teknisi'),
        technician: "Menunggu Penugasan Teknisi",
        diagnosis: "Unit belum diperiksa fisik oleh teknisi di workshop.",
        actionPlan: "Menunggu hasil pembongkaran awal dan pengecekan tegangan.",
        technicianNote: "Penyimpanan SSD aman dan terlindungi SOP.",
        costLabor: 0,
        costParts: 0,
        costCleaning: formData.has('addon_cleaning') ? 50000 : 0,
        costRepasta: formData.has('addon_repasta') ? 75000 : 0,
        paymentStatus: "Unpaid",
        isApproved: false,
        isCompleted: false,
        unitHandedOver: false,
        unitHandedOverTime: null,
        isClosed: false,
        invoiceNo: serverTicket ? serverTicket.invoice_no : ("INV-SC-" + new Date().getFullYear() + "-" + Math.floor(1000 + Math.random() * 9000))
      };

      localStorage.setItem('sercomp_active_order', JSON.stringify(orderData));
      document.getElementById('createdTicketNo').innerText = ticketNo;

      if (!bsSuccessModal) {
        bsSuccessModal = new bootstrap.Modal(document.getElementById('successModal'));
      }
      bsSuccessModal.show();
    })
    .catch(err => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalBtnText;
      console.error(err);
      alert('Terjadi kendala saat menyimpan tiket. Menggunakan mode demonstrasi lokal.');
      
      const ticketNo = "#SC-" + new Date().getFullYear() + "-" + Math.floor(1000 + Math.random() * 9000);
      document.getElementById('createdTicketNo').innerText = ticketNo;
      if (!bsSuccessModal) {
        bsSuccessModal = new bootstrap.Modal(document.getElementById('successModal'));
      }
      bsSuccessModal.show();
    });
  }
</script>
@endsection
