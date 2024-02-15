<?php

namespace Botble\Hotel\Repositories\Eloquent;

use Botble\Hotel\Repositories\Interfaces\PlaceInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class PlaceRepository extends RepositoriesAbstract implements PlaceInterface
{
    /**
     * {@inheritDoc}
     */
    public function getRelatedPlaces(int $placeId, $limit = 3)
    {
        $this->model = $this->originalModel;
        $this->model = $this->model
            ->where('id', '<>', $placeId);

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
                'ht_places.*',
            ],
            'with'      => [
                'slugable',
            ],
        ];

        return $this->advancedGet($params);
    }
}
