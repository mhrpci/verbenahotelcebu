<?php

namespace Botble\Hotel\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Hotel\Forms\RoomForm;
use Botble\Hotel\Http\Requests\RoomRequest;
use Botble\Hotel\Models\RoomDate;
use Botble\Hotel\Repositories\Interfaces\RoomDateInterface;
use Botble\Hotel\Repositories\Interfaces\RoomInterface;
use Botble\Hotel\Tables\RoomTable;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class RoomController extends BaseController
{
    /**
     * @var RoomInterface
     */
    protected $roomRepository;

    /**
     * @param RoomInterface $roomRepository
     */
    public function __construct(RoomInterface $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    /**
     * @param RoomTable $table
     * @return Factory|View
     * @throws Throwable
     */
    public function index(RoomTable $table)
    {
        page_title()->setTitle(trans('plugins/hotel::room.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/hotel::room.create'));

        return $formBuilder->create(RoomForm::class)->renderForm();
    }

    /**
     * @param RoomRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(RoomRequest $request, BaseHttpResponse $response)
    {
        $request->merge([
            'images' => json_encode(array_filter($request->input('images', []))),
        ]);

        $room = $this->roomRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(ROOM_MODULE_SCREEN_NAME, $request, $room));

        if ($room) {
            $room->amenities()->sync($request->input('amenities', []));
        }

        return $response
            ->setPreviousUrl(route('room.index'))
            ->setNextUrl(route('room.edit', $room->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $room = $this->roomRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $room));

        page_title()->setTitle(trans('plugins/hotel::room.edit') . ' "' . $room->name . '"');

        return $formBuilder->create(RoomForm::class, ['model' => $room])->renderForm();
    }

    /**
     * @param int $id
     * @param RoomRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, RoomRequest $request, BaseHttpResponse $response)
    {
        $room = $this->roomRepository->findOrFail($id);

        $room->fill($request->input());
        $room->images = json_encode(array_filter($request->input('images', [])));

        $this->roomRepository->createOrUpdate($room);

        event(new UpdatedContentEvent(ROOM_MODULE_SCREEN_NAME, $request, $room));

        $room->amenities()->sync($request->input('amenities', []));

        return $response
            ->setPreviousUrl(route('room.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $room = $this->roomRepository->findOrFail($id);

            $this->roomRepository->delete($room);

            event(new DeletedContentEvent(ROOM_MODULE_SCREEN_NAME, $request, $room));

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
            $room = $this->roomRepository->findOrFail($id);
            $this->roomRepository->delete($room);
            event(new DeletedContentEvent(ROOM_MODULE_SCREEN_NAME, $request, $room));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoomAvailability($id, Request $request)
    {
        $request->validate([
            'start' => 'required',
            'end'   => 'required',
        ]);

        $room = $this->roomRepository->findOrFail($id);

        $allDates = [];

        for ($i = strtotime($request->query('start')); $i <= strtotime($request->query('end')); $i += 60 * 60 * 24) {
            $date = [
                'id'              => rand(0, 999),
                'active'          => 0,
                'price'           => $room->price,
                'number_of_rooms' => $room->number_of_rooms,
                'is_default'      => true,
                'textColor'       => '#2791fe',
            ];
            $date['price_html'] = format_price($date['price']);
            $date['title'] = $date['event'] = $date['price_html'];
            $date['start'] = $date['end'] = date('Y-m-d', $i);

            $date['active'] = 1;
            $allDates[date('Y-m-d', $i)] = $date;
        }

        $rows = RoomDate::where('room_id', $id)
            ->where('start_date', '>=', date('Y-m-d H:i:s', strtotime($request->query('start'))))
            ->where('end_date', '<=', date('Y-m-d H:i:s', strtotime($request->query('end'))))
            ->take(40)
            ->get();

        if (!empty($rows)) {
            foreach ($rows as $row) {
                $row->start = date('Y-m-d', strtotime($row->start_date));
                $row->end = date('Y-m-d', strtotime($row->start_date));
                $row->textColor = '#2791fe';
                $price = $row->price;
                if (empty($price)) {
                    $price = $room->price;
                }
                $row->title = $row->event = format_price($price);
                $row->price = $price;

                if (!$row->active) {
                    $row->title = $row->event = trans('plugins/hotel::room.booked');
                    $row->backgroundColor = '#fe2727';
                    $row->classNames = ['blocked-event'];
                    $row->textColor = '#fe2727';
                    $row->active = 0;
                } else {
                    $row->classNames = ['active-event'];
                    $row->active = 1;
                }

                $allDates[date('Y-m-d', strtotime($row->start_date))] = $row->toArray();
            }
        }

        $bookings = $room->getBookingsInRange($request->query('start'), $request->query('end'));

        if (!empty($bookings)) {
            foreach ($bookings as $booking) {
                for ($i = strtotime($booking->start_date); $i < strtotime($booking->end_date); $i += 60 * 60 * 24) {
                    if (isset($allDates[date('Y-m-d', $i)])) {
                        $allDates[date('Y-m-d', $i)]['number_of_rooms'] -= $booking->number_of_rooms;
                        if ($allDates[date('Y-m-d', $i)]['number_of_rooms'] <= 0) {
                            $allDates[date('Y-m-d', $i)]['active'] = 0;
                            $allDates[date('Y-m-d', $i)]['event'] = trans('plugins/hotel::room.full_book');
                            $allDates[date('Y-m-d', $i)]['title'] = trans('plugins/hotel::room.full_book');
                            $allDates[date('Y-m-d', $i)]['classNames'] = ['full-book-event'];
                        }
                    }
                }
            }
        }

        $data = array_values($allDates);

        return response()->json($data);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param RoomDateInterface $roomDateRepository
     * @return BaseHttpResponse
     */
    public function storeRoomAvailability(
        $id,
        Request $request,
        BaseHttpResponse $response,
        RoomDateInterface $roomDateRepository
    ) {
        $request->validate([
            'start_date' => 'required',
            'end_date'   => 'required',
        ]);

        for ($i = strtotime($request->input('start_date')); $i <= strtotime($request->input('end_date')); $i += 60 * 60 * 24) {
            $roomDate = $roomDateRepository->getFirstBy([
                'start_date' => date('Y-m-d', $i),
                'room_id'    => $id,
            ]);

            if (empty($roomDate)) {
                $roomDate = $roomDateRepository->getModel();
                $roomDate->room_id = $id;
            }

            $roomDate->fill($request->input());

            $roomDate->start_date = date('Y-m-d H:i:s', $i);
            $roomDate->end_date = date('Y-m-d H:i:s', $i);

            $roomDateRepository->createOrUpdate($roomDate);
        }

        return $response
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}
