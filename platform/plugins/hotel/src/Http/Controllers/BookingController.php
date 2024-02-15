<?php

namespace Botble\Hotel\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Hotel\Forms\BookingForm;
use Botble\Hotel\Http\Requests\BookingRequest;
use Botble\Hotel\Repositories\Interfaces\BookingInterface;
use Botble\Hotel\Tables\BookingTable;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class BookingController extends BaseController
{
    /**
     * @var BookingInterface
     */
    protected $bookingRepository;

    /**
     * @param BookingInterface $bookingRepository
     */
    public function __construct(BookingInterface $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * @param BookingTable $table
     * @return Factory|View
     * @throws Throwable
     */
    public function index(BookingTable $table)
    {
        page_title()->setTitle(trans('plugins/hotel::booking.name'));

        return $table->renderTable();
    }

    /**
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $booking = $this->bookingRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $booking));

        page_title()->setTitle(trans('plugins/hotel::booking.edit') . ' "' . $booking->room->room->name . '"');

        return $formBuilder->create(BookingForm::class, ['model' => $booking])->renderForm();
    }

    /**
     * @param $id
     * @param BookingRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, BookingRequest $request, BaseHttpResponse $response)
    {
        $booking = $this->bookingRepository->findOrFail($id);

        $booking->fill($request->input());

        $this->bookingRepository->createOrUpdate($booking);

        event(new UpdatedContentEvent(BOOKING_MODULE_SCREEN_NAME, $request, $booking));

        return $response
            ->setPreviousUrl(route('booking.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $booking = $this->bookingRepository->findOrFail($id);

            $this->bookingRepository->delete($booking);

            event(new DeletedContentEvent(BOOKING_MODULE_SCREEN_NAME, $request, $booking));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $booking = $this->bookingRepository->findOrFail($id);
            $this->bookingRepository->delete($booking);
            event(new DeletedContentEvent(BOOKING_MODULE_SCREEN_NAME, $request, $booking));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
