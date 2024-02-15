<?php

namespace Botble\Hotel\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface PlaceInterface extends RepositoryInterface
{
    /**
     * @param int $placeId
     * @param int $limit
     * @return mixed
     */
    public function getRelatedPlaces(int $placeId, $limit = 3);
}
