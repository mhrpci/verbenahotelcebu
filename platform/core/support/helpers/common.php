<?php

use Carbon\Carbon;

if (!function_exists('format_time')) {
    /**
     * @param Carbon $timestamp
     * @param string $format
     * @return string
     */
    function format_time(Carbon $timestamp, $format = 'j M Y H:i')
    {
        $first = Carbon::create(0000, 0, 0, 00, 00, 00);
        if ($timestamp->lte($first)) {
            return '';
        }

        return $timestamp->format($format);
    }
}

if (!function_exists('date_from_database')) {
    /**
     * @param string $time
     * @param string $format
     * @return string
     */
    function date_from_database($time, $format = 'Y-m-d')
    {
        if (empty($time)) {
            return $time;
        }

        return format_time(Carbon::parse($time), $format);
    }
}

if (!function_exists('human_file_size')) {
    /**
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    function human_file_size($bytes, $precision = 2): string
    {
        $units = ['B', 'kB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return number_format($bytes, $precision, ',', '.') . ' ' . $units[$pow];
    }
}

if (!function_exists('get_file_data')) {
    /**
     * @param string $file
     * @param bool $toArray
     * @return bool|mixed
     */
    function get_file_data($file, $toArray = true)
    {
        $file = File::get($file);
        if (!empty($file)) {
            if ($toArray) {
                return json_decode($file, true);
            }
            return $file;
        }
        if (!$toArray) {
            return null;
        }
        return [];
    }
}

if (!function_exists('json_encode_prettify')) {
    /**
     * @param array $data
     * @return string
     */
    function json_encode_prettify($data)
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}

if (!function_exists('save_file_data')) {
    /**
     * @param string $path
     * @param array|string $data
     * @param bool $json
     * @return bool|mixed
     */
    function save_file_data($path, $data, $json = true)
    {
        try {
            if ($json) {
                $data = json_encode_prettify($data);
            }
            if (!File::isDirectory(File::dirname($path))) {
                File::makeDirectory(File::dirname($path), 493, true);
            }
            File::put($path, $data);

            return true;
        } catch (Exception $exception) {
            info($exception->getMessage());
            return false;
        }
    }
}

if (!function_exists('scan_folder')) {
    /**
     * @param string $path
     * @param array $ignoreFiles
     * @return array
     */
    function scan_folder($path, $ignoreFiles = [])
    {
        try {
            if (File::isDirectory($path)) {
                $data = array_diff(scandir($path), array_merge(['.', '..', '.DS_Store'], $ignoreFiles));
                natsort($data);
                return $data;
            }
            return [];
        } catch (Exception $exception) {
            return [];
        }
    }
}
