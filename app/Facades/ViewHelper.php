<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Classes\ViewHelper
 */
class ViewHelper extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'viewhelper';
    }

}
