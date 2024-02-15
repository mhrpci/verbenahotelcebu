<section class="menu-area gradient-white pt-80 pb-80">
    <div class="container">
        <!-- Section Title -->
        <div class="section-title text-center mb-80">
            <span class="title-tag">{!! clean($title) !!}</span>
            <h2>{!! clean($sub_title) !!}</h2>
        </div>
        <!-- Menu Loop -->
        <div class="menu-loop">
            <div class="row justify-content-center">
                @php
                    $chunks = $foods->chunk(ceil($foods->count() / 2));
                @endphp
                @if (count($chunks) > 0)
                    <div class="col-lg-6 col-md-10">
                        @foreach($chunks[0] as $food)
                            <div class="single-menu-box wow fadeInUp" data-wow-delay=".3s">
                                <div class="menu-img" style="background-image: url({{ RvMedia::getImageUrl($food->image, 'thumb', false, RvMedia::getDefaultImage()) }});">
                                </div>
                                <div class="menu-desc">
                                    <h4>{{ $food->name }}</h4>
                                    <p>{{ $food->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                @if (count($chunks) > 1)
                    <div class="col-lg-6 col-md-10">
                        @foreach($chunks[1] as $food)
                            <div class="single-menu-box wow fadeInUp" data-wow-delay=".3s">
                                <div class="menu-img" style="background-image: url({{ RvMedia::getImageUrl($food->image, 'thumb', false, RvMedia::getDefaultImage()) }});">
                                </div>
                                <div class="menu-desc">
                                    <h4>{{ $food->name }}</h4>
                                    <p>{{ $food->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
