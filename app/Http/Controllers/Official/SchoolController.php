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

class SchoolController extends Controller {

    public function index() {
        $location = Request::input('location', '');
        $listResult = DB::table('school');
        if($location != "")
        {
            $listResult->where('school.location','=',$location);
        }
        $listResult = $listResult->select('id','city','school_name','photo')
                                    ->orderBy('id','desc')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);

        $listResult = json_decode($listResult->toJson(),true)['data'];
        foreach($listResult as $k=>$v)
        {
            $v['photo'] = FileUpload::getFiles($v['photo']);
            $photo = isset($v['photo'][0]['urlScale0']) ? $v['photo'][0]['urlScale0'] : '';
            if ($photo == '') {
                $photo = asset('assets/official/img/7534.jpg');
            }
            $listResult[$k]['photo'] = $photo;
        }

        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        $this->view->with('location', $location);
        $this->view->with('twCity',Config::get('data.twCity'));
        return $this->view;
    }

    public function plan() {
        $id = Route::input('id', '');
        $dataResult = DB::table('school_plan')
                            ->select('school_plan.topic','school_plan.idea','school_plan.plan','school_plan.file','school_plan.contact_name','school_plan.contact_tel','school_plan.contact_email','school_plan.related_group','school_plan.related_url','school.city','school.school_name','school.photo')
                            ->leftJoin('school','school_plan.school_id','=','school.id')
                            ->where('school_id','=',$id)
                            ->get();
        if (count($dataResult) > 0)
        {
            $dataResult[0]['plan'] = json_decode($dataResult[0]['plan'], true);
            $dataResult[0]['file'] = FileUpload::getFiles($dataResult[0]['file']);
            $dataResult[0]['related_group'] = json_decode($dataResult[0]['related_group'], true);
            $dataResult[0]['related_url'] = json_decode($dataResult[0]['related_url'], true);
        }
        else
        {
            $dataResult = DB::table('school')
                            ->select('city','school_name','photo')
                            ->where('id','=',$id)
                            ->get();
        }
        $photo = FileUpload::getFiles($dataResult[0]['photo']);
        $photo = isset($photo[0]['urlScale0']) ? $photo[0]['urlScale0'] : '';
        if ($photo == '') {
            $photo = asset('assets/official/img/7534.jpg');
        }
        $dataResult[0]['school_photo'] = $photo;

        $this->view->with('dataResult', $dataResult);
        $this->view->with('school_id', $id);
        $this->view->with('twCity',Config::get('data.twCity'));
        return $this->view;
    }

    public function news() {
        $id = Route::input('id', '');
        $dataResult = DB::table('news')
                            ->select('news.id','news.title','school.city','school.school_name','school.photo',DB::raw('DATE_FORMAT(news.created_at, "%Y.%m.%d") as created_at'))
                            ->leftJoin('school','news.school_id','=','school.id')
                            ->where('news.school_id','=',$id)
                            ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($dataResult->toJson(),true)['total']);

        $dataResult = json_decode($dataResult->toJson(),true)['data'];
        if(count($dataResult) == 0)
        {
            $dataResult = DB::table('school')
                            ->select('city','school_name','photo')
                            ->where('id','=',$id)
                            ->get();
        }
        $photo = FileUpload::getFiles($dataResult[0]['photo']);
        $photo = isset($photo[0]['urlScale0']) ? $photo[0]['urlScale0'] : '';
        if ($photo == '') {
            $photo = asset('assets/official/img/7534.jpg');
        }
        $dataResult[0]['school_photo'] = $photo;

        $this->view->with('dataResult', $dataResult);
        $this->view->with('pagination', $pagination);
        $this->view->with('school_id', $id);
        $this->view->with('twCity',Config::get('data.twCity'));

        return $this->view;
    }

    public function news_content() {
        $id = Route::input('id', '');
        $dataResult = DB::table('news')
                            ->select('news.title','news.school_id','news.content','school.city','school.school_name','school.photo',DB::raw('DATE_FORMAT(news.created_at, "%Y.%m.%d") as created_at'))
                            ->leftJoin('school','news.school_id','=','school.id')
                            ->where('news.id','=',$id)
                            ->get();
        if (count($dataResult) > 0)
        {
            $dataResult[0]['content'] = json_decode($dataResult[0]['content'], true);
        }
        else
        {
            $dataResult = DB::table('school')
                            ->select('city','school_name','photo')
                            ->where('id','=',$id)
                            ->get();
        }
        $photo = FileUpload::getFiles($dataResult[0]['photo']);
        $photo = isset($photo[0]['urlScale0']) ? $photo[0]['urlScale0'] : '';
        if ($photo == '') {
            $photo = asset('assets/official/img/7534.jpg');
        }
        $dataResult[0]['school_photo'] = $photo;

        $this->view->with('dataResult', $dataResult);
        $this->view->with('school_id', $dataResult[0]['school_id']);
        $this->view->with('twCity',Config::get('data.twCity'));
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
                            ->select('city','school_name','photo as school_photo')
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
                    $dataResult[$k]['front'] = $v['photo'][0]['urlScale0'];
                }
                else
                {
                    $v['school_photo'] = FileUpload::getFiles($v['school_photo']);
                    $dataResult[$k]['front'] = $v['school_photo'][0]['urlScale0'];
                }
            }
        }
        $photo = FileUpload::getFiles($dataResult[0]['school_photo']);
        $photo = isset($photo[0]['urlScale0']) ? $photo[0]['urlScale0'] : '';
        if ($photo == '') {
            $photo = asset('assets/official/img/7534.jpg');
        }
        $dataResult[0]['school_photo'] = $photo;

        $this->view->with('dataResult', $dataResult);
        $this->view->with('school_id', $id);
        $this->view->with('twCity',Config::get('data.twCity'));

        return $this->view;
    }

    public function execute_content() {
        $id = Route::input('id', '');
        $dataResult = DB::table('school_execute')
                            ->select('school_execute.school_id',DB::raw('DATE_FORMAT(school_execute.date, "%Y.%m.%d") as date'),'school_execute.member','school_execute.content','school_execute.file','school_execute.photo','school.city','school.school_name','school.photo as school_photo')
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
                            ->select('city','school_name','photo as school_photo')
                            ->where('id','=',$id)
                            ->get();
        }
        $photo = FileUpload::getFiles($dataResult[0]['school_photo']);
        $photo = isset($photo[0]['urlScale0']) ? $photo[0]['urlScale0'] : '';
        if ($photo == '') {
            $photo = asset('assets/official/img/7534.jpg');
        }
        $dataResult[0]['school_photo'] = $photo;

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
                            ->select('city','school_name','photo as school_photo')
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
                    $dataResult[$k]['front'] = $v['photo'][0]['urlScale0'];
                }
                else
                {
                    $v['school_photo'] = FileUpload::getFiles($v['school_photo']);
                    $dataResult[$k]['front'] = $v['school_photo'][0]['urlScale0'];
                }
            }
        }
        $photo = FileUpload::getFiles($dataResult[0]['school_photo']);
        $photo = isset($photo[0]['urlScale0']) ? $photo[0]['urlScale0'] : '';
        if ($photo == '') {
            $photo = asset('assets/official/img/7534.jpg');
        }
        $dataResult[0]['school_photo'] = $photo;

        $this->view->with('dataResult', $dataResult);
        $this->view->with('school_id', $id);
        $this->view->with('twCity',Config::get('data.twCity'));

        return $this->view;
    }

    public function tutor_content() {
        $id = Route::input('id', '');
        $dataResult = DB::table('school_tutor')
                            ->select('school_tutor.school_id',DB::raw('DATE_FORMAT(school_tutor.date, "%Y.%m.%d") as date'),'school_tutor.member','school_tutor.content','school_tutor.file','school_tutor.photo','school.city','school.school_name','school.photo as school_photo')
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
                            ->select('city','school_name','photo as school_photo')
                            ->where('id','=',$id)
                            ->get();
        }
        $photo = FileUpload::getFiles($dataResult[0]['school_photo']);
        $photo = isset($photo[0]['urlScale0']) ? $photo[0]['urlScale0'] : '';
        if ($photo == '') {
            $photo = asset('assets/official/img/7534.jpg');
        }
        $dataResult[0]['school_photo'] = $photo;

        $this->view->with('dataResult', $dataResult);
        $this->view->with('school_id', $dataResult[0]['school_id']);
        $this->view->with('twCity',Config::get('data.twCity'));
        return $this->view;
    }

}
