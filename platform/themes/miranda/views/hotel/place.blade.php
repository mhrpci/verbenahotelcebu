<!--====== BREADCRUMB PART START ======-->
<section class="breadcrumb-area" style="background-image: url({{ theme_option('rooms_banner') ? RvMedia::getImageUrl(theme_option('rooms_banner')) : Theme::asset()->url('img/bg/banner.jpg') }});">
    <div class="container">
        <div class="breadcrumb-text">
            <h2 class="page-title">{{ __('Place') }}</h2>

            {!! Theme::partial('breadcrumb') !!}
        </div>
    </div>
</section>
<!--====== BREADCRUMB PART END ======-->

<section class="places-wrapper pt-120 pb-120">
    <div class="container">
        <div class="places-details">
            @if ($place->image)
                <div class="main-thumb mb-50">
                    <img src="{{ RvMedia::getImageUrl($place->image) }}" alt="images">
                </div>
            @endif
            <div class="title-wrap mb-50 d-flex align-items-center justify-content-between">
                <div class="title">
                    <span class="place-cat">{{ __('destination') }}</span>
                    <h2>{{ $place->name }}</h2>
                </div>
            </div>
            {!! clean($place->content) !!}
        </div>
        <!-- Places Boxes -->
        <div class="places-boxes pt-115">
            <!-- Title -->
            <div class="section-title text-center mb-50">
                <h2>{{ __('Other Places') }}</h2>
            </div>
            <div class="row justify-content-center">
                @foreach($relatedPlaces as $relatedPlace)
                    <div class="col-lg-4 col-md-4 col-sm-6 col-10">
                        <div class="place-box mt-30">
                            <div class="place-bg-wrap">
                                <div class="place-bg" style="background-image: url({{ RvMedia::getImageUrl($relatedPlace->image, '380x280', false, RvMedia::getDefaultImage()) }});"></div>
                            </div>
                            <div class="desc">
                                <h4><a href="{{ $relatedPlace->url }}">{{ $relatedPlace->name }}</a></h4>
                                <span class="time">{{ $relatedPlace->distance }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!--====== PLACES CONTENT END ======-->
