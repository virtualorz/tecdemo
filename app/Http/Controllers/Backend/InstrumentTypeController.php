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

class InstrumentTypeController extends Controller {

    public function index() {

        $listResult = DB::table('instrument_type');

        $listResult = $listResult->select('instrument_type.id',
                                            'instrument_type.name',
                                            DB::raw('DATE_FORMAT(instrument_type.created_at, "%Y/%m/%d") as created_at'),
                                            'member_admin.name as created_admin_name',
                                            DB::raw('count(instrument_data.instrument_type_id) as instrument_count'))
                                    ->leftJoin('member_admin','instrument_type.create_admin_id','=','member_admin.id')
                                    ->leftJoin('instrument_data','instrument_data.instrument_type_id','=','instrument_type.id')
                                    ->groupBy('instrument_type.id')
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
        $dataResult = DB::table('instrument_type')
                            ->select('instrument_type.id',
                                    DB::raw('DATE_FORMAT(instrument_type.created_at, "%Y/%m/%d %H:%i:%s") as created_at'),
                                    'instrument_type.name','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','instrument_type.create_admin_id','=','member_admin.id')
                            ->where('instrument_type.id',$id)
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('instrument_type')
                            ->select('instrument_type.id',
                                    DB::raw('DATE_FORMAT(instrument_type.created_at, "%Y/%m/%d %H:%i:%s") as created_at'),
                                    'instrument_type.name','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','instrument_type.create_admin_id','=','member_admin.id')
                            ->where('instrument_type.id',$id)
                            ->get();
                            
        $this->view->with('dataResult', $dataResult[0]);
        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'name' => 'string|required|max:32',
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
                $id = DB::table('instrument_type')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'name'=>Request::input('name'),
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                $result_after = DB::table('instrument_type')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'instrument_type',
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
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }

        try {
            DB::transaction(function(){
                $result_before = DB::table('instrument_type')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DB::table('instrument_type')
                    ->where('id',Request::input('id'))
                    ->update(['name'=>Request::input('name'),
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('instrument_type')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'instrument_type',
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

                $result_before = DB::table('instrument_type')
                                    ->where('id',$id)
                                    ->get();
                DB::table('instrument_type')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'instrument_type',
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
