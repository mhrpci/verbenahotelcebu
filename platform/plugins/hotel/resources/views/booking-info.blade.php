@if ($booking)
    <div class="row">
        <div class="col-md-6">
            <p>{{ __('Time') }}: <i>{{ $booking->created_at }}</i></p>
            <p>{{ __('Full Name') }}: <i>{{ $booking->address->first_name }} {{ $booking->address->last_name }}</i></p>
            <p>{{ __('Email') }}: <i><a href="mailto:{{ $booking->address->email }}">{{ $booking->address->email }}</a></i></p>
            <p>{{ __('Phone') }}: <i>@if ($booking->address->phone) <a href="tel:{{ $booking->address->phone }}">{{ $booking->address->phone }}</a> @else N/A @endif</i></p>
            <p>{{ __('Address') }}: <i>{{ $booking->address->id ? $booking->address->address . ', ' . $booking->address->city . ', ' . $booking->address->state . ', ' . $booking->address->country . ', ' . $booking->address->zip : 'N/A' }}</i></p>
        </div>
        <div class="col-md-6">
            <p>{{ __('Room') }}: <i>@if ($booking->room->room->id) <a href="{{ $booking->room->room->url }}" target="_blank">{{ $booking->room->room->name }}</a> @else N/A @endif</i></p>
            <p><strong>{{ __('Start Date') }}</strong>: <i>{{ $booking->room->start_date }}</i></p>
            <p><strong>{{ __('End Date') }}</strong>: <i>{{ $booking->room->end_date }}</i></p>
            <p><strong>{{ __('Arrival Time') }}</strong>: <i>{{ $booking->arrival_time }}</i></p>
        </div>
    </div>
    <br>
    @if ($booking->requests)
        <p style="margin-bottom: 10px;"><strong>{{ __('Requests') }}</strong>: {{ $booking->requests }}</p>
    @endif
    <p><strong>{{ __('Room') }}</strong>:</p>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th class="text-center">{{ __('Image') }}</th>
            <th>{{ __('Name') }}</th>
            <th class="text-center">{{ __('Checkin Date') }}</th>
            <th class="text-center">{{ __('Checkout Date') }}</th>
            <th class="text-center">{{ __('Number of rooms') }}</th>
            <th class="text-center">{{ __('Price') }}</th>
            <th class="text-center">{{ __('Tax') }}</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center" style="width: 150px; vertical-align: middle">
                    <a href="{{ $booking->room->room->url }}" target="_blank">
                        <img src="{{ RvMedia::getImageUrl($booking->room->room->image, 'thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $booking->room->room->name }}" width="140">
                    </a>
                </td>
                <td style="vertical-align: middle"><a href="{{ $booking->room->room->url }}" target="_blank">{{ $booking->room->room->name }}</a></td>
                <td class="text-center" style="vertical-align: middle">{{ $booking->room->start_date }}</td>
                <td class="text-center" style="vertical-align: middle">{{ $booking->room->end_date }}</td>
                <td class="text-center" style="vertical-align: middle">{{ $booking->room->number_of_rooms }}</td>
                <td class="text-center" style="vertical-align: middle"><strong>{{ format_price($booking->room->price) }}</strong></td>
                <td class="text-center" style="vertical-align: middle"><strong>{{ format_price($booking->tax_amount) }}</strong></td>
            </tr>
        </tbody>
    </table>
    <br>
    @if ($booking->services->count())
        <p><strong>{{ __('Services') }}</strong>:</p>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th class="text-center">{{ __('Price') }}</th>
                <th class="text-center">{{ __('Total') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($booking->services as $service)
                <tr>
                    <td style="vertical-align: middle">
                        {{ $service->name }}
                    </td>
                    <td class="text-center" style="vertical-align: middle">{{ format_price($service->price) }} x {{ $booking->room->number_of_rooms }}</td>
                    <td class="text-center" style="vertical-align: middle">{{ format_price($service->price * $booking->room->number_of_rooms) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <br>
    @endif
    <br>
    <p><strong>{{ __('Total Amount') }}</strong>: <span class="text-danger">{{ format_price($booking->amount) }}</span></p>
    <p><strong>{{ __('Payment method') }}</strong>: {{ $booking->payment->id ? $booking->payment->payment_channel->label() : 'N/A' }}</p>
    <p><strong>{{ __('Payment status') }}</strong>: {!! $booking->payment->id ? $booking->payment->status->toHtml() : \Botble\Payment\Enums\PaymentStatusEnum::PENDING()->toHtml() !!}</p>
@endif
