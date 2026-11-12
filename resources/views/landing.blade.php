@extends('layouts.main')

@section('title', 'SerComp - Servis Laptop & PC Desktop Transparan & Bergaransi')

@section('content')

<!-- HERO SECTION (CENTERED HEADING, SUBHEADING, CTAS & LARGE PRODUCT PREVIEW BELOW) -->
<section class="hero-surface pt-5 pb-0 border-bottom overflow-hidden position-relative">
  <div class="container pt-lg-4 text-center">
    
    <!-- Top Trust Pill Badge -->
    <div class="d-inline-flex align-items-center gap-2 px-3.5 py-1.5 rounded-pill bg-white border shadow-2xs text-secondary small fw-semibold mb-3">
      <i data-lucide="shield-check" class="text-primary" style="width: 16px; height: 16px;"></i>
      <span>Standar Servis Teruji &bull; Estimasi di Muka &bull; Garansi 90 Hari</span>
    </div>

    <!-- Centered Main Heading -->
    <h1 class="display-5 fw-bold text-dark mb-3 mx-auto lh-sm" style="letter-spacing: -0.03em; max-width: 820px;">
      Servis Laptop & PC Desktop Transparan.<br>
      <span class="text-primary">Estimasi Terkunci Sebelum Dibongkar.</span>
    </h1>

    <!-- Subheading -->
    <p class="lead text-secondary mb-4 mx-auto" style="font-size: 1.1rem; max-width: 660px; line-height: 1.6;">
      Pemeriksaan fisik langsung di meja kerja workshop. Konsumen menyetujui rincian biaya di muka sebelum suku cadang diganti, lengkap dengan dokumentasi foto purnajual dan masa uji kepuasan 24 jam.
    </p>

    <!-- Centered CTAs & Guarantee Tags -->
    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 mb-4">
      <a href="{{ url('/booking') }}" class="btn btn-primary btn-lg px-4 py-3 shadow-sm d-inline-flex align-items-center gap-2">
        <i data-lucide="calendar-plus" style="width: 20px; height: 20px;"></i>
        <span>Reservasi Servis Baru</span>
      </a>
      <a href="#lacak" class="btn btn-outline-secondary btn-lg px-4 py-3 d-inline-flex align-items-center gap-2 bg-white">
        <i data-lucide="search" style="width: 18px; height: 18px;"></i>
        <span>Lacak Status Tiket</span>
      </a>
    </div>

    <!-- 3 Core Guarantees Row -->
    <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 gap-md-5 text-secondary small pt-2 mb-5">
      <div class="d-inline-flex align-items-center gap-2">
        <i data-lucide="lock" class="text-primary" style="width: 18px; height: 18px;"></i>
        <span class="text-dark fw-semibold">Biaya Terkunci di Muka</span>
      </div>
      <div class="d-inline-flex align-items-center gap-2">
        <i data-lucide="badge-check" class="text-success" style="width: 18px; height: 18px;"></i>
        <span class="text-dark fw-semibold">Garansi 90 Hari Penuh</span>
      </div>
      <div class="d-inline-flex align-items-center gap-2">
        <i data-lucide="shield" class="text-info" style="width: 18px; height: 18px;"></i>
        <span class="text-dark fw-semibold">Privasi Storage 100% Aman</span>
      </div>
    </div>

    <!-- LARGE PRODUCT SCREENSHOT / INTERACTIVE PREVIEW DIRECTLY BELOW -->
    <div class="row justify-content-center">
      <div class="col-lg-11 col-xl-10">
        
        <!-- Mock Browser / App Shell Frame -->
        <div class="card card-bento p-0 border shadow-lg overflow-hidden text-start" style="border-radius: 16px 16px 0 0; border-bottom: none !important;">
          
          <!-- Browser / Window Header Toolbar -->
          <div class="bg-dark px-4 py-3 d-flex align-items-center justify-content-between border-bottom border-dark-subtle text-white">
            <div class="d-flex align-items-center gap-2">
              <span class="d-inline-block rounded-circle bg-danger" style="width: 10px; height: 10px; opacity: 0.8;"></span>
              <span class="d-inline-block rounded-circle bg-warning" style="width: 10px; height: 10px; opacity: 0.8;"></span>
              <span class="d-inline-block rounded-circle bg-success" style="width: 10px; height: 10px; opacity: 0.8;"></span>
              <span class="ms-2 font-monospace text-white-50 small d-none d-sm-inline" style="font-size: 11px;">
                sercomp.id/portal/ticket/SC-2026-9042
              </span>
            </div>
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-success-subtle text-success border border-success-subtle font-monospace d-inline-flex align-items-center gap-1" style="font-size: 11px;">
                <i data-lucide="check-circle-2" style="width: 14px; height: 14px;"></i> LIVE TRACKING WORKSHOP
              </span>
            </div>
          </div>

          <!-- Inside Application Mockup View -->
          <div class="p-3.5 p-md-4 bg-light">
            
            <!-- App Bar Inside -->
            <div class="bg-white p-3 p-md-3.5 rounded-3 border mb-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 shadow-2xs">
              <div class="d-flex align-items-center gap-3">
                <div class="brand-box flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.15rem;">SC</div>
                <div>
                  <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold text-dark fs-6">Asus ROG Strix G15</span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace" style="font-size: 11px;">#SC-2026-9042</span>
                  </div>
                  <span class="text-secondary small">Pemilik: <strong>Dzul Khair Aropian</strong> &bull; Masuk: 27 Okt 2026</span>
                </div>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="badge-status badge-status-success fw-bold d-inline-flex align-items-center gap-1">
                  <i data-lucide="check-circle" style="width: 14px; height: 14px;"></i> Unit Siap Diserahkan
                </span>
                <a href="{{ url('/user-panel?ticket=SC-2026-9042') }}" class="btn btn-sm btn-outline-primary fw-semibold px-3 py-1.5 d-none d-sm-inline-flex align-items-center gap-1">
                  <span>Buka Portal</span>
                  <i data-lucide="external-link" style="width: 13px; height: 13px;"></i>
                </a>
              </div>
            </div>

            <!-- Bento Content Breakdown Inside Product Frame -->
            <div class="row g-3">
              
              <!-- Left: Diagnosa & Tindakan -->
              <div class="col-lg-7">
                <div class="card h-100 border rounded-3 p-3.5 bg-white shadow-2xs">
                  <div class="d-flex align-items-center justify-content-between pb-2 mb-3 border-bottom">
                    <div class="d-inline-flex align-items-center gap-2">
                      <i data-lucide="cpu" class="text-primary" style="width: 16px; height: 16px;"></i>
                      <strong class="text-dark small text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">Hasil Pemeriksaan Meja Kerja</strong>
                    </div>
                    <span class="badge bg-light text-secondary border" style="font-size: 10px;">Teknisi: Budi Santoso</span>
                  </div>

                  <div class="mb-3">
                    <span class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px;">Temuan Fisik Hardware:</span>
                    <p class="small text-dark mb-0 lh-base">
                      Bearing kipas pendingin GPU macet total berkerak debu tebal, thermal paste mengeras menjadi bubuk. Suhu idle mencapai 85°C.
                    </p>
                  </div>

                  <div class="p-2.5 rounded-2 bg-light border mb-3">
                    <span class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px;">Rencana Tindakan SOP:</span>
                    <span class="small text-dark fw-semibold">
                      Penggantian modul dual fan cooler baru OEM + deep cleaning kisi-kisi heatsink + thermal paste Arctic MX-4.
                    </span>
                  </div>

                  <!-- Dual Evidence Badges -->
                  <div class="row g-2 pt-1 border-top mt-auto">
                    <div class="col-6">
                      <div class="p-2 rounded-2 bg-light border d-flex align-items-center gap-2">
                        <i data-lucide="camera" class="text-primary flex-shrink-0" style="width: 18px; height: 18px;"></i>
                        <div class="text-truncate">
                          <strong class="d-block text-dark text-truncate" style="font-size: 11px;">1. Running Test</strong>
                          <span class="text-success text-truncate d-block" style="font-size: 10px;">68°C Full Load Stabil</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="p-2 rounded-2 bg-light border d-flex align-items-center gap-2">
                        <i data-lucide="package" class="text-success flex-shrink-0" style="width: 18px; height: 18px;"></i>
                        <div class="text-truncate">
                          <strong class="d-block text-dark text-truncate" style="font-size: 11px;">2. Suku Cadang Lama</strong>
                          <span class="text-secondary text-truncate d-block" style="font-size: 10px;">Dikembalikan ke Dus</span>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              <!-- Right: Rincian Biaya & QRIS -->
              <div class="col-lg-5">
                <div class="card h-100 border rounded-3 p-3.5 bg-white shadow-2xs d-flex flex-column justify-content-between">
                  <div>
                    <div class="d-flex align-items-center justify-content-between pb-2 mb-2.5 border-bottom">
                      <strong class="text-dark small text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">Rincian Biaya Terkunci</strong>
                      <span class="badge bg-primary text-uppercase font-monospace" style="font-size: 9px;">Zero Hidden Cost</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-1.5 border-bottom small">
                      <span class="text-secondary">Modul Fan Cooler Asus</span>
                      <span class="font-monospace fw-semibold text-dark">Rp 220.000</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-1.5 border-bottom small">
                      <span class="text-secondary">Jasa Teknisi & Stresstest</span>
                      <span class="font-monospace fw-semibold text-dark">Rp 150.000</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-1.5 border-bottom small">
                      <span class="text-secondary">Deep Cleaning & Repasta</span>
                      <span class="font-monospace fw-semibold text-dark">Rp 125.000</span>
                    </div>

                    <div class="p-2.5 rounded-2 bg-light border mt-2.5 d-flex justify-content-between align-items-center">
                      <span class="fw-bold text-dark small">Total Biaya:</span>
                      <span class="fs-5 fw-bold text-primary font-monospace">Rp 495.000</span>
                    </div>
                  </div>

                  <div class="pt-3 border-top mt-3">
                    <div class="d-flex align-items-center justify-content-between small">
                      <span class="text-secondary">Metode Bayar:</span>
                      <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold d-inline-flex align-items-center gap-1">
                        <i data-lucide="check-circle-2" style="width: 13px; height: 13px;"></i> QRIS DINAMIS LUNAS
                      </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between small mt-1">
                      <span class="text-secondary">Proteksi:</span>
                      <span class="text-dark fw-semibold">E-Warranty 90 Hari</span>
                    </div>
                  </div>

                </div>
              </div>

            </div>

          </div>
        </div>

      </div>
    </div>

  </div>
