<?php

namespace Botble\SslCommerz\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Models\BaseModel;
use Botble\Hotel\Services\BookingService;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Botble\Payment\Services\Traits\PaymentTrait;
use Botble\SslCommerz\Library\SslCommerz\SslCommerzNotification;
use Illuminate\Http\Request;

class SslCommerzPaymentController extends BaseController
{
    use PaymentTrait;

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function success(Request $request, BaseHttpResponse $response)
    {
        $transactionId = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification;

        $validation = $sslc->orderValidate($request->input(), $transactionId, $amount, $currency);

        $booking = $this->processBooking($request, $validation);

        if (!$validation) {
            return $response
                ->setError()
                ->setNextUrl(route('public.booking.information', $booking->transaction_id))
                ->setMessage(__('Payment failed!'));
        }

        return $response
            ->setNextUrl(route('public.booking.information', $booking->transaction_id))
            ->setMessage(__('Booking successfully!'));
    }

    /**
     * @param Request $request
     * @param string $status
     * @return bool|BaseModel
     */
    protected function processBooking(Request $request, bool $validation = false)
    {
        $transactionId = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        $bookingId = $request->input('value_a');

        $this->storeLocalPayment([
            'amount'          => $amount,
            'currency'        => $currency,
            'charge_id'       => $transactionId,
            'payment_channel' => SSLCOMMERZ_PAYMENT_METHOD_NAME,
            'status'          => $validation ? PaymentStatusEnum::COMPLETED : PaymentStatusEnum::FAILED,
            'customer_id'     => null,
            'payment_type'    => 'direct',
            'order_id'        => $bookingId,
        ]);

        return app(BookingService::class)->processBooking($bookingId, $transactionId);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function fail(Request $request, BaseHttpResponse $response)
    {
        $booking = $this->processBooking($request);

        return $response
            ->setError()
            ->setNextUrl(route('public.booking.information', $booking->transaction_id))
            ->setMessage(__('Payment failed!'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function cancel(Request $request, BaseHttpResponse $response)
    {
        $booking = $this->processBooking($request);

        return $response
            ->setError()
            ->setNextUrl(route('public.booking.information', $booking->transaction_id))
            ->setMessage(__('Payment failed!'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function ipn(Request $request, BaseHttpResponse $response)
    {
        // Received all the payment information from the gateway
        // Check transaction id is posted or not.
        if (!$request->input('tran_id')) {
            return $response
                ->setError()
                ->setMessage(__('Invalid Data!'));
        }

        $transactionId = $request->input('tran_id');

        // Check order status in order table against the transaction id or order id.
        $transaction = Payment::where('charge_id', $transactionId)
            ->select(['charge_id', 'status'])->first();

        if (!$transaction) {
            return $response
                ->setError()
                ->setMessage(__('Invalid Transaction!'));
        }

        if ($transaction->status == PaymentStatusEnum::PENDING) {
            $sslc = new SslCommerzNotification;
            $validation = $sslc->orderValidate($request->all(), $transactionId, $transaction->amount,
                $transaction->currency);
            if ($validation == true) {
                /*
                That means IPN worked. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successful transaction to customer
                */
                Payment::where('charge_id', $transactionId)
                    ->update(['status' => PaymentStatusEnum::COMPLETED]);

                return $response
                    ->setError()
                    ->setMessage(__('Transaction is successfully completed!'));
            }
            /*
               That means IPN worked, but Transaction validation failed.
               Here you need to update order status as Failed in order table.
               */
            Payment::where('charge_id', $transactionId)
                ->update(['status' => PaymentStatusEnum::FAILED]);

            return $response
                ->setError()
                ->setMessage(__('Validation Fail!'));
        }

        // That means Order status already updated. No need to update database.
        return $response
            ->setError()
            ->setMessage(__('Transaction is already successfully completed!'));
    }
}
