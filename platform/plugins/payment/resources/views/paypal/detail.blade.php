@if ($payment)
    <p>{{ trans('plugins/payment::payment.payment_id') }}: {{ $payment->id }}</p>

    <div>
        {{ trans('plugins/payment::payment.details') }}:
        <strong>
            @foreach($payment->transactions as $transaction)
                {{ $transaction->amount->total }} {{ $transaction->amount->currency }} @if (!empty($transaction->description)) ({{ $transaction->description }}) @endif
            @endforeach
        </strong>
    </div>

    <p>{{ trans('plugins/payment::payment.payer_name') }}
        : {{ $payment->payer->payer_info->first_name }} {{ $payment->payer->payer_info->last_name }}</p>
    <p>{{ trans('plugins/payment::payment.email') }}: {{ $payment->payer->payer_info->email }}</p>
    <p>{{ trans('plugins/payment::payment.phone')  }}: {{ $payment->payer->payer_info->phone }}</p>
    <p>{{ trans('plugins/payment::payment.country') }}: {{ $payment->payer->payer_info->country_code }}</p>

    <p>
        {{ trans('plugins/payment::payment.shipping_address') }}
        : {{ $payment->payer->payer_info->shipping_address->recipient_name }}
        , {{ $payment->payer->payer_info->shipping_address->line1 }},
        {{ $payment->payer->payer_info->shipping_address->city }}
        , {{ $payment->payer->payer_info->shipping_address->state }} {{ $payment->payer->payer_info->shipping_address->portal_code }}
        ,
        {{ $payment->payer->payer_info->shipping_address->country_code }}
    </p>
@endif

