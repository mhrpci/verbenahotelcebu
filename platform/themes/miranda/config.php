<?php

use Botble\Theme\Theme;

return [

    /*
    |--------------------------------------------------------------------------
    | Inherit from another theme
    |--------------------------------------------------------------------------
    |
    | Set up inherit from another if the file is not exists,
    | this is work with "layouts", "partials" and "views"
    |
    | [Notice] assets cannot inherit.
    |
    */

    'inherit' => null, //default

    /*
    |--------------------------------------------------------------------------
    | Listener from events
    |--------------------------------------------------------------------------
    |
    | You can hook a theme when event fired on activities
    | this is cool feature to set up a title, meta, default styles and scripts.
    |
    | [Notice] these event can be override by package config.
    |
    */

    'events' => [

        // Listen on event before render a theme,
        // this event should call to assign some assets,
        // breadcrumb template.
        'beforeRenderTheme' => function (Theme $theme)
        {
            // You may use this event to set up your assets.
            $theme->asset()->usePath()->add('animate-css', 'css/animate.min.css');
            $theme->asset()->usePath()->add('bootstrap-css', 'css/bootstrap.min.css');
            $theme->asset()->usePath()->add('font-awesome-css', 'css/font-awesome.min.css');
            $theme->asset()->usePath()->add('flaticon', 'css/flaticon.css');
            $theme->asset()->usePath()->add('slick-css', 'css/slick.css');
            $theme->asset()->usePath()->add('nice-select-css', 'css/nice-select.css');
            $theme->asset()->usePath()->add('bootstrap-datepicker-css', 'css/bootstrap-datepicker.css');
            $theme->asset()->usePath()->add('style', 'css/style.css', [], [], '1.0.7');

            $theme->asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr-3.6.0.min.js');
            $theme->asset()->container('header')->usePath()->add('jquery', 'js/jquery-1.12.4.min.js');
            $theme->asset()->container('footer')->usePath()->add('bootstrap-js', 'js/bootstrap.min.js');
            $theme->asset()->container('footer')->usePath()->add('slick-js', 'js/slick.min.js');
            $theme->asset()->container('footer')->usePath()->add('jquery.nice-select', 'js/jquery.nice-select.min.js');
            $theme->asset()->container('footer')->usePath()->add('bootstrap-datepicker-js', 'js/bootstrap-datepicker.js');
            $theme->asset()->container('footer')->usePath()->add('wow', 'js/wow.min.js');
            $theme->asset()->container('footer')->usePath()->add('main', 'js/main.js', [], [], '1.0.7');

            if (function_exists('shortcode')) {
                $theme->composer(['index', 'page', 'post'], function (\Botble\Shortcode\View\View $view) {
                    $view->withShortcodes();
                });
            }
        },
    ]
];
