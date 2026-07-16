<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create or update a default user
        User::updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ]);

        // Core demo data
        $this->call([
            SuppliersTableSeeder::class,
            MaterialCategoriesSeeder::class,
            RawMaterialsSeeder::class,
            ProductsSeeder::class,
            ProductionFormulasSeeder::class,
        ]);
    }
}
