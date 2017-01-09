<?php

namespace App\Http\Controllers\Official;

//
use User;
use DB;
use DBOperator;
use DBProcedure;
use Request;
use Route;
use Config;
use FileUpload;
use Validator;
use Log;
use Sitemap;
use SitemapAccess;
use Crypt;

class ResetpwController extends Controller {

    public function index() {
        $id = Route::input('id');
        $uid = explode('_',Crypt::decrypt($id));
        $status = 1;
        if(strtotime("+10 minutes",strtotime($uid[0])) < strtotime(date('Y-m-d H:i:s')))
        {
            $status = 0;
        }

        $this->view->with('status', $status);
        $this->view->with('id', $id);

        return $this->view;
    }

    public function finish() {

        return $this->view;
    }

    ##

    public function ajax_set() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'email' => 'string|required|max:200',
                    'password' => 'string|required|max:200|same:passwordR'
        ]);
        if ($validator->fails()) {
            $invalid[] = $validator->errors();
        }
        if (count($invalid) > 0) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $invalid;
            return $this->view;
        }
        $id = Request::input('id');
        $uid = explode('_',Crypt::decrypt($id));

        //檢查信箱
        $member_data = DB::table('member_data')
                ->select('email')
                ->where('email',Request::input('email'))
                ->where('id',$uid[1])
                ->get();
        
        if(count($member_data)== 0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array('Email輸入錯誤!');
            return $this->view;
        }

        try {
            DB::transaction(function()use($uid){
                DB::table('member_data')
                    ->where('id',$uid[1])
                    ->update(['password'=>User::hashPassword(Request::input('password'))
                        ]);
            });

        } catch (DBProcedureException $e) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            $this->view['detail'][] = $e->getMessage();

            return $this->view;
        }

        $this->view['msg'] = trans('message.success.change');
        return $this->view;
    }

}
