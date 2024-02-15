<?php

namespace Botble\Base\Supports;

use Assets;
use Illuminate\Support\Arr;
use Throwable;

class Editor
{
    public function __construct()
    {
        add_action(BASE_ACTION_ENQUEUE_SCRIPTS, [$this, 'registerAssets'], 12);
    }

    public function registerAssets()
    {
        Assets::addScriptsDirectly(
            config('core.base.general.editor.' .
                setting('rich_editor', config('core.base.general.editor.primary')) . '.js')
        )
            ->addScriptsDirectly('vendor/core/core/base/js/editor.js');
    }

    /**
     * @param string $name
     * @param null $value
     * @param bool $withShortCode
     * @param array $attributes
     * @return string
     * @throws Throwable
     */
    public function render($name, $value = null, $withShortCode = false, array $attributes = [])
    {
        $attributes['class'] = Arr::get($attributes, 'class', '') .
            ' editor-' .
            setting('rich_editor', config('core.base.general.editor.primary'));

        $attributes['id'] = Arr::has($attributes, 'id') ? $attributes['id'] : $name;
        $attributes['with-short-code'] = $withShortCode;
        $attributes['rows'] = Arr::get($attributes, 'rows', 4);

        return view('core/base::forms.partials.editor', compact('name', 'value', 'attributes'))
            ->render();
    }
}
