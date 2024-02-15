<?php

namespace Database\Seeders;

use Botble\Hotel\Models\Service;
use Botble\Base\Supports\BaseSeeder;
use Illuminate\Support\Facades\Artisan;

class ServiceSeeder extends BaseSeeder
{
    public function run()
    {
        Service::truncate();

        $services = [
            [
                'name'        => 'Wifi',
                'price'       => 100,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel molestie nisl. Duis ac mi leo.',
            ],
            [
                'name'        => 'Car Rental',
                'price'       => 30,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel molestie nisl. Duis ac mi leo.',
            ],
            [
                'name'        => 'Satellite TV',
                'price'       => 50,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel molestie nisl. Duis ac mi leo.',
            ],
            [
                'name'        => 'Sea View',
                'price'       => 10,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel molestie nisl. Duis ac mi leo.',
            ],
            [
                'name'        => 'Laundry',
                'price'       => 10,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel molestie nisl. Duis ac mi leo.',
            ],
            [
                'name'        => 'Breakfast',
                'price'       => 10,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel molestie nisl. Duis ac mi leo.',
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            Artisan::call('cms:language:sync', ['class' => Service::class]);
        }
    }
}
