<?php

namespace Database\Seeders;

use App\Models\MaterialCategory;
use Illuminate\Database\Seeder;

class MaterialCategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Foam'],
            ['name' => 'Leather'],
            ['name' => 'Metal'],
        ];

        foreach ($categories as $c) {
            MaterialCategory::updateOrCreate(['name' => $c['name']], $c);
        }
    }
}
