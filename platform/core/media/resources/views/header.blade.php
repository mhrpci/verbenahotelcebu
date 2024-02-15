<meta name="csrf-token" content="{{ csrf_token() }}">

@foreach(config('core.media.media.libraries.stylesheets', []) as $css)
    <link href="{{ url($css) }}" rel="stylesheet" type="text/css"/>
@endforeach

@include('core/media::config')
