<?php

namespace App\Http\Controllers;

//
use DB;
use DBOperator;
use DBProcedure;
use User;
use Request;
use Route;
use Validator;
use Config;

class RelationDDLController extends Controller {

    private $view;

    public function __construct() {
        $currAction = Route::currentRouteAction();
        $preStr = 'App\\Http\\Controllers\\';
        $pos = strpos($currAction, $preStr);
        if ($pos !== false) {
            $currAction = substr($currAction, strpos($currAction, '\\Contollers') + strlen($preStr));
        }
        $viewPath = str_replace(['\\', 'Controller@'], '.', $currAction);
        $tmpPos = strrpos($viewPath, '.');
        if ($tmpPos !== false) {
            $this->view_folder = substr($viewPath, 0, $tmpPos);
        }

        if (Request::ajax()) {
            $this->view = [
                'result' => 'ok',
                'msg' => '',
                'detail' => [],
                'data' => []
            ];
        } else {
            if (view()->exists($viewPath)) {
                $this->view = view($viewPath);
            }
        }
    }

    public function type_main() {
        $main_id = Request::input('main_id');

        $bindParam = [
            $main_id
        ];
        $listResult = DBProcedure::callFirstDataSet('backend_template_load_type_sub', $bindParam);


        $this->view['data'][] = $listResult;
        return $this->view;
    }

}
