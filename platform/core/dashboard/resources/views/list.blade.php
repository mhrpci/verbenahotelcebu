@extends('core/base::layouts.master')
@section('content')
    <div id="dashboard-alerts">
        <verify-license-component verify-url="{{ route('settings.license.verify') }}" setting-url="{{ route('settings.options') }}"></verify-license-component>
    </div>
    {!! apply_filters(DASHBOARD_FILTER_ADMIN_NOTIFICATIONS, null) !!}
    <div class="row">
        {!! apply_filters(DASHBOARD_FILTER_TOP_BLOCKS, null) !!}
    </div>
    <div class="clearfix"></div>
    <div id="list_widgets" class="row">
        @foreach ($userWidgets as $widget)
            {!! $widget !!}
        @endforeach
        <div class="clearfix"></div>
    </div>

    @if (count($userWidgets) > 0)
        <a href="#" class="manage-widget"><i class="fa fa-plus"></i> {{ trans('core/dashboard::dashboard.manage_widgets') }}</a>
        @include('core/dashboard::partials.modals', compact('widgets'))
    @endif

@stop
