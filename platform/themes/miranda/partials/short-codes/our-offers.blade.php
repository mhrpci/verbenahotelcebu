<section class="offers-area pb-60">
    <div class="container">
        <div class="offer-boxes-loop">
            @foreach($rooms as $room)
                <div class="offer-box">
                <div class="thumb">
                    <img src="{{ RvMedia::getImageUrl($room->image, '1170x570') }}" alt="{{ $room->name }}">
                </div>
                <div class="offer-desc">
                    <div class="title-wrap">
                        <div class="title">
                            <span class="room-cat">{{ $room->category->name }}</span>
                            <h2><a href="{{ $room->url }}">{{ $room->name }}</a></h2>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-lg-6">
                            <div class="offer-text">
                                <p>{{ $room->description }}</p>
                                <form action="{{ route('public.booking') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                                    <input type="hidden" name="start_date" value="{{ request()->query('start_date', now()->format('d-m-Y')) }}">
                                    <input type="hidden" name="end_date" value="{{ request()->query('end_date', now()->addDay()->format('d-m-Y')) }}">
                                    <input type="hidden" name="adults" value="{{ request()->query('adults', 1) }}">
                                    <button type="submit" class="main-btn btn-filled booking-button">{{ __('Book now for :price', ['price' => format_price($room->price * (isset($nights) ? $nights : 1))]) }}</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="offer-feature">
                                @if (count($room->amenities) > 0)
                                    <ul>
                                        @foreach($room->amenities->take(7) as $amenity)
                                            <li><i class="{{ $amenity->icon }}"></i> {{ $amenity->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
