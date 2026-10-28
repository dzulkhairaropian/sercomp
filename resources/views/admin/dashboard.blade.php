@extends('layouts.admin')

@section('title', 'Dashboard KPI Workshop - SerComp')
@section('page-title', 'Dashboard KPI & Ringkasan Workshop')
@section('page-subtitle', 'Monitoring indikator performa perbaikan, kepatuhan SLA 1x24 jam, dan omzet bengkel.')

@section('page-actions')
<a href="{{ url('/teknisi/servis') }}" class="btn btn-primary btn-sm fw-semibold shadow-sm d-flex align-items-center gap-1">
  <i class="bi bi-list-check" aria-hidden="true"></i> Kelola Antrean Servis
</a>
@endsection

@section('content')

<!-- 4 TOP KPI STATS -->
<div class="row g-3 mb-4">
  
  <!-- KPI 1 -->
  <div class="col-sm-6 col-xl-3">
    <div class="card-kpi h-100">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Total Antrean Unit</span>
        <div class="badge bg-primary-subtle text-primary p-2 rounded-3">
          <i class="bi bi-inbox-fill fs-5" aria-hidden="true"></i>
        </div>
      </div>
      <div class="fs-2 fw-bold text-dark mb-1">{{ $totalOrders ?? 14 }} <span class="fs-6 text-muted fw-normal">Unit</span></div>
      <div class="d-flex align-items-center text-success small fw-semibold">
        <i class="bi bi-arrow-up-right me-1" aria-hidden="true"></i> Terdata di Database
      </div>
    </div>
  </div>

  <!-- KPI 2 -->
  <div class="col-sm-6 col-xl-3">
    <div class="card-kpi h-100">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Sedang Dikerjakan</span>
        <div class="badge bg-success-subtle text-success p-2 rounded-3">
          <i class="bi bi-tools fs-5" aria-hidden="true"></i>
        </div>
      </div>
      <div class="fs-2 fw-bold text-dark mb-1">{{ $inProgress ?? 5 }} <span class="fs-6 text-muted fw-normal">Unit</span></div>
      <div class="d-flex align-items-center text-muted small">
        <span class="text-success fw-bold me-1">Lunas QRIS:</span> Di meja reparasi
      </div>
    </div>
  </div>

  <!-- KPI 3 -->
  <div class="col-sm-6 col-xl-3">
    <div class="card-kpi h-100">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Menunggu Approval / Bayar</span>
        <div class="badge bg-warning-subtle text-warning-emphasis p-2 rounded-3">
          <i class="bi bi-hourglass-split fs-5" aria-hidden="true"></i>
        </div>
      </div>
      <div class="fs-2 fw-bold text-warning mb-1">{{ $waitingApproval ?? 4 }} <span class="fs-6 text-muted fw-normal">Unit</span></div>
      <div class="d-flex align-items-center text-muted small">
        Menunggu konfirmasi konsumen
      </div>
    </div>
  </div>

  <!-- KPI 4 -->
  <div class="col-sm-6 col-xl-3">
    <div class="card-kpi h-100 bg-primary-subtle border-primary-subtle">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-primary text-uppercase fw-bold" style="font-size: 11px;">Total Omzet Servis Lunas</span>
        <div class="badge bg-primary text-white p-2 rounded-3">
          <i class="bi bi-cash-stack fs-5" aria-hidden="true"></i>
        </div>
      </div>
      <div class="fs-2 fw-bold text-primary mb-1">Rp {{ number_format(($totalRevenue ?? 4850000), 0, ',', '.') }}</div>
      <div class="d-flex align-items-center text-muted small">
        Berdasarkan transaksi QRIS tervalidasi
      </div>
    </div>
  </div>

</div>

