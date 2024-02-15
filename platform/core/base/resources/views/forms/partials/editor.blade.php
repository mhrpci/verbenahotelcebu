@if (!(isset($attributes['without-buttons']) && $attributes['without-buttons'] == true))
    <div style="height: 34px;">
        @php $result = !empty($attributes['id']) ? $attributes['id'] : $name; @endphp
        <span class="editor-action-item action-show-hide-editor">
            <button class="btn btn-primary show-hide-editor-btn" type="button" data-result="{{ $result }}">{{ trans('core/base::forms.show_hide_editor') }}</button>
        </span>
        <span class="editor-action-item">
            <a href="#" class="btn_gallery btn btn-primary"
               data-result="{{ $result }}"
               data-multiple="true"
               data-action="media-insert-{{ setting('rich_editor', config('core.base.general.editor.primary')) }}">
                <i class="far fa-image"></i> {{ trans('core/media::media.add') }}
            </a>
        </span>
        @if (isset($attributes['with-short-code']) && $attributes['with-short-code'] == true && function_exists('shortcode'))
            <span class="editor-action-item list-shortcode-items">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle add_shortcode_btn_trigger" data-result="{{ $result }}" type="button" data-toggle="dropdown"><i class="fa fa-code"></i> {{ trans('core/base::forms.short_code') }}
                    </button>
                    <ul class="dropdown-menu">
                        @foreach ($shortcodes = shortcode()->getAll() as $key => $item)
                            <li>
                                <a href="{{ route('short-codes.ajax-get-admin-config', $key) }}" data-has-admin-config="{{ Arr::has($item, 'admin_config') }}" data-key="{{ $key }}" data-description="{{ $item['description'] }}">{{ $item['name'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </span>
            @once
                @push('footer')
                    <div class="modal fade short_code_modal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('core/base::forms.add_short_code') }}</strong></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>

                                <div class="modal-body with-padding">
                                    <form class="form-horizontal short-code-data-form">
                                        <input type="hidden" class="short_code_input_key">

                                        <div class="half-circle-spinner">
                                            <div class="circle circle-1"></div>
                                            <div class="circle circle-2"></div>
                                        </div>

                                        <div class="short-code-admin-config">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button class="float-left btn btn-secondary" data-dismiss="modal">{{ trans('core/base::tables.cancel') }}</button>
                                    <button class="float-right btn btn-primary add_short_code_btn">{{ trans('core/base::forms.add') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end Modal -->
                @endpush
            @endonce
        @endif

        {!! apply_filters(BASE_FILTER_FORM_EDITOR_BUTTONS, null) !!}
    </div>
    <div class="clearfix"></div>
@endif

{!! call_user_func_array([Form::class, setting('rich_editor', config('core.base.general.editor.primary'))], [$name, $value, $attributes]) !!}
