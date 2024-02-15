        <!--====== Back to Top ======-->
        <a href="#" class="back-to-top" id="backToTop">
            <i class="fal fa-angle-double-up"></i>
        </a>
        <!--====== FOOTER START ======-->
        <footer class="footer-two">
            <div class="footer-widget-area pt-100 pb-50">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 order-1">
                            <!-- Site Info Widget -->
                            <div class="widget site-info-widget mb-50">
                                <div class="footer-logo mb-50">
                                    <img src="{{ RvMedia::getImageUrl(theme_option('logo_white')) }}" alt="{{ theme_option('site_title') }}">
                                </div>
                                <p>{{ theme_option('about-us') }}</p>
                                <div class="social-links mt-40">
                                    @if (theme_option('facebook'))
                                        <a href="{{ theme_option('facebook') }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                    @endif
                                    @if (theme_option('twitter'))
                                        <a href="{{ theme_option('twitter') }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                    @endif
                                    @if (theme_option('youtube'))
                                        <a href="{{ theme_option('youtube') }}" target="_blank"><i class="fab fa-youtube"></i></a>
                                    @endif
                                    @if (theme_option('behance'))
                                        <a href="{{ theme_option('behance') }}" target="_blank"><i class="fab fa-behance"></i></a>
                                    @endif
                                    @if (theme_option('linkedin'))
                                        <a href="{{ theme_option('linkedin') }}" target="_blank"><i class="fab fa-linkedin"></i></a>
                                    @endif
                                    @if (theme_option('google'))
                                        <a href="{{ theme_option('google') }}" target="_blank"><i class="fab fa-google"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 order-3 order-lg-2">
                            <!-- Nav Widget -->
                            {!! dynamic_sidebar('footer_sidebar') !!}
                        </div>
                        <div class="col-lg-3 col-sm-6 order-2 order-lg-3">
                            <!-- Contact Widget -->
                            <div class="widget contact-widget mb-50">
                                <h4 class="widget-title">{{ __('Contact Us') }}.</h4>
                                <div class="contact-lists">
                                    <div class="contact-box">
                                        <div class="icon">
                                            <i class="flaticon-call"></i>
                                        </div>
                                        <div class="desc">
                                            <h6 class="title">{{ __('Phone Number') }}</h6>
                                            {{ theme_option('hotline') }}
                                        </div>
                                    </div>
                                    <div class="contact-box">
                                        <div class="icon">
                                            <i class="flaticon-message"></i>
                                        </div>
                                        <div class="desc">
                                            <h6 class="title">{{ __('Email Address') }}</h6>
                                            <a href="mailto:{{ theme_option('email') }}">{{ theme_option('email') }}</a>
                                        </div>
                                    </div>
                                    <div class="contact-box">
                                        <div class="icon">
                                            <i class="flaticon-location-pin"></i>
                                        </div>
                                        <div class="desc">
                                            <h6 class="title">{{ __('Office Address') }}</h6>
                                            {{ theme_option('address') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="copyright-area pt-30 pb-30">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-5 order-2 order-md-1">
                            <p class="copyright-text copyright-two">{{ theme_option('copyright') }}</p>
                        </div>
                        <div class="col-lg-6 col-md-7 order-1 order-md-2">
                            <div class="footer-menu text-center text-md-right">
                                <ul>
                                    @if (theme_option('term_of_use_url'))
                                        <li><a href="{{ theme_option('term_of_use_url') }}">{{ __('Terms of use') }}</a></li>
                                    @endif
                                    @if (theme_option('privacy_policy_url'))
                                        <li><a href="{{ theme_option('privacy_policy_url') }}">{{ __('Privacy Environmental Policy') }}</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!--====== FOOTER END ======-->

        {!! Theme::footer() !!}
    </body>
</html>
