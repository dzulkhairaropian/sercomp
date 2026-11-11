<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Sparepart;
use Illuminate\Database\Seeder;

class SparepartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catFan = Category::where('slug', 'fan-cooling')->first()?->id;
        $catRam = Category::where('slug', 'ram-memory')->first()?->id;
        $catSsd = Category::where('slug', 'ssd-storage')->first()?->id;
        $catScreen = Category::where('slug', 'screen-display')->first()?->id;
        $catPsu = Category::where('slug', 'psu-power')->first()?->id;
        $catCable = Category::where('slug', 'cable-connectors')->first()?->id;
        $catBattery = Category::where('slug', 'battery-pack')->first()?->id;

        $spareparts = [
            // Fan & Cooling (Harga Jual & Harga Modal untuk BI Margin)
            [
                'part_code' => 'FAN-ASUS-G15',
                'part_name' => 'Modul Dual Fan Cooler Original Asus ROG Strix G15',
                'category' => 'Fan',
                'category_id' => $catFan,
                'stock_qty' => 6,
                'cost_price' => 145000,
                'unit_price' => 220000,
            ],
            [
                'part_code' => 'FAN-ACER-N5',
                'part_name' => 'Kipas Pendingin CPU/GPU Acer Nitro 5 AN515',
                'category' => 'Fan',
                'category_id' => $catFan,
                'stock_qty' => 4,
                'cost_price' => 130000,
                'unit_price' => 195000,
            ],
            [
                'part_code' => 'FAN-LEN-L5',
                'part_name' => 'Cooling Fan Blower Lenovo Legion 5 15ACH6',
                'category' => 'Fan',
                'category_id' => $catFan,
                'stock_qty' => 5,
                'cost_price' => 160000,
                'unit_price' => 240000,
            ],

            // RAM & Memory
            [
                'part_code' => 'RAM-DDR4-8GB',
                'part_name' => 'RAM DDR4 SODIMM 8GB Kingston 3200MHz CL22',
                'category' => 'RAM',
                'category_id' => $catRam,
                'stock_qty' => 14,
                'cost_price' => 210000,
                'unit_price' => 320000,
            ],
            [
                'part_code' => 'RAM-DDR4-16GB',
                'part_name' => 'RAM DDR4 SODIMM 16GB Corsair Vengeance 3200MHz',
                'category' => 'RAM',
                'category_id' => $catRam,
                'stock_qty' => 8,
                'cost_price' => 390000,
                'unit_price' => 580000,
            ],
            [
                'part_code' => 'RAM-DDR5-16GB',
                'part_name' => 'RAM DDR5 SODIMM 16GB Crucial 4800MHz 1.1V',
                'category' => 'RAM',
                'category_id' => $catRam,
                'stock_qty' => 5,
                'cost_price' => 520000,
                'unit_price' => 750000,
            ],

            // SSD Storage
            [
                'part_code' => 'SSD-NVME-512',
                'part_name' => 'SSD M.2 NVMe PCIe Gen 3 512GB Kingston NV1',
                'category' => 'SSD',
                'category_id' => $catSsd,
                'stock_qty' => 10,
                'cost_price' => 360000,
                'unit_price' => 520000,
            ],
            [
                'part_code' => 'SSD-NVME-1TB',
                'part_name' => 'SSD M.2 NVMe PCIe Gen 4 1TB Samsung 980 Pro Heatsink',
                'category' => 'SSD',
                'category_id' => $catSsd,
                'stock_qty' => 6,
                'cost_price' => 980000,
                'unit_price' => 1350000,
            ],
            [
                'part_code' => 'SSD-SATA-500',
                'part_name' => 'SSD 2.5 Inch SATA III 500GB Crucial MX500',
                'category' => 'SSD',
                'category_id' => $catSsd,
                'stock_qty' => 7,
                'cost_price' => 330000,
                'unit_price' => 475000,
            ],

            // Screen & Display
            [
                'part_code' => 'LCD-156-FHD',
                'part_name' => 'Panel Layar LCD 15.6 Inch IPS FHD 144Hz Slim 40-Pin',
                'category' => 'Screen',
                'category_id' => $catScreen,
                'stock_qty' => 4,
                'cost_price' => 590000,
                'unit_price' => 850000,
            ],
            [
                'part_code' => 'LCD-140-FHD',
                'part_name' => 'Panel Layar LCD 14.0 Inch IPS FHD 60Hz Slim 30-Pin',
                'category' => 'Screen',
                'category_id' => $catScreen,
                'stock_qty' => 3,
                'cost_price' => 490000,
                'unit_price' => 720000,
            ],

            // PSU & Power Supply
            [
                'part_code' => 'PSU-650W-80P',
                'part_name' => 'Power Supply Unit 650W 80+ Bronze Aerocool Lux',
                'category' => 'PSU',
                'category_id' => $catPsu,
                'stock_qty' => 5,
                'cost_price' => 510000,
                'unit_price' => 750000,
            ],
            [
                'part_code' => 'PSU-750W-GOLD',
                'part_name' => 'Power Supply Unit 750W 80+ Gold Fully Modular Corsair RM750e',
                'category' => 'PSU',
                'category_id' => $catPsu,
                'stock_qty' => 2,
                'cost_price' => 1100000,
                'unit_price' => 1550000,
            ],

            // Cable & Connectors
            [
                'part_code' => 'CAB-EDP-30PIN',
                'part_name' => 'Kabel Flexible Video LVDS / eDP 30-Pin Slim Screen',
                'category' => 'Cable',
                'category_id' => $catCable,
                'stock_qty' => 8,
                'cost_price' => 55000,
                'unit_price' => 110000,
            ],
            [
                'part_code' => 'CAB-DC-JACK',
                'part_name' => 'Kabel Konektor DC Power Jack Harness Asus TUF Gaming',
                'category' => 'Cable',
                'category_id' => $catCable,
                'stock_qty' => 9,
                'cost_price' => 40000,
                'unit_price' => 85000,
            ],

            // Battery Replacement
            [
                'part_code' => 'BAT-ACER-SF3',
                'part_name' => 'Baterai Internal Acer Swift 3 AP18C8K 4-Cell 4200mAh',
                'category' => 'Cable',
                'category_id' => $catBattery,
                'stock_qty' => 5,
                'cost_price' => 310000,
                'unit_price' => 450000,
            ],
        ];

        foreach ($spareparts as $sp) {
            Sparepart::updateOrCreate(['part_code' => $sp['part_code']], $sp);
        }
    }
}
