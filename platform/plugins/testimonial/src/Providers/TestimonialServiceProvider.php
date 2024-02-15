<?php

namespace Botble\Testimonial\Providers;

use Illuminate\Routing\Events\RouteMatched;
use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Testimonial\Models\Testimonial;
use Botble\Testimonial\Repositories\Caches\TestimonialCacheDecorator;
use Botble\Testimonial\Repositories\Eloquent\TestimonialRepository;
use Botble\Testimonial\Repositories\Interfaces\TestimonialInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Language;

class TestimonialServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(TestimonialInterface::class, function () {
            return new TestimonialCacheDecorator(new TestimonialRepository(new Testimonial));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/testimonial')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web']);

        $this->app->booted(function () {
            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                Language::registerModule([Testimonial::class]);
            }
        });

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-testimonial',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/testimonial::testimonial.name',
                'icon'        => 'far fa-comment-dots',
                'url'         => route('testimonial.index'),
                'permissions' => ['testimonial.index'],
            ]);
        });
    }
}
