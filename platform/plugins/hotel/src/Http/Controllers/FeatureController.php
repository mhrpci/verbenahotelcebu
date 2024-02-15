<?php

namespace Botble\Hotel\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Hotel\Forms\FeatureForm;
use Botble\Hotel\Http\Requests\FeatureRequest;
use Botble\Hotel\Repositories\Interfaces\FeatureInterface;
use Botble\Hotel\Tables\FeatureTable;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class FeatureController extends BaseController
{
    /**
     * @var FeatureInterface
     */
    protected $featureRepository;

    /**
     * @param FeatureInterface $featureRepository
     */
    public function __construct(FeatureInterface $featureRepository)
    {
        $this->featureRepository = $featureRepository;
    }

    /**
     * @param FeatureTable $table
     * @return Factory|View
     * @throws Throwable
     */
    public function index(FeatureTable $table)
    {
        page_title()->setTitle(trans('plugins/hotel::feature.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/hotel::feature.create'));

        return $formBuilder->create(FeatureForm::class)->renderForm();
    }

    /**
     * @param FeatureRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(FeatureRequest $request, BaseHttpResponse $response)
    {
        $feature = $this->featureRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FEATURE_MODULE_SCREEN_NAME, $request, $feature));

        return $response
            ->setPreviousUrl(route('feature.index'))
            ->setNextUrl(route('feature.edit', $feature->id))
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
        $feature = $this->featureRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $feature));

        page_title()->setTitle(trans('plugins/hotel::feature.edit') . ' "' . $feature->name . '"');

        return $formBuilder->create(FeatureForm::class, ['model' => $feature])->renderForm();
    }

    /**
     * @param $id
     * @param FeatureRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, FeatureRequest $request, BaseHttpResponse $response)
    {
        $feature = $this->featureRepository->findOrFail($id);

        $feature->fill($request->input());

        $this->featureRepository->createOrUpdate($feature);

        event(new UpdatedContentEvent(FEATURE_MODULE_SCREEN_NAME, $request, $feature));

        return $response
            ->setPreviousUrl(route('feature.index'))
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
            $feature = $this->featureRepository->findOrFail($id);

            $this->featureRepository->delete($feature);

            event(new DeletedContentEvent(FEATURE_MODULE_SCREEN_NAME, $request, $feature));

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
            $feature = $this->featureRepository->findOrFail($id);
            $this->featureRepository->delete($feature);
            event(new DeletedContentEvent(FEATURE_MODULE_SCREEN_NAME, $request, $feature));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
