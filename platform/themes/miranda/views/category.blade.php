<!--====== BREADCRUMB PART START ======-->
<section class="breadcrumb-area" style="background-image: url({{ theme_option('news_banner') ? RvMedia::getImageUrl(theme_option('news_banner')) : Theme::asset()->url('img/bg/banner.jpg') }});">
    <div class="container">
        <div class="breadcrumb-text">
            <span>{{ __('Category') }}</span>
            <h2 class="page-title">{{ $category->name }}</h2>

            {!! Theme::partial('breadcrumb') !!}
        </div>
    </div>
</section>
<!--====== BREADCRUMB PART END ======-->
<!--====== BLOG SECTION START ======-->
<section class="blog-section pt-120 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @include(Theme::getThemeNamespace() . '::views.loop', compact('posts'))
            </div>
            <!-- Blog Sidebar -->
            <div class="col-lg-4 col-md-8 col-sm-10">
                <div class="sidebar">
                    {!! dynamic_sidebar('primary_sidebar') !!}
                </div>
            </div>
        </div>
    </div>
</section>
<!--====== BLOG SECTION END ======-->
