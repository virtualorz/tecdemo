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

class InstrumentSuppliesController extends Controller {

    public function index() {

        $listResult = DB::table('instrument_supplies');

        $listResult = $listResult->select('instrument_supplies.id','instrument_supplies.name','rate1','rate2','rate3','rate4',DB::raw('DATE_FORMAT(instrument_supplies.created_at, "%Y-%m-%d") as created_at'),'member_admin.name as created_admin_name')
                                    ->leftJoin('member_admin','instrument_supplies.create_admin_id','=','member_admin.id')
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
        $dataResult = DB::table('instrument_supplies')
                            ->select('instrument_supplies.*','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','instrument_supplies.create_admin_id','=','member_admin.id')
                            ->where('instrument_supplies.id',$id)
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('instrument_supplies')
                            ->select('instrument_supplies.*','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','instrument_supplies.create_admin_id','=','member_admin.id')
                            ->where('instrument_supplies.id',$id)
                            ->get();
                            
        $this->view->with('dataResult', $dataResult[0]);
        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'name' => 'string|required|max:32',
                    'rate1' => 'numeric|required',
                    'rate2' => 'numeric|required',
                    'rate3' => 'numeric|required',
                    'rate4' => 'numeric|required',
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
            DB::transaction(function(){
                $id = DB::table('instrument_supplies')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'name'=>Request::input('name'),
                                    'rate1'=>Request::input('rate1'),
                                    'rate2'=>Request::input('rate2'),
                                    'rate3'=>Request::input('rate3'),
                                    'rate4'=>Request::input('rate4'),
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                $result_after = DB::table('instrument_supplies')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'instrument_supplies',
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
                    'rate1' => 'numeric|required',
                    'rate2' => 'numeric|required',
                    'rate3' => 'numeric|required',
                    'rate4' => 'numeric|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        try {
            DB::transaction(function(){
                $result_before = DB::table('instrument_supplies')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DB::table('instrument_supplies')
                    ->where('id',Request::input('id'))
                    ->update(['name'=>Request::input('name'),
                                'rate1'=>Request::input('rate1'),
                                'rate2'=>Request::input('rate2'),
                                'rate3'=>Request::input('rate3'),
                                'rate4'=>Request::input('rate4'),
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('instrument_supplies')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'instrument_supplies',
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

                $result_before = DB::table('instrument_supplies')
                                    ->where('id',$id)
                                    ->get();
                DB::table('instrument_supplies')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'instrument_supplies',
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
