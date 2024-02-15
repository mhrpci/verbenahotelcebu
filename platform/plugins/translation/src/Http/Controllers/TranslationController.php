<?php

namespace Botble\Translation\Http\Controllers;

use Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Language;
use Botble\Translation\Http\Requests\LocaleRequest;
use Botble\Translation\Http\Requests\TranslationRequest;
use Botble\Translation\Manager;
use Illuminate\Support\Facades\DB;
use File;
use Illuminate\Http\Request;
use Botble\Translation\Models\Translation;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Schema;
use Theme;

class TranslationController extends BaseController
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * TranslationController constructor.
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getIndex(Request $request)
    {
        page_title()->setTitle(trans('plugins/translation::translation.translations'));

        Assets::addScripts(['bootstrap-editable'])
            ->addStyles(['bootstrap-editable'])
            ->addScriptsDirectly('vendor/core/plugins/translation/js/translation.js')
            ->addStylesDirectly('vendor/core/plugins/translation/css/translation.css');

        $group = $request->input('group');

        $locales = $this->loadLocales();
        $groups = Translation::groupBy('group');
        $excludedGroups = $this->manager->getConfig('exclude_groups');
        if ($excludedGroups) {
            $groups->whereNotIn('group', $excludedGroups);
        }

        $groups = $groups->select('group')->get()->pluck('group', 'group');
        if ($groups instanceof Collection) {
            $groups = $groups->all();
        }
        $groups = ['' => trans('plugins/translation::translation.choose_a_group')] + $groups;
        $numChanged = Translation::where('group', $group)->where('status', Translation::STATUS_CHANGED)->count();

        $allTranslations = Translation::where('group', $group)->orderBy('key', 'asc')->get();
        $numTranslations = count($allTranslations);
        $translations = [];
        foreach ($allTranslations as $translation) {
            $translations[$translation->key][$translation->locale] = $translation;
        }

        return view('plugins/translation::index')
            ->with('translations', $translations)
            ->with('locales', $locales)
            ->with('groups', $groups)
            ->with('group', $group)
            ->with('numTranslations', $numTranslations)
            ->with('numChanged', $numChanged)
            ->with('editUrl', route('translations.group.edit', ['group' => $group]))
            ->with('deleteEnabled', $this->manager->getConfig('delete_enabled'));
    }

    /**
     * @return Collection
     */
    protected function loadLocales()
    {
        // Set the default locale as the first one.
        $locales = Translation::groupBy('locale')
            ->select('locale')
            ->get()
            ->pluck('locale');

        if ($locales instanceof Collection) {
            $locales = $locales->all();
        }
        $locales = array_merge([config('app.locale')], $locales);

        return array_unique($locales);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update(TranslationRequest $request, BaseHttpResponse $response)
    {
        $group = $request->input('group');

        if (!in_array($group, $this->manager->getConfig('exclude_groups'))) {
            $name = $request->input('name');
            $value = $request->input('value');

            [$locale, $key] = explode('|', $name, 2);
            $translation = Translation::firstOrNew([
                'locale' => $locale,
                'group'  => $group,
                'key'    => $key,
            ]);
            $translation->value = (string)$value ?: null;
            $translation->status = Translation::STATUS_CHANGED;
            $translation->save();
        }

        return $response;
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postImport(Request $request, BaseHttpResponse $response)
    {
        $counter = $this->manager->importTranslations($request->get('replace', false));

        return $response->setMessage(trans('plugins/translation::translation.import_done', compact('counter')));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function postPublish(Request $request, BaseHttpResponse $response)
    {
        if (!File::isWritable(resource_path('lang')) || !File::isWritable(resource_path('lang/vendor'))) {
            return $response
                ->setError(true)
                ->setMessage(trans('plugins/translation::translation.folder_is_not_writeable'));
        }

        $group = $request->input('group');

        $this->manager->exportTranslations($group, $group === '_json');

        return $response->setMessage(trans('plugins/translation::translation.done_publishing'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLocales()
    {
        page_title()->setTitle(trans('plugins/translation::translation.locales'));

        Assets::addScriptsDirectly('vendor/core/plugins/translation/js/locales.js');

        $existingLocales = Language::getAvailableLocales();
        $languages = Language::getListLanguages();
        $flags = Language::getListLanguageFlags();

        $locales = [];
        foreach ($languages as $item) {
            if (!in_array($item[0], $locales)) {
                $locales[$item[0]] = $item[2];
            }
        }

        return view('plugins/translation::locales', compact('existingLocales', 'locales', 'flags'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postLocales(LocaleRequest $request, BaseHttpResponse $response)
    {
        if (!File::isWritable(resource_path('lang')) || !File::isWritable(resource_path('lang/vendor'))) {
            return $response
                ->setError(true)
                ->setMessage(trans('plugins/translation::translation.folder_is_not_writeable'));
        }

        $defaultLocale = resource_path('lang/en');
        $locale = $request->input('locale');
        if (File::exists($defaultLocale)) {
            File::copyDirectory($defaultLocale, resource_path('lang/' . $locale));
        }

        $this->createLocaleInPath(resource_path('lang/vendor/core'), $locale);
        $this->createLocaleInPath(resource_path('lang/vendor/packages'), $locale);
        $this->createLocaleInPath(resource_path('lang/vendor/plugins'), $locale);

        $themeLocale = Arr::first(scan_folder(theme_path(Theme::getThemeName() . '/lang')));

        if ($themeLocale) {
            File::copy(theme_path(Theme::getThemeName() . '/lang/' . $themeLocale), resource_path('lang/' . $locale . '.json'));
        }

        return $response->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function deleteLocale($locale, BaseHttpResponse $response)
    {
        if ($locale !== 'en') {

            if (!File::isWritable(resource_path('lang')) || !File::isWritable(resource_path('lang/vendor'))) {
                return $response
                    ->setError(true)
                    ->setMessage(trans('plugins/translation::translation.folder_is_not_writeable'));
            }

            $defaultLocale = resource_path('lang/' . $locale);
            if (File::exists($defaultLocale)) {
                File::deleteDirectory($defaultLocale);
            }

            if (File::exists(resource_path('lang/' . $locale . '.json'))) {
                File::delete(resource_path('lang/' . $locale . '.json'));
            }

            $this->removeLocaleInPath(resource_path('lang/vendor/core'), $locale);
            $this->removeLocaleInPath(resource_path('lang/vendor/packages'), $locale);
            $this->removeLocaleInPath(resource_path('lang/vendor/plugins'), $locale);

            if (is_plugin_active('translation') && Schema::hasTable('translations')) {
                DB::table('translations')->where('locale', $locale)->delete();
            }
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param string $path
     * @param string $locale
     * @return int|void
     */
    protected function createLocaleInPath(string $path, $locale)
    {
        $folders = File::directories($path);

        foreach ($folders as $module) {
            foreach (File::directories($module) as $item) {
                if (File::name($item) == 'en') {
                    File::copyDirectory($item, $module . '/' . $locale);
                }
            }
        }

        return count($folders);
    }

    /**
     * @param string $path
     * @return int|void
     */
    protected function removeLocaleInPath(string $path, $locale)
    {
        $folders = File::directories($path);

        foreach ($folders as $module) {
            foreach (File::directories($module) as $item) {
                if (File::name($item) == $locale) {
                    File::deleteDirectory($item);
                }
            }
        }

        return count($folders);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getThemeTranslations(Request $request)
    {
        page_title()->setTitle(trans('plugins/translation::translation.theme-translations'));

        Assets::addScriptsDirectly('vendor/core/plugins/translation/js/theme-translations.js')
            ->addStylesDirectly('vendor/core/plugins/translation/css/theme-translations.css');

        $groups = Language::getAvailableLocales();
        $defaultLanguage = Arr::get($groups, 'en');

        if (!$request->has('ref_lang')) {
            $group = Arr::first($groups);
        } else {
            $group = Arr::first(Arr::where($groups, function ($item) use ($request) {
                return $item['locale'] == $request->input('ref_lang');
            }));
        }

        $translations = [];
        if ($group) {
            $jsonFile = resource_path('lang/' . $group['locale'] . '.json');

            if (!File::exists($jsonFile)) {
                $jsonFile = theme_path(Theme::getThemeName() . '/lang/' . $group['locale'] . '.json');
            }

            if (!File::exists($jsonFile)) {
                $languages = scan_folder(theme_path(Theme::getThemeName() . '/lang'));

                if (!empty($languages)) {
                    $jsonFile = theme_path(Theme::getThemeName() . '/lang/' . Arr::first($languages));
                }
            }

            if (File::exists($jsonFile)) {
                $translations = get_file_data($jsonFile, true);
            }

            if ($group['locale'] != 'en') {
                $defaultEnglishFile = theme_path(Theme::getThemeName() . '/lang/en.json');

                if ($defaultEnglishFile) {
                    $translations = array_merge(get_file_data($defaultEnglishFile, true), $translations);
                }
            }
        }

        ksort($translations);

        return view('plugins/translation::theme-translations', compact('translations', 'groups', 'group', 'defaultLanguage'));
    }

    /**
     * @param Request $request
     */
    public function postThemeTranslations(Request $request, BaseHttpResponse $response)
    {
        $translations = $request->input('translations', []);

        $json = [];

        foreach ($translations as $translation) {
            $json[$translation['key']] = $translation['value'];
        }

        if (!File::isWritable(resource_path('lang'))) {
            return $response
                ->setError(true)
                ->setMessage(trans('plugins/translation::translation.folder_is_not_writeable'));
        }

        ksort($json);

        $jsonFile = resource_path('lang/' . $request->input('locale') . '.json');

        File::put($jsonFile, json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $response
            ->setPreviousUrl(route('translations.theme-translations'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}
