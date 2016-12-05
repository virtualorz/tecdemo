<?php

namespace App\Classes\DB;

use RuntimeException;

class DBProcedureException extends RuntimeException {
    
    public $errorInfo;

}
