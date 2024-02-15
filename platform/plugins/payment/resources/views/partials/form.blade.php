<link rel="stylesheet" href="{{ asset('vendor/core/plugins/payment/libraries/card/card.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/core/plugins/payment/css/payment.css') }}">

<div class="checkout-wrapper">
    <div>
        <form action="{{ route('payments.checkout') }}" method="post" class="payment-checkout-form">
            @csrf
            <input type="hidden" name="name" value="{{ $name }}">
            <input type="hidden" name="amount" value="{{ $amount }}">
            <input type="hidden" name="currency" value="{{ $currency }}">
            <input type="hidden" name="return_url" value="{{ $returnUrl }}">
            <input type="hidden" name="callback_url" value="{{ $callbackUrl }}">
            {!! apply_filters(PAYMENT_FILTER_PAYMENT_PARAMETERS, null) !!}
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

                {!! apply_filters(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, null, compact('name', 'amount', 'currency')) !!}

                @if (setting('payment_cod_status') == 1)
                    <li class="list-group-item">
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

            <br>
            <div class="text-center">
                <button class="payment-checkout-btn btn btn-info" data-processing-text="{{ __('Processing. Please wait...') }}" data-error-header="{{ __('Error') }}">{{ __('Checkout') }}</button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('vendor/core/plugins/payment/libraries/card/card.js') }}"></script>
@if (setting('payment_stripe_status') == 1)
    <script src="{{ asset('https://js.stripe.com/v2/') }}"></script>
@endif
<script src="{{ asset('vendor/core/plugins/payment/js/payment.js') }}"></script>
