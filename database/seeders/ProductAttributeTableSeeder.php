<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;

class ProductAttributeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public array $fashionAttributes = [
        'Color',
        'Size'
    ];

    public function run()
    {
            foreach ($this->fashionAttributes as $fashionAttribute) {
                ProductAttribute::create([
                    'name'   => $fashionAttribute,
                ]);
            }
    }
}
