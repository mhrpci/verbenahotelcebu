<!--====== PLACES CONTENT START ======-->
<section class="places-wrapper">
    <div class="container">
        <!-- Places Boxes -->
        <div class="places-boxes">
            <!-- Title -->
            <div class="section-title text-center mb-50">
                <span class="title-tag">{!! clean($title) !!}</span>
                <h2>{!! clean($sub_title) !!}</h2>
            </div>
            <div class="row justify-content-center">
                @foreach($galleries as $gallery)
                    <div class="col-lg-4 col-md-4 col-sm-6 col-10">
                        <div class="place-box mt-30">
                            <div class="place-bg-wrap">
                                <div class="place-bg" style="background-image: url({{ RvMedia::getImageUrl($gallery->image, '380x280', false, RvMedia::getDefaultImage()) }});"></div>
                            </div>
                            <div class="desc">
                                <h4><a href="{{ $gallery->url }}">{{ $gallery->name }}</a></h4>
                                <span class="time">{{ $gallery->created_at->format('d M, Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!--====== PLACES CONTENT END ======-->
