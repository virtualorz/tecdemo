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

class TCDataController extends Controller {

    public function index() {

        $listResult = DB::table('system_tc_data');

        $listResult = $listResult->select('id','name',DB::raw('DATE_FORMAT(created_at, "%Y/%m/%d") as created_at'),'enable')
                                    ->orderBy('id','desc')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        return $this->view;
    }

    public function add() {

        return $this->view;
    }

    public function edit() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('system_tc_data')
                            ->select('system_tc_data.id',
                                    DB::raw('DATE_FORMAT(system_tc_data.created_at, "%Y/%m/%d %H:%i:%s") as created_at'),
                                    'system_tc_data.name','system_tc_data.content','system_tc_data.enable','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','system_tc_data.create_admin_id','=','member_admin.id')
                            ->where('system_tc_data.id',$id)
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('system_tc_data')
                            ->select('system_tc_data.id',
                                    DB::raw('DATE_FORMAT(system_tc_data.created_at, "%Y/%m/%d %H:%i:%s") as created_at'),
                                    'system_tc_data.name','system_tc_data.content','system_tc_data.enable','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','system_tc_data.create_admin_id','=','member_admin.id')
                            ->where('system_tc_data.id',$id)
                            ->get();
        if (count($dataResult[0]) > 0) {
            //$dataResult[0][0]['created_at'] = (new DateTime($dataResult[0][0]['created_at']))->format('Y/m/d');
            $dataResult[0]['content'] = json_decode($dataResult[0]['content'], true);
        }
                            
        $this->view->with('dataResult', $dataResult[0]);
        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'name' => 'string|required|max:32',
                    'content' => 'string|required',
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

        $content = FileUpload::moveEditor(Request::input('content'));
        try {
            DB::transaction(function()use($content){
                $id = DB::table('system_tc_data')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'name'=>Request::input('name'),
                                    'content'=>$content,
                                    'enable'=>Request::input('enable'),
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                $result_after = DB::table('system_tc_data')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'system_tc_data',
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
                    'name' => 'string|required|max:32',
                    'content' => 'string|required',
                    'enable' => 'integer|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        $content = FileUpload::moveEditor(Request::input('content'));
        try {
            DB::transaction(function()use($content){
                $result_before = DB::table('system_tc_data')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DB::table('system_tc_data')
                    ->where('id',Request::input('id'))
                    ->update(['name'=>Request::input('name'),
                                'content'=>$content,
                                'enable'=>Request::input('enable'),
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('system_tc_data')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'system_tc_data',
                    'operator' => DBOperator::OP_UPDATE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'admin_id' => User::id()
                ]);
                FileUpload::deleteEditor($result_before[0]['content'],$result_after[0]['content']);
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

                $result_before = DB::table('system_tc_data')
                                    ->where('id',$id)
                                    ->get();
                DB::table('system_tc_data')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'system_tc_data',
                    'operator' => DBOperator::OP_DELETE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'admin_id' => User::id()
                ]);
                FileUpload::deleteEditor($result_before[0]['content']);
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
