@extends('layouts.main')

@section('title', 'SerComp - Servis Laptop & PC Desktop Transparan & Bergaransi')

@section('content')

<!-- HERO SECTION (CLEAN, CONFIDENT, MODERN BENTO) -->
<section class="hero-surface py-5 border-bottom overflow-hidden position-relative">
  <div class="container py-lg-4">
    <div class="row align-items-center g-5">
      
      <!-- Left Column: Value Proposition & Direct Action -->
      <div class="col-lg-7 text-center text-lg-start">
        
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-light border text-secondary small fw-semibold mb-3">
          <i class="bi bi-shield-check text-primary fs-6"></i>
          <span>Standar Servis Teruji &bull; Estimasi di Muka &bull; Garansi 90 Hari</span>
        </div>

        <h1 class="display-5 fw-bold text-dark mb-3 lh-sm" style="letter-spacing: -0.03em;">
          Servis Laptop & PC Desktop<br>
          <span class="text-primary">Estimasi Terkunci Sebelum Dibongkar.</span>
        </h1>

        <p class="lead text-secondary mb-4" style="font-size: 1.08rem; max-width: 620px;">
          Pemeriksaan fisik langsung di meja kerja workshop. Konsumen menyetujui rincian biaya di muka sebelum suku cadang diganti, lengkap dengan bukti foto komponen dan masa uji kepuasan 24 jam.
        </p>

        <!-- CTA & Quick Track In-Hero Form -->
        <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3 mb-4">
          <a href="{{ url('/booking') }}" class="btn btn-primary btn-lg px-4 py-3 shadow-sm d-inline-flex align-items-center gap-2">
            <i class="bi bi-calendar2-plus fs-5"></i>
            <span>Reservasi Servis Baru</span>
          </a>
          <a href="#lacak" class="btn btn-outline-secondary btn-lg px-4 py-3 d-inline-flex align-items-center gap-2">
            <i class="bi bi-search"></i>
            <span>Lacak Status Tiket</span>
          </a>
        </div>

        <!-- 3 Quick Guarantees Strip -->
        <div class="row g-3 text-start pt-3 border-top mt-2">
          <div class="col-4">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-lock-fill text-primary fs-4"></i>
              <div>
                <strong class="d-block text-dark small">Biaya Terkunci</strong>
                <span class="text-muted" style="font-size: 11px;">Wajib disetujui di muka</span>
              </div>
            </div>
          </div>
          <div class="col-4">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-patch-check-fill text-success fs-4"></i>
              <div>
                <strong class="d-block text-dark small">Garansi 90 Hari</strong>
                <span class="text-muted" style="font-size: 11px;">Jasa & suku cadang</span>
              </div>
            </div>
          </div>
          <div class="col-4">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-shield-lock-fill text-info fs-4"></i>
              <div>
                <strong class="d-block text-dark small">Privasi Storage</strong>
                <span class="text-muted" style="font-size: 11px;">Data tidak diakses</span>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Right Column: Live Interactive Ticket Card Preview -->
      <div class="col-lg-5">
        <div class="card card-bento card-bento-featured overflow-hidden">
          
          <!-- Card Header -->
          <div class="bg-gradient-hero p-4 text-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="badge-status badge-status-success small">
                <i class="bi bi-check2-circle"></i> Unit Siap Diambil
              </span>
              <span class="font-monospace small text-white-50">#SC-2026-9042</span>
            </div>
            <h5 class="fw-bold mb-0">Asus ROG Strix G15</h5>
            <span class="text-white-50 small">Keluhan: Suhu tinggi (95°C) & kipas pendingin berisik</span>
          </div>

          <!-- Card Body -->
          <div class="card-body p-4">
            
            <!-- Diagnostic Card Note -->
            <div class="mb-3.5 p-3 rounded-3 bg-light border-start border-primary border-3 border shadow-2xs">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary fw-semibold text-uppercase font-monospace" style="font-size: 0.6875rem; letter-spacing: 0.06em;">
                  <i class="bi bi-tools text-primary me-1.5"></i>Diagnosa Meja Kerja Workshop
                </span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5" style="font-size: 0.65rem;">
                  Terverifikasi Teknisi
                </span>
              </div>
              <div class="small text-dark lh-base">
                <div class="mb-1.5"><strong class="text-secondary">Temuan Fisik:</strong> Bearing kipas pendingin aus & pasta termal mengering.</div>
                <div><strong class="text-secondary">Tindakan:</strong> Modul pendingin original baru + penggantian Arctic MX-4.</div>
              </div>
            </div>

            <!-- Items Breakdown -->
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom small">
              <span class="text-secondary">Modul Fan Cooler Original Asus</span>
              <strong class="text-dark">Rp 220.000</strong>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom small">
              <span class="text-secondary">Jasa Teknisi & Uji Suhu 60 Menit</span>
              <strong class="text-dark">Rp 150.000</strong>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom small">
              <span class="text-secondary">Deep Cleaning & Arctic MX-4</span>
              <strong class="text-dark">Rp 125.000</strong>
            </div>

            <div class="d-flex justify-content-between align-items-center pt-3 mb-3">
              <div>
                <strong class="text-dark d-block">Total Biaya Disetujui:</strong>
                <span class="badge bg-success-subtle text-success small border border-success-subtle">
                  <i class="bi bi-check-circle me-1"></i> Lunas via QRIS Dinamis
                </span>
              </div>
              <span class="fs-4 fw-bold text-primary font-monospace">Rp 495.000</span>
            </div>

            <!-- Dual Evidence Section (Professional Proof Card) -->
            <div class="mb-3.5 p-3 rounded-3 bg-light border shadow-2xs">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary fw-semibold text-uppercase font-monospace" style="font-size: 0.6875rem; letter-spacing: 0.06em;">
                  <i class="bi bi-shield-check text-success me-1.5"></i>Dokumentasi Purnajual (Dual Evidence)
                </span>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.65rem;">
                  Lengkap 2 Foto
                </span>
              </div>
              <div class="row g-2">
                <div class="col-6">
                  <div class="p-2 rounded-2 bg-white border d-flex align-items-center gap-2">
                    <div class="icon-box-primary flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.85rem;">
                      <i class="bi bi-camera"></i>
                    </div>
                    <div class="overflow-hidden">
                      <strong class="d-block text-dark text-truncate" style="font-size: 0.75rem;">1. Running Test</strong>
                      <span class="text-success text-truncate d-block" style="font-size: 0.6875rem;">Suhu Stabil 68°C</span>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="p-2 rounded-2 bg-white border d-flex align-items-center gap-2">
                    <div class="icon-box-primary flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.85rem;">
                      <i class="bi bi-box-seam text-success"></i>
                    </div>
                    <div class="overflow-hidden">
                      <strong class="d-block text-dark text-truncate" style="font-size: 0.75rem;">2. Part Rusak</strong>
                      <span class="text-secondary text-truncate d-block" style="font-size: 0.6875rem;">Kembali ke Pemilik</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <a href="{{ url('/user-panel?ticket=SC-2026-9042') }}" class="btn btn-outline-primary w-100 fw-semibold d-inline-flex justify-content-center align-items-center gap-2 py-2.5">
              <span>Buka Tampilan Portal Pelanggan</span>
              <i class="bi bi-arrow-right"></i>
            </a>

          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- QUICK TRACK SEARCH BAR (INTEGRATED & CLEAN) -->
