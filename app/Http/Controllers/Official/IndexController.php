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
        /*$schoolResut = DB::table('index_school')
                            ->select('index_school.school_id','index_school.photo','school.school_name','school.photo as school_photo','school.city')
                            ->leftJoin('school','index_school.school_id','=','school.id')
                            ->where('index_school.enable',1)
                            ->orderBy('index_school.data_order','asc')
                            ->get();
        $newsResut = DB::table('news')
                            ->select('news.id','news.is_notice','news.title',DB::raw('DATE_FORMAT(news.created_at, "%Y.%m.%d") as created_at'))
                            ->orderBy('news.id','desc')
                            ->take(5)
                            ->get();
        $videoResut = DB::table('video')
                            ->select('video.id','video.title','video.url',DB::raw('DATE_FORMAT(video.date, "%Y.%m.%d") as date'))
                            ->orderBy('video.id','desc')
                            ->take(3)
                            ->get();
        foreach($schoolResut as $k=>$v)
        {
            $v['photo'] = FileUpload::getFiles($v['photo']);
            $v['school_photo'] = FileUpload::getFiles($v['school_photo']);
            $photo = isset($v['photo'][0]['urlScale0']) ? $v['photo'][0]['urlScale0'] : '';
            $school_photo = isset($v['school_photo'][0]['urlScale0']) ? $v['school_photo'][0]['urlScale0'] : '';
            if ($photo == '') {
                if($school_photo != '')
                {
                    $photo = $school_photo;
                }
                else
                {
                    $photo = asset('assets/official/img/7534.jpg');
                }
                
            }
            $schoolResut[$k]['photo'] = $photo;
        }

        $this->view->with('schoolResut', $schoolResut);
        $this->view->with('newsResut', $newsResut);
        $this->view->with('videoResut', $videoResut);
        $this->view->with('twCity',Config::get('data.twCity'));*/
        return $this->view;
    }

}
