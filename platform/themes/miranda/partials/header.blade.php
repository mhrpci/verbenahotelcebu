<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <!-- Fonts-->
        <link href="https://fonts.googleapis.com/css2?family={{ urlencode(theme_option('primary_font', 'Archivo')) }}:ital,wght@0,400;0,500;0,600;0,700;1,400;1,700&family={{ urlencode(theme_option('secondary_font', 'Old Standard TT')) }}:ital,wght@0,400;0,700;1,400&family={{ urlencode(theme_option('tertiary_font', 'Roboto')) }}:wght@400;500;700&display=swap" rel="stylesheet" type="text/css">
        <!-- CSS Library-->

        <style>
            :root {
                --color-1st: {{ theme_option('primary_color', '#bead8e') }};
                --primary-font: '{{ theme_option('primary_font', 'Archivo') }}', sans-serif;
                --secondary-font: '{{ theme_option('secondary_font', 'Old Standard TT') }}', sans-serif;
                --tertiary-font: '{{ theme_option('tertiary_font', 'Roboto') }}', sans-serif;
            }
        </style>

        {!! Theme::header() !!}
    </head>
    <body @if (BaseHelper::siteLanguageDirection() == 'rtl') dir="rtl" @endif>
    <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    @if (theme_option('preloader_enabled', 'no') == 'yes')
        <!--====== Preloader ======-->
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="cssload-container">
                <div class="cssload-loading"><i></i><i></i><i></i><i></i></div>
            </div>
        </div>
    @endif
<!--====== HEADER START ======-->
<header class="header-absolute sticky-header @if (url()->current() == url('')) header-two @else inner-page @endif">
    <div class="container container-custom-one">
        <div class="nav-container d-flex align-items-center justify-content-between breakpoint-on">
            <!-- Main Menu -->
            <div class="nav-menu d-lg-flex align-items-center">

                <!-- Navbar Close Icon -->
                <div class="navbar-close">
                    <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                </div>

                <!-- Off canvas Menu  -->
                <div class="toggle">
                    <a href="#" id="offCanvasBtn"><i class="fal fa-bars"></i></a>
                </div>
                <!-- Menu Items -->
                <div class="menu-items">
                    {!! Menu::renderMenuLocation('header-menu', ['view' => 'menu']) !!}
                </div>

                <!-- from pushed-item -->
                <div class="nav-pushed-item"></div>
            </div>

            <!-- Site Logo -->
            <div class="site-logo">
                <a href="{{ url('/') }}" class="main-logo">
                    <img src="{{ RvMedia::getImageUrl(theme_option(url()->current() == url('') ? 'logo_white' : 'logo')) }}" alt="{{ theme_option('site_title') }}">
                </a>
                <a href="{{ url('/') }}" class="sticky-logo">
                    <img src="{{ RvMedia::getImageUrl(theme_option('logo')) }}" alt="{{ theme_option('site_title') }}">
                </a>
            </div>

            <!-- Header Info Pushed To Menu Wrap -->
            <div class="nav-push-item">
                <!-- Header Info -->
                <div class="header-info d-lg-flex align-items-center">
                    @if (theme_option('hotline'))
                        <div class="item">
                            <i class="fal fa-phone"></i>
                            <span>{{ __('Phone Number') }}</span>
                            <a href="tel:{{ theme_option('hotline') }}">
                                <h5 class="title">{{ theme_option('hotline') }}</h5>
                            </a>
                        </div>
                    @endif
                    @if (theme_option('email'))
                        <div class="item">
                            <i class="fal fa-envelope"></i>
                            <span>{{ __('Email Address') }}</span>
                            <a href="mailto:{{ theme_option('email') }}">
                                <h5 class="title">{{ theme_option('email') }}</h5>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Navbar Toggler -->
            <div class="navbar-toggler">
                <span></span><span></span><span></span>
            </div>
        </div>
    </div>
</header>
<!--====== HEADER END ======-->
<!--====== OFF CANVAS START ======-->
<div class="offcanvas-wrapper">
    <div class="offcanvas-overly"></div>
    <div class="offcanvas-widget">
        <a href="#" class="offcanvas-close"><i class="fal fa-times"></i></a>
        <!-- Search Widget -->
        @if (is_plugin_active('hotel'))
            <div class="widget search-widget">
                <h5 class="widget-title">{{ __('Search room') }}</h5>
                <form action="{{ route('public.rooms') }}">
                    <input type="text" name="q" value="{{ old('q', request()->query('q')) }}" placeholder="{{ __('Enter keyword') }}...">
                    <button type="submit"><i class="far fa-search"></i></button>
                </form>
            </div>
        @endif

        <!-- About Widget -->
        <div class="widget about-widget">
            <h5 class="widget-title">{{ __('About us') }}</h5>
            <p>{{ theme_option('about-us') }}</p>
        </div>
        <!-- Nav Widget -->
        <div class="widget nav-widget">
            <h5 class="widget-title">{{ __('Our pages') }}</h5>
            {!! Menu::renderMenuLocation('side-menu', ['view' => 'menu']) !!}
        </div>
        <!-- Social Link -->
        <div class="widget social-link">
            <h5 class="widget-title">{{ __('Contact us') }}</h5>
            <ul>
                @if (theme_option('facebook'))
                    <li><a href="{{ theme_option('facebook') }}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                @endif
                @if (theme_option('twitter'))
                    <li><a href="{{ theme_option('twitter') }}" target="_blank"><i class="fab fa-twitter"></i></a></li>
                @endif
                @if (theme_option('youtube'))
                    <li><a href="{{ theme_option('youtube') }}" target="_blank"><i class="fab fa-youtube"></i></a></li>
                @endif
                @if (theme_option('behance'))
                    <li><a href="{{ theme_option('behance') }}" target="_blank"><i class="fab fa-behance"></i></a></li>
                @endif
                @if (theme_option('linkedin'))
                    <li><a href="{{ theme_option('linkedin') }}" target="_blank"><i class="fab fa-linkedin"></i></a></li>
                @endif
                @if (theme_option('google'))
                    <li><a href="{{ theme_option('google') }}" target="_blank"><i class="fab fa-google"></i></a></li>
                @endif
            </ul>
        </div>
    </div>
</div>
<!--====== OFF CANVAS END ======-->
