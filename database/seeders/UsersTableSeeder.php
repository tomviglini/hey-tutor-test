<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $faker->seed(2);

        $users = [];
        for($i = 0; $i < 100; $i++) {
            $users[] = [
                'name'  => $faker->name(),
                'email' => $faker->unique()->safeEmail
            ];
        }
        User::insert($users);
    }
}
