<?php

namespace Botble\Hotel\Tables;

use Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Hotel\Repositories\Interfaces\ServiceInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use RvMedia;
use Yajra\DataTables\DataTables;

class ServiceTable extends TableAbstract
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
     * ServiceTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param ServiceInterface $serviceRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ServiceInterface $serviceRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $serviceRepository;

        if (!Auth::user()->hasAnyPermission(['service.edit', 'service.destroy'])) {
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
                if (!Auth::user()->hasPermission('service.edit')) {
                    return $item->name;
                }
                return Html::link(route('service.edit', $item->id), $item->name);
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
                return $this->getOperations('service.edit', 'service.destroy', $item);
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
            'ht_services.id',
            'ht_services.name',
            'ht_services.image',
            'ht_services.price',
            'ht_services.currency_id',
            'ht_services.created_at',
            'ht_services.status',
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
                'name'  => 'ht_services.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'image'      => [
                'name'  => 'posts.image',
                'title' => trans('core/base::tables.image'),
                'width' => '70px',
            ],
            'name'       => [
                'name'  => 'ht_services.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'price'      => [
                'name'  => 'ht_services.price',
                'title' => trans('plugins/hotel::service.form.price'),
                'class' => 'text-center',
            ],
            'created_at' => [
                'name'  => 'ht_services.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'name'  => 'ht_services.status',
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
        return $this->addCreateButton(route('service.create'), 'service.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('service.deletes'), 'service.destroy', parent::bulkActions());
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
            'ht_services.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'ht_services.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'ht_services.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
