<?php

namespace Botble\Hotel\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Hotel\Repositories\Interfaces\RoomInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class RoomRepository extends RepositoriesAbstract implements RoomInterface
{
    /**
     * {@inheritDoc}
     */
    public function getRooms($filters = [], $params = [])
    {
        $filters = array_merge([
            'keyword'          => null,
            'room_category_id' => null,
        ], $filters);

        $params = array_merge([
            'condition' => [],
            'order_by'  => [
                'ht_rooms.created_at' => 'DESC',
            ],
            'take'      => null,
            'paginate'  => [
                'per_page'      => 10,
                'current_paged' => 1,
            ],
            'select'    => [
                'ht_rooms.*',
            ],
            'with'      => [
                'amenities',
                'slugable',
            ],
        ], $params);

        $this->model = $this->originalModel;

        if ($filters['keyword'] !== null) {
            $this->model = $this->model
                ->where('ht_rooms.name', 'LIKE', '%' . $filters['keyword'] . '%');
        }

        if ($filters['room_category_id'] !== null) {
            $this->model = $this->model->where('ht_rooms.room_category_id', $filters['room_category_id']);
        }

        $this->model->where('ht_rooms.status', BaseStatusEnum::PUBLISHED);

        return $this->advancedGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getRelatedRooms(int $roomId, $limit = 4)
    {
        $this->model = $this->originalModel;
        $this->model = $this->model
            ->where('id', '<>', $roomId);

        $params = [
            'condition' => [],
            'order_by'  => [
                'created_at' => 'DESC',
            ],
            'take'      => $limit,
            'paginate'  => [
                'per_page'      => 12,
                'current_paged' => 1,
            ],
            'select'    => [
                'ht_rooms.*',
            ],
            'with'      => [
                'amenities',
                'slugable',
            ],
        ];

        return $this->advancedGet($params);
    }
}
