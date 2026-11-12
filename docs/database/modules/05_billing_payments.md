# Modul 05: Billing, Payments & Invoices (Keuangan & Kasir POS)

Modul finansial yang mengatur penerbitan faktur tagihan resmi, pembayaran multi-tahap (Uang Muka / DP vs Pelunasan), integrasi QRIS dinamis, kasir tunai POS, serta tracking piutang usaha (*Accounts Receivable*).

---

## 1. Tabel `invoices`
Dokumen faktur penagihan resmi yang terbit pasca persetujuan biaya pelanggan.

| Kolom | Tipe Data | Nullable | Default | Keterangan / Deskripsi |
| :--- | :--- | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | *Auto Increment* | Primary Key |
| `service_order_id` | `BIGINT UNSIGNED` | NO | - | Foreign Key ke `service_orders.id` (1 tiket = 1 faktur) |
| `invoice_no` | `VARCHAR(32)` | NO | - | Nomor faktur resmi (cth: `INV-SC-2026-8812`) |
| `amount_total` | `DECIMAL(12,2)`| NO | - | Total nilai tagihan bersih (Rp) |
| `amount_paid` | `DECIMAL(12,2)`| NO | `0.00` | Akumulasi dana yang sudah disetorkan |
| `amount_due` | `DECIMAL(12,2)`| NO | `0.00` | Sisa saldo piutang / belum lunas (*Outstanding*) |
| `payment_method` | `VARCHAR(50)` | NO | `'QRIS Dinamis'` | Metode bayar (*QRIS Dinamis, Tunai Kasir POS, Transfer Bank*) |
| `payment_status` | `VARCHAR(50)` | NO | `'Unpaid'` | Status: `'Unpaid'`, `'Partially Paid (DP)'`, `'Paid'`, `'Expired'`, `'Cancelled'` |
| `due_date` | `DATE` | YES | `NULL` | Tanggal jatuh tempo pelunasan |
| `qris_transaction_id`| `VARCHAR(255)`| YES | `NULL` | ID transaksi settlement gateway QRIS |
| `paid_at` | `TIMESTAMP` | YES | `NULL` | Waktu saat tagihan lunas total |
| `created_at` | `TIMESTAMP` | YES | `NULL` | Waktu invoice diterbitkan |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | Waktu update status pembayaran |

---

## 2. Tabel `payments`
Pencatatan riwayat transaksi uang masuk (mendukung multi-pembayaran per invoice).

| Kolom | Tipe Data | Nullable | Default | Keterangan / Deskripsi |
| :--- | :--- | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | *Auto Increment* | Primary Key |
| `invoice_id` | `BIGINT UNSIGNED` | NO | - | Foreign Key ke `invoices.id` |
| `payment_reference`| `VARCHAR(64)` | NO | - | Nomor referensi/RRN transaksi bank atau QRIS |
| `payment_type` | `ENUM` | NO | `'Full Payment'` | `'Down Payment (DP)'`, `'Full Payment'`, `'Final Settlement (Pelunasan)'` |
| `amount` | `DECIMAL(12,2)`| NO | - | Nominal dana yang dibayarkan pada sesi ini |
| `payment_method` | `VARCHAR(50)` | NO | `'QRIS Dinamis'` | Channel pembayaran yang digunakan |
| `status` | `ENUM` | NO | `'Success'` | Status sesi: `'Pending'`, `'Success'`, `'Failed'` |
| `paid_at` | `TIMESTAMP` | NO | `CURRENT_TIMESTAMP` | Waktu transaksi divalidasi |
| `received_by` | `BIGINT UNSIGNED` | YES | `NULL` | Foreign Key ke `users.id` (Kasir penerima jika tunai) |
| `notes` | `TEXT` | YES | `NULL` | Keterangan pembayaran (cth: *DP 50% di muka*) |
| `created_at` | `TIMESTAMP` | YES | `NULL` | Timestamp transaksi |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | Timestamp transaksi |

---

## Relasi Antar Tabel
* `invoices.id` &rarr; `payments.invoice_id` (1 : N)
* `invoices.id` &rarr; `warranties.invoice_id` (1 : 1)
* `users.id` &rarr; `payments.received_by` (1 : N)
