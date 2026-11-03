@extends('layouts.admin')

@section('title', 'Stok Suku Cadang - SerComp')
@section('page-title', 'Katalog & Inventori Suku Cadang')
@section('page-subtitle', 'Monitoring stok suku cadang original, pasta pendingin, dan add-ons perbaikan workshop.')

@section('page-actions')
<button type="button" class="btn btn-primary btn-sm fw-semibold shadow-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalAddPart">
  <i class="bi bi-plus-circle"></i> Tambah Item Part
</button>
@endsection

@section('content')

<!-- STATS INVENTORI -->
<div class="row g-3 mb-4">
  <div class="col-sm-6 col-md-3">
    <div class="card-kpi">
      <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Total SKU Item</span>
      <div class="fs-3 fw-bold text-dark mt-1">{{ $totalSku ?? 0 }} Jenis</div>
      <span class="text-primary small">Suku Cadang & Add-on</span>
    </div>
  </div>
  <div class="col-sm-6 col-md-3">
    <div class="card-kpi">
      <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Total Unit Fisik</span>
      <div class="fs-3 fw-bold text-success mt-1">{{ $totalPcs ?? 0 }} Pcs</div>
      <span class="text-muted small">Tersedia di Rak Workshop</span>
    </div>
  </div>
  <div class="col-sm-6 col-md-3">
    <div class="card-kpi">
      <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Stok Menipis</span>
      <div class="fs-3 fw-bold text-warning mt-1">{{ $lowStockCount ?? 0 }} Item</div>
      <span class="text-warning small fw-semibold">Perlu Restock</span>
    </div>
  </div>
  <div class="col-sm-6 col-md-3">
    <div class="card-kpi bg-primary-subtle border-primary-subtle">
      <span class="text-primary text-uppercase fw-bold" style="font-size: 11px;">Estimasi Valuasi Stok</span>
      <div class="fs-3 fw-bold text-primary mt-1">Rp {{ number_format(($totalValuation ?? 0), 0, ',', '.') }}</div>
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
          <th>Harga Jual Konsumen</th>
          <th>Status</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody id="sparepartBody">
        @forelse($spareparts ?? [] as $part)
          <tr>
            <td><code class="text-primary fw-bold">{{ $part->part_code }}</code></td>
            <td><strong>{{ $part->part_name }}</strong></td>
            <td><span class="badge bg-light text-dark border">{{ ucfirst($part->category) }}</span></td>
            <td><strong class="fs-6 {{ $part->stock_qty <= 3 ? 'text-warning' : 'text-dark' }}">{{ $part->stock_qty }}</strong> Pcs</td>
            <td class="text-primary fw-bold">Rp {{ number_format($part->unit_price, 0, ',', '.') }}</td>
            <td>
              @if($part->stock_qty <= 3)
                <span class="badge badge-warning-accessible">Menipis</span>
              @else
                <span class="badge badge-success-accessible">Tersedia</span>
              @endif
            </td>
            <td class="text-end">
              <button type="button" 
                      class="btn btn-sm btn-outline-secondary py-1 px-2.5" 
                      onclick="openEditModal({{ $part->id }}, '{{ addslashes($part->part_code) }}', '{{ addslashes($part->part_name) }}', '{{ $part->category }}', {{ $part->stock_qty }}, {{ $part->unit_price }})">
                <i class="bi bi-pencil-square me-1"></i> Edit
              </button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">Belum ada suku cadang terdaftar di database.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL TAMBAH SUKU CADANG BARU -->
<div class="modal fade" id="modalAddPart" tabindex="-1" aria-labelledby="modalAddPartLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow rounded-4">
      <form action="{{ route('admin.sparepart.store') }}" method="POST">
        @csrf
        <div class="modal-header border-bottom">
          <h6 class="modal-title fw-bold text-dark" id="modalAddPartLabel">
            <i class="bi bi-plus-circle-fill text-primary me-2"></i> Tambah Item Suku Cadang Baru
          </h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4 row g-3 small">
          <div class="col-12">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Kode Part (Unique SKU):</label>
            <input type="text" name="part_code" class="form-control font-monospace" placeholder="Contoh: FAN-ASUS-G15" required>
          </div>
          <div class="col-12">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Nama Komponen Part:</label>
            <input type="text" name="part_name" class="form-control" placeholder="Contoh: Modul Dual Fan Cooler Lenovo Legion 5" required>
          </div>
          <div class="col-md-6">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Kategori Sesuai Spec 3NF:</label>
            <select name="category" class="form-select" required>
              <option value="Fan">Fan</option>
              <option value="RAM">RAM</option>
              <option value="SSD">SSD</option>
              <option value="Screen">Screen</option>
              <option value="PSU">PSU</option>
              <option value="Cable">Cable</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Stok Awal (Pcs):</label>
            <input type="number" name="stock_qty" class="form-control" min="0" value="5" required>
          </div>
          <div class="col-12">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Harga Jual Konsumen (Rp):</label>
            <input type="number" name="unit_price" class="form-control" min="0" step="1000" placeholder="220000" required>
          </div>
        </div>
        <div class="modal-footer border-top p-3">
          <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
            <i class="bi bi-save me-1"></i> Simpan Suku Cadang
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL EDIT / UPDATE STOK PART -->
<div class="modal fade" id="modalEditPart" tabindex="-1" aria-labelledby="modalEditPartLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow rounded-4">
      <form id="formEditPart" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header border-bottom">
          <h6 class="modal-title fw-bold text-dark" id="modalEditPartLabel">
            <i class="bi bi-pencil-square text-primary me-2"></i> Update Stok Suku Cadang
          </h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4 row g-3 small">
          <div class="col-12">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Kode Part (SKU):</label>
            <input type="text" id="editPartCode" class="form-control font-monospace bg-light" readonly>
          </div>
          <div class="col-12">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Nama Komponen:</label>
            <input type="text" name="part_name" id="editPartName" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Kategori:</label>
            <select name="category" id="editCategory" class="form-select" required>
              <option value="Fan">Fan</option>
              <option value="RAM">RAM</option>
              <option value="SSD">SSD</option>
              <option value="Screen">Screen</option>
              <option value="PSU">PSU</option>
              <option value="Cable">Cable</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Stok Fisik Tersedia (Pcs):</label>
            <input type="number" name="stock_qty" id="editStockQty" class="form-control" min="0" required>
          </div>
          <div class="col-12">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Harga Satuan (Rp):</label>
            <input type="number" name="unit_price" id="editUnitPrice" class="form-control" min="0" step="1000" required>
          </div>
        </div>
        <div class="modal-footer border-top p-3">
          <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
            <i class="bi bi-check2-circle me-1"></i> Perbarui Stok Part
          </button>
        </div>
      </form>
    </div>
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

  function openEditModal(id, code, name, category, stock, price) {
    document.getElementById('formEditPart').action = "{{ url('/teknisi/sparepart') }}/" + id;
    document.getElementById('editPartCode').value = code;
    document.getElementById('editPartName').value = name;
    document.getElementById('editCategory').value = category;
    document.getElementById('editStockQty').value = stock;
    document.getElementById('editUnitPrice').value = price;

    const modal = new bootstrap.Modal(document.getElementById('modalEditPart'));
    modal.show();
  }
</script>
@endsection
