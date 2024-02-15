<?php

namespace Botble\Hotel\Repositories\Caches;

use Botble\Hotel\Repositories\Interfaces\RoomInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class RoomCacheDecorator extends CacheAbstractDecorator implements RoomInterface
{
    /**
     * {@inheritDoc}
     */
    public function getRooms($filters = [], $params = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getRelatedRooms(int $roomId, $limit = 4)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
