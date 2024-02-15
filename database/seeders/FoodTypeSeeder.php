<?php

namespace Database\Seeders;

use Botble\Hotel\Models\FoodType;
use Botble\Base\Supports\BaseSeeder;
use Illuminate\Support\Facades\Artisan;

class FoodTypeSeeder extends BaseSeeder
{
    public function run()
    {
        FoodType::truncate();

        $foodTypes = [
            [
                'name' => 'Chicken',
                'icon' => 'flaticon-boiled',
            ],
            [
                'name' => 'Italian',
                'icon' => 'flaticon-pizza',
            ],
            [
                'name' => 'Coffee',
                'icon' => 'flaticon-coffee',
            ],
            [
                'name' => 'Bake Cake',
                'icon' => 'flaticon-cake',
            ],
            [
                'name' => 'Cookies',
                'icon' => 'flaticon-cookie',
            ],
            [
                'name' => 'Cocktail',
                'icon' => 'flaticon-cocktail',
            ],
        ];

        foreach ($foodTypes as $foodType) {
            FoodType::create($foodType);
        }

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            Artisan::call('cms:language:sync', ['class' => FoodType::class]);
        }
    }
}
