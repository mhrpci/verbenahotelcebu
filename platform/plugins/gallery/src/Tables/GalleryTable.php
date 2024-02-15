<?php

namespace Botble\Gallery\Tables;

use BaseHelper;
use Illuminate\Support\Facades\Auth;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Gallery\Repositories\Interfaces\GalleryInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class GalleryTable extends TableAbstract
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
     * GalleryTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param GalleryInterface $galleryRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, GalleryInterface $galleryRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $galleryRepository;

        if (!Auth::user()->hasAnyPermission(['galleries.edit', 'galleries.destroy'])) {
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
                if (!Auth::user()->hasPermission('galleries.edit')) {
                    return $item->name;
                }

                return Html::link(route('galleries.edit', $item->id), $item->name);
            })
            ->editColumn('image', function ($item) {
                return $this->displayThumbnail($item->image, ['width' => 70]);
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
                return $this->getOperations('galleries.edit', 'galleries.destroy', $item);
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
            'galleries.id',
            'galleries.name',
            'galleries.order',
            'galleries.created_at',
            'galleries.status',
            'galleries.image',
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
                'name'  => 'galleries.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'image'      => [
                'name'  => 'galleries.image',
                'title' => trans('core/base::tables.image'),
                'width' => '70px',
            ],
            'name'       => [
                'name'  => 'galleries.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'order'      => [
                'name'  => 'galleries.order',
                'title' => trans('core/base::tables.order'),
                'width' => '100px',
            ],
            'created_at' => [
                'name'  => 'galleries.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'name'  => 'galleries.status',
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
        return $this->addCreateButton(route('galleries.create'), 'galleries.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('galleries.deletes'), 'galleries.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'galleries.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'galleries.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|' . Rule::in(BaseStatusEnum::values()),
            ],
            'galleries.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
