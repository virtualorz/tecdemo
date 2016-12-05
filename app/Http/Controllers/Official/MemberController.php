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
use User;
use Validator;

class MemberController extends Controller {

    public function index() {
        $dataResult = DB::table('school')
                            ->select('location','city','town','account','school_name','photo')
                            ->where('id','=',User::Id())
                            ->get();
        $photo = FileUpload::getFiles($dataResult[0]['photo']);
        $photo = isset($photo[0]['urlScale0']) ? $photo[0]['urlScale0'] : '';
        if ($photo == '') {
            $photo = asset('assets/official/img/7534.jpg');
        }
        $dataResult[0]['school_photo'] = $photo;

        $this->view->with('dataResult', $dataResult);
        $this->view->with('school_id', User::Id());
        $this->view->with('twCity',Config::get('data.twCity'));
        $this->view->with('twTown',Config::get('data.twTown'));
        $this->view->with('location',Config::get('data.location'));
        
        return $this->view;
    }
}
