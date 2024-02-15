<?php

namespace Database\Seeders;

use Botble\Hotel\Models\Currency;
use Botble\Base\Supports\BaseSeeder;

class CurrencySeeder extends BaseSeeder
{
    public function run()
    {
        Currency::truncate();

        $currencies = [
            [
                'title'            => 'USD',
                'symbol'           => '$',
                'is_prefix_symbol' => true,
                'order'            => 0,
                'decimals'         => 0,
                'is_default'       => 1,
                'exchange_rate'    => 1,
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
}
