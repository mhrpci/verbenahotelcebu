<?php

namespace Botble\Base\Supports;

use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Setting\Models\Setting;
use File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Mimey\MimeTypes;
use RvMedia;

class BaseSeeder extends Seeder
{
    /**
     * @param string $folder
     * @return array
     */
    public function uploadFiles(string $folder): array
    {
        File::deleteDirectory(config('filesystems.disks.public.root') . '/' . $folder);
        MediaFile::where('url', 'LIKE', $folder . '/%')->forceDelete();
        MediaFolder::where('name', $folder)->forceDelete();

        $mimeType = new MimeTypes;

        $files = [];
        foreach (File::allFiles(database_path('seeders/files/' . $folder)) as $file) {
            $type = $mimeType->getMimeType(File::extension($file));
            $files[] = RvMedia::uploadFromPath($file, 0, $folder, $type);
        }

        return $files;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function activateAllPlugins(): array
    {
        Setting::where('key', 'activated_plugins')->delete();

        $plugins = array_values(scan_folder(plugin_path()));

        foreach ($plugins as $key => $plugin) {
            $content = get_file_data(plugin_path($plugin) . '/plugin.json');
            if (empty($content) || !Arr::get($content, 'ready', 1)) {
                Arr::forget($plugins, $key);
            }
        }

        Setting::create([
            'key'   => 'activated_plugins',
            'value' => json_encode($plugins),
        ]);

        return $plugins;
    }
}
