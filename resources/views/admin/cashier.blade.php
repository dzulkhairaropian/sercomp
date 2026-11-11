@extends('layouts.admin')

@section('title', 'Kasir & Serah Terima Unit - SerComp')
@section('page-title', 'Kasir & Meja Penyerahan Unit')
@section('page-subtitle', 'Penerimaan pelunasan (Tunai, EDC, Transfer, QRIS), pencetakan faktur invoice A4, dan serah terima unit ber-tandatangan.')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
  <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- KPI KASIR & PENYERAHAN -->
<div class="row g-3 mb-4">
  <div class="col-sm-6 col-md-4">
    <div class="card-kpi p-3 bg-white rounded-3 border">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Belum Lunas (Bayar di Kasir)</span>
        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">Kasir</span>
      </div>
      <div class="d-flex align-items-baseline gap-2">
        <h2 class="fw-bold mb-0 text-dark">{{ $countUnpaidReady ?? 0 }}</h2>
        <span class="text-muted small">Unit Siap Bayar</span>
      </div>
      <div class="text-secondary small mt-2">
        <i class="bi bi-wallet2 me-1 text-warning"></i> Selesai diservis, menunggu pembayaran
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-md-4">
    <div class="card-kpi p-3 bg-white rounded-3 border">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Siap Diserahkan ke Konsumen</span>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Handover</span>
      </div>
      <div class="d-flex align-items-baseline gap-2">
        <h2 class="fw-bold mb-0 text-primary">{{ $countReadyHandover ?? 0 }}</h2>
        <span class="text-muted small">Unit di Etalase</span>
      </div>
      <div class="text-secondary small mt-2">
        <i class="bi bi-box-seam me-1 text-primary"></i> Membutuhkan tanda tangan digital
      </div>
    </div>
  </div>

  <div class="col-sm-12 col-md-4">
    <div class="card-kpi p-3 bg-white rounded-3 border">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <span class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Total Riwayat Selesai</span>
        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Arsip</span>
      </div>
      <div class="d-flex align-items-baseline gap-2">
        <h2 class="fw-bold mb-0 text-success">{{ count($orders ?? []) }}</h2>
        <span class="text-muted small">Transaksi</span>
      </div>
      <div class="text-secondary small mt-2">
        <i class="bi bi-shield-check me-1 text-success"></i> Bergaransi e-Warranty
      </div>
    </div>
  </div>
</div>

<!-- DAFTAR UNIT SELESAI SIAP KASIR -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h6 class="fw-bold text-dark mb-0">Antrean Kasir & Serah Terima Unit</h6>
      <span class="text-muted small">Daftar unit yang telah selesai diperbaiki teknisi dan siap ditransaksikan</span>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0 small">
      <thead class="table-light text-uppercase text-secondary">
        <tr>
          <th>No. Tiket</th>
          <th>Pelanggan</th>
          <th>Perangkat</th>
          <th>Teknisi PJ</th>
          <th>Total Tagihan</th>
          <th>Status Bayar</th>
          <th>Serah Terima</th>
          <th class="text-end">Aksi Kasir</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $o)
        <tr>
          <td>
            <span class="fw-bold font-monospace text-primary">{{ $o->ticket_no }}</span>
            <span class="d-block text-muted" style="font-size: 10px;">{{ $o->updated_at?->diffForHumans() }}</span>
          </td>
          <td>
            <strong class="text-dark d-block">{{ $o->customer?->name ?? 'Guest' }}</strong>
            <span class="text-muted" style="font-size: 11px;"><i class="bi bi-telephone me-1"></i>{{ $o->customer?->phone ?? '-' }}</span>
          </td>
          <td>
            <strong>{{ $o->device_type }}</strong>
            <span class="d-block text-muted">{{ $o->device_brand }} {{ $o->device_model }}</span>
          </td>
          <td>
            <span class="badge bg-light text-dark border">
              <i class="bi bi-tools text-primary me-1"></i>{{ $o->technician?->name ?? 'Staf Workshop' }}
            </span>
          </td>
          <td>
            <strong class="text-dark fs-6 font-monospace">Rp {{ number_format($o->invoice?->amount_total ?? 0, 0, ',', '.') }}</strong>
          </td>
          <td>
            @if($o->invoice?->payment_status === 'Paid')
              <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold">
                <i class="bi bi-check-circle-fill me-1"></i> LUNAS ({{ $o->invoice->payment_method }})
              </span>
            @else
              <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-bold">
                <i class="bi bi-clock-fill me-1"></i> BELUM LUNAS
              </span>
            @endif
          </td>
          <td>
            @if($o->unit_handed_over)
              <span class="badge bg-success text-white">Diserahkan</span>
              <span class="d-block text-muted" style="font-size: 9px;">Penerima: {{ $o->receiver_name }}</span>
            @else
              <span class="badge bg-warning text-dark">Belum Diserahkan</span>
            @endif
          </td>
          <td class="text-end">
            <div class="btn-group btn-group-sm">
              @if($o->invoice?->payment_status !== 'Paid')
                <button type="button" class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalPay{{ $o->id }}">
                  <i class="bi bi-cash me-1"></i> Bayar
                </button>
              @endif

              <a href="{{ url('/invoice?ticket=' . urlencode($o->ticket_no)) }}" target="_blank" class="btn btn-outline-secondary" title="Cetak Invoice Faktur A4">
                <i class="bi bi-receipt"></i> Cetak
              </a>

              <a href="{{ route('admin.meja-kerja', ['ticket' => $o->ticket_no]) }}" class="btn btn-outline-primary" title="Buka Detail">
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>

            <!-- MODAL BAYAR KASIR -->
            @if($o->invoice?->payment_status !== 'Paid')
            <div class="modal fade" id="modalPay{{ $o->id }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow text-start">
                  <form action="{{ route('admin.kasir.pay', ['ticket' => $o->ticket_no]) }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom p-3 px-4">
                      <h6 class="modal-title fw-bold text-dark"><i class="bi bi-cash-stack text-success me-2"></i>Penerimaan Pembayaran Kasir</h6>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 small">
                      <div class="p-3 rounded-3 bg-light border mb-3">
                        <div class="d-flex justify-content-between mb-1">
                          <span class="text-muted">Nomor Tiket:</span>
                          <strong class="font-monospace text-primary">#{{ $o->ticket_no }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                          <span class="text-muted">Pelanggan:</span>
                          <strong>{{ $o->customer?->name }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                          <span class="text-muted">Total Tagihan:</span>
                          <strong class="text-success fs-6 font-monospace">Rp {{ number_format($o->invoice?->amount_total ?? 0, 0, ',', '.') }}</strong>
                        </div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 10px;">Metode Pembayaran:</label>
                        <select name="payment_method" class="form-select form-select-sm" required>
                          <option value="Tunai / Kasir">Uang Tunai / Cash (Kasir)</option>
                          <option value="Transfer Bank / EDC">Debit / Kartu Kredit EDC / Transfer Bank</option>
                          <option value="QRIS Dinamis">QRIS Statis / Dinamis</option>
                        </select>
                      </div>
                    </div>
                    <div class="modal-footer border-top p-3 px-4">
                      <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-success btn-sm fw-bold px-3">
                        <i class="bi bi-check2-circle me-1"></i> Konfirmasi & Cetak Kuitansi
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            @endif

          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center py-4 text-muted">
            Belum ada unit selesai yang siap ditransaksikan kasir.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
