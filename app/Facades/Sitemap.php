<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Classes\ViewHelper
 */
class Sitemap extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'sitemap';
    }

}
