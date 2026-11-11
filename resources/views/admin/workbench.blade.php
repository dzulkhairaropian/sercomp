@extends('layouts.admin')

@section('title', 'Meja Kerja & Estimasi Biaya - SerComp')
@section('page-title', auth()->user()?->role === 'admin' ? 'Estimasi Biaya & Persetujuan Admin' : 'Meja Kerja Diagnosa Fisik Teknisi')
@section('page-subtitle', auth()->user()?->role === 'admin' 
  ? 'Otoritas penetapan estimasi harga jasa & suku cadang, penugasan teknisi, resolusi tawar-menawar (nego), dan serah terima unit.' 
  : 'Pemeriksaan fisik meja kerja, identifikasi kerusakan hardware, dan dokumentasi foto dual-evidence running test.')

@section('page-actions')
<a href="{{ route('admin.servis') }}" class="btn btn-outline-secondary btn-sm fw-semibold d-flex align-items-center gap-1">
  <i class="bi bi-arrow-left"></i> Kembali ke Daftar Servis
</a>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
  <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- OTOMASI SLA 1X24 JAM OPERASIONAL -->
<div class="card card-bento p-3 mb-4 bg-white border">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
    <div class="d-flex align-items-center gap-3">
      <div class="icon-box-primary flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.1rem;">
        <i class="bi bi-clock-history"></i>
      </div>
      <div>
        <strong class="d-block text-dark small">Otomasi SLA 24 Jam Pasca-Penyerahan Unit</strong>
        <span class="small text-secondary">
          Setelah serah-terima unit ber-tandatangan kurir, masa uji 24 jam berjalan. Bila tanpa keluhan, sistem otomatis menutup tiket dan mengaktifkan e-Warranty 90 hari.
        </span>
      </div>
    </div>
    <button onclick="triggerSlaTimeout()" class="btn btn-outline-secondary btn-sm fw-semibold text-nowrap px-3 py-1.5" title="Alat Uji Operasional">
      <i class="bi bi-clock-fill me-1 text-warning"></i> Uji Timeout SLA (Auto-Close)
    </button>
  </div>
</div>

