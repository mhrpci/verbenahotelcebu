<?php

namespace Database\Seeders;

use Botble\Hotel\Models\Place;
use Botble\Slug\Models\Slug;
use Faker\Factory;
use Botble\Base\Supports\BaseSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use SlugHelper;

class PlaceSeeder extends BaseSeeder
{
    public function run()
    {
        $this->uploadFiles('places');

        Place::truncate();

        $faker = Factory::create();

        $places = [
            [
                'name'     => 'Duplex Restaurant',
                'distance' => '1,500m | 21 min. Walk',
                'content'  => $faker->realText(1000),
                'image'    => 'places/01.jpg',
            ],
            [
                'name'     => 'Duplex Restaurant',
                'distance' => '1,500m | 21 min. Walk',
                'content'  => $faker->realText(1000),
                'image'    => 'places/02.jpg',
            ],
            [
                'name'     => 'Duplex Restaurant',
                'distance' => '1,500m | 21 min. Walk',
                'content'  => $faker->realText(1000),
                'image'    => 'places/03.jpg',
            ],
            [
                'name'     => 'Duplex Restaurant',
                'distance' => '1,500m | 21 min. Walk',
                'content'  => $faker->realText(1000),
                'image'    => 'places/04.jpg',
            ],
            [
                'name'     => 'Duplex Restaurant',
                'distance' => '1,500m | 21 min. Walk',
                'content'  => $faker->realText(1000),
                'image'    => 'places/05.jpg',
            ],
            [
                'name'     => 'Duplex Restaurant',
                'distance' => '1,500m | 21 min. Walk',
                'content'  => $faker->realText(1000),
                'image'    => 'places/06.jpg',
            ],
        ];

        Slug::where(['reference_type' => Place::class])->delete();

        foreach ($places as $place) {
            $place = Place::create($place);

            Slug::create([
                'reference_type' => Place::class,
                'reference_id'   => $place->id,
                'key'            => Str::slug($place->name),
                'prefix'         => SlugHelper::getPrefix(Place::class),
            ]);
        }

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            Artisan::call('cms:language:sync', ['class' => Place::class]);
        }
    }
}
