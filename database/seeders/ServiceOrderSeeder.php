<?php

namespace Database\Seeders;

use App\Models\Diagnostic;
use App\Models\Invoice;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ServiceOrder;
use App\Models\Sparepart;
use App\Models\TicketStatusHistory;
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
        $admin = User::where('email', 'admin@sercomp.id')->first();
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
        $partLcd140 = Sparepart::where('part_code', 'LCD-140-FHD')->first();
        $partPsu650 = Sparepart::where('part_code', 'PSU-650W-80P')->first();
        $partBatAcer = Sparepart::where('part_code', 'BAT-ACER-SF3')->first();

        $dataset = [
            // Tiket 1: SC-2026-9042 | Tahap: Di Workshop (Menunggu Nego)
            [
                'ticket_no' => 'SC-2026-9042',
                'customer' => $custHendra,
                'technician' => $budi,
                'customer_type' => 'Personal',
                'company_name' => null,
                'crm_segment_snapshot' => 'Aktif',
                'lead_source' => 'Google Search',
                'branch_code' => 'JKT-PST-01',
                'device_type' => 'Laptop',
                'device_brand' => 'Asus',
                'device_model' => 'ROG Strix G15 (G513)',
                'serial_number' => 'SN-ASUS-G513-99881',
                'issue_chronology' => 'Laptop mati mendadak saat rendering 3D, tercium bau sangit dari ventilasi exhaust belakang.',
                'addon_cleaning' => true,
                'addon_repasta' => true,
                'delivery_method' => 'Diantar Sendiri',
                'handover_method' => null,
                'order_status' => 'Di Workshop',
                'security_pin' => 'K#7X',
                'negotiation_status' => 'Requested',
                'negotiation_budget' => 380000,
                'negotiation_notes' => 'Tolong dibantu diskon jasa sedikit kak, saya langganan servis di sini.',
                'items' => [
                    [
                        'part' => $partFanAsus,
                        'qty' => 1,
                        'unit_price' => 220000,
                        'cost_price' => 145000,
                    ],
                ],
                'diagnostic' => [
                    'symptom_category' => 'Thermal / Overheating',
                    'fault_category' => 'Cooling Fan Bearing Seized',
                    'physical_damage_analysis' => 'Bearing kipas pendingin GPU macet total berkerak debu tebal, thermal paste mengeras jadi bubuk.',
                    'action_plan' => 'Ganti modul dual fan cooler, deep cleaning heatsink, dan repasta thermal Arctic MX-4.',
                    'labor_cost' => 150000,
                    'parts_cost' => 220000,
                    'is_approved_by_user' => false,
                ],
                'payments' => [],
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subHours(8),
                'history' => [
                    ['from' => null, 'to' => 'Booking', 'by' => null, 'time' => Carbon::now()->subHours(8)],
                    ['from' => 'Booking', 'to' => 'Di Workshop', 'by' => $admin?->id, 'time' => Carbon::now()->subHours(6)],
                ],
            ],

            // Tiket 2: SC-2026-8812 | Tahap: Closed (e-Warranty 90 Hari Aktif)
            // Fix Gap: Sparepart terhubung (LCD-140-FHD), digital signature = valid, handover_method terpisah
            [
                'ticket_no' => 'SC-2026-8812',
                'customer' => $custRina,
                'technician' => $agus,
                'customer_type' => 'Personal',
                'company_name' => null,
                'crm_segment_snapshot' => 'Setia / High Value',
                'lead_source' => 'Instagram Ads',
                'branch_code' => 'JKT-PST-01',
                'device_type' => 'Laptop',
                'device_brand' => 'Lenovo',
                'device_model' => 'Ideapad Slim 3 14ALC6',
                'serial_number' => 'SN-LEN-IP3-77210',
                'issue_chronology' => 'Layar bergaris vertikal ungu-hijau setelah laptop tertindih buku tebal di ransel.',
                'addon_cleaning' => false,
                'addon_repasta' => false,
                'delivery_method' => 'Diantar Sendiri',
                'handover_method' => 'Pickup Counter',
                'courier_name' => 'Staf Workshop SerComp',
                'receiver_name' => 'Rina Kartika',
                'has_signature' => true,
                'order_status' => 'Closed',
                'security_pin' => 'R9*L',
                'negotiation_status' => 'None',
                'rating_score' => 5,
                'feedback_comments' => 'Pengerjaan cepat sekali dan layar LCD jernih seperti baru!',
                'items' => [
                    [
                        'part' => $partLcd140,
                        'qty' => 1,
                        'unit_price' => 720000,
                        'cost_price' => 490000,
                    ],
                ],
                'diagnostic' => [
                    'symptom_category' => 'Display Artifact / Glitch',
                    'fault_category' => 'LCD Panel Internal Crack',
                    'physical_damage_analysis' => 'Panel IPS mengalami crack internal pada substrate kaca driver gate COF kanan.',
                    'action_plan' => 'Penggantian modul panel layar LCD 14.0 FHD IPS slim original.',
                    'labor_cost' => 125000,
                    'parts_cost' => 720000,
                    'is_approved_by_user' => true,
                    'approved_at' => Carbon::now()->subDays(6),
                ],
                'payments' => [
                    [
                        'type' => 'Full Payment',
                        'method' => 'QRIS Dinamis',
                        'amount' => 845000,
                        'time' => Carbon::now()->subDays(5),
                    ],
                ],
                'unit_handed_over' => true,
                'handed_over_at' => Carbon::now()->subDays(4),
                'warranty_duration_days' => 90,
                'created_at' => Carbon::now()->subDays(7),
                'history' => [
                    ['from' => null, 'to' => 'Booking', 'by' => null, 'time' => Carbon::now()->subDays(7)],
                    ['from' => 'Booking', 'to' => 'Di Workshop', 'by' => $admin?->id, 'time' => Carbon::now()->subDays(6)->subHours(20)],
                    ['from' => 'Di Workshop', 'to' => 'Approval Biaya', 'by' => $agus?->id, 'time' => Carbon::now()->subDays(6)->subHours(10)],
                    ['from' => 'Approval Biaya', 'to' => 'Reparasi', 'by' => null, 'time' => Carbon::now()->subDays(5)],
                    ['from' => 'Reparasi', 'to' => 'Selesai', 'by' => $agus?->id, 'time' => Carbon::now()->subDays(4)->subHours(8)],
                    ['from' => 'Selesai', 'to' => 'Diserahkan', 'by' => $admin?->id, 'time' => Carbon::now()->subDays(4)],
                    ['from' => 'Diserahkan', 'to' => 'Closed', 'by' => null, 'time' => Carbon::now()->subDays(3)],
                ],
            ],

            // Tiket 3: SC-2026-8790 | Tahap: Closed (Storage Upgrade PC)
            [
                'ticket_no' => 'SC-2026-8790',
                'customer' => $custDimas,
                'technician' => $dodi,
                'customer_type' => 'Personal',
                'company_name' => null,
                'crm_segment_snapshot' => 'Setia / High Value',
                'lead_source' => 'Walk-in Workshop',
                'branch_code' => 'JKT-PST-01',
                'device_type' => 'PC Desktop',
                'device_brand' => 'Custom Rig',
                'device_model' => 'Ryzen 7 5800X / RTX 3070',
                'serial_number' => 'RIG-DIMAS-2024',
                'issue_chronology' => 'Loading game lambat di HDD lawas, ingin upgrade SSD NVMe 1TB dan thermal overhaul.',
                'addon_cleaning' => true,
                'addon_repasta' => true,
                'delivery_method' => 'Diantar Sendiri',
                'handover_method' => 'Pickup Counter',
                'courier_name' => 'Ambil Mandiri Konsumen',
                'receiver_name' => 'Dimas Pratama',
                'has_signature' => true,
                'order_status' => 'Closed',
                'security_pin' => 'D2@M',
                'negotiation_status' => 'None',
                'rating_score' => 5,
                'feedback_comments' => 'Game langsung responsif, suhu drop 15 derajat.',
                'items' => [
                    [
                        'part' => $partSsd1tb,
                        'qty' => 1,
                        'unit_price' => 1350000,
                        'cost_price' => 980000,
                    ],
                ],
                'diagnostic' => [
                    'symptom_category' => 'Storage Bottleneck / Slow',
                    'fault_category' => 'Harddisk Bad Sector S.M.A.R.T Warning',
                    'physical_damage_analysis' => 'HDD lama memiliki 48 bad sector pending. Sirkulasi casing kotor.',
                    'action_plan' => 'Pasang SSD Samsung 980 Pro 1TB M.2 NVMe, clone sistem OS Windows 11, dan repasta.',
                    'labor_cost' => 150000,
                    'parts_cost' => 1350000,
                    'is_approved_by_user' => true,
                    'approved_at' => Carbon::now()->subDays(9),
                ],
                'payments' => [
                    [
                        'type' => 'Full Payment',
                        'method' => 'Transfer Bank / EDC',
                        'amount' => 1625000,
                        'time' => Carbon::now()->subDays(8),
                    ],
                ],
                'unit_handed_over' => true,
                'handed_over_at' => Carbon::now()->subDays(7),
                'warranty_duration_days' => 180,
                'created_at' => Carbon::now()->subDays(10),
                'history' => [
                    ['from' => null, 'to' => 'Booking', 'by' => null, 'time' => Carbon::now()->subDays(10)],
                    ['from' => 'Booking', 'to' => 'Di Workshop', 'by' => $admin?->id, 'time' => Carbon::now()->subDays(9)->subHours(18)],
                    ['from' => 'Di Workshop', 'to' => 'Approval Biaya', 'by' => $dodi?->id, 'time' => Carbon::now()->subDays(9)->subHours(10)],
                    ['from' => 'Approval Biaya', 'to' => 'Reparasi', 'by' => null, 'time' => Carbon::now()->subDays(8)],
                    ['from' => 'Reparasi', 'to' => 'Selesai', 'by' => $dodi?->id, 'time' => Carbon::now()->subDays(7)->subHours(6)],
                    ['from' => 'Selesai', 'to' => 'Diserahkan', 'by' => $admin?->id, 'time' => Carbon::now()->subDays(7)],
                    ['from' => 'Diserahkan', 'to' => 'Closed', 'by' => null, 'time' => Carbon::now()->subDays(6)],
                ],
            ],

            // Tiket 4: SC-2026-9035 | Tahap: Reparasi (DP Dibayar Konsumen di Awal)
            // Fix Gap: Pembayaran DP (Down Payment) dicatat resmi di payments & due_date
            [
                'ticket_no' => 'SC-2026-9035',
                'customer' => $custSiti,
                'technician' => $agus,
                'customer_type' => 'Personal',
                'company_name' => null,
                'crm_segment_snapshot' => 'Aktif',
                'lead_source' => 'Website Direct',
                'branch_code' => 'JKT-PST-01',
                'device_type' => 'Laptop',
                'device_brand' => 'HP',
                'device_model' => 'Pavilion Gaming 15-dk',
                'serial_number' => 'HP-PV15-DK0188',
                'issue_chronology' => 'Engsel kiri patah mengeluarkan serpihan plastik saat dibuka.',
                'addon_cleaning' => true,
                'addon_repasta' => false,
                'delivery_method' => 'Ambil oleh Teknisi',
                'handover_method' => null,
                'order_status' => 'Reparasi',
                'security_pin' => 'S4!P',
                'negotiation_status' => 'None',
                'items' => [],
                'diagnostic' => [
                    'symptom_category' => 'Chassis / Physical Breakage',
                    'fault_category' => 'Hinge Housing Bracket Fractured',
                    'physical_damage_analysis' => 'Kuningan baut hinge housing frame bottom case pecah, engsel macet.',
                    'action_plan' => 'Rekonstruksi kuningan baut resin epoxy tebal dan kalibrasi tegangan engsel.',
                    'labor_cost' => 175000,
                    'parts_cost' => 0,
                    'is_approved_by_user' => true,
                    'approved_at' => Carbon::now()->subHours(12),
                ],
                'payments' => [
                    [
                        'type' => 'DP',
                        'method' => 'QRIS Dinamis',
                        'amount' => 100000, // DP dibayar di muka Rp 100.000 (Sisa Outstanding Rp 125.000 saat pelunasan unit)
                        'time' => Carbon::now()->subHours(10),
                    ],
                ],
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subDay(),
                'history' => [
                    ['from' => null, 'to' => 'Booking', 'by' => null, 'time' => Carbon::now()->subDay()],
                    ['from' => 'Booking', 'to' => 'Di Workshop', 'by' => $admin?->id, 'time' => Carbon::now()->subHours(16)],
                    ['from' => 'Di Workshop', 'to' => 'Approval Biaya', 'by' => $agus?->id, 'time' => Carbon::now()->subHours(12)],
                    ['from' => 'Approval Biaya', 'to' => 'Reparasi', 'by' => null, 'time' => Carbon::now()->subHours(10)],
                ],
            ],

            // Tiket 5: SC-2026-9029 | Tahap: Selesai (Menunggu Pelunasan Kasir & Handover)
            [
                'ticket_no' => 'SC-2026-9029',
                'customer' => $custBayu,
                'technician' => $dodi,
                'customer_type' => 'Company',
                'company_name' => 'PT Surya Digital Corpora',
                'crm_segment_snapshot' => 'Setia / High Value',
                'lead_source' => 'Corporate B2B',
                'branch_code' => 'JKT-PST-01',
                'device_type' => 'PC Desktop',
                'device_brand' => 'Dell Optiplex',
                'device_model' => 'Tower 7080 Workstation',
                'serial_number' => 'DELL-OPT-7080-SDC',
                'issue_chronology' => 'PC sering mati mendadak saat render video proyek animasi studio.',
                'addon_cleaning' => false,
                'addon_repasta' => false,
                'delivery_method' => 'Diantar Sendiri',
                'handover_method' => null,
                'order_status' => 'Selesai',
                'security_pin' => 'B7#W',
                'negotiation_status' => 'None',
                'items' => [
                    [
                        'part' => $partPsu650,
                        'qty' => 1,
                        'unit_price' => 750000,
                        'cost_price' => 510000,
                    ],
                ],
                'diagnostic' => [
                    'symptom_category' => 'Power Supply Surge / Shutdown',
                    'fault_category' => 'PSU 12V Rail Capacitor Leaked',
                    'physical_damage_analysis' => 'Kapasitor rel 12V power supply bocor memicu OCP shut down otomatis.',
                    'action_plan' => 'Ganti PSU unit ke Aerocool 650W 80+ Bronze, rapikan cable management.',
                    'labor_cost' => 100000,
                    'parts_cost' => 750000,
                    'is_approved_by_user' => true,
                    'approved_at' => Carbon::now()->subHours(18),
                ],
                'payments' => [],
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subDays(2),
                'history' => [
                    ['from' => null, 'to' => 'Booking', 'by' => null, 'time' => Carbon::now()->subDays(2)],
                    ['from' => 'Booking', 'to' => 'Di Workshop', 'by' => $admin?->id, 'time' => Carbon::now()->subDays(1)->subHours(12)],
                    ['from' => 'Di Workshop', 'to' => 'Approval Biaya', 'by' => $dodi?->id, 'time' => Carbon::now()->subHours(18)],
                    ['from' => 'Approval Biaya', 'to' => 'Reparasi', 'by' => null, 'time' => Carbon::now()->subHours(14)],
                    ['from' => 'Reparasi', 'to' => 'Selesai', 'by' => $dodi?->id, 'time' => Carbon::now()->subHours(4)],
                ],
            ],

            // Tiket 6: SC-2026-9051 | Tahap: Approval Biaya
            // Fix Gap: Sparepart terhubung (BAT-ACER-SF3), parts_cost 450rb sinkron dengan 1x Baterai
            [
                'ticket_no' => 'SC-2026-9051',
                'customer' => $custClarissa,
                'technician' => $budi,
                'customer_type' => 'Personal',
                'company_name' => null,
                'crm_segment_snapshot' => 'Leads',
                'lead_source' => 'TikTok / Reels',
                'branch_code' => 'JKT-PST-01',
                'device_type' => 'Laptop',
                'device_brand' => 'Acer',
                'device_model' => 'Swift 3 SF314',
                'serial_number' => 'ACER-SF314-2201',
                'issue_chronology' => 'Baterai boros drastis dan touchpad sering loncat sendiri saat dicharge.',
                'addon_cleaning' => false,
                'addon_repasta' => false,
                'delivery_method' => 'Diantar Sendiri',
                'handover_method' => null,
                'order_status' => 'Approval Biaya',
                'security_pin' => 'C3*Q',
                'negotiation_status' => 'None',
                'items' => [
                    [
                        'part' => $partBatAcer,
                        'qty' => 1,
                        'unit_price' => 450000,
                        'cost_price' => 310000,
                    ],
                ],
                'diagnostic' => [
                    'symptom_category' => 'Battery Swelling / Degradation',
                    'fault_category' => 'Lithium Cell Bulging Pressing Trackpad',
                    'physical_damage_analysis' => 'Baterai internal kembung menekan trackpad dari bawah hingga klik mengeras.',
                    'action_plan' => 'Ganti baterai internal 4-cell baru OEM original dan kalibrasi grounding trackpad.',
                    'labor_cost' => 100000,
                    'parts_cost' => 450000,
                    'is_approved_by_user' => false,
                ],
                'payments' => [],
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subHours(5),
                'history' => [
                    ['from' => null, 'to' => 'Booking', 'by' => null, 'time' => Carbon::now()->subHours(5)],
                    ['from' => 'Booking', 'to' => 'Di Workshop', 'by' => $admin?->id, 'time' => Carbon::now()->subHours(4)],
                    ['from' => 'Di Workshop', 'to' => 'Approval Biaya', 'by' => $budi?->id, 'time' => Carbon::now()->subHours(2)],
                ],
            ],

            // Tiket 7: SC-2026-9060 | B2B Fleet 10 Unit PC (Upgrade RAM)
            // Fix Gap: QTY = 10 pcs RAM 16GB (10 x 580.000 = 5.800.000). Masuk akal!
            // Fix Gap: Nego disetujui 6.000.000 -> net_total invoice tersinkronisasi Rp 6.000.000
            [
                'ticket_no' => 'SC-2026-9060',
                'customer' => $custMega,
                'technician' => $dodi,
                'customer_type' => 'Company',
                'company_name' => 'PT Mega Solusi Nusantara',
                'crm_segment_snapshot' => 'Setia / High Value',
                'lead_source' => 'Corporate B2B',
                'branch_code' => 'JKT-PST-01',
                'device_type' => 'PC Desktop',
                'device_brand' => 'Dell Optiplex',
                'device_model' => '7090 Micro Fleet (10 Unit)',
                'serial_number' => 'MSN-FLEET-2024-01-10',
                'issue_chronology' => 'Paket perawatan 10 unit PC kantor: lambat saat buka aplikasi SAP, butuh upgrade RAM 16GB per unit.',
                'addon_cleaning' => true,
                'addon_repasta' => true,
                'delivery_method' => 'Diantar Sendiri',
                'handover_method' => null,
                'order_status' => 'Reparasi',
                'security_pin' => 'M8@K',
                'negotiation_status' => 'Approved',
                'negotiation_budget' => 6000000,
                'negotiation_notes' => 'Disetujui paket diskon volume korporasi total nett Rp 6.000.000.',
                'custom_net_total' => 6000000,
                'items' => [
                    [
                        'part' => $partRam16,
                        'qty' => 10, // 10 keping untuk 10 PC armada kantor!
                        'unit_price' => 580000,
                        'cost_price' => 390000,
                    ],
                ],
                'diagnostic' => [
                    'symptom_category' => 'Memory Starvation / Sluggish Fleet',
                    'fault_category' => 'Insufficient RAM Capacity Under Heavy Load',
                    'physical_damage_analysis' => 'RAM 8GB bawaan bottleneck 95% pemakaian SAP ERP. Heatsink kotor.',
                    'action_plan' => 'Instalasi 10 keping RAM 16GB, deep cleaning radiator exhaust, dan thermal repasta.',
                    'labor_cost' => 500000,
                    'parts_cost' => 5800000,
                    'is_approved_by_user' => true,
                    'approved_at' => Carbon::now()->subDays(1),
                ],
                'payments' => [
                    [
                        'type' => 'DP',
                        'method' => 'Transfer Bank / EDC',
                        'amount' => 3000000, // DP 50% B2B Corporate
                        'time' => Carbon::now()->subHours(16),
                    ],
                ],
                'due_date' => Carbon::now()->addDays(30), // B2B Termin NET 30
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subDays(3),
                'history' => [
                    ['from' => null, 'to' => 'Booking', 'by' => null, 'time' => Carbon::now()->subDays(3)],
                    ['from' => 'Booking', 'to' => 'Di Workshop', 'by' => $admin?->id, 'time' => Carbon::now()->subDays(2)],
                    ['from' => 'Di Workshop', 'to' => 'Approval Biaya', 'by' => $dodi?->id, 'time' => Carbon::now()->subDays(1)->subHours(10)],
                    ['from' => 'Approval Biaya', 'to' => 'Reparasi', 'by' => null, 'time' => Carbon::now()->subHours(16)],
                ],
            ],

            // Tiket 8: SC-2026-9077 | Booking Baru (Belum Di-assign)
            // Fix Gap: Konsistensi biaya add-on cleaning 50rb
            [
                'ticket_no' => 'SC-2026-9077',
                'customer' => $custFajar,
                'technician' => null,
                'customer_type' => 'Company',
                'company_name' => 'CV Techindo Creative Media',
                'crm_segment_snapshot' => 'Leads',
                'lead_source' => 'Corporate B2B',
                'branch_code' => 'JKT-PST-01',
                'device_type' => 'Laptop',
                'device_brand' => 'Apple',
                'device_model' => 'MacBook Pro 14 M1 Pro',
                'serial_number' => 'C02G99XXMD6R',
                'issue_chronology' => 'Keyboard tombol spacebar macet akibat ketumpahan cairan kopi tipis kemarin sore.',
                'addon_cleaning' => true,
                'addon_repasta' => false,
                'delivery_method' => 'Ambil oleh Teknisi',
                'pickup_address' => 'Gedung Cyber 2 Tower Lt. 15, Jl. HR Rasuna Said Jakarta Selatan.',
                'handover_method' => null,
                'order_status' => 'Booking',
                'security_pin' => 'F5!T',
                'negotiation_status' => 'None',
                'items' => [],
                'diagnostic' => null,
                'payments' => [],
                'unit_handed_over' => false,
                'created_at' => Carbon::now()->subHours(2),
                'history' => [
                    ['from' => null, 'to' => 'Booking', 'by' => null, 'time' => Carbon::now()->subHours(2)],
                ],
            ],
        ];

        foreach ($dataset as $od) {
            $hasSig = !empty($od['has_signature']) ? 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==' : null;

            $order = ServiceOrder::updateOrCreate(
                ['ticket_no' => $od['ticket_no']],
                [
                    'customer_id' => $od['customer']?->id,
                    'technician_id' => $od['technician']?->id,
                    'customer_type' => $od['customer_type'] ?? 'Personal',
                    'company_name' => $od['company_name'] ?? null,
                    'crm_segment_snapshot' => $od['crm_segment_snapshot'] ?? 'Leads',
                    'lead_source' => $od['lead_source'] ?? 'Website Direct',
                    'branch_code' => $od['branch_code'] ?? 'JKT-PST-01',
                    'device_type' => $od['device_type'],
                    'device_brand' => $od['device_brand'],
                    'device_model' => $od['device_model'],
                    'serial_number' => $od['serial_number'] ?? null,
                    'security_pin' => $od['security_pin'],
                    'issue_chronology' => $od['issue_chronology'],
                    'addon_cleaning' => $od['addon_cleaning'],
                    'addon_repasta' => $od['addon_repasta'],
                    'delivery_method' => $od['delivery_method'],
                    'pickup_address' => $od['pickup_address'] ?? null,
                    'order_status' => $od['order_status'],
                    'negotiation_status' => $od['negotiation_status'] ?? 'None',
                    'negotiation_budget' => $od['negotiation_budget'] ?? null,
                    'negotiation_notes' => $od['negotiation_notes'] ?? null,
                    'handover_method' => $od['handover_method'] ?? null,
                    'courier_name' => $od['courier_name'] ?? null,
                    'receiver_name' => $od['receiver_name'] ?? null,
                    'signature_data' => $hasSig,
                    'rating_score' => $od['rating_score'] ?? null,
                    'feedback_comments' => $od['feedback_comments'] ?? null,
                    'unit_handed_over' => $od['unit_handed_over'],
                    'handed_over_at' => $od['handed_over_at'] ?? null,
                    'created_at' => $od['created_at'],
                    'updated_at' => $od['created_at'],
                ]
            );

            // Diagnosa
            $diagData = $od['diagnostic'];
            $costCleaning = $od['addon_cleaning'] ? 50000 : 0;
            $costRepasta = $od['addon_repasta'] ? 75000 : 0;
            $laborCost = $diagData['labor_cost'] ?? 0;
            $partsCost = $diagData['parts_cost'] ?? 0;
            $calcNetTotal = $laborCost + $partsCost + $costCleaning + $costRepasta;
            $finalNetTotal = $od['custom_net_total'] ?? $calcNetTotal;

            if ($diagData) {
                Diagnostic::updateOrCreate(
                    ['service_order_id' => $order->id],
                    [
                        'symptom_category' => $diagData['symptom_category'] ?? null,
                        'fault_category' => $diagData['fault_category'] ?? null,
                        'physical_damage_analysis' => $diagData['physical_damage_analysis'] ?? null,
                        'action_plan' => $diagData['action_plan'] ?? null,
                        'labor_cost' => $laborCost,
                        'parts_cost' => $partsCost,
                        'addon_cleaning_cost' => $costCleaning,
                        'addon_repasta_cost' => $costRepasta,
                        'net_total_cost' => $finalNetTotal,
                        'is_approved_by_user' => $diagData['is_approved_by_user'] ?? false,
                        'approved_at' => $diagData['approved_at'] ?? null,
                        'created_at' => $od['created_at'],
                        'updated_at' => $od['created_at'],
                    ]
                );
            }

            // Order Items dengan Qty, Cost Price & Margin
            OrderItem::where('service_order_id', $order->id)->delete();
            if (!empty($od['items'])) {
                foreach ($od['items'] as $it) {
                    $part = $it['part'];
                    $qty = $it['qty'];
                    $unitPrice = $it['unit_price'];
                    $costPrice = $it['cost_price'];
                    $subtotal = $unitPrice * $qty;
                    $marginTotal = ($unitPrice - $costPrice) * $qty;

                    OrderItem::create([
                        'service_order_id' => $order->id,
                        'sparepart_id' => $part?->id,
                        'item_name' => $part ? $part->part_name : 'Komponen Replacement',
                        'quantity' => $qty,
                        'cost_price' => $costPrice,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                        'margin_total' => $marginTotal,
                        'created_at' => $od['created_at'],
                        'updated_at' => $od['created_at'],
                    ]);
                }
            }

            // Hitung status invoice dari payments & outstanding
            $totalPaymentsPaid = 0;
            if (!empty($od['payments'])) {
                foreach ($od['payments'] as $p) {
                    if (($p['type'] ?? '') !== 'REFUND') {
                        $totalPaymentsPaid += $p['amount'];
                    }
                }
            }
            $calculatedPayStatus = 'Unpaid';
            if ($totalPaymentsPaid > 0 && $totalPaymentsPaid < $finalNetTotal) {
                $calculatedPayStatus = 'Partial'; // DP telah masuk
            } elseif ($totalPaymentsPaid >= $finalNetTotal && $finalNetTotal > 0) {
                $calculatedPayStatus = 'Paid';
            }

            // Invoice
            $invoice = Invoice::updateOrCreate(
                ['service_order_id' => $order->id],
                [
                    'invoice_no' => 'INV-' . $order->ticket_no,
                    'amount_total' => $finalNetTotal,
                    'initial_estimate' => $finalNetTotal,
                    'due_date' => $od['due_date'] ?? null,
                    'payment_method' => !empty($od['payments']) ? $od['payments'][0]['method'] : 'QRIS Dinamis',
                    'payment_status' => $calculatedPayStatus,
                    'qris_transaction_id' => !empty($od['payments']) ? 'PAY-' . strtoupper(substr(md5($order->ticket_no), 0, 10)) : null,
                    'paid_at' => $calculatedPayStatus === 'Paid' ? (!empty($od['payments']) ? $od['payments'][0]['time'] : null) : null,
                    'created_at' => $od['created_at'],
                    'updated_at' => $od['created_at'],
                ]
            );

            // Payments (Multi-transaksi DP / Pelunasan)
            Payment::where('invoice_id', $invoice->id)->delete();
            if (!empty($od['payments'])) {
                foreach ($od['payments'] as $pIdx => $p) {
                    Payment::create([
                        'invoice_id' => $invoice->id,
                        'payment_reference' => 'TRX-' . $order->ticket_no . '-' . ($pIdx + 1),
                        'payment_type' => $p['type'],
                        'amount' => $p['amount'],
                        'payment_method' => $p['method'],
                        'status' => 'Success',
                        'paid_at' => $p['time'],
                        'received_by' => $admin?->id,
                        'notes' => 'Pembayaran terverifikasi sistem.',
                        'created_at' => $p['time'],
                        'updated_at' => $p['time'],
                    ]);
                }
            }

            // Status Histories (Audit Trail per Tahap)
            TicketStatusHistory::where('service_order_id', $order->id)->delete();
            if (!empty($od['history'])) {
                foreach ($od['history'] as $h) {
                    TicketStatusHistory::create([
                        'service_order_id' => $order->id,
                        'status_from' => $h['from'],
                        'status_to' => $h['to'],
                        'changed_by' => $h['by'],
                        'changed_at' => $h['time'],
                        'notes' => 'Status otomatis diperbarui ke ' . $h['to'],
                        'created_at' => $h['time'],
                        'updated_at' => $h['time'],
                    ]);
                }
            }

            // Garansi (Closed)
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
