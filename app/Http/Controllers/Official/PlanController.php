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

class PlanController extends Controller {

    public function index() {
        $dataResult = DB::table('plan')
                            ->select('content')
                            ->orderBy('id','desc')
                            ->take(1)
                            ->get();
        if (count($dataResult) > 0)
        {
            $dataResult[0]['content'] = json_decode($dataResult[0]['content'], true);
        }
        else
        {
            $dataResult[0]['content'] = [];
        }

        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

    public function time() {
        
        $dataResult = DB::table('plan_time')
                            ->select('name',DB::raw('DATE_FORMAT(start_dt, "%Y年%m月") as start_dt'),DB::raw('DATE_FORMAT(end_dt, "%m月") as end_dt'),'item')
                            ->get();
        if (count($dataResult) > 0)
        {
            foreach($dataResult as $k=>$v)
            {
                 $dataResult[$k]['item'] = json_decode($v['item'], true);
            }
           
        }

        $this->view->with('dataResult', $dataResult);

        return $this->view;
    }

    public function target() {
        $dataResult = DB::table('plan_target')
                            ->select('name',DB::raw('DATE_FORMAT(start_dt, "%Y年%m月") as start_dt'),DB::raw('DATE_FORMAT(end_dt, "%m月") as end_dt'),'item')
                            ->get();
        if (count($dataResult) > 0)
        {
            foreach($dataResult as $k=>$v)
            {
                 $dataResult[$k]['item'] = json_decode($v['item'], true);
            }
           
        }
        
        $this->view->with('dataResult', $dataResult);

        return $this->view;
    }

}
