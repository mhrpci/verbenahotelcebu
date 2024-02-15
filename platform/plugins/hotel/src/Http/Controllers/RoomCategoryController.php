<?php

namespace Botble\Hotel\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Hotel\Forms\RoomCategoryForm;
use Botble\Hotel\Http\Requests\RoomCategoryRequest;
use Botble\Hotel\Repositories\Interfaces\RoomCategoryInterface;
use Botble\Hotel\Tables\RoomCategoryTable;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class RoomCategoryController extends BaseController
{
    /**
     * @var RoomCategoryInterface
     */
    protected $roomCategoryRepository;

    /**
     * @param RoomCategoryInterface $roomCategoryRepository
     */
    public function __construct(RoomCategoryInterface $roomCategoryRepository)
    {
        $this->roomCategoryRepository = $roomCategoryRepository;
    }

    /**
     * @param RoomCategoryTable $table
     * @return Factory|View
     * @throws Throwable
     */
    public function index(RoomCategoryTable $table)
    {
        page_title()->setTitle(trans('plugins/hotel::room-category.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/hotel::room-category.create'));

        return $formBuilder->create(RoomCategoryForm::class)->renderForm();
    }

    /**
     * @param RoomCategoryRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(RoomCategoryRequest $request, BaseHttpResponse $response)
    {
        $roomCategory = $this->roomCategoryRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(ROOM_CATEGORY_MODULE_SCREEN_NAME, $request, $roomCategory));

        return $response
            ->setPreviousUrl(route('room-category.index'))
            ->setNextUrl(route('room-category.edit', $roomCategory->id))
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
        $roomCategory = $this->roomCategoryRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $roomCategory));

        page_title()->setTitle(trans('plugins/hotel::room-category.edit') . ' "' . $roomCategory->name . '"');

        return $formBuilder->create(RoomCategoryForm::class, ['model' => $roomCategory])->renderForm();
    }

    /**
     * @param $id
     * @param RoomCategoryRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, RoomCategoryRequest $request, BaseHttpResponse $response)
    {
        $roomCategory = $this->roomCategoryRepository->findOrFail($id);

        $roomCategory->fill($request->input());

        $this->roomCategoryRepository->createOrUpdate($roomCategory);

        event(new UpdatedContentEvent(ROOM_CATEGORY_MODULE_SCREEN_NAME, $request, $roomCategory));

        return $response
            ->setPreviousUrl(route('room-category.index'))
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
            $roomCategory = $this->roomCategoryRepository->findOrFail($id);

            $this->roomCategoryRepository->delete($roomCategory);

            event(new DeletedContentEvent(ROOM_CATEGORY_MODULE_SCREEN_NAME, $request, $roomCategory));

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
            $roomCategory = $this->roomCategoryRepository->findOrFail($id);
            $this->roomCategoryRepository->delete($roomCategory);
            event(new DeletedContentEvent(ROOM_CATEGORY_MODULE_SCREEN_NAME, $request, $roomCategory));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
