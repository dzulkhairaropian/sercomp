<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 24px; }
    .card { max-width: 580px; margin: 0 auto; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .header { background: #0f172a; color: #ffffff; padding: 20px 24px; }
    .header h2 { margin: 0; font-size: 18px; font-weight: 700; }
    .header span { font-size: 12px; color: #94a3b8; }
    .body { padding: 24px; }
    .badge { display: inline-block; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 700; background: #e0f2fe; color: #0284c7; }
    .table { width: 100%; border-collapse: collapse; margin: 16px 0; font-size: 13px; }
    .table th, .table td { padding: 8px 12px; border-bottom: 1px solid #f1f5f9; text-align: left; }
    .table th { background: #f8fafc; color: #64748b; font-weight: 600; }
    .btn { display: inline-block; background: #2563eb; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 14px; text-align: center; margin-top: 16px; }
    .footer { padding: 16px 24px; background: #f8fafc; font-size: 11px; color: #94a3b8; text-align: center; border-top: 1px solid #f1f5f9; }
  </style>
</head>
<body>
  <div class="card">
    <div class="header">
      <h2>SerComp • Service Computer Digital</h2>
      <span>Transparansi Reparasi Laptop & PC Workshop Modern</span>
    </div>
    <div class="body">
      <div style="margin-bottom: 12px;">
        <span class="badge">{{ $order->ticket_no }}</span>
        <span style="font-size: 12px; color: #64748b; margin-left: 8px;">Status: <strong>{{ $order->order_status }}</strong></span>
      </div>
      <h3 style="margin-top: 0; color: #0f172a; font-size: 16px;">{{ $headline }}</h3>
      <p style="font-size: 14px; line-height: 1.6; color: #475569;">
        Halo <strong>{{ $order->customer?->name ?? 'Pelanggan SerComp' }}</strong>,
      </p>
      <p style="font-size: 14px; line-height: 1.6; color: #475569;">
        {{ $detailMessage }}
      </p>

      <table class="table">
        <tr>
          <th>Perangkat</th>
          <td>{{ $order->device_type }} - {{ $order->device_brand }} {{ $order->device_model }}</td>
        </tr>
        @if($order->diagnostic && $order->diagnostic->net_total_cost > 0)
        <tr>
          <th>Estimasi Biaya Jasa</th>
          <td>Rp {{ number_format($order->diagnostic->labor_cost, 0, ',', '.') }}</td>
        </tr>
        <tr>
          <th>Suku Cadang</th>
          <td>Rp {{ number_format($order->diagnostic->parts_cost, 0, ',', '.') }}</td>
        </tr>
        @if($order->diagnostic->addon_cleaning_cost > 0)
        <tr>
          <th>Deep Cleaning</th>
          <td>Rp {{ number_format($order->diagnostic->addon_cleaning_cost, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if($order->diagnostic->addon_repasta_cost > 0)
        <tr>
          <th>Repasta Arctic MX-4</th>
          <td>Rp {{ number_format($order->diagnostic->addon_repasta_cost, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr style="font-weight: bold; background: #f8fafc;">
          <th>Total Terkunci</th>
          <td style="color: #2563eb;">Rp {{ number_format($order->diagnostic->net_total_cost, 0, ',', '.') }}</td>
        </tr>
        @endif
      </table>

      <div style="text-align: center; margin: 24px 0;">
        <a href="{{ $actionUrl }}" class="btn" style="color: #ffffff;">Buka Portal Lacak Tiket</a>
      </div>

      <p style="font-size: 12px; color: #94a3b8; margin: 0;">
        Simpan nomor tiket Anda <strong>{{ $order->ticket_no }}</strong> dan PIN keamanan <strong>{{ $order->security_pin }}</strong>.
      </p>
    </div>
    <div class="footer">
      SerComp Workshop & Logistics • Layanan Cepat, Transparan, Bergaransi 90 Hari.
    </div>
  </div>
</body>
</html>
