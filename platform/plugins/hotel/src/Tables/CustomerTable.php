<?php

namespace Botble\Hotel\Tables;

use Auth;
use BaseHelper;
use Botble\Hotel\Repositories\Interfaces\CustomerInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class CustomerTable extends TableAbstract
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
     * CustomerTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param CustomerInterface $customerRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, CustomerInterface $customerRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $customerRepository;

        if (!Auth::user()->hasAnyPermission(['customer.edit', 'customer.destroy'])) {
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
            ->editColumn('first_name', function ($item) {
                if (!Auth::user()->hasPermission('customer.edit')) {
                    return $item->getFullName();
                }

                return Html::link(route('customer.edit', $item->id), $item->getFullName());
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('customer.edit', 'customer.destroy', $item);
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
            'ht_customers.id',
            'ht_customers.first_name',
            'ht_customers.last_name',
            'ht_customers.email',
            'ht_customers.created_at',
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
                'name'  => 'ht_customers.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'first_name' => [
                'name'  => 'ht_customers.first_name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'email'      => [
                'name'  => 'ht_customers.email',
                'title' => trans('core/base::tables.email'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name'  => 'ht_customers.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        return $this->addCreateButton(route('customer.create'), 'customer.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('customer.deletes'), 'customer.destroy', parent::bulkActions());
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
            'ht_customers.first_name' => [
                'title'    => trans('plugins/hotel::customer.form.first_name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'ht_customers.last_name'  => [
                'title'    => trans('plugins/hotel::customer.form.lst_name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'ht_customers.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
