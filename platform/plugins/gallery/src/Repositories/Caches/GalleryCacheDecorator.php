<?php

namespace Botble\Gallery\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Gallery\Repositories\Interfaces\GalleryInterface;

class GalleryCacheDecorator extends CacheAbstractDecorator implements GalleryInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAll(array $with = ['slugable', 'user'])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getDataSiteMap()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getFeaturedGalleries($limit, array $with = ['slugable', 'user'])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