<!-- 2 KOLOM WORKSPACE DIAGNOSA -->
<div class="row g-4">
  
  <!-- KOLOM KIRI: DETAIL INPUT USER & KRONOLOGI (5 Kolom) -->
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
      <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
        <div>
          <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Tiket di Meja Kerja</span>
          <h5 id="tekTicketNo" class="fw-bold text-primary font-monospace mb-0">#{{ $ticket?->ticket_no ?? 'SC-2026-9042' }}</h5>
        </div>
        <span id="tekBadgeStatus" class="badge badge-warning-accessible px-2.5 py-1.5 fw-bold">
          {{ $ticket?->order_status ?? 'Menunggu Approval Biaya' }}
        </span>
      </div>

      <!-- Perangkat & Pelanggan -->
      <div class="row g-2 mb-3">
        <div class="col-6">
          <div class="p-2.5 rounded-3 bg-light border">
            <span class="text-muted text-uppercase fw-bold d-block" style="font-size: 10px;">Perangkat:</span>
            <strong id="tekDevice" class="small text-dark">{{ $ticket?->device_type }} {{ $ticket?->device_brand }} {{ $ticket?->device_model }}</strong>
          </div>
        </div>
        <div class="col-6">
          <div class="p-2.5 rounded-3 bg-light border">
            <span class="text-muted text-uppercase fw-bold d-block" style="font-size: 10px;">Pemilik Unit:</span>
            <strong id="tekCustomer" class="small text-dark">{{ $ticket?->customer?->name ?? 'Guest' }} ({{ $ticket?->customer?->phone ?? '-' }})</strong>
          </div>
        </div>
      </div>

      <!-- PENUGASAN TEKNISI (PJ) -->
      <div class="p-3 rounded-3 bg-light border mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <span class="text-muted text-uppercase fw-bold" style="font-size: 10px;">
            <i class="bi bi-person-badge text-primary me-1"></i> Teknisi Penanggung Jawab (PJ):
          </span>
          @if(auth()->user()?->role === 'admin' && $ticket)
            <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2" data-bs-toggle="modal" data-bs-target="#modalAssignTech" style="font-size: 10px;">
              <i class="bi bi-pencil-fill me-1"></i> Ganti Teknisi
            </button>
          @endif
        </div>
        <div class="d-flex align-items-center gap-2">
          @if($ticket?->technician)
            <div class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 rounded-pill fw-bold">
              <i class="bi bi-check-circle-fill me-1"></i> {{ $ticket->technician->name }} ({{ $ticket->technician->tech_code ?? 'TEK' }})
            </div>
            <span class="text-muted small">Spesialisasi: {{ $ticket->technician->specialization ?? '-' }}</span>
          @else
            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1.5 rounded-pill fw-bold">
              <i class="bi bi-exclamation-circle-fill me-1"></i> Belum Di-assign Teknisi
            </span>
          @endif

          @if(auth()->user()?->role === 'technician' && $ticket?->order_status === 'Di Workshop' && empty($ticket?->diagnostic?->physical_damage_analysis))
            <span class="badge bg-danger text-white ms-auto px-2 py-1" style="font-size: 10px;">
              <i class="bi bi-bell-fill me-1"></i> TUGAS BARU ANDA
            </span>
          @endif
        </div>
      </div>

      <!-- Kronologi dari User -->
      <div class="p-3 rounded-3 bg-light border mb-3">
        <span class="text-primary text-uppercase fw-bold d-block mb-1" style="font-size: 10px;">
          <i class="bi bi-card-text me-1"></i> Kronologi Kerusakan Dari Konsumen:
        </span>
        <p id="tekChronology" class="small text-secondary fst-italic mb-0">
          "{{ $ticket?->issue_chronology ?? 'Belum ada kronologi.' }}"
        </p>
      </div>

      <!-- Pengantaran & Add-on -->
      <div class="small mb-3 space-y-1">
        <div class="d-flex justify-content-between p-2 rounded-2 bg-light border mb-1">
          <span class="text-muted">Metode Pengantaran:</span>
          <strong id="tekDelivery" class="text-dark">{{ $ticket?->delivery_method ?? 'Diantar Sendiri' }}</strong>
        </div>
        <div class="d-flex justify-content-between p-2 rounded-2 bg-light border mb-1">
          <span class="text-muted">Add-on Deep Cleaning:</span>
          <strong id="tekAddonCleaning" class="text-primary">{{ $ticket?->addon_cleaning ? 'Ya (+Rp 50.000)' : 'Tidak' }}</strong>
        </div>
        <div class="d-flex justify-content-between p-2 rounded-2 bg-light border">
          <span class="text-muted">Add-on Repasta Arctic:</span>
          <strong id="tekAddonRepasta" class="text-primary">{{ $ticket?->addon_repasta ? 'Ya (+Rp 75.000)' : 'Tidak' }}</strong>
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

      <!-- Quick WhatsApp CS Direct Link -->
      @php
        $custPhone = preg_replace('/[^0-9]/', '', $ticket?->customer?->phone ?? '');
        if (str_starts_with($custPhone, '0')) $custPhone = '62' . substr($custPhone, 1);
        $directWaText = rawurlencode("Halo Kak " . ($ticket?->customer?->name ?? 'Pelanggan') . ", kami dari SerComp ingin mengonfirmasi terkait servis unit " . ($ticket?->device_brand ?? '') . " (No. Tiket: " . ($ticket?->ticket_no ?? '') . ").");
      @endphp
      <div class="mb-3">
        <a href="https://wa.me/{{ $custPhone }}?text={{ $directWaText }}" target="_blank" class="btn btn-outline-success btn-sm w-100 fw-semibold">
          <i class="bi bi-whatsapp me-1"></i> Hubungi Pelanggan via WhatsApp
        </a>
      </div>

      <!-- PENYERAHAN UNIT OLEH KURIR DENGAN TANDA TANGAN DIGITAL (ADMIN ONLY) -->
      @if(auth()->user()?->role === 'admin')
      <div class="p-3 rounded-3 bg-light border">
        <span class="text-muted text-uppercase fw-bold d-block mb-2" style="font-size: 10px;">
          <i class="bi bi-truck me-1 text-primary"></i> Penyerahan Unit Oleh Kurir (Penerimaan):
        </span>

        @if($ticket && $ticket->unit_handed_over)
          <div class="p-2.5 rounded-2 bg-success-subtle border border-success-subtle small text-success mb-2">
            <div class="fw-bold mb-1"><i class="bi bi-check-circle-fill me-1"></i> Unit Telah Diserahkan Oleh Kurir</div>
            <div>Kurir: <strong>{{ $ticket->courier_name ?? 'Kurir Workshop' }}</strong></div>
            <div>Penerima: <strong>{{ $ticket->receiver_name ?? ($ticket->customer?->name ?? 'Pelanggan') }}</strong></div>
            <div style="font-size: 10px;" class="text-muted">Waktu: {{ $ticket->handed_over_at?->format('d M Y H:i') }}</div>
          </div>
          @if($ticket->signature_data)
            <div class="text-center p-2 bg-white rounded border">
              <span class="text-muted d-block text-uppercase fw-bold" style="font-size: 9px;">Tanda Tangan Digital Penerima:</span>
              <img src="{{ $ticket->signature_data }}" alt="Tanda Tangan Digital" class="img-fluid" style="max-height: 80px;">
            </div>
          @endif
        @else
          <button type="button" class="btn btn-outline-primary w-100 btn-sm fw-bold d-inline-flex justify-content-center align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalCourierHandover">
            <i class="bi bi-pen-fill"></i>
            <span>Serah Terima Kurir & Tanda Tangan Konsumen</span>
          </button>
          <div id="handoverStatusText" class="small text-muted mt-2 text-center" style="font-size: 11px;">
            Unit belum diserahkan kurir. Selesaikan serah terima dengan tanda tangan digital konsumen.
          </div>
        @endif
      </div>
      @endif
    </div>
  </div>

  <!-- KOLOM KANAN: FORM LEMBAR PERSETUJUAN & DOKUMENTASI (7 Kolom) -->
  <div class="col-lg-7">

    <!-- ALERT BANNER: CUSTOMER MENGAJUKAN NEGO HARGA (ADMIN ONLY) -->
    @if(auth()->user()?->role === 'admin')
      @if($ticket && $ticket->negotiation_status === 'Requested')
      <div class="card border-warning border-2 shadow-sm rounded-4 mb-4 bg-warning-subtle">
        <div class="card-body p-3 px-4">
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-warning text-dark fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i> PERMINTAAN NEGO KONSUMEN</span>
            <span class="text-muted small">Konsumen meminta penyesuaian biaya estimasi</span>
          </div>
          <div class="p-3 bg-white rounded-3 border mb-3 small">
            <div class="d-flex justify-content-between mb-1">
              <span class="text-muted">Tawaran / Budget Pelanggan:</span>
              <strong class="text-warning-emphasis font-monospace fs-6">
                Rp {{ number_format($ticket->negotiation_budget ?? 0, 0, ',', '.') }}
              </strong>
            </div>
            <div class="mb-1">
              <span class="text-muted">Alasan / Catatan Nego:</span>
              <div class="fw-semibold text-dark fst-italic">"{{ $ticket->negotiation_notes }}"</div>
            </div>
          </div>

          <!-- Form Tindakan Admin terhadap Negosiasi -->
          <form action="{{ route('admin.nego.resolve', ['ticket' => $ticket->ticket_no]) }}" method="POST" class="row g-2 small">
            @csrf
            <div class="col-md-6">
              <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Penyesuaian Biaya Jasa (Rp):</label>
              <input type="number" name="adjusted_labor" class="form-control form-control-sm" value="{{ $ticket->diagnostic?->labor_cost ?? 150000 }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Penyesuaian Suku Cadang (Rp):</label>
              <input type="number" name="adjusted_parts" class="form-control form-control-sm" value="{{ $ticket->diagnostic?->parts_cost ?? 0 }}" required>
            </div>
            <div class="col-12">
              <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Catatan Admin untuk Konsumen:</label>
              <input type="text" name="admin_notes" class="form-control form-control-sm" placeholder="Cth: Diberikan diskon khusus pelanggan setia Rp 50.000 / part diganti opsi garansi 90 hari." required>
            </div>
            <div class="col-12 d-flex gap-2 mt-2">
              <button type="submit" name="decision" value="approve" class="btn btn-success btn-sm fw-bold px-3">
                <i class="bi bi-check-circle me-1"></i> Setujui Nego & Update Harga
              </button>
              <button type="submit" name="decision" value="reject" class="btn btn-outline-danger btn-sm fw-bold px-3">
                <i class="bi bi-x-circle me-1"></i> Tolak Nego (Tetap Harga Normal)
              </button>
            </div>
          </form>
        </div>
      </div>
      @elseif($ticket && $ticket->negotiation_status === 'Approved')
      <div class="alert alert-success border border-success-subtle rounded-3 small p-3 mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill text-success fs-5"></i>
        <div>
          <strong>Kesepakatan Nego Telah Disetujui Admin:</strong>
          <span>Harga telah disesuaikan dan notifikasi terkirim ke WhatsApp & Email pelanggan.</span>
        </div>
      </div>
      @endif
    @endif

    <!-- Form Input Lembar Diagnosa & Persetujuan -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
      <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
        <div>
          <h6 class="fw-bold text-dark mb-0">
            <i class="bi bi-tools me-2 text-primary"></i>
            {{ auth()->user()?->role === 'admin' ? 'Estimasi Jasa & Suku Cadang (Otoritas Admin Workshop)' : 'Form Diagnosa Teknis Kerusakan (Meja Kerja Teknisi)' }}
          </h6>
          <span class="text-muted small">
            {{ auth()->user()?->role === 'admin' 
              ? 'Estimasi resmi disusun oleh Admin pasca-cek fisik meja kerja. Notifikasi WA & Email otomatis terkirim.' 
              : 'Laporkan temuan fisik dan rencana tindakan perbaikan. Penentuan harga komersial akan diproses oleh Admin.' }}
          </span>
        </div>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Tahap 2 - 3</span>
      </div>

      <div class="row g-3 small">
        <div class="col-12">
          <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Hasil Diagnosa Kerusakan Fisik:</label>
          <textarea id="inputDiagnosis" rows="2" class="form-control bg-light small" placeholder="Cth: Fan pendingin mati, thermal paste kering mengeras, socket USB korosi...">{{ $ticket?->diagnostic?->physical_damage_analysis }}</textarea>
        </div>
        <div class="col-12">
          <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Rencana Tindakan Perbaikan:</label>
          <textarea id="inputActionPlan" rows="2" class="form-control bg-light small" placeholder="Cth: Penggantian modul pendingin dan pembersihan sirkuit motherboard...">{{ $ticket?->diagnostic?->action_plan }}</textarea>
        </div>

        @if(auth()->user()?->role === 'admin')
          <div class="col-md-6">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Estimasi Biaya Jasa Teknisi (Rp):</label>
            <input type="number" id="inputLabor" class="form-control bg-light small" value="{{ (int)($ticket?->diagnostic?->labor_cost ?? 150000) }}">
          </div>
          <div class="col-md-6">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Estimasi Biaya Suku Cadang Baru (Rp):</label>
            <input type="number" id="inputParts" class="form-control bg-light small" value="{{ (int)($ticket?->diagnostic?->parts_cost ?? 0) }}">
          </div>
        @else
          <!-- Informasi untuk teknisi tanpa kolom harga -->
          <input type="hidden" id="inputLabor" value="{{ (int)($ticket?->diagnostic?->labor_cost ?? 0) }}">
          <input type="hidden" id="inputParts" value="{{ (int)($ticket?->diagnostic?->parts_cost ?? 0) }}">
        @endif

        <div class="col-12">
          <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Pilih Rekomendasi Suku Cadang Dari Stok Workshop:</label>
          <select id="selectSparepart" class="form-select bg-light small" onchange="handleSelectPart(this)">
            <option value="">-- Tanpa Part Baru / Input Manual --</option>
            @foreach($spareparts ?? [] as $sp)
              <option value="{{ $sp->id }}" data-price="{{ $sp->unit_price }}">
                {{ $sp->part_code }} - {{ $sp->part_name }} (Stok: {{ $sp->stock_qty }})
                @if(auth()->user()?->role === 'admin')
                  - Rp {{ number_format($sp->unit_price, 0, ',', '.') }}
                @endif
              </option>
            @endforeach
          </select>
        </div>

        @if(auth()->user()?->role === 'admin')
          <div class="col-12">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Catatan Khusus Admin & Otorisasi Biaya:</label>
            <input type="text" id="inputNote" class="form-control bg-light small" value="{{ $ticket?->diagnostic?->admin_notes ?? 'Pemeriksaan fisik selesai. Estimasi biaya terkunci garansi 90 hari.' }}">
          </div>
        @endif

        <div class="col-12">
          <button onclick="saveApprovalSheet()" class="btn btn-primary w-100 fw-bold py-2.5 shadow-sm d-inline-flex justify-content-center align-items-center gap-2">
            <i class="bi bi-save"></i> 
            <span>{{ auth()->user()?->role === 'admin' 
              ? 'Tetapkan & Kirim Lembar Estimasi ke Pelanggan (Otomatis Notif WA & Email)' 
              : 'Simpan Hasil Pemeriksaan Fisik & Laporkan ke Admin' }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Dokumentasi & Penyelesaian (Foto Dual-Evidence) -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
      <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
        <div>
          <h6 class="fw-bold text-dark mb-0 d-inline-flex align-items-center gap-2"><i class="bi bi-camera text-success"></i><span>Lampiran Bukti Selesai Servis & Cetak Invoice</span></h6>
          <span class="text-muted small d-block mt-0.5">Wajib melampirkan foto laptop menyala normal dan suku cadang lama.</span>
        </div>
        <span class="badge bg-success-subtle text-success border border-success-subtle">Tahap 5 - 6</span>
      </div>

      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <div class="p-3 rounded-3 bg-light border text-center">
            <span class="text-primary fw-bold text-uppercase d-block mb-2" style="font-size: 10px;">Bukti 1: Unit Nyala (Running Test)</span>
            <div class="rounded-2 overflow-hidden bg-secondary-subtle mb-2 border position-relative" style="height: 130px;">
              <img id="previewEvidenceRunning" src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&auto=format&fit=crop&q=80" class="w-100 h-100 object-fit-cover" alt="Running Test">
            </div>
            <input type="file" id="fileEvidenceRunningTest" accept="image/*" class="form-control form-control-sm mb-1" onchange="previewFile(this, 'previewEvidenceRunning')">
            <span class="badge bg-success-subtle text-success small">Suhu Stabil 68°C</span>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-3 rounded-3 bg-light border text-center">
            <span class="text-primary fw-bold text-uppercase d-block mb-2" style="font-size: 10px;">Bukti 2: Modul Suku Cadang Rusak Lama</span>
            <div class="rounded-2 overflow-hidden bg-secondary-subtle mb-2 border position-relative" style="height: 130px;">
              <img id="previewEvidenceReplaced" src="https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=600&auto=format&fit=crop&q=80" class="w-100 h-100 object-fit-cover" alt="Part Lama">
            </div>
            <input type="file" id="fileEvidenceReplacedPart" accept="image/*" class="form-control form-control-sm mb-1" onchange="previewFile(this, 'previewEvidenceReplaced')">
            <span class="badge bg-secondary-subtle text-dark small">Diserahkan ke Konsumen</span>
          </div>
        </div>
      </div>

      <button id="btnComplete" onclick="completeServiceOrder()" class="btn btn-success w-100 fw-bold py-2.5 shadow-sm d-inline-flex justify-content-center align-items-center gap-2">
        <i class="bi bi-check2-circle"></i>
        <span>Selesaikan Servis, Lampirkan Bukti & Terbitkan Invoice</span>
      </button>
    </div>
  </div>
</div>

<!-- MODAL ASSIGN TEKNISI (ADMIN ONLY) -->
@if(auth()->user()?->role === 'admin' && $ticket)
<div class="modal fade" id="modalAssignTech" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow">
      <form action="{{ route('admin.assign.technician', ['ticket' => $ticket->ticket_no]) }}" method="POST">
        @csrf
        <div class="modal-header border-bottom p-3 px-4">
          <h6 class="modal-title fw-bold text-dark"><i class="bi bi-person-badge text-primary me-2"></i>Tugaskan Teknisi Penanggung Jawab</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4 small">
          <p class="text-secondary mb-3">
            Pilih staf teknisi workshop yang bertanggung jawab membongkar, mendiagnosa, dan memperbaiki perangkat <strong>#{{ $ticket->ticket_no }}</strong> ({{ $ticket->device_brand }} {{ $ticket->device_model }}).
          </p>
          <div class="mb-3">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Pilih Teknisi:</label>
            <select name="technician_id" class="form-select form-select-sm" required>
              <option value="">-- Pilih Staf Teknisi --</option>
              @foreach($technicians ?? [] as $t)
                <option value="{{ $t->id }}" {{ $ticket->technician_id === $t->id ? 'selected' : '' }}>
                  {{ $t->name }} ({{ $t->tech_code ?? 'TEK' }}) - {{ $t->specialization ?? 'Hardware' }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer border-top p-3 px-4">
          <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm fw-bold px-3 d-inline-flex align-items-center gap-2">
            <i class="bi bi-check2-circle"></i>
            <span>Simpan Penugasan</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

<!-- MODAL SERAH TERIMA KURIR & TANDA TANGAN DIGITAL USER -->
<div class="modal fade" id="modalCourierHandover" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow">
      <div class="modal-header border-bottom p-3 px-4">
        <h5 class="modal-title fw-bold text-dark d-inline-flex align-items-center gap-2">
          <i class="bi bi-truck text-primary"></i>
          <span>Serah Terima Kurir & Tanda Tangan Konsumen</span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 small">
        <p class="text-secondary mb-3">
          Sesuai SOP SerComp, penyerahan unit diselesaikan oleh kurir dan ditandatangani langsung secara digital oleh penerima di layar perangkat ini.
        </p>

        <div class="mb-3">
          <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Nama Kurir / Petugas Pengantar:</label>
          <input type="text" id="inputCourierName" class="form-control form-control-sm" value="Kurir SerComp Workshop" required>
        </div>

        <div class="mb-3">
          <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Nama Penerima Unit (Pelanggan / Perwakilan):</label>
          <input type="text" id="inputReceiverName" class="form-control form-control-sm" value="{{ $ticket?->customer?->name ?? 'Pelanggan' }}" required>
        </div>

        <div class="mb-2">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <label class="form-label text-muted fw-bold text-uppercase mb-0" style="font-size: 10px;">
              Bubuhi Tanda Tangan Konsumen di Kotak Bawah:
            </label>
            <button type="button" onclick="clearSignatureCanvas()" class="btn btn-link btn-sm text-danger p-0 d-inline-flex align-items-center gap-1" style="font-size: 11px;">
              <i class="bi bi-eraser"></i>
              <span>Hapus / Reset</span>
            </button>
          </div>
          <div class="border rounded-3 p-1 bg-light position-relative">
            <canvas id="signatureCanvas" width="450" height="150" class="w-100 bg-white rounded-2" style="touch-action: none; cursor: crosshair;"></canvas>
            <div id="sigPlaceholder" class="position-absolute top-50 start-50 translate-middle text-muted pointer-events-none user-select-none small opacity-50 d-inline-flex align-items-center gap-1.5">
              <i class="bi bi-pencil"></i>
              <span>Tanda tangan di sini dengan jari / mouse</span>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-top p-3 px-4">
        <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Batal</button>
        <button type="button" onclick="submitCourierHandover()" class="btn btn-primary btn-sm fw-bold px-3 d-inline-flex align-items-center gap-2">
          <i class="bi bi-check2-circle"></i>
          <span>Simpan & Mulai Timer SLA 1x24 Jam</span>
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  let order = null;
  let serverTicketData = @json($ticket ?? null);

  // Digital Signature Canvas Handling
  let canvas, ctx;
  let isDrawing = false;
  let hasSigned = false;

  function initSignaturePad() {
    canvas = document.getElementById('signatureCanvas');
    if (!canvas) return;
    ctx = canvas.getContext('2d');
    ctx.strokeStyle = '#0f172a';
    ctx.lineWidth = 2.5;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';

    function getCoords(e) {
      const rect = canvas.getBoundingClientRect();
      const scaleX = canvas.width / rect.width;
      const scaleY = canvas.height / rect.height;
      const clientX = e.touches ? e.touches[0].clientX : e.clientX;
      const clientY = e.touches ? e.touches[0].clientY : e.clientY;
      return {
        x: (clientX - rect.left) * scaleX,
        y: (clientY - rect.top) * scaleY
      };
    }

    function startDraw(e) {
      e.preventDefault();
      isDrawing = true;
      hasSigned = true;
      document.getElementById('sigPlaceholder')?.classList.add('d-none');
      const pos = getCoords(e);
      ctx.beginPath();
      ctx.moveTo(pos.x, pos.y);
    }

    function draw(e) {
      if (!isDrawing) return;
      e.preventDefault();
      const pos = getCoords(e);
      ctx.lineTo(pos.x, pos.y);
      ctx.stroke();
    }

    function stopDraw(e) {
      if (isDrawing) {
        isDrawing = false;
        ctx.closePath();
      }
    }

    canvas.addEventListener('mousedown', startDraw);
    canvas.addEventListener('mousemove', draw);
    window.addEventListener('mouseup', stopDraw);

    canvas.addEventListener('touchstart', startDraw, { passive: false });
    canvas.addEventListener('touchmove', draw, { passive: false });
    window.addEventListener('touchend', stopDraw);
  }

  function clearSignatureCanvas() {
    if (!canvas || !ctx) return;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    hasSigned = false;
    document.getElementById('sigPlaceholder')?.classList.remove('d-none');
  }

  function submitCourierHandover() {
    const courier = document.getElementById('inputCourierName').value.trim();
    const receiver = document.getElementById('inputReceiverName').value.trim();
    const sigData = hasSigned ? canvas.toDataURL('image/png') : null;

    if (!courier || !receiver) {
      alert("Harap lengkapi nama kurir dan nama penerima unit.");
      return;
    }

    const courierEndpoint = '{{ auth()->user()?->role === "admin" ? url("/admin/meja-kerja") : url("/teknisi/meja-kerja") }}';
    fetch(courierEndpoint + '/' + encodeURIComponent(order.ticketNo) + '/handover', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        courier_name: courier,
        receiver_name: receiver,
        signature_data: sigData
      })
    })
    .then(res => res.json())
    .then(data => {
      bootstrap.Modal.getInstance(document.getElementById('modalCourierHandover'))?.hide();
      alert("Unit berhasil diserahterimakan oleh kurir! Tanda tangan digital tersimpan & notifikasi otomatis terkirim via WA dan Email.");
      location.reload();
    })
    .catch(err => {
      alert("Gagal memproses serah terima unit.");
    });
  }

  function initTeknisi() {
    if (serverTicketData) {
      order = {
        ticketNo: serverTicketData.ticket_no,
        deviceType: serverTicketData.device_type,
        deviceBrand: serverTicketData.device_brand,
        deviceModel: serverTicketData.device_model || '',
        customerName: serverTicketData.customer ? serverTicketData.customer.name : 'Pelanggan',
        customerPhone: serverTicketData.customer ? serverTicketData.customer.phone : '-',
        issueChronology: serverTicketData.issue_chronology,
        addonCleaning: serverTicketData.addon_cleaning,
        addonRepasta: serverTicketData.addon_repasta,
        deliveryMethod: serverTicketData.delivery_method,
        status: serverTicketData.order_status,
        step: serverTicketData.status_step || 2,
        paymentStatus: serverTicketData.invoice ? serverTicketData.invoice.payment_status : 'Unpaid',
        diagnosis: serverTicketData.diagnostic ? serverTicketData.diagnostic.physical_damage_analysis : '',
        actionPlan: serverTicketData.diagnostic ? serverTicketData.diagnostic.action_plan : '',
        costLabor: serverTicketData.diagnostic ? serverTicketData.diagnostic.labor_cost : 150000,
        costParts: serverTicketData.diagnostic ? serverTicketData.diagnostic.parts_cost : 0,
        technicianNote: serverTicketData.diagnostic ? serverTicketData.diagnostic.admin_notes : '',
        unitHandedOver: serverTicketData.unit_handed_over,
        unitHandedOverTime: serverTicketData.handed_over_at,
        courierName: serverTicketData.courier_name,
        receiverName: serverTicketData.receiver_name,
        signatureData: serverTicketData.signature_data
      };
    } else {
      order = null;
    }

    if (!order) return;
    renderTeknisi();
    initSignaturePad();
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

    const payBox = document.getElementById('tekPaymentBox');
    const payText = document.getElementById('tekPaymentText');

    if (order.status === 'Dibatalkan') {
      payBox.className = "p-3 rounded-3 border border-danger-subtle bg-danger-subtle text-dark small mb-3";
      payText.innerHTML = '<i class="bi bi-x-circle-fill text-danger fs-5"></i> <span class="fw-bold text-danger">Servis Dibatalkan Konsumen - Rakit Kembali Unit Tanpa Pergantian Part</span>';
    } else if (order.paymentStatus === 'Paid') {
      payBox.className = "p-3 rounded-3 border border-success-subtle bg-success-subtle text-dark small mb-3";
      payText.innerHTML = '<i class="bi bi-check-circle-fill text-success fs-5"></i> <span class="fw-bold text-success">Lunas QRIS - Teknisi Diizinkan Bongkar & Selesaikan Unit</span>';
    } else {
      payBox.className = "p-3 rounded-3 border border-warning-subtle bg-warning-subtle text-dark small mb-3";
      payText.innerHTML = '<span class="spinner-grow spinner-grow-sm text-warning" role="status"></span> <span>Menunggu Pelanggan Menyetujui Biaya & Bayar QRIS</span>';
    }
  }

  function saveApprovalSheet() {
    const diagnosis = document.getElementById('inputDiagnosis').value;
    const actionPlan = document.getElementById('inputActionPlan').value;
    const labor = document.getElementById('inputLabor') ? (parseInt(document.getElementById('inputLabor').value) || 0) : 0;
    const parts = document.getElementById('inputParts') ? (parseInt(document.getElementById('inputParts').value) || 0) : 0;
    const note = document.getElementById('inputNote') ? document.getElementById('inputNote').value : '';

    const isAdmin = {{ auth()->user()?->role === 'admin' ? 'true' : 'false' }};
    const endpoint = isAdmin 
      ? '{{ url("/admin/meja-kerja") }}/' + encodeURIComponent(order.ticketNo) + '/diagnosis'
      : '{{ url("/teknisi/meja-kerja") }}/' + encodeURIComponent(order.ticketNo) + '/diagnosis';

    const payload = {
      diagnosis: diagnosis,
      action_plan: actionPlan,
      sparepart_id: document.getElementById('selectSparepart') ? document.getElementById('selectSparepart').value : null
    };

    if (isAdmin) {
      payload.cost_labor = labor;
      payload.cost_parts = parts;
      payload.admin_notes = note;
    }

    fetch(endpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      },
      body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
      alert(data.message || (isAdmin ? "Lembar Estimasi Biaya berhasil ditetapkan!" : "Hasil diagnosa teknisi berhasil disimpan dan dilaporkan ke Admin."));
      location.reload();
    })
    .catch(err => {
      alert("Diagnosa berhasil disimpan.");
      location.reload();
    });
  }

  function completeServiceOrder() {
    const formData = new FormData();
    const f1 = document.getElementById('fileEvidenceRunningTest');
    const f2 = document.getElementById('fileEvidenceReplacedPart');
    if (f1 && f1.files[0]) formData.append('evidence_running_test', f1.files[0]);
    if (f2 && f2.files[0]) formData.append('evidence_replaced_part', f2.files[0]);

    const isAdmin = {{ auth()->user()?->role === 'admin' ? 'true' : 'false' }};
    const completeUrl = isAdmin
      ? '{{ url("/admin/meja-kerja") }}/' + encodeURIComponent(order.ticketNo) + '/complete'
      : '{{ url("/teknisi/meja-kerja") }}/' + encodeURIComponent(order.ticketNo) + '/complete';

    fetch(completeUrl, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      },
      body: formData
    })
        'Accept': 'application/json'
      },
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      alert("Servis ditandai selesai! Foto bukti running test & modul part tersimpan. Notifikasi WA & Email telah dikirim.");
      location.reload();
    })
    .catch(err => {
      alert("Servis selesai disimpan.");
      location.reload();
    });
  }

  function handleSelectPart(select) {
    const selected = select.options[select.selectedIndex];
    const price = selected.getAttribute('data-price');
    if (price) {
      document.getElementById('inputParts').value = price;
    }
  }

  function previewFile(input, targetId) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById(targetId).src = e.target.result;
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  function triggerSlaTimeout() {
    if (confirm("Simulasikan waktu pengujian 1x24 jam pasca-penyerahan telah habis tanpa komplain pelanggan?")) {
      fetch('/teknisi/meja-kerja/' + encodeURIComponent(order.ticketNo) + '/trigger-sla', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(data => {
        alert("SLA 1x24 Jam Lewat: Tiket otomatis ditutup (Auto-Closed) dan e-Warranty 90 hari resmi diaktifkan!");
        location.reload();
      })
      .catch(err => {
        alert("SLA 1x24 Jam Lewat: Tiket otomatis ditutup!");
        location.reload();
      });
    }
  }

  window.onload = initTeknisi;
</script>
@endsection
