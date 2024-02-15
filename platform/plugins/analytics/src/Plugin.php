<?php

namespace Botble\Analytics;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Dashboard\Models\DashboardWidget;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Exception;

class Plugin extends PluginOperationAbstract
{
    /**
     * @throws Exception
     */
    public static function remove()
    {
        $widgets = app(DashboardWidgetInterface::class)
            ->getModel()
            ->whereIn('name', [
                'widget_analytics_general',
                'widget_analytics_page',
                'widget_analytics_browser',
                'widget_analytics_referrer',
            ])
            ->get();

        foreach ($widgets as $widget) {
            /**
             * @var DashboardWidget $widget
             */
            $widget->delete();
        }
    }
}
