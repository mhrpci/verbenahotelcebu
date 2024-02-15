<?php

namespace Botble\Base\Traits;

use Illuminate\Support\ServiceProvider;

/**
 * @mixin ServiceProvider
 */
trait LoadAndPublishDataTrait
{
    /**
     * @var string
     */
    protected $namespace = null;

    /**
     * @var string
     */
    protected $basePath = null;

    /**
     * @param string $namespace
     * @return $this
     */
    public function setNamespace(string $namespace): self
    {
        $this->namespace = ltrim(rtrim($namespace, '/'), '/');

        return $this;
    }

    /**
     * Publish the given configuration file name (without extension) and the given module
     * @param array|string $fileNames
     * @return $this
     */
    public function loadAndPublishConfigurations($fileNames): self
    {
        if (!is_array($fileNames)) {
            $fileNames = [$fileNames];
        }
        foreach ($fileNames as $fileName) {
            $this->mergeConfigFrom($this->getConfigFilePath($fileName), $this->getDotedNamespace() . '.' . $fileName);
            if ($this->app->runningInConsole()) {
                $this->publishes([
                    $this->getConfigFilePath($fileName) => config_path($this->getDashedNamespace() . '/' . $fileName . '.php'),
                ], 'cms-config');
            }
        }

        return $this;
    }

    /**
     * Get path of the give file name in the given module
     * @param string $file
     * @return string
     */
    protected function getConfigFilePath($file): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/config/' . $file . '.php';
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath ?? platform_path();
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setBasePath($path): self
    {
        $this->basePath = $path;

        return $this;
    }

    /**
     * @return string
     */
    protected function getDashedNamespace(): string
    {
        return str_replace('.', '/', $this->namespace);
    }

    /**
     * @return string
     */
    protected function getDotedNamespace(): string
    {
        return str_replace('/', '.', $this->namespace);
    }

    /**
     * Publish the given configuration file name (without extension) and the given module
     * @param array|string $fileNames
     * @return $this
     */
    public function loadRoutes($fileNames = ['web']): self
    {
        if (!is_array($fileNames)) {
            $fileNames = [$fileNames];
        }

        foreach ($fileNames as $fileName) {
            $this->loadRoutesFrom($this->getRouteFilePath($fileName));
        }

        return $this;
    }

    /**
     * @param string $file
     * @return string
     */
    protected function getRouteFilePath($file): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/routes/' . $file . '.php';
    }

    /**
     * @return $this
     */
    public function loadAndPublishViews(): self
    {
        $this->loadViewsFrom($this->getViewsPath(), $this->getDashedNamespace());
        if ($this->app->runningInConsole()) {
            $this->publishes([$this->getViewsPath() => resource_path('views/vendor/' . $this->getDashedNamespace())],
                'cms-views');
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getViewsPath(): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/resources/views/';
    }

    /**
     * @return $this
     */
    public function loadAndPublishTranslations(): self
    {
        $this->loadTranslationsFrom($this->getTranslationsPath(), $this->getDashedNamespace());
        $this->publishes([$this->getTranslationsPath() => resource_path('lang/vendor/' . $this->getDashedNamespace())],
            'cms-lang');

        return $this;
    }

    /**
     * @return string
     */
    protected function getTranslationsPath(): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/resources/lang/';
    }

    /**
     * @return $this
     */
    public function loadMigrations(): self
    {
        $this->loadMigrationsFrom($this->getMigrationsPath());

        return $this;
    }

    /**
     * @return string
     */
    protected function getMigrationsPath(): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/database/migrations/';
    }

    /**
     * @param string|null $path
     * @return $this
     */
    public function publishAssets($path = null): self
    {
        if ($this->app->runningInConsole()) {
            if (empty($path)) {
                $path = 'vendor/core/' . $this->getDashedNamespace();
            }
            $this->publishes([$this->getAssetsPath() => public_path($path)], 'cms-public');
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getAssetsPath(): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/public/';
    }
}
