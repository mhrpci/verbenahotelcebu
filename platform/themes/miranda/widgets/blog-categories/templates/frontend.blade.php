@php
    $categories = app(\Botble\Blog\Repositories\Interfaces\CategoryInterface::class)
        ->getModel()
        ->where('status', \Botble\Base\Enums\BaseStatusEnum::PUBLISHED)
        ->orderBy('posts_count', 'DESC')
        ->orderBy('order', 'DESC')
        ->orderBy('created_at', 'DESC')
        ->withCount(['posts' => function ($query) {
            $query->where('posts.status', \Botble\Base\Enums\BaseStatusEnum::PUBLISHED);
        }])
        ->take($config['number_display'])
        ->with(['slugable'])
        ->get();
@endphp

<div class="widget categories-widget mb-40">
    <h5 class="widget-title">{{ $config['name'] }}</h5>
    <ul>
        @foreach($categories as $category)
        <li>
            <a href="{{ $category->url }}">{{ $category->name }}<span>{{ $category->posts_count }}</span></a>
        </li>
        @endforeach
    </ul>
</div>