<!-- BARIS 2: METRIK SLA & UNIT PRIORITAS MEJA KERJA -->
<div class="row g-4 mb-4">
  
  <!-- LEFT: UNIT PRIORITAS DI MEJA KERJA -->
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
      <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
        <div>
          <h5 class="fw-bold text-dark mb-0">Unit Prioritas di Meja Kerja</h5>
          <span class="text-muted small">Tiket terkini yang siap ditindaklanjuti teknisi.</span>
        </div>
        <span class="badge bg-primary px-3 py-1.5 fw-bold font-monospace" id="kpiActiveTicket">
          {{ $activeTicket ? $activeTicket->ticket_no : 'Belum Ada Unit' }}
        </span>
      </div>

      <div class="p-3 bg-light rounded-3 border mb-3">
        <div class="row g-2 align-items-center">
          <div class="col-sm-6">
            <span class="text-muted text-uppercase fw-bold d-block" style="font-size: 10px;">Perangkat Konsumen:</span>
            <strong class="text-dark fs-6" id="kpiActiveDevice">
              {{ $activeTicket ? ($activeTicket->device_brand . ' ' . $activeTicket->device_model) : 'Belum Ada Unit di Meja Kerja' }}
            </strong>
            <span class="d-block text-muted small" id="kpiActiveCustomer">
              {{ $activeTicket ? ($activeTicket->customer_name . ' (' . $activeTicket->customer_phone . ')') : 'Menunggu reservasi dari pelanggan.' }}
            </span>
          </div>
          <div class="col-sm-6 text-sm-end">
            <span class="badge badge-warning-accessible px-3 py-1.5 fw-bold" id="kpiActiveStatus">
              {{ $activeTicket ? $activeTicket->status : 'Siap Menerima Unit' }}
            </span>
            <span class="d-block text-muted mt-1 small">Metode: {{ $activeTicket ? $activeTicket->delivery_method : '-' }}</span>
          </div>
        </div>
      </div>

      <div class="small mb-3">
        <span class="text-muted fw-bold d-block mb-1">Keluhan Konsumen / Diagnosa Fisik:</span>
        <p class="text-secondary bg-white p-2.5 rounded-3 border mb-0" id="kpiActiveDiagnosis">
          {{ $activeTicket && $activeTicket->diagnosis ? $activeTicket->diagnosis : ($activeTicket ? $activeTicket->issue_chronology : 'Belum ada unit yang diproses. Silakan input reservasi baru atau tunggu pelanggan submit form.') }}
        </p>
      </div>

      <div class="d-flex flex-wrap gap-2 mt-auto pt-2 border-top">
        @if($activeTicket)
          <a href="{{ url('/teknisi/meja-kerja/' . urlencode($activeTicket->ticket_no)) }}" class="btn btn-primary btn-sm fw-bold px-3 py-2">
            <i class="bi bi-pencil-square me-1" aria-hidden="true"></i> Buka Lembar Diagnosa di Meja Kerja
          </a>
          <a href="{{ url('/invoice?ticket=' . urlencode($activeTicket->ticket_no)) }}" target="_blank" class="btn btn-outline-secondary btn-sm fw-semibold px-3 py-2">
            <i class="bi bi-receipt me-1" aria-hidden="true"></i> Pratinjau Invoice
          </a>
        @else
          <a href="{{ url('/booking') }}" target="_blank" class="btn btn-primary btn-sm fw-bold px-3 py-2">
            <i class="bi bi-plus-circle me-1" aria-hidden="true"></i> Input Tiket Reservasi Pertama
          </a>
        @endif
      </div>
    </div>
  </div>

  <!-- RIGHT: SLA METRICS & KEPATUHAN 1X24 JAM -->
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
      <div class="mb-3 pb-3 border-bottom">
        <h5 class="fw-bold text-dark mb-1">Kepatuhan SLA & Kualitas Layanan</h5>
        <span class="text-muted small">Standarisasi mutu Proyek Sistem Informasi 2.</span>
      </div>

      <div class="space-y-3">
        
        <!-- SLA 1 -->
        <div class="mb-3">
          <div class="d-flex justify-content-between small fw-bold mb-1">
            <span class="text-dark">Respon Cek Fisik Awal (&lt; 15 Menit)</span>
            <span class="text-success">98.5% Tepat Waktu</span>
          </div>
          <div class="progress" style="height: 8px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: 98.5%"></div>
          </div>
        </div>

        <!-- SLA 2 -->
        <div class="mb-3">
          <div class="d-flex justify-content-between small fw-bold mb-1">
            <span class="text-dark">Toleransi Uji 1x24 Jam Auto-Close</span>
            <span class="text-primary">100% Terlaksana</span>
          </div>
          <div class="progress" style="height: 8px;">
            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
          </div>
        </div>

        <!-- SLA 3 -->
        <div class="mb-3">
          <div class="d-flex justify-content-between small fw-bold mb-1">
            <span class="text-dark">Akurasi Estimasi Biaya di Muka</span>
            <span class="text-info">96.0% Sesuai</span>
          </div>
          <div class="progress" style="height: 8px;">
            <div class="progress-bar bg-info" role="progressbar" style="width: 96%"></div>
          </div>
        </div>

        <!-- Rating Box -->
        <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center">
          <div>
            <div class="d-flex align-items-center gap-1 text-warning mb-1">
              <i class="bi bi-star-fill" aria-hidden="true"></i>
              <i class="bi bi-star-fill" aria-hidden="true"></i>
              <i class="bi bi-star-fill" aria-hidden="true"></i>
              <i class="bi bi-star-fill" aria-hidden="true"></i>
              <i class="bi bi-star-fill" aria-hidden="true"></i>
              <span class="text-dark fw-bold ms-1">4.92 / 5.0</span>
            </div>
            <span class="text-muted" style="font-size: 11px;">Berdasarkan ulasan kepuasan pelanggan purnajual</span>
          </div>
          <span class="badge bg-success text-white px-2 py-1">Top Rating</span>
        </div>

      </div>
    </div>
  </div>

