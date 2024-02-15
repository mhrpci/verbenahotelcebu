<script>
    RV_MEDIA_URL = {!! json_encode(RvMedia::getUrls()) !!};
    RV_MEDIA_CONFIG = {!! json_encode([
        'permissions' => RvMedia::getPermissions(),
        'translations' => trans('core/media::media.javascript'),
        'pagination' => [
            'paged' => config('core.media.media.pagination.paged'),
            'posts_per_page' => config('core.media.media.pagination.per_page'),
            'in_process_get_media' => false,
            'has_more' =>  true,
        ],
        'chunk' => config('core.media.media.chunk'),
    ]) !!}
</script>
