<?php

namespace Botble\Gallery\Providers;

use Assets;
use Botble\Gallery\Services\GalleryService;
use Botble\Shortcode\Compilers\Shortcode;
use Eloquent;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use MetaBox;

class HookServiceProvider extends ServiceProvider
{
    /**
     * @throws \Throwable
     */
    public function boot()
    {
        add_action(BASE_ACTION_META_BOXES, [$this, 'addGalleryBox'], 13, 2);

        if (function_exists('shortcode')) {
            add_shortcode('gallery', trans('plugins/gallery::gallery.gallery_images'),
                trans('plugins/gallery::gallery.add_gallery_short_code'), [$this, 'render']);
            shortcode()->setAdminConfig('gallery', view('plugins/gallery::partials.short-code-admin-config')->render());
        }

        add_filter(BASE_FILTER_PUBLIC_SINGLE_DATA, [$this, 'handleSingleView'], 11);
    }

    /**
     * @param string $context
     * @param $object
     */
    public function addGalleryBox($context, $object)
    {
        if ($object && in_array(get_class($object),
                config('plugins.gallery.general.supported', [])) && $context == 'advanced') {
            Assets::addStylesDirectly(['vendor/core/plugins/gallery/css/admin-gallery.css'])
                ->addScriptsDirectly(['vendor/core/plugins/gallery/js/gallery-admin.js'])
                ->addScripts(['sortable']);

            MetaBox::addMetaBox('gallery_wrap', trans('plugins/gallery::gallery.gallery_box'), [$this, 'galleryMetaField'],
                get_class($object), $context, 'default');
        }
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function galleryMetaField()
    {
        $value = null;
        $args = func_get_args();
        if ($args[0] && $args[0]->id) {
            $value = gallery_meta_data($args[0]);
        }

        return view('plugins/gallery::gallery-box', compact('value'))->render();
    }

    /**
     * @param Shortcode $shortcode
     * @return string
     */
    public function render($shortcode)
    {
        return render_galleries($shortcode->limit ? $shortcode->limit : 6);
    }

    /**
     * @param Eloquent $slug
     * @return array|Eloquent
     *
     * @throws BindingResolutionException
     */
    public function handleSingleView($slug)
    {
        return (new GalleryService)->handleFrontRoutes($slug);
    }
}
