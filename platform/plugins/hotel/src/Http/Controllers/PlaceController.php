<?php

namespace Botble\Hotel\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Hotel\Http\Requests\PlaceRequest;
use Botble\Hotel\Repositories\Interfaces\PlaceInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Hotel\Tables\PlaceTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Hotel\Forms\PlaceForm;
use Botble\Base\Forms\FormBuilder;

class PlaceController extends BaseController
{
    /**
     * @var PlaceInterface
     */
    protected $placeRepository;

    /**
     * @param PlaceInterface $placeRepository
     */
    public function __construct(PlaceInterface $placeRepository)
    {
        $this->placeRepository = $placeRepository;
    }

    /**
     * @param PlaceTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(PlaceTable $table)
    {
        page_title()->setTitle(trans('plugins/hotel::place.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/hotel::place.create'));

        return $formBuilder->create(PlaceForm::class)->renderForm();
    }

    /**
     * @param PlaceRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(PlaceRequest $request, BaseHttpResponse $response)
    {
        $place = $this->placeRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(PLACE_MODULE_SCREEN_NAME, $request, $place));

        return $response
            ->setPreviousUrl(route('place.index'))
            ->setNextUrl(route('place.edit', $place->id))
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
        $place = $this->placeRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $place));

        page_title()->setTitle(trans('plugins/hotel::place.edit') . ' "' . $place->name . '"');

        return $formBuilder->create(PlaceForm::class, ['model' => $place])->renderForm();
    }

    /**
     * @param $id
     * @param PlaceRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, PlaceRequest $request, BaseHttpResponse $response)
    {
        $place = $this->placeRepository->findOrFail($id);

        $place->fill($request->input());

        $this->placeRepository->createOrUpdate($place);

        event(new UpdatedContentEvent(PLACE_MODULE_SCREEN_NAME, $request, $place));

        return $response
            ->setPreviousUrl(route('place.index'))
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
            $place = $this->placeRepository->findOrFail($id);

            $this->placeRepository->delete($place);

            event(new DeletedContentEvent(PLACE_MODULE_SCREEN_NAME, $request, $place));

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
            $place = $this->placeRepository->findOrFail($id);
            $this->placeRepository->delete($place);
            event(new DeletedContentEvent(PLACE_MODULE_SCREEN_NAME, $request, $place));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
