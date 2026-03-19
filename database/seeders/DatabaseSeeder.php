<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tax;
use App\Models\User;
use Database\Seeders\ProductTableSeeder;
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
        $this->call([
            ProductCategoryTableSeeder::class,
            ProductBrandTableSeeder::class,
            TaxTableSeeder::class,
            RoleTableSeeder::class,
            RolePermissionTableSeeder::class,
            UserTableSeeder::class,
            BarcodeTableSeeder::class,
            UnitTableSeeder::class,
            ProductAttributeTableSeeder::class,
            ProductAttributeOptionTableSeeder::class,
            ProductTableSeeder::class,
            ProductVariationTableSeeder::class,
        ]);
    }
}
