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

class NewsController extends Controller {

    public function index() {
        $id = Route::input('id', '');
        $dataResult = DB::table('system_index_notice')
                            ->select('system_index_notice.title','system_index_notice.content',DB::raw('DATE_FORMAT(system_index_notice.created_at, "%Y/%m/%d") as created_at'))
                            ->where('system_index_notice.id','=',$id)
                            ->get();
        if (count($dataResult) > 0)
        {
            $dataResult[0]['content'] = json_decode($dataResult[0]['content'], true);
        }


        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

}
