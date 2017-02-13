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
use Crypt;

class MemberProtofolioController extends Controller {

    public function index() {

        $name = Request::input('name', '');
        $card_id_number = Request::input('card_id_number', '');
        $email = Request::input('email', '');
        $member_type = Request::input('member_type', '');
        $organize = Request::input('organize', '');
        $department = Request::input('department', '');
        $pi = Request::input('pi', '');

        $listResult = DB::table('member_data');
        if($name != "")
        {
            $listResult->where('member_data.name','like','%'.$name.'%');
        }
        if($card_id_number != "")
        {
            $listResult->where('member_data.card_id_number','=',$card_id_number);
        }
        if($email != "")
        {
            $listResult->where('member_data.email','=',$email);
        }
        if($member_type != "")
        {
            $listResult->where('member_data.type','=',$member_type);
        }
        if($organize != "")
        {
            $listResult->where('system_pi_list.organize_id','=',$organize);
            $departmentResult = DB::table('system_department');
            $departmentResult = $departmentResult->select('id','name')
                                        ->where('organize_id',$organize)
                                        ->get();
            $this->view->with('departmentResult', $departmentResult);
        }
        if($department != "")
        {
            $listResult->where('system_pi_list.department_id','=',$department);
            $piResult = DB::table('system_pi_list');
            $piResult = $piResult->select('id','name')
                                        ->where('department_id',$department)
                                        ->get();
            $this->view->with('piResult', $piResult);
        }
        if($pi != "")
        {
            $listResult->where('system_pi_list.id','=',$pi);
        }

        $listResult = $listResult->select('member_data.id',
                                            DB::raw('DATE_FORMAT(member_data.created_at, "%Y-%m-%d") as created_at'),
                                            'member_data.name',
                                            'member_data.card_id_number',
                                            'member_data.type',
                                            'member_data.enable')
                                    ->leftJoin('system_pi_list','member_data.pi_list_id','=','system_pi_list.id')
                                    ->orderBy('member_data.id','desc')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        $organizeResult = DB::table('system_organize')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        $this->view->with('organizeResult', $organizeResult);
        $this->view->with('member_typeResult', Config::get('data.id_type'));
        return $this->view;
    }

    public function add() {
        $organizeResult = DB::table('system_organize')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();

        $this->view->with('organizeResult', $organizeResult);
        $this->view->with('permission', Config::get('data.permission'));
        $this->view->with('journal', Config::get('data.journal'));
        $this->view->with('member_typeResult', Config::get('data.id_type'));
        return $this->view;
    }

    public function edit() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('member_data')
                            ->select('member_data.*',
                                        'system_organize.name as organize_name',
                                        'system_department.name as department_name',
                                        'system_pi_list.name as pi_name',
                                        'member_admin.name as created_admin_name')
                            ->leftJoin('system_organize','member_data.organize_id','=','system_organize.id')
                            ->leftJoin('system_department','member_data.department_id','=','system_department.id')
                            ->leftJoin('system_pi_list','member_data.pi_list_id','=','system_pi_list.id')
                            ->leftJoin('member_admin','member_data.create_admin_id','=','member_admin.id')
                            ->where('member_data.id',$id)
                            ->get();
        $organizeResult = DB::table('system_organize')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        $departmentResult = DB::table('system_department')
                                    ->where('organize_id',$dataResult[0]['organize_id'])
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        $piResult = DB::table('system_pi_list')
                                    ->where('department_id',$dataResult[0]['department_id'])
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        $permissionResult = array();
        $permissionResultTmp = DB::table('member_permission')
                                    ->where('member_data_id',$id)
                                    ->select('permission')
                                    ->get();
        foreach($permissionResultTmp as $k=>$v)
        {
            array_push($permissionResult,$v['permission']);
        }

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('organizeResult', $organizeResult);
        $this->view->with('departmentResult', $departmentResult);
        $this->view->with('piResult', $piResult);
        $this->view->with('permissionResult', $permissionResult);
        $this->view->with('permission', Config::get('data.permission'));
        $this->view->with('member_typeResult', Config::get('data.id_type'));

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('member_data')
                            ->select('member_data.*',
                                        'system_organize.name as organize_name',
                                        'system_department.name as department_name',
                                        'system_pi_list.name as pi_name',
                                        'member_admin.name as created_admin_name')
                            ->leftJoin('system_organize','member_data.organize_id','=','system_organize.id')
                            ->leftJoin('system_department','member_data.department_id','=','system_department.id')
                            ->leftJoin('system_pi_list','member_data.pi_list_id','=','system_pi_list.id')
                            ->leftJoin('member_admin','member_data.create_admin_id','=','member_admin.id')
                            ->where('member_data.id',$id)
                            ->get();
        $permissionResult = array();
        $permissionResultTmp = DB::table('member_permission')
                                    ->where('member_data_id',$id)
                                    ->select('permission')
                                    ->get();
        foreach($permissionResultTmp as $k=>$v)
        {
            array_push($permissionResult,$v['permission']);
        }

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('permissionResult', $permissionResult);
        $this->view->with('permission', Config::get('data.permission'));
        $this->view->with('member_typeResult', Config::get('data.id_type'));

