<div class="blog-loop">
    @foreach ($posts as $post)
        <div class="post-box mb-40">
            <div class="post-media">
                <img src="{{ RvMedia::getImageUrl($post->image, '770x460', false, RvMedia::getDefaultImage()) }}" alt="{{ $post->name }}">
            </div>
            <div class="post-desc">
                @foreach($post->categories as $category)
                    <a href="{{ $category->url }}" class="cat">{{ $category->name }}</a>
                @endforeach
                <h2>
                    <a href="{{ $post->url }}">{{ $post->name }}</a>
                </h2>
                <ul class="post-meta">
                    <li><a href="{{ $post->url }}"><i class="far fa-eye"></i>{{ number_format($post->views) }} {{ __('Views') }}</a></li>
                    <li><a href="{{ $post->url }}"><i class="far fa-calendar-alt"></i>{{ $post->created_at->format('d M, Y') }}</a></li>
                </ul>
                <p>{{ $post->description }}</p>

                <div class="post-footer">
                    <div class="author">
                        <a href="{{ $post->url }}">
                            <img src="{{ $post->author->avatar_url }}" alt="{{ $post->author->getFullName() }}" width="40" style="border-radius: 50%">
                            {{ $post->author->getFullName() }}
                        </a>
                    </div>
                    <div class="read-more">
                        <a href="{{ $post->url }}"><i class="far fa-arrow-right"></i>{{ __('Read More') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {!! $posts->withQueryString()->links() !!}
</div>
