<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database in clean, sequential order.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,     // 1. Master Data Kategori (Sparepart, Device, Service)
            UserSeeder::class,         // 2. Akun Internal (Admin & Teknisi) & Pelanggan (Personal & B2B)
            SparepartSeeder::class,    // 3. Master Data Suku Cadang Terkategori
            ServiceOrderSeeder::class, // 4. Dataset Transaksional Servis, Diagnosa, Invoice & BI CRM
        ]);
    }
}
