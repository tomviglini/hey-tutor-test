<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $faker->seed(1);

        $products = [];
        for($i = 0; $i < 100; $i++) {
            $products[] = [
                'name'  => $faker->unique()->word(),
                'price' => $faker->randomFloat(2, 1, 50000)
            ];
        }
        Product::insert($products);
    }
}