<section id="lacak" class="py-4 bg-white border-bottom">
  <div class="container">
    <div class="card card-bento p-3 p-md-4 bg-light border">
      <div class="row align-items-center g-3">
        <div class="col-lg-5">
          <div class="d-flex align-items-center gap-3">
            <div class="icon-box-primary flex-shrink-0">
              <i class="bi bi-search"></i>
            </div>
            <div>
              <h5 class="fw-bold text-dark mb-0.5">Lacak Status Tiket Servis</h5>
              <p class="text-muted small mb-0">
                Ketik nomor resi tiket untuk memeriksa lembar diagnosa dan progres unit Anda.
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-7">
          <form action="{{ url('/user-panel') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2">
            <label for="ticketInput" class="visually-hidden">Nomor Tiket Servis</label>
            <input type="text" name="ticket" id="ticketInput" class="form-control form-control-lg fs-6 font-monospace" placeholder="Masukkan nomor resi, cth: SC-2026-9042" required>
            <button type="submit" class="btn btn-primary btn-lg px-4 text-nowrap shadow-sm d-flex align-items-center justify-content-center gap-2">
              <i class="bi bi-arrow-right-circle"></i>
              <span>Lacak Unit</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 3 PILAR UTAMA TRANSPARANSI (MODERN BENTO GRID) -->
