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

class AdminController extends Controller {

    public function index() {
        $listResult = DB::table('member_admin')
                            ->select('id','name','email',DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as created_at'))
                            ->orderBy('id','desc')
                            ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        return $this->view;
    }

    public function add() {

        $listResult = DB::table('member_admin_permission')
                            ->select('id','name')
                            ->where('enable',1)
                            ->orderBy('id','desc')
                            ->get();
        $this->view->with('listResult',$listResult);

        return $this->view;
    }

    public function edit() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('member_admin')
                            ->select('member_admin.id','member_admin.created_at','member_admin.name','member_admin.email','member_admin.password','member_admin.permission_id','member_admin.enable','member_admin2.name as created_admin_name')
                            ->leftJoin('member_admin as member_admin2','member_admin.create_admin_id','=','member_admin2.id')
                            ->where('member_admin.id',$id)
                            ->get();
        $listResult = DB::table('member_admin_permission')
                            ->select('id','name')
                            ->where('enable',1)
                            ->orderBy('id','desc')
                            ->get();
        $this->view->with('listResult',$listResult);

        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('member_admin')
                            ->select('member_admin.id','member_admin.created_at','member_admin.name','member_admin.email','member_admin.password','member_admin.permission_id','member_admin.enable','member_admin2.name as created_admin_name')
                            ->leftJoin('member_admin as member_admin2','member_admin.create_admin_id','=','member_admin2.id')
                            ->where('member_admin.id',$id)
                            ->get();
        $listResult = DB::table('member_admin_permission')
                            ->select('id','name')
                            ->where('enable',1)
                            ->orderBy('id','desc')
                            ->get();
        $this->view->with('listResult',$listResult);

        $this->view->with('dataResult', $dataResult[0]);
        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'name' => 'string|required|max:50',
                    'email' => 'string|required|max:384',
                    'password' => 'string|required',
                    'permission_id' => 'integer|required',
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

        //檢查email重複
        $member_admin = DB::table('member_admin')
                    ->where('email',Request::input('email'))
                    ->get();
        if(count($member_admin) !=0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array('email重複');
            return $this->view;
        }

        try {
            DB::transaction(function(){
                $id = DB::table('member_admin')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'name'=>Request::input('name'),
                                    'email'=>Request::input('email'),
                                    'password'=>User::hashPassword(Request::input('password')),
                                    'permission_id'=>Request::input('permission_id'),
                                    'enable'=>Request::input('enable'),
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                $result_after = DB::table('member_admin')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'member_admin',
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
                    'name' => 'string|required|max:50',
                    'email' => 'string|required|max:384',
                    'password' => 'string|required',
                    'permission_id' => 'integer|required',
                    'enable' => 'integer|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        //檢查帳號重複
        $member_admin = DB::table('member_admin')
                    ->where('email',Request::input('email'))
                    ->where('id','!=',Request::input('id'))
                    ->get();
        if(count($member_admin) !=0)
        {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = array('email重複');
            return $this->view;
        }


        try {
            DB::transaction(function(){
                $result_before = DB::table('member_admin')
                                    ->where('id',Request::input('id'))
                                    ->get();
                $password = Request::input('password');
                if($result_before[0]['password'] != Request::input('password'))
                {
                    $password = User::hashPassword(Request::input('password'));
                }
                DB::table('member_admin')
                    ->where('id',Request::input('id'))
                    ->update(['name'=>Request::input('name'),
                                'email'=>Request::input('email'),
                                'password'=>$password,
                                'permission_id'=>Request::input('permission_id'),
                                'enable'=>Request::input('enable'),
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('member_admin')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'member_admin',
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

                $result_before = DB::table('member_admin')
                                    ->where('id',$id)
                                    ->get();
                DB::table('member_admin')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'member_admin',
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
}
