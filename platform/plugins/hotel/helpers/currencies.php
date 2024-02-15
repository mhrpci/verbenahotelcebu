<?php

use Botble\Hotel\Facades\CurrencyFacade;
use Botble\Hotel\Repositories\Interfaces\CurrencyInterface;
use Botble\Hotel\Models\Currency;
use Botble\Hotel\Supports\CurrencySupport;
use Illuminate\Support\Collection;

if (!function_exists('format_price')) {
    /**
     * @param int $price
     * @param Currency|null $currency
     * @param bool $withoutCurrency
     * @param bool $useSymbol
     * @return string
     */
    function format_price($price, $currency = null, $withoutCurrency = false, $useSymbol = true): string
    {
        if (!$currency) {
            $currency = get_application_currency();
        } elseif ($currency != null && !($currency instanceof Currency)) {
            $currency = app(CurrencyInterface::class)->getFirstBy(['ht_currencies.id' => $currency]);
        }

        if (!$currency) {
            return human_price_text($price, $currency);
        }

        if ($useSymbol && $currency->is_prefix_symbol) {
            return $currency->symbol . human_price_text($price, $currency);
        }

        if ($withoutCurrency) {
            return human_price_text($price, $currency);
        }

        return human_price_text($price, $currency, ($useSymbol ? $currency->symbol : $currency->title));
    }
}

if (!function_exists('human_price_text')) {
    /**
     * @param int $price
     * @param Currency|null $currency
     * @param string $priceUnit
     * @return string
     */
    function human_price_text($price, $currency, $priceUnit = ''): string
    {
        $numberAfterDot = ($currency instanceof Currency) ? $currency->decimals : 0;

        if (config('plugins.hotel.hotel.display_big_money_in_million_billion')) {
            if ($price >= 1000000 && $price < 1000000000) {
                $price = round($price / 1000000, 2);
                $numberAfterDot = 2;
                $priceUnit = __('million') . ' ' . $priceUnit;
            } elseif ($price >= 1000000000) {
                $price = round($price / 1000000000, 2);
                $numberAfterDot = 2;
                $priceUnit = __('billion') . ' ' . $priceUnit;
            }
        }

        if (is_numeric($price)) {
            $price = preg_replace('/[^0-9,.]/s', '', $price);
        }

        $price = number_format($price, $numberAfterDot, '.', ',');

        return $price . ($priceUnit ? ' ' . $priceUnit : '');
    }
}

if (!function_exists('cms_currency')) {
    /**
     * @return CurrencySupport
     */
    function cms_currency()
    {
        return CurrencyFacade::getFacadeRoot();
    }
}

if (!function_exists('get_all_currencies')) {
    /**
     * @return Collection
     */
    function get_all_currencies()
    {
        return app(CurrencyInterface::class)->getAllCurrencies();
    }
}

if (!function_exists('get_application_currency')) {
    /**
     * @return Currency|null
     */
    function get_application_currency()
    {
        return cms_currency()->getApplicationCurrency();
    }
}

if (!function_exists('get_application_currency_id')) {
    /**
     * @return int|null
     */
    function get_application_currency_id()
    {
        $currency = cms_currency()->getApplicationCurrency();

        return $currency ? $currency->id : null;
    }
}