</div>

<!-- BARIS 3: AKTIVITAS OPERASIONAL & PERANGKAT SERVIS TERBARU -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
    <div>
      <h5 class="fw-bold text-dark mb-0">Ringkasan Unit Masuk Terbaru</h5>
      <span class="text-muted small">5 tiket terakhir yang terdaftar di database MySQL.</span>
    </div>
    <a href="{{ url('/teknisi/servis') }}" class="btn btn-outline-primary btn-sm fw-semibold">
      Lihat Semua Unit &rarr;
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0 small">
      <thead class="table-light text-uppercase text-secondary">
        <tr>
          <th>No. Tiket</th>
          <th>Perangkat</th>
          <th>Pelanggan</th>
          <th>Kendala</th>
          <th>Status</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentTickets ?? [] as $rt)
          <tr>
            <td><span class="font-monospace fw-bold text-primary">{{ $rt->ticket_no }}</span></td>
            <td><strong>{{ $rt->device_brand }} {{ $rt->device_model }}</strong></td>
            <td>{{ $rt->customer_name }}</td>
            <td style="max-width: 250px;"><span class="text-truncate d-block text-secondary">{{ $rt->issue_chronology }}</span></td>
            <td>
              @php
                $badgeClass = match($rt->step) {
                  1, 2 => 'badge-info-accessible',
                  3, 4 => 'badge-warning-accessible',
                  5 => 'badge-info-accessible',
                  6 => 'badge bg-primary text-white',
                  7 => 'badge-success-accessible',
                  default => 'badge bg-light text-dark border'
                };
              @endphp
              <span class="badge {{ $badgeClass }}">{{ $rt->status }}</span>
            </td>
            <td class="text-end">
              <a href="{{ url('/teknisi/meja-kerja/' . urlencode($rt->ticket_no)) }}" class="btn btn-sm btn-primary py-1 px-2.5 fw-semibold" style="font-size: 11px;">
                Meja Kerja
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-3">Belum ada unit servis di database.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
