@if (get_payment_setting('status', RAZORPAY_PAYMENT_METHOD_NAME) == 1)
    <li class="list-group-item">
        <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_{{ RAZORPAY_PAYMENT_METHOD_NAME }}"
               value="{{ RAZORPAY_PAYMENT_METHOD_NAME }}" data-toggle="collapse" data-target=".payment_{{ RAZORPAY_PAYMENT_METHOD_NAME }}_wrap"
               data-parent=".list_payment_method"
               @if (setting('default_payment_method') == RAZORPAY_PAYMENT_METHOD_NAME) checked @endif
        >
        <label for="payment_{{ RAZORPAY_PAYMENT_METHOD_NAME }}">{{ get_payment_setting('name', RAZORPAY_PAYMENT_METHOD_NAME) }}</label>
        <div class="payment_{{ RAZORPAY_PAYMENT_METHOD_NAME }}_wrap payment_collapse_wrap collapse @if (setting('default_payment_method') == RAZORPAY_PAYMENT_METHOD_NAME) show @endif">
            <p>{!! get_payment_setting('description', RAZORPAY_PAYMENT_METHOD_NAME, __('Payment with Razorpay')) !!}</p>
        </div>
    </li>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        var razorpay = new Razorpay({
            key: "{{ get_payment_setting('key', RAZORPAY_PAYMENT_METHOD_NAME) }}",
            amount: '{{ $amount * 100 }}',
            name: '{{ $name }}',
            description: '{{ $name }}',
            order_id: '{{ $orderId }}',
            handler: function (transaction) {
                var form = $('.payment-checkout-form');
                form.append($('<input type="hidden" name="razorpay_payment_id">').val(transaction.razorpay_payment_id));
                form.append($('<input type="hidden" name="razorpay_order_id">').val(transaction.razorpay_order_id));
                form.append($('<input type="hidden" name="razorpay_signature">').val(transaction.razorpay_signature));
                form.submit();
            }
        });

        $('.payment-checkout-form').on('submit', function (e) {
            if ($('input[name=payment_method]:checked').val() === 'razorpay' && !$('input[name=razorpay_payment_id]').val()) {
                e.preventDefault();
            }
        });

        $(document).off('click', '.payment-checkout-btn').on('click', '.payment-checkout-btn', function (event) {
            event.preventDefault();
            var _self = $(this);
            _self.attr('disabled', 'disabled');
            if ($('input[name=payment_method]:checked').val() === 'razorpay') {
                razorpay.open();
            } else {
                $('.payment-checkout-form').submit();
            }
        });
    </script>
@endif
