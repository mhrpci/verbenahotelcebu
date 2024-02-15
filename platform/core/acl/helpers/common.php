<?php

if (!function_exists('get_login_background')) {
    /**
     * @return string
     */
    function get_login_background(): string
    {
        $images = setting('login_screen_backgrounds', []);

        if (is_array($images)) {
            $images = array_filter($images);
        }

        if (empty($images) || !is_array($images)) {
            return url(Arr::random(config('core.acl.general.backgrounds', [])));
        }

        $image = Arr::random($images);

        if (!$image) {
            return url(Arr::random(config('core.acl.general.backgrounds', [])));
        }

        return RvMedia::getImageUrl($image);
    }
}
