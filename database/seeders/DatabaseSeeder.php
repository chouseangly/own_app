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
        $this->call([
            ProductCategoryTableSeeder::class,
            RoleTableSeeder::class, // Create roles first
            RolePermissionTableSeeder::class, // Then assign permissions
            UserTableSeeder::class // Finally create users and assign roles
        ]);
    }
}
