<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceTicket;
use App\Models\ServiceTicketItem;
use App\Models\SlaLog;
use App\Models\Sparepart;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SercompDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. TEKNISI & STAF WORKSHOP
        $techBudi = User::updateOrCreate(
            ['email' => 'budi@sercomp.id'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('teknisi123'),
                'role' => 'technician',
                'tech_code' => 'TEK-2026-01',
                'phone' => '081298765432',
                'specialization' => 'Hardware Specialist Level 3',
                'is_active' => true,
            ]
        );

        $techAgus = User::updateOrCreate(
            ['email' => 'agus@sercomp.id'],
            [
                'name' => 'Agus Prasetyo',
                'password' => Hash::make('teknisi123'),
                'role' => 'technician',
                'tech_code' => 'TEK-2026-02',
                'phone' => '081377889900',
                'specialization' => 'Micro-Soldering & Power IC',
                'is_active' => true,
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@sercomp.id'],
            [
                'name' => 'Admin SerComp',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'tech_code' => 'ADM-2026-01',
                'phone' => '081122334455',
                'specialization' => 'Workshop Manager',
                'is_active' => true,
            ]
        );

        // 2. KATALOG LAYANAN & ADD-ON
        $services = [
            ['name' => 'Deep Cleaning & Dust Removal', 'category' => 'maintenance', 'price' => 50000, 'is_addon' => true],
            ['name' => 'Repasta Thermal Arctic MX-4', 'category' => 'maintenance', 'price' => 75000, 'is_addon' => true],
            ['name' => 'Troubleshoot Motherboard & Power IC', 'category' => 'hardware', 'price' => 250000, 'is_addon' => false],
            ['name' => 'Jasa Penggantian Modul Fan Cooler', 'category' => 'hardware', 'price' => 150000, 'is_addon' => false],
            ['name' => 'Jasa Ganti Keyboard Laptop', 'category' => 'hardware', 'price' => 100000, 'is_addon' => false],
            ['name' => 'Jasa Upgrade SSD & RAM', 'category' => 'hardware', 'price' => 75000, 'is_addon' => false],
            ['name' => 'Instalasi Bersih OS Windows 11 / Linux', 'category' => 'software', 'price' => 80000, 'is_addon' => false],
            ['name' => 'Rakit PC Desktop Custom & Cable Mgmt', 'category' => 'custom_pc', 'price' => 150000, 'is_addon' => false],
        ];

        foreach ($services as $srv) {
            Service::updateOrCreate(['name' => $srv['name']], $srv);
        }

        // 3. STOK SUKU CADANG (SPAREPARTS)
        $partFan = Sparepart::updateOrCreate(
            ['part_code' => 'FAN-ASUS-G15'],
            [
                'name' => 'Modul Fan Cooler Original Asus ROG Strix G15',
                'category' => 'cooling',
                'stock' => 5,
                'cost_price' => 160000,
                'selling_price' => 220000,
                'min_stock_alert' => 3,
                'is_active' => true,
            ]
        );

        $partPaste = Sparepart::updateOrCreate(
            ['part_code' => 'TH-ARCTIC-MX4'],
            [
                'name' => 'Thermal Paste Arctic MX-4 (4 Gram)',
                'category' => 'chemical',
                'stock' => 18,
                'cost_price' => 45000,
                'selling_price' => 75000,
                'min_stock_alert' => 5,
                'is_active' => true,
            ]
        );

        $partSsd = Sparepart::updateOrCreate(
            ['part_code' => 'SSD-NVME-512'],
            [
                'name' => 'SSD M.2 NVMe PCIe Gen 4 Kingston NV2 512GB',
                'category' => 'storage',
                'stock' => 8,
                'cost_price' => 450000,
                'selling_price' => 580000,
                'min_stock_alert' => 3,
                'is_active' => true,
            ]
        );

        $partRam = Sparepart::updateOrCreate(
            ['part_code' => 'RAM-DDR4-8GB'],
            [
                'name' => 'RAM SODIMM DDR4 8GB 3200MHz Kingston Value',
                'category' => 'memory',
                'stock' => 12,
                'cost_price' => 220000,
                'selling_price' => 310000,
                'min_stock_alert' => 4,
                'is_active' => true,
            ]
        );

        $partKb = Sparepart::updateOrCreate(
            ['part_code' => 'KB-ASUS-VIVO'],
            [
                'name' => 'Keyboard Laptop Asus Vivobook A412 Series',
                'category' => 'input_device',
                'stock' => 2,
                'cost_price' => 120000,
                'selling_price' => 190000,
                'min_stock_alert' => 2,
                'is_active' => true,
            ]
        );

        // 4. DATA DEMO TIKET SERVIS (JOB ORDERS)
        // Tiket 1: Active Interactive Demo Order
        $ticket1 = ServiceTicket::updateOrCreate(
            ['ticket_no' => '#SC-2026-9042'],
            [
                'customer_name' => 'Dzul Khair Aropian',
                'customer_phone' => '081298765432',
                'customer_email' => 'dzul@student.unm.ac.id',
                'device_type' => 'Laptop',
                'device_brand' => 'Asus',
                'device_model' => 'ROG Strix G15 (Core i7 / RTX 3060)',
                'device_specs' => 'Intel Core i7 11800H, RTX 3060 6GB, 16GB DDR4',
                'issue_chronology' => 'Layar tiba-tiba mati saat rendering tugas, lampu indikator menyala tapi kipas berisik sekali dan laptop panas.',
                'delivery_method' => 'Diantar Sendiri',
                'technician_id' => $techBudi->id,
                'diagnosis' => 'Kipas pendingin (fan cooler) aus & bearing macet, menyebabkan CPU thermal throttling darurat.',
                'action_plan' => '1) Penggantian unit modul fan cooler original baru; 2) Deep cleaning debu heatsink; 3) Repasta thermal Arctic MX-4; 4) Running test suhu 60 menit.',
                'technician_notes' => 'Motherboard normal bebas korsleting. Seluruh data SSD terlindungi 100%.',
                'step' => 3,
                'status' => 'Menunggu Persetujuan Biaya',
                'cost_labor' => 150000,
                'cost_parts' => 220000,
                'cost_cleaning' => 50000,
                'cost_repasta' => 75000,
                'total_cost' => 495000,
                'payment_status' => 'Unpaid',
                'payment_method' => 'QRIS',
                'is_approved' => false,
                'is_completed' => false,
                'unit_handed_over' => false,
                'is_closed' => false,
                'evidence_running_test_path' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&auto=format&fit=crop&q=80',
                'evidence_replaced_part_path' => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=600&auto=format&fit=crop&q=80',
                'invoice_no' => 'INV-SC-2026-9042',
            ]
        );

        ServiceTicketItem::updateOrCreate(
            ['service_ticket_id' => $ticket1->id, 'item_name' => 'Jasa Penggantian Modul Fan Cooler & Running Test'],
            ['item_type' => 'service', 'quantity' => 1, 'unit_price' => 150000, 'subtotal' => 150000]
        );
        ServiceTicketItem::updateOrCreate(
            ['service_ticket_id' => $ticket1->id, 'item_name' => 'Modul Fan Cooler Original Asus ROG G15'],
            ['item_type' => 'sparepart', 'sparepart_id' => $partFan->id, 'quantity' => 1, 'unit_price' => 220000, 'subtotal' => 220000]
        );
        ServiceTicketItem::updateOrCreate(
            ['service_ticket_id' => $ticket1->id, 'item_name' => 'Add-on: Deep Cleaning & Dust Removal'],
            ['item_type' => 'addon', 'quantity' => 1, 'unit_price' => 50000, 'subtotal' => 50000]
        );
        ServiceTicketItem::updateOrCreate(
            ['service_ticket_id' => $ticket1->id, 'item_name' => 'Add-on: Repasta Thermal Arctic MX-4 High-Performance'],
            ['item_type' => 'addon', 'quantity' => 1, 'unit_price' => 75000, 'subtotal' => 75000]
        );

        // Tiket 2: Sedang Dikerjakan
        ServiceTicket::updateOrCreate(
            ['ticket_no' => '#SC-2026-0982'],
            [
                'customer_name' => 'Siti Rahmawati',
                'customer_phone' => '081388992211',
                'device_type' => 'Laptop',
                'device_brand' => 'Asus',
                'device_model' => 'Vivobook 14 (A412DA)',
                'issue_chronology' => 'Mati total kena tetesan air hujan di ransel.',
                'delivery_method' => 'Ambil oleh Teknisi',
                'pickup_address' => 'Jl. Salemba Tengah No. 12, Jakarta Pusat',
                'technician_id' => $techAgus->id,
                'diagnosis' => 'Korosi pada pin IC Regulator VCore akibat lembap air hujan.',
                'action_plan' => 'Solder ulang micro-soldering IC regulator dan pembersihan ultrasonic board.',
                'step' => 5,
                'status' => 'Sedang Dikerjakan',
                'cost_labor' => 150000,
                'cost_parts' => 120000,
                'total_cost' => 270000,
                'payment_status' => 'Paid',
                'paid_at' => Carbon::now()->subHours(2),
                'is_approved' => true,
                'approved_at' => Carbon::now()->subHours(3),
                'invoice_no' => 'INV-SC-2026-0982',
            ]
        );

        // Tiket 3: Selesai & Ditutup dengan Garansi 90 Hari
        $ticket3 = ServiceTicket::updateOrCreate(
            ['ticket_no' => '#SC-2026-6640'],
            [
                'customer_name' => 'Rizky Hidayat',
                'customer_phone' => '081399887766',
                'device_type' => 'Laptop',
                'device_brand' => 'Acer',
                'device_model' => 'Nitro 5 (AN515-57)',
                'issue_chronology' => 'Tuts keyboard huruf W, S, dan spasi mati.',
                'delivery_method' => 'Diantar Sendiri',
                'technician_id' => $techBudi->id,
                'diagnosis' => 'Jalur membran switch keyboard putus permanen.',
                'action_plan' => 'Penggantian modul keyboard backlight baru.',
                'step' => 7,
                'status' => 'Closed / Completed',
                'cost_labor' => 100000,
                'cost_parts' => 190000,
                'total_cost' => 290000,
                'payment_status' => 'Paid',
                'paid_at' => Carbon::now()->subDays(3),
                'is_approved' => true,
                'is_completed' => true,
                'completed_at' => Carbon::now()->subDays(2),
                'unit_handed_over' => true,
                'unit_handed_over_at' => Carbon::now()->subDays(2),
                'sla_auto_close_deadline' => Carbon::now()->subDays(1),
                'is_closed' => true,
                'closed_at' => Carbon::now()->subDays(1),
                'closed_by' => 'system_sla',
                'warranty_code' => 'WRN-2026-6640',
                'warranty_valid_until' => Carbon::now()->addDays(88),
                'invoice_no' => 'INV-SC-2026-6640',
            ]
        );

        SlaLog::create([
            'service_ticket_id' => $ticket3->id,
            'event_type' => 'auto_closed',
            'description' => 'SLA 1x24 Jam leluasa tanpa komplain. Sistem menutup tiket dan mengaktifkan e-Warranty 90 hari.',
        ]);
    }
}
