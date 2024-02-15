<?php

namespace Botble\Hotel\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Hotel\Forms\AmenityForm;
use Botble\Hotel\Http\Requests\AmenityRequest;
use Botble\Hotel\Repositories\Interfaces\AmenityInterface;
use Botble\Hotel\Tables\AmenityTable;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class AmenityController extends BaseController
{
    /**
     * @var AmenityInterface
     */
    protected $amenitiesRepository;

    /**
     * @param AmenityInterface $amenityRepository
     */
    public function __construct(AmenityInterface $amenityRepository)
    {
        $this->amenityRepository = $amenityRepository;
    }

    /**
     * @param AmenityTable $table
     * @return Factory|View
     * @throws Throwable
     */
    public function index(AmenityTable $table)
    {
        page_title()->setTitle(trans('plugins/hotel::amenity.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/hotel::amenity.create'));

        return $formBuilder->create(AmenityForm::class)->renderForm();
    }

    /**
     * @param AmenityRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(AmenityRequest $request, BaseHttpResponse $response)
    {
        $amenity = $this->amenityRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(AMENITIES_MODULE_SCREEN_NAME, $request, $amenity));

        return $response
            ->setPreviousUrl(route('amenity.index'))
            ->setNextUrl(route('amenity.edit', $amenity->id))
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
        $amenity = $this->amenityRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $amenity));

        page_title()->setTitle(trans('plugins/hotel::amenity.edit') . ' "' . $amenity->name . '"');

        return $formBuilder->create(AmenityForm::class, ['model' => $amenity])->renderForm();
    }

    /**
     * @param $id
     * @param AmenityRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, AmenityRequest $request, BaseHttpResponse $response)
    {
        $amenity = $this->amenityRepository->findOrFail($id);

        $amenity->fill($request->input());

        $this->amenityRepository->createOrUpdate($amenity);

        event(new UpdatedContentEvent(AMENITIES_MODULE_SCREEN_NAME, $request, $amenity));

        return $response
            ->setPreviousUrl(route('amenity.index'))
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
            $amenity = $this->amenityRepository->findOrFail($id);

            $this->amenityRepository->delete($amenity);

            event(new DeletedContentEvent(AMENITIES_MODULE_SCREEN_NAME, $request, $amenity));

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
            $amenity = $this->amenityRepository->findOrFail($id);
            $this->amenityRepository->delete($amenity);
            event(new DeletedContentEvent(AMENITIES_MODULE_SCREEN_NAME, $request, $amenity));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
