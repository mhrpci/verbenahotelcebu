<div class="form-group">
    <label class="control-label">{{ trans('plugins/payment::payment.payment_name') }}</label>
    <input type="text" name="name" class="form-control" data-shortcode-attribute="attribute" placeholder="{{ trans('plugins/payment::payment.payment_name') }}">
</div>

<div class="form-group">
    <label class="control-label">{{ trans('plugins/payment::payment.amount') }}</label>
    <input type="number" name="amount" class="form-control" value="1" data-shortcode-attribute="attribute" placeholder="{{ trans('plugins/payment::payment.amount') }}">
</div>

<div class="form-group">
    <label class="control-label">{{ trans('plugins/payment::payment.currency') }}</label>
    <input type="text" name="currency" class="form-control" value="USD" data-shortcode-attribute="attribute" placeholder="{{ trans('plugins/payment::payment.currency') }}">
</div>

<div class="form-group">
    <label class="control-label">{{ trans('plugins/payment::payment.callback_url') }}</label>
    <input type="text" name="callback_url" class="form-control" value="/" data-shortcode-attribute="attribute" placeholder="{{ trans('plugins/payment::payment.callback_url') }}">
</div>

<div class="form-group">
    <label class="control-label">{{ trans('plugins/payment::payment.return_url') }}</label>
    <input type="text" name="return_url" class="form-control" value="/" data-shortcode-attribute="attribute" placeholder="{{ trans('plugins/payment::payment.return_url') }}">
</div>
