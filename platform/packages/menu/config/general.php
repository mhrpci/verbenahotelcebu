<?php

return [
    'locations' => [
        'header-menu' => 'Header Navigation',
        'main-menu'   => 'Main Navigation',
        'footer-menu' => 'Footer Navigation',
    ],
    'cache'     => [
        'enabled' => env('CACHE_FRONTEND_MENU', false),
    ],
];