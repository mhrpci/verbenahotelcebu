'use strict';

var BPayment = BPayment || {};

BPayment.init = function () {
    let paymentMethod = $(document).find('input[name=payment_method]').first();

    if (paymentMethod.length) {
        paymentMethod.trigger('click').trigger('change');
        paymentMethod.closest('.list-group-item').find('.payment_collapse_wrap').addClass('show');
    }


    if ($('.stripe-card-wrapper').length > 0) {
        new Card({
            form: '.payment-checkout-form',
            container: '.stripe-card-wrapper',
            formSelectors: {
                numberInput: 'input#stripe-number',
                expiryInput: 'input#stripe-exp',
                cvcInput: 'input#stripe-cvc',
                nameInput: 'input#stripe-name'
            },
        });
    }

    $(document).on('change', '.js_payment_method', function () {
        $('.payment_collapse_wrap').removeClass('collapse').removeClass('show').removeClass('active');
    });

    $(document).on('click', '.payment-checkout-btn', function () {
        var _self = $(this);
        var form = _self.closest('form');
        _self.attr('disabled', 'disabled');
        var submitInitialText = _self.html();
        _self.html('<i class="fa fa-gear fa-spin"></i> ' + _self.data('processing-text'));
        if ($('input[name=payment_method]:checked').val() === 'stripe') {
            Stripe.setPublishableKey($('#payment-stripe-key').data('value'));
            Stripe.card.createToken(form, function (status, response) {
                if (response.error) {
                    if (typeof Botble != 'undefined') {
                        Botble.showError(response.error.message, _self.data('error-header'));
                    } else {
                        alert(response.error.message);
                    }
                    _self.removeAttr('disabled');
                    _self.html(submitInitialText);
                } else {
                    form.append($('<input type="hidden" name="stripeToken">').val(response.id));
                    form.submit();
                }
            });
        } else {
            form.submit();
        }
    });
};

$(document).ready(function () {
    BPayment.init();
});
