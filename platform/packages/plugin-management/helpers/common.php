<?php

if (!function_exists('plugin_path')) {
    /**
     * @return string
     */
    function plugin_path($path = null)
    {
        return platform_path('plugins' . DIRECTORY_SEPARATOR . $path);
    }
}

if (!function_exists('is_plugin_active')) {
    /**
     * @param string $alias
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    function is_plugin_active($alias)
    {
        $path = plugin_path($alias);

        if (!File::isDirectory($path) || !File::exists($path . '/plugin.json')) {
            return false;
        }

        $content = get_file_data($path . '/plugin.json');
        if (empty($content)) {
            return false;
        }

        return class_exists($content['provider']);
    }
}

if (!function_exists('get_active_plugins')) {
    /**
     * @return array
     */
    function get_active_plugins()
    {
        try {
            return json_decode(setting('activated_plugins', '[]'), true);
        } catch (Exception $exception) {
            return [];
        }
    }
}
