<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Setting\Models\Setting as SettingModel;
use Botble\Slug\Models\Slug;
use SlugHelper;

class SettingSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SettingModel::insertOrIgnore([
            [
                'key'   => SlugHelper::getPermalinkSettingKey(Post::class),
                'value' => 'news',
            ],
            [
                'key'   => SlugHelper::getPermalinkSettingKey(Category::class),
                'value' => 'news',
            ],
            [
                'key'   => 'payment_cod_status',
                'value' => 1,
            ],
            [
                'key'   => 'payment_cod_description',
                'value' => 'Please pay money directly to the postman, if you choose cash on delivery method (COD).',
            ],
            [
                'key'   => 'payment_bank_transfer_status',
                'value' => 1,
            ],
            [
                'key'   => 'payment_bank_transfer_description',
                'value' => 'Please send money to our bank account: ACB - 1990 404 19.',
            ],
        ]);

        Slug::where('reference_type', Post::class)->update(['prefix' => 'news']);
        Slug::where('reference_type', Category::class)->update(['prefix' => 'news']);
    }
}
