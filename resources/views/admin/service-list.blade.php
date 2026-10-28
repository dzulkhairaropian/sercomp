@extends('layouts.admin')

@section('title', 'Daftar Servis Unit Laptop & PC - SerComp')
@section('page-title', 'Daftar Servis Unit (Job Orders)')
@section('page-subtitle', 'Manajemen seluruh perangkat servis, filter kategori unit, dan kontrol tahapan perbaikan.')

@section('page-actions')
<a href="{{ url('/booking') }}" target="_blank" class="btn btn-primary btn-sm fw-semibold shadow-sm d-flex align-items-center gap-1">
  <i class="bi bi-plus-circle" aria-hidden="true"></i> Input Tiket Baru
</a>
@endsection

@section('content')

<!-- FILTER & SEARCH BAR -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
  <div class="row g-3 align-items-center justify-content-between">
    
    <!-- Filter Status Tabs -->
    <div class="col-lg-8">
      <div class="d-flex flex-wrap gap-1" id="filterButtonGroup">
        <button onclick="applyFilter('all', this)" class="btn btn-sm btn-primary fw-semibold px-3 py-1.5 active-filter">
          Semua ({{ $countAll ?? count($tickets) }})
        </button>
        <button onclick="applyFilter('Laptop', this)" class="btn btn-sm btn-outline-secondary fw-semibold px-3 py-1.5">
          <i class="bi bi-laptop me-1" aria-hidden="true"></i> Laptop ({{ $countLaptop ?? 0 }})
        </button>
        <button onclick="applyFilter('PC Desktop', this)" class="btn btn-sm btn-outline-secondary fw-semibold px-3 py-1.5">
          <i class="bi bi-pc-display me-1" aria-hidden="true"></i> PC Desktop ({{ $countPc ?? 0 }})
        </button>
        <button onclick="applyFilter('Menunggu', this)" class="btn btn-sm btn-outline-secondary fw-semibold px-3 py-1.5">
          <i class="bi bi-hourglass-split me-1" aria-hidden="true"></i> Butuh Tindakan ({{ $countApproval ?? 0 }})
        </button>
        <button onclick="applyFilter('Dikerjakan', this)" class="btn btn-sm btn-outline-secondary fw-semibold px-3 py-1.5">
          <i class="bi bi-tools me-1" aria-hidden="true"></i> Dikerjakan ({{ $countProgress ?? 0 }})
        </button>
        <button onclick="applyFilter('Selesai', this)" class="btn btn-sm btn-outline-secondary fw-semibold px-3 py-1.5">
          <i class="bi bi-check-circle me-1" aria-hidden="true"></i> Selesai & Closed ({{ $countCompleted ?? 0 }})
        </button>
      </div>
    </div>

    <!-- Search Input -->
    <div class="col-lg-4">
      <div class="input-group input-group-sm">
        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search" aria-hidden="true"></i></span>
        <label for="serviceSearchInput" class="visually-hidden">Cari Tiket Servis</label>
        <input type="text" id="serviceSearchInput" class="form-control border-start-0" placeholder="Cari nomor tiket, nama, perangkat..." oninput="filterServiceTable()">
      </div>
    </div>

  </div>
</div>

