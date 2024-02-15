<!--====== BREADCRUMB PART START ======-->
<section class="breadcrumb-area" style="background-image: url({{ theme_option('rooms_banner') ? RvMedia::getImageUrl(theme_option('rooms_banner')) : Theme::asset()->url('img/bg/banner.jpg') }});">
    <div class="container">
        <div class="breadcrumb-text">
            <h2 class="page-title">{{ __('Booking Information') }}</h2>
            {!! Theme::partial('breadcrumb') !!}
        </div>
    </div>
</section>
<!--====== BREADCRUMB PART END ======-->

<section class="pt-120 pb-120">
    <div class="container">
        <div class="justify-content-center">
            @if (session('success_msg'))
                <div class="form-group">
                    <div class="alert alert-success">
                        {{ session('success_msg') }}
                    </div>
                </div>
            @endif
            <div class="booking-form-body room-details">
                <h3 class="mb-20">{{ __('Your booking information') }}</h3>
                <br>
                @include('plugins/hotel::booking-info', compact('booking'))
            </div>
        </div>
    </div>
</section>
