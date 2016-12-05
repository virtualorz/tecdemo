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

class VideoController extends Controller {

    public function index() {
        $listResult = DB::table('video')
                            ->select('video.id','video.title','video.url',DB::raw('DATE_FORMAT(video.date, "%Y.%m.%d") as date'))
                            ->orderBy('video.id','desc')
                            ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);

        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);

        return $this->view;
    }

}
