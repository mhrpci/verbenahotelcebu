<section class="restaurant-tab-area pb-60">
    <div class="container">
        <ul class="restaurant-rood-list row justify-content-center nav nav-pills mb-30" id="restaurant-tab"
            role="tablist">
            @foreach($foodTypes as $foodType)
                <li class="nav-item col-lg-2 col-md-3 col-sm-4 col-6">
                    <a class="nav-link @if ($loop->first) active @endif" data-toggle="pill" href="#{{ Str::slug($foodType->name) }}">
                        <i class="{{ $foodType->icon }}"></i>
                        <span class="title">{{ $foodType->name }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
        <!-- tab content -->
        <div class="tab-content " id="restaurant-tabContent">
            @foreach($foodTypes as $foodType)
                <div class="tab-pane fade @if ($loop->first) show active @endif" id="{{ Str::slug($foodType->name) }}" role="tabpanel">
                    <div class="row">
                        @foreach($foodType->foods as $food)
                            <div class="col-lg-3 col-6">
                                <div class="food-box">
                                    <div class="thumb">
                                        <img src="{{ RvMedia::getImageUrl($food->image, '380x280', false, RvMedia::getDefaultImage()) }}" alt="images">
                                        <span class="price">{{ format_price($food->price) }}</span>
                                    </div>
                                    <div class="desc">
                                        <span class="cat">{{ $food->type->name }}</span>
                                        <h4>{{ $food->name }}</h4>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
