{!! Form::hidden('gallery', $value ? json_encode($value) : null, ['id' => 'gallery-data', 'class' => 'form-control']) !!}
<div>
    <div class="list-photos-gallery">
        <div class="row" id="list-photos-items">
            @if (!empty($value))
                @foreach ($value as $key => $item)
                    <div class="col-md-2 col-sm-3 col-4 photo-gallery-item" data-id="{{ $key }}" data-img="{{ Arr::get($item, 'img') }}" data-description="{{ Arr::get($item, 'description') }}">
                        <div class="gallery_image_wrapper">
                            <img src="{{ RvMedia::getImageUrl(Arr::get($item, 'img'), 'thumb') }}" alt="{{ trans('plugins/gallery::gallery.item') }}">
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="form-group">
        <a href="#" class="btn_select_gallery">{{ trans('plugins/gallery::gallery.select_image') }}</a>&nbsp;
        <a href="#" class="text-danger reset-gallery @if (empty($value)) hidden @endif">{{ trans('plugins/gallery::gallery.reset') }}</a>
    </div>
</div>

<div id="edit-gallery-item" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('plugins/gallery::gallery.update_photo_description') }}</strong></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body with-padding">
                <p><input type="text" class="form-control" id="gallery-item-description" placeholder="{{ trans('plugins/gallery::gallery.update_photo_description_placeholder') }}"></p>
            </div>

            <div class="modal-footer">
                <button class="float-left btn btn-danger" id="delete-gallery-item" href="#">{{ trans('plugins/gallery::gallery.delete_photo') }}</button>
                <button class="float-right btn btn-secondary" data-dismiss="modal">{{ trans('core/base::forms.cancel') }}</button>
                <button class="float-right btn btn-primary" id="update-gallery-item">{{ trans('core/base::forms.update') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- end Modal -->
