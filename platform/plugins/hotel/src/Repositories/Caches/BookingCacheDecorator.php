<?php

namespace Botble\Hotel\Repositories\Caches;

use Botble\Hotel\Repositories\Interfaces\BookingInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class BookingCacheDecorator extends CacheAbstractDecorator implements BookingInterface
{
    /**
     * {@inheritdoc}
     */
    public function getPendingBookings($select = ['*'], array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function countPendingBookings()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
