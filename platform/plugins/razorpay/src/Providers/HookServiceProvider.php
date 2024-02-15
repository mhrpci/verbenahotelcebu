<?php

namespace Botble\Razorpay\Providers;

use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Repositories\Interfaces\PaymentInterface;
use Exception;
use Html;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Throwable;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, [$this, 'registerRazorpayMethod'], 11, 2);
        add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, [$this, 'checkoutWithRazorpay'], 11, 2);

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, [$this, 'addPaymentSettings'], 93);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['RAZORPAY'] = RAZORPAY_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 20, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == RAZORPAY_PAYMENT_METHOD_NAME) {
                $value = 'Razorpay';
            }

            return $value;
        }, 20, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == RAZORPAY_PAYMENT_METHOD_NAME) {
                $value = Html::tag('span', PaymentMethodEnum::getLabel($value),
                    ['class' => 'label-success status-label'])
                    ->toHtml();
            }

            return $value;
        }, 20, 2);
    }

    /**
     * @param string $settings
     * @return string
     * @throws Throwable
     */
    public function addPaymentSettings($settings)
    {
        return $settings . view('plugins/razorpay::settings')->render();
    }

    /**
     * @param string $html
     * @param array $data
     * @return string
     */
    public function registerRazorpayMethod($html, $data)
    {
        try {
            $api = new Api(get_payment_setting('key', RAZORPAY_PAYMENT_METHOD_NAME),
                get_payment_setting('secret', RAZORPAY_PAYMENT_METHOD_NAME));

            $receiptId = Str::random(20);

            $order = $api->order->create([
                'receipt'  => $receiptId,
                'amount'   => $data['amount'] * 100,
                'currency' => $data['currency'],
            ]);

            $data['orderId'] = $order['id'];

            return $html . view('plugins/razorpay::methods', $data)->render();
        } catch (Exception $exception) {
            info($exception->getMessage());
            return null;
        }
    }

    /**
     * @param Request $request
     * @param array $data
     * @return array
     */
    public function checkoutWithRazorpay(array $data, Request $request)
    {
        if ($request->input('payment_method') == RAZORPAY_PAYMENT_METHOD_NAME) {
            try {
                $api = new Api(get_payment_setting('key', RAZORPAY_PAYMENT_METHOD_NAME),
                    get_payment_setting('secret', RAZORPAY_PAYMENT_METHOD_NAME));

                $api->utility->verifyPaymentSignature([
                    'razorpay_signature'  => $request->input('razorpay_signature'),
                    'razorpay_payment_id' => $request->input('razorpay_payment_id'),
                    'razorpay_order_id'   => $request->input('razorpay_order_id'),
                ]);

                $status = PaymentStatusEnum::COMPLETED;
            } catch (SignatureVerificationError $exception) {
                $status = PaymentStatusEnum::FAILED;
            }

            $data['charge_id'] = $request->input('razorpay_payment_id');

            if (!$data['charge_id']) {
                $data['charge_id'] = Str::upper(Str::random(10));
            }

            app(PaymentInterface::class)->create([
                'account_id'      => Arr::get($data, 'account_id'),
                'amount'          => $data['amount'],
                'currency'        => $data['currency'],
                'charge_id'       => $data['charge_id'],
                'payment_channel' => RAZORPAY_PAYMENT_METHOD_NAME,
                'status'          => $status,
                'order_id'        => $request->input('order_id'),
            ]);
        }

        return $data;
    }
}
