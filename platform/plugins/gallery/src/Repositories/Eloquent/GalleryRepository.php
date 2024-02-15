<?php

namespace Botble\Gallery\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Gallery\Repositories\Interfaces\GalleryInterface;

class GalleryRepository extends RepositoriesAbstract implements GalleryInterface
{

    /**
     * {@inheritDoc}
     */
    public function getAll(array $with = ['slugable', 'user'])
    {
        $data = $this->model
            ->with($with)
            ->where('galleries.status', BaseStatusEnum::PUBLISHED)
            ->orderBy('galleries.order')
            ->orderBy('galleries.created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getDataSiteMap()
    {
        $data = $this->model
            ->with('slugable')
            ->where('galleries.status', BaseStatusEnum::PUBLISHED)
            ->select('galleries.*')
            ->orderBy('galleries.order', 'asc')
            ->orderBy('galleries.created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getFeaturedGalleries($limit, array $with = ['slugable', 'user'])
    {
        $data = $this->model
            ->with($with)
            ->where([
                'galleries.status'   => BaseStatusEnum::PUBLISHED,
                'galleries.is_featured' => 1,
            ])
            ->select([
                'galleries.id',
                'galleries.name',
                'galleries.user_id',
                'galleries.image',
                'galleries.created_at',
            ])
            ->orderBy('galleries.order')
            ->orderBy('galleries.created_at', 'desc')
            ->limit($limit);

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
