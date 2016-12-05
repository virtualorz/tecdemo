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
        $listResult = DB::table('news')
                            ->select('news.id','news.title',DB::raw('DATE_FORMAT(news.created_at, "%Y.%m.%d") as created_at'))
                            ->orderBy('news.id','desc')
                            ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);

        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);

        return $this->view;
    }

    public function content() {
        $id = Route::input('id', '');
        $dataResult = DB::table('news')
                            ->select('news.title','news.content','news.school_id','school.city','school.school_name',DB::raw('DATE_FORMAT(news.created_at, "%Y.%m.%d") as created_at'))
                            ->leftJoin('school','news.school_id','=','school.id')
                            ->where('news.id','=',$id)
                            ->get();
        if (count($dataResult) > 0)
        {
            $dataResult[0]['content'] = json_decode($dataResult[0]['content'], true);
        }

        $listResult = "";
        if($dataResult[0]['school_name'] == null)
        {
            $dataResult[0]['school_name'] = "系統訊息";
            $listResult = DB::table('news')
                            ->select('news.id','news.title',DB::raw('DATE_FORMAT(news.created_at, "%Y.%m.%d") as created_at'))
                            ->take(5)
                            ->get();
            
        }
        else
        {
            $listResult = DB::table('news')
                            ->select('news.id','news.title',DB::raw('DATE_FORMAT(news.created_at, "%Y.%m.%d") as created_at'))
                            ->where('news.school_id','=',$dataResult[0]['school_id'])
                            ->take(5)
                            ->get();
        }

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('listResult', $listResult);

        return $this->view;
    }

    public function execute() {
        $id = Route::input('id', '');
        $dataResult = DB::table('school_execute')
                            ->select('school_execute.id',DB::raw('DATE_FORMAT(school_execute.date, "%Y.%m.%d") as date'),'school_execute.photo','school.city','school.school_name','school.photo as school_photo')
                            ->leftJoin('school','school_execute.school_id','=','school.id')
                            ->where('school_execute.school_id','=',$id)
                            ->get();
        
        if(count($dataResult) == 0)
        {
            $dataResult = DB::table('school')
                            ->select('city','school_name')
                            ->where('id','=',$id)
                            ->get();
        }
        else
        {
            foreach($dataResult as $k=>$v)
            {
                $photo = json_decode($v['photo'],true);
                if(count($photo) !=0)
                {
                    foreach($photo as $k1=>$v1)
                    {
                        unset($photo[$k1]['text']);
                    }
                    $v['photo'] = json_encode($photo);
                    $v['photo'] = FileUpload::getFiles($v['photo']);
                    $dataResult[$k]['front'] = $v['photo'][0]['url'];
                }
                else
                {
                    $v['school_photo'] = FileUpload::getFiles($v['school_photo']);
                    $dataResult[$k]['front'] = $v['school_photo'][0]['url'];
                }
            }
        }

        $this->view->with('dataResult', $dataResult);
        $this->view->with('school_id', $id);
        $this->view->with('twCity',Config::get('data.twCity'));

        return $this->view;
    }

    public function execute_content() {
        $id = Route::input('id', '');
        $dataResult = DB::table('school_execute')
                            ->select('school_execute.school_id',DB::raw('DATE_FORMAT(school_execute.date, "%Y.%m.%d") as date'),'school_execute.member','school_execute.content','school_execute.file','school_execute.photo','school.city','school.school_name')
                            ->leftJoin('school','school_execute.school_id','=','school.id')
                            ->where('school_execute.id','=',$id)
                            ->get();
        if (count($dataResult) > 0)
        {
            $dataResult[0]['content'] = json_decode($dataResult[0]['content'], true);
            //照片處理
            $photo = json_decode($dataResult[0]['photo'],true);
            $photo_text = array();
            foreach($photo as $k=>$v)
            {
                array_push($photo_text,$photo[$k]['text']);
                unset($photo[$k]['text']);
            }
            $dataResult[0]['photo'] = json_encode($photo);
            
            $dataResult[0]['photo'] = FileUpload::getFiles($dataResult[0]['photo']);
            foreach($dataResult[0]['photo'] as $k=>$v)
            {
                $dataResult[0]['photo'][$k]['text'] = $photo_text[$k];
            }
            $dataResult[0]['file'] = FileUpload::getFiles($dataResult[0]['file']);
        }
        else
        {
            $dataResult = DB::table('school')
                            ->select('city','school_name')
                            ->where('id','=',$id)
                            ->get();
        }

        $this->view->with('dataResult', $dataResult);
        $this->view->with('school_id', $dataResult[0]['school_id']);
        $this->view->with('twCity',Config::get('data.twCity'));
        return $this->view;
    }

    public function tutor() {
        $id = Route::input('id', '');
        $dataResult = DB::table('school_tutor')
                            ->select('school_tutor.id',DB::raw('DATE_FORMAT(school_tutor.date, "%Y.%m.%d") as date'),'school_tutor.photo','school.city','school.school_name','school.photo as school_photo')
                            ->leftJoin('school','school_tutor.school_id','=','school.id')
                            ->where('school_tutor.school_id','=',$id)
                            ->get();
        
        if(count($dataResult) == 0)
        {
            $dataResult = DB::table('school')
                            ->select('city','school_name')
                            ->where('id','=',$id)
                            ->get();
        }
        else
        {
            foreach($dataResult as $k=>$v)
            {
                $photo = json_decode($v['photo'],true);
                if(count($photo) !=0)
                {
                    foreach($photo as $k1=>$v1)
                    {
                        unset($photo[$k1]['text']);
                    }
                    $v['photo'] = json_encode($photo);
                    $v['photo'] = FileUpload::getFiles($v['photo']);
                    $dataResult[$k]['front'] = $v['photo'][0]['url'];
                }
                else
                {
                    $v['school_photo'] = FileUpload::getFiles($v['school_photo']);
                    $dataResult[$k]['front'] = $v['school_photo'][0]['url'];
                }
            }
        }

        $this->view->with('dataResult', $dataResult);
        $this->view->with('school_id', $id);
        $this->view->with('twCity',Config::get('data.twCity'));

        return $this->view;
    }

    public function tutor_content() {
        $id = Route::input('id', '');
        $dataResult = DB::table('school_tutor')
                            ->select('school_tutor.school_id',DB::raw('DATE_FORMAT(school_tutor.date, "%Y.%m.%d") as date'),'school_tutor.member','school_tutor.content','school_tutor.file','school_tutor.photo','school.city','school.school_name')
                            ->leftJoin('school','school_tutor.school_id','=','school.id')
                            ->where('school_tutor.id','=',$id)
                            ->get();
        if (count($dataResult) > 0)
        {
            $dataResult[0]['content'] = json_decode($dataResult[0]['content'], true);
            //照片處理
            $photo = json_decode($dataResult[0]['photo'],true);
            $photo_text = array();
            foreach($photo as $k=>$v)
            {
                array_push($photo_text,$photo[$k]['text']);
                unset($photo[$k]['text']);
            }
            $dataResult[0]['photo'] = json_encode($photo);
            
            $dataResult[0]['photo'] = FileUpload::getFiles($dataResult[0]['photo']);
            foreach($dataResult[0]['photo'] as $k=>$v)
            {
                $dataResult[0]['photo'][$k]['text'] = $photo_text[$k];
            }
            $dataResult[0]['file'] = FileUpload::getFiles($dataResult[0]['file']);
        }
        else
        {
            $dataResult = DB::table('school')
                            ->select('city','school_name')
                            ->where('id','=',$id)
                            ->get();
        }

        $this->view->with('dataResult', $dataResult);
        $this->view->with('school_id', $dataResult[0]['school_id']);
        $this->view->with('twCity',Config::get('data.twCity'));
        return $this->view;
    }

}
