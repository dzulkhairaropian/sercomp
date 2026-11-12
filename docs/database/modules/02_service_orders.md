# Modul 02: Service Orders & Workflow Lifecycle (State Machine Servis)

Modul inti sistem yang mencatat reservasi unit, alur kerja operasional (*7 stages state machine*), audit log riwayat status, bukti serah terima kurir, dan negosiasi biaya.

---

## 1. Tabel `service_orders`
Tabel transaksi utama untuk setiap unit laptop atau PC yang masuk ke bengkel.

| Kolom | Tipe Data | Nullable | Default | Keterangan / Deskripsi |
| :--- | :--- | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | *Auto Increment* | Primary Key |
| `parent_service_order_id` | `BIGINT UNSIGNED` | YES | `NULL` | Foreign Key ke `service_orders.id` (Ref klaim garansi/re-entry) |
| `customer_id` | `BIGINT UNSIGNED` | YES | `NULL` | Foreign Key ke `users.id` (Pemilik unit) |
| `technician_id` | `BIGINT UNSIGNED` | YES | `NULL` | Foreign Key ke `users.id` (Teknisi yang ditugaskan) |
| `ticket_no` | `VARCHAR(32)` | NO | - | Nomor tiket servis unik (cth: `SC-2026-9042`) |
| `security_pin` | `VARCHAR(10)` | NO | `'1234'` | Kode PIN keamanan akses pelacakan portal |
| `customer_name` | `VARCHAR(150)`| YES | `NULL` | Snapshot nama pelanggan saat booking |
| `customer_phone` | `VARCHAR(30)` | YES | `NULL` | Nomor WhatsApp pelanggan |
| `customer_type` | `ENUM` | NO | `'Personal'` | `'Personal'`, `'Company'` |
| `company_name` | `VARCHAR(150)`| YES | `NULL` | Nama perusahaan pelanggan (jika B2B) |
| `device_type` | `ENUM` | NO | - | `'Laptop'`, `'PC Desktop'` |
| `device_brand` | `VARCHAR(80)` | NO | - | Merek perangkat (Asus, Lenovo, Acer, dsb.) |
| `device_model` | `VARCHAR(120)`| NO | - | Seri/tipe spesifik (cth: `ROG Strix G15`) |
| `serial_number` | `VARCHAR(80)` | YES | `NULL` | Nomor seri fisik perangkat (S/N) |
| `issue_chronology` | `TEXT` | NO | - | Uraian keluhan awal dari konsumen |
| `issue_photo_url` | `VARCHAR(255)`| YES | `NULL` | Path foto fisik awal saat booking |
| `addon_cleaning` | `TINYINT(1)` | NO | `0` | Add-on pembersihan debu heatsink |
| `addon_repasta` | `TINYINT(1)` | NO | `0` | Add-on repasta thermal Arctic MX-4 |
| `delivery_method` | `ENUM` | NO | `'Diantar Sendiri'` | `'Diantar Sendiri'`, `'Ambil oleh Teknisi'` |
| `pickup_address` | `TEXT` | YES | `NULL` | Alamat penjemputan jika menggunakan kurir |
| `branch_code` | `VARCHAR(30)` | NO | `'JKT-PST-01'` | Kode cabang workshop |
| `lead_source` | `VARCHAR(50)` | NO | `'Website Direct'`| Saluran asal reservasi (*Organic, Ads, Walk-in*) |
| `order_status` | `ENUM` | NO | `'Booking'` | Status siklus: `'Booking'`, `'Di Workshop'`, `'Approval Biaya'`, `'Paid'`, `'Reparasi'`, `'Selesai'`, `'Diserahkan'`, `'Closed'`, `'Dibatalkan'` |
| `crm_segment_snapshot` | `VARCHAR(50)`| YES | `NULL` | Snapshot segmentasi: `'Leads'`, `'Aktif'`, `'Setia'` |
| `negotiation_status`| `ENUM` | NO | `'None'` | Status tawar budget: `'None'`, `'Requested'`, `'Approved'`, `'Rejected'` |
| `negotiation_notes` | `TEXT` | YES | `NULL` | Catatan/alasan negosiasi dari pelanggan |
| `negotiation_budget`| `DECIMAL(12,2)`| YES | `NULL` | Budget penawaran yang diajukan pelanggan |
| `unit_handed_over` | `TINYINT(1)` | NO | `0` | Indikator unit fisik sudah diserahkan |
| `handed_over_at` | `TIMESTAMP` | YES | `NULL` | Waktu fisik unit diserahkan |
| `sla_auto_close_at`| `TIMESTAMP` | YES | `NULL` | Batas akhir toleransi uji 24 jam (*auto-close*) |
| `courier_name` | `VARCHAR(100)`| YES | `NULL` | Nama kurir yang menyerahkan unit |
| `receiver_name` | `VARCHAR(100)`| YES | `NULL` | Nama pihak penerima unit |
| `signature_data` | `LONGTEXT` | YES | `NULL` | Base64 gambar tanda tangan digital serah terima |
| `rating_score` | `TINYINT UNSIGNED`| YES | `NULL` | Skor kepuasan pelanggan (1 - 5) |
| `feedback_comments`| `TEXT` | YES | `NULL` | Ulasan tertulis pelanggan |
| `created_at` | `TIMESTAMP` | YES | `NULL` | Waktu booking tiket dibuat |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | Waktu pembaruan status |

---

## 2. Tabel `ticket_status_histories`
Audit trail lengkap untuk mencatat setiap perpindahan status tiket beserta aktor pengubahnya.

| Kolom | Tipe Data | Nullable | Default | Keterangan / Deskripsi |
| :--- | :--- | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | *Auto Increment* | Primary Key |
| `service_order_id` | `BIGINT UNSIGNED` | NO | - | Foreign Key ke `service_orders.id` |
| `status_from` | `VARCHAR(50)` | YES | `NULL` | Status sebelum transisi |
| `status_to` | `VARCHAR(50)` | NO | - | Status tujuan transisi |
| `changed_by` | `BIGINT UNSIGNED` | YES | `NULL` | Foreign Key ke `users.id` (Aktor pengubah status) |
| `notes` | `TEXT` | YES | `NULL` | Catatan alasan perubahan status |
| `changed_at` | `TIMESTAMP` | NO | `CURRENT_TIMESTAMP` | Waktu eksekusi transisi status |
| `created_at` | `TIMESTAMP` | YES | `NULL` | Timestamp Laravel |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | Timestamp Laravel |

---

## Relasi Antar Tabel
* `service_orders.id` &rarr; `diagnostics.service_order_id` (1 : 1)
* `service_orders.id` &rarr; `order_items.service_order_id` (1 : N)
* `service_orders.id` &rarr; `invoices.service_order_id` (1 : 1)
* `service_orders.id` &rarr; `ticket_status_histories.service_order_id` (1 : N)
