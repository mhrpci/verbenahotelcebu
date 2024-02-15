@extends('core/base::layouts.master')
@section('content')
    {!! Form::open(['route' => ['slug.settings']]) !!}
        <div class="max-width-1200">
            <div class="flexbox-annotated-section">

                <div class="flexbox-annotated-section-annotation">
                    <div class="annotated-section-title pd-all-20">
                        <h2>{{ trans('packages/slug::slug.settings.title') }}</h2>
                    </div>
                    <div class="annotated-section-description pd-all-20 p-none-t">
                        <p class="color-note">{{ trans('packages/slug::slug.settings.description') }}</p>
                    </div>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content pd-all-20">
                        @foreach(SlugHelper::supportedModels() as $model => $name)
                            <div class="form-group">
                                <label class="text-title-field" for="{{ SlugHelper::getPermalinkSettingKey($model) }}">{{ $name }}</label>
                                <input type="text" class="next-input" name="{{ SlugHelper::getPermalinkSettingKey($model) }}" id="{{ SlugHelper::getPermalinkSettingKey($model) }}"
                                       value="{{ setting(SlugHelper::getPermalinkSettingKey($model), SlugHelper::getPrefix($model)) }}">
                                <input type="hidden" name="{{ SlugHelper::getPermalinkSettingKey($model) }}-model-key" value="{{ $model }}">
                                <span class="help-ts">
                                    {{ trans('packages/slug::slug.settings.preview') }}: <a href="javascript:void(0)">{{ url((string)setting(SlugHelper::getPermalinkSettingKey($model), SlugHelper::getPrefix($model))) }}/{{ Str::slug('your url here') }}</a>
                                </span>
                            </div>
                        @endforeach

                        <hr>

                        <div class="form-group">
                            <label class="text-title-field"
                                   for="slug_turn_off_automatic_url_translation_into_latin">{{ trans('packages/slug::slug.settings.turn_off_automatic_url_translation_into_latin') }}
                            </label>
                            <label class="hrv-label">
                                <input type="radio" name="slug_turn_off_automatic_url_translation_into_latin" class="hrv-radio"
                                       value="1"
                                       @if (setting('slug_turn_off_automatic_url_translation_into_latin', 0) == 1) checked @endif>{{ trans('core/setting::setting.general.yes') }}
                            </label>
                            <label class="hrv-label">
                                <input type="radio" name="slug_turn_off_automatic_url_translation_into_latin" class="hrv-radio"
                                       value="0"
                                       @if (setting('slug_turn_off_automatic_url_translation_into_latin', 0) == 0) checked @endif>{{ trans('core/setting::setting.general.no') }}
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="flexbox-annotated-section" style="border: none">
                <div class="flexbox-annotated-section-annotation">
                    &nbsp;
                </div>
                <div class="flexbox-annotated-section-content">
                    <button class="btn btn-info" type="submit">{{ trans('core/setting::setting.save_settings') }}</button>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
@endsection
