<?php

namespace Botble\Payment\Services\Traits;

use Illuminate\Support\Facades\Auth;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Repositories\Interfaces\PaymentInterface;
use Illuminate\Support\Arr;

trait PaymentTrait
{

    /**
     * Store payment on local
     *
     * @param array $args
     * @return mixed
     */
    public function storeLocalPayment(array $args = [])
    {
        $data = array_merge([
            'user_id' => Auth::check() ? Auth::user()->getAuthIdentifier() : 0,
        ], $args);

        $paymentChannel = Arr::get($data, 'payment_channel', PaymentMethodEnum::STRIPE);

        return app(PaymentInterface::class)->create([
            'account_id'      => Arr::get($data, 'account_id'),
            'amount'          => $data['amount'],
            'currency'        => $data['currency'],
            'charge_id'       => $data['charge_id'],
            'order_id'        => $data['order_id'],
            'customer_id'     => Arr::get($data, 'customer_id'),
            'customer_type'   => Arr::get($data, 'customer_type'),
            'payment_channel' => $paymentChannel,
            'status'          => Arr::get($data, 'status', PaymentStatusEnum::PENDING),
        ]);
    }
}
