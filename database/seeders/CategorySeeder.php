<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Kategori Suku Cadang Hardware
            [
                'name' => 'Fan & Cooling System',
                'slug' => 'fan-cooling',
                'type' => 'sparepart',
                'description' => 'Modul kipas pendingin, heatsink tembaga, dan kipas exhaust laptop/PC.',
            ],
            [
                'name' => 'RAM & Memory',
                'slug' => 'ram-memory',
                'type' => 'sparepart',
                'description' => 'Memori DDR4/DDR5 SODIMM Laptop dan Longdimm Desktop PC.',
            ],
            [
                'name' => 'SSD & Solid State Drive',
                'slug' => 'ssd-storage',
                'type' => 'sparepart',
                'description' => 'Media penyimpanan NVMe PCIe Gen3/Gen4 dan SSD SATA 2.5 inci.',
            ],
            [
                'name' => 'Screen & Display Panel',
                'slug' => 'screen-display',
                'type' => 'sparepart',
                'description' => 'Panel LCD/LED IPS 60Hz-165Hz slim 30-pin/40-pin.',
            ],
            [
                'name' => 'PSU & Power Supply',
                'slug' => 'psu-power',
                'type' => 'sparepart',
                'description' => 'Unit power supply tier 80+ Bronze/Gold dan adaptor charger OEM.',
            ],
            [
                'name' => 'Cable & Connectors',
                'slug' => 'cable-connectors',
                'type' => 'sparepart',
                'description' => 'Kabel fleksibel eDP/LVDS, DC jack socket, dan flat ribbon cable.',
            ],
            [
                'name' => 'Motherboard & IC Power',
                'slug' => 'motherboard-ic',
                'type' => 'sparepart',
                'description' => 'Komponen micro-soldering, mosfet, IC charger, dan bios chip.',
            ],
            [
                'name' => 'Battery & Cell Pack',
                'slug' => 'battery-pack',
                'type' => 'sparepart',
                'description' => 'Baterai internal lithium-ion replacement bergaransi resmi.',
            ],
            [
                'name' => 'Keyboard & Trackpad',
                'slug' => 'keyboard-input',
                'type' => 'sparepart',
                'description' => 'Keyboard backlit laptop dan modul touchpad precision.',
            ],

            // Kategori Jenis Perangkat (BI Device Segments)
            [
                'name' => 'Laptop Gaming & Workstation',
                'slug' => 'laptop-gaming',
                'type' => 'device',
                'description' => 'Perangkat performa tinggi kebutuhan gaming, CAD, dan rendering.',
            ],
            [
                'name' => 'Laptop Ultrabook & Consumer',
                'slug' => 'laptop-consumer',
                'type' => 'device',
                'description' => 'Laptop tipis kebutuhan kantor, kuliah, dan mobilitas harian.',
            ],
            [
                'name' => 'PC Desktop Gaming & Creator',
                'slug' => 'pc-gaming',
                'type' => 'device',
                'description' => 'Custom build PC gaming dan desktop studio konten kreator.',
            ],
            [
                'name' => 'PC Office & Workstation Corporate',
                'slug' => 'pc-office',
                'type' => 'device',
                'description' => 'Unit PC all-in-one dan mini PC perkantoran.',
            ],

            // Kategori Layanan Servis (BI Service Tracks)
            [
                'name' => 'Perbaikan Hardware & IC',
                'slug' => 'service-hardware',
                'type' => 'service',
                'description' => 'Penggantian part fisik dan reparasi sirkuit motherboard.',
            ],
            [
                'name' => 'Maintenance & Thermal Overhaul',
                'slug' => 'service-maintenance',
                'type' => 'service',
                'description' => 'Deep cleaning debu dan penggantian pasta termal performa tinggi.',
            ],
            [
                'name' => 'Upgrade Storage & Memory',
                'slug' => 'service-upgrade',
                'type' => 'service',
                'description' => 'Instalasi ekspansi RAM dan cloning sistem operasi ke SSD.',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
