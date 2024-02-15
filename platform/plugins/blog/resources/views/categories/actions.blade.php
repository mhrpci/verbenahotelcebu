<a href="{{ route('categories.edit', $item->id) }}" class="btn btn-icon btn-primary" data-toggle="tooltip" data-original-title="{{ trans('core/base::tables.edit') }}"><i class="fa fa-edit"></i></a>
@if (!$item->is_default)
    <a href="#" class="btn btn-icon btn-danger deleteDialog" data-toggle="tooltip" data-section="{{ route('categories.destroy', $item->id) }}" role="button" data-original-title="{{ trans('core/base::tables.delete_entry') }}" >
        <i class="fa fa-trash"></i>
    </a>
@endif

