# Modul 07: Business Intelligence (BI Datamarts & Views)

Modul analitik eksekutif yang memanfaatkan MySQL/MariaDB database views untuk mengekstrak metrik kinerja bengkel, laporan laba rugi suku cadang, penuaan piutang (*Aging Receivables*), dan segmentasi RFM pelanggan CRM.

---

## 1. Database View: `v_bi_executive_summary`
Ringkasan metrik harian/bulanan untuk Dashboard Eksekutif Manajemen Workshop.

* **Fungsi:** Mengagregasi total tiket masuk, unit selesai, omzet kotor, total penerimaan kas, dan sisa piutang berjalan.
* **Komponen Kolom Output:**
  - `total_tickets`: Akumulasi tiket seluruh cabang.
  - `completed_tickets`: Unit yang berhasil diperbaiki dan lulus uji.
  - `total_revenue`: Total omzet jasa dan sparepart.
  - `total_collected`: Total dana yang sudah diterima via QRIS dan Kasir Tunai.
  - `total_receivables`: Piutang pelanggan yang belum dilunasi.
  - `active_warranties_count`: Jumlah unit dalam masa garansi 90 hari aktif.

---

## 2. Database View: `v_bi_technician_performance`
Analisis produktivitas dan kualitas perbaikan per teknisi hardware.

* **Fungsi:** Menilai performa teknisi, rata-rata durasi pengerjaan (*Lead Time*), total omzet jasa yang dihasilkan, serta rasio klaim ulang garansi (*Rework Rate*).
* **Komponen Kolom Output:**
  - `technician_id`: ID teknisi.
  - `technician_name`: Nama staf teknisi.
  - `total_assigned`: Jumlah unit yang ditangani.
  - `total_completed`: Jumlah unit yang selesai diperbaiki.
  - `avg_turnaround_hours`: Rata-rata jam penyelesaian unit.
  - `rework_count`: Jumlah unit yang masuk kembali karena klaim garansi.
  - `customer_rating_avg`: Rata-rata penilaian bintang kepuasan pelanggan (1 - 5).

---

## 3. Database View: `v_bi_sparepart_profitability`
Analisis margin profitabilitas penjualan suku cadang bengkel.

* **Fungsi:** Membandingkan nilai omzet penjualan suku cadang terhadap harga modal (HPP / COGS) untuk memetakan sparepart paling menguntungkan.
* **Komponen Kolom Output:**
  - `part_code`: Kode SKU sparepart.
  - `part_name`: Nama komponen.
  - `total_qty_sold`: Akumulasi unit terpasang.
  - `total_sales_amount`: Total nilai penjualan kotor.
  - `total_cost_amount`: Total modal pengadaan sparepart.
  - `gross_profit`: Total laba kotor suku cadang (`total_sales_amount` - `total_cost_amount`).
  - `margin_percentage`: Persentase margin keuntungan.

---

## 4. Database View: `v_bi_aging_receivables`
Analisis penuaan piutang pelanggan (khusus invoice dengan skema DP 50%).

* **Fungsi:** Mengelompokkan piutang belum tertagih berdasarkan bucket usia penagihan (*Current, 1-30 hari, 31-60 hari, >60 hari*).
* **Komponen Kolom Output:**
  - `invoice_no`: Nomor faktur.
  - `customer_name`: Nama pelanggan atau perusahaan (B2B).
  - `amount_total`: Nilai tagihan awal.
  - `amount_paid`: Pembayaran DP yang sudah disetor.
  - `outstanding_amount`: Sisa tagihan tertunggak.
  - `aging_bucket`: Klasifikasi hari penunggakan.
