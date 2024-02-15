@php
$tags = get_popular_tags($config['number_display']);
@endphp

<div class="widget popular-tag-widget mb-40">
    <h5 class="widget-title">{{ $config['name'] }}</h5>
    <ul>
        @foreach($tags as $tag)
            <li><a href="{{ $tag->url }}">{{ $tag->name }}</a></li>
        @endforeach
    </ul>
</div>
