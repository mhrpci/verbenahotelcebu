<?php

use Botble\Payment\Supports\StripeHelper;

if (!function_exists('convert_stripe_amount_from_api')) {
    /**
     * @param int|float $amount
     * @param \Illuminate\Database\Eloquent\Model $currency
     * @return float|int
     */
    function convert_stripe_amount_from_api($amount, $currency)
    {
        return $amount / StripeHelper::getStripeCurrencyMultiplier($currency);
    }
}

if (!function_exists('get_payment_setting')) {
    /**
     * @param string $key
     * @param null $type
     * @param null $default
     * @return string|null
     */
    function get_payment_setting($key, $type = null, $default = null)
    {
        if (!empty($type)) {
            $key = 'payment_' . $type . '_' . $key;
        } else {
            $key = 'payment_' . $key;
        }

        return setting($key, $default);
    }
}
