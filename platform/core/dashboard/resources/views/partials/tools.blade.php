<div class="tools">
    <a href="#" class="{{ Arr::get($settings, 'state', 'expand') }}" data-toggle="tooltip" title="{{ trans('core/dashboard::dashboard.collapse_expand') }}" data-state="{{ Arr::get($settings, 'state', 'expand') == 'collapse' ? 'expand' : 'collapse' }}"></a>
    <a href="#" class="reload" data-toggle="tooltip" title="{{ trans('core/dashboard::dashboard.reload') }}"></a>
    <a href="#" class="fullscreen" data-toggle="tooltip" data-original-title="{{ trans('core/dashboard::dashboard.fullscreen') }}" title="{{ trans('core/dashboard::dashboard.fullscreen') }}"> </a>
    <a href="#" class="remove" data-toggle="tooltip" title="{{ trans('core/dashboard::dashboard.hide') }}"></a>
</div>