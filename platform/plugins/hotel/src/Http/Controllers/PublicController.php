<?php

namespace Botble\Hotel\Http\Controllers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Hotel\Http\Requests\CheckoutRequest;
use Botble\Hotel\Models\Booking;
use Botble\Hotel\Models\Place;
use Botble\Hotel\Models\Room;
use Botble\Hotel\Repositories\Interfaces\BookingAddressInterface;
use Botble\Hotel\Repositories\Interfaces\BookingInterface;
use Botble\Hotel\Repositories\Interfaces\BookingRoomInterface;
use Botble\Hotel\Repositories\Interfaces\PlaceInterface;
use Botble\Hotel\Repositories\Interfaces\RoomInterface;
use Botble\Hotel\Repositories\Interfaces\ServiceInterface;
use Botble\Hotel\Services\BookingService;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Http\Requests\PayPalPaymentCallbackRequest;
use Botble\Payment\Repositories\Interfaces\PaymentInterface;
use Botble\Payment\Services\Gateways\BankTransferPaymentService;
use Botble\Payment\Services\Gateways\CodPaymentService;
use Botble\Payment\Services\Gateways\PayPalPaymentService;
use Botble\Payment\Services\Gateways\StripePaymentService;
use Botble\SeoHelper\SeoOpenGraph;
use Botble\Slug\Repositories\Interfaces\SlugInterface;
use Carbon\Carbon;
use EmailHandler;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Response;
use RvMedia;
use SeoHelper;
use SlugHelper;
use Theme;
use Throwable;

