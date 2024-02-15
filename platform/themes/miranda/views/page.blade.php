@if (url()->current() != url('') && $page->id != setting('show_on_front') && $page->template != 'homepage')
    <!--====== BREADCRUMB PART START ======-->
    <section class="breadcrumb-area" style="background-image: url({{ theme_option('news_banner') ? RvMedia::getImageUrl(theme_option('news_banner')) : Theme::asset()->url('img/bg/banner.jpg') }});">
        <div class="container">
            <div class="breadcrumb-text">
                <h2 class="page-title">{{ $page->name }}</h2>

                {!! Theme::partial('breadcrumb') !!}
            </div>
        </div>
    </section>
    <!--====== BREADCRUMB PART END ======-->
@endif

@if ($page->template == 'homepage')
    {!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, clean($page->content), $page) !!}
@elseif ($page->template == 'full-width')
    <section class="blog-section contact-part pt-120 pb-120">
        {!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, clean($page->content), $page) !!}
    </section>
@else
    <section class="blog-section contact-part pt-120 pb-120">
        <div class="container">
            @if ($page->template == 'default')
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        {!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, clean($page->content), $page) !!}
                    </div>
                    <div class="col-lg-4 col-md-8 col-sm-10">
                        <div class="sidebar">
                            {!! dynamic_sidebar('primary_sidebar') !!}
                        </div>
                    </div>
                </div>
            @else
                {!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, clean($page->content), $page) !!}
            @endif
        </div>
    </section>
@endif
