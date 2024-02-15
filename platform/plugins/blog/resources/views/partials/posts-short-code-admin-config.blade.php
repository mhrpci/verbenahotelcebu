<div class="form-group">
    <label class="control-label">{{ trans('plugins/blog::base.number_posts_per_page') }}</label>
    <input type="number" name="paginate" class="form-control" value="{{ theme_option('number_of_posts_in_a_category', 12) }}" data-shortcode-attribute="attribute" placeholder="{{ trans('plugins/blog::base.number_posts_per_page') }}">
</div>