class PublicController extends Controller
{
    /**
     * @param Request $request
     * @param RoomInterface $roomRepository
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|Response
     * @throws \Throwable
     */
    public function getRooms(Request $request, RoomInterface $roomRepository, BaseHttpResponse $response)
    {
        SeoHelper::setTitle(__('Rooms'));

        if ($request->input('start_date') && $request->input('end_date')) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->input('start_date'));
            $endDate = Carbon::createFromFormat('d-m-Y', $request->input('end_date'));
        } else {
            $startDate = now();
            $endDate = now()->addDay();
        }

        $nights = $endDate->diffInDays($startDate);

        if ($request->ajax() && $request->wantsJson()) {
            if (strtotime($startDate->toDateString()) - strtotime($endDate->toDateString()) < 60 * 60 * 24) {
                return $response
                    ->setError(true)
                    ->setMessage(__('Dates are not valid'));
            }

            if ($nights > 30) {
                return $response
                    ->setError(true)
                    ->setMessage(__('Maximum day for booking is 30'));
            }
        }

        $filters = [
            'keyword' => $request->query('q'),
        ];

        $params = [
            'paginate' => [
                'per_page'      => 10,
                'current_paged' => (int)$request->input('page', 1),
            ],
        ];

        $allRooms = $roomRepository->getRooms($filters, $params);

        $condition = [
            'start_date' => $startDate->format('d-m-Y'),
            'end_date'   => $endDate->format('d-m-Y'),
            'adults'     => $request->input('adults', 1),
        ];

        $rooms = [];
        foreach ($allRooms as $allRoom) {
            if ($allRoom->isAvailableAt($condition)) {
                $rooms[] = $allRoom;
            }
        }

        Theme::breadcrumb()
            ->add(__('Home'), url('/'))
            ->add(__('Rooms'), route('public.rooms'));

        if ($request->ajax() && $request->wantsJson()) {
            foreach ($rooms as $room) {
                $data = view(Theme::getThemeNamespace() . '::views.hotel.includes.room-item',
                    compact('room'))->render();
            }

            return $response->setData($data);
        }

        return Theme::scope('hotel.rooms', compact('rooms', 'nights'))->render();
    }

    /**
     * @param string $key
     * @param SlugInterface $slugRepository
     * @param RoomInterface $roomRepository
     * @return Response
     */
    public function getRoom(string $key, SlugInterface $slugRepository, RoomInterface $roomRepository)
    {
        $slug = $slugRepository->getFirstBy([
            'slugs.key'      => $key,
            'reference_type' => Room::class,
            'prefix'         => SlugHelper::getPrefix(Room::class),
        ]);

        if (!$slug) {
            abort(404);
        }

        $room = $roomRepository->getFirstBy(
            ['id' => $slug->reference_id],
            ['*'],
            ['amenities', 'currency', 'category']
        );

        if (!$room) {
            abort(404);
        }

        SeoHelper::setTitle($room->name)->setDescription(Str::words($room->description, 120));

        $meta = new SeoOpenGraph;
        if ($room->image) {
            $meta->setImage(RvMedia::getImageUrl($room->image));
        }
        $meta->setDescription($room->description);
        $meta->setUrl($room->url);
        $meta->setTitle($room->name);
        $meta->setType('article');

        SeoHelper::setSeoOpenGraph($meta);

        Theme::breadcrumb()
            ->add(__('Home'), url('/'))
            ->add($room->name, $room->url);

        $relatedRooms = $roomRepository->getRelatedRooms($room->id,
            theme_option('number_of_related_rooms', 2));

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, ROOM_MODULE_SCREEN_NAME, $room);

        $images = [];
        foreach ($room->images as $image) {
            $images[] = RvMedia::getImageUrl($image, null, false, RvMedia::getDefaultImage());
        }

        return Theme::scope('hotel.room', compact('room', 'images', 'relatedRooms'))->render();
    }

    /**
     * @param string $key
     * @param SlugInterface $slugRepository
     * @param PlaceInterface $placeRepository
     * @return Response
     */
    public function getPlace(string $key, SlugInterface $slugRepository, PlaceInterface $placeRepository)
    {
        $slug = $slugRepository->getFirstBy([
            'slugs.key'      => $key,
            'reference_type' => Place::class,
            'prefix'         => SlugHelper::getPrefix(Place::class),
        ]);

        if (!$slug) {
            abort(404);
        }

        $place = $placeRepository->getFirstBy(
            ['id' => $slug->reference_id],
            ['*'],
            ['slugable']
        );

        if (!$place) {
            abort(404);
        }

        SeoHelper::setTitle($place->name)->setDescription(Str::words($place->description, 120));

        $meta = new SeoOpenGraph;
        if ($place->image) {
            $meta->setImage(RvMedia::getImageUrl($place->image));
        }
        $meta->setDescription($place->description);
        $meta->setUrl($place->url);
        $meta->setTitle($place->name);
        $meta->setType('article');

        SeoHelper::setSeoOpenGraph($meta);

        Theme::breadcrumb()
            ->add(__('Home'), url('/'))
            ->add($place->name, $place->url);

        $relatedPlaces = $placeRepository->getRelatedPlaces($place->id, 3);

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, PLACE_MODULE_SCREEN_NAME, $place);

        return Theme::scope('hotel.place', compact('place', 'relatedPlaces'))->render();
    }

    /**
     * @param Request $request
     * @param RoomInterface $roomRepository
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postBooking(Request $request, RoomInterface $roomRepository, BaseHttpResponse $response)
    {
        $request->validate([
            'start_date' => 'required:date_format:d-m-Y',
            'end_date'   => 'required:date_format:d-m-Y',
            'adults'     => 'required',
        ]);

        $room = $roomRepository->getFirstBy(
            ['id' => $request->input('room_id')],
            ['*'],
            ['currency', 'category']
        );

        if (!$room) {
            abort(404);
        }

        $token = md5(Str::random(40));

        session([$token => $request->except(['_token'])]);

        return $response->setNextUrl(route('public.booking.form', $token));
    }

    /**
     * @param string $token
     * @param RoomInterface $roomRepository
     * @param ServiceInterface $serviceRepository
     * @return Response
     */
    public function getBooking($token, RoomInterface $roomRepository, ServiceInterface $serviceRepository)
    {
        SeoHelper::setTitle(__('Booking'));

        $sessionData = [];
        if (session()->has($token)) {
            $sessionData = session($token);
        }

        if (empty($sessionData)) {
            abort(404);
        }

        Theme::breadcrumb()
            ->add(__('Home'), url('/'))
            ->add(__('Booking'), route('public.booking'));

        Theme::asset()->add('card-css', asset('vendor/core/plugins/payment/libraries/card/card.css'), [], [], '1.0.0');
        Theme::asset()->add('payment-css', asset('vendor/core/plugins/payment/css/payment.css'), [], [], '1.0.0');

        Theme::asset()
            ->container('footer')
            ->add('card-js', asset('vendor/core/plugins/payment/libraries/card/card.js'), ['jquery'], [], '1.0.0');

        if (setting('payment_stripe_status') == 1) {
            Theme::asset()
                ->container('footer')
                ->add('stripe-js', asset('https://js.stripe.com/v2/'), [], [], '1.0.0');
        }

        Theme::asset()
            ->container('footer')
            ->add('payment-js', asset('vendor/core/plugins/payment/js/payment.js'), ['jquery'], [], '1.0.0');

        $startDate = Carbon::createFromFormat('d-m-Y', Arr::get($sessionData, 'start_date'));
        $endDate = Carbon::createFromFormat('d-m-Y', Arr::get($sessionData, 'end_date'));
        $nights = $endDate->diffInDays($startDate);
        $adults = Arr::get($sessionData, 'adults');

        $room = $roomRepository->getFirstBy(
            ['id' => Arr::get($sessionData, 'room_id')],
            ['*'],
            ['currency', 'category']
        );

        if (!$room) {
            abort(404);
        }

        $taxAmount = $room->tax->percentage * $room->price / 100;

        $total = $room->price * $nights + $taxAmount;

        $services = $serviceRepository->allBy(['status' => BaseStatusEnum::PUBLISHED]);

        return Theme::scope('hotel.booking', compact(
                'room',
                'services',
                'startDate',
                'endDate',
                'adults',
                'total',
                'taxAmount',
                'token'
            )
        )->render();
    }

    /**
     * @param CheckoutRequest $request
     * @param BookingInterface $bookingRepository
     * @param RoomInterface $roomRepository
     * @param BookingAddressInterface $bookingAddressRepository
     * @param BookingRoomInterface $bookingRoomRepository
     * @param ServiceInterface $serviceRepository
     * @param PayPalPaymentService $payPalService
     * @param StripePaymentService $stripePaymentService
     * @param CodPaymentService $codPaymentService
     * @param BankTransferPaymentService $bankTransferPaymentService
     * @param BookingService $bookingService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCheckout(
        CheckoutRequest $request,
        BookingInterface $bookingRepository,
        RoomInterface $roomRepository,
        BookingAddressInterface $bookingAddressRepository,
        BookingRoomInterface $bookingRoomRepository,
        ServiceInterface $serviceRepository,
        PayPalPaymentService $payPalService,
        StripePaymentService $stripePaymentService,
        CodPaymentService $codPaymentService,
        BankTransferPaymentService $bankTransferPaymentService,
        BookingService $bookingService
    ) {
        $room = $roomRepository->findOrFail($request->input('room_id'));

        $booking = $bookingRepository->getModel();
        $booking->fill($request->input());

        $startDate = Carbon::createFromFormat('d-m-Y', $request->input('start_date'));
        $endDate = Carbon::createFromFormat('d-m-Y', $request->input('end_date'));
        $nights = $endDate->diffInDays($startDate);

        $taxAmount = $room->tax->percentage * $room->price / 100;

        $booking->amount = $room->price * $nights + $taxAmount;
        $booking->tax_amount = $taxAmount;

        $booking->transaction_id = Str::upper(Str::random(32));

        if ($request->input('services')) {
            $services = $serviceRepository->getModel()
                ->whereIn('id', $request->input('services'))
                ->get();

            foreach ($services as $service) {
                $booking->amount += $service->price;
            }
        }

        $booking = $bookingRepository->createOrUpdate($booking);

        if ($request->input('services')) {
            $booking->services()->attach($request->input('services'));
        }

        $bookingRoomRepository->createOrUpdate([
            'room_id'         => $room->id,
            'booking_id'      => $booking->id,
            'price'           => $room->price,
            'currency_id'     => $room->currency_id,
            'number_of_rooms' => 1,
            'start_date'      => $startDate->format('Y-m-d'),
            'end_date'        => $endDate->format('Y-m-d'),
        ]);

        $bookingAddress = $bookingAddressRepository->getModel();
        $bookingAddress->fill($request->input());
        $bookingAddress->booking_id = $booking->id;
        $bookingAddressRepository->createOrUpdate($bookingAddress);

        $request->merge([
            'order_id' => $booking->id,
        ]);

        $paymentData = [
            'error'     => false,
            'message'   => false,
            'amount'    => $booking->amount,
            'currency'  => strtoupper(get_application_currency()->title),
            'type'      => $request->input('payment_method'),
            'charge_id' => null,
        ];

        switch ($request->input('payment_method')) {
            case PaymentMethodEnum::STRIPE:
                $result = $stripePaymentService->execute($request);
                if (!$result) {
                    $paymentData['error'] = true;
                    $paymentData['message'] = $stripePaymentService->getErrorMessage();
                } else {
                    $paymentData['charge_id'] = $result;
                }

                break;

            case PaymentMethodEnum::PAYPAL:
                $checkoutUrl = $payPalService->execute($request);
                if ($checkoutUrl) {
                    return redirect($checkoutUrl);
                }

                $paymentData['error'] = true;
                $paymentData['message'] = $payPalService->getErrorMessage();
                break;
            case PaymentMethodEnum::COD:
                $paymentData['charge_id'] = $codPaymentService->execute($request);
                break;

            case PaymentMethodEnum::BANK_TRANSFER:
                $paymentData['charge_id'] = $bankTransferPaymentService->execute($request);
                break;
            default:
                $paymentData = apply_filters(PAYMENT_FILTER_AFTER_POST_CHECKOUT, $paymentData, $request);
                break;
        }

        $bookingService->processBooking($booking->id, $paymentData['charge_id']);

        if ($request->input('token')) {
            session()->forget($request->input('token'));
        }

        return redirect()
            ->route('public.booking.information', $booking->transaction_id)
            ->with('success_msg', __('Checkout successfully!'));
    }

    /**
     * @param string $transactionId
     * @param BookingInterface $bookingRepository
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function checkoutSuccess($transactionId, BookingInterface $bookingRepository)
    {
        $booking = $bookingRepository->getFirstBy(['transaction_id' => $transactionId]);

        if (!$booking) {
            abort(404);
        }

        SeoHelper::setTitle(__('Booking Information'));

        Theme::breadcrumb()
            ->add(__('Home'), url('/'))
            ->add(__('Booking'), route('public.booking.information', $transactionId));

        return Theme::scope('hotel.booking-information', compact('booking'))->render();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param RoomInterface $roomRepository
     * @param ServiceInterface $serviceRepository
     */
    public function ajaxCalculateBookingAmount(
        Request $request,
        BaseHttpResponse $response,
        RoomInterface $roomRepository,
        ServiceInterface $serviceRepository
    ) {
        $request->validate([
            'start_date' => 'required:date_format:d-m-Y',
            'end_date'   => 'required:date_format:d-m-Y',
            'room_id'    => 'required',
        ]);

        $startDate = Carbon::createFromFormat('d-m-Y', $request->input('start_date'));
        $endDate = Carbon::createFromFormat('d-m-Y', $request->input('end_date'));
        $nights = $endDate->diffInDays($startDate);

        $room = $roomRepository->findOrFail($request->input('room_id'));

        $taxAmount = $room->tax->percentage * $room->price / 100;

        $amount = $room->price * $nights + $taxAmount;

        if ($request->input('services')) {
            $services = $serviceRepository->getModel()
                ->whereIn('id', $request->input('services'))
                ->get();

            foreach ($services as $service) {
                $amount += $service->price;
            }
        }

        return $response->setData([
            'amount'     => format_price($amount),
            'amount_raw' => $amount,
        ]);
    }

    /**
     * @param PayPalPaymentCallbackRequest $request
     * @param PayPalPaymentService $palPaymentService
     * @param BaseHttpResponse $response
     * @param BookingService $bookingService
     * @return BaseHttpResponse
     */
    public function getPayPalStatus(
        PayPalPaymentCallbackRequest $request,
        PayPalPaymentService $palPaymentService,
        BaseHttpResponse $response,
        BookingService $bookingService
    ) {
        $palPaymentService->afterMakePayment($request);

        $booking = $bookingService->processBooking($request->input('order_id'), $request->input('paymentId'));

        return $response
            ->setNextUrl(route('public.booking.information', $booking->transaction_id))
            ->setMessage(__('Checkout successfully!'));
    }
}
