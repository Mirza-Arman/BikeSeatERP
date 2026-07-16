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
                'supplier_code' => 'SUP-001',
                'company_name' => 'Acme Supplies Ltd',
                'contact_person' => 'John Doe',
                'phone' => '555-0100',
                'email' => 'sales@acme.example',
                'address' => '123 Industrial Ave',
                'city' => 'Lahore',
                'balance' => 0,
                'status' => 'active',
            ],
            [
                'supplier_code' => 'SUP-002',
                'company_name' => 'RawParts Co',
                'contact_person' => 'Jane Smith',
                'phone' => '555-0111',
                'email' => 'info@rawparts.example',
                'address' => '45 Supply Rd',
                'city' => 'Karachi',
                'balance' => 0,
                'status' => 'active',
            ],
        ];

        foreach ($suppliers as $s) {
            Supplier::updateOrCreate(['supplier_code' => $s['supplier_code']], $s);
        }
    }
}
