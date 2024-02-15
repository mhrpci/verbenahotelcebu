<!--====== BREADCRUMB PART START ======-->
<section class="breadcrumb-area" style="background-image: url({{ theme_option('rooms_banner') ? RvMedia::getImageUrl(theme_option('rooms_banner')) : Theme::asset()->url('img/bg/banner.jpg') }});">
    <div class="container">
        <div class="breadcrumb-text">
            <h2 class="page-title">{{ __('Booking') }}</h2>
            {!! Theme::partial('breadcrumb') !!}
        </div>
    </div>
</section>
<!--====== BREADCRUMB PART END ======-->

<section class="pt-120 pb-120">
    <div class="container">
        @if (session()->has('success_msg'))
            <div class="form-group">
                <div class="alert alert-success">
                    {{ session('success_msg') }}
                </div>
            </div>
        @endif
        @if (session()->has('error_msg'))
            <div class="form-group">
                <div class="alert alert-warning">
                    {{ session('error_msg') }}
                </div>
            </div>
        @endif
        @if (isset($error_msg))
            <div class="form-group">
                <div class="alert alert-warning">
                    {{ $error_msg }}
                </div>
            </div>
        @endif
        @if (isset($errors) && count($errors) > 0)
            <div class="form-group">
                <div class="alert alert-warning">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="booking-form-body room-details">
                    <form action="{{ route('public.booking.checkout') }}" class="booking-form-main payment-checkout-form" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="amount" value="{{ $total }}">
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        <input type="hidden" name="start_date" value="{{ $startDate->format('d-m-Y') }}">
                        <input type="hidden" name="end_date" value="{{ $endDate->format('d-m-Y') }}">
                        <input type="hidden" name="adults" value="{{ $adults }}">
                        <input type="hidden" name="currency" value="{{ strtoupper(get_application_currency()->title) }}">
                        <input type="hidden" name="currency_id" value="{{ get_application_currency_id() }}">
                        <input type="hidden" name="callback_url" value="{{ route('public.payment.paypal.status') }}">

                        <h3 class="mb-20">{{ __('Add Extra Services') }}</h3>
                        <div class="room-booking-form p-0 mb-20">
                            @php
                                $chunks = $services->chunk(ceil($services->count() / 2));
                            @endphp
                            <div class="row">
                                @if (count($chunks) > 0)
                                    <div class="col-md-6">
                                        @foreach($chunks[0] as $service)
                                            <div class="form-group mb-20 custom-checkbox">
                                                <label for="service_{{ $service->id }}">
                                                    <input type="checkbox" class="service-item" id="service_{{ $service->id }}" name="services[]" value="{{ $service->id }}">
                                                    {{ $service->name }} <em>({{ format_price($service->price) }})</em>
                                                    <span></span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @if (count($chunks) > 1)
                                    <div class="col-md-6">
                                        @foreach($chunks[1] as $service)
                                            <div class="form-group mb-20 custom-checkbox">
                                                <label for="service_{{ $service->id }}">
                                                    <input type="checkbox" class="service-item" id="service_{{ $service->id }}" name="services[{{ $service->id }}]" value="{{ $service->id }}">
                                                    {{ $service->name }} <em>({{ format_price($service->price) }})</em>
                                                    <span></span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <h3 class="mb-20">{{ __('Your Information') }}</h3>
                        <div class="room-booking-form p-0">

                            <p class="mb-20">{{ __('Required fields are followed by *') }}</p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group input-group input-group-two mb-20">
                                            <label for="txt-first-name">{{ __('First Name') }} <span class="required">*</span></label>
                                            <input type="text" name="first_name" id="txt-first-name" required value="{{ old('first_name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group input-group input-group-two mb-20">
                                            <label for="txt-last-name">{{ __('Last Name') }} <span class="required">*</span></label>
                                            <input type="text" name="last_name" id="txt-last-name" required value="{{ old('last_name') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group input-group input-group-two mb-20">
                                            <label for="txt-email">{{ __('Email') }} <span class="required">*</span></label>
                                            <input type="email" name="email" id="txt-email" required value="{{ old('email') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group input-group input-group-two mb-20">
                                            <label for="txt-phone">{{ __('Phone') }} <span class="required">*</span></label>
                                            <input type="text" name="phone" id="txt-phone" required value="{{ old('phone') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group input-group input-group-two mb-20">
                                            <label for="txt-address">{{ __('Address') }}</label>
                                            <input type="text" name="address" id="txt-address" value="{{ old('address') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group input-group input-group-two mb-20">
                                            <label for="txt-city">{{ __('City') }}</label>
                                            <input type="text" name="city" id="txt-city" value="{{ old('city') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group input-group input-group-two mb-20">
                                            <label for="txt-country">{{ __('Country') }}</label>
                                            <input type="text" name="country" id="txt-country" value="{{ old('country') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group input-group input-group-two mb-20">
                                            <label for="txt-zip">{{ __('Zip') }}</label>
                                            <input type="text" name="zip" id="txt-zip" value="{{ old('zip') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group input-group input-group-two left-icon mb-20">
                                    <label for="arrival_time">{{ __('Arrival') }}</label>
                                    <select name="arrival_time" id="arrival_time">
                                        <option>{{ __('I do not know') }}</option>
                                        <option>12:00 - 1:00 {{ __('AM') }}</option>
                                        <option>1:00 - 2:00 {{ __('AM') }}</option>
                                        <option>2:00 - 3:00 {{ __('AM') }}</option>
                                        <option>3:00 - 4:00 {{ __('AM') }}</option>
                                        <option>4:00 - 5:00 {{ __('AM') }}</option>
                                        <option>5:00 - 6:00 {{ __('AM') }}</option>
                                        <option>6:00 - 7:00 {{ __('AM') }}</option>
                                        <option>7:00 - 8:00 {{ __('AM') }}</option>
                                        <option>8:00 - 9:00 {{ __('AM') }}</option>
                                        <option>9:00 - 10:00 {{ __('AM') }}</option>
                                        <option>10:00 - 11:00 {{ __('AM') }}</option>
                                        <option>11:00 - 12:00 {{ __('AM') }}</option>
                                        <option>12:00 - 1:00 {{ __('PM') }}</option>
                                        <option>1:00 - 2:00 {{ __('PM') }}</option>
                                        <option>2:00 - 3:00 {{ __('PM') }}</option>
                                        <option>3:00 - 4:00 {{ __('PM') }}</option>
                                        <option>4:00 - 5:00 {{ __('PM') }}</option>
                                        <option>5:00 - 6:00 {{ __('PM') }}</option>
                                        <option>6:00 - 7:00 {{ __('PM') }}</option>
                                        <option>7:00 - 8:00 {{ __('PM') }}</option>
                                        <option>8:00 - 9:00 {{ __('PM') }}</option>
                                        <option>9:00 - 10:00 {{ __('PM') }}</option>
                                        <option>10:00 - 11:00 {{ __('PM') }}</option>
                                        <option>11:00 - 12:00 {{ __('PM') }}</option>
                                    </select>
                                </div>
                                <div class="form-group mb-20">
                                    <label for="requests">{{ __('Requests') }}</label>
                                    <textarea name="note" rows="3" id="requests" placeholder="{{ __('Write Something') }}...">{{ old('note') }}</textarea>
                                </div>

                                <div class="form-group mb-20">
                                    <label for="requests">{{ __('Payment method') }}</label>
                                    <ul class="list-group list_payment_method">
                                    @if (setting('payment_stripe_status') == 1)
                                        <li class="list-group-item">
                                            <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_stripe"
                                                   value="stripe" @if (!setting('default_payment_method') || setting('default_payment_method') == \Botble\Payment\Enums\PaymentMethodEnum::STRIPE) checked @endif data-toggle="collapse" data-target=".payment_stripe_wrap" data-parent=".list_payment_method">
                                            <label for="payment_stripe" class="text-left">
                                                {{ setting('payment_stripe_name', trans('plugins/payment::payment.payment_via_card')) }}
                                            </label>
                                            <div class="payment_stripe_wrap payment_collapse_wrap collapse @if (!setting('default_payment_method') || setting('default_payment_method') == \Botble\Payment\Enums\PaymentMethodEnum::STRIPE) show @endif">
                                                <div class="card-checkout">
                                                    <div class="form-group">
                                                        <div class="stripe-card-wrapper"></div>
                                                    </div>
                                                    <div class="form-group @if ($errors->has('number') || $errors->has('expiry')) has-error @endif">
                                                        <div class="row">
                                                            <div class="col-sm-9">
                                                                <input placeholder="{{ trans('plugins/payment::payment.card_number') }}"
                                                                       class="form-control" type="text" id="stripe-number" data-stripe="number">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <input placeholder="{{ trans('plugins/payment::payment.mm_yy') }}" class="form-control"
                                                                       type="text" id="stripe-exp" data-stripe="exp">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group @if ($errors->has('name') || $errors->has('cvc')) has-error @endif">
                                                        <div class="row">
                                                            <div class="col-sm-9">
                                                                <input placeholder="{{ trans('plugins/payment::payment.full_name') }}"
                                                                       class="form-control" id="stripe-name" type="text" data-stripe="name">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <input placeholder="{{ trans('plugins/payment::payment.cvc') }}" class="form-control"
                                                                       type="text" id="stripe-cvc" data-stripe="cvc">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="payment-stripe-key" data-value="{{ setting('payment_stripe_client_id') }}"></div>
                                            </div>
                                        </li>
                                    @endif
                                    @if (setting('payment_paypal_status') == 1)
                                        <li class="list-group-item">
                                            <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_paypal"
                                                   @if (setting('default_payment_method') == \Botble\Payment\Enums\PaymentMethodEnum::PAYPAL) checked @endif
                                                   value="paypal">
                                            <label for="payment_paypal" class="text-left">{{ setting('payment_paypal_name', trans('plugins/payment::payment.payment_via_paypal')) }}</label>
                                        </li>
                                    @endif

                                    {!! apply_filters(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, null, ['amount' => $total, 'currency' => strtoupper(get_application_currency()->title), 'name' => $room->name]) !!}

                                    @if (setting('payment_cod_status') == 1)
                                        <li class="list-group-item" style="border-top-width: 0;">
                                            <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_cod"
                                                   @if (setting('default_payment_method') == \Botble\Payment\Enums\PaymentMethodEnum::COD) checked @endif
                                                   value="cod" data-toggle="collapse" data-target=".payment_cod_wrap" data-parent=".list_payment_method">
                                            <label for="payment_cod" class="text-left">{{ setting('payment_cod_name', trans('plugins/payment::payment.payment_via_cod')) }}</label>
                                            <div class="payment_cod_wrap payment_collapse_wrap collapse @if (setting('default_payment_method') == \Botble\Payment\Enums\PaymentMethodEnum::COD) show @endif" style="padding: 15px 0;">
                                                {!! clean(setting('payment_cod_description')) !!}
                                            </div>
                                        </li>
                                    @endif
                                    @if (setting('payment_bank_transfer_status') == 1)
                                        <li class="list-group-item">
                                            <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_bank_transfer"
                                                   @if (setting('default_payment_method') == \Botble\Payment\Enums\PaymentMethodEnum::BANK_TRANSFER) checked @endif
                                                   value="bank_transfer" data-toggle="collapse" data-target=".payment_bank_transfer_wrap" data-parent=".list_payment_method">
                                            <label for="payment_bank_transfer" class="text-left">{{ setting('payment_bank_transfer_name', trans('plugins/payment::payment.payment_via_bank_transfer')) }}</label>
                                            <div class="payment_bank_transfer_wrap payment_collapse_wrap collapse @if (setting('default_payment_method') == \Botble\Payment\Enums\PaymentMethodEnum::BANK_TRANSFER) show @endif" style="padding: 15px 0;">
                                                {!! clean(setting('payment_bank_transfer_description')) !!}
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                                </div>

                                @if (setting('enable_captcha') && is_plugin_active('captcha'))
                                    <div class="form-group mb-20">
                                        {!! Captcha::display() !!}
                                    </div>
                                @endif

                                <div class="form-group mb-20 custom-checkbox">
                                    <label for="terms_conditions">
                                        <input type="checkbox" id="terms_conditions" name="terms_conditions" value="1" @if (old('terms_conditions') == 1) checked @endif> {{ __('Terms & conditions *') }}
                                        <span></span>
                                    </label>
                                </div>
                                <div class="form-group mb-0">
                                    <button type="submit" class="main-btn btn-filled payment-checkout-btn" data-processing-text="{{ __('Processing. Please wait...') }}" data-error-header="{{ __('Error') }}">{{ __('Checkout') }}</button>
                                </div>
                        </div>
                    </form>
                </div>
                <div class="room-details">
                    <div class="deatils-box">
                        <div class="room-rules clearfix mb-60 mt-60">
                            <h3 class="subtitle">{{ __('Hotel Rules') }}</h3>
                            <div class="room-rules-list">
                                {!! clean(theme_option('hotel_rules')) !!}
                            </div>
                        </div>
                        <div class="cancellation-box clearfix mb-60">
                            <h3 class="subtitle">{{ __('Cancellation') }}</h3>
                            {!! clean(theme_option('cancellation')) !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Blog Sidebar -->
            <div class="col-lg-4 col-md-8 col-sm-10">
                <div class="sidebar">
                    <div style="font-family: 'Old Standard TT', serif; color: #fff; font-size: 14px;">
                        <div style="position: relative">
                            <span class="room-type" style="background-color: #bead8e; color: #fff; padding: 3px 5px; text-transform: uppercase; position: absolute; top: 15px; left: 15px;">{{ $room->name }}</span>
                            <img src="{{ RvMedia::getImageUrl($room->image, '380x280', false, RvMedia::getDefaultImage()) }}" alt="{{ $room->name }}">
                        </div>
                        <div style="padding: 20px; background-color: #bead8e;">
                            <div class="text-center mb-20">
                                <p>{{ __('YOUR RESERVATION') }}</p>
                            </div>
                            <div>
                                <p>{{ __('Check-In') }}: {{ $startDate->format('l, d M, Y') }}</p>
                                <p>{{ __('Check-Out') }}: {{ $endDate->format('l, d M, Y') }}</p>
                                <p>{{ __('Number of guests') }}: {{ $adults }}</p>
                                <p>{{ __('Tax') }}: {{ format_price($taxAmount) }}</p>
                            </div>
                        </div>
                        <div class="pb-20 pt-20 text-center" style="font-size: 30px; background-color: #151516;">
                            <p>{{ __('Total') }}: <span class="total-amount-text">{{ format_price($total) }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
