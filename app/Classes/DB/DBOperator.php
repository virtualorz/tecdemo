<?php

namespace App\Classes\DB;

class DBOperator {

    const OP_UNDEFINED = 0;
    // 0 ~ 100 basic
    const OP_SELECT = 1;
    const OP_INSERT = 2;
    const OP_UPDATE = 3;
    const OP_DELETE = 4;
    const OP_ENABLE = 5;
    const OP_DISABLE = 6;
    const OP_ORDER = 7;
    const OP_LOGIN_SUCCESS = 8;
    const OP_LOGOUT = 9;
    const OP_LOGIN_FAIL = 10;
    const OP_LOGIN_FAIL_PASSWORD = 11;
    const OP_LOGIN_FAIL_ENABLE = 12;
    const OP_CHANGE_PASSWORD = 13;
    const OP_EXPORT = 14;
    const OP_REGISTER_AUTH = 15;
    const OP_FORGET_PASSWORD = 16;
    const OP_CLIKI_VIEW = 17;
    
    // custom
    const OP_REPLY_COMPANY_QA = 101;
    const OP_REPLY_CONTACTUS = 102;
    const OP_PRODUCT_SERIES_FOCUS_OFF = 103;
    const OP_PRODUCT_SERIES_FOCUS_ON = 104;
    const OP_PRODUCT_NEW_OFF = 105;
    const OP_PRODUCT_NEW_ON = 106;
    const OP_PRODUCT_HOT_OFF = 107;
    const OP_PRODUCT_HOT_ON = 108;

}
