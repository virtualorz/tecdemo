<?php

namespace App\Http\Controllers\Official;

//
use DB;
use DBOperator;
use DBProcedure;
use Request;
use Route;
use Config;
use FileUpload;
use Log;

class PolicyController extends Controller {

    public function index() {
        
        return $this->view;
    }

}
