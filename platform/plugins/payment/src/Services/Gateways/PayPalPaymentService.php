<?php

namespace Botble\Payment\Services\Gateways;

use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Services\Abstracts\PayPalPaymentAbstract;
use Botble\Payment\Services\Traits\PaymentTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PayPalPaymentService extends PayPalPaymentAbstract
{
    use PaymentTrait;

    /**
     * Make a payment
     *
     * @param Request $request
     *
     * @return mixed
     * @throws Exception
     */
    public function makePayment(Request $request)
    {
        $amount = $request->input('amount');

        $data = [
            'name'     => $request->input('name'),
            'quantity' => 1,
            'price'    => $amount,
            'sku'      => null,
            'type'     => PaymentMethodEnum::PAYPAL,
        ];

        $currency = $request->input('currency', config('plugins.payment.payment.currency'));
        $currency = strtoupper($currency);

        $queryParams = [
            'type'     => PaymentMethodEnum::PAYPAL,
            'amount'   => $amount,
            'currency' => $currency,
            'order_id' => $request->input('order_id'),
        ];

        $checkoutUrl = $this
            ->setReturnUrl($request->input('callback_url') . '?' . http_build_query($queryParams))
            ->setCurrency($currency)
            ->setItem($data)
            ->createPayment($request->input('description'));

        return $checkoutUrl;
    }

    /**
     * Use this function to perform more logic after user has made a payment
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function afterMakePayment(Request $request)
    {
        $paymentStatus = $this->getPaymentStatus($request);

        $status = PaymentStatusEnum::COMPLETED;

        if (!$paymentStatus || ($paymentStatus && $paymentStatus->state !== 'approved')) {
            $status = PaymentStatusEnum::FAILED;
        }

        $chargeId = $request->input('paymentId', Str::upper(Str::random(10)));

        $this->storeLocalPayment([
            'amount'          => $request->input('amount'),
            'currency'        => $request->input('currency'),
            'charge_id'       => $chargeId,
            'order_id'        => $request->input('order_id'),
            'customer_id'     => $request->input('customer_id'),
            'customer_type'   => $request->input('customer_type'),
            'payment_channel' => PaymentMethodEnum::PAYPAL,
            'status'          => $status,
        ]);

        return $chargeId;
    }
}