<section class="py-5 bg-white border-bottom">
  <div class="container py-lg-3">
    
    <div class="text-center mx-auto mb-5" style="max-width: 650px;">
      <span class="badge-status badge-status-info mb-2">5 Pilar Bisnis SerComp</span>
      <h2 class="fw-bold text-dark mb-2" style="letter-spacing: -0.02em;">Mengapa Memilih SerComp?</h2>
      <p class="text-secondary small">
        Solusi perbaikan komputer yang meniadakan ketidakpastian biaya dan menjamin keamanan perangkat Anda.
      </p>
    </div>

    <div class="row g-4">
      
      <!-- Bento 1: Estimasi Terkunci (Featured) -->
      <div class="col-lg-6">
        <div class="card card-bento p-4 p-md-5 h-100 d-flex flex-column justify-content-between">
          <div>
            <div class="icon-box-primary mb-4">
              <i class="bi bi-lock"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Estimasi Terkunci Pasca-Bongkar Meja Kerja</h4>
            <p class="text-secondary lh-base mb-4">
              Biaya servis tidak ditebak di awal. Teknisi memeriksa tegangan & kerusakan fisik di meja kerja workshop, menerbitkan lembar persetujuan resmi. Tidak ada biaya siluman—unit hanya dikerjakan setelah Anda menyetujui rinciannya.
            </p>
          </div>
          <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between small">
            <span class="text-muted"><i class="bi bi-check-circle-fill text-success me-1"></i> Hak Konsumen:</span>
            <strong class="text-dark">Bisa batal tanpa paksaan bila tidak cocok</strong>
          </div>
        </div>
      </div>

      <!-- Bento 2: Dual Evidence -->
      <div class="col-lg-6">
        <div class="card card-bento p-4 p-md-5 h-100 d-flex flex-column justify-content-between">
          <div>
            <div class="icon-box-success mb-4">
              <i class="bi bi-camera"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Bukti Foto Purnajual Ganda (Dual Evidence)</h4>
            <p class="text-secondary lh-base mb-4">
              Sebelum tiket diselesaikan, sistem mewajibkan teknisi mengunggah dua foto autentik: foto unit hidup normal lolos uji ketahanan suhu stabil, serta foto suku cadang lama yang diganti untuk diserahkan kembali kepada Anda.
            </p>
          </div>
          <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between small">
            <span class="text-muted"><i class="bi bi-shield-check text-primary me-1"></i> Standar Purnajual:</span>
            <strong class="text-dark">Sparepart bekas diserahkan utuh</strong>
          </div>
        </div>
      </div>

      <!-- Bento 3: e-Warranty & SLA 24h -->
      <div class="col-lg-6">
        <div class="card card-bento p-4 p-md-5 h-100 d-flex flex-column justify-content-between">
          <div>
            <div class="icon-box-primary mb-4">
              <i class="bi bi-patch-check"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Masa Uji 24 Jam & e-Warranty 90 Hari</h4>
            <p class="text-secondary lh-base mb-4">
              Setelah unit diserahkan, timer evaluasi 24 jam berjalan. Jika pelanggan tidak mengajukan keluhan, sistem menutup tiket otomatis dan menerbitkan sertifikat garansi digital 90 hari tanpa perlu menyimpan kartu kertas.
            </p>
          </div>
          <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between small">
            <span class="text-muted"><i class="bi bi-qr-code text-primary me-1"></i> Klaim Mudah:</span>
            <strong class="text-dark">Cukup sebutkan nomor resi tiket servis</strong>
          </div>
        </div>
      </div>

      <!-- Bento 4: Keamanan Data & Privasi -->
      <div class="col-lg-6">
        <div class="card card-bento p-4 p-md-5 h-100 d-flex flex-column justify-content-between">
          <div>
            <div class="icon-box-dark mb-4">
              <i class="bi bi-shield-lock"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Privasi Data & Sistem Boot Mandiri</h4>
            <p class="text-secondary lh-base mb-4">
              Uji tegangan komponen dan kestabilan perangkat dijalankan menggunakan media live-boot diagnostik khusus. Teknisi tidak membuka berkas personal, foto, maupun data dokumen kerja di storage Anda.
            </p>
          </div>
          <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between small">
            <span class="text-muted"><i class="bi bi-file-earmark-lock text-info me-1"></i> Kebijakan Privasi:</span>
            <strong class="text-dark">Storage terkunci & bebas dari akses</strong>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- KATALOG TARIF LAYANAN (CLEAN, TRANSPARENT PRICING) -->
