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

class PIListController extends Controller {

    public function index() {

        /*if(env('INIT_PI',false))
        {
            $table1 = DB::table('table1')->get();
            foreach($table1 as $k=>$v)
            {
                $system_organize = DB::table('system_organize')
                                    ->select('id')
                                    ->where('name',$v['organize'])
                                    ->get();
                if(count($system_organize) ==0)
                {
                    $organize_id = DB::table('system_organize')
                                ->insertGetId(
                                    array('created_at'=>date('Y-m-d H:i:s'),
                                        'updated_at'=>date('Y-m-d H:i:s'),
                                        'name'=>$v['organize'],
                                        'create_admin_id'=>User::id(),
                                        'update_admin_id'=>User::id()
                                    )
                                );
                    $department_id = DB::table('system_department')
                                ->insertGetId(
                                    array('created_at'=>date('Y-m-d H:i:s'),
                                        'updated_at'=>date('Y-m-d H:i:s'),
                                        'organize_id'=>$organize_id,
                                        'name'=>$v['department'],
                                        'create_admin_id'=>User::id(),
                                        'update_admin_id'=>User::id()
                                    )
                                );

                }
                else
                {
                    $organize_id = $system_organize[0]['id'];
                    $system_department = DB::table('system_department')
                                    ->select('id')
                                    ->where('name',$v['department'])
                                    ->get();
                    if(count($system_department) ==0)
                    {
                        $department_id = DB::table('system_department')
                                ->insertGetId(
                                    array('created_at'=>date('Y-m-d H:i:s'),
                                        'updated_at'=>date('Y-m-d H:i:s'),
                                        'organize_id'=>$organize_id,
                                        'name'=>$v['department'],
                                        'create_admin_id'=>User::id(),
                                        'update_admin_id'=>User::id()
                                    )
                                );
                    }
                    else
                    {
                        $department_id = $system_department[0]['id'];
                    }
                }

                DB::table('system_pi_list')
                        ->insert(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=>date('Y-m-d H:i:s'),
                                'organize_id'=>$organize_id,
                                'department_id'=>$department_id,
                                'name'=>$v['name'],
                                'email'=>$v['email'],
                                'phone'=>$v['tel'],
                                'contact_name'=>$v['contact_name'],
                                'contact_phone'=>$v['contact_phone'],
                                'contact_email'=>$v['contact_email'],
                            )
                );
            }
        }*/

        $listResult = DB::table('system_pi_list');

        $listResult = $listResult->select('system_pi_list.id','system_pi_list.name',DB::raw('DATE_FORMAT(system_pi_list.created_at, "%Y/%m/%d") as created_at'),'system_organize.name as organize_name','system_department.name as department_name')
                                    ->leftJoin('system_organize','system_pi_list.organize_id','=','system_organize.id')
                                    ->leftJoin('system_department','system_pi_list.department_id','=','system_department.id')
                                    ->orderBy('id','desc')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        return $this->view;
    }

    public function add() {
        $organizeResult = DB::table('system_organize')
                            ->select('id','name')
                            ->get();
        $this->view->with('organizeResult', $organizeResult);

        return $this->view;
    }

    public function edit() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('system_pi_list')
                            ->select('system_pi_list.id',
                                    DB::raw('DATE_FORMAT(system_pi_list.created_at, "%Y/%m/%d %H:%i:%s") as created_at'),
                                    'system_pi_list.organize_id',
                                    'system_pi_list.department_id',
                                    'system_pi_list.name',
                                    'system_pi_list.email',
                                    'system_pi_list.phone',
                                    'system_pi_list.contact_name',
                                    'system_pi_list.contact_phone',
                                    'system_pi_list.contact_email',
                                    'member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','system_pi_list.create_admin_id','=','member_admin.id')
                            ->where('system_pi_list.id',$id)
                            ->get();
         $organizeResult = DB::table('system_organize')
                            ->select('id','name')
                            ->get();
         $departmentResult = DB::table('system_department')
                            ->select('id','name')
                            ->where('organize_id',$dataResult[0]['organize_id'])
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('organizeResult', $organizeResult);
        $this->view->with('departmentResult', $departmentResult);

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('system_pi_list')
                            ->select('system_pi_list.id',
                                    DB::raw('DATE_FORMAT(system_pi_list.created_at, "%Y/%m/%d %H:%i:%s") as created_at'),
                                    'system_pi_list.organize_id',
                                    'system_pi_list.department_id',
                                    'system_pi_list.name',
                                    'system_pi_list.email',
                                    'system_pi_list.phone',
                                    'system_pi_list.contact_name',
                                    'system_pi_list.contact_phone',
                                    'system_pi_list.contact_email',
                                    'member_admin.name as created_admin_name',
                                    'system_organize.name as organize_name',
                                    'system_department.name as department_name')
                            ->leftJoin('member_admin','system_pi_list.create_admin_id','=','member_admin.id')
                            ->leftJoin('system_organize','system_pi_list.organize_id','=','system_organize.id')
                            ->leftJoin('system_department','system_pi_list.department_id','=','system_department.id')
                            ->where('system_pi_list.id',$id)
                            ->get();
                            
