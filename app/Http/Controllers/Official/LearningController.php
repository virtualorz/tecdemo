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

class LearningController extends Controller {

    public function index() {
        $dataResult = DB::table('learning')
                            ->select('learning.id',DB::raw('DATE_FORMAT(learning.date, "%Y.%m.%d") as date'),'learning.photo','learning.city','school.school_name','school.photo as school_photo')
                            ->leftJoin('school','learning.school_id','=','school.id')
                             ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($dataResult->toJson(),true)['total']);

        $dataResult = json_decode($dataResult->toJson(),true)['data'];
        if (count($dataResult) > 0)
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
                    $dataResult[$k]['front'] = $v['school_photo'][0]['url'];
                }
            }
        }

       $this->view->with('dataResult', $dataResult);
       $this->view->with('pagination', $pagination);
       $this->view->with('twCity',Config::get('data.twCity'));

        return $this->view;
    }

    public function content() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('learning')
                            ->select('learning.id','learning.created_at','learning.title','learning.city as learning_city','learning.town as learning_town','learning.school_id','learning.school_others','learning.date','learning.member','learning.content','learning.file','learning.photo','school.city','school.town','school.school_name','member_admin.name as created_admin_name','school.photo as school_photo')
                            ->leftJoin('member_admin','learning.create_admin_id','=','member_admin.id')
                            ->leftJoin('school','learning.school_id','=','school.id')
                            ->where('learning.id',$id)
                            ->get();
        if($dataResult[0]['school_id'] == null)
        {
            $dataResult[0]['city'] = $dataResult[0]['learning_city'];
            $dataResult[0]['town'] = $dataResult[0]['learning_town'];
            $dataResult[0]['school_name'] = $dataResult[0]['school_others'];
            $dataResult[0]['school_photo'] = "[]";
        }
        
        if (count($dataResult[0]) > 0)
        {
            $dataResult[0]['content'] = json_decode($dataResult[0]['content'], true);
        }
        $photo = FileUpload::getFiles($dataResult[0]['school_photo']);
        $photo = isset($photo[0]['urlScale0']) ? $photo[0]['urlScale0'] : '';
        if ($photo == '') {
            $photo = asset('assets/official/img/7534.jpg');
        }
        $dataResult[0]['school_photo'] = $photo;

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

        $this->view->with('dataResult', $dataResult);
        $this->view->with('twCity',Config::get('data.twCity'));
        $this->view->with('twTown',Config::get('data.twTown'));

        return $this->view;
    }

}
