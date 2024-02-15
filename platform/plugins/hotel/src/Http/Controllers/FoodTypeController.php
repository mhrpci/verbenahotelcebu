<?php

namespace Botble\Hotel\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Hotel\Forms\FoodTypeForm;
use Botble\Hotel\Http\Requests\FoodTypeRequest;
use Botble\Hotel\Repositories\Interfaces\FoodTypeInterface;
use Botble\Hotel\Tables\FoodTypeTable;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class FoodTypeController extends BaseController
{
    /**
     * @var FoodTypeInterface
     */
    protected $foodTypeRepository;

    /**
     * @param FoodTypeInterface $foodTypeRepository
     */
    public function __construct(FoodTypeInterface $foodTypeRepository)
    {
        $this->foodTypeRepository = $foodTypeRepository;
    }

    /**
     * @param FoodTypeTable $table
     * @return Factory|View
     * @throws Throwable
     */
    public function index(FoodTypeTable $table)
    {
        page_title()->setTitle(trans('plugins/hotel::food-type.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/hotel::food-type.create'));

        return $formBuilder->create(FoodTypeForm::class)->renderForm();
    }

    /**
     * @param FoodTypeRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(FoodTypeRequest $request, BaseHttpResponse $response)
    {
        $foodType = $this->foodTypeRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FOOD_TYPE_MODULE_SCREEN_NAME, $request, $foodType));

        return $response
            ->setPreviousUrl(route('food-type.index'))
            ->setNextUrl(route('food-type.edit', $foodType->id))
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
        $foodType = $this->foodTypeRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $foodType));

        page_title()->setTitle(trans('plugins/hotel::food-type.edit') . ' "' . $foodType->name . '"');

        return $formBuilder->create(FoodTypeForm::class, ['model' => $foodType])->renderForm();
    }

    /**
     * @param $id
     * @param FoodTypeRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, FoodTypeRequest $request, BaseHttpResponse $response)
    {
        $foodType = $this->foodTypeRepository->findOrFail($id);

        $foodType->fill($request->input());

        $this->foodTypeRepository->createOrUpdate($foodType);

        event(new UpdatedContentEvent(FOOD_TYPE_MODULE_SCREEN_NAME, $request, $foodType));

        return $response
            ->setPreviousUrl(route('food-type.index'))
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
            $foodType = $this->foodTypeRepository->findOrFail($id);

            $this->foodTypeRepository->delete($foodType);

            event(new DeletedContentEvent(FOOD_TYPE_MODULE_SCREEN_NAME, $request, $foodType));

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
            $foodType = $this->foodTypeRepository->findOrFail($id);
            $this->foodTypeRepository->delete($foodType);
            event(new DeletedContentEvent(FOOD_TYPE_MODULE_SCREEN_NAME, $request, $foodType));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
