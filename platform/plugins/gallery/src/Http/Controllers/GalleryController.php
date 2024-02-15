<?php

namespace Botble\Gallery\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Traits\HasDeleteManyItemsTrait;
use Botble\Gallery\Forms\GalleryForm;
use Botble\Gallery\Tables\GalleryTable;
use Botble\Gallery\Http\Requests\GalleryRequest;
use Botble\Gallery\Repositories\Interfaces\GalleryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;

class GalleryController extends BaseController
{
    use HasDeleteManyItemsTrait;

    /**
     * @var GalleryInterface
     */
    protected $galleryRepository;

    /**
     * @param GalleryInterface $galleryRepository
     */
    public function __construct(GalleryInterface $galleryRepository)
    {
        $this->galleryRepository = $galleryRepository;
    }

    /**
     * @param GalleryTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(GalleryTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/gallery::gallery.galleries'));

        return $dataTable->renderTable();
    }

    /**
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/gallery::gallery.create'));

        return $formBuilder->create(GalleryForm::class)->renderForm();
    }

    /**
     * @param GalleryRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(GalleryRequest $request, BaseHttpResponse $response)
    {
        $gallery = $this->galleryRepository->getModel();
        $gallery->fill($request->input());
        $gallery->user_id = Auth::id();

        $gallery = $this->galleryRepository->createOrUpdate($gallery);

        event(new CreatedContentEvent(GALLERY_MODULE_SCREEN_NAME, $request, $gallery));

        return $response
            ->setPreviousUrl(route('galleries.index'))
            ->setNextUrl(route('galleries.edit', $gallery->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $gallery = $this->galleryRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $gallery));

        page_title()->setTitle(trans('plugins/gallery::gallery.edit') . ' "' . $gallery->name . '"');

        return $formBuilder->create(GalleryForm::class, ['model' => $gallery])->renderForm();
    }

    /**
     * @param int $id
     * @param GalleryRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, GalleryRequest $request, BaseHttpResponse $response)
    {
        $gallery = $this->galleryRepository->findOrFail($id);
        $gallery->fill($request->input());

        $this->galleryRepository->createOrUpdate($gallery);

        event(new UpdatedContentEvent(GALLERY_MODULE_SCREEN_NAME, $request, $gallery));

        return $response
            ->setPreviousUrl(route('galleries.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $gallery = $this->galleryRepository->findOrFail($id);
            $this->galleryRepository->delete($gallery);
            event(new DeletedContentEvent(GALLERY_MODULE_SCREEN_NAME, $request, $gallery));

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
        return $this->executeDeleteItems($request, $response, $this->galleryRepository, GALLERY_MODULE_SCREEN_NAME);
    }
}