</section>

<!-- STATS BAR (HORIZONTAL ROW OF FOUR LARGE NUMBERS) -->
<section class="py-4 py-lg-5 bg-white border-bottom">
  <div class="container">
    <div class="row g-4 text-center justify-content-center">
      
      <div class="col-6 col-lg-3">
        <div class="p-3">
          <div class="display-6 fw-bold text-primary font-monospace mb-1" style="letter-spacing: -0.03em;">
            4.250+
          </div>
          <span class="text-secondary small fw-medium d-block">
            Unit Laptop & PC Ditangani
          </span>
        </div>
      </div>

      <div class="col-6 col-lg-3">
        <div class="p-3">
          <div class="display-6 fw-bold text-dark font-monospace mb-1" style="letter-spacing: -0.03em;">
            90 Hari
          </div>
          <span class="text-secondary small fw-medium d-block">
            Jaminan Garansi Part & Jasa
          </span>
        </div>
      </div>

      <div class="col-6 col-lg-3">
        <div class="p-3">
          <div class="display-6 fw-bold text-success font-monospace mb-1" style="letter-spacing: -0.03em;">
            98,4%
          </div>
          <span class="text-secondary small fw-medium d-block">
            Tingkat Keberhasilan Servis
          </span>
        </div>
      </div>

      <div class="col-6 col-lg-3">
        <div class="p-3">
          <div class="display-6 fw-bold text-dark font-monospace mb-1" style="letter-spacing: -0.03em;">
            0 Biaya
          </div>
          <span class="text-secondary small fw-medium d-block">
            Biaya Tersembunyi (Zero Hidden Cost)
          </span>
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
              <i data-lucide="search"></i>
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
              <i data-lucide="arrow-right-circle" style="width: 18px; height: 18px;"></i>
              <span>Lacak Unit</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 3-COLUMN FEATURE GRID (ICON, TITLE, SHORT DESCRIPTION) -->
