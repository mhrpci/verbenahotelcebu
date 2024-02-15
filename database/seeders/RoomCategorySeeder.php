<?php

namespace Database\Seeders;

use Botble\Hotel\Models\RoomCategory;
use Botble\Base\Supports\BaseSeeder;
use Illuminate\Support\Facades\Artisan;

class RoomCategorySeeder extends BaseSeeder
{
    public function run()
    {
        RoomCategory::truncate();

        $roomCategories = [
            [
                'name' => 'Luxury',
            ],
            [
                'name' => 'Family',
            ],
            [
                'name' => 'Double Bed',
            ],
            [
                'name' => 'Relax',
            ],
        ];

        foreach ($roomCategories as $roomCategory) {
            RoomCategory::create($roomCategory);
        }

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            Artisan::call('cms:language:sync', ['class' => RoomCategory::class]);
        }
    }
}
