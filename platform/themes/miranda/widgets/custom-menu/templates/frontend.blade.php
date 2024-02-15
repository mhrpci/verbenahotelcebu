<div class="widget nav-widget mb-50">
    <div>
        <h4 class="widget-title">{{ $config['name'] }}</h4>
        {!!
            Menu::generateMenu(['slug' => $config['menu_id']])
        !!}
    </div>
</div>
