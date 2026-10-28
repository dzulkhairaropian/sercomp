<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Sparepart;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SercompDatabaseSeeder extends Seeder
{
    /**
     * Seed master data: Akun Teknisi, Stok Suku Cadang, dan Katalog Layanan.
     * Tidak menyertakan data tiket dummy agar sistem murni menggunakan input pengguna.
     */
    public function run(): void
    {
        // =========================================================================
        // 1. MASTER AKUN TEKNISI & STAF WORKSHOP
        // =========================================================================
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

        // =========================================================================
        // 2. MASTER KATALOG LAYANAN & ADD-ON
        // =========================================================================
        $services = [
            // Add-ons Perawatan Cepat
            [
                'name' => 'Deep Cleaning & Dust Removal',
                'category' => 'maintenance',
                'price' => 50000,
                'description' => 'Pembersihan debu kerak pada sirip heatsink pendingin, exhaust, dan motherboard.',
                'is_addon' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Repasta Thermal Arctic MX-4',
                'category' => 'maintenance',
                'price' => 75000,
                'description' => 'Pelumasan ulang pasta pendingin CPU & GPU dengan Arctic MX-4 berkualitas tinggi.',
                'is_addon' => true,
                'is_active' => true,
            ],
            // Jasa Utama
            [
                'name' => 'Jasa Perbaikan Motherboard & Power IC',
                'category' => 'hardware',
                'price' => 250000,
                'description' => 'Penanganan korsleting jalur VCore 19V, solder ulang IC regulator, dan reballing chip.',
                'is_addon' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Jasa Penggantian Modul Fan Cooler',
                'category' => 'hardware',
                'price' => 150000,
                'description' => 'Bongkar pasang modul pendingin heatsink dan uji running test suhu 60 menit.',
                'is_addon' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Jasa Penggantian Layar Panel LCD',
                'category' => 'hardware',
                'price' => 125000,
                'description' => 'Penggantian panel layar LCD slim/IPS bebas dead pixel dan uji refresh rate.',
                'is_addon' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Jasa Ganti Keyboard Laptop',
                'category' => 'hardware',
                'price' => 100000,
                'description' => 'Penggantian keyboard tanam/backlight dan uji respons seluruh tuts keyboard.',
                'is_addon' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Jasa Upgrade SSD / RAM + Kloning OS',
                'category' => 'hardware',
                'price' => 75000,
                'description' => 'Pemasangan SSD NVMe/RAM baru dan kloning sistem operasi tanpa menghilangkan data.',
                'is_addon' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Rakit PC Desktop Custom & Cable Management',
                'category' => 'custom_pc',
                'price' => 150000,
                'description' => 'Perakitan unit PC kantor/gaming, cable management rapi, setting BIOS, dan stress test.',
                'is_addon' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Instalasi Bersih OS Windows 11 / Linux',
                'category' => 'software',
                'price' => 80000,
                'description' => 'Instalasi bersih sistem operasi resmi, driver official vendor, dan aplikasi standar.',
                'is_addon' => false,
                'is_active' => true,
            ],
        ];

        foreach ($services as $srv) {
            Service::updateOrCreate(['name' => $srv['name']], $srv);
        }

        // =========================================================================
        // 3. MASTER STOK SUKU CADANG (SPAREPARTS WORKSHOP)
        // =========================================================================
        $spareparts = [
            // Cooling
            [
                'part_code' => 'FAN-ASUS-G15',
                'name' => 'Modul Fan Cooler Original Asus ROG Strix G15',
                'category' => 'cooling',
                'stock' => 6,
                'cost_price' => 160000,
                'selling_price' => 220000,
                'min_stock_alert' => 3,
                'is_active' => true,
            ],
            [
                'part_code' => 'FAN-LENOVO-LEGION',
                'name' => 'Modul Dual Fan Cooler Lenovo Legion 5 Series',
                'category' => 'cooling',
                'stock' => 5,
                'cost_price' => 175000,
                'selling_price' => 240000,
                'min_stock_alert' => 2,
                'is_active' => true,
            ],
            [
                'part_code' => 'FAN-ACER-NITRO',
                'name' => 'Modul Fan Cooler Acer Nitro 5 (AN515)',
                'category' => 'cooling',
                'stock' => 4,
                'cost_price' => 140000,
                'selling_price' => 195000,
                'min_stock_alert' => 2,
                'is_active' => true,
            ],

            // Thermal Compound
            [
                'part_code' => 'TH-ARCTIC-MX4',
                'name' => 'Thermal Paste Arctic MX-4 High-Performance (4g)',
                'category' => 'chemical',
                'stock' => 20,
                'cost_price' => 45000,
                'selling_price' => 75000,
                'min_stock_alert' => 5,
                'is_active' => true,
            ],
            [
                'part_code' => 'TH-GRIZZLY-KRYO',
                'name' => 'Thermal Grizzly Kryonaut Ultra High-End (1g)',
                'category' => 'chemical',
                'stock' => 8,
                'cost_price' => 75000,
                'selling_price' => 120000,
                'min_stock_alert' => 3,
                'is_active' => true,
            ],

            // Storage SSD
            [
                'part_code' => 'SSD-NVME-256',
                'name' => 'SSD M.2 NVMe PCIe Gen 3 Kingston NV1 256GB',
                'category' => 'storage',
                'stock' => 10,
                'cost_price' => 260000,
                'selling_price' => 340000,
                'min_stock_alert' => 3,
                'is_active' => true,
            ],
            [
                'part_code' => 'SSD-NVME-512',
                'name' => 'SSD M.2 NVMe PCIe Gen 4 Kingston NV2 512GB',
                'category' => 'storage',
                'stock' => 12,
                'cost_price' => 450000,
                'selling_price' => 580000,
                'min_stock_alert' => 4,
                'is_active' => true,
            ],
            [
                'part_code' => 'SSD-NVME-1TB',
                'name' => 'SSD M.2 NVMe PCIe Gen 4 Kingston NV2 1TB',
                'category' => 'storage',
                'stock' => 7,
                'cost_price' => 820000,
                'selling_price' => 990000,
                'min_stock_alert' => 2,
                'is_active' => true,
            ],

            // Memory RAM
            [
                'part_code' => 'RAM-DDR4-8GB',
                'name' => 'RAM SODIMM DDR4 8GB 3200MHz Kingston Value',
                'category' => 'memory',
                'stock' => 15,
                'cost_price' => 220000,
                'selling_price' => 310000,
                'min_stock_alert' => 5,
                'is_active' => true,
            ],
            [
                'part_code' => 'RAM-DDR4-16GB',
                'name' => 'RAM SODIMM DDR4 16GB 3200MHz Kingston Value',
                'category' => 'memory',
                'stock' => 8,
                'cost_price' => 390000,
                'selling_price' => 490000,
                'min_stock_alert' => 3,
                'is_active' => true,
            ],
            [
                'part_code' => 'RAM-DDR5-16GB',
                'name' => 'RAM SODIMM DDR5 16GB 4800MHz Crucial Micron',
                'category' => 'memory',
                'stock' => 6,
                'cost_price' => 580000,
                'selling_price' => 720000,
                'min_stock_alert' => 2,
                'is_active' => true,
            ],

            // Input Device Keyboard
            [
                'part_code' => 'KB-ASUS-VIVO',
                'name' => 'Keyboard Laptop Asus Vivobook A412 Series (Black)',
                'category' => 'input_device',
                'stock' => 4,
                'cost_price' => 120000,
                'selling_price' => 190000,
                'min_stock_alert' => 2,
                'is_active' => true,
            ],
            [
                'part_code' => 'KB-LENOVO-IDEAPAD',
                'name' => 'Keyboard Laptop Lenovo Ideapad Slim 3 Series',
                'category' => 'input_device',
                'stock' => 3,
                'cost_price' => 135000,
                'selling_price' => 200000,
                'min_stock_alert' => 2,
                'is_active' => true,
            ],

            // Display LCD
            [
                'part_code' => 'LCD-14-IPS-30P',
                'name' => 'Layar Panel LCD 14.0 Slim FHD IPS 30 Pin Non-Touch',
                'category' => 'display',
                'stock' => 3,
                'cost_price' => 580000,
                'selling_price' => 750000,
                'min_stock_alert' => 2,
                'is_active' => true,
            ],
            [
                'part_code' => 'LCD-156-144HZ',
                'name' => 'Layar Panel LCD 15.6 FHD IPS 144Hz 40 Pin Gaming',
                'category' => 'display',
                'stock' => 2,
                'cost_price' => 780000,
                'selling_price' => 980000,
                'min_stock_alert' => 2,
                'is_active' => true,
            ],

            // Power Adaptor
            [
                'part_code' => 'ADAP-TYPE-C-65W',
                'name' => 'Adaptor Charger Universal 65W USB Type-C Original',
                'category' => 'power',
                'stock' => 8,
                'cost_price' => 190000,
                'selling_price' => 270000,
                'min_stock_alert' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($spareparts as $part) {
            Sparepart::updateOrCreate(['part_code' => $part['part_code']], $part);
        }
    }
}
