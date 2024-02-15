<?php

namespace Botble\Table\Providers;

use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\ServiceProvider;

class TableServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        Helper::autoload(__DIR__ . '/../../helpers');

        $this->setNamespace('core/table')
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->publishAssets();
    }
}
