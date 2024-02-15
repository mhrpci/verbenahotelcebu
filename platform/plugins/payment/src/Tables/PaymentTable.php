<?php

namespace Botble\Payment\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Repositories\Interfaces\PaymentInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class PaymentTable extends TableAbstract
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
     * PaymentTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param PaymentInterface $paymentRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, PaymentInterface $paymentRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $paymentRepository;

        if (!Auth::user()->hasAnyPermission(['payment.show', 'payment.destroy'])) {
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
            ->editColumn('charge_id', function ($item) {
                return Html::link(route('payment.show', $item->id), $item->charge_id);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('payment_channel', function ($item) {
                return $item->payment_channel->label();
            })
            ->editColumn('amount', function ($item) {
                return $item->amount . ' ' . $item->currency;
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('payment.show', 'payment.destroy', $item);
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
            'payments.id',
            'payments.charge_id',
            'payments.amount',
            'payments.currency',
            'payments.payment_channel',
            'payments.created_at',
            'payments.status',
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
            'id'              => [
                'name'  => 'payments.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'charge_id'       => [
                'name'  => 'payments.charge_id',
                'title' => trans('plugins/payment::payment.charge_id'),
                'class' => 'text-left',
            ],
            'amount'          => [
                'name'  => 'payments.amount',
                'title' => trans('plugins/payment::payment.amount'),
                'class' => 'text-left',
            ],
            'payment_channel' => [
                'name'  => 'payments.payment_channel',
                'title' => trans('plugins/payment::payment.payment_channel'),
                'class' => 'text-left',
            ],
            'created_at'      => [
                'name'  => 'payments.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'          => [
                'name'  => 'payments.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('payment.deletes'), 'payment.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'payments.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => PaymentStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', PaymentStatusEnum::values()),
            ],
            'payments.charge_id'  => [
                'title'    => trans('plugins/payment::payment.charge_id'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'payments.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
