<?php

namespace Database\Seeders;

use App\Models\Diagnostic;
use App\Models\Invoice;
use App\Models\OrderItem;
use App\Models\ServiceOrder;
use App\Models\Sparepart;
use App\Models\User;
use App\Models\Warranty;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SercompDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Workshop & Teknisi
        $technicians = [
            [
                'email' => 'budi@sercomp.id',
                'name' => 'Budi Santoso',
                'password' => Hash::make('teknisi123'),
                'role' => 'technician',
                'tech_code' => 'TEK-2026-01',
                'phone' => '081298765432',
                'specialization' => 'Hardware Specialist Level 3',
                'is_active' => true,
            ],
            [
                'email' => 'agus@sercomp.id',
                'name' => 'Agus Prasetyo',
                'password' => Hash::make('teknisi123'),
                'role' => 'technician',
                'tech_code' => 'TEK-2026-02',
                'phone' => '081377889900',
                'specialization' => 'Micro-Soldering & Power IC',
                'is_active' => true,
            ],
            [
                'email' => 'dodi@sercomp.id',
                'name' => 'Dodi Firmansyah',
                'password' => Hash::make('teknisi123'),
                'role' => 'technician',
                'tech_code' => 'TEK-2026-03',
                'phone' => '085711223344',
                'specialization' => 'Desktop PC & Custom Build',
                'is_active' => true,
            ],
            [
                'email' => 'admin@sercomp.id',
                'name' => 'Admin SerComp',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'tech_code' => 'ADM-2026-01',
                'phone' => '081122334455',
                'specialization' => 'Workshop Manager',
                'is_active' => true,
            ],
        ];

        foreach ($technicians as $tech) {
            User::updateOrCreate(['email' => $tech['email']], $tech);
        }

        $budi = User::where('email', 'budi@sercomp.id')->first();
        $agus = User::where('email', 'agus@sercomp.id')->first();
        $dodi = User::where('email', 'dodi@sercomp.id')->first();

        // Akun Pelanggan (Customers)
        $customersData = [
            ['name' => 'Hendra Wijaya', 'email' => 'hendra@gmail.com', 'phone' => '081234567890'],
            ['name' => 'Rina Kartika', 'email' => 'rina.kartika@yahoo.com', 'phone' => '081399881122'],
            ['name' => 'Dimas Pratama', 'email' => 'dimas.pratama@outlook.com', 'phone' => '085622334455'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti.nur@gmail.com', 'phone' => '081977665544'],
            ['name' => 'Bayu Wicaksono', 'email' => 'bayu.wicak@perusahaan.co.id', 'phone' => '081133445566'],
        ];

        $customers = [];
        foreach ($customersData as $cd) {
            $customers[$cd['email']] = User::updateOrCreate(
                ['phone' => $cd['phone']],
                [
                    'name' => $cd['name'],
                    'email' => $cd['email'],
                    'password' => Hash::make('password123'),
                    'role' => 'customer',
                    'is_active' => true,
                ]
            );
        }

        // 2. Master Suku Cadang (Spareparts 6 Kategori)
        $sparepartsData = [
            [
                'part_code' => 'FAN-ASUS-G15',
                'part_name' => 'Modul Fan Cooler Original Asus ROG Strix G15',
                'category' => 'Fan',
                'stock_qty' => 6,
                'unit_price' => 220000,
            ],
            [
                'part_code' => 'RAM-DDR4-8GB',
                'part_name' => 'RAM DDR4 SODIMM 8GB Kingston 3200MHz',
                'category' => 'RAM',
                'stock_qty' => 12,
                'unit_price' => 320000,
            ],
            [
                'part_code' => 'SSD-NVME-512',
                'part_name' => 'SSD M.2 NVMe PCIe Gen 3 512GB Kingston NV1',
                'category' => 'SSD',
                'stock_qty' => 10,
                'unit_price' => 520000,
            ],
            [
                'part_code' => 'LCD-156-FHD',
                'part_name' => 'Panel Layar LCD 15.6 IPS FHD 144Hz Slim',
                'category' => 'Screen',
                'stock_qty' => 4,
                'unit_price' => 850000,
            ],
            [
                'part_code' => 'PSU-650W-80P',
                'part_name' => 'Power Supply Unit 650W 80+ Bronze Aerocool',
                'category' => 'PSU',
                'stock_qty' => 5,
                'unit_price' => 750000,
            ],
            [
                'part_code' => 'CAB-EDP-30PIN',
                'part_name' => 'Kabel Flexible LVDS/eDP 30-Pin Slim Screen',
                'category' => 'Cable',
                'stock_qty' => 8,
                'unit_price' => 110000,
            ],
        ];

        $parts = [];
        foreach ($sparepartsData as $sp) {
            $parts[$sp['part_code']] = Sparepart::updateOrCreate(['part_code' => $sp['part_code']], $sp);
        }

        // 3. Dataset Transaksional Servis Multi-Tahap (Bahan Analisis Business Intelligence II)
        $ordersDataset = [
            // Tiket 1: Aktif di Meja Kerja (Di Workshop)
            [
                'ticket_no' => 'SC-2026-9042',
                'customer' => $customers['hendra@gmail.com'],
                'technician' => $budi,
                'device_type' => 'Laptop',
                'device_brand' => 'Asus',
                'device_model' => 'ROG Strix G15 (G513)',
                'issue_chronology' => 'Laptop mati mendadak saat rendering 3D, tercium bau sangit dari ventilasi exhaust belakang.',
                'addon_cleaning' => true,
                'addon_repasta' => true,
                'delivery_method' => 'Diantar Sendiri',
                'order_status' => 'Di Workshop',
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subHours(6),
                'diagnostic' => [
                    'analysis' => 'Korsleting jalur VCore 19V pada kapasitor SMD dekat MOSFET power stage. Modul fan exhaust kiri macet total.',
                    'action' => 'Injeksi tegangan DC power supply, solder kapasitor SMD, ganti modul fan cooler, repasta thermal MX-4.',
                    'labor' => 250000,
                    'parts' => 220000,
                    'approved' => false,
                ],
                'part_used' => $parts['FAN-ASUS-G15'],
                'invoice_status' => 'Unpaid',
                'warranty' => false,
            ],
            // Tiket 2: Selesai & Ditutup (Closed) - e-Warranty Aktif 90 Hari
            [
                'ticket_no' => 'SC-2026-8812',
                'customer' => $customers['rina.kartika@yahoo.com'],
                'technician' => $agus,
                'device_type' => 'Laptop',
                'device_brand' => 'Lenovo',
                'device_model' => 'IdeaPad Slim 3 14ITL6',
                'issue_chronology' => 'Layar bergaris vertikal ungu pasca tertekan di tas punggung. Port flexible sedikit kendor.',
                'addon_cleaning' => true,
                'addon_repasta' => false,
                'delivery_method' => 'Diantar Sendiri',
                'order_status' => 'Closed',
                'unit_handed_over' => true,
                'handed_over_at' => Carbon::now()->subDays(5),
                'sla_auto_close_at' => Carbon::now()->subDays(4),
                'created_at' => Carbon::now()->subDays(7),
                'diagnostic' => [
                    'analysis' => 'Panel LCD retak internal pada lapisan TFT matrix. Kabel flexible eDP 30-pin aus pin ke-12.',
                    'action' => 'Penggantian modul panel layar LCD 14 FHD IPS baru dan kalibrasi refresh rate 60Hz.',
                    'labor' => 150000,
                    'parts' => 850000,
                    'approved' => true,
                ],
                'part_used' => $parts['LCD-156-FHD'],
                'invoice_status' => 'Paid',
                'paid_at' => Carbon::now()->subDays(6),
                'warranty' => [
                    'code' => 'WAR-2026-8812',
                    'days' => 90,
                    'starts' => Carbon::today()->subDays(4),
                    'expires' => Carbon::today()->subDays(4)->addDays(90),
                    'status' => 'Active',
                ],
            ],
            // Tiket 3: Selesai & Ditutup (Closed) - Custom PC Gaming (Storage SSD Upgrade)
            [
                'ticket_no' => 'SC-2026-8790',
                'customer' => $customers['dimas.pratama@outlook.com'],
                'technician' => $dodi,
                'device_type' => 'PC Desktop',
                'device_brand' => 'Custom Rakitan',
                'device_model' => 'Core i5 Gen 12 / RTX 3060',
                'issue_chronology' => 'Booting Windows sangat lambat (> 3 menit), harddisk lama sering freeze 100% disk usage.',
                'addon_cleaning' => true,
                'addon_repasta' => true,
                'delivery_method' => 'Ambil oleh Teknisi',
                'pickup_address' => 'Jl. Kebon Jeruk No. 45, Jakarta Barat',
                'order_status' => 'Closed',
                'unit_handed_over' => true,
                'handed_over_at' => Carbon::now()->subDays(12),
                'sla_auto_close_at' => Carbon::now()->subDays(11),
                'created_at' => Carbon::now()->subDays(14),
                'diagnostic' => [
                    'analysis' => 'Harddisk HDD 1TB bad sector pada track boot sector. Heatsink processor berdebu tebal.',
                    'action' => 'Pemasangan SSD M.2 NVMe 512GB baru, kloning data OS, repasta pendingin MX-4, cable management rapi.',
                    'labor' => 125000,
                    'parts' => 520000,
                    'approved' => true,
                ],
                'part_used' => $parts['SSD-NVME-512'],
                'invoice_status' => 'Paid',
                'paid_at' => Carbon::now()->subDays(13),
                'warranty' => [
                    'code' => 'WAR-2026-8790',
                    'days' => 90,
                    'starts' => Carbon::today()->subDays(11),
                    'expires' => Carbon::today()->subDays(11)->addDays(90),
                    'status' => 'Active',
                ],
            ],
            // Tiket 4: Tahap Reparasi (Lunas QRIS, Teknisi Sedang Mengerjakan)
            [
                'ticket_no' => 'SC-2026-9035',
                'customer' => $customers['siti.nur@gmail.com'],
                'technician' => $agus,
                'device_type' => 'Laptop',
                'device_brand' => 'Acer',
                'device_model' => 'Nitro 5 AN515-57',
                'issue_chronology' => 'Kipas berbunyi kasar berderit kencang saat main game lalu laptop thermal throttling dan shutdown.',
                'addon_cleaning' => true,
                'addon_repasta' => true,
                'delivery_method' => 'Diantar Sendiri',
                'order_status' => 'Reparasi',
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subHours(18),
                'diagnostic' => [
                    'analysis' => 'Bearing kipas pendingin aus oleng, thermal paste kering mengering seperti batu kapur.',
                    'action' => 'Ganti modul kipas pendingin baru, pembersihan sirip heatsink radiator, pelumasan ulang thermal MX-4.',
                    'labor' => 150000,
                    'parts' => 220000,
                    'approved' => true,
                ],
                'part_used' => $parts['FAN-ASUS-G15'],
                'invoice_status' => 'Paid',
                'paid_at' => Carbon::now()->subHours(5),
                'warranty' => false,
            ],
            // Tiket 5: Tahap Selesai (Menunggu Penyerahan Fisik ke Konsumen)
            [
                'ticket_no' => 'SC-2026-9029',
                'customer' => $customers['bayu.wicak@perusahaan.co.id'],
                'technician' => $dodi,
                'device_type' => 'PC Desktop',
                'device_brand' => 'Dell',
                'device_model' => 'OptiPlex 7080 Tower',
                'issue_chronology' => 'PC kantor tidak menyala sama sekali, tidak ada lampu standby motherboard.',
                'addon_cleaning' => false,
                'addon_repasta' => false,
                'delivery_method' => 'Ambil oleh Teknisi',
                'pickup_address' => 'Gedung Wisma Asia Lt. 8, Slipi, Jakarta Barat',
                'order_status' => 'Selesai',
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subDays(1),
                'diagnostic' => [
                    'analysis' => 'Power Supply Unit (PSU) short circuit pada rail 12V output akibat fluktuasi listrik gedung.',
                    'action' => 'Penggantian unit PSU 650W 80+ Bronze baru dan stress test running load 100% selama 60 menit.',
                    'labor' => 150000,
                    'parts' => 750000,
                    'approved' => true,
                ],
                'part_used' => $parts['PSU-650W-80P'],
                'invoice_status' => 'Paid',
                'paid_at' => Carbon::now()->subHours(14),
                'warranty' => false,
            ],
            // Tiket 6: Tahap Approval Biaya (Menunggu Pembayaran QRIS dari Konsumen)
            [
                'ticket_no' => 'SC-2026-9051',
                'customer' => $customers['hendra@gmail.com'],
                'technician' => $budi,
                'device_type' => 'Laptop',
                'device_brand' => 'HP',
                'device_model' => 'Pavilion Gaming 15-dk',
                'issue_chronology' => 'RAM bawaan 8GB sering penuh saat multitasking editing Photoshop dan Premiere.',
                'addon_cleaning' => true,
                'addon_repasta' => false,
                'delivery_method' => 'Diantar Sendiri',
                'order_status' => 'Approval Biaya',
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subHours(3),
                'diagnostic' => [
                    'analysis' => 'Slot RAM DDR4 kedua kosong. Perangkat sehat tanpa korsleting.',
                    'action' => 'Pemasangan keping RAM DDR4 8GB 3200MHz tambahan untuk konfigurasi Dual Channel 16GB.',
                    'labor' => 75000,
                    'parts' => 320000,
                    'approved' => true,
                ],
                'part_used' => $parts['RAM-DDR4-8GB'],
                'invoice_status' => 'Unpaid',
                'warranty' => false,
            ],
            // Tiket 7: Tahap Booking Baru (Unit Belum Tiba di Meja Workshop)
            [
                'ticket_no' => 'SC-2026-9060',
                'customer' => $customers['rina.kartika@yahoo.com'],
                'technician' => null,
                'device_type' => 'Laptop',
                'device_brand' => 'Asus',
                'device_model' => 'Vivobook Ultra 14 A413',
                'issue_chronology' => 'Engsel kiri patah mengeluarkan suara krek saat dibuka, layar berkedip jika digerakkan.',
                'addon_cleaning' => true,
                'addon_repasta' => true,
                'delivery_method' => 'Diantar Sendiri',
                'order_status' => 'Booking',
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subMinutes(45),
                'diagnostic' => [
                    'analysis' => null,
                    'action' => null,
                    'labor' => 0,
                    'parts' => 0,
                    'approved' => false,
                ],
                'part_used' => null,
                'invoice_status' => 'Unpaid',
                'warranty' => false,
            ],
        ];

        foreach ($ordersDataset as $od) {
            $order = ServiceOrder::updateOrCreate(
                ['ticket_no' => $od['ticket_no']],
                [
                    'customer_id' => $od['customer']->id,
                    'technician_id' => $od['technician']?->id,
                    'device_type' => $od['device_type'],
                    'device_brand' => $od['device_brand'],
                    'device_model' => $od['device_model'],
                    'issue_chronology' => $od['issue_chronology'],
                    'addon_cleaning' => $od['addon_cleaning'],
                    'addon_repasta' => $od['addon_repasta'],
                    'delivery_method' => $od['delivery_method'],
                    'pickup_address' => $od['pickup_address'] ?? null,
                    'order_status' => $od['order_status'],
                    'unit_handed_over' => $od['unit_handed_over'],
                    'handed_over_at' => $od['handed_over_at'] ?? null,
                    'sla_auto_close_at' => $od['sla_auto_close_at'] ?? null,
                    'created_at' => $od['created_at'],
                    'updated_at' => $od['created_at'],
                ]
            );

            $costCleaning = $od['addon_cleaning'] ? 50000 : 0;
            $costRepasta = $od['addon_repasta'] ? 75000 : 0;
            $labor = $od['diagnostic']['labor'];
            $partsCost = $od['diagnostic']['parts'];
            $netTotal = $labor + $partsCost + $costCleaning + $costRepasta;

            Diagnostic::updateOrCreate(
                ['service_order_id' => $order->id],
                [
                    'physical_damage_analysis' => $od['diagnostic']['analysis'],
                    'action_plan' => $od['diagnostic']['action'],
                    'labor_cost' => $labor,
                    'parts_cost' => $partsCost,
                    'addon_cleaning_cost' => $costCleaning,
                    'addon_repasta_cost' => $costRepasta,
                    'net_total_cost' => $netTotal,
                    'is_approved_by_user' => $od['diagnostic']['approved'],
                    'approved_at' => $od['diagnostic']['approved'] ? $od['created_at']->copy()->addHours(2) : null,
                    'created_at' => $od['created_at'],
                    'updated_at' => $od['created_at'],
                ]
            );

            if (!empty($od['part_used'])) {
                OrderItem::updateOrCreate(
                    [
                        'service_order_id' => $order->id,
                        'sparepart_id' => $od['part_used']->id,
                    ],
                    [
                        'item_name' => $od['part_used']->part_name,
                        'quantity' => 1,
                        'unit_price' => $od['part_used']->unit_price,
                        'subtotal' => $od['part_used']->unit_price,
                        'created_at' => $od['created_at'],
                        'updated_at' => $od['created_at'],
                    ]
                );
            }

            $invoice = Invoice::updateOrCreate(
                ['service_order_id' => $order->id],
                [
                    'invoice_no' => 'INV-' . $order->ticket_no,
                    'amount_total' => $netTotal,
                    'payment_method' => 'QRIS Dinamis',
                    'payment_status' => $od['invoice_status'],
                    'qris_transaction_id' => $od['invoice_status'] === 'Paid' ? 'QRIS-' . strtoupper(substr(md5($order->ticket_no), 0, 10)) : null,
                    'paid_at' => $od['paid_at'] ?? null,
                    'created_at' => $od['created_at'],
                    'updated_at' => $od['created_at'],
                ]
            );

            if (!empty($od['warranty'])) {
                Warranty::updateOrCreate(
                    ['invoice_id' => $invoice->id],
                    [
                        'warranty_code' => $od['warranty']['code'],
                        'warranty_duration_days' => $od['warranty']['days'],
                        'starts_at' => $od['warranty']['starts'],
                        'expires_at' => $od['warranty']['expires'],
                        'warranty_status' => $od['warranty']['status'],
                        'created_at' => $od['created_at'],
                        'updated_at' => $od['created_at'],
                    ]
                );
            }
        }
    }
}
