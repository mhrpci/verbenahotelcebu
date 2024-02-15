<?php

namespace Botble\Hotel\Tables;

use Auth;
use BaseHelper;
use Botble\Hotel\Enums\BookingStatusEnum;
use Botble\Hotel\Repositories\Interfaces\BookingInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class BookingTable extends TableAbstract
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
     * BookingTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param BookingInterface $bookingRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, BookingInterface $bookingRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $bookingRepository;

        if (!Auth::user()->hasAnyPermission(['booking.edit', 'booking.destroy'])) {
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
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('amount', function ($item) {
                return format_price($item->amount, $item->currency_id);
            })
            ->editColumn('payment_status', function ($item) {
                return $item->payment->status->label() ? $item->payment->status->toHtml() : '&mdash;';
            })
            ->editColumn('payment_method', function ($item) {
                return $item->payment->payment_channel->label() ? $item->payment->payment_channel->label() : '&mdash;';
            })
            ->editColumn('customer_id', function ($item) {
                return $item->address->id ? $item->address->first_name . ' ' . $item->address->last_name : '&mdash;';
            })
            ->editColumn('room_id', function ($item) {
                return $item->room->room->id ? Html::link($item->room->room->url,
                    $item->room->room->name, ['target' => '_blank']) : '&mdash;';
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('booking.edit', 'booking.destroy', $item);
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
            'ht_bookings.id',
            'ht_bookings.created_at',
            'ht_bookings.status',
            'ht_bookings.amount',
            'ht_bookings.currency_id',
            'ht_bookings.payment_id',
        ];

        $query = $model
            ->select($select)
            ->with(['address', 'room', 'payment']);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, $select));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'          => [
                'name'  => 'ht_bookings.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'customer_id' => [
                'name'  => 'ht_bookings.customer_id',
                'title' => trans('plugins/hotel::booking.customer'),
                'class' => 'text-left',
                'orderable'  => false,
                'searchable' => false,
            ],
            'room_id'     => [
                'name'       => 'ht_bookings.room_id',
                'title'      => trans('plugins/hotel::booking.room'),
                'class'      => 'text-left',
                'orderable'  => false,
                'searchable' => false,
            ],
            'amount'      => [
                'name'  => 'ht_bookings.amount',
                'title' => trans('plugins/hotel::booking.amount'),
                'class' => 'text-left',
            ],
            'payment_method'  => [
                'name'  => 'ht_bookings.id',
                'title' => trans('plugins/hotel::booking.payment_method'),
                'class' => 'text-center',
            ],
            'payment_status'  => [
                'name'  => 'ht_bookings.id',
                'title' => trans('plugins/hotel::booking.payment_status_label'),
                'class' => 'text-center',
            ],
            'created_at'  => [
                'name'  => 'ht_bookings.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
                'class' => 'text-left',
            ],
            'status'      => [
                'name'  => 'ht_bookings.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
                'class' => 'text-left',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('booking.deletes'), 'booking.destroy', parent::bulkActions());
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
            'ht_bookings.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BookingStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BookingStatusEnum::values()),
            ],
            'ht_bookings.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
