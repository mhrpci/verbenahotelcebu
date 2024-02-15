<section class="text-block with-bg pt-115 pb-115" @if ($background_image) style="background-image: url({{ RvMedia::getImageUrl($background_image) }});" @endif>
    <div class="container">
        <div class="row align-items-center justify-content-center justify-content-lg-between">
            <div class="col-lg-5 col-md-8 col-sm-10 wow fadeInLeft" data-wow-delay=".3s">
                <div class="block-text mb-small">
                    <div class="section-title mb-20">
                        <span class="title-tag">{!! clean($title) !!}</span>
                        @if ($sub_title)
                            <h2>{!! clean($sub_title) !!}</h2>
                        @endif
                    </div>
                    <p>{!! clean($description) !!}</p>
                    @if ($button_text)
                        <a href="{{ $button_url }}" class="main-btn btn-filled mt-40">{{ $button_text }}</a>
                    @endif
                </div>
            </div>
            <div class="col-lg-6 col-md-10 wow fadeInRight" data-wow-delay=".5s">
                <div class="video-wrap" @if ($video_image) style="background-image: url({{ RvMedia::getImageUrl($video_image) }});" @endif>
                    @if ($video_url)
                        <a href="{{ $video_url }}" class="popup-video"><i class="fas fa-play"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
