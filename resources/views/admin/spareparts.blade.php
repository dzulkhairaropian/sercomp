@extends('layouts.admin')

@section('title', 'Stok Suku Cadang - SerComp')
@section('page-title', 'Katalog & Inventori Suku Cadang')
@section('page-subtitle', 'Monitoring stok suku cadang original, pasta pendingin, dan add-ons perbaikan workshop.')

@section('page-actions')
<button onclick="alert('Modal Tambah Suku Cadang Baru siap diintegrasikan!')" class="btn btn-primary btn-sm fw-semibold shadow-sm d-flex align-items-center gap-1">
  <i class="bi bi-plus-circle"></i> Tambah Item Part
</button>
@endsection

@section('content')

<!-- STATS INVENTORI -->
<div class="row g-3 mb-4">
  <div class="col-sm-6 col-md-3">
    <div class="card-kpi">
      <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Total SKU Item</span>
      <div class="fs-3 fw-bold text-dark mt-1">{{ $totalSku ?? 24 }} Jenis</div>
      <span class="text-primary small">Suku Cadang & Add-on</span>
    </div>
  </div>
  <div class="col-sm-6 col-md-3">
    <div class="card-kpi">
      <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Total Unit Fisik</span>
      <div class="fs-3 fw-bold text-success mt-1">{{ $totalPcs ?? 142 }} Pcs</div>
      <span class="text-muted small">Tersedia di Rak Workshop</span>
    </div>
  </div>
  <div class="col-sm-6 col-md-3">
    <div class="card-kpi">
      <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Stok Menipis</span>
      <div class="fs-3 fw-bold text-warning mt-1">{{ $lowStockCount ?? 2 }} Item</div>
      <span class="text-warning small fw-semibold">Perlu Restock</span>
    </div>
  </div>
  <div class="col-sm-6 col-md-3">
    <div class="card-kpi bg-primary-subtle border-primary-subtle">
      <span class="text-primary text-uppercase fw-bold" style="font-size: 11px;">Estimasi Valuasi Stok</span>
      <div class="fs-3 fw-bold text-primary mt-1">Rp {{ number_format(($totalValuation ?? 12800000), 0, ',', '.') }}</div>
      <span class="text-muted small">Nilai Aset Komponen</span>
    </div>
  </div>
</div>

<!-- TABEL DAFTAR SUKU CADANG -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 pb-3 border-bottom">
    <div>
      <h5 class="fw-bold text-dark mb-0">Daftar Komponen & Add-on Workshop</h5>
      <span class="text-muted small">Suku cadang bergaransi distributor resmi Indonesia.</span>
    </div>

    <input type="text" id="sparepartSearch" class="form-control form-control-sm" placeholder="Cari nama part / kode..." style="max-width: 250px;" oninput="filterParts()">
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0 small" id="sparepartTable">
      <thead class="table-light text-uppercase text-secondary">
        <tr>
          <th>Kode Part</th>
          <th>Nama Komponen</th>
          <th>Kategori</th>
          <th>Stok Tersedia</th>
          <th>Harga Beli (Modal)</th>
          <th>Harga Jual Konsumen</th>
          <th>Margin / Unit</th>
          <th>Status</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody id="sparepartBody">
        @if(isset($spareparts) && $spareparts->count() > 0)
          @foreach($spareparts as $part)
            <tr>
              <td><code class="text-primary fw-bold">{{ $part->part_code }}</code></td>
              <td><strong>{{ $part->name }}</strong></td>
              <td><span class="badge bg-light text-dark border">{{ ucfirst($part->category) }}</span></td>
              <td><strong class="fs-6 {{ $part->stock <= $part->min_stock_alert ? 'text-warning' : 'text-dark' }}">{{ $part->stock }}</strong> Pcs</td>
              <td>Rp {{ number_format($part->cost_price, 0, ',', '.') }}</td>
              <td class="text-primary fw-bold">Rp {{ number_format($part->selling_price, 0, ',', '.') }}</td>
              <td><span class="text-success fw-semibold">+Rp {{ number_format($part->selling_price - $part->cost_price, 0, ',', '.') }}</span></td>
              <td>
                @if($part->stock <= $part->min_stock_alert)
                  <span class="badge badge-warning-accessible">Menipis</span>
                @else
                  <span class="badge badge-success-accessible">Tersedia</span>
                @endif
              </td>
              <td class="text-end">
                <button onclick="alert('Kelola stok {{ $part->part_code }}')" class="btn btn-sm btn-outline-secondary py-1 px-2" aria-label="Kelola stok {{ $part->name }}">Edit</button>
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td><code class="text-primary fw-bold">FAN-ASUS-G15</code></td>
            <td><strong>Modul Fan Cooler Original Asus ROG Strix G15</strong></td>
            <td><span class="badge bg-light text-dark border">Cooling</span></td>
            <td><strong class="fs-6 text-dark">5</strong> Pcs</td>
            <td>Rp 160.000</td>
            <td class="text-primary fw-bold">Rp 220.000</td>
            <td><span class="text-success fw-semibold">+Rp 60.000</span></td>
            <td><span class="badge badge-success-accessible">Tersedia</span></td>
            <td class="text-end">
              <button onclick="alert('Update stok FAN-ASUS-G15')" class="btn btn-sm btn-outline-secondary py-1 px-2">Edit</button>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>
    </table>
  </div>
</div>

@endsection

@section('scripts')
<script>
  function filterParts() {
    const q = document.getElementById('sparepartSearch').value.toLowerCase();
    const rows = document.querySelectorAll('#sparepartBody tr');
    rows.forEach(r => {
      r.style.display = r.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
  }
</script>
@endsection
