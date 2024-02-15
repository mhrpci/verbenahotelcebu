<?php

namespace Botble\Dashboard\Supports;

use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Throwable;

class DashboardWidgetInstance
{
    /**
     * @var string
     */
    public $type = 'widget';

    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $icon;

    /**
     * @var string
     */
    public $color;

    /**
     * @var string
     */
    public $route;

    /**
     * @var string
     */
    public $bodyClass;

    /**
     * @var bool
     */
    public $isEqualHeight = true;

    /**
     * @var string
     */
    public $column;

    /**
     * @var string
     */
    public $permission;

    /**
     * @var int
     */
    public $statsTotal = 0;

    /**
     * @var bool
     */
    public $hasLoadCallback = false;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return DashboardWidgetInstance
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return DashboardWidgetInstance
     */
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return DashboardWidgetInstance
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return DashboardWidgetInstance
     */
    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return DashboardWidgetInstance
     */
    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     * @return DashboardWidgetInstance
     */
    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return string
     */
    public function getBodyClass(): string
    {
        return $this->bodyClass;
    }

    /**
     * @param string $bodyClass
     * @return DashboardWidgetInstance
     */
    public function setBodyClass(string $bodyClass): self
    {
        $this->bodyClass = $bodyClass;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEqualHeight(): bool
    {
        return $this->isEqualHeight;
    }

    /**
     * @param bool $isEqualHeight
     * @return DashboardWidgetInstance
     */
    public function setIsEqualHeight(bool $isEqualHeight): self
    {
        $this->isEqualHeight = $isEqualHeight;

        return $this;
    }

    /**
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * @param string $column
     * @return DashboardWidgetInstance
     */
    public function setColumn(string $column): self
    {
        $this->column = $column;

        return $this;
    }

    /**
     * @return string
     */
    public function getPermission(): string
    {
        return $this->permission;
    }

    /**
     * @param string $permission
     * @return DashboardWidgetInstance
     */
    public function setPermission(string $permission): self
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatsTotal(): int
    {
        return $this->statsTotal;
    }

    /**
     * @param int $statsTotal
     * @return DashboardWidgetInstance
     */
    public function setStatsTotal(int $statsTotal): self
    {
        $this->statsTotal = $statsTotal;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHasLoadCallback(): int
    {
        return $this->hasLoadCallback;
    }

    /**
     * @param bool $hasLoadCallback
     * @return DashboardWidgetInstance
     */
    public function setHasLoadCallback(bool $hasLoadCallback): self
    {
        $this->hasLoadCallback = $hasLoadCallback;

        return $this;
    }

    /**
     * @param array $widgets
     * @param Collection $widgetSettings
     * @return array
     * @throws Throwable
     */
    public function init($widgets, $widgetSettings)
    {
        if (!Auth::user()->hasPermission($this->permission)) {
            return $widgets;
        }

        $widget = $widgetSettings->where('name', $this->key)->first();
        $widgetSetting = $widget ? $widget->settings->first() : null;

        if (!$widget) {
            $widget = app(DashboardWidgetInterface::class)
                ->firstOrCreate(['name' => $this->key]);
        }

        $widget->title = $this->title;
        $widget->icon = $this->icon;
        $widget->color = $this->color;
        $widget->route = $this->route;
        if ($this->type === 'widget') {
            $widget->bodyClass = $this->bodyClass;
            $widget->column = $this->column;

            $data = [
                'id'   => $widget->id,
                'type' => $this->type,
                'view' => view('core/dashboard::widgets.base', compact('widget', 'widgetSetting'))->render(),
            ];

            if (empty($widgetSetting) || array_key_exists($widgetSetting->order, $widgets)) {
                $widgets[] = $data;
            } else {
                $widgets[$widgetSetting->order] = $data;
            }

            return $widgets;
        }

        $widget->statsTotal = $this->statsTotal;

        $widgets[$this->key] = [
            'id'   => $widget->id,
            'type' => $this->type,
            'view' => view('core/dashboard::widgets.stats', compact('widget', 'widgetSetting'))->render(),
        ];

        return $widgets;
    }
}
