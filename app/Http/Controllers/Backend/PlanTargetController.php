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

class PlanTargetController extends Controller {

    public function index() {
        $listResult = DB::table('plan_target')
                            ->select('id','name','start_dt','end_dt',DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as created_at'))
                            ->orderBy('data_order','asc')
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
        $dataResult = DB::table('plan_target')
                            ->select('plan_target.id','plan_target.created_at','plan_target.name','plan_target.start_dt','plan_target.end_dt','plan_target.item','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','plan_target.create_admin_id','=','member_admin.id')
                            ->where('plan_target.id',$id)
                            ->get();
        $dataResult[0]['item'] = json_decode($dataResult[0]['item'],true);

        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('plan_target')
                            ->select('plan_target.id','plan_target.created_at','plan_target.name','plan_target.start_dt','plan_target.end_dt','plan_target.item','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','plan_target.create_admin_id','=','member_admin.id')
                            ->where('plan_target.id',$id)
                            ->get();
        $dataResult[0]['item'] = json_decode($dataResult[0]['item'],true);

        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'name' => 'string|required|max:50',
                    'start_dt' => 'string|required',
                    'end_dt' => 'string|required',
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
        //項目處理
        $item = array();
        $item_main = Request::input('item_main');
        $item_main_key = Request::input('item_main_key');
        $item_sub = Request::input('item_sub');
        $item_sub_rel_main = Request::input('item_sub_rel_main');

        foreach($item_main as $k=>$v)
        {
            $item[$item_main_key[$k]] = array('value'=>$v,'sub_item'=>array());
        }

        foreach($item_sub as $k=>$v)
        {
            array_push($item[$item_sub_rel_main[$k]]['sub_item'],$v);
        }

        try {
            DB::transaction(function($item) use ($item){
                $id = DB::table('plan_target')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'name'=>Request::input('name'),
                                    'start_dt'=>Request::input('start_dt'),
                                    'end_dt'=>Request::input('end_dt'),
                                    'item'=>json_encode($item),
                                    'data_order'=>0,
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                DB::table('plan_target')
                    ->where('id',$id)
                    ->update(['data_order'=>$id]);
                $result_after = DB::table('plan_target')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'plan_target',
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
                    'start_dt' => 'string|required',
                    'end_dt' => 'string|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        //項目處理
        $item = array();
        $item_main = Request::input('item_main');
        $item_main_key = Request::input('item_main_key');
        $item_sub = Request::input('item_sub');
        $item_sub_rel_main = Request::input('item_sub_rel_main');
        foreach($item_main as $k=>$v)
        {
            $item[$item_main_key[$k]] = array('value'=>$v,'sub_item'=>array());
        }

        foreach($item_sub as $k=>$v)
        {
            array_push($item[$item_sub_rel_main[$k]]['sub_item'],$v);
        }

        try {
            DB::transaction(function($item) use ($item){
                $result_before = DB::table('plan_target')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DB::table('plan_target')
                    ->where('id',Request::input('id'))
                    ->update(['name'=>Request::input('name'),
                                'start_dt'=>Request::input('start_dt'),
                                'end_dt'=>Request::input('end_dt'),
                                'item'=>json_encode($item),
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('plan_target')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'plan_target',
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

                $result_before = DB::table('plan_target')
                                    ->where('id',$id)
                                    ->get();
                DB::table('plan_target')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'plan_target',
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

    public function ajax_load_order() {
        
        try {
            $listResult = DB::table('plan_target')
                            ->select('plan_target.id','plan_target.name')
                            ->orderBy('plan_target.data_order','asc')
                            ->get();
            

        } catch (DBProcedureException $e) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            $this->view['detail'][] = $e->getMessage();

            return $this->view;
        }

        $this->view['msg'] = trans('message.success.add');
        $this->view['data'] = $listResult;
        return $this->view;
    }

    public function ajax_set_order() {
        $validator = Validator::make(Request::all(), [
                    'sort_result' => 'string|required'
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        $sort_result = json_decode('["'.str_replace(',','","',Request::input('sort_result')).'"]',true);

        try {
            $count = 1;
            foreach ($sort_result as $k => $v) {
                if(intval($v) !=0)
                {
                    DB::table('plan_target')
                        ->where('id',$v)
                        ->update(['data_order'=>$count]);
                }
                $count ++;
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
}
