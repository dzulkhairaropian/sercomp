@extends('layouts.admin')

@section('title', 'Dashboard Operasional Meja Kerja Teknisi - SerComp')
@section('page-title', 'Dashboard Teknisi Workshop')
@section('page-subtitle', 'Monitoring Pengerjaan Hardware, Diagnosa Fisik & Bukti Purnajual.')

@section('content')
<div class="row g-3 mb-4">
  <!-- Card 1: Antrean Diagnosa Fisik -->
  <div class="col-sm-6 col-xl-3">
    <div class="card-kpi p-3 bg-white rounded-3 border">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">1. Tugas Baru Di-Assign</span>
        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Perlu Dicek</span>
      </div>
      <div class="d-flex align-items-baseline gap-2">
        <h2 class="fw-bold mb-0 text-danger">{{ $queueCheck ?? 0 }}</h2>
        <span class="text-muted small">Unit Ditugaskan</span>
      </div>
      <div class="text-secondary small mt-2">
        <i class="bi bi-person-check-fill me-1 text-danger"></i> Ditugaskan oleh Admin
      </div>
    </div>
  </div>

  <!-- Card 2: Siap Reparasi (QRIS Paid) -->
  <div class="col-sm-6 col-xl-3">
    <div class="card-kpi p-3 bg-white rounded-3 border">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">2. Siap Dikerjakan (Paid)</span>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">SOP Aman</span>
      </div>
      <div class="d-flex align-items-baseline gap-2">
        <h2 class="fw-bold mb-0 text-primary">{{ $queueRepair ?? 0 }}</h2>
        <span class="text-muted small">Unit</span>
      </div>
      <div class="text-secondary small mt-2">
        <i class="bi bi-shield-check me-1 text-primary"></i> QRIS lunas, siap bongkar
      </div>
    </div>
  </div>

  <!-- Card 3: Tunggu Bukti Foto Dual-Evidence -->
  <div class="col-sm-6 col-xl-3">
    <div class="card-kpi p-3 bg-white rounded-3 border">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">3. Butuh Upload Foto</span>
        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill">Dual Evidence</span>
      </div>
      <div class="d-flex align-items-baseline gap-2">
        <h2 class="fw-bold mb-0 text-info">{{ $queueDualEvidence ?? 0 }}</h2>
        <span class="text-muted small">Unit</span>
      </div>
      <div class="text-secondary small mt-2">
        <i class="bi bi-camera me-1 text-info"></i> Running test & part lama
      </div>
    </div>
  </div>

  <!-- Card 4: Servis Selesai & Bergaransi -->
  <div class="col-sm-6 col-xl-3">
    <div class="card-kpi p-3 bg-white rounded-3 border">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">4. Servis Selesai</span>
        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Selesai/Garansi</span>
      </div>
      <div class="d-flex align-items-baseline gap-2">
        <h2 class="fw-bold mb-0 text-success">{{ $myCompletedCount ?? 0 }}</h2>
        <span class="text-muted small">Unit</span>
      </div>
      <div class="text-secondary small mt-2">
        <i class="bi bi-check2-circle me-1 text-success"></i> Kualitas teruji normal
      </div>
    </div>
  </div>
</div>

