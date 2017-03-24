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
use Session;

class RegisterController extends Controller {

    public function index() {
        
        $organizeResult = DB::table('system_organize')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();

        $this->view->with('organizeResult', $organizeResult);

        return $this->view;
    }

    public function finish() {

        return $this->view;
    }

    ##

    public function ajax_register() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'name' => 'string|required|max:15',
                    'organize' => 'integer|required',
                    'department' => 'integer|required',
                    'email' => 'string|required|max:200',
                    'password' => 'string|required|max:200|same:passwordR',
                    'phone' => 'string|required|max:120',
                    'pi' => 'integer|required',
                    'lab_phone' => 'string|required|max:120',
                    'member_agree' => 'required',
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
        //檢查重複
        $count1 = DB::table('member_data')
                ->where('email',Request::input('email'))
                ->count();
        
        if($count1 != 0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array('帳號重複！');
            return $this->view;
        }

        try {
            DB::transaction(function(){
                $id = DB::table('member_data')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'name'=>Request::input('name'),
                                    'organize_id'=>Request::input('organize'),
                                    'department_id'=>Request::input('department'),
                                    'title'=>Request::input('title'),
                                    'email'=>Request::input('email'),
                                    'password'=>User::hashPassword(Request::input('password')),
                                    'phone'=>Request::input('phone'),
                                    'pi_list_id'=>Request::input('pi'),
                                    'lab_phone'=>Request::input('lab_phone'),
                                    'enable'=>0
                            )
                        );
                $result_after = DB::table('member_data')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'member_data',
                    'operator' => DBOperator::OP_INSERT,
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'member_id' => $id
                ]);
                
            });

        } catch (DBProcedureException $e) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            $this->view['detail'][] = $e->getMessage();

            return $this->view;
        }

        //快取註冊資料
        $register_data = array('created_at'=>date('Y-m-d H:i:s'),
                                    'name'=>Request::input('name'),
                                    'organize_id'=>Request::input('organize'),
                                    'department_id'=>Request::input('department'),
                                    'title'=>Request::input('title'),
                                    'email'=>Request::input('email'),
                                    'password'=>User::hashPassword(Request::input('password')),
                                    'phone'=>Request::input('phone'),
                                    'pi_list_id'=>Request::input('pi'),
                                    'lab_phone'=>Request::input('lab_phone'),
                                    'enable'=>0
                            );
        Session::set('cache_register', $register_data);

        $this->view['msg'] = trans('message.success.register');
        return $this->view;
    }

    public function ajax_get_department() {

        $id = Request::input('id');
        $listResult = DB::table('system_department');
        $listResult = $listResult->select('id','name')
                                    ->where('organize_id',$id)
                                    ->get();
        
        return $listResult;
    }

    public function ajax_get_pi() {

        $id = Request::input('id');
        $listResult = DB::table('system_pi_list');
        $listResult = $listResult->select('id','name')
                                    ->where('department_id',$id)
                                    ->get();
        
        return $listResult;
    }

}