<section class="py-5 bg-light border-bottom">
  <div class="container py-lg-3">
    
    <div class="text-center mx-auto mb-5" style="max-width: 680px;">
      <span class="badge-status badge-status-info mb-2">Standar Kualitas</span>
      <h2 class="fw-bold text-dark mb-2" style="letter-spacing: -0.02em;">Keunggulan Standar Workshop SerComp</h2>
      <p class="text-secondary small">
        Tiga prinsip fundamental yang melindungi kepuasan, privasi data, dan anggaran biaya reparasi Anda.
      </p>
    </div>

    <div class="row g-4">
      
      <!-- Feature 1 -->
      <div class="col-md-4">
        <div class="card card-bento p-4 p-lg-4 h-100 bg-white border">
          <div class="icon-box-primary mb-3">
            <i data-lucide="shield-check"></i>
          </div>
          <h5 class="fw-bold text-dark mb-2">Keamanan Storage Terjamin</h5>
          <p class="text-secondary small mb-0 lh-base">
            Data pribadi pada SSD/HDD tidak diakses atau dibuka oleh teknisi. Integritas sistem file dan privasi dokumen Anda terlindungi protokol workshop ketat.
          </p>
        </div>
      </div>

      <!-- Feature 2 -->
      <div class="col-md-4">
        <div class="card card-bento p-4 p-lg-4 h-100 bg-white border">
          <div class="icon-box-success mb-3">
            <i data-lucide="camera"></i>
          </div>
          <h5 class="fw-bold text-dark mb-2">Dual-Evidence Purnajual</h5>
          <p class="text-secondary small mb-0 lh-base">
            Dokumentasi lengkap foto running-test suhu stabil serta foto fisik modul lama yang diganti untuk diserahkan kembali secara utuh ke tangan Anda.
          </p>
        </div>
      </div>

      <!-- Feature 3 -->
      <div class="col-md-4">
        <div class="card card-bento p-4 p-lg-4 h-100 bg-white border">
          <div class="icon-box-primary mb-3">
            <i data-lucide="badge-check"></i>
          </div>
          <h5 class="fw-bold text-dark mb-2">e-Warranty 90 Hari & Uji 24h</h5>
          <p class="text-secondary small mb-0 lh-base">
            Masa uji kepuasan 24 jam pasca-penyerahan unit sebelum tiket ditutup otomatis, diikuti jaminan digital 90 hari tanpa repot nota fisik.
          </p>
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
              <i data-lucide="lock"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Estimasi Terkunci Pasca-Bongkar Meja Kerja</h4>
            <p class="text-secondary lh-base mb-4">
              Biaya servis tidak ditebak di awal. Teknisi memeriksa tegangan & kerusakan fisik di meja kerja workshop, menerbitkan lembar persetujuan resmi. Tidak ada biaya siluman—unit hanya dikerjakan setelah Anda menyetujui rinciannya.
            </p>
          </div>
          <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between small">
            <span class="text-muted d-inline-flex align-items-center gap-1.5"><i data-lucide="check-circle" class="text-success" style="width: 15px; height: 15px;"></i> Hak Konsumen:</span>
            <strong class="text-dark">Bisa batal tanpa paksaan bila tidak cocok</strong>
          </div>
        </div>
      </div>

      <!-- Bento 2: Dual Evidence -->
      <div class="col-lg-6">
        <div class="card card-bento p-4 p-md-5 h-100 d-flex flex-column justify-content-between">
          <div>
            <div class="icon-box-success mb-4">
              <i data-lucide="camera"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Bukti Foto Purnajual Ganda (Dual Evidence)</h4>
            <p class="text-secondary lh-base mb-4">
              Sebelum tiket diselesaikan, sistem mewajibkan teknisi mengunggah dua foto autentik: foto unit hidup normal lolos uji ketahanan suhu stabil, serta foto suku cadang lama yang diganti untuk diserahkan kembali kepada Anda.
            </p>
          </div>
          <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between small">
            <span class="text-muted d-inline-flex align-items-center gap-1.5"><i data-lucide="shield-check" class="text-primary" style="width: 15px; height: 15px;"></i> Standar Purnajual:</span>
            <strong class="text-dark">Sparepart bekas diserahkan utuh</strong>
          </div>
        </div>
      </div>

      <!-- Bento 3: e-Warranty & SLA 24h -->
      <div class="col-lg-6">
        <div class="card card-bento p-4 p-md-5 h-100 d-flex flex-column justify-content-between">
          <div>
            <div class="icon-box-primary mb-4">
              <i data-lucide="badge-check"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Masa Uji 24 Jam & e-Warranty 90 Hari</h4>
            <p class="text-secondary lh-base mb-4">
              Setelah unit diserahkan, timer evaluasi 24 jam berjalan. Jika pelanggan tidak mengajukan keluhan, sistem menutup tiket otomatis dan menerbitkan sertifikat garansi digital 90 hari tanpa perlu menyimpan kartu kertas.
            </p>
          </div>
          <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between small">
            <span class="text-muted d-inline-flex align-items-center gap-1.5"><i data-lucide="qr-code" class="text-primary" style="width: 15px; height: 15px;"></i> Klaim Mudah:</span>
            <strong class="text-dark">Cukup sebutkan nomor resi tiket servis</strong>
          </div>
        </div>
      </div>

      <!-- Bento 4: Keamanan Data & Privasi -->
      <div class="col-lg-6">
        <div class="card card-bento p-4 p-md-5 h-100 d-flex flex-column justify-content-between">
          <div>
            <div class="icon-box-dark mb-4">
              <i data-lucide="shield-alert"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Privasi Data & Sistem Boot Mandiri</h4>
            <p class="text-secondary lh-base mb-4">
              Uji tegangan komponen dan kestabilan perangkat dijalankan menggunakan media live-boot diagnostik khusus. Teknisi tidak membuka berkas personal, foto, maupun data dokumen kerja di storage Anda.
            </p>
          </div>
          <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between small">
            <span class="text-muted d-inline-flex align-items-center gap-1.5"><i data-lucide="file-lock-2" class="text-info" style="width: 15px; height: 15px;"></i> Kebijakan Privasi:</span>
            <strong class="text-dark">Storage terkunci & bebas dari akses</strong>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- KATALOG TARIF LAYANAN (CLEAN, TRANSPARENT PRICING) -->
