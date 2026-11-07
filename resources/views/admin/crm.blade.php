@extends('layouts.admin')

@section('title', 'Manajemen Pelanggan CRM & Tugas BI - SerComp')
@section('page-title', 'Customer Relationship Management (CRM)')
@section('page-subtitle', 'Segmentasi pelanggan setia, prospek tanya-tanya, dan pelacakan detail riwayat servis untuk tugas Business Intelligence.')

@section('page-actions')
<button type="button" class="btn btn-primary btn-sm fw-semibold shadow-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalAddCustomer">
  <i class="bi bi-person-plus-fill"></i> Tambah Akun Pelanggan Khusus
</button>
<a href="{{ route('admin.bi.export') }}" class="btn btn-outline-success btn-sm fw-semibold shadow-sm d-flex align-items-center gap-1">
  <i class="bi bi-file-earmark-spreadsheet"></i> Export Dataset BI (.csv)
</a>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
  <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- 4 KARTU STATISTIK CRM & BI -->
<div class="row g-3 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card-kpi p-3 bg-white rounded-3 border h-100">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Total Database Pelanggan</span>
        <span class="badge bg-secondary-subtle text-secondary rounded-pill p-2"><i class="bi bi-people-fill fs-6"></i></span>
      </div>
      <div class="fs-2 fw-bold text-dark mb-1">{{ $countTotal ?? 0 }} <span class="fs-6 text-muted fw-normal">Orang</span></div>
      <div class="d-flex gap-2 text-secondary small">
        <span class="badge bg-light text-dark border"><i class="bi bi-person me-1"></i>Personal: {{ $countPersonal ?? 0 }}</span>
        <span class="badge bg-dark-subtle text-dark border"><i class="bi bi-building me-1"></i>Company: {{ $countCompany ?? 0 }}</span>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card-kpi p-3 bg-white rounded-3 border h-100">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Customer Setia (Loyal)</span>
        <span class="badge bg-success-subtle text-success rounded-pill p-2"><i class="bi bi-star-fill fs-6"></i></span>
      </div>
      <div class="fs-2 fw-bold text-success mb-1">{{ $countSetia ?? 0 }} <span class="fs-6 text-muted fw-normal">Orang</span></div>
      <div class="text-muted small">Repeat order ≥ 2x atau omzet ≥ Rp 750.000</div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card-kpi p-3 bg-white rounded-3 border h-100">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Customer Aktif (Reguler)</span>
        <span class="badge bg-primary-subtle text-primary rounded-pill p-2"><i class="bi bi-lightning-charge-fill fs-6"></i></span>
      </div>
      <div class="fs-2 fw-bold text-primary mb-1">{{ $countAktif ?? 0 }} <span class="fs-6 text-muted fw-normal">Orang</span></div>
      <div class="text-muted small">1 order sedang/telah selesai dikerjakan</div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card-kpi p-3 bg-white rounded-3 border h-100">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Tanya-tanya Aja (Leads)</span>
        <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill p-2"><i class="bi bi-chat-dots-fill fs-6"></i></span>
      </div>
      <div class="fs-2 fw-bold text-warning mb-1">{{ $countTanya ?? 0 }} <span class="fs-6 text-muted fw-normal">Orang</span></div>
      <div class="text-muted small">Booking awal / batal tanpa bayar (Follow-up)</div>
    </div>
  </div>
</div>

<!-- FILTER & PENCARIAN -->
<div class="card border-0 shadow-sm rounded-4 bg-white p-3 mb-4">
  <form action="{{ route('admin.crm') }}" method="GET" class="row g-2 align-items-center">
    <div class="col-md-5 col-lg-4">
      <div class="input-group">
        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari nama pelanggan, perusahaan, nomor telepon..." value="{{ request('search') }}">
      </div>
    </div>
    <div class="col-md-3 col-lg-3">
      <select name="type" class="form-select bg-light" onchange="this.form.submit()">
        <option value="">-- Semua Tipe Pelanggan --</option>
        <option value="Personal" {{ request('type') === 'Personal' ? 'selected' : '' }}>Personal / Perorangan</option>
        <option value="Company" {{ request('type') === 'Company' ? 'selected' : '' }}>Company / Perusahaan</option>
      </select>
    </div>
    <div class="col-md-3 col-lg-3">
      <select name="segment" class="form-select bg-light" onchange="this.form.submit()">
        <option value="">-- Semua Segmentasi CRM --</option>
        <option value="Customer Setia" {{ request('segment') === 'Customer Setia' ? 'selected' : '' }}>Customer Setia (Loyal)</option>
        <option value="Customer Aktif" {{ request('segment') === 'Customer Aktif' ? 'selected' : '' }}>Customer Aktif (Reguler)</option>
        <option value="Tanya-tanya Aja (Leads)" {{ request('segment') === 'Tanya-tanya Aja (Leads)' ? 'selected' : '' }}>Tanya-tanya Aja (Leads)</option>
      </select>
    </div>
    <div class="col-md-1 col-lg-2 text-md-end">
      <a href="{{ route('admin.crm') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
    </div>
  </form>
</div>

