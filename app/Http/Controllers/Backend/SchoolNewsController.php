<?php

namespace App\Http\Controllers\Backend;

//
use DB;
use DBOperator;
use DBProcedure;
use User;
use Request;
use Route;
use Validator;
use Config;
use Sitemap;
use SitemapAccess;
use Log;
use FileUpload;
use Mail;

class SchoolNewsController extends Controller {

    public function index() {
        $title = Request::input('title', '');
        $city = Request::input('city', '');
        $town = Request::input('town', '');
        $school_id = Request::input('school_id', '');

        $listResult = DB::table('news');
        if($title != "")
        {
            $listResult->where('news.title','like','%'.$title.'%');
        }
        if($city != "")
        {
            $listResult->where('school.city','=',$city);
        }
        if($town != "")
        {
            $listResult->where('school.town','=',$town);
        }
        if($school_id != "")
        {
            $listResult->where('news.school_id','=',$school_id);
        }

        $listResult = $listResult->select('news.id','news.title','school.school_name',DB::raw('DATE_FORMAT(news.created_at, "%Y-%m-%d") as created_at'))
                                ->leftJoin('school','news.school_id','=','school.id')
                                ->whereNotNull('school_id')
                                ->orderBy('news.id','desc')
                                ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);

        $schoolResult = array();
        if($city != '' && $town != '')
        {
            $schoolResult = DB::table('school')
                            ->select('id','school_name')
                            ->where('city',$city)
                            ->where('town',$town)
                            ->get();
        }
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        $this->view->with('twCity',Config::get('data.twCity'));
        $this->view->with('twTown',Config::get('data.twTown'));
        $this->view->with('schoolResult',$schoolResult);

        return $this->view;
    }

    ##

}
