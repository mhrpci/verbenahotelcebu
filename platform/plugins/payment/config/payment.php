<?php

use App\Models\User;

return [
    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret'    => env('PAYPAL_SECRET'),
        /**
         * SDK configuration
         */
        'settings'  => [
            /**
             * Available option 'sandbox' or 'live'
             */
            'mode'                   => env('PAYPAL_MODE', 'sandbox'),
            /**
             * Specify the max request time in seconds
             */
            'http.ConnectionTimeOut' => 1000,
            /**
             * Whether want to log to a file
             */
            'log.LogEnabled'         => env('PAYPAL_LOG', true),
            /**
             * Specify the file that want to write on
             */
            'log.FileName'           => storage_path() . '/logs/paypal.log',
            /**
             * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
             *
             * Logging is most verbose in the 'FINE' level and decreases as you
             * proceed towards ERROR
             */
            'log.LogLevel'           => 'FINE',
        ],
    ],

    'stripe' => [
        'model'  => User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'currency' => env('PAYMENT_DEFAULT_CURRENCY', 'USD'),

    'return_url_after_payment' => env('PAYMENT_RETURN_URL_AFTER_PAYMENT', '/'),
];
