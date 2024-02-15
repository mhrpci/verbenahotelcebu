<?php

namespace Botble\Paystack\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Hotel\Repositories\Interfaces\BookingInterface;
use Botble\Hotel\Services\BookingService;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Services\Traits\PaymentTrait;
use Illuminate\Http\Request;
use Paystack;

class PaystackController extends BaseController
{
    use PaymentTrait;

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param BookingService $bookingService
     * @return BaseHttpResponse
     */
    public function getPaymentStatus(Request $request, BaseHttpResponse $response, BookingService $bookingService)
    {
        $result = Paystack::getPaymentData();

        $booking = app(BookingInterface::class)->findById($result['data']['metadata']['order_id']);

        if (!$result['status']) {
            return $response
                ->setError()
                ->setNextUrl(route('public.booking.information', $booking->transaction_id))
                ->setMessage($result['message']);
        }

        $this->storeLocalPayment([
            'amount'          => $result['data']['amount'] / 100,
            'currency'        => $result['data']['currency'],
            'charge_id'       => $request->input('reference'),
            'payment_channel' => PAYSTACK_PAYMENT_METHOD_NAME,
            'status'          => PaymentStatusEnum::COMPLETED,
            'customer_id'     => null,
            'payment_type'    => 'direct',
            'order_id'        => $result['data']['metadata']['order_id'],
        ]);

        $bookingService->processBooking($result['data']['metadata']['order_id'], $request->input('reference'));

        return $response
            ->setNextUrl(route('public.booking.information', $booking->transaction_id))
            ->setMessage(__('Booking successfully!'));
    }
}
