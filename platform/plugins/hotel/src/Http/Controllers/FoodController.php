<?php

namespace Botble\Hotel\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Hotel\Forms\FoodForm;
use Botble\Hotel\Http\Requests\FoodRequest;
use Botble\Hotel\Repositories\Interfaces\FoodInterface;
use Botble\Hotel\Tables\FoodTable;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class FoodController extends BaseController
{
    /**
     * @var FoodInterface
     */
    protected $foodRepository;

    /**
     * @param FoodInterface $foodRepository
     */
    public function __construct(FoodInterface $foodRepository)
    {
        $this->foodRepository = $foodRepository;
    }

    /**
     * @param FoodTable $table
     * @return Factory|View
     * @throws Throwable
     */
    public function index(FoodTable $table)
    {
        page_title()->setTitle(trans('plugins/hotel::food.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/hotel::food.create'));

        return $formBuilder->create(FoodForm::class)->renderForm();
    }

    /**
     * @param FoodRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(FoodRequest $request, BaseHttpResponse $response)
    {
        $food = $this->foodRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FOOD_MODULE_SCREEN_NAME, $request, $food));

        return $response
            ->setPreviousUrl(route('food.index'))
            ->setNextUrl(route('food.edit', $food->id))
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
        $food = $this->foodRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $food));

        page_title()->setTitle(trans('plugins/hotel::food.edit') . ' "' . $food->name . '"');

        return $formBuilder->create(FoodForm::class, ['model' => $food])->renderForm();
    }

    /**
     * @param $id
     * @param FoodRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, FoodRequest $request, BaseHttpResponse $response)
    {
        $food = $this->foodRepository->findOrFail($id);

        $food->fill($request->input());

        $this->foodRepository->createOrUpdate($food);

        event(new UpdatedContentEvent(FOOD_MODULE_SCREEN_NAME, $request, $food));

        return $response
            ->setPreviousUrl(route('food.index'))
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
            $food = $this->foodRepository->findOrFail($id);

            $this->foodRepository->delete($food);

            event(new DeletedContentEvent(FOOD_MODULE_SCREEN_NAME, $request, $food));

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
            $food = $this->foodRepository->findOrFail($id);
            $this->foodRepository->delete($food);
            event(new DeletedContentEvent(FOOD_MODULE_SCREEN_NAME, $request, $food));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
