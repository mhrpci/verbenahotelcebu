<?php

namespace Botble\Hotel\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface RoomInterface extends RepositoryInterface
{
    /**
     * @param array $filters
     * @param array $params
     * @return mixed
     */
    public function getRooms($filters = [], $params = []);

    /**
     * @param int $roomId
     * @param int $limit
     * @return mixed
     */
    public function getRelatedRooms(int $roomId, $limit = 4);
}
