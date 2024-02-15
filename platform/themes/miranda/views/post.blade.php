<!--====== BREADCRUMB PART START ======-->
<section class="breadcrumb-area" style="background-image: url({{ theme_option('news_banner') ? RvMedia::getImageUrl(theme_option('news_banner')) : Theme::asset()->url('img/bg/banner.jpg') }});">
    <div class="container">
        <div class="breadcrumb-text">
            <span>{{ $post->name }}</span>
            <h2 class="page-title">{{ __('News Details') }}</h2>

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
                <div class="news-details-box">
                    <div class="entry-content">
                        @if (!$post->categories->isEmpty())
                            @foreach($post->categories as $category)
                                <a href="{{ $category->url }}" class="cat">{{ $category->name }}</a>
                            @endforeach
                        @endif
                        <h2 class="title">{{ $post->name }}</h2>
                        <ul class="post-meta">
                            <li><a href="{{ $post->url }}"><i class="fal fa-user"></i>{{ $post->author->getFullName() }}</a></li>
                            <li><a href="{{ $post->url }}"><i class="fal fa-calendar-alt"></i>{{ $post->created_at->format('d M, Y') }}</a></li>
                            <li><a href="{{ $post->url }}"><i class="far fa-eye"></i>{{ number_format($post->views) }} {{ __('Views') }}</a></li>
                        </ul>
                        <div class="mb-30">
                            {!! clean($post->content) !!}
                        </div>
                    </div>
                    <div class="entry-footer">
                        <div class="tag-and-share mt-50 mb-50 d-md-flex align-items-center justify-content-between">
                            @if (!$post->tags->isEmpty())
                                <div class="tag">
                                    <h5>{{ __('Related Tags') }}</h5>
                                    <ul>
                                        @foreach ($post->tags as $tag)
                                        <li><a href="{{ $tag->url }}">{{ $tag->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="share text-md-right">
                                <h5>{{ __('Social Share') }}</h5>
                                <ul>
                                        <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}&title={{ rawurldecode($post->description) }}" target="_blank" title="Share on Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ rawurldecode($post->description) }}" target="_blank" title="Share on Twitter"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&summary={{ rawurldecode($post->description) }}&source=Linkedin" title="Share on Linkedin" target="_blank"><i class="fab fa-linkedin"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="related-post mt-50">
                            <h3 class="mb-30">{{ __('Related Posts') }}</h3>
                            <div class="row">
                                @foreach (get_related_posts($post->slug, 2) as $relatedItem)
                                    <div class="col-md-6">
                                        <div class="related-post-box mb-50">
                                            <div class="thumb"
                                                 style="background-image: url({{ RvMedia::getImageUrl($relatedItem->image, null, false, RvMedia::getDefaultImage()) }});">
                                            </div>
                                            <div class="desc">
                                                <a href="{{ $relatedItem->url }}" class="date"><i class="far fa-calendar-alt"></i>{{ $relatedItem->created_at->format('d M, Y') }}</a>
                                                <h4><a href="{{ $relatedItem->url }}">{{ $relatedItem->name }}</a></h4>
                                                <p>{{ $relatedItem->description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, null) !!}
                        </div>
                    </div>
                </div>
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
