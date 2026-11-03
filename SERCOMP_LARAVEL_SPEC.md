# SERCOMP - SISTEM INFORMASI JASA SERVICE KOMPUTER

> Berdasarkan Dokumen Capstone Project: `LAPORAN_TUGAS_LENGKAP_SIAP_KUMPUL_PROYEK_SISTEM_INFORMASI_II_v2.6_FINAL.docx`  
> Program Studi Sistem Informasi, Fakultas Teknologi Informasi, Universitas Nusa Mandiri (2026).

---

## 1. SPESIFIKASI STACK & FITUR UTAMA

- **Backend:** Laravel 11 / 12 / 13 (PHP 8.2+)
- **Database:** MySQL 8.0 (InnoDB, Skema 3NF)
- **Frontend:** Blade, Bootstrap 5.3, Tailwind CSS v3, FontAwesome 6
- **Integrasi:** Midtrans (QRIS Dinamis API), WhatsApp Gateway, AWS S3 / Local Storage

### 5 Pilar Bisnis SerComp
1. **Pemesanan Tanpa Registrasi (Guest-Friendly):** Konsumen cukup isi formulir reservasi kerusakan laptop/PC, langsung terbit nomor resi tiket unik (`SC-2026-XXXX`).
2. **Estimasi Terkunci Pasca-Bongkar Meja Kerja:** Biaya servis tidak ditebak di awal. Teknisi memeriksa tegangan & kerusakan fisik di meja kerja workshop, menerbitkan lembar persetujuan resmi. Tidak ada biaya siluman.
3. **Pembayaran Terintegrasi QRIS Dinamis:** Konsumen menyetujui rincian biaya lalu membayar via QRIS. Status otomatis berubah via webhook Midtrans sebelum teknisi membongkar suku cadang.
4. **Bukti Foto Purnajual Ganda (Dual Evidence):** Wajib unggah 2 foto sebelum tiket selesai:
   - Foto unit hidup normal lolos uji running test suhu stabil.
   - Foto suku cadang lama/rusak yang diganti untuk diserahkan kembali ke pelanggan.
5. **Otomasi SLA 1x24 Jam & e-Warranty 90 Hari:** Saat unit diserahkan (`unit_handed_over = true`), timer 24 jam berjalan. Jika pelanggan tidak mengajukan keluhan, scheduler menutup tiket otomatis (`Closed`) dan menerbitkan sertifikat digital garansi 90 hari.

---

## 2. STATE MACHINE 7 TAHAP STATUS SERVIS

```
[1. Booking] 
    └── Konsumen input form di web → Terbit nomor resi SC-2026-XXXX
[2. Di Workshop] 
    └── Unit fisik tiba di meja kerja teknisi untuk diagnosa hardware
[3. Approval Biaya] 
    └── Lembar persetujuan terbit (ongkos jasa, part, add-on)
[4. Paid] 
    └── Konsumen bayar lunas via QRIS Dinamis (Webhook Midtrans)
[5. Reparasi] 
    └── Teknisi eksekusi pengerjaan & penggantian sparepart
[6. Selesai] 
    └── Teknisi upload 2 foto bukti (running test normal & part lama)
[7. Diserahkan -> Closed] 
    └── Unit diterima konsumen → SLA 24 jam habis → Auto-Closed → e-Warranty 90 hari aktif
```

---

## 3. SKEMA BASIS DATA (ERD RELASIONAL 3NF)

