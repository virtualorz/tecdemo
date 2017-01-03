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

class IndexController extends Controller {

    public function index() {
        $newsResut = DB::table('system_index_notice')
                            ->select('id',DB::raw('DATE_FORMAT(created_at, "%Y.%m.%d") as created_at'),'title')
                            ->where('enable',1)
                            ->orderBy('id','desc')
                            ->take(4)
                            ->get();
        $activityResut = DB::table('activity_data')
                            ->select('uid','salt',DB::raw('DATE_FORMAT(start_dt, "%Y.%m.%d") as start_dt'),DB::raw('DATE_FORMAT(end_dt, "%Y.%m.%d") as end_dt'),'activity_name')
                            ->orderBy('created_at','desc')
                            ->take(5)
                            ->get();

        $this->view->with('newsResut', $newsResut);
        $this->view->with('activityResut', $activityResut);

        return $this->view;
    }

}
