<?php

namespace Botble\Hotel\Http\Controllers;

use Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Hotel\Http\Requests\UpdateSettingsRequest;
use Botble\Hotel\Repositories\Interfaces\CurrencyInterface;
use Botble\Hotel\Services\StoreCurrenciesService;
use Botble\Setting\Supports\SettingStore;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class HotelController extends BaseController
{

    /**
     * @var CurrencyInterface
     */
    protected $currencyRepository;

    /**
     * HotelController constructor.
     * @param CurrencyInterface $currencyRepository
     */
    public function __construct(CurrencyInterface $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * @return Factory|View
     */
    public function getSettings()
    {
        page_title()->setTitle(trans('plugins/hotel::hotel.settings'));

        Assets::addScripts(['jquery-ui'])
            ->addScriptsDirectly([
                'vendor/core/plugins/hotel/js/currencies.js',
            ])
            ->addStylesDirectly([
                'vendor/core/plugins/hotel/css/currencies.css',
            ]);

        $currencies = $this->currencyRepository
            ->getAllCurrencies()
            ->toArray();

        return view('plugins/hotel::settings.index', compact('currencies'));
    }

    /**
     * @param UpdateSettingsRequest $request
     * @param BaseHttpResponse $response
     * @param StoreCurrenciesService $service
     * @param SettingStore $settingStore
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function postSettings(
        UpdateSettingsRequest $request,
        BaseHttpResponse $response,
        StoreCurrenciesService $service,
        SettingStore $settingStore
    ) {
        foreach ($request->except(['_token', 'currencies', 'deleted_currencies']) as $settingKey => $settingValue) {
            $settingStore->set($settingKey, $settingValue);
        }

        $settingStore->save();
        $currencies = json_decode($request->input('currencies'), true) ?: [];
        $deletedCurrencies = json_decode($request->input('deleted_currencies', []), true) ?: [];

        $service->execute($currencies, $deletedCurrencies);

        return $response
            ->setNextUrl(route('hotel.settings'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}
