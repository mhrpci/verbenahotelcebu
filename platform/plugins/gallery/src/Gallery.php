<?php

namespace Botble\Gallery;

use Botble\Gallery\Repositories\Interfaces\GalleryMetaInterface;
use Illuminate\Support\Arr;
use Theme;

class Gallery
{
    /**
     * @var GalleryMetaInterface
     */
    protected $galleryMetaRepository;

    /**
     * Gallery constructor.
     * @param GalleryMetaInterface $galleryMetaRepository
     */
    public function __construct(GalleryMetaInterface $galleryMetaRepository)
    {
        $this->galleryMetaRepository = $galleryMetaRepository;
    }

    /**
     * @param string | array $model
     * @return Gallery
     */
    public function registerModule($model)
    {
        if (!is_array($model)) {
            $model = [$model];
        }
        config([
            'plugins.gallery.general.supported' => array_merge(config('plugins.gallery.general.supported', []), $model),
        ]);

        return $this;
    }

    /**
     * @param string | array $model
     * @return Gallery
     */
    public function removeModule($model)
    {
        $models = config('plugins.gallery.general.supported', []);

        foreach ($models as $key  => $item) {
            if ($item == $model) {
                Arr::forget($models, $key);
                break;
            }
        }

        config(['plugins.gallery.general.supported' => $models]);

        return $this;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Eloquent|false $data
     * @throws \Exception
     */
    public function saveGallery($request, $data)
    {
        if ($data != false && in_array(get_class($data), config('plugins.gallery.general.supported', []))) {
            if (empty($request->input('gallery'))) {
                $this->galleryMetaRepository->deleteBy([
                    'reference_id'   => $data->id,
                    'reference_type' => get_class($data),
                ]);
            }
            $meta = $this->galleryMetaRepository->getFirstBy([
                'reference_id'   => $data->id,
                'reference_type' => get_class($data),
            ]);
            if (!$meta) {
                $meta = $this->galleryMetaRepository->getModel();
                $meta->reference_id = $data->id;
                $meta->reference_type = get_class($data);
            }

            $meta->images = $request->input('gallery');
            $this->galleryMetaRepository->createOrUpdate($meta);
        }
    }

    /**
     * @param \Eloquent|false $data
     * @return bool
     * @throws \Exception
     */
    public function deleteGallery($data)
    {
        if (in_array(get_class($data), config('plugins.gallery.general.supported', []))) {
            $this->galleryMetaRepository->deleteBy([
                'reference_id'   => $data->id,
                'reference_type' => get_class($data),
            ]);
        }

        return true;
    }

    /**
     * @return $this
     */
    public function registerAssets()
    {
        Theme::asset()
            ->usePath(false)
            ->add('lightgallery-css', asset('vendor/core/plugins/gallery/css/lightgallery.min.css'), [], [], '1.0.0')
            ->add('gallery-css', asset('vendor/core/plugins/gallery/css/gallery.css'), [], [], '1.0.0');

        Theme::asset()
            ->container('footer')
            ->usePath(false)
            ->add('lightgallery-js', asset('vendor/core/plugins/gallery/js/lightgallery.min.js'), ['jquery'], [], '1.0.0')
            ->add('imagesloaded', asset('vendor/core/plugins/gallery/js/imagesloaded.pkgd.min.js'), ['jquery'], [], '1.0.0')
            ->add('masonry', asset('vendor/core/plugins/gallery/js/masonry.pkgd.min.js'), ['jquery'], [], '1.0.0')
            ->add('gallery-js', asset('vendor/core/plugins/gallery/js/gallery.js'), ['jquery'], [], '1.0.0');

        return $this;
    }
}
