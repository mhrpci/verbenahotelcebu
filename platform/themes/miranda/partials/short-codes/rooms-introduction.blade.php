<!--====== ROOM Gallery CTA START ======-->
<section class="room-gallery-cta" style="background-image: url({{ RvMedia::getImageUrl($background_image) }});">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="cta-text text-center">
                    <span>{!! clean($title) !!}</span>
                    <h2>{!! clean($description) !!}</h2>
                    @if ($button_text)
                        <ul class="mt-50">
                            <li class="wow fadeInUp" data-wow-delay=".3s"><a class="main-btn btn-filled" href="{{ $button_url }}">{{ $button_text }}</a></li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="rotate-images">
        @if ($first_image)
            <img src="{{ RvMedia::getImageUrl($first_image) }}" class="rotate-image-one" alt="Image">
        @endif
        @if ($third_image)
            <img src="{{ RvMedia::getImageUrl($second_image) }}" class="rotate-image-two" alt="Image">
        @endif
        @if ($third_image)
            <img src="{{ RvMedia::getImageUrl($third_image) }}" class="rotate-image-three" alt="Image">
        @endif
    </div>
</section>
<!--====== ROOM Gallery CTA END ======-->
