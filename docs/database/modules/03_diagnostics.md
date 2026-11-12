# Modul 03: Diagnostics & Physical Inspection (Diagnosa Meja Kerja)

Modul teknis yang menyimpan analisa kerusakan komponen hardware, penentuan estimasi harga, bukti foto purnajual (*Dual-Evidence*), dan persetujuan pelanggan (*Zero Hidden Cost*).

---

## 1. Tabel `diagnostics`
Menyimpan lembar kerja diagnosa teknisi hardware dan rincian estimasi biaya di muka.

| Kolom | Tipe Data | Nullable | Default | Keterangan / Deskripsi |
| :--- | :--- | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | *Auto Increment* | Primary Key |
| `service_order_id` | `BIGINT UNSIGNED` | NO | - | Foreign Key ke `service_orders.id` (Unik, 1 tiket = 1 diagnosa) |
| `estimated_by` | `BIGINT UNSIGNED` | YES | `NULL` | Foreign Key ke `users.id` (Admin/Teknisi yang menentukan harga) |
| `physical_damage_analysis`| `TEXT` | YES | `NULL` | Catatan analisa kerusakan fisik meja kerja |
| `symptom_category` | `VARCHAR(80)` | YES | `NULL` | Kategori gejala (*Power Failure, Overheat, Display, Storage, dsb.*) |
| `fault_category` | `VARCHAR(80)` | YES | `NULL` | Kategori akar masalah (*IC Power, Cooling, Panel, Fluid Corrosion*) |
| `action_plan` | `TEXT` | YES | `NULL` | Rencana langkah perbaikan hardware yang akan dieksekusi |
| `admin_notes` | `TEXT` | YES | `NULL` | Catatan internal pengelola workshop |
| `labor_cost` | `DECIMAL(12,2)`| NO | `0.00` | Biaya jasa profesional teknisi |
| `parts_cost` | `DECIMAL(12,2)`| NO | `0.00` | Akumulasi biaya suku cadang baru |
| `addon_cleaning_cost` | `DECIMAL(12,2)`| NO | `0.00` | Biaya add-on deep cleaning heatsink |
| `addon_repasta_cost` | `DECIMAL(12,2)`| NO | `0.00` | Biaya add-on repasta Arctic MX-4 |
| `net_total_cost` | `DECIMAL(12,2)`| NO | `0.00` | Total biaya bersih yang wajib disetujui di muka |
| `evidence_running_test_photo` | `VARCHAR(255)`| YES | `NULL` | **Dual-Evidence 1:** Path foto unit menyala normal & suhu stabil |
| `evidence_replaced_part_photo`| `VARCHAR(255)`| YES | `NULL` | **Dual-Evidence 2:** Path foto suku cadang lama yang telah diganti |
| `is_approved_by_user` | `TINYINT(1)` | NO | `0` | Status persetujuan resmi konsumen |
| `approved_at` | `TIMESTAMP` | YES | `NULL` | Waktu persetujuan biaya oleh konsumen |
| `is_price_locked` | `TINYINT(1)` | NO | `0` | Indikator nominal estimasi terkunci paten |
| `created_at` | `TIMESTAMP` | YES | `NULL` | Waktu penerbitan diagnosa awal |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | Waktu revisi diagnosa / estimasi |

---

## Karakteristik Transparansi (SOP)
1. **Zero Hidden Cost:** Nominal `net_total_cost` mengunci total biaya sebelum unit dibongkar.
2. **Dual-Evidence Enforcement:** Kolom `evidence_running_test_photo` dan `evidence_replaced_part_photo` wajib terisi sebelum status unit dapat dipindahkan ke `Selesai`.
3. **Pemberitahuan Persetujuan:** Status persetujuan dicatat melalui `is_approved_by_user` dan `approved_at`.
