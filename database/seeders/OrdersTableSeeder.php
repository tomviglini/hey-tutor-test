<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Order;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $faker->seed(3);

        $orders = [];
        for($i = 1; $i <= 100; $i++) {
            for($n = 0; $n < $faker->randomDigitNotNull(); $n++) {
                $orders[] = [
                    'user_id'  => $i,
                    'product_id' => $faker->numberBetween(1, 100),
                    'quantity' => $faker->numberBetween(1, 10),
                    'total_amount' => $faker->randomFloat(2, 1, 50000)
                ];
            }
        }
        Order::insert($orders);
    }
}
