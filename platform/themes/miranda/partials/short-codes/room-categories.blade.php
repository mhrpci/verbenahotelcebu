<!--====== ROOM TYPE START ======-->
<section class="room-type-section pt-115 pb-115" style="background-image: url({{ RvMedia::getImageUrl($background_image) }});">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="section-title text-lg-left text-center">
                    <span class="title-tag">{!! clean($title) !!}</span>
                    <h2>{!! clean($sub_title) !!}</h2>
                </div>
            </div>
            <div class="col-lg-6">
                <ul class="room-filter nav nav-pills justify-content-center justify-content-lg-end" id="room-tab"
                    role="tablist">
                    @foreach($categories as $category)
                    <li class="nav-item">
                        <a class="nav-link @if ($loop->first) active @endif" id="{{ Str::slug($category->name) }}-tab" data-toggle="pill" href="#{{ Str::slug($category->name) }}">
                            {{ $category->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="tab-content mt-65" id="room-tabContent">
            @foreach($categories as $index => $category)
                <div class="tab-pane fade @if ($index == 0) show active @endif" id="{{ Str::slug($category->name) }}" role="tabpanel">
                <div class="room-items">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                @if (count($category->rooms) > 0)
                                    <div class="col-12">
                                        <div class="room-box extra-wide">
                                            <div class="room-bg"
                                                 style="background-image: url({{ RvMedia::getImageUrl($category->rooms[0]->image, '775x280') }});"></div>
                                            <div class="room-content">
                                                    <span class="room-count"><i class="fal fa-th"></i>{{ $category->rooms[0]->number_of_rooms }} {{ __('room(s)') }}</span>
                                                <h3><a href="{{ $category->rooms[0]->url }}">{{ $category->rooms[0]->name }}</a></h3>
                                            </div>
                                            <a href="{{ $category->rooms[0]->url }}" class="room-link"><i class="fal fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                @endif
                                @if (count($category->rooms) > 2)
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="room-box">
                                            <div class="room-bg"
                                                 style="background-image: url({{ RvMedia::getImageUrl($category->rooms[2]->image, '380x280') }});">
                                            </div>
                                            <div class="room-content">
                                                <span class="room-count"><i class="fal fa-th"></i>{{ $category->rooms[2]->number_of_rooms }} {{ __('room(s)') }}</span>
                                                <h3><a href="{{ $category->rooms[2]->url }}">{{ $category->rooms[2]->name }}</a></h3>
                                            </div>
                                            <a href="{{ $category->rooms[2]->url }}" class="room-link"><i class="fal fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                @endif
                                @if (count($category->rooms) > 3)
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="room-box">
                                            <div class="room-bg"
                                                 style="background-image: url({{ RvMedia::getImageUrl($category->rooms[3]->image, '380x280') }});">
                                            </div>
                                            <div class="room-content">
                                                <span class="room-count"><i class="fal fa-th"></i>{{ $category->rooms[3]->number_of_rooms }} {{ __('room(s)') }}</span>
                                                <h3><a href="{{ $category->rooms[3]->url }}">{{ $category->rooms[3]->name }}</a></h3>
                                            </div>
                                            <a href="{{ $category->rooms[3]->url }}" class="room-link"><i class="fal fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4">
                            @if (count($category->rooms) > 1)
                                <div class="room-box extra-height">
                                    <div class="room-bg" style="background-image: url({{ RvMedia::getImageUrl($category->rooms[1]->image, '380x575')  }});">
                                    </div>
                                    <div class="room-content">
                                        <span class="room-count"><i class="fal fa-th"></i>{{ $category->rooms[1]->number_of_rooms }} {{ __('room(s)') }}</span>
                                        <h3><a href="{{ $category->rooms[1]->url }}">{{ $category->rooms[1]->name }}</a></h3>
                                    </div>
                                    <a href="{{ $category->rooms[1]->url }}" class="room-link"><i class="fal fa-arrow-right"></i></a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!--====== ROOM TYPE END ======-->
