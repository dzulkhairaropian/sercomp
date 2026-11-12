# Modul 01: User & Authentication (Autentikasi & RBAC)

Modul ini mengelola data pengguna, kredensial, segmentasi tipe pelanggan (*Personal* vs *Company*), serta pembagian hak akses (*Role-Based Access Control*).

---

## 1. Tabel `users`
Menyimpan seluruh aktor sistem: Administrator Workshop, Kasir POS, Teknisi Hardware, dan Pelanggan.

| Kolom | Tipe Data | Nullable | Default | Keterangan / Deskripsi |
| :--- | :--- | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | *Auto Increment* | Primary Key |
| `name` | `VARCHAR(255)` | NO | - | Nama lengkap staf atau pelanggan |
| `email` | `VARCHAR(255)` | NO | - | Email unik untuk autentikasi sistem |
| `email_verified_at`| `TIMESTAMP` | YES | `NULL` | Waktu verifikasi email |
| `password` | `VARCHAR(255)` | NO | - | Hash bcrypt kata sandi |
| `role` | `ENUM` | NO | `'customer'` | Nilai: `'admin'`, `'technician'`, `'customer'` |
| `phone` | `VARCHAR(30)` | YES | `NULL` | Nomor kontak WhatsApp utama |
| `customer_type` | `ENUM` | NO | `'Personal'` | Nilai: `'Personal'`, `'Company'` |
| `company_name` | `VARCHAR(150)`| YES | `NULL` | Nama perusahaan / instansi (jika akun B2B/Company) |
| `tech_id` | `VARCHAR(30)` | YES | `NULL` | Kode identitas staf teknisi (cth: `TEK-2026-01`) |
| `remember_token` | `VARCHAR(100)`| YES | `NULL` | Token "Remember Me" Laravel |
| `created_at` | `TIMESTAMP` | YES | `NULL` | Waktu pembuatan akun |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | Waktu perubahan terakhir akun |

---

## 2. Tabel `password_reset_tokens`
Menyimpan token verifikasi pemulihan kata sandi.

| Kolom | Tipe Data | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- |
| `email` | `VARCHAR(255)` | NO | - | Primary Key (Email terdaftar) |
| `token` | `VARCHAR(255)` | NO | - | Hash token reset password |
| `created_at` | `TIMESTAMP` | YES | `NULL` | Waktu pengajuan reset |

---

## 3. Tabel `sessions`
Penyimpanan sesi login pengguna (*database session driver*).

| Kolom | Tipe Data | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- |
| `id` | `VARCHAR(255)` | NO | - | Primary Key (Session ID unik) |
| `user_id` | `BIGINT UNSIGNED` | YES | `NULL` | Foreign Key ke `users.id` |
| `ip_address` | `VARCHAR(45)` | YES | `NULL` | Alamat IP pengguna |
| `user_agent` | `TEXT` | YES | `NULL` | Informasi browser / device agent |
| `payload` | `LONGTEXT` | NO | - | Data session terenkripsi |
| `last_activity` | `INT` | NO | - | Unix timestamp aktivitas terakhir |

---

## Relasi Antar Tabel
* `users.id` &rarr; `service_orders.customer_id` (1 : N)
* `users.id` &rarr; `service_orders.technician_id` (1 : N)
* `users.id` &rarr; `diagnostics.estimated_by` (1 : N)
* `users.id` &rarr; `ticket_status_histories.changed_by` (1 : N)
* `users.id` &rarr; `payments.received_by` (1 : N)