<section id="layanan" class="py-5 bg-light border-bottom" style="scroll-margin-top: 70px;">
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
                <i data-lucide="fan"></i>
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
                <i data-lucide="cpu"></i>
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
                <i data-lucide="hard-drive"></i>
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
                <i data-lucide="zap"></i>
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
                <i data-lucide="monitor"></i>
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
                <i data-lucide="shield-check"></i>
              </div>
              <span class="badge-status badge-status-neutral small">Windows & Linux</span>
            </div>
            <h5 class="fw-bold text-dark mb-2">Instal Ulang & Optimalisasi OS</h5>
            <p class="text-secondary small mb-3">
              Pemasangan driver resmi perangkat keras tanpa bloatware, aktivasi lisensi original, konfigurasi antivirus, dan pembersihan malware.
            </p>
          </div>
          <div class="pt-3 border-top d-flex justify-content-between align-items-center">
            <span class="text-muted small">Tarif Layanan:</span>
            <strong class="text-primary fs-5 font-monospace">Rp 75.000</strong>
          </div>
        </div>
      </div>
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
<section id="alur-kerja" class="py-5 bg-white border-bottom" style="scroll-margin-top: 70px;">
  <div class="container py-lg-4">
    
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

<!-- SECTION JAMINAN GARANSI 90 HARI & INTEGRITAS SERVIS -->
<section id="garansi" class="py-5 bg-light border-bottom" style="scroll-margin-top: 70px;">
  <div class="container py-lg-4">
    <div class="row align-items-center g-5">
      <div class="col-lg-5">
        <div class="card card-bento p-4 p-md-5 border-0 bg-white shadow-sm position-relative overflow-hidden">
          <div class="position-absolute top-0 end-0 bg-primary text-white px-3 py-1 fw-bold font-monospace" style="font-size: 11px; border-bottom-left-radius: 10px;">
            E-WARRANTY 90D
          </div>
          <div class="icon-box-primary mb-3" style="width: 52px; height: 52px; font-size: 1.5rem;">
            <i data-lucide="shield-check"></i>
          </div>
          <h4 class="fw-bold text-dark mb-2">Jaminan Garansi 90 Hari Penuh</h4>
          <p class="text-secondary small mb-4">
            Perlindungan purnajual menyeluruh mencakup suku cadang yang diganti serta jasa teknisi profesional SerComp.
          </p>
          
          <ul class="list-unstyled d-flex flex-column gap-2 mb-0 small">
            <li class="d-flex align-items-center gap-2 text-dark">
              <i data-lucide="badge-check" class="text-success flex-shrink-0" style="width: 18px; height: 18px;"></i>
              <span><strong>Masa Uji Kepuasan 24 Jam:</strong> Pembatalan atau klaim tanpa biaya jika unit belum optimal.</span>
            </li>
            <li class="d-flex align-items-center gap-2 text-dark">
              <i data-lucide="badge-check" class="text-success flex-shrink-0" style="width: 18px; height: 18px;"></i>
              <span><strong>Klaim Bebas Ribet:</strong> Cukup tunjukkan nomor tiket digital tanpa kartu fisik.</span>
            </li>
            <li class="d-flex align-items-center gap-2 text-dark">
              <i data-lucide="badge-check" class="text-success flex-shrink-0" style="width: 18px; height: 18px;"></i>
              <span><strong>Part Original Berkualitas:</strong> Garansi langsung tukar baru bila sparepart bermasalah.</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="ps-lg-3">
          <span class="badge-status badge-status-success mb-2">Perlindungan Pelanggan</span>
          <h2 class="fw-bold text-dark mb-3" style="letter-spacing: -0.02em;">Standar Garansi Tanpa Kompromi</h2>
          <p class="text-secondary mb-4">
            Kami memastikan setiap unit yang keluar dari meja kerja telah melalui uji beban (stress-test) dan pemeriksaan fisik berlapis. Garansi aktif otomatis secara digital begitu unit Anda terima.
          </p>

          <div class="row g-3">
            <div class="col-sm-6">
              <div class="p-3 bg-white rounded-3 border">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <i data-lucide="cpu" class="text-primary" style="width: 18px; height: 18px;"></i>
                  <strong class="text-dark small">Garansi Suku Cadang</strong>
                </div>
                <p class="text-secondary small mb-0" style="font-size: 12px;">
                  Motherboard IC, keyboard, LCD, kipas pendingin, RAM, dan SSD bergaransi tukar baru selama 90 hari.
                </p>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="p-3 bg-white rounded-3 border">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <i data-lucide="wrench" class="text-success" style="width: 18px; height: 18px;"></i>
                  <strong class="text-dark small">Garansi Jasa Pengerjaan</strong>
                </div>
                <p class="text-secondary small mb-0" style="font-size: 12px;">
                  Bebas biaya servis ulang untuk kerusakan berulang pada komponen perbaikan yang sama.
                </p>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="p-3 bg-white rounded-3 border">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <i data-lucide="shield-check" class="text-info" style="width: 18px; height: 18px;"></i>
                  <strong class="text-dark small">Jaminan Privasi Data</strong>
                </div>
                <p class="text-secondary small mb-0" style="font-size: 12px;">
                  Integritas storage Anda terjamin aman tanpa pembongkaran partisi sistem pribadi.
                </p>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="p-3 bg-white rounded-3 border">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <i data-lucide="qr-code" class="text-warning" style="width: 18px; height: 18px;"></i>
                  <strong class="text-dark small">e-Warranty Otomatis</strong>
                </div>
                <p class="text-secondary small mb-0" style="font-size: 12px;">
                  Sertifikat digital terbit otomatis pada portal pelacakan setelah 24 jam penerimaan unit.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS SECTION (THREE SIDE-BY-SIDE QUOTE CARDS) -->
