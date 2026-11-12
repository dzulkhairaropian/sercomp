@extends('layouts.main')

@section('title', 'Formulir Reservasi Servis - SerComp')

@section('styles')
<style>
  .sticky-summary {
    position: sticky;
    top: 90px;
  }
  .form-step-title {
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: var(--sc-slate-500);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  .form-step-title i {
    color: var(--sc-primary);
  }
</style>
@endsection

@section('content')
<div class="py-5 bg-light">
  <div class="container">
    
    <!-- Breadcrumb & Header -->
    <div class="mb-4">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-2 small">
          <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a></li>
          <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Reservasi Servis</li>
        </ol>
      </nav>
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
          <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.02em;">Formulir Reservasi Servis</h2>
          <p class="text-secondary mb-0 small">
            Daftarkan kendala unit Anda untuk mendapatkan nomor resi pelacakan dan estimasi di meja kerja workshop.
          </p>
        </div>
        <span class="badge-status badge-status-info small">
          <i class="bi bi-clock-history"></i> Respon Awal &lt; 15 Menit
        </span>
      </div>
    </div>

    <div class="row g-4">
      
      <!-- LEFT: MAIN FORM -->
      <div class="col-lg-8">
        <div class="card card-bento overflow-hidden bg-white">
          
          <div class="p-4 px-4 bg-white border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-3">
              <div class="icon-box-primary" style="width: 40px; height: 40px; font-size: 1.2rem;">
                <i data-lucide="clipboard-check"></i>
              </div>
              <div>
                <h5 class="fw-bold text-dark mb-0.5">Rincian Reservasi Unit</h5>
                <span class="text-secondary small">Data ini menjadi acuan pemeriksaan fisik awal teknisi di meja kerja.</span>
              </div>
            </div>
            <span class="badge-status badge-status-info small font-monospace">
              Langkah 1 dari 2
            </span>
          </div>

          <form id="bookingForm" onsubmit="handleFormSubmit(event)" action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data" class="p-4 p-md-5">
            @csrf

            <!-- 1. Pilihan Perangkat -->
            <div class="mb-4">
              <div class="form-step-title">
                <i data-lucide="laptop" style="width: 16px; height: 16px;"></i> 1. Jenis Perangkat
              </div>
              <div class="row g-3 device-select-grid">
                <div class="col-sm-6">
                  <input type="radio" name="device_type" id="dev_laptop" value="Laptop" checked class="device-card-input" onchange="updateSummary()">
                  <label for="dev_laptop" class="device-card-label text-center" tabindex="0">
                    <i data-lucide="laptop" class="text-primary mx-auto mb-2 d-block" style="width: 36px; height: 36px;"></i>
                    <strong class="text-dark small d-block">Laptop / Notebook</strong>
                    <span class="text-muted d-block" style="font-size: 11px;">Asus, Lenovo, Acer, HP, Dell, MSI, ThinkPad</span>
                  </label>
                </div>
                <div class="col-sm-6">
                  <input type="radio" name="device_type" id="dev_pc" value="PC Desktop" class="device-card-input" onchange="updateSummary()">
                  <label for="dev_pc" class="device-card-label text-center" tabindex="0">
                    <i data-lucide="monitor" class="text-primary mx-auto mb-2 d-block" style="width: 36px; height: 36px;"></i>
                    <strong class="text-dark small d-block">PC Desktop / AIO / Tower</strong>
                    <span class="text-muted d-block" style="font-size: 11px;">Custom Gaming/Editing, All-In-One, Mini PC Office</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- 2. Merek & Seri Perangkat -->
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label for="device_brand" class="form-label fw-semibold small text-secondary">
                  Merek / Brand <span class="text-danger">*</span>
                </label>
                <input type="text" name="device_brand" id="device_brand" required class="form-control" placeholder="Contoh: Asus / Lenovo / Rakitan PC" oninput="updateSummary()">
              </div>
              <div class="col-md-6">
                <label for="device_model" class="form-label fw-semibold small text-secondary">
                  Model / Seri Perangkat <span class="text-danger">*</span>
                </label>
                <input type="text" name="device_model" id="device_model" required class="form-control" placeholder="Contoh: ROG Strix G15 / Core i5-12400" oninput="updateSummary()">
              </div>
            </div>

            <!-- 3. Kronologi & Gejala Kerusakan -->
            <div class="mb-4">
              <div class="form-step-title">
                <i data-lucide="file-text" style="width: 16px; height: 16px;"></i> 2. Uraian Gejala Kerusakan
              </div>
              <textarea name="issue_chronology" id="issue_chronology" rows="3" required class="form-control"
                placeholder="Ceritakan kronologi kendala (contoh: Layar tiba-tiba freeze lalu blank saat render, kipas bersuara kasar, atau unit mati total)..."></textarea>
              <div class="form-text small text-muted">
                Catatan ini dikunci pada berita acara pengerjaan teknisi.
              </div>
            </div>

            <!-- 4. Lampiran Bukti Foto Kerusakan -->
            <div class="mb-4">
              <label class="form-label fw-semibold small text-secondary">
                Foto Fisik / Tampilan Layar Error (Opsional)
              </label>
              <input type="file" name="photo" id="photoInput" accept="image/*" class="visually-hidden" onchange="previewImage(this)">
              
              <label for="photoInput" class="w-100 border border-2 border-dashed rounded-3 p-3.5 text-center bg-light cursor-pointer d-block" tabindex="0" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault(); document.getElementById('photoInput').click();}">
                <div id="uploadPlaceholder">
                  <i data-lucide="upload-cloud" class="text-primary mx-auto mb-1 d-block" style="width: 32px; height: 32px;"></i>
                  <div class="fw-semibold small text-dark mt-1">Klik atau tekan Enter untuk melampirkan foto</div>
                  <span class="text-muted" style="font-size: 11px;">Format JPG, PNG (Maksimal 5MB)</span>
                </div>
                <div id="imagePreview" class="d-none text-success fw-semibold small">
                  <i data-lucide="check-circle-2" class="me-1 d-inline" style="width: 16px; height: 16px;"></i> Berkas terpilih: <span id="fileName"></span>
                </div>
              </label>
            </div>

            <!-- 5. Layanan Tambahan (Add-ons) -->
            <div class="p-3.5 bg-light rounded-3 border mb-4">
              <div class="form-step-title mb-2.5">
                <i data-lucide="plus-circle" style="width: 16px; height: 16px;"></i> 3. Layanan Perawatan Tambahan (Opsional)
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="addon_cleaning" id="addon_cleaning" value="1" onchange="updateSummary()">
                <label class="form-check-label small" for="addon_cleaning">
                  <strong class="text-dark">Deep Cleaning & Dust Removal (+Rp 50.000)</strong>
                  <span class="d-block text-muted" style="font-size: 11px;">Pembersihan debu menyeluruh pada kipas heatsink, exhaust, dan slot motherboard.</span>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="addon_repasta" id="addon_repasta" value="1" onchange="updateSummary()">
                <label class="form-check-label small" for="addon_repasta">
                  <strong class="text-dark">Repasta Thermal Arctic MX-4 (+Rp 75.000)</strong>
                  <span class="d-block text-muted" style="font-size: 11px;">Penggantian pasta pendingin performa tinggi pada CPU & GPU untuk mencegah overheat.</span>
                </label>
              </div>
            </div>

            <!-- 6. Metode Penyerahan Unit -->
            <div class="mb-4">
              <div class="form-step-title">
                <i data-lucide="map-pin" style="width: 16px; height: 16px;"></i> 4. Metode Serah-Terima Unit Perangkat
              </div>
              <div class="row g-3 device-select-grid">
                <div class="col-sm-6">
                  <input type="radio" name="delivery_method" id="deliv_self" value="Diantar Sendiri" checked class="device-card-input" onchange="toggleAddressField(false)">
                  <label for="deliv_self" class="device-card-label" tabindex="0">
                    <strong class="text-dark small d-flex align-items-center gap-1.5 mb-1"><i data-lucide="building-2" class="text-primary" style="width: 16px; height: 16px;"></i> Diantar Sendiri ke Workshop</strong>
                    <span class="text-muted d-block" style="font-size: 11px;">Bawa unit langsung ke Service Hub SerComp. Bebas ongkos kurir.</span>
                  </label>
                </div>
                <div class="col-sm-6">
                  <input type="radio" name="delivery_method" id="deliv_pickup" value="Ambil oleh Teknisi" class="device-card-input" onchange="toggleAddressField(true)">
                  <label for="deliv_pickup" class="device-card-label" tabindex="0">
                    <strong class="text-dark small d-flex align-items-center gap-1.5 mb-1"><i data-lucide="truck" class="text-primary" style="width: 16px; height: 16px;"></i> Jemput oleh Mitra (Pick-up)</strong>
                    <span class="text-muted d-block" style="font-size: 11px;">Mitra kurir menjemput unit ke lokasi Anda dengan berita acara serah terima resmi.</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Alamat Penjemputan (Kondisional) -->
            <div id="addressContainer" class="mb-4 d-none">
              <label for="pickup_address" class="form-label fw-semibold small text-secondary">
                Alamat Lengkap Penjemputan Unit <span class="text-danger">*</span>
              </label>
              <textarea name="pickup_address" id="pickup_address" rows="2" class="form-control" placeholder="Tuliskan alamat penjemputan, nomor rumah/kantor, jalan, dan patokan terdekat..."></textarea>
            </div>

            <!-- 7. Identitas Pelanggan -->
            <div class="p-3.5 bg-light rounded-3 border mb-4">
              <div class="form-step-title mb-2.5">
                <i data-lucide="user" style="width: 16px; height: 16px;"></i> 5. Profil Pelanggan
              </div>
              <div class="d-flex flex-wrap gap-4 mb-2">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="customer_type" id="typePersonal" value="Personal" checked onchange="toggleCompanyInput(this)">
                  <label class="form-check-label fw-semibold text-dark small" for="typePersonal">
                    Personal / Perorangan
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="customer_type" id="typeCompany" value="Company" onchange="toggleCompanyInput(this)">
                  <label class="form-check-label fw-semibold text-dark small" for="typeCompany">
                    Perusahaan / Kantor (Faktur Perusahaan)
                  </label>
                </div>
              </div>

              <div id="companyNameWrapper" class="mt-2.5 d-none mb-3">
                <label for="company_name" class="form-label text-secondary fw-semibold small">Nama Perusahaan / PT / Instansi:</label>
                <input type="text" name="company_name" id="company_name" class="form-control form-control-sm" placeholder="Contoh: PT Teknologi Bangsa" oninput="updateSummary()">
              </div>

              <div class="row g-3">
                <div class="col-md-4">
                  <label for="customer_name" class="form-label fw-semibold small text-secondary">
                    Nama Lengkap <span class="text-danger">*</span>
                  </label>
                  <input type="text" name="customer_name" id="customer_name" required class="form-control" placeholder="Nama Anda" oninput="updateSummary()">
                </div>
                <div class="col-md-4">
                  <label for="customer_phone" class="form-label fw-semibold small text-secondary">
                    Nomor WhatsApp <span class="text-danger">*</span>
                  </label>
                  <input type="tel" name="customer_phone" id="customer_phone" required class="form-control" placeholder="08xxxxxxxxxx">
                </div>
                <div class="col-md-4">
                  <label for="customer_email" class="form-label fw-semibold small text-secondary">
                    Email (Opsional)
                  </label>
                  <input type="email" name="customer_email" id="customer_email" class="form-control" placeholder="nama@email.com">
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100 py-3 fs-6 shadow-sm d-flex align-items-center justify-content-center gap-2 rounded-3">
              <i data-lucide="send" style="width: 20px; height: 20px;"></i>
              <span>Terbitkan Tiket & Dapatkan Nomor Resi</span>
            </button>
          </form>

        </div>
      </div>

      <!-- RIGHT: LIVE SUMMARY & TRUST CARD -->
      <div class="col-lg-4">
        <div class="sticky-summary">
          
          <!-- Summary Card -->
          <div class="card card-bento p-4 mb-3 bg-white">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom d-flex align-items-center justify-content-between">
              <span>Ringkasan Estimasi</span>
              <span class="badge-status badge-status-info small">Pra-Diagnosa</span>
            </h6>

            <div class="small mb-3">
              <div class="d-flex justify-content-between text-secondary mb-1">
                <span>Perangkat:</span>
                <strong id="sumDevice" class="text-dark">Laptop</strong>
              </div>
              <div class="d-flex justify-content-between text-secondary mb-1">
                <span>Merek / Seri:</span>
                <span id="sumBrand" class="text-dark fw-semibold">-</span>
              </div>
              <div class="d-flex justify-content-between text-secondary mb-1">
                <span>Pelanggan:</span>
                <span id="sumCustomer" class="text-dark fw-semibold">-</span>
              </div>
              <div class="d-flex justify-content-between text-secondary">
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
                <span class="text-muted">Repasta Arctic MX-4:</span>
                <span class="fw-bold text-dark">Rp 75.000</span>
              </div>
              <hr class="my-2">
              <div class="d-flex justify-content-between align-items-center">
                <strong class="text-dark">Estimasi Awal:</strong>
                <span id="sumTotal" class="fs-5 fw-bold text-primary font-monospace">Rp 0</span>
              </div>
              <span class="text-muted d-block mt-1.5" style="font-size: 10px; line-height: 1.4;">
                *Biaya jasa utama & sparepart ditetapkan pasca-cek fisik di meja kerja workshop. Konsumen berhak menolak bila estimasi tidak disetujui.
              </span>
            </div>

            <div class="d-flex flex-column gap-2.5 small">
              <div class="d-flex align-items-center gap-2 text-secondary">
                <i data-lucide="shield-check" class="text-success flex-shrink-0" style="width: 18px; height: 18px;"></i>
                <span>Garansi 90 hari suku cadang & jasa</span>
              </div>
              <div class="d-flex align-items-center gap-2 text-secondary">
                <i data-lucide="lock" class="text-primary flex-shrink-0" style="width: 18px; height: 18px;"></i>
                <span>Data storage tidak dibuka tanpa izin</span>
              </div>
              <div class="d-flex align-items-center gap-2 text-secondary">
                <i data-lucide="qr-code" class="text-dark flex-shrink-0" style="width: 18px; height: 18px;"></i>
                <span>Pembayaran QRIS setelah biaya disetujui</span>
              </div>
            </div>
          </div>

          <!-- Bantuan Kontak -->
          <div class="card card-bento p-3 bg-white">
            <div class="d-flex align-items-center gap-2.5">
              <div class="icon-box-primary flex-shrink-0" style="width: 38px; height: 38px; font-size: 1.1rem;">
                <i data-lucide="headphones"></i>
              </div>
              <div class="small">
                <strong class="d-block text-dark">Butuh Bantuan?</strong>
                <span class="text-muted" style="font-size: 11px;">Hubungi hotline teknisi di WhatsApp 0812-9876-5432.</span>
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
        <div class="icon-box-success mx-auto mb-3" style="width: 56px; height: 56px; font-size: 1.75rem;">
          <i class="bi bi-check-lg"></i>
        </div>
        <h4 class="fw-bold mb-1" id="successModalLabel">Tiket Reservasi Terbit</h4>
        <p class="text-muted small mb-3">Nomor resi pelacakan unit servis Anda:</p>
        
        <div class="p-3 bg-light border rounded-3 mb-2 d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2">
          <div class="text-start">
            <span class="text-muted d-block small" style="font-size: 11px;">Nomor Resi Tiket:</span>
            <span id="createdTicketNo" class="fs-4 fw-bold font-monospace text-primary">#SC-2026-9042</span>
          </div>
          <button type="button" id="btnCopyTicket" onclick="copyTicketCode()" class="btn btn-outline-primary btn-sm fw-semibold py-1.5 px-3 d-inline-flex align-items-center gap-2">
            <i class="bi bi-clipboard" id="iconCopyTicket"></i>
            <span id="btnCopyText">Salin Tiket</span>
          </button>
        </div>

        <div class="p-3 bg-warning-subtle border border-warning-subtle rounded-3 mb-3 d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2">
          <div class="text-start">
            <span class="text-dark fw-bold d-block small" style="font-size: 11px;"><i class="bi bi-key-fill text-warning-emphasis me-1.5"></i>PIN Keamanan (Huruf, Simbol & Angka):</span>
            <span id="createdSecurityPin" class="fs-4 fw-bold font-monospace text-dark tracking-wider">K#7X</span>
          </div>
          <button type="button" id="btnCopyPin" onclick="copySecurityPin()" class="btn btn-outline-dark btn-sm fw-semibold py-1.5 px-3 d-inline-flex align-items-center gap-2">
            <i class="bi bi-clipboard" id="iconCopyPin"></i>
            <span id="btnCopyPinText">Salin PIN</span>
          </button>
        </div>

        <p class="small text-secondary mb-4">
          Data tersimpan di sistem. Anda dapat memantau diagnosa teknisi, menyetujui rincian biaya, dan membayar via QRIS di <strong>Portal Pelanggan</strong>.
        </p>

        <div class="d-grid gap-2">
          <a id="btnOpenUserPanel" href="{{ url('/user-panel') }}" class="btn btn-primary fw-bold py-2.5 d-inline-flex justify-content-center align-items-center gap-2">
            <i class="bi bi-box-arrow-up-right"></i>
            <span>Buka Portal Pelanggan Sekarang</span>
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

  function toggleCompanyInput(radio) {
    const wrapper = document.getElementById('companyNameWrapper');
    const compInput = document.getElementById('company_name');
    if (radio.value === 'Company') {
      wrapper.classList.remove('d-none');
      if (compInput) compInput.required = true;
    } else {
      wrapper.classList.add('d-none');
      if (compInput) {
        compInput.required = false;
        compInput.value = '';
      }
    }
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
    .then(async res => {
      const data = await res.json();
      if (!res.ok || !data.success) {
        let errorMsg = data.message || "Gagal menyimpan data reservasi.";
        if (data.errors) {
          const detail = Object.values(data.errors).flat().join("\n• ");
          errorMsg += "\n• " + detail;
        }
        throw new Error(errorMsg);
      }
      return data;
    })
    .then(data => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalBtnText;

      const ticketNo = data.ticket_no || (data.ticket && data.ticket.ticket_no);
      document.getElementById('createdTicketNo').innerText = ticketNo;
      if (data.security_pin) {
        document.getElementById('createdSecurityPin').innerText = data.security_pin;
      }
      const btnOpen = document.getElementById('btnOpenUserPanel');
      if (btnOpen) {
        btnOpen.href = data.redirect_url || ("{{ url('/user-panel') }}?ticket=" + encodeURIComponent(ticketNo));
      }

      try { localStorage.removeItem('sercomp_active_order'); } catch(e) {}

      if (!bsSuccessModal) {
        bsSuccessModal = new bootstrap.Modal(document.getElementById('successModal'));
      }
      bsSuccessModal.show();
    })
    .catch(err => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalBtnText;
      console.error(err);
      alert(err.message || 'Terjadi kendala saat memproses tiket di database.');
    });
  }

  function copySecurityPin() {
    const pin = document.getElementById('createdSecurityPin').innerText.trim();
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(pin).then(() => {
        showPinCopiedFeedback();
      }).catch(() => {
        fallbackCopy(pin);
      });
    } else {
      fallbackCopy(pin);
    }
  }

  function showPinCopiedFeedback() {
    const btnText = document.getElementById('btnCopyPinText');
    const icon = document.getElementById('iconCopyPin');
    const original = btnText.innerText;
    btnText.innerText = "Tersalin!";
    icon.className = "bi bi-check2 me-1";
    setTimeout(() => {
      btnText.innerText = original;
      icon.className = "bi bi-clipboard me-1";
    }, 2000);
  }

  function copyTicketCode() {
    const ticketText = document.getElementById('createdTicketNo').innerText.trim();
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(ticketText).then(() => {
        showCopiedFeedback();
      }).catch(() => {
        fallbackCopy(ticketText);
      });
    } else {
      fallbackCopy(ticketText);
    }
  }

  function showCopiedFeedback() {
    const btnText = document.getElementById('btnCopyText');
    const icon = document.getElementById('iconCopyTicket');
    const original = btnText.innerText;
    btnText.innerText = "Tersalin!";
    icon.className = "bi bi-check2 me-1";
    setTimeout(() => {
      btnText.innerText = original;
      icon.className = "bi bi-clipboard me-1";
    }, 2000);
  }

  function fallbackCopy(text) {
    const temp = document.createElement('textarea');
    temp.value = text;
    document.body.appendChild(temp);
    temp.select();
    document.execCommand('copy');
    document.body.removeChild(temp);
    showCopiedFeedback();
  }
</script>
@endsection
