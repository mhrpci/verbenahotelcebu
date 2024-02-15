<?php

namespace Database\Seeders;

use Botble\Hotel\Models\Amenity;
use Botble\Base\Supports\BaseSeeder;
use Illuminate\Support\Facades\Artisan;

class AmenitySeeder extends BaseSeeder
{
    public function run()
    {
        Amenity::truncate();

        $amenities = [
            [
                'name' => 'Air conditioner',
                'icon' => 'fal fa-bath',
            ],
            [
                'name' => 'High speed WiFi',
                'icon' => 'fal fa-wifi',
            ],
            [
                'name' => 'Strong Locker',
                'icon' => 'fal fa-key',
            ],
            [
                'name' => 'Breakfast',
                'icon' => 'fal fa-cut',
            ],
            [
                'name' => 'Kitchen',
                'icon' => 'fal fa-guitar',
            ],
            [
                'name' => 'Smart Security',
                'icon' => 'fal fa-lock',
            ],
            [
                'name' => 'Cleaning',
                'icon' => 'fal fa-broom',
            ],
            [
                'name' => 'Shower',
                'icon' => 'fal fa-shower',
            ],
            [
                'name' => '24/7 Online Support',
                'icon' => 'fal fa-headphones-alt',
            ],
            [
                'name' => 'Grocery',
                'icon' => 'fal fa-shopping-basket',
            ],
            [
                'name' => 'Single bed',
                'icon' => 'fal fa-bed',
            ],
            [
                'name' => 'Expert Team',
                'icon' => 'fal fa-users',
            ],
            [
                'name' => 'Shop near',
                'icon' => 'fal fa-shopping-cart',
            ],
            [
                'name' => 'Towels',
                'icon' => 'fal fa-bus',
            ],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            Artisan::call('cms:language:sync', ['class' => Amenity::class]);
        }
    }
}
