<div class="room-booking-form">
    @if (!$availableForBooking)
        <h5 class="title">{{ __('Check Availability') }}</h5>
    @endif
    <form action="{{ $availableForBooking ? route('public.booking') : route('public.rooms') }}" method="{{ $availableForBooking ? 'POST' : 'GET' }}">
        @if ($availableForBooking)
            @csrf
            @if ($room)
                <input type="hidden" name="room_id" value="{{ $room->id }}">
            @endif
        @endif
        <div class="input-group input-group-two left-icon mb-20">
            <label for="arrival-date">{{ __('Check-In') }}</label>
            <div class="icon"><i class="fal fa-calendar-alt"></i></div>
            <input type="text" placeholder="{{ request()->query('start_date', now()->format('d-m-Y')) }}" value="{{ request()->query('start_date', now()->format('d-m-Y')) }}" name="start_date" id="arrival-date">
        </div>
        <div class="input-group input-group-two left-icon mb-20">
            <label for="departure-date">{{ __('Check-Out') }}</label>
            <div class="icon"><i class="fal fa-calendar-alt"></i></div>
            <input type="text" placeholder="{{ request()->query('end_date', now()->addDay()->format('d-m-Y')) }}" value="{{ request()->query('end_date', now()->addDay()->format('d-m-Y')) }}" name="end_date" id="departure-date">
        </div>
        <div class="input-group input-group-two left-icon mb-20">
            <label for="adults">{{ __('Guests') }}</label>
            <select name="adults" id="adults">
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" @if (request()->query('adults', 1) == $i) selected @endif>{{ $i }} {{ $i == 1 ? __('Guest') : __('Guests') }}</option>
                @endfor
            </select>
        </div>
        <div class="input-group">
            <button class="main-btn btn-filled">{{ $availableForBooking ? __('Book Now') : __('Check Availability') }}</button>
        </div>
    </form>
</div>
