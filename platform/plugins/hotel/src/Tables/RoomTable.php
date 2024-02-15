<?php

namespace Botble\Hotel\Tables;

use Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Hotel\Repositories\Interfaces\RoomInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use RvMedia;
use Yajra\DataTables\DataTables;

class RoomTable extends TableAbstract
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
     * RoomTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param RoomInterface $roomRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, RoomInterface $roomRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $roomRepository;

        if (!Auth::user()->hasAnyPermission(['room.edit', 'room.destroy'])) {
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
                if (!Auth::user()->hasPermission('room.edit')) {
                    return $item->name;
                }
                return Html::link(route('room.edit', $item->id), $item->name);
            })
            ->editColumn('image', function ($item) {
                return Html::image(RvMedia::getImageUrl($item->image, 'thumb', false, RvMedia::getDefaultImage()),
                    $item->name, ['width' => 50]);
            })
            ->editColumn('price', function ($item) {
                return format_price($item->price, $item->currency_id);
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
                return $this->getOperations('room.edit', 'room.destroy', $item);
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $model = $this->repository->getModel();
        $query = $model->select([
            'ht_rooms.id',
            'ht_rooms.name',
            'ht_rooms.images',
            'ht_rooms.price',
            'ht_rooms.currency_id',
            'ht_rooms.created_at',
            'ht_rooms.status',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'         => [
                'name'  => 'ht_rooms.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'image'      => [
                'name'       => 'ht_rooms.image',
                'title'      => trans('core/base::tables.image'),
                'width'      => '60px',
                'class'      => 'no-sort',
                'orderable'  => false,
                'searchable' => false,
            ],
            'name'       => [
                'name'  => 'ht_rooms.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'price'      => [
                'name'  => 'ht_rooms.price',
                'title' => trans('plugins/hotel::room.form.price'),
                'class' => 'text-center',
            ],
            'created_at' => [
                'name'  => 'ht_rooms.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'name'  => 'ht_rooms.status',
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
        return $this->addCreateButton(route('room.create'), 'room.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('room.deletes'), 'room.destroy', parent::bulkActions());
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
            'ht_rooms.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'ht_rooms.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'ht_rooms.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