<section class="py-5 bg-white border-bottom">
  <div class="container py-lg-4">
    
    <div class="text-center mx-auto mb-5" style="max-width: 680px;">
      <span class="badge-status badge-status-success mb-2">Ulasan Nyata Pelanggan</span>
      <h2 class="fw-bold text-dark mb-2" style="letter-spacing: -0.02em;">Dipercaya Ribuan Pemilik Perangkat</h2>
      <p class="text-secondary small">
        Pengalaman nyata konsumen profesional, kreator konten, dan gamer yang unitnya ditangani di SerComp.
      </p>
    </div>

    <div class="row g-4">
      
      <!-- Testimonial 1 -->
      <div class="col-md-4">
        <div class="card card-bento p-4 h-100 bg-white border d-flex flex-column justify-content-between">
          <div>
            <div class="text-warning mb-3 small">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
            </div>
            <p class="text-secondary small fst-italic lh-base mb-4">
              "Laptop Asus ROG saya mati saat render 3D. Di tempat lain selalu ditebak biaya tinggi di muka, tapi di SerComp diperiksa dulu di meja kerja dan biayanya terkunci sebelum dibongkar. Hasilnya adem kembali!"
            </p>
          </div>
          <div class="d-flex align-items-center gap-3 pt-3 border-top">
            <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; font-size: 14px;">
              DA
            </div>
            <div>
              <strong class="d-block text-dark small">Dimas Anggoro</strong>
              <span class="text-secondary" style="font-size: 11px;">3D Motion Designer &bull; Asus ROG G15</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Testimonial 2 -->
      <div class="col-md-4">
        <div class="card card-bento p-4 h-100 bg-white border d-flex flex-column justify-content-between">
          <div>
            <div class="text-warning mb-3 small">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
            </div>
            <p class="text-secondary small fst-italic lh-base mb-4">
              "Paling suka dengan sistem Dual Evidence-nya. Ada bukti foto running test suhu stabil dan modul kipas rusak yang diganti dikembalikan dalam dus. Sangat transparan dan profesional."
            </p>
          </div>
          <div class="d-flex align-items-center gap-3 pt-3 border-top">
            <div class="rounded-circle bg-success-subtle text-success fw-bold d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; font-size: 14px;">
              RN
            </div>
            <div>
              <strong class="d-block text-dark small">Rania Novita</strong>
              <span class="text-secondary" style="font-size: 11px;">Lead Engineer PT Digital • ThinkPad X1</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Testimonial 3 -->
      <div class="col-md-4">
        <div class="card card-bento p-4 h-100 bg-white border d-flex flex-column justify-content-between">
          <div>
            <div class="text-warning mb-3 small">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
            </div>
            <p class="text-secondary small fst-italic lh-base mb-4">
              "Klaim garansi benar-benar paperless tanpa nota kertas, cukup sebutkan nomor tiket digital. Garansi 90 hari membuat tenang untuk pemakaian harian kantor."
            </p>
          </div>
          <div class="d-flex align-items-center gap-3 pt-3 border-top">
            <div class="rounded-circle bg-info-subtle text-info fw-bold d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; font-size: 14px;">
              BP
            </div>
            <div>
              <strong class="d-block text-dark small">Bambang Pratama</strong>
              <span class="text-secondary" style="font-size: 11px;">Operations Head • PC Desktop Rig</span>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
