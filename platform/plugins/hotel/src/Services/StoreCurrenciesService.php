<?php

namespace Botble\Hotel\Services;

use Botble\Hotel\Repositories\Eloquent\CurrencyRepository;
use Botble\Hotel\Repositories\Interfaces\CurrencyInterface;
use Exception;

class StoreCurrenciesService
{
    /**
     * @var CurrencyRepository
     */
    protected $currencyRepository;

    /**
     * StoreCurrenciesService constructor.
     * @param CurrencyInterface $currency
     */
    public function __construct(CurrencyInterface $currency)
    {
        $this->currencyRepository = $currency;
    }

    /**
     * @param array $currencies
     * @param array $deletedCurrencies
     * @throws Exception
     */
    public function execute(array $currencies, array $deletedCurrencies)
    {
        if ($deletedCurrencies) {
            $this->currencyRepository->deleteBy([
                ['id', 'IN', $deletedCurrencies],
            ]);
        }

        foreach ($currencies as $item) {
            $currency = $this->currencyRepository->findById($item['id']);
            if (!$currency) {
                $this->currencyRepository->create($item);
            } else {
                $currency->fill($item);
                $this->currencyRepository->createOrUpdate($currency);
            }
        }
    }
}
