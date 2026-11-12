# Modul 06: Warranty & Aftersales (Sertifikat Garansi Digital)

Modul purnajual yang mengelola penerbitan sertifikat digital e-Warranty 90 hari, pelacakan masa berlaku otomatis, dan klaim garansi tanpa kertas (*paperless claim*).

---

## 1. Tabel `warranties`
Menyimpan sertifikat garansi purnajual resmi yang aktif otomatis setelah unit diterima pelanggan dan lolos evaluasi SLA 24 jam.

| Kolom | Tipe Data | Nullable | Default | Keterangan / Deskripsi |
| :--- | :--- | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | *Auto Increment* | Primary Key |
| `invoice_id` | `BIGINT UNSIGNED` | NO | - | Foreign Key ke `invoices.id` (1 invoice lunas = 1 sertifikat garansi) |
| `warranty_code` | `VARCHAR(32)` | NO | - | Kode unik garansi digital (cth: `#WAR-2026-9904`) |
| `warranty_duration_days` | `SMALLINT UNSIGNED` | NO | `90` | Durasi standar perlindungan purnajual (90 hari) |
| `starts_at` | `DATE` | NO | - | Tanggal mulai berlakunya garansi |
| `expires_at` | `DATE` | NO | - | Tanggal jatuh tempo akhir masa garansi |
| `warranty_status` | `ENUM` | NO | `'Active'` | Status: `'Active'`, `'Claimed'`, `'Expired'` |
| `created_at` | `TIMESTAMP` | YES | `NULL` | Waktu penerbitan sertifikat garansi |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | Waktu update status garansi |

---

## Mekanisme Purnajual SerComp
1. **Paperless Claim:** Pelanggan tidak perlu menyimpan nota fisik atau kartu garansi kertas. Klaim dilakukan dengan memasukkan nomor tiket pada portal pelacakan.
2. **Re-entry Tracking:** Jika terjadi klaim garansi pada unit yang sama sebelum `expires_at`, tiket baru yang dibuat akan mengisi field `parent_service_order_id` yang merujuk pada tiket awal untuk melacak efektivitas teknisi.
3. **Cakupan Garansi:** Mencakup penggantian suku cadang baru yang rusak serta bebas biaya jasa teknisi untuk penanganan kerusakan berulang.
