<?php

namespace Database\Seeders;

use Botble\Hotel\Models\Feature;
use Botble\Base\Supports\BaseSeeder;
use Illuminate\Support\Facades\Artisan;

class FeatureSeeder extends BaseSeeder
{
    public function run()
    {
        Feature::truncate();

        $features = [
            [
                'name'        => 'Have High Rating',
                'icon'        => 'flaticon-rating',
                'is_featured' => true,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            ],
            [
                'name'        => 'Quiet Hours',
                'icon'        => 'flaticon-clock',
                'is_featured' => true,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            ],
            [
                'name'        => 'Best Locations',
                'icon'        => 'flaticon-location-pin',
                'is_featured' => true,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            ],
            [
                'name'        => 'Free Cancellation',
                'icon'        => 'flaticon-clock-1',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            ],
            [
                'name'        => 'Payment Options',
                'icon'        => 'flaticon-credit-card',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            ],
            [
                'name'        => 'Special Offers',
                'icon'        => 'flaticon-discount',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            ],
        ];

        foreach ($features as $feature) {
            Feature::create($feature);
        }

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            Artisan::call('cms:language:sync', ['class' => Feature::class]);
        }
    }
}
