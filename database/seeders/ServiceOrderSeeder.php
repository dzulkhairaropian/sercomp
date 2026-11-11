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

class ServiceOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $budi = User::where('email', 'budi@sercomp.id')->first();
        $agus = User::where('email', 'agus@sercomp.id')->first();
        $dodi = User::where('email', 'dodi@sercomp.id')->first();

        $custHendra = User::where('email', 'hendra@gmail.com')->first();
        $custRina = User::where('email', 'rina.kartika@yahoo.com')->first();
        $custDimas = User::where('email', 'dimas.pratama@outlook.com')->first();
        $custSiti = User::where('email', 'siti.nur@gmail.com')->first();
        $custBayu = User::where('email', 'bayu.wicak@perusahaan.co.id')->first();
        $custMega = User::where('email', 'it.procurement@megacorp.co.id')->first();
        $custClarissa = User::where('email', 'clarissa.p@gmail.com')->first();
        $custFajar = User::where('email', 'fajar.ramadhan@techindo.com')->first();

        $partFanAsus = Sparepart::where('part_code', 'FAN-ASUS-G15')->first();
        $partRam16 = Sparepart::where('part_code', 'RAM-DDR4-16GB')->first();
        $partSsd1tb = Sparepart::where('part_code', 'SSD-NVME-1TB')->first();
        $partSsd512 = Sparepart::where('part_code', 'SSD-NVME-512')->first();
        $partLcd156 = Sparepart::where('part_code', 'LCD-156-FHD')->first();
        $partPsu650 = Sparepart::where('part_code', 'PSU-650W-80P')->first();

        $dataset = [
            // Tiket 1: Aktif di Meja Kerja (Di Workshop)
            [
                'ticket_no' => 'SC-2026-9042',
                'customer' => $custHendra,
                'technician' => $budi,
                'customer_type' => 'Personal',
                'company_name' => null,
                'device_type' => 'Laptop',
                'device_brand' => 'Asus',
                'device_model' => 'ROG Strix G15 (G513)',
                'issue_chronology' => 'Laptop mati mendadak saat rendering 3D, tercium bau sangit dari ventilasi exhaust belakang.',
                'addon_cleaning' => true,
                'addon_repasta' => true,
                'delivery_method' => 'Diantar Sendiri',
                'order_status' => 'Di Workshop',
                'security_pin' => 'K#7X',
                'negotiation_status' => 'Requested',
                'negotiation_budget' => 380000,
                'negotiation_notes' => 'Tolong dibantu potong biaya jasa sedikit kak, saya langganan servis laptop kantor di sini.',
                'diagnostic' => [
                    'physical_damage_analysis' => 'Bearing kipas pendingin GPU macet total berkerak debu, thermal paste mengeras menjadi bubuk kering. Jalur fuse 19V aman.',
                    'action_plan' => 'Ganti modul kipas dual-cooler, deep cleaning heatsink tembaga, dan repasta Arctic MX-4.',
                    'labor_cost' => 150000,
                    'parts_cost' => 220000,
                    'is_approved_by_user' => false,
                    'sparepart' => $partFanAsus,
                ],
                'invoice_status' => 'Unpaid',
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subHours(8),
            ],

            // Tiket 2: Selesai & Ditutup (Closed) - e-Warranty Aktif 90 Hari
            [
                'ticket_no' => 'SC-2026-8812',
                'customer' => $custRina,
                'technician' => $agus,
                'customer_type' => 'Personal',
                'company_name' => null,
                'device_type' => 'Laptop',
                'device_brand' => 'Lenovo',
                'device_model' => 'Ideapad Slim 3 14ALC6',
                'issue_chronology' => 'Layar bergaris vertikal ungu-hijau setelah laptop tertindih buku tebal di ransel kuliah.',
                'addon_cleaning' => false,
                'addon_repasta' => false,
                'delivery_method' => 'Diantar Sendiri',
                'order_status' => 'Closed',
                'security_pin' => 'R9*L',
                'negotiation_status' => 'None',
                'diagnostic' => [
                    'physical_damage_analysis' => 'Panel IPS mengalami crack internal pada substrate kaca driver gate COF kanan.',
                    'action_plan' => 'Penggantian modul panel layar LCD 14.0 FHD IPS slim original.',
                    'labor_cost' => 125000,
                    'parts_cost' => 720000,
                    'is_approved_by_user' => true,
                    'approved_at' => Carbon::now()->subDays(6),
                    'sparepart' => null,
                ],
                'invoice_status' => 'Paid',
                'paid_at' => Carbon::now()->subDays(5),
                'unit_handed_over' => true,
                'courier_name' => 'Staf Workshop SerComp',
                'receiver_name' => 'Rina Kartika',
                'handed_over_at' => Carbon::now()->subDays(4),
                'warranty_duration_days' => 90,
                'created_at' => Carbon::now()->subDays(7),
            ],

            // Tiket 3: Selesai & Ditutup (Closed) - Custom PC Gaming (Storage Upgrade)
            [
                'ticket_no' => 'SC-2026-8790',
                'customer' => $custDimas,
                'technician' => $dodi,
                'customer_type' => 'Personal',
                'company_name' => null,
                'device_type' => 'PC Desktop',
                'device_brand' => 'Custom Rig',
                'device_model' => 'Ryzen 7 5800X / RTX 3070',
                'issue_chronology' => 'Loading game Cyberpunk 2077 sangat lama di HDD lama, ingin upgrade SSD NVMe cepat dan repasta liquid metal.',
                'addon_cleaning' => true,
                'addon_repasta' => true,
                'delivery_method' => 'Diantar Sendiri',
                'order_status' => 'Closed',
                'security_pin' => 'D2@M',
                'negotiation_status' => 'None',
                'diagnostic' => [
                    'physical_damage_analysis' => 'Harddisk lama memiliki 48 bad sector pending. Sirkulasi casing berdebu tebal.',
                    'action_plan' => 'Pasang SSD Samsung 980 Pro 1TB M.2 NVMe, clone sistem OS Windows 11, dan repasta prosesor.',
                    'labor_cost' => 150000,
                    'parts_cost' => 1350000,
                    'is_approved_by_user' => true,
                    'approved_at' => Carbon::now()->subDays(9),
                    'sparepart' => $partSsd1tb,
                ],
                'invoice_status' => 'Paid',
                'paid_at' => Carbon::now()->subDays(8),
                'unit_handed_over' => true,
                'courier_name' => 'Ambil Mandiri Konsumen',
                'receiver_name' => 'Dimas Pratama',
                'handed_over_at' => Carbon::now()->subDays(7),
                'warranty_duration_days' => 180,
                'created_at' => Carbon::now()->subDays(10),
            ],

            // Tiket 4: Tahap Reparasi (Lunas QRIS, Teknisi Sedang Mengerjakan)
            [
                'ticket_no' => 'SC-2026-9035',
                'customer' => $custSiti,
                'technician' => $agus,
                'customer_type' => 'Personal',
                'company_name' => null,
                'device_type' => 'Laptop',
                'device_brand' => 'HP',
                'device_model' => 'Pavilion Gaming 15-dk',
                'issue_chronology' => 'Engsel kiri patah mengeluarkan serpihan plastik saat dibuka, kabel antena wifi terjepit.',
                'addon_cleaning' => true,
                'addon_repasta' => false,
                'delivery_method' => 'Ambil oleh Teknisi',
                'order_status' => 'Reparasi',
                'security_pin' => 'S4!P',
                'negotiation_status' => 'None',
                'diagnostic' => [
                    'physical_damage_analysis' => 'Kuningan baut hinge housing frame bottom case pecah hancur, engsel macet butuh pelumasan.',
                    'action_plan' => 'Rekonstruksi kuningan baut resin epoxy tebal dan kalibrasi tegangan per engsel kiri-kanan.',
                    'labor_cost' => 175000,
                    'parts_cost' => 0,
                    'is_approved_by_user' => true,
                    'approved_at' => Carbon::now()->subHours(12),
                    'sparepart' => null,
                ],
                'invoice_status' => 'Paid',
                'paid_at' => Carbon::now()->subHours(10),
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subDay(),
            ],

            // Tiket 5: Tahap Selesai (Siap Transaksi di Kasir & Serah Terima)
            [
                'ticket_no' => 'SC-2026-9029',
                'customer' => $custBayu,
                'technician' => $dodi,
                'customer_type' => 'Company',
                'company_name' => 'PT Surya Digital Corpora',
                'device_type' => 'PC Desktop',
                'device_brand' => 'Dell Optiplex',
                'device_model' => 'Tower 7080 Workstation',
                'issue_chronology' => 'PC sering mati mendadak saat render video proyek animasi studio.',
                'addon_cleaning' => false,
                'addon_repasta' => false,
                'delivery_method' => 'Diantar Sendiri',
                'order_status' => 'Selesai',
                'security_pin' => 'B7#W',
                'negotiation_status' => 'None',
                'diagnostic' => [
                    'physical_damage_analysis' => 'Kapasitor rel 12V power supply bocor memicu OCP shut down otomatis.',
                    'action_plan' => 'Ganti PSU unit ke Aerocool 650W 80+ Bronze, rapikan cable management.',
                    'labor_cost' => 100000,
                    'parts_cost' => 750000,
                    'is_approved_by_user' => true,
                    'approved_at' => Carbon::now()->subHours(18),
                    'sparepart' => $partPsu650,
                ],
                'invoice_status' => 'Unpaid', // Menunggu bayar di kasir
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subDays(2),
            ],

            // Tiket 6: Tahap Approval Biaya (Menunggu Approval Konsumen)
            [
                'ticket_no' => 'SC-2026-9051',
                'customer' => $custClarissa,
                'technician' => $budi,
                'customer_type' => 'Personal',
                'company_name' => null,
                'device_type' => 'Laptop',
                'device_brand' => 'Acer',
                'device_model' => 'Swift 3 SF314',
                'issue_chronology' => 'Baterai boros drastis dan touchpad sering loncat sendiri saat dicharge.',
                'addon_cleaning' => false,
                'addon_repasta' => false,
                'delivery_method' => 'Diantar Sendiri',
                'order_status' => 'Approval Biaya',
                'security_pin' => 'C3*Q',
                'negotiation_status' => 'None',
                'diagnostic' => [
                    'physical_damage_analysis' => 'Baterai internal kembung menekan trackpad dari bawah hingga klik mengeras.',
                    'action_plan' => 'Ganti baterai internal 4-cell baru OEM original dan kalibrasi grounding trackpad.',
                    'labor_cost' => 100000,
                    'parts_cost' => 450000,
                    'is_approved_by_user' => false,
                    'sparepart' => null,
                ],
                'invoice_status' => 'Unpaid',
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subHours(5),
            ],

            // Tiket 7: Korporasi B2B - 10 Unit Maintenance (Company Lead)
            [
                'ticket_no' => 'SC-2026-9060',
                'customer' => $custMega,
                'technician' => $dodi,
                'customer_type' => 'Company',
                'company_name' => 'PT Mega Solusi Nusantara',
                'device_type' => 'PC Desktop',
                'device_brand' => 'Dell Optiplex',
                'device_model' => '7090 Micro Fleet',
                'issue_chronology' => 'Paket perawatan 10 unit PC kantor akuntansi: lambat, berdebu tebal, butuh upgrade RAM 16GB.',
                'addon_cleaning' => true,
                'addon_repasta' => true,
                'delivery_method' => 'Diantar Sendiri',
                'order_status' => 'Reparasi',
                'security_pin' => 'M8@K',
                'negotiation_status' => 'Approved',
                'negotiation_budget' => 6000000,
                'negotiation_notes' => 'Disetujui diskon korporasi 10% untuk maintenance armada kantor.',
                'diagnostic' => [
                    'physical_damage_analysis' => 'Thermal throttling terdeteksi di seluruh unit, RAM 8GB bawaan bottleneck pemakaian SAP.',
                    'action_plan' => 'Instalasi RAM 16GB per unit, deep cleaning radiator exhaust, dan thermal repasta.',
                    'labor_cost' => 500000,
                    'parts_cost' => 5800000,
                    'is_approved_by_user' => true,
                    'approved_at' => Carbon::now()->subDays(1),
                    'sparepart' => $partRam16,
                ],
                'invoice_status' => 'Paid',
                'paid_at' => Carbon::now()->subHours(16),
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subDays(3),
            ],

            // Tiket 8: Booking Baru (Belum Di-assign Teknisi)
            [
                'ticket_no' => 'SC-2026-9077',
                'customer' => $custFajar,
                'technician' => null,
                'customer_type' => 'Company',
                'company_name' => 'CV Techindo Creative Media',
                'device_type' => 'Laptop',
                'device_brand' => 'Apple',
                'device_model' => 'MacBook Pro 14 M1 Pro',
                'issue_chronology' => 'Keyboard tombol spacebar macet akibat ketumpahan cairan kopi tipis kemarin sore.',
                'addon_cleaning' => true,
                'addon_repasta' => false,
                'delivery_method' => 'Ambil oleh Teknisi',
                'pickup_address' => 'Gedung Cyber 2 Tower Lt. 15, Jl. HR Rasuna Said Jakarta Selatan.',
                'order_status' => 'Booking',
                'security_pin' => 'F5!T',
                'negotiation_status' => 'None',
                'diagnostic' => null,
                'invoice_status' => 'Unpaid',
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subHours(2),
            ],
        ];

        foreach ($dataset as $od) {
            $order = ServiceOrder::updateOrCreate(
                ['ticket_no' => $od['ticket_no']],
                [
                    'customer_id' => $od['customer']?->id,
                    'technician_id' => $od['technician']?->id,
                    'customer_type' => $od['customer_type'] ?? 'Personal',
                    'company_name' => $od['company_name'] ?? null,
                    'security_pin' => $od['security_pin'],
                    'device_type' => $od['device_type'],
                    'device_brand' => $od['device_brand'],
                    'device_model' => $od['device_model'],
                    'issue_chronology' => $od['issue_chronology'],
                    'addon_cleaning' => $od['addon_cleaning'],
                    'addon_repasta' => $od['addon_repasta'],
                    'delivery_method' => $od['delivery_method'],
                    'pickup_address' => $od['pickup_address'] ?? null,
                    'order_status' => $od['order_status'],
                    'negotiation_status' => $od['negotiation_status'] ?? 'None',
                    'negotiation_budget' => $od['negotiation_budget'] ?? null,
                    'negotiation_notes' => $od['negotiation_notes'] ?? null,
                    'unit_handed_over' => $od['unit_handed_over'],
                    'handed_over_at' => $od['handed_over_at'] ?? null,
                    'courier_name' => $od['courier_name'] ?? null,
                    'receiver_name' => $od['receiver_name'] ?? null,
                    'created_at' => $od['created_at'],
                    'updated_at' => $od['created_at'],
                ]
            );

            // Simpan / update Diagnosa
            $diagData = $od['diagnostic'];
            $costCleaning = $od['addon_cleaning'] ? 50000 : 0;
            $costRepasta = $od['addon_repasta'] ? 75000 : 0;
            $laborCost = $diagData['labor_cost'] ?? 0;
            $partsCost = $diagData['parts_cost'] ?? 0;
            $netTotal = $laborCost + $partsCost + $costCleaning + $costRepasta;

            Diagnostic::updateOrCreate(
                ['service_order_id' => $order->id],
                [
                    'physical_damage_analysis' => $diagData['physical_damage_analysis'] ?? null,
                    'action_plan' => $diagData['action_plan'] ?? null,
                    'labor_cost' => $laborCost,
                    'parts_cost' => $partsCost,
                    'addon_cleaning_cost' => $costCleaning,
                    'addon_repasta_cost' => $costRepasta,
                    'net_total_cost' => $netTotal,
                    'is_approved_by_user' => $diagData['is_approved_by_user'] ?? false,
                    'approved_at' => $diagData['approved_at'] ?? null,
                    'created_at' => $od['created_at'],
                    'updated_at' => $od['created_at'],
                ]
            );

            // Item sparepart
            if (!empty($diagData['sparepart'])) {
                $sp = $diagData['sparepart'];
                OrderItem::updateOrCreate(
                    [
                        'service_order_id' => $order->id,
                        'sparepart_id' => $sp->id,
                    ],
                    [
                        'item_name' => $sp->part_name,
                        'quantity' => 1,
                        'unit_price' => $sp->unit_price,
                        'subtotal' => $sp->unit_price,
                    ]
                );
            }

            // Invoice
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

            // Garansi jika status Closed
            if ($order->order_status === 'Closed') {
                $duration = $od['warranty_duration_days'] ?? 90;
                $start = $order->handed_over_at ? Carbon::parse($order->handed_over_at)->toDateString() : Carbon::now()->toDateString();
                $expires = Carbon::parse($start)->addDays($duration)->toDateString();

                Warranty::updateOrCreate(
                    ['invoice_id' => $invoice->id],
                    [
                        'warranty_code' => 'WAR-' . $order->ticket_no,
                        'warranty_duration_days' => $duration,
                        'starts_at' => $start,
                        'expires_at' => $expires,
                        'warranty_status' => 'Active',
                    ]
                );
            }
        }
    }
}
