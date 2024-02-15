<!--====== ROOM SLIDER START ======-->
<section class="room-slider">
    <div class="container-fluid p-0">
        <div class="row rooms-slider-one">
            @foreach($rooms as $room)
                <div class="col">
                    <div class="slider-img" style="background-image: url({{ RvMedia::getImageUrl($room->image, '550x580') }});"></div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="rooms-content-wrap">
        <div class="container">
            <div class="row justify-content-center justify-content-md-start">
                <div class="col-xl-4 col-lg-5 col-sm-8">
                    <div class="room-content-box">
                        <div class="slider-count"></div>
                        <div class="slider-count-big"></div>
                        <div class="room-content-slider">
                            @foreach($rooms as $room)
                                <div class="single-content">
                                    <div class="icon">
                                        <i class="flaticon-hotel"></i>
                                    </div>
                                    <h3><a href="{{ $room->url }}">{{ $room->name }}</a></h3>
                                    <p>{{ $room->description }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--====== ROOM SLIDER END ======-->