```mermaid
erDiagram
    USERS ||--o{ SERVICE_ORDERS : "membuat / menangani"
    SERVICE_ORDERS ||--|| DIAGNOSTICS : "memiliki lembar persetujuan"
    SERVICE_ORDERS ||--|| INVOICES : "diterbitkan faktur"
    INVOICES ||--|| WARRANTIES : "mengaktifkan garansi"
    SERVICE_ORDERS ||--o{ ORDER_ITEMS : "memiliki rincian suku cadang"
    SPAREPARTS ||--o{ ORDER_ITEMS : "katalog suku cadang"

    USERS {
        bigint id PK
        string name
        string email UK
        string phone
        string password
        enum role "customer, technician, admin"
        timestamps created_at_updated_at
    }

    SERVICE_ORDERS {
        bigint id PK
        bigint customer_id FK "nullable"
        bigint technician_id FK "nullable"
        string ticket_no UK "SC-YYYY-XXXX"
        enum device_type "Laptop, PC Desktop"
        string device_brand
        string device_model
        text issue_chronology
        string issue_photo_url "nullable"
        boolean addon_cleaning "default false"
        boolean addon_repasta "default false"
        enum delivery_method "Diantar Sendiri, Ambil oleh Teknisi"
        text pickup_address "nullable"
        enum order_status "Booking, Di Workshop, Approval Biaya, Paid, Reparasi, Selesai, Diserahkan, Closed"
        boolean unit_handed_over "default false"
        timestamp handed_over_at "nullable"
        timestamp sla_auto_close_at "nullable"
        timestamps created_at_updated_at
    }

    DIAGNOSTICS {
        bigint id PK
        bigint service_order_id FK UK
        text physical_damage_analysis
        text action_plan
        decimal labor_cost "12,2"
        decimal parts_cost "12,2"
        decimal addon_cleaning_cost "12,2"
        decimal addon_repasta_cost "12,2"
        decimal net_total_cost "12,2"
        string evidence_running_test_photo "nullable"
        string evidence_replaced_part_photo "nullable"
        boolean is_approved_by_user "default false"
        timestamp approved_at "nullable"
        timestamps created_at_updated_at
    }

    INVOICES {
        bigint id PK
        bigint service_order_id FK UK
        string invoice_no UK "INV-SC-YYYY-XXXX"
        decimal amount_total "12,2"
        enum payment_method "QRIS Dinamis"
        enum payment_status "Unpaid, Paid, Expired"
        string qris_transaction_id "nullable"
        timestamp paid_at "nullable"
        timestamps created_at_updated_at
    }

    WARRANTIES {
        bigint id PK
        bigint invoice_id FK UK
        string warranty_code UK "WAR-YYYY-XXXX"
        int warranty_duration_days "default 90"
        date starts_at
        date expires_at
        enum warranty_status "Active, Claimed, Expired"
        timestamps created_at_updated_at
    }

    SPAREPARTS {
        bigint id PK
        string part_code UK
        string part_name
        enum category "Fan, RAM, SSD, Screen, PSU, Cable"
        int stock_qty
        decimal unit_price "12,2"
        timestamps created_at_updated_at
    }

    ORDER_ITEMS {
        bigint id PK
        bigint service_order_id FK
        bigint sparepart_id FK
        string item_name
        int quantity
        decimal unit_price "12,2"
        decimal subtotal "12,2"
        timestamps created_at_updated_at
    }
```

---

## 4. FILE MIGRATION LARAVEL LENGKAP

