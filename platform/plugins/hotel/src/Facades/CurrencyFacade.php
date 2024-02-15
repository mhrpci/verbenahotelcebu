<?php

namespace Botble\Hotel\Facades;

use Botble\Hotel\Supports\CurrencySupport;
use Illuminate\Support\Facades\Facade;

class CurrencyFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CurrencySupport::class;
    }
}