<!-- SOP MEJA KERJA TEKNISI & ANTREAN OPERASIONAL -->
<div class="row g-4">
  <!-- Kolom Kiri: SOP Meja Kerja Standar Teknisi -->
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
      <div class="d-flex align-items-center gap-2 mb-3">
        <div class="badge bg-primary text-white p-2 rounded-3">
          <i class="bi bi-shield-check fs-6"></i>
        </div>
        <div>
          <h6 class="fw-bold text-dark mb-0">SOP Standar Teknisi Workshop</h6>
          <span class="text-muted small" style="font-size: 11px;">Pedoman kerja teknisi SerComp</span>
        </div>
      </div>

      <div class="list-group list-group-flush small">
        <div class="list-group-item px-0 py-2 border-0 d-flex gap-2">
          <i class="bi bi-1-circle-fill text-primary mt-0.5"></i>
          <div>
            <strong>Pengerjaan Berdasarkan Penugasan Admin</strong>
            <p class="text-muted mb-0" style="font-size: 11px;">Teknisi hanya menangani tiket yang telah di-assign oleh Admin Workshop sesuai spesialisasi.</p>
          </div>
        </div>
        <div class="list-group-item px-0 py-2 border-0 d-flex gap-2">
          <i class="bi bi-2-circle-fill text-primary mt-0.5"></i>
          <div>
            <strong>Fokus Diagnosa Teknis Tanpa Beban Harga</strong>
            <p class="text-muted mb-0" style="font-size: 11px;">Input kerusakan fisik & part rekomendasi. Kalkulasi harga & otorisasi biaya ditangani Admin.</p>
          </div>
        </div>
        <div class="list-group-item px-0 py-2 border-0 d-flex gap-2">
          <i class="bi bi-3-circle-fill text-primary mt-0.5"></i>
          <div>
            <strong>Dilarang Bongkar Sebelum QRIS Paid</strong>
            <p class="text-muted mb-0" style="font-size: 11px;">Pastikan status unit minimal "Reparasi" (konsumen telah menyetujui estimasi & bayar QRIS).</p>
          </div>
        </div>
        <div class="list-group-item px-0 py-2 border-0 d-flex gap-2">
          <i class="bi bi-4-circle-fill text-primary mt-0.5"></i>
          <div>
            <strong>Wajib 2 Bukti Foto (Dual Evidence)</strong>
            <p class="text-muted mb-0" style="font-size: 11px;">Wajib upload foto running test suhu normal dan foto fisik modul suku cadang lama sebelum menyelesaikan unit.</p>
          </div>
        </div>
      </div>

      <div class="mt-3 pt-3 border-top">
        <a href="{{ route('admin.workbench') }}" class="btn btn-primary w-100 fw-bold btn-sm py-2">
          <i class="bi bi-tools me-1"></i> Buka Meja Kerja Diagnosa
        </a>
      </div>
    </div>
  </div>

  <!-- Kolom Kanan: Antrean Unit Perlu Penanganan Teknisi -->
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h6 class="fw-bold text-dark mb-0">Antrean Perbaikan Unit Teknisi</h6>
          <span class="text-muted small">Daftar perangkat laptop & PC yang perlu diagnosa fisik dan pengerjaan</span>
        </div>
        <a href="{{ route('admin.servis') }}" class="btn btn-outline-primary btn-sm fw-semibold">
          Lihat Semua ({{ count($recentQueue ?? []) }})
        </a>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
          <thead class="table-light">
            <tr>
              <th>No. Tiket</th>
              <th>Perangkat</th>
              <th>Keluhan Pelanggan</th>
              <th>Status</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentQueue ?? [] as $t)
            <tr class="{{ $t->order_status === 'Di Workshop' ? 'table-warning-subtle' : '' }}">
              <td>
                <span class="fw-bold font-monospace text-primary">{{ $t->ticket_no }}</span>
                @if($t->order_status === 'Di Workshop')
                  <span class="badge bg-danger text-white ms-1" style="font-size: 9px;"><i class="bi bi-bell-fill me-0.5"></i>TUGAS BARU</span>
                @endif
                <span class="d-block text-muted" style="font-size: 10px;">{{ $t->created_at?->diffForHumans() }}</span>
              </td>
              <td>
                <strong>{{ $t->device_type }}</strong>
                <span class="d-block text-muted">{{ $t->device_brand }} {{ $t->device_model }}</span>
              </td>
              <td>
                <span class="text-truncate d-inline-block text-dark" style="max-width: 200px;">{{ $t->issue_chronology }}</span>
              </td>
              <td>
                @if($t->order_status === 'Reparasi')
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-bold">Siap Servis</span>
                @elseif($t->order_status === 'Selesai')
                  <span class="badge badge-success-accessible">Selesai</span>
                @elseif($t->order_status === 'Approval Biaya')
                  <span class="badge badge-warning-accessible">Tunggu Approval</span>
                @else
                  <span class="badge badge-status-neutral">{{ $t->order_status }}</span>
                @endif
              </td>
              <td class="text-end">
                <a href="{{ route('admin.workbench', ['ticket' => $t->ticket_no]) }}" class="btn btn-sm btn-primary fw-semibold px-2.5 py-1">
                  <i class="bi bi-tools me-1"></i> Tangani
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center py-4 text-muted">
                Belum ada antrean unit untuk saat ini.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
