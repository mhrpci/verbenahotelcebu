<div id="{{ $name }}" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog {{ $modal_size }} @if (!$modal_size) @if (strlen($content) < 120) modal-xs @elseif (strlen($content) > 1000) modal-lg @endif @endif">
        <div class="modal-content">
            <div class="modal-header bg-{{ $type }}">
                <h4 class="modal-title"><i class="til_img"></i><strong>{!! $title !!}</strong></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body with-padding">
                {!! $content !!}
            </div>

            <div class="modal-footer">
                <button class="float-left btn btn-warning" data-dismiss="modal">{{ trans('core/base::tables.cancel') }}</button>
                <a class="float-right btn btn-{{ $type }}" id="{{ $action_id }}" href="#">{!! $action_name !!}</a>
            </div>
        </div>
    </div>
</div>
<!-- end Modal -->