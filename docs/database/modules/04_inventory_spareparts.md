# Modul 04: Inventory & Spareparts (Katalog Suku Cadang)

Modul logistik yang mencatat master data suku cadang komputer, kategori komponen, kuantitas stok workshop, harga modal (*Cost of Goods Sold / COGS*), harga jual konsumen, serta pencatatan pemakaian part pada tiket perbaikan.

---

## 1. Tabel `categories`
Klasifikasi komponen hardware dan jasa perbaikan.

| Kolom | Tipe Data | Nullable | Default | Keterangan / Deskripsi |
| :--- | :--- | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | *Auto Increment* | Primary Key |
| `name` | `VARCHAR(100)` | NO | - | Nama kategori (cth: *Cooling Fan, Memory RAM, SSD, Motherboard IC*) |
| `slug` | `VARCHAR(120)` | NO | - | URL slug unik kategori |
| `description` | `TEXT` | YES | `NULL` | Penjelasan ruang lingkup kategori |
| `created_at` | `TIMESTAMP` | YES | `NULL` | Timestamp Laravel |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | Timestamp Laravel |

---

## 2. Tabel `spareparts`
Master katalog stok suku cadang fisik di gudang workshop.

| Kolom | Tipe Data | Nullable | Default | Keterangan / Deskripsi |
| :--- | :--- | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | *Auto Increment* | Primary Key |
| `category_id` | `BIGINT UNSIGNED` | YES | `NULL` | Foreign Key ke `categories.id` |
| `part_code` | `VARCHAR(50)` | NO | - | SKU unik komponen (cth: `FAN-ASUS-G15`, `RAM-D4-8GB`) |
| `part_name` | `VARCHAR(255)` | NO | - | Nama lengkap komponen sparepart |
| `category` | `ENUM` | NO | - | Enum kompatibilitas lama: `'Fan'`, `'RAM'`, `'SSD'`, `'Screen'`, `'PSU'`, `'Cable'` |
| `stock_qty` | `INT UNSIGNED` | NO | `0` | Kuantitas fisik stok siap pakai di workshop |
| `unit_price` | `DECIMAL(12,2)`| NO | - | Harga jual resmi kepada pelanggan (Rp) |
| `cost_price` | `DECIMAL(12,2)`| NO | `0.00` | Harga modal pembelian / HPP (untuk kalkulasi margin BI) |
| `created_at` | `TIMESTAMP` | YES | `NULL` | Timestamp pendaftaran item part |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | Waktu update stok atau harga |

---

## 3. Tabel `order_items`
Rincian item suku cadang atau modul perbaikan yang dialokasikan ke tiket servis tertentu.

| Kolom | Tipe Data | Nullable | Default | Keterangan / Deskripsi |
| :--- | :--- | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | *Auto Increment* | Primary Key |
| `service_order_id`| `BIGINT UNSIGNED` | NO | - | Foreign Key ke `service_orders.id` |
| `sparepart_id` | `BIGINT UNSIGNED` | YES | `NULL` | Foreign Key ke `spareparts.id` (Dapat `NULL` jika part custom) |
| `item_name` | `VARCHAR(255)` | NO | - | Deskripsi nama item suku cadang / jasa |
| `quantity` | `INT UNSIGNED` | NO | `1` | Jumlah kuantitas part yang dipasang |
| `cost_price` | `DECIMAL(12,2)`| NO | `0.00` | Snapshot harga modal satuan saat dipasang |
| `unit_price` | `DECIMAL(12,2)`| NO | - | Snapshot harga jual satuan kepada pelanggan |
| `subtotal` | `DECIMAL(12,2)`| NO | - | Total harga (`quantity` * `unit_price`) |
| `margin_total` | `DECIMAL(12,2)`| NO | `0.00` | Total laba kotor item (`subtotal` - (`quantity` * `cost_price`)) |
| `created_at` | `TIMESTAMP` | YES | `NULL` | Waktu alokasi sparepart ke unit |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | Timestamp Laravel |

---

## Relasi Antar Tabel
* `categories.id` &rarr; `spareparts.category_id` (1 : N)
* `spareparts.id` &rarr; `order_items.sparepart_id` (1 : N)
* `service_orders.id` &rarr; `order_items.service_order_id` (1 : N)
