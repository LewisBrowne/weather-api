<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        User::create([
            'first_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),
            'email' => 'test@test.com',
            'password' => bcrypt('password123!'),
            'guid' => Str::uuid()->toString()
        ]);
    }
}


