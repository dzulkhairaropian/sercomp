<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Admin Workshop & Manajemen
        User::updateOrCreate(
            ['email' => 'admin@sercomp.id'],
            [
                'name' => 'Admin SerComp',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'tech_code' => 'ADM-2026-01',
                'phone' => '081122334455',
                'specialization' => 'Workshop Manager & Pricing Approver',
                'is_active' => true,
            ]
        );

        // 2. Akun Teknisi Workshop (Spesialisasi Beragam)
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
        ];

        foreach ($technicians as $tech) {
            User::updateOrCreate(['email' => $tech['email']], $tech);
        }

        // 3. Akun Pelanggan Personal & Korporasi (Bahan CRM & BI)
        $customers = [
            [
                'name' => 'Hendra Wijaya',
                'email' => 'hendra@gmail.com',
                'phone' => '081234567890',
                'customer_type' => 'Personal',
                'company_name' => null,
            ],
            [
                'name' => 'Rina Kartika',
                'email' => 'rina.kartika@yahoo.com',
                'phone' => '081399881122',
                'customer_type' => 'Personal',
                'company_name' => null,
            ],
            [
                'name' => 'Dimas Pratama',
                'email' => 'dimas.pratama@outlook.com',
                'phone' => '085622334455',
                'customer_type' => 'Personal',
                'company_name' => null,
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nur@gmail.com',
                'phone' => '081977665544',
                'customer_type' => 'Personal',
                'company_name' => null,
            ],
            [
                'name' => 'Bayu Wicaksono',
                'email' => 'bayu.wicak@perusahaan.co.id',
                'phone' => '081133445566',
                'customer_type' => 'Company',
                'company_name' => 'PT Surya Digital Corpora',
            ],
            [
                'name' => 'Bpk. Hendra Gunawan (IT Manager)',
                'email' => 'it.procurement@megacorp.co.id',
                'phone' => '081288990011',
                'customer_type' => 'Company',
                'company_name' => 'PT Mega Solusi Nusantara',
            ],
            [
                'name' => 'Clarissa Putri',
                'email' => 'clarissa.p@gmail.com',
                'phone' => '081211223377',
                'customer_type' => 'Personal',
                'company_name' => null,
            ],
            [
                'name' => 'Fajar Ramadhan',
                'email' => 'fajar.ramadhan@techindo.com',
                'phone' => '087855443322',
                'customer_type' => 'Company',
                'company_name' => 'CV Techindo Creative Media',
            ],
        ];

        foreach ($customers as $c) {
            User::updateOrCreate(
                ['phone' => $c['phone']],
                [
                    'name' => $c['name'],
                    'email' => $c['email'],
                    'password' => Hash::make('password123'),
                    'role' => 'customer',
                    'customer_type' => $c['customer_type'],
                    'company_name' => $c['company_name'],
                    'is_active' => true,
                ]
            );
        }
    }
}
