<?php

namespace Botble\Gallery\Facades;

use Botble\Gallery\Gallery;
use Illuminate\Support\Facades\Facade;

class GalleryFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     */
    protected static function getFacadeAccessor()
    {
        return Gallery::class;
    }
}