<!-- TABEL UTAMA SERVIS LAPTOP & PC -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0 small" id="serviceTable">
      <thead class="table-light text-uppercase text-secondary">
        <tr>
          <th>No. Tiket</th>
          <th>Tgl Masuk</th>
          <th>Pelanggan</th>
          <th>Perangkat & Spesifikasi</th>
          <th>Gejala Kendala / Diagnosa</th>
          <th>Estimasi Biaya</th>
          <th>Status Servis</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody id="serviceTableBody">
        @forelse($tickets as $t)
          <tr data-device="{{ $t->device_type }}" data-status="{{ $t->status }}" class="{{ $loop->first ? 'table-primary-subtle' : '' }}">
            <td>
              <span class="font-monospace fw-bold text-primary">{{ $t->ticket_no }}</span>
              @if($t->step === 3 || $t->step === 5)
                <span class="badge bg-primary text-white ms-1" style="font-size: 9px;">AKTIF</span>
              @endif
            </td>
            <td><span class="text-muted">{{ $t->created_at ? $t->created_at->format('d M Y') : date('d M Y') }}</span></td>
            <td>
              <strong class="text-dark d-block">{{ $t->customer_name }}</strong>
              <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $t->customer_phone) }}" target="_blank" class="text-decoration-none text-muted" style="font-size: 11px;">
                <i class="bi bi-whatsapp text-success me-1" aria-hidden="true"></i>{{ $t->customer_phone }}
              </a>
            </td>
            <td>
              <span class="badge bg-light text-dark border mb-1">
                <i class="bi {{ $t->device_type === 'Laptop' ? 'bi-laptop' : 'bi-pc-display' }} me-1 text-primary" aria-hidden="true"></i>{{ $t->device_type }}
              </span>
              <strong class="d-block text-dark">{{ $t->device_brand }} {{ $t->device_model }}</strong>
            </td>
            <td style="max-width: 250px;">
              <span class="text-truncate d-block text-secondary" title="{{ $t->issue_chronology }}">
                {{ $t->issue_chronology }}
              </span>
              @if($t->diagnosis)
                <span class="text-dark d-block fw-semibold" style="font-size: 11px;">
                  <i class="bi bi-tools text-primary me-1"></i>{{ Str::limit($t->diagnosis, 60) }}
                </span>
              @else
                <span class="text-muted d-block fst-italic" style="font-size: 11px;">
                  Belum diperiksa fisik
                </span>
              @endif
              @if($t->cost_cleaning > 0 || $t->cost_repasta > 0)
                <span class="text-info fw-semibold" style="font-size: 10px;">
                  Add-on: {{ $t->cost_cleaning > 0 ? 'Cleaning' : '' }} {{ $t->cost_repasta > 0 ? '+ Repasta' : '' }}
                </span>
              @endif
            </td>
            <td>
              <strong class="text-dark">Rp {{ number_format($t->total_cost, 0, ',', '.') }}</strong>
              <span class="d-block {{ $t->payment_status === 'Paid' ? 'text-success' : 'text-warning' }} fw-bold" style="font-size: 10px;">
                {{ strtoupper($t->payment_status) }} {{ $t->payment_status === 'Paid' ? 'QRIS' : '' }}
              </span>
            </td>
            <td>
              @php
                $badgeClass = match($t->step) {
                  1, 2 => 'badge-info-accessible',
                  3, 4 => 'badge-warning-accessible',
                  5 => 'badge-info-accessible',
                  6 => 'badge bg-primary text-white',
                  7 => 'badge-success-accessible',
                  default => 'badge bg-light text-dark border'
                };
              @endphp
              <span class="badge {{ $badgeClass }} px-2.5 py-1.5 fw-bold">
                {{ $t->status }}
              </span>
            </td>
            <td class="text-end">
              <div class="btn-group">
                <a href="{{ url('/teknisi/meja-kerja/' . urlencode($t->ticket_no)) }}" class="btn btn-sm btn-primary fw-semibold" aria-label="Buka di Meja Kerja Tiket {{ $t->ticket_no }}" title="Buka di Meja Kerja Diagnosa">
                  <i class="bi bi-tools me-1" aria-hidden="true"></i> Meja Kerja
                </a>
                <a href="{{ url('/invoice?ticket=' . urlencode($t->ticket_no)) }}" target="_blank" class="btn btn-sm btn-outline-secondary" aria-label="Pratinjau Faktur Invoice" title="Pratinjau Faktur Invoice">
                  <i class="bi bi-receipt" aria-hidden="true"></i>
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-4">Belum ada antrean servis unit di database.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection

@section('scripts')
<script>
  let activeFilterValue = 'all';

  function applyFilter(filter, btn) {
    activeFilterValue = filter;
    
    document.querySelectorAll('#filterButtonGroup .btn').forEach(b => {
      b.className = "btn btn-sm btn-outline-secondary fw-semibold px-3 py-1.5";
    });
    btn.className = "btn btn-sm btn-primary fw-semibold px-3 py-1.5";

    filterServiceTable();
  }

  function filterServiceTable() {
    const search = document.getElementById('serviceSearchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#serviceTableBody tr');

    rows.forEach(r => {
      const text = r.innerText.toLowerCase();
      const dev = r.getAttribute('data-device') || '';
      const st = r.getAttribute('data-status') || '';

      const matchSearch = text.includes(search);
      let matchFilter = true;

      if (activeFilterValue !== 'all') {
        if (activeFilterValue === 'Laptop') {
          matchFilter = dev.includes('Laptop');
        } else if (activeFilterValue === 'PC Desktop') {
          matchFilter = dev.includes('PC Desktop');
        } else if (activeFilterValue === 'Menunggu') {
          matchFilter = st.toLowerCase().includes('menunggu') || st.toLowerCase().includes('approval');
        } else if (activeFilterValue === 'Dikerjakan') {
          matchFilter = st.toLowerCase().includes('dikerjakan');
        } else if (activeFilterValue === 'Selesai') {
          matchFilter = st.toLowerCase().includes('selesai') || st.toLowerCase().includes('closed');
        }
      }

      r.style.display = (matchSearch && matchFilter) ? '' : 'none';
    });
  }
</script>
@endsection