        return $this->view;
    }

    public function active() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('member_data')
                            ->select('member_data.*',
                                        'system_organize.name as organize_name',
                                        'system_department.name as department_name',
                                        'system_pi_list.name as pi_name')
                            ->leftJoin('system_organize','member_data.organize_id','=','system_organize.id')
                            ->leftJoin('system_department','member_data.department_id','=','system_department.id')
                            ->leftJoin('system_pi_list','member_data.pi_list_id','=','system_pi_list.id')
                            ->where('member_data.id',$id)
                            ->get();
        
        $organizeResult = DB::table('system_organize')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        $departmentResult = DB::table('system_department')
                                    ->where('organize_id',$dataResult[0]['organize_id'])
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        $piResult = DB::table('system_pi_list')
                                    ->where('department_id',$dataResult[0]['department_id'])
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        
        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('organizeResult', $organizeResult);
        $this->view->with('departmentResult', $departmentResult);
        $this->view->with('piResult', $piResult);
        $this->view->with('permission', Config::get('data.permission'));
        $this->view->with('member_typeResult', Config::get('data.id_type'));

        return $this->view;
    }

    public function activitylog() {
        $id = Route::input('id', 0);
        $listResult = DB::table('activity_reservation_data')
                            ->select('activity_data.id',
                                        'activity_data.start_dt',
                                        'activity_data.end_dt',
                                        'activity_data.activity_name',
                                        'activity_data.level',
                                        'activity_data.time',
                                        'activity_reservation_data.attend_status',
                                        'activity_reservation_data.pass_status')
                            ->leftJoin('activity_data','activity_reservation_data.activity_id','=','activity_data.id')
                            ->where('activity_reservation_data.member_id',$id)
                            ->get();
        
        
        
        $this->view->with('listResult', $listResult);

        return $this->view;
    }

    public function notice() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('member_data')
                            ->select('member_data.id','member_data.name','member_data.email')
                            ->where('member_data.id',$id)
                            ->get();
        
        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'name' => 'string|required|max:16',
                    'card_id_number' => 'string|required|max:20',
                    'organize_id' => 'integer|required',
                    'department_id' => 'integer|required',
                    'email' => 'string|required|max:200',
                    'password' => 'string|required|max:200|same:passwordR',
                    'phone' => 'string|required|max:24',
                    'pi_list_id' => 'integer|required',
                    'lab_phone' => 'string|required|max:24',
                    'type' => 'integer|required',
                    'start_dt' => 'date|required',
                    'limit_month' => 'integer|required',
                    'permission' => 'array|required',
                    'enable' => 'integer|required',
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
        $count2 = DB::table('member_data')
                ->where('card_id_number',Request::input('card_id_number'))
                ->count();
        
        if($count1 != 0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array('帳號重複！');
            return $this->view;
        }
        if($count2 != 0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array('此學生號已註冊過！');
            return $this->view;
        }

        try {
            DB::transaction(function(){
                $id = DB::table('member_data')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'name'=>Request::input('name'),
                                    'card_id_number'=>Request::input('card_id_number'),
                                    'organize_id'=>Request::input('organize_id'),
                                    'department_id'=>Request::input('department_id'),
                                    'title'=>Request::input('title'),
                                    'email'=>Request::input('email'),
                                    'password'=>User::hashPassword(Request::input('password')),
                                    'phone'=>Request::input('phone'),
                                    'pi_list_id'=>Request::input('pi_list_id'),
                                    'lab_phone'=>Request::input('lab_phone'),
                                    'type'=>Request::input('type'),
                                    'start_dt'=>Request::input('start_dt'),
                                    'limit_month'=>Request::input('limit_month'),
                                    'enable'=>Request::input('enable'),
                                    'create_admin_id'=>User::id()
                            )
                        );
                $result_after = DB::table('member_data')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'member_data',
                    'operator' => DBOperator::OP_INSERT,
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'admin_id' => User::id()
                ]);
                //權限資料
                $permission = Request::input('permission');
                foreach($permission as $k=>$v)
                {
                    DB::table('member_permission')
                        ->insert(array(
                            'member_data_id'=>$id,
                            'permission'=>$v
                        ));
                }
                //jounral資料
                $journal_type = Request::input('journal_type');
                if($journal_type != "")
                {
                    DB::table('member_journal')
                        ->insert(array(
                            'member_data_id'=>$id,
                            'member_journal_id'=>'1',
                            'created_at'=>date('Y-m-d H:i:s'),
                            'type'=>Request::input('journal_type'),
                            'release_dt'=>Request::input('release_dt'),
                            'topic'=>Request::input('topic'),
                            'journal'=>Request::input('journal'),
                            'author'=>Request::input('author'),
                            'url'=>Request::input('url')
                        ));
                }
            });

        } catch (DBProcedureException $e) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            $this->view['detail'][] = $e->getMessage();

            return $this->view;
        }

        $this->view['msg'] = trans('message.success.add');
        return $this->view;
    }

    public function ajax_edit() {
        $validator = Validator::make(Request::all(), [
                    'name' => 'string|required|max:16',
                    'card_id_number' => 'string|required|max:20',
                    'organize_id' => 'integer|required',
                    'department_id' => 'integer|required',
                    'email' => 'string|required|max:200',
                    'password' => 'string|required|max:200|same:passwordR',
                    'phone' => 'string|required|max:24',
                    'pi_list_id' => 'integer|required',
                    'lab_phone' => 'string|required|max:24',
                    'type' => 'integer|required',
                    'start_dt' => 'date|required',
                    'limit_month' => 'integer|required',
                    'permission' => 'array|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        //檢查重複
        $count1 = DB::table('member_data')
                ->where('email',Request::input('email'))
                ->where('id','!=',Request::input('id'))
                ->count();
        $count2 = DB::table('member_data')
                ->where('card_id_number',Request::input('card_id_number'))
                ->where('id','!=',Request::input('id'))
                ->count();
        
        if($count1 != 0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array('帳號重複！');
            return $this->view;
        }
        if($count2 != 0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array('此學生號已註冊過！');
            return $this->view;
        }
        
        try {
            DB::transaction(function(){
                $result_before = DB::table('member_data')
                                    ->where('id',Request::input('id'))
                                    ->get();
                if($result_before[0]['password'] == Request::input('password'))
                {
                    DB::table('member_data')
                        ->where('id',Request::input('id'))
                        ->update(['name'=>Request::input('name'),
                                    'card_id_number'=>Request::input('card_id_number'),
                                    'organize_id'=>Request::input('organize_id'),
                                    'department_id'=>Request::input('department_id'),
                                    'title'=>Request::input('title'),
                                    'email'=>Request::input('email'),
                                    'phone'=>Request::input('phone'),
                                    'pi_list_id'=>Request::input('pi_list_id'),
                                    'lab_phone'=>Request::input('lab_phone'),
                                    'type'=>Request::input('type'),
                                    'start_dt'=>Request::input('start_dt'),
                                    'limit_month'=>Request::input('limit_month'),
                        ]);
                }
                else
                {
                    DB::table('member_data')
                        ->where('id',Request::input('id'))
                        ->update(['name'=>Request::input('name'),
                                    'card_id_number'=>Request::input('card_id_number'),
                                    'organize_id'=>Request::input('organize_id'),
                                    'department_id'=>Request::input('department_id'),
                                    'title'=>Request::input('title'),
                                    'email'=>Request::input('email'),
                                    'password'=>User::hashPassword(Request::input('password')),
                                    'phone'=>Request::input('phone'),
                                    'pi_list_id'=>Request::input('pi_list_id'),
                                    'lab_phone'=>Request::input('lab_phone'),
                                    'type'=>Request::input('type'),
                                    'start_dt'=>Request::input('start_dt'),
                                    'limit_month'=>Request::input('limit_month'),
                        ]);
                }
                $result_after = DB::table('member_data')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'member_data',
                    'operator' => DBOperator::OP_UPDATE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'admin_id' => User::id()
                ]);

                //權限資料
                DB::table('member_permission')
                    ->where('member_data_id',Request::input('id'))
                    ->delete();
                $permission = Request::input('permission');
                foreach($permission as $k=>$v)
                {
                    DB::table('member_permission')
                        ->insert(array(
                            'member_data_id'=>Request::input('id'),
                            'permission'=>$v
                        ));
                }

            });

        } catch (\PDOException $ex) {
            DB::rollBack();

            \Log::error($ex->getMessage());
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            return $this->view;
        }

        $this->view['msg'] = trans('message.success.edit');
        return $this->view;
    }

    public function ajax_active() {
        $validator = Validator::make(Request::all(), [
                    'organize_id' => 'integer|required',
                    'department_id' => 'integer|required',
                    'pi_list_id' => 'integer|required',
                    'type' => 'integer|required',
                    'start_dt' => 'date|required',
                    'limit_month' => 'integer|required',
                    'permission' => 'array|required',
                    'enable' => 'integer|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        
        try {
            DB::transaction(function(){
                $result_before = DB::table('member_data')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DB::table('member_data')
                    ->where('id',Request::input('id'))
                    ->update(['organize_id'=>Request::input('organize_id'),
                                'department_id'=>Request::input('department_id'),
                                'pi_list_id'=>Request::input('pi_list_id'),
                                'type'=>Request::input('type'),
                                'start_dt'=>Request::input('start_dt'),
                                'limit_month'=>Request::input('limit_month'),
                                'enable'=>Request::input('enable'),
                                'create_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('member_data')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'member_data',
                    'operator' => DBOperator::OP_UPDATE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'admin_id' => User::id()
                ]);

                //權限資料
                DB::table('member_permission')
                    ->where('member_data_id',Request::input('id'))
                    ->delete();
                $permission = Request::input('permission');
                foreach($permission as $k=>$v)
                {
                    DB::table('member_permission')
                        ->insert(array(
                            'member_data_id'=>Request::input('id'),
                            'permission'=>$v
                        ));
                }

            });

        } catch (\PDOException $ex) {
            DB::rollBack();

            \Log::error($ex->getMessage());
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            return $this->view;
        }

        $this->view['msg'] = trans('message.success.edit');
        return $this->view;
    }

    public function ajax_notice() {
        $validator = Validator::make(Request::all(), [
                    'title' => 'string|required|max:20',
                    'content' => 'string|required'
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        
        try {
            DB::transaction(function(){
                $member_notice_log_id = DB::table('member_notice_log')
                            ->select('member_notice_log_id')
                            ->where('member_data_id',Request::input('id'))
                            ->orderBy('member_notice_log_id','desc')
                            ->first();
                if(!isset($member_notice_log_id['member_notice_log_id']))
                {
                    $member_notice_log_id = 0;
                }
                else
                {
                    $member_notice_log_id = $member_notice_log_id['member_notice_log_id'];
                }
                $member_notice_log_id = intval($member_notice_log_id) +1;

                $id = DB::table('member_notice_log')
                        ->insertGetId(
                            array('uid'=>'-',
                                    'salt'=>'-',
                                    'member_data_id'=>Request::input('id'),
                                    'member_notice_log_id'=>$member_notice_log_id,
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'email'=>Request::input('email'),
                                    'title'=>Request::input('title'),
                                    'content'=>Request::input('content'),
                                    'is_read'=>'0',
                                    'create_admin_id'=>User::id()
                            )
                        );
                //製作uid以及salt
                $date = date('Y-m-d H:i:s').$id;
                $salt = substr(md5($date),5,5);
                $uid = md5($salt.$date);
                
                DB::table('member_notice_log')
                    ->where('member_data_id',Request::input('id'))
                    ->where('member_notice_log_id',$member_notice_log_id)
                    ->update(['uid'=>$uid,
                                'salt'=>$salt
                    ]);
                
                //取得會員個人資料
                $member_data = DB::table('member_data')
                        ->select('name','email','password')
                        ->where('id',Request::input('id'))
                        ->first();
                if(count($member_data) !=0)
                {
                    $login_hash = DB::table('member_login_hash')
                        ->select('hash')
                        ->where('email',$member_data['email'])
                        ->where('password',$member_data['password'])
                        ->first();
                    if(count($login_hash) == 0)
                    {
                        $login_uid = Crypt::encrypt($member_data['email'].'_'.$member_data['password']);
                        $hash = md5($login_uid);
                        DB::table('member_login_hash')
                            ->where('email',$member_data['email'])
                            ->delete();
                        DB::table('member_login_hash')
                            ->insert(array(
                                    'email'=>$member_data['email'],
                                    'password'=>$member_data['password'],
                                    'hash'=>$hash,
                                    'uid'=>$login_uid
                                    ));
                    }
                    else
                    {
                        $hash = $login_hash['hash'];
                    }
                    
                    $dataResult = array('user'=>$member_data['name'],'url'=> asset('/member/message/detail/id-'.$uid.'-'.$salt.'-'.$hash));
                    Mail::send('emails.notice', [
                                'dataResult' => $dataResult,
                                    ], function ($m) {
                                $m->to(Request::input('email'), '');
                                $m->subject("系統通知訊息");
                    });
                }
            });

        } catch (DBProcedureException $e) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            $this->view['detail'][] = $e->getMessage();

            return $this->view;
        }

        $this->view['msg'] = trans('message.success.add');
        return $this->view;
    }

    public function ajax_delete() {
        $validator = Validator::make(Request::all(), [
                    'id' => 'array|required'
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        $ids = Request::input('id', []);
        try {
            foreach ($ids as $k => $v) {
                $id = $v;

                $result_before = DB::table('member_data')
                                    ->where('id',$id)
                                    ->get();
                DB::table('member_data')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'member_data',
                    'operator' => DBOperator::OP_DELETE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'admin_id' => User::id()
                ]);
            }
        } catch (\PDOException $ex) {
            DB::rollBack();

            \Log::error($ex->getMessage());
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            return $this->view;
        } catch (\Exception $ex) {
            DB::rollBack();

            \Log::error($ex->getMessage());
            $this->view['result'] = 'no';
            $this->view['msg'] = $ex->getMessage();
            return $this->view;
        }

        
        $this->view['msg'] = trans('message.success.delete');
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
