@if (is_plugin_active('blog'))
    @php
        $posts = get_recent_posts($config['number_display']);
    @endphp
    @if ($posts->count())
        <div class="widget popular-feeds mb-40">
            <h5 class="widget-title">{{ $config['name'] }}</h5>
            <div class="popular-feed-loop">
                @foreach ($posts as $post)
                <div class="single-popular-feed">
                    <div class="feed-img">
                        <img src="{{ RvMedia::getImageUrl($post->image, 'thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $post->name }}">
                    </div>
                    <div class="feed-desc">
                        <h6><a href="{{ $post->url }}">{{ $post->name }}</a></h6>
                        <span class="time"><i class="far fa-calendar-alt"></i> {{ $post->created_at->format('d M, Y') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif
@endif