        $this->view->with('dataResult', $dataResult[0]);
        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'organize_id' => 'integer|required',
                    'department_id' => 'integer|required',
                    'name' => 'string|required|max:16',
                    'email' => 'string|required|max:384',
                    'phone' => 'string|required|max:120',
                    'contact_name' => 'string|required|max:24',
                    'contact_phone' => 'string|required|max:120',
                    'contact_email' => 'string|required|max:384',
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

        //檢查PI資料重複
        $picount = DB::table('system_pi_list')
                            ->where('organize_id',Request::input('organize_id'))
                            ->where('department_id',Request::input('department_id'))
                            ->where('name',Request::input('name'))
                            ->count();
        if($picount != 0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array(trans('message.error.PI_exist'));
            return $this->view;
        }
        //檢查學校重複
        if(Request::input('organize_id','') == '-1' && Request::input('other_organize','') != '')
        {
            $organizecount = DB::table('system_organize')
                            ->where('name',Request::input('other_organize'))
                            ->count();
            if($organizecount != 0)
            {
                $this->view['result'] = 'no';
                $this->view['msg'] = trans('message.error.validation');
                $this->view['detail'] = array(trans('message.error.organize_exist'));
                return $this->view;
            }
        }
        //檢查系所重複
        if(Request::input('organize_id','') != '-1' && Request::input('other_department','') != '')
        {
            $departmentcount = DB::table('system_department')
                            ->where('organize_id',Request::input('organize_id'))
                            ->where('name',Request::input('other_department'))
                            ->count();
            if($departmentcount != 0)
            {
                $this->view['result'] = 'no';
                $this->view['msg'] = trans('message.error.validation');
                $this->view['detail'] = array(trans('message.error.department_exist'));
                return $this->view;
            }
        }

        //新增其他學校資料
        $organize_id = 0;
        if(Request::input('organize_id','') == '-1' && Request::input('other_organize','') != '')
        {
            $id = DB::table('system_organize')
                ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'name'=>Request::input('other_organize'),
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
            $organize_id = $id;
        }
        //新增其他系所資料
        $department_id = 0;
        if(Request::input('organize_id','') != '-1')
        {
            $organize_id = Request::input('organize_id');
        }
        if(Request::input('department_id','') == '-1' && Request::input('other_department','') != '')
        {
            $id = DB::table('system_department')
                ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'organize_id'=>$organize_id,
                                    'name'=>Request::input('other_department'),
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
            $department_id = $id;
        }
        if(Request::input('department_id','') != '-1')
        {
            $department_id = Request::input('department_id');
        }
        $param = array($organize_id,$department_id);


