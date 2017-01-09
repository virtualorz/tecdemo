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
use Crypt;
use Mail;
use Cache;
use Carbon\Carbon;

class ForgetpwController extends Controller {

    public function index() {
        
        return $this->view;
    }

    public function finish() {
        
        return $this->view;
    }

    ##

    public function ajax_send() {
        $validator = Validator::make(Request::all(), [
                    'email' => 'string|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        //確認email存在
        $member_data = DB::table('member_data')
            ->select('id','name')
            ->where('member_data.email',Request::input('email'))
            ->first();

        if(count($member_data) == 0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array('Email不存在，請重新填寫');

            return $this->view;
        }

        $uid = Crypt::encrypt(date('Y-m-d H:i:s').'_'.$member_data['id']);

        $dataResult = array('user'=>$member_data['name'],'url'=> asset('reset_pw/id-'.$uid));
                Mail::send('emails.fpw', [
                        'dataResult' => $dataResult,
                            ], function ($m) {
                        $m->to(Request::input('email'), '');
                        $m->subject("重新設定密碼通知");
        });

        //快取儲存資料
        $expiresAt = Carbon::now()->addMinutes(10);
        Cache::put('forget_pw_email', Request::input('email'), $expiresAt);
        Cache::put('forget_pw_id', $member_data['id'], $expiresAt);
        Cache::put('forget_pw_name', $member_data['name'], $expiresAt);

        $this->view['msg'] = trans('message.success.request');
        return $this->view;
    }

    public function ajax_resend() {
        
        //確認快取email存在
        $email = Cache::get('forget_pw_email','');
        $id = Cache::get('forget_pw_id','');
        $name = Cache::get('forget_pw_name','');

        if($email == '')
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array('認證已過期請重新申請');

            return $this->view;
        }

        $uid = Crypt::encrypt(date('Y-m-d H:i:s').'_'.$id);

        $dataResult = array('user'=>$name,'url'=> asset('reset_pw/id-'.$uid));
                Mail::send('emails.fpw', [
                        'dataResult' => $dataResult,
                            ], function ($m)use($email) {
                        $m->to($email, '');
                        $m->subject("重新設定密碼通知");
        });

        //快取儲存資料
        $expiresAt = Carbon::now()->addMinutes(10);
        Cache::put('forget_pw_email', $email, $expiresAt);
        Cache::put('forget_pw_id', $id, $expiresAt);
        Cache::put('forget_pw_name', $name, $expiresAt);

        $this->view['msg'] = trans('message.success.request');
        return $this->view;
    }
}