<section id="layanan" class="py-5 bg-light border-bottom">
  <div class="container py-lg-3">
    
    <div class="text-center mx-auto mb-5" style="max-width: 650px;">
      <span class="badge-status badge-status-info mb-2">Katalog Tarif Servis</span>
      <h2 class="fw-bold text-dark mb-2" style="letter-spacing: -0.02em;">Standar Biaya Jasa Perbaikan</h2>
      <p class="text-secondary small">
        Biaya jasa transparan dengan garansi 90 hari untuk laptop dan PC desktop arsitektur Windows & Linux.
      </p>
    </div>

    <div class="row g-4">
      
      <!-- Layanan 1 -->
      <div class="col-md-6 col-lg-4">
        <div class="card card-bento p-4 h-100 d-flex flex-column justify-content-between bg-white">
          <div>
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="icon-box-primary">
                <i class="bi bi-fan"></i>
              </div>
              <span class="badge-status badge-status-neutral small">Perawatan Suhu</span>
            </div>
            <h5 class="fw-bold text-dark mb-2">Deep Cleaning & Repasta</h5>
            <p class="text-secondary small mb-3">
              Pembersihan debu pada kipas exhaust heatsink dan penggantian pasta termal CPU/GPU menggunakan Arctic MX-4 kualitas industri.
            </p>
          </div>
          <div class="pt-3 border-top d-flex justify-content-between align-items-center">
            <span class="text-muted small">Tarif Jasa:</span>
            <strong class="text-primary fs-5 font-monospace">Rp 125.000</strong>
          </div>
        </div>
      </div>

      <!-- Layanan 2 -->
      <div class="col-md-6 col-lg-4">
        <div class="card card-bento p-4 h-100 d-flex flex-column justify-content-between bg-white">
          <div>
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="icon-box-primary">
                <i class="bi bi-cpu"></i>
              </div>
              <span class="badge-status badge-status-neutral small">Penggantian Komponen</span>
            </div>
            <h5 class="fw-bold text-dark mb-2">Ganti Suku Cadang Laptop</h5>
            <p class="text-secondary small mb-3">
              Penggantian baterai drop, keyboard macet, layar LCD bergaris, dan kipas pendingin original dengan jaminan garansi distributor resmi.
            </p>
          </div>
          <div class="pt-3 border-top d-flex justify-content-between align-items-center">
            <span class="text-muted small">Jasa Pasang:</span>
            <strong class="text-primary fs-5 font-monospace">Rp 100.000 <span class="fs-6 text-muted fw-normal">+ Part</span></strong>
          </div>
        </div>
      </div>

      <!-- Layanan 3 -->
      <div class="col-md-6 col-lg-4">
        <div class="card card-bento p-4 h-100 d-flex flex-column justify-content-between bg-white">
          <div>
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="icon-box-success">
                <i class="bi bi-speedometer2"></i>
              </div>
              <span class="badge-status badge-status-neutral small">Upgrade Hardware</span>
            </div>
            <h5 class="fw-bold text-dark mb-2">Upgrade SSD NVMe & RAM</h5>
            <p class="text-secondary small mb-3">
              Kloning sistem operasi tanpa hilang data, penambahan RAM DDR4/DDR5 dual-channel, dan konfigurasi SSD Gen 3 / Gen 4 berkecepatan tinggi.
            </p>
          </div>
          <div class="pt-3 border-top d-flex justify-content-between align-items-center">
            <span class="text-muted small">Jasa Kloning:</span>
            <strong class="text-primary fs-5 font-monospace">Rp 75.000 <span class="fs-6 text-muted fw-normal">+ Part</span></strong>
          </div>
        </div>
      </div>

      <!-- Layanan 4 -->
      <div class="col-md-6 col-lg-4">
        <div class="card card-bento p-4 h-100 d-flex flex-column justify-content-between bg-white">
          <div>
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="icon-box-primary">
                <i class="bi bi-lightning-charge"></i>
              </div>
              <span class="badge-status badge-status-warning small">Micro-Soldering</span>
            </div>
            <h5 class="fw-bold text-dark mb-2">Perbaikan Motherboard & IC</h5>
            <p class="text-secondary small mb-3">
              Penanganan laptop mati total, korsleting jalur power 19V, penggantian kapasitor bocor, IC regulator pengisian daya, dan flash corrupt BIOS.
            </p>
          </div>
          <div class="pt-3 border-top d-flex justify-content-between align-items-center">
            <span class="text-muted small">Mulai dari:</span>
            <strong class="text-primary fs-5 font-monospace">Rp 250.000</strong>
          </div>
        </div>
      </div>

      <!-- Layanan 5 -->
      <div class="col-md-6 col-lg-4">
        <div class="card card-bento p-4 h-100 d-flex flex-column justify-content-between bg-white">
          <div>
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="icon-box-primary">
                <i class="bi bi-pc-display"></i>
              </div>
              <span class="badge-status badge-status-neutral small">PC Desktop</span>
            </div>
            <h5 class="fw-bold text-dark mb-2">Perakitan PC & Manajemen Kabel</h5>
            <p class="text-secondary small mb-3">
              Perakitan PC baru, perapihan kabel PSU (cable routing), pengaturan kurva kipas BIOS agar hening, dan stress test kestabilan 100% beban kerja.
            </p>
          </div>
          <div class="pt-3 border-top d-flex justify-content-between align-items-center">
            <span class="text-muted small">Tarif Rakit:</span>
            <strong class="text-primary fs-5 font-monospace">Rp 150.000</strong>
          </div>
        </div>
      </div>

      <!-- Layanan 6 -->
      <div class="col-md-6 col-lg-4">
        <div class="card card-bento p-4 h-100 d-flex flex-column justify-content-between bg-white">
          <div>
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="icon-box-primary">
                <i class="bi bi-window-stack"></i>
              </div>
              <span class="badge-status badge-status-neutral small">Software Sistem</span>
            </div>
            <h5 class="fw-bold text-dark mb-2">Instalasi Bersih OS & Driver</h5>
            <p class="text-secondary small mb-3">
              Instalasi bersih Windows 11 atau distribusi Linux, instalasi driver resmi vendor perangkat keras, dan software utilitas bebas bloatware.
            </p>
          </div>
          <div class="pt-3 border-top d-flex justify-content-between align-items-center">
            <span class="text-muted small">Tarif Jasa:</span>
            <strong class="text-primary fs-5 font-monospace">Rp 80.000</strong>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- 4 ALUR KERJA SERVIS TRANSPARAN (CONNECTED TIMELINE) -->
