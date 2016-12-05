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

class PlanController extends Controller {

    public function index() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('plan')
                            ->select('plan.id','plan.created_at','plan.content','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','plan.create_admin_id','=','member_admin.id')
                            ->orderBy('plan.id','desc')
                            ->first();
        if(count($dataResult) == 0)
        {
            $dataResult['id'] = 0;
            $dataResult['created_at'] = date('Y-m-d H:i:s');
            $dataResult['content'] = '[]';
            $dataResult['created_admin_name'] = User::get('name');
        }
        $this->view->with('dataResult', $dataResult);
        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'id' => 'integer|required',
                    'content' => 'string|required',
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

        try {
            $id = Request::input('id');
            if($id == 0)
            {
                DB::transaction(function(){
                    $id = DB::table('plan')
                            ->insertGetId(
                                array('created_at'=>date('Y-m-d H:i:s'),
                                        'updated_at'=>date('Y-m-d H:i:s'),
                                        'content'=>Request::input('content'),
                                        'create_admin_id'=>User::id(),
                                        'update_admin_id'=>User::id()
                                )
                            );
                    $result_after = DB::table('plan')
                                    ->where('id',$id)
                                    ->get();
                    DBProcedure::writeLog([
                        'table' => 'plan',
                        'operator' => DBOperator::OP_INSERT,
                        'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                        'admin_id' => User::id()
                    ]);
                });
            }
            else
            {
                DB::transaction(function(){
                $result_before = DB::table('plan')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DB::table('plan')
                    ->where('id',Request::input('id'))
                    ->update(['content'=>Request::input('content'),
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('plan')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'plan',
                    'operator' => DBOperator::OP_UPDATE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'admin_id' => User::id()
                ]);
            });
            }

        } catch (DBProcedureException $e) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            $this->view['detail'][] = $e->getMessage();

            return $this->view;
        }

        $this->view['msg'] = trans('message.success.edit');
        return $this->view;
    }

    public function ajax_edit() {
        $validator = Validator::make(Request::all(), [
                    'title' => 'string|required|max:50',
                    'content' => 'string|required',
                    'is_notice' => 'integer|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }


        try {
            DB::transaction(function(){
                $result_before = DB::table('news')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DB::table('news')
                    ->where('id',Request::input('id'))
                    ->update(['is_notice'=>Request::input('is_notice'),
                                'title'=>Request::input('title'),
                                'content'=>Request::input('content'),
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('news')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'news',
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

                $result_before = DB::table('news')
                                    ->where('id',$id)
                                    ->get();
                DB::table('news')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'news',
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
