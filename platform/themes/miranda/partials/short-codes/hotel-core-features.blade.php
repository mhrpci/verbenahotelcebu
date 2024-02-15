<section class="core-feature-section pt-40 pb-40">
    <div class="container">
        <div class="section-title text-center mb-50">
            <span class="title-tag">{!! clean($title) !!}</span>
            <h2>{!! clean($sub_title) !!}</h2>
        </div>
        <!-- Feature Loop -->
        <div class="row features-loop">
            @foreach($features as $feature)
                <div class="col-lg-4 col-sm-6 order-1">
                    <div class="feature-box dark-box wow fadeInLeft" data-wow-delay=".3s">
                        <div class="icon">
                            <i class="{{ $feature->icon }}"></i>
                        </div>
                        <h3>{{ $feature->name }}</h3>
                        <p>{{ $feature->description }}</p>
                        <span class="count">{{ ($loop->index < 9 ? '0' : '') . ($loop->index + 1) }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