<section id="alur-kerja" class="py-5 bg-white border-bottom">
  <div class="container py-lg-3">
    
    <div class="text-center mx-auto mb-5" style="max-width: 650px;">
      <span class="badge-status badge-status-info mb-2">Alur Servis</span>
      <h2 class="fw-bold text-dark mb-2" style="letter-spacing: -0.02em;">4 Langkah Servis Transparan</h2>
      <p class="text-secondary small">
        Setiap tahap terdokumentasi dan dapat dipantau langsung lewat portal digital SerComp.
      </p>
    </div>

    <div class="row g-4">
      
      <!-- Step 1 -->
      <div class="col-md-6 col-lg-3">
        <div class="card card-bento p-4 h-100 text-center">
          <div class="workflow-step-num active mx-auto mb-3">1</div>
          <h6 class="fw-bold text-dark mb-2">Reservasi Tiket</h6>
          <p class="text-secondary small mb-0">
            Isi formulir kerusakan perangkat, unggah foto fisik, dan dapatkan nomor resi unik seperti <span class="font-monospace text-primary">SC-2026-XXXX</span>.
          </p>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="col-md-6 col-lg-3">
        <div class="card card-bento p-4 h-100 text-center">
          <div class="workflow-step-num mx-auto mb-3">2</div>
          <h6 class="fw-bold text-dark mb-2">Diagnosa & Persetujuan</h6>
          <p class="text-secondary small mb-0">
            Teknisi memeriksa unit di workshop dan menerbitkan estimasi biaya. Unit baru dibongkar setelah Anda memberikan persetujuan resmi.
          </p>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="col-md-6 col-lg-3">
        <div class="card card-bento p-4 h-100 text-center">
          <div class="workflow-step-num mx-auto mb-3">3</div>
          <h6 class="fw-bold text-dark mb-2">Bayar QRIS Dinamis</h6>
          <p class="text-secondary small mb-0">
            Pembayaran biaya reparasi via scan QRIS otomatis. Pengerjaan teknisi langsung berjalan seketika pembayaran terkonfirmasi.
          </p>
        </div>
      </div>

      <!-- Step 4 -->
      <div class="col-md-6 col-lg-3">
        <div class="card card-bento p-4 h-100 text-center">
          <div class="workflow-step-num mx-auto mb-3">4</div>
          <h6 class="fw-bold text-dark mb-2">Serah Terima & Garansi</h6>
          <p class="text-secondary small mb-0">
            Unit diserahkan dengan bukti foto purnajual ganda. Masa uji 24 jam berjalan untuk mengaktifkan sertifikat digital garansi 90 hari.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- CALL TO ACTION SECTION (HUMAN & CLEAN) -->
<section class="py-5 bg-light border-bottom">
  <div class="container py-lg-3">
    <div class="card card-bento bg-gradient-hero p-4 p-md-5 text-white border-0 shadow-lg">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <div class="badge-status badge-status-info small mb-3">
            <i class="bi bi-tools"></i> Teknisi Siap Menangani Unit Anda
          </div>
          <h3 class="fw-bold mb-2">Perangkat Anda Butuh Perbaikan Cepat?</h3>
          <p class="text-white-50 mb-0 small" style="max-width: 580px;">
            Lakukan reservasi secara mandiri dalam 2 menit. Dapatkan estimasi akurat di muka dan pantau progres perbaikan dari mana saja.
          </p>
        </div>
        <div class="col-lg-4 text-lg-end">
          <a href="{{ url('/booking') }}" class="btn btn-primary btn-lg px-4 py-3 shadow-sm d-inline-flex align-items-center gap-2">
            <i class="bi bi-calendar2-plus"></i>
            <span>Buka Formulir Reservasi</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
