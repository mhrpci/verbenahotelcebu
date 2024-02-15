<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;

class DatabaseSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->activateAllPlugins();

        $this->call(CurrencySeeder::class);
        $this->call(AmenitySeeder::class);
        $this->call(FoodTypeSeeder::class);
        $this->call(RoomCategorySeeder::class);
        $this->call(RoomSeeder::class);
        $this->call(FoodTypeSeeder::class);
        $this->call(FoodSeeder::class);
        $this->call(FeatureSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(PlaceSeeder::class);
        $this->call(TaxSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(MenuSeeder::class);

        $this->uploadFiles('galleries');
        $this->uploadFiles('general');
        $this->uploadFiles('news');
        $this->uploadFiles('sliders');
        $this->uploadFiles('testimonials');
    }
}