</section>

<!-- FAQ SECTION STYLED AS CHAT CONVERSATION (CHAT BUBBLES) -->
<section class="py-5 bg-light border-bottom">
  <div class="container py-lg-4" style="max-width: 820px;">
    
    <div class="text-center mx-auto mb-5">
      <span class="badge-status badge-status-info mb-2">Tanya Jawab Populer</span>
      <h2 class="fw-bold text-dark mb-2" style="letter-spacing: -0.02em;">Pertanyaan yang Sering Diajukan</h2>
      <p class="text-secondary small">
        Pahami alur kerja, perlindungan data, dan garansi lewat interaksi langsung berikut.
      </p>
    </div>

    <div class="d-flex flex-column gap-4">
      
      <!-- Chat Item 1 -->
      <div class="d-flex flex-column gap-2">
        <!-- Question Bubble (Right aligned user) -->
        <div class="d-flex justify-content-end align-items-start gap-2">
          <div class="p-3 rounded-4 bg-primary text-white shadow-2xs" style="max-width: 540px; border-bottom-right-radius: 4px !important;">
            <p class="small mb-0">Bagaimana jika setelah diperiksa teknisi saya merasa biayanya tidak cocok?</p>
          </div>
          <div class="brand-box flex-shrink-0 bg-light border text-primary" style="width: 32px; height: 32px; font-size: 11px;">Anda</div>
        </div>
        <!-- Answer Bubble (Left aligned SerComp) -->
        <div class="d-flex justify-content-start align-items-start gap-2">
          <div class="brand-box flex-shrink-0" style="width: 32px; height: 32px; font-size: 11px;">SC</div>
          <div class="p-3 rounded-4 bg-white border shadow-2xs text-dark" style="max-width: 540px; border-bottom-left-radius: 4px !important;">
            <p class="small mb-0 lh-base">
              Unit tidak akan dibongkar sebelum persetujuan resmi Anda. Anda bebas membatalkan perbaikan dan mengambil unit kembali tanpa biaya tersembunyi (*Zero Hidden Cost*).
            </p>
          </div>
        </div>
      </div>

      <!-- Chat Item 2 -->
      <div class="d-flex flex-column gap-2">
        <!-- Question Bubble -->
        <div class="d-flex justify-content-end align-items-start gap-2">
          <div class="p-3 rounded-4 bg-primary text-white shadow-2xs" style="max-width: 540px; border-bottom-right-radius: 4px !important;">
            <p class="small mb-0">Apakah file dan data di dalam SSD/harddisk saya aman?</p>
          </div>
          <div class="brand-box flex-shrink-0 bg-light border text-primary" style="width: 32px; height: 32px; font-size: 11px;">Anda</div>
        </div>
        <!-- Answer Bubble -->
        <div class="d-flex justify-content-start align-items-start gap-2">
          <div class="brand-box flex-shrink-0" style="width: 32px; height: 32px; font-size: 11px;">SC</div>
          <div class="p-3 rounded-4 bg-white border shadow-2xs text-dark" style="max-width: 540px; border-bottom-left-radius: 4px !important;">
            <p class="small mb-0 lh-base">
              Tentu aman. SOP teknisi kami hanya berfokus pada diagnosa jalur kelistrikan hardware. Partisi sistem file penyimpanan Anda tidak akan pernah dibuka atau diakses.
            </p>
          </div>
        </div>
      </div>

      <!-- Chat Item 3 -->
      <div class="d-flex flex-column gap-2">
        <!-- Question Bubble -->
        <div class="d-flex justify-content-end align-items-start gap-2">
          <div class="p-3 rounded-4 bg-primary text-white shadow-2xs" style="max-width: 540px; border-bottom-right-radius: 4px !important;">
            <p class="small mb-0">Bagaimana cara mengklaim garansi 90 hari jika unit kendala kembali?</p>
          </div>
          <div class="brand-box flex-shrink-0 bg-light border text-primary" style="width: 32px; height: 32px; font-size: 11px;">Anda</div>
        </div>
        <!-- Answer Bubble -->
        <div class="d-flex justify-content-start align-items-start gap-2">
          <div class="brand-box flex-shrink-0" style="width: 32px; height: 32px; font-size: 11px;">SC</div>
          <div class="p-3 rounded-4 bg-white border shadow-2xs text-dark" style="max-width: 540px; border-bottom-left-radius: 4px !important;">
            <p class="small mb-0 lh-base">
              Klaim bersifat 100% *paperless*. Cukup buka halaman pelacakan SerComp dan sebutkan nomor tiket digital Anda saat datang ke workshop. Suku cadang akan langsung diganti baru.
            </p>
          </div>
        </div>
      </div>

    </div>

  </div>
