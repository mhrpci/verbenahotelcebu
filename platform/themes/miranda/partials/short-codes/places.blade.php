<div class="places-boxes pt-20">
    <div class="row justify-content-center">
        @foreach($places as $place)
            <div class="col-lg-4 col-md-4 col-sm-6 col-10">
                <div class="place-box mt-30">
                    <div class="place-bg-wrap">
                        <div class="place-bg" style="background-image: url({{ RvMedia::getImageUrl($place->image, '380x280', false, RvMedia::getDefaultImage()) }});"></div>
                    </div>
                    <div class="desc">
                        <h4><a href="{{ $place->url }}">{{ $place->name }}</a></h4>
                        <span class="time">{{ $place->distance }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
