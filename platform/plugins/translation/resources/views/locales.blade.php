@extends('core/base::layouts.master')
@section('content')
    <div class="widget meta-boxes">
        <div class="widget-title">
            <h4>&nbsp; {{ trans('plugins/translation::translation.locales') }}</h4>
        </div>
        <div class="widget-body box-translation">
            <div class="row">
                <div class="col-md-5">
                    <div class="main-form">
                        <div class="form-wrap">
                            <form class="add-locale-form" action="{{ route('translations.locales') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="locale_id" class="control-label">{{ trans('plugins/translation::translation.choose_language') }}</label>
                                    <select id="locale_id" name="locale" class="form-control select-search-full">
                                        <option>{{ trans('plugins/translation::translation.select_language') }}</option>
                                        @foreach ($locales as $key => $name)
                                            <option value="{{ $key }}"> {{ $name }} - {{ $key }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="submit">
                                    <button class="btn btn-primary" type="submit">{{ trans('plugins/translation::translation.add_new_language') }}</button>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="table-responsive">
                        <table class="table table-hover table-language table-header-color" style="background: #f1f1f1;">
                            <thead>
                            <tr>
                                <th class="text-left"><span>{{ trans('plugins/translation::translation.name') }}</span></th>
                                <th class="text-center"><span>{{ trans('plugins/translation::translation.locale') }}</span></th>
                                <th class="text-center"><span>{{ trans('plugins/translation::translation.flag') }}</span></th>
                                <th class="text-center"><span>{{ trans('plugins/translation::translation.actions') }}</span></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($existingLocales as $item)
                                    @include('plugins/translation::partials.locale-item', compact('item'))
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    @include('core/table::partials.modal-item', [
        'type'        => 'danger',
        'name'        => 'modal-confirm-delete',
        'title'       => trans('core/base::tables.confirm_delete'),
        'content'     => trans('plugins/translation::translation.confirm_delete_message'),
        'action_name' => trans('core/base::tables.delete'),
        'action_button_attributes' => [
            'class' => 'delete-crud-entry',
        ],
    ])
@stop
