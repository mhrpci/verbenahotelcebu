@if ($payment)
    <p>{{ trans('plugins/payment::payment.payment_id') }}: {{ $payment->id }}</p>
    <p>{{ trans('plugins/payment::payment.payer_name') }}: {{ $payment->source->name }}</p>
    <p>{{ trans('plugins/payment::payment.card') }}: {{ $payment->source->brand }} - **** **** **** {{ $payment->source->last4 }}
        - {{ $payment->source->exp_month }}/{{ $payment->source->exp_year }}</p>
    <p>{{ trans('plugins/payment::payment.country') }}: {{ $payment->source->country }}</p>
    @if (!empty($payment->source->address_line1))
        <p>{{ trans('plugins/payment::payment.address') }}: {{ $payment->source->address_line1 }}</p>
    @endif
@endif


