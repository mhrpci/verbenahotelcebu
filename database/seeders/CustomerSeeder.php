<?php

namespace Database\Seeders;

use Botble\Hotel\Models\Customer;
use Faker\Factory;
use Botble\Base\Supports\BaseSeeder;

class CustomerSeeder extends BaseSeeder
{
    public function run()
    {
        Customer::truncate();

        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            Customer::create([
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
                'email'      => $faker->safeEmail,
                'phone'      => $faker->e164PhoneNumber,
            ]);
        }
    }
}
