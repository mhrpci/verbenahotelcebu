@extends('core/base::layouts.master')
@section('content')
    <div id="main-settings">
        <license-component
            verify-url="{{ route('settings.license.verify') }}"
            activate-license-url="{{ route('settings.license.activate') }}"
            deactivate-license-url="{{ route('settings.license.deactivate') }}"
        ></license-component>
    </div>
    {!! Form::open(['route' => ['settings.edit']]) !!}
        <div class="max-width-1200">
            <div class="flexbox-annotated-section">

                <div class="flexbox-annotated-section-annotation">
                    <div class="annotated-section-title pd-all-20">
                        <h2>{{ trans('core/setting::setting.general.general_block') }}</h2>
                    </div>
                    <div class="annotated-section-description pd-all-20 p-none-t">
                        <p class="color-note">{{ trans('core/setting::setting.general.description') }}</p>
                    </div>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content pd-all-20">
                        <div class="form-group">
                            <label class="text-title-field"
                                   for="admin_email">{{ trans('core/setting::setting.general.admin_email') }}</label>
                            <input type="email" class="next-input" name="admin_email" id="admin_email"
                                   value="{{ setting('admin_email') }}">
                        </div>

                        <div class="form-group">
                            <label class="text-title-field"
                                   for="time_zone">{{ trans('core/setting::setting.general.time_zone') }}
                            </label>
                            <div class="ui-select-wrapper">
                                <select name="time_zone" class="ui-select form-control select-search-full" id="time_zone">
                                    @foreach(DateTimeZone::listIdentifiers() as $timezone)
                                        <option value="{{ $timezone }}" @if (setting('time_zone', 'UTC') === $timezone) selected @endif>{{ $timezone }}</option>
                                    @endforeach
                                </select>
                                <svg class="svg-next-icon svg-next-icon-size-16">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                                </svg>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="text-title-field"
                                   for="locale">{{ trans('core/setting::setting.general.locale') }}
                            </label>
                            <div class="ui-select-wrapper">
                                <select name="locale" class="ui-select form-control select-search-full" id="locale">
                                    @php
                                        $defaultLocale = setting('locale', config('app.locale'));
                                        if (app()->environment('demo') && session('site-locale') && array_key_exists(session('site-locale'), \Botble\Base\Supports\Language::getAvailableLocales())) {
                                            $defaultLocale = session('site-locale');
                                        }
                                    @endphp
                                    @foreach (\Botble\Base\Supports\Language::getAvailableLocales() as $key => $locale)
                                        <option value="{{ $locale['locale'] }}" @if ($defaultLocale === $locale['locale']) selected @endif>{{ $locale['name'] }} - {{ $locale['locale'] }}</option>
                                    @endforeach
                                </select>
                                <svg class="svg-next-icon svg-next-icon-size-16">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                                </svg>
                            </div>
                        </div>

                        <div class="form-group">

                            <label class="text-title-field"
                                   for="locale_direction">{{ trans('core/setting::setting.general.locale_direction') }}
                            </label>
                            <label class="hrv-label">
                                <input type="radio" name="locale_direction" class="hrv-radio" value="ltr"
                                       @if (setting('locale_direction', 'ltr') == 'ltr') checked @endif>{{ trans('core/setting::setting.locale_direction_ltr') }}
                            </label>
                            <label class="hrv-label">
                                <input type="radio" name="locale_direction" class="hrv-radio" value="rtl"
                                       @if (setting('locale_direction', 'ltr') == 'rtl') checked @endif>{{ trans('core/setting::setting.locale_direction_rtl') }}
                            </label>
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="enable_send_error_reporting_via_email" value="0">
                            <label>
                                <input type="checkbox" class="hrv-checkbox" value="1" @if (setting('enable_send_error_reporting_via_email')) checked @endif name="enable_send_error_reporting_via_email">
                                {{ trans('core/setting::setting.general.enable_send_error_reporting_via_email') }}
                            </label>
                        </div>

                    </div>
                </div>

            </div>

            <div class="flexbox-annotated-section">

                <div class="flexbox-annotated-section-annotation">
                    <div class="annotated-section-title pd-all-20">
                        <h2>{{ trans('core/setting::setting.general.admin_appearance_title') }}</h2>
                    </div>
                    <div class="annotated-section-description pd-all-20 p-none-t">
                        <p class="color-note">{{ trans('core/setting::setting.general.admin_appearance_description') }}</p>
                    </div>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content pd-all-20">
                        <div class="form-group">
                            <label class="text-title-field"
                                   for="admin-logo">{{ trans('core/setting::setting.general.admin_logo') }}
                            </label>
                            <div class="admin-logo-image-setting">
                                {!! Form::mediaImage('admin_logo', setting('admin_logo'), ['allow_thumb' => false]) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="text-title-field"
                                   for="admin-favicon">{{ trans('core/setting::setting.general.admin_favicon') }}
                            </label>
                            <div class="admin-favicon-image-setting">
                                {!! Form::mediaImage('admin_favicon', setting('admin_favicon'), ['allow_thumb' => false]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="text-title-field"
                                   for="admin-login-screen-backgrounds">{{ trans('core/setting::setting.general.admin_login_screen_backgrounds') }}
                            </label>
                            <div class="admin-login-screen-backgrounds-setting">
                                {!! Form::mediaImages('login_screen_backgrounds[]', setting('login_screen_backgrounds')) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="text-title-field"
                                   for="admin_title">{{ trans('core/setting::setting.general.admin_title') }}</label>
                            <input data-counter="120" type="text" class="next-input" name="admin_title" id="admin_title"
                                   value="{{ setting('admin_title', config('app.name')) }}">
                        </div>

                        <div class="form-group">

                            <label class="text-title-field"
                                   for="rich_editor">{{ trans('core/setting::setting.general.rich_editor') }}
                            </label>
                            <label class="hrv-label">
                                <input type="radio" name="rich_editor" class="hrv-radio" value="ckeditor"
                                       @if (setting('rich_editor', 'ckeditor') == 'ckeditor') checked @endif>CkEditor
                            </label>
                            <label class="hrv-label">
                                <input type="radio" name="rich_editor" class="hrv-radio" value="tinymce"
                                       @if (setting('rich_editor', 'ckeditor') == 'tinymce') checked @endif>TinyMCE
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="text-title-field"
                                   for="default_admin_theme">{{ trans('core/setting::setting.general.default_admin_theme') }}
                            </label>
                            <div class="ui-select-wrapper">
                                <select name="default_admin_theme" class="ui-select" id="default_admin_theme">
                                    @foreach(Assets::getThemes() as $theme => $path)
                                        <option value="{{ $theme }}" @if (setting('default_admin_theme', config('core.base.general.default-theme')) === $theme) selected @endif>
                                            {{ Str::studly($theme) }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="svg-next-icon svg-next-icon-size-16">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                                </svg>
                            </div>
                        </div>

                        <div class="form-group">
                                <input type="hidden" name="enable_change_admin_theme" value="0">
                                <label><input type="checkbox" class="hrv-checkbox" value="1"
                                              @if (setting('enable_change_admin_theme')) checked @endif name="enable_change_admin_theme"> {{ trans('core/setting::setting.general.enable_change_admin_theme') }} </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flexbox-annotated-section">
                <div class="flexbox-annotated-section-annotation">
                    <div class="annotated-section-title pd-all-20">
                        <h2>{{ trans('core/setting::setting.general.cache_block') }}</h2>
                    </div>
                    <div class="annotated-section-description pd-all-20 p-none-t">
                        <p class="color-note">{{ trans('core/setting::setting.general.cache_description') }}</p>
                    </div>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content pd-all-20">

                        <div class="form-group">
                            <label class="text-title-field"
                                   for="enable_cache">{{ trans('core/setting::setting.general.enable_cache') }}
                            </label>
                            <label class="hrv-label">
                                <input type="radio" name="enable_cache" class="hrv-radio" value="1" @if (setting('enable_cache')) checked @endif>
                                {{ trans('core/setting::setting.general.yes') }}
                            </label>
                            <label class="hrv-label">
                                <input type="radio" name="enable_cache" class="hrv-radio" value="0" @if (!setting('enable_cache')) checked @endif>
                                {{ trans('core/setting::setting.general.no') }}
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="text-title-field"
                                   for="cache_time">{{ trans('core/setting::setting.general.cache_time') }}</label>
                            <input type="number" class="next-input" name="cache_time" id="cache_time"
                                   value="{{ setting('cache_time', 10) }}">
                        </div>

                        <div class="form-group">
                            <label class="text-title-field"
                                   for="enable_cache">{{ trans('core/setting::setting.general.cache_admin_menu') }}
                            </label>
                            <label class="hrv-label">
                                <input type="radio" name="cache_admin_menu_enable" class="hrv-radio" value="1" @if (setting('cache_admin_menu_enable')) checked @endif>
                                {{ trans('core/setting::setting.general.yes') }}
                            </label>
                            <label class="hrv-label">
                                <input type="radio" name="cache_admin_menu_enable" class="hrv-radio" value="0" @if (!setting('cache_admin_menu_enable')) checked @endif>
                                {{ trans('core/setting::setting.general.no') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {!! apply_filters(BASE_FILTER_AFTER_SETTING_CONTENT, null) !!}

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
