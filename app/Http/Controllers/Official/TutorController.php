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

class TutorController extends Controller {

    public function index() {
        $dataResult = DB::table('school_tutor')
                            ->select('school_tutor.id',DB::raw('DATE_FORMAT(school_tutor.date, "%Y.%m.%d") as date'),'school_tutor.photo','school.city','school.school_name','school.photo as school_photo')
                            ->leftJoin('school','school_tutor.school_id','=','school.id')
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
                    $photo = isset($v['photo'][0]['urlScale0']) ? $v['photo'][0]['urlScale0'] : '';
                    if ($photo == '') {
                        $photo = asset('assets/official/img/7534.jpg');
                    }
                    $dataResult[$k]['front'] = $photo ;
                }
                else
                {
                    $v['school_photo'] = FileUpload::getFiles($v['school_photo']);
                    $school_photo = isset($v['school_photo'][0]['urlScale0']) ? $v['school_photo'][0]['urlScale0'] : '';
                    if ($school_photo == '') {
                        $school_photo = asset('assets/official/img/7534.jpg');
                    }
                    $dataResult[$k]['front'] = $school_photo;
                }
            }
        }

       $this->view->with('dataResult', $dataResult);
       $this->view->with('pagination', $pagination);
       $this->view->with('twCity',Config::get('data.twCity'));

        return $this->view;
    }

}
