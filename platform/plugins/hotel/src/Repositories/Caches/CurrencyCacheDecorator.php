<?php

namespace Botble\Hotel\Repositories\Caches;

use Botble\Hotel\Repositories\Interfaces\CurrencyInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class CurrencyCacheDecorator extends CacheAbstractDecorator implements CurrencyInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAllCurrencies()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