</section>

<!-- CALL TO ACTION BANNER (HEADING, SUPPORTING TEXT, TWO SIDE-BY-SIDE BUTTONS) -->
<section class="py-5 bg-white border-bottom">
  <div class="container py-lg-4">
    <div class="card card-bento p-4 p-md-5 bg-dark text-white border-0 shadow-lg position-relative overflow-hidden">
      
      <!-- Subtle Tech Circuit Accent Background -->
      <div class="position-absolute end-0 top-0 bottom-0 d-none d-lg-block opacity-10 pe-5 pt-3 pointer-events-none">
        <i class="bi bi-cpu" style="font-size: 16rem;"></i>
      </div>

      <div class="position-relative" style="z-index: 2; max-width: 680px;">
        <span class="badge bg-primary text-uppercase font-monospace px-3 py-1.5 mb-3" style="font-size: 10px; letter-spacing: 0.05em;">
          GARANSI RESMI & BIAYA TERKUNCI
        </span>
        <h2 class="display-6 fw-bold text-white mb-3" style="letter-spacing: -0.02em;">
          Siap Memperbaiki Perangkat Anda dengan Transparansi Penuh?
        </h2>
        <p class="text-white-50 lead mb-4" style="font-size: 1.05rem; line-height: 1.6;">
          Buat reservasi mandiri dalam 2 menit. Dapatkan nomor tiket digital, persetujuan estimasi di muka, dan pantau proses reparasi dari mana saja secara real-time.
        </p>

        <!-- Two Buttons Side by Side: Primary Action & Secondary Action -->
        <div class="d-flex flex-wrap align-items-center gap-3">
          <a href="{{ url('/booking') }}" class="btn btn-primary btn-lg px-4 py-3 fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
            <i data-lucide="calendar-plus" style="width: 20px; height: 20px;"></i>
            <span>Buka Formulir Reservasi</span>
          </a>
          <a href="#lacak" class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold d-inline-flex align-items-center gap-2">
            <i data-lucide="search" style="width: 18px; height: 18px;"></i>
            <span>Lacak Status Tiket</span>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

@endsection
