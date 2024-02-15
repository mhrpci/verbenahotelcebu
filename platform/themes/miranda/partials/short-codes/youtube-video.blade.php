@if (!empty($url))
    <div class="embed-responsive embed-responsive-16by9 mb30">
        <iframe class="embed-responsive-item" allowfullscreen frameborder="0" height="315" width="420" src="{{ $url }}"></iframe>
    </div>
@else
    <p>{{ __('Youtube URL is invalid.') }}</p>
@endif
