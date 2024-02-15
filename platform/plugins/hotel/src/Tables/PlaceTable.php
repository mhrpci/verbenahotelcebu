<?php

namespace Botble\Hotel\Tables;

use Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Hotel\Repositories\Interfaces\PlaceInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use RvMedia;
use Yajra\DataTables\DataTables;

class PlaceTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * PlaceTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param PlaceInterface $placeRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, PlaceInterface $placeRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $placeRepository;

        if (!Auth::user()->hasAnyPermission(['place.edit', 'place.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('place.edit')) {
                    return $item->name;
                }
                return Html::link(route('place.edit', $item->id), $item->name);
            })
            ->editColumn('image', function ($item) {
                return Html::image(RvMedia::getImageUrl($item->image, 'thumb', false, RvMedia::getDefaultImage()),
                    $item->name, ['width' => 50]);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('place.edit', 'place.destroy', $item);
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $model = $this->repository->getModel();
        $select = [
            'ht_places.id',
            'ht_places.name',
            'ht_places.image',
            'ht_places.created_at',
            'ht_places.status',
        ];

        $query = $model->select($select);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, $select));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'         => [
                'name'  => 'ht_places.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'image'      => [
                'name'       => 'ht_foods.image',
                'title'      => trans('core/base::tables.image'),
                'width'      => '60px',
                'class'      => 'no-sort',
                'orderable'  => false,
                'searchable' => false,
            ],
            'name'       => [
                'name'  => 'ht_places.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name'  => 'ht_places.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'name'  => 'ht_places.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        return $this->addCreateButton(route('place.create'), 'place.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('place.deletes'), 'place.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->getBulkChanges();
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'ht_places.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'ht_places.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'ht_places.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
