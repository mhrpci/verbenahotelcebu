<?php

namespace Botble\Gallery\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface GalleryInterface extends RepositoryInterface
{

    /**
     * Get all galleries.
     *
     * @param array $with
     * @return mixed
     */
    public function getAll(array $with = ['slugable', 'user']);

    /**
     * @return mixed
     */
    public function getDataSiteMap();

    /**
     * @param int $limit
     * @param array $with
     */
    public function getFeaturedGalleries($limit, array $with = ['slugable', 'user']);
}
