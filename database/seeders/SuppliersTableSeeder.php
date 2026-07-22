<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SuppliersTableSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'supplier_code' => 'SUP-000001',
                'company_name' => 'Karachi Foam Industries',
                'contact_person' => 'Ahmed Khan',
                'phone' => '0300-1234567',
                'whatsapp' => '0300-1234567',
                'email' => 'sales@karachifoam.pk',
                'address' => 'Industrial Estate, F.B Area',
                'city' => 'Karachi',
                'balance' => 150000.00,
                'status' => 'active',
                'notes' => 'Primary foam supplier for motorcycle seats',
            ],
            [
                'supplier_code' => 'SUP-000002',
                'company_name' => 'Lahore Leather Works',
                'contact_person' => 'Muhammad Ali',
                'phone' => '0321-7654321',
                'whatsapp' => '0321-7654321',
                'email' => 'info@lahoreleather.pk',
                'address' => 'Leather Market, Badami Bagh',
                'city' => 'Lahore',
                'balance' => 85000.00,
                'status' => 'active',
                'notes' => 'Premium synthetic leather supplier',
            ],
            [
                'supplier_code' => 'SUP-000003',
                'company_name' => 'Faisalabad Steel Traders',
                'contact_person' => 'Hassan Raza',
                'phone' => '0333-9876543',
                'whatsapp' => '0333-9876543',
                'email' => 'steel@faslabadtraders.pk',
                'address' => 'Steel Market, Peoples Colony',
                'city' => 'Faisalabad',
                'balance' => 45000.00,
                'status' => 'active',
                'notes' => 'Metal frames and springs supplier',
            ],
            [
                'supplier_code' => 'SUP-000004',
                'company_name' => 'Multan Chemicals Ltd',
                'contact_person' => 'Saima Ahmed',
                'phone' => '0345-1112223',
                'whatsapp' => '0345-1112223',
                'email' => 'chemicals@multanltd.pk',
                'address' => 'Chemical Zone, Industrial Estate',
                'city' => 'Multan',
                'balance' => 25000.00,
                'status' => 'active',
                'notes' => 'Adhesives and bonding agents',
            ],
            [
                'supplier_code' => 'SUP-000005',
                'company_name' => 'Rawalpindi Fabrics Co',
                'contact_person' => 'Bilal Malik',
                'phone' => '0312-4445556',
                'whatsapp' => '0312-4445556',
                'email' => 'fabrics@rawalpindico.pk',
                'address' => 'Textile Market, Raja Bazaar',
                'city' => 'Rawalpindi',
                'balance' => 0,
                'status' => 'inactive',
                'notes' => 'Backup fabric supplier - currently inactive',
            ],
        ];

        foreach ($suppliers as $s) {
            Supplier::updateOrCreate(['supplier_code' => $s['supplier_code']], $s);
        }
    }
}
