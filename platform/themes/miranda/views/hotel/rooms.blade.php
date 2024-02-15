<!--====== BREADCRUMB PART START ======-->
<section class="breadcrumb-area" style="background-image: url({{ theme_option('rooms_banner') ? RvMedia::getImageUrl(theme_option('rooms_banner')) : Theme::asset()->url('img/bg/banner.jpg') }});">
    <div class="container">
        <div class="breadcrumb-text">
            <h2 class="page-title">{{ __('Rooms') }}</h2>
            {!! Theme::partial('breadcrumb') !!}
        </div>
    </div>
</section>
<!--====== BREADCRUMB PART END ======-->
<div class="pt-120 pb-120">
    {!! do_shortcode('[all-rooms]') !!}
</div>
