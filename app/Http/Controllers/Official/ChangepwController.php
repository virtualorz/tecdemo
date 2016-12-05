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

class ChangepwController extends Controller {

    public function index() {
        $dataResult = DB::table('school')
                            ->select('city','school_name','photo')
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
        
        return $this->view;
    }

    ##

    public function ajax_edit() {
        $validator = Validator::make(Request::all(), [
                    'oldpw' => 'string|required|',
                    'newpw' => 'string|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        $oldpw = User::hashPassword(Request::input('oldpw'));
        $newpw = User::hashPassword(Request::input('newpw'));
        
        //檢查舊密碼
        $oldResult = DB::table('school')
                        ->select('id')
                        ->where('id',User::Id())
                        ->where('password','=',$oldpw)
                        ->get();
        if(count($oldResult) == 0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array("舊密碼輸入錯誤!");

            return $this->view;
        }
        else
        {
            try {
                DB::transaction(function($newpw) use($newpw){
                    
                    $result_before = DB::table('school')
                                        ->where('id',User::Id())
                                        ->get();
                    DB::table('school')
                        ->where('id',User::Id())
                        ->update(['password'=>$newpw,

                        ]);
                    $result_after = DB::table('school')
                                        ->where('id',User::Id())
                                        ->get();
                    DBProcedure::writeLog([
                        'table' => 'school',
                        'operator' => DBOperator::OP_UPDATE,
                        'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                        'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                        'member_id' => User::id()
                    ]);
                });

            } catch (\PDOException $ex) {
                DB::rollBack();

                \Log::error($ex->getMessage());
                $this->view['result'] = 'no';
                $this->view['msg'] = trans('message.error.database');
                return $this->view;
            }
        }
        
        
        $this->view['msg'] = trans('message.success.edit');
        return $this->view;
    }

}
