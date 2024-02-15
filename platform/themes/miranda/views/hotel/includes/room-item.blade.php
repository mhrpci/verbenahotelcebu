<div class="room-box mb-30">
    <div class="room-img-wrap">
        <div class="room-img" style="background-image: url({{ RvMedia::getImageUrl($room->image, '380x280', false, RvMedia::getDefaultImage()) }});"></div>
    </div>
    <div class="room-desc">
        @if (count($room->amenities) > 0)
            <ul class="icons">
                @foreach($room->amenities->take(7) as $amenity)
                    <li><i class="{{ $amenity->icon }}"></i></li>
                @endforeach
            </ul>
        @endif

        <div class="text-left">
            <h4 class="title"><a href="{{ $room->url }}">{{ $room->name }}</a></h4>
            <p class="mb-10">{{ $room->description }}</p>
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
</div>
