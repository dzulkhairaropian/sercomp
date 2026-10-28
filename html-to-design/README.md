# SerComp - HTML to Design (Figma Import Asset Pack)

Folder ini berisi **11 berkas HTML mandiri (self-contained)** yang telah dikompilasi secara utuh dari aplikasi SerComp. Seluruh styling CSS (Bootstrap 5.3, Bootstrap Icons, Font Plus Jakarta Sans, dan Design System SerComp) sudah tertanam di dalamnya, siap diimpor langsung menjadi artboard/frame di **Figma** menggunakan plugin **html.to.design**.

---

## 📁 Daftar Berkas Antarmuka (Screens)

| No | Nama Berkas | Deskripsi Halaman | Peran Pengguna |
|---|---|---|---|
| **01** | `01_landing_page.html` | Halaman Depan / Landing Page, Katalog Biaya, Hero Banner, & Widget Lacak | Publik / Konsumen |
| **02** | `02_booking_form.html` | Formulir Reservasi Servis 4 Langkah & Live Summary Estimasi Biaya | Konsumen |
| **03** | `03_tracking_portal_step1_waiting.html` | Portal Pelanggan - Tahap 1: Menunggu Unit Tiba & Cek Fisik Workshop | Konsumen |
| **04** | `04_tracking_portal_step3_approval.html` | Portal Pelanggan - Tahap 3: Lembar Persetujuan Biaya Terkunci & Modal QRIS | Konsumen |
| **05** | `05_tracking_portal_step6_handover_sla.html` | Portal Pelanggan - Tahap 6: Unit Diserahkan, Timer SLA 24h, & Bukti Part | Konsumen |
| **06** | `06_technician_login.html` | Halaman Login Internal Khusus Teknisi & Admin Workshop | Teknisi / Admin |
| **07** | `07_technician_dashboard_kpi.html` | Dashboard KPI Workshop, Ringkasan Omzet, Kepatuhan SLA, & Unit Meja Kerja | Teknisi / Admin |
| **08** | `08_technician_service_orders.html` | Daftar Seluruh Antrean Servis Unit (Laptop & PC) Lengkap Filter Kategori | Teknisi / Admin |
| **09** | `09_technician_workbench.html` | Meja Kerja Diagnosa Hardware, Input Biaya Jasa/Part, & Bukti Running Test | Teknisi |
| **10** | `10_technician_spareparts.html` | Katalog & Manajemen Inventori Stok Suku Cadang (16 SKU Item) | Admin / Teknisi |
| **11** | `11_invoice_receipt_a4.html` | Faktur Invoice Resmi Format Standar Cetak A4 Berstempel Lunas Digital | Konsumen / Admin |

---

## 🚀 Cara Impor ke Figma Menggunakan Plugin "html.to.design"

### Metode 1: Impor Langsung File HTML (Paling Cepat)
1. Buka aplikasi **Figma** atau buka kanvas baru di [figma.com](https://figma.com).
2. Di menu bar atau panel plugin, cari dan jalankan plugin **`html.to.design`**.
3. Di dalam jendela plugin, pilih tab **Import from file** (atau ikon folder/upload).
4. Seret (*drag and drop*) salah satu berkas HTML dari folder ini (contoh: `01_landing_page.html`) ke dalam kotak upload plugin.
5. Pilih ukuran viewport:
   * **Desktop:** `1440px` (Direkomendasikan)
   * **Mobile:** `390px` (Jika ingin melihat responsif mobile)
6. Klik tombol **Import**. Seluruh komponen, font, tombol, ikon SVG, dan kartu akan otomatis terkonversi menjadi frame dan layer vektor Figma yang dapat diedit bebas (*Auto Layout ready*).

---

### Metode 2: Menggunakan Ekstensi Browser "html.to.design"
Jika Anda menjalankan server lokal Laravel (`php artisan serve` di `http://127.0.0.1:8000`):
1. Pasang ekstensi Chrome/Edge **html.to.design**.
2. Buka halaman di browser (misalnya `http://127.0.0.1:8000` atau `http://127.0.0.1:8000/booking`).
3. Klik ikon ekstensi **html.to.design** di toolbar browser.
4. Klik **Capture full page**, lalu simpan file `.h2d` atau kirim langsung ke Figma.

---

## 🎨 Spesifikasi Desain Terpadu (Design Tokens)
- **Primary Color:** Sapphire Tech Blue (`#0284c7`)
- **Navy Accent:** Deep Navy (`#0d233a`) & Midnight Navy (`#071524`)
- **Latar Belakang:** Slate 50 (`#f8fafc`)
- **Tipografi:** Plus Jakarta Sans (Variable 400, 500, 600, 700, 800)
- **Standar Aksesibilitas:** WCAG AA (Kontras teks dan tombol minimum 4.5:1)
- **Ikonografi:** Bootstrap Icons SVG Terstandarisasi (bebas emoji mentah)