        try {
            DB::transaction(function()use($param){
                $id = DB::table('system_pi_list')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'organize_id'=>$param[0],
                                    'department_id'=>$param[1],
                                    'name'=>Request::input('name'),
                                    'email'=>Request::input('email'),
                                    'phone'=>Request::input('phone'),
                                    'contact_name'=>Request::input('contact_name'),
                                    'contact_phone'=>Request::input('contact_phone'),
                                    'contact_email'=>Request::input('contact_email'),
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                $result_after = DB::table('system_pi_list')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'system_pi_list',
                    'operator' => DBOperator::OP_INSERT,
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'admin_id' => User::id()
                ]);
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
                    'organize_id' => 'integer|required',
                    'department_id' => 'integer|required',
                    'name' => 'string|required|max:16',
                    'email' => 'string|required|max:384',
                    'phone' => 'string|required|max:120',
                    'contact_name' => 'string|required|max:24',
                    'contact_phone' => 'string|required|max:120',
                    'contact_email' => 'string|required|max:384',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        //檢查PI資料重複
        $picount = DB::table('system_pi_list')
                            ->where('organize_id',Request::input('organize_id'))
                            ->where('department_id',Request::input('department_id'))
                            ->where('name',Request::input('name'))
                            ->where('id','!=',Request::input('id'))
                            ->count();
        if($picount != 0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array(trans('message.error.PI_exist'));
            return $this->view;
        }

        //檢查學校重複
        if(Request::input('organize_id','') == '-1' && Request::input('other_organize','') != '')
        {
            $organizecount = DB::table('system_organize')
                            ->where('name',Request::input('other_organize'))
                            ->count();
            if($organizecount != 0)
            {
                $this->view['result'] = 'no';
                $this->view['msg'] = trans('message.error.validation');
                $this->view['detail'] = array(trans('message.error.organize_exist'));
                return $this->view;
            }
        }
        //檢查系所重複
        if(Request::input('organize_id','') != '-1' && Request::input('other_department','') != '')
        {
            $departmentcount = DB::table('system_department')
                            ->where('organize_id',Request::input('organize_id'))
                            ->where('name',Request::input('other_department'))
                            ->count();
            if($departmentcount != 0)
            {
                $this->view['result'] = 'no';
                $this->view['msg'] = trans('message.error.validation');
                $this->view['detail'] = array(trans('message.error.department_exist'));
                return $this->view;
            }
        }

        //新增其他學校資料
        $organize_id = 0;
        if(Request::input('organize_id','') == '-1' && Request::input('other_organize','') != '')
        {
            $id = DB::table('system_organize')
                ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'name'=>Request::input('other_organize'),
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
            $organize_id = $id;
        }
        //新增其他系所資料
        $department_id = 0;
        if(Request::input('organize_id','') != '-1')
        {
            $organize_id = Request::input('organize_id');
        }
        if(Request::input('department_id','') == '-1' && Request::input('other_department','') != '')
        {
            $id = DB::table('system_department')
                ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'organize_id'=>$organize_id,
                                    'name'=>Request::input('other_department'),
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
            $department_id = $id;
        }
        if(Request::input('department_id','') != '-1')
        {
            $department_id = Request::input('department_id');
        }
        $param = array($organize_id,$department_id);

        try {
            DB::transaction(function()use($param){
                $result_before = DB::table('system_pi_list')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DB::table('system_pi_list')
                    ->where('id',Request::input('id'))
                    ->update(['updated_at'=>date('Y-m-d H:i:s'),
                                'organize_id'=>$param[0],
                                'department_id'=>$param[1],
                                'name'=>Request::input('name'),
                                'email'=>Request::input('email'),
                                'phone'=>Request::input('phone'),
                                'contact_name'=>Request::input('contact_name'),
                                'contact_phone'=>Request::input('contact_phone'),
                                'contact_email'=>Request::input('contact_email'),
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('system_pi_list')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'system_pi_list',
                    'operator' => DBOperator::OP_UPDATE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'admin_id' => User::id()
                ]);
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

                $result_before = DB::table('system_pi_list')
                                    ->where('id',$id)
                                    ->get();
                DB::table('system_pi_list')
                    ->where('id',$id)
                    ->delete();
                //判斷是否有其他相同系所 學校的pi 若無責刪除系所 學校
                $count_department = DB::table('system_pi_list')
                                    ->where('department_id',$result_before[0]['department_id'])
                                    ->count();
                $count_organize = DB::table('system_pi_list')
                                    ->where('organize_id',$result_before[0]['organize_id'])
                                    ->count();
                if($count_department == 0)
                {
                    DB::table('system_department')
                        ->where('id',$result_before[0]['department_id'])
                        ->delete();
                }
                if($count_organize == 0)
                {
                    DB::table('system_organize')
                        ->where('id',$result_before[0]['organize_id'])
                        ->delete();
                }
                DBProcedure::writeLog([
                    'table' => 'system_pi_list',
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
}
