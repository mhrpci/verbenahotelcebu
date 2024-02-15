<li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
    <a href="javascript:;" class="dropdown-toggle dropdown-header-name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="icon-envelope-open"></i>
        <span class="badge badge-default"> {{ count($bookings) }} </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-right">
        <li class="external">
                <h3>{!! trans('plugins/hotel::booking.new_booking_notice', ['count' => count($bookings)]) !!}</h3>
            <a href="{{ route('booking.index') }}">{{ trans('plugins/hotel::booking.view_all') }}</a>
        </li>
        <li>
            <ul class="dropdown-menu-list scroller" style="height: {{ count($bookings) * 70 }}px;" data-handle-color="#637283">
                    @foreach($bookings as $booking)
                    <li>
                        <a href="{{ route('booking.edit', $booking->id) }}">
                            <span class="photo">
                                <img src="{{ \Botble\Base\Supports\Gravatar::image($booking->address->email) }}" class="rounded-circle" alt="{{ $booking->address->first_name }} {{ $booking->address->last_name }}">
                            </span>
                            <span class="subject"><span class="from"> {{ $booking->address->first_name }} {{ $booking->address->last_name }} </span><span class="time">{{ $booking->created_at->toDateTimeString() }} </span></span>
                            <span class="message"> {{ $booking->address->phone }} - {{ $booking->address->email }} </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
</li>