<!-- TABEL DATA PELANGGAN CRM -->
<div class="card border-0 shadow-sm rounded-4 bg-white p-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h6 class="fw-bold text-dark mb-0">Daftar Pelanggan & Analisis LTV (Lifetime Value)</h6>
      <span class="text-muted small">Klasifikasi perilaku pelanggan untuk kampanye promosi dan pelaporan BI</span>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0 small">
      <thead class="table-light">
        <tr>
          <th>Nama Pelanggan</th>
          <th>Tipe Akun</th>
          <th>Kontak (WA & Email)</th>
          <th>Segmentasi CRM</th>
          <th>Frekuensi Servis</th>
          <th>Total Nilai Transaksi</th>
          <th>Perangkat Diservis</th>
          <th class="text-end">Aksi CS</th>
        </tr>
      </thead>
      <tbody>
        @forelse($customers as $c)
        <tr>
          <td>
            <strong class="text-dark d-block">{{ $c->name }}</strong>
            @if($c->customer_type === 'Company' && !empty($c->company_name))
              <span class="d-block text-primary small fw-semibold"><i class="bi bi-building me-1"></i>{{ $c->company_name }}</span>
            @endif
            <span class="text-muted" style="font-size: 11px;">Member sejak {{ $c->created_at?->format('d M Y') }}</span>
          </td>
          <td>
            @if($c->customer_type === 'Company')
              <span class="badge bg-dark text-white px-2 py-1"><i class="bi bi-building me-1"></i>Company</span>
            @else
              <span class="badge bg-light text-secondary border px-2 py-1"><i class="bi bi-person me-1"></i>Personal</span>
            @endif
          </td>
          <td>
            <span class="d-block font-monospace"><i class="bi bi-whatsapp text-success me-1"></i>{{ $c->phone }}</span>
            <span class="d-block text-muted" style="font-size: 11px;">{{ $c->email }}</span>
          </td>
          <td>
            <span class="badge {{ $c->segment_badge }} px-2.5 py-1.5 rounded-pill fw-semibold">
              {{ $c->segment }}
            </span>
          </td>
          <td>
            <span class="fw-bold text-dark">{{ $c->total_orders }} Order</span>
            <span class="d-block text-muted" style="font-size: 10px;">{{ $c->paid_orders }} Lunas • {{ $c->cancelled_orders }} Batal</span>
          </td>
          <td>
            <span class="fw-bold font-monospace text-primary fs-6">Rp {{ number_format($c->total_spent, 0, ',', '.') }}</span>
          </td>
          <td>
            @if(!empty($c->devices))
              @foreach($c->devices as $d)
                <span class="badge bg-light text-dark border d-inline-block mb-1" style="font-size: 10px;">{{ $d }}</span>
              @endforeach
            @else
              <span class="text-muted" style="font-size: 11px;">Belum ada servis</span>
            @endif
          </td>
          <td class="text-end">
            @php
              $cleanPhone = preg_replace('/[^0-9]/', '', $c->phone);
              if (str_starts_with($cleanPhone, '0')) $cleanPhone = '62' . substr($cleanPhone, 1);
              $waText = rawurlencode("Halo Kak {$c->name}, terima kasih telah menjadi bagian dari keluarga SerComp. Ada kendala pada laptop/PC yang bisa kami bantu?");
            @endphp
            <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waText }}" target="_blank" class="btn btn-outline-success btn-sm px-2.5 py-1 fw-semibold" title="Hubungi via WhatsApp">
              <i class="bi bi-whatsapp me-1"></i> WA CS
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center py-5 text-muted">
            Tidak ada data pelanggan yang sesuai dengan filter pencarian.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL TAMBAH PELANGGAN KHUSUS CRM -->
<div class="modal fade" id="modalAddCustomer" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow">
      <div class="modal-header border-bottom p-3 px-4">
        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-person-plus-fill me-2 text-primary"></i>Tambah Akun Pelanggan Khusus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.crm.store') }}" method="POST">
        @csrf
        <div class="modal-body p-4 small">
          <div class="mb-3">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Tipe Pelanggan:</label>
            <div class="d-flex gap-3">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="customer_type" id="crmTypePersonal" value="Personal" checked onchange="document.getElementById('crmCompanyWrapper').classList.add('d-none')">
                <label class="form-check-label" for="crmTypePersonal">Personal</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="customer_type" id="crmTypeCompany" value="Company" onchange="document.getElementById('crmCompanyWrapper').classList.remove('d-none')">
                <label class="form-check-label" for="crmTypeCompany">Company / Korporasi</label>
              </div>
            </div>
          </div>
          <div id="crmCompanyWrapper" class="mb-3 d-none">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Nama Perusahaan / PT / Instansi:</label>
            <input type="text" name="company_name" class="form-control" placeholder="Cth: PT Solusi Digital Nusantara">
          </div>
          <div class="mb-3">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Nama Kontak / PIC Pelanggan:</label>
            <input type="text" name="name" class="form-control" placeholder="Cth: Dr. Iwan Fals / Bpk. Budi Santoso" required>
          </div>
          <div class="mb-3">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Nomor WhatsApp / HP Aktif:</label>
            <input type="tel" name="phone" class="form-control" placeholder="Cth: 081234567890" required>
          </div>
          <div class="mb-3">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Alamat Email (Opsional):</label>
            <input type="email" name="email" class="form-control" placeholder="Cth: customer@perusahaan.com">
          </div>
          <div class="mb-3">
            <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Catatan Khusus CRM / Profil Bisnis:</label>
            <textarea name="notes" rows="2" class="form-control" placeholder="Cth: Pelanggan korporat, punya 10 unit PC kantor..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-top p-3 px-4">
          <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
            <i class="bi bi-save me-1"></i> Simpan ke CRM
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
