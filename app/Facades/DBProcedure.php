<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Classes\ViewHelper
 */
class DBProcedure extends Facade {
    const LOG_OP_ADD = 1;
    const LOG_OP_EDIT = 2;
    const LOG_OP_DELETE = 3;
    const LOG_OP_ENABLE = 4;
    const LOG_OP_DISABLE = 5;
    const LOG_OP_LOGIN_SUCCESS = 6;
    const LOG_OP_LOGIN_FAIL = 7;
    const LOG_OP_LOGOUT = 8;
    const LOG_OP_ORDER = 9;

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'dbprocedure';
    }

}