Salin kode ini ke file `database/migrations/2026_01_01_000001_create_sercomp_tables.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id();
            $table->string('part_code', 50)->unique();
            $table->string('part_name');
            $table->enum('category', ['Fan', 'RAM', 'SSD', 'Screen', 'PSU', 'Cable']);
            $table->unsignedInteger('stock_qty')->default(0);
            $table->decimal('unit_price', 12, 2);
            $table->timestamps();
        });

        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ticket_no', 32)->unique();
            $table->enum('device_type', ['Laptop', 'PC Desktop']);
            $table->string('device_brand', 80);
            $table->string('device_model', 120);
            $table->text('issue_chronology');
            $table->string('issue_photo_url')->nullable();
            $table->boolean('addon_cleaning')->default(false);
            $table->boolean('addon_repasta')->default(false);
            $table->enum('delivery_method', ['Diantar Sendiri', 'Ambil oleh Teknisi'])->default('Diantar Sendiri');
            $table->text('pickup_address')->nullable();
            $table->enum('order_status', [
                'Booking', 'Di Workshop', 'Approval Biaya', 'Paid', 'Reparasi', 'Selesai', 'Diserahkan', 'Closed'
            ])->default('Booking');
            $table->boolean('unit_handed_over')->default(false);
            $table->timestamp('handed_over_at')->nullable();
            $table->timestamp('sla_auto_close_at')->nullable();
            $table->timestamps();
        });

        Schema::create('diagnostics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->unique()->constrained('service_orders')->cascadeOnDelete();
            $table->text('physical_damage_analysis')->nullable();
            $table->text('action_plan')->nullable();
            $table->decimal('labor_cost', 12, 2)->default(0);
            $table->decimal('parts_cost', 12, 2)->default(0);
            $table->decimal('addon_cleaning_cost', 12, 2)->default(0);
            $table->decimal('addon_repasta_cost', 12, 2)->default(0);
            $table->decimal('net_total_cost', 12, 2)->default(0);
            $table->string('evidence_running_test_photo')->nullable();
            $table->string('evidence_replaced_part_photo')->nullable();
            $table->boolean('is_approved_by_user')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained('service_orders')->cascadeOnDelete();
            $table->foreignId('sparepart_id')->nullable()->constrained('spareparts')->nullOnDelete();
            $table->string('item_name');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->unique()->constrained('service_orders')->cascadeOnDelete();
            $table->string('invoice_no', 32)->unique();
            $table->decimal('amount_total', 12, 2);
            $table->enum('payment_method', ['QRIS Dinamis'])->default('QRIS Dinamis');
            $table->enum('payment_status', ['Unpaid', 'Paid', 'Expired'])->default('Unpaid');
            $table->string('qris_transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->unique()->constrained('invoices')->cascadeOnDelete();
            $table->string('warranty_code', 32)->unique();
            $table->unsignedSmallInteger('warranty_duration_days')->default(90);
            $table->date('starts_at');
            $table->date('expires_at');
            $table->enum('warranty_status', ['Active', 'Claimed', 'Expired'])->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranties');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('diagnostics');
        Schema::dropIfExists('service_orders');
        Schema::dropIfExists('spareparts');
    }
};
```

---

## 5. SCHEDULER LARAVEL (SLA 1X24 JAM AUTO-CLOSE)

Tambahkan di `routes/console.php` (Laravel 11+):

```php
use App\Models\ServiceOrder;
use App\Models\Warranty;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Carbon;

Schedule::call(function () {
    // Ambil order yang sudah diserahkan, SLA lewat 24 jam, status belum Closed
    $ordersToClose = ServiceOrder::query()
        ->where('unit_handed_over', true)
        ->where('order_status', '!=', 'Closed')
        ->whereNotNull('sla_auto_close_at')
        ->where('sla_auto_close_at', '<=', Carbon::now())
        ->with('invoice.warranty')
        ->get();

    foreach ($ordersToClose as $order) {
        $order->update(['order_status' => 'Closed']);

        // Penerbitan e-Warranty otomatis jika belum terbit
        if ($order->invoice && !$order->invoice->warranty) {
            Warranty::create([
                'invoice_id'             => $order->invoice->id,
                'warranty_code'          => 'WAR-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6)),
                'warranty_duration_days' => 90,
                'starts_at'              => Carbon::today(),
                'expires_at'             => Carbon::today()->addDays(90),
                'warranty_status'        => 'Active',
            ]);
        }
    }
})->hourly();
```

---

## 6. LOGIKA CONTROLLER KUNCI

### Saat Unit Diserahkan ke Konsumen (`handedOver`)
```php
public function handOverUnit(ServiceOrder $order)
{
    $now = now();
    $order->update([
        'order_status'      => 'Diserahkan',
        'unit_handed_over'  => true,
        'handed_over_at'    => $now,
        'sla_auto_close_at' => $now->copy()->addHours(24),
    ]);

    return back()->with('success', 'Unit diserahkan. SLA timer 24 jam aktif.');
}
```

### Format Cetak Invoice A4 (`resources/views/invoices/print.blade.php`)
```css
@media print {
    @page { size: A4 portrait; margin: 15mm; }
    body { background: white; color: black; }
    .no-print { display: none !important; }
}
```
