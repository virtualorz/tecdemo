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

class ActivityListController extends Controller {

    public function index() {

        $date = Request::input('date', '');
        $name = Request::input('name', '');
        $instrument = Request::input('instrument', '');

        $listResult = DB::table('activity_data');
        if($date != "")
        {
            $listResult->where('activity_data.start_dt','<=',$date);
            $listResult->where('activity_data.end_dt','<=',$date);
        }
        if($name != "")
        {
            $listResult->where('activity_data.activity_name','like','%'.$name.'%');
        }
        if($instrument != "")
        {
            $listResult->where('activity_instrument.activity_instrument_id','=',$instrument);
        }

        $listResult = $listResult->select('activity_data.id',
                                            'activity_data.start_dt',
                                            'activity_data.end_dt',
                                            DB::raw('DATE_FORMAT(activity_data.created_at, "%Y-%m-%d") as created_at'),
                                            'activity_data.activity_name',
                                            'activity_data.time',
                                            DB::raw('count(activity_reservation_data.id) as reservation_count'))
                                    ->leftJoin('activity_reservation_data','activity_reservation_data.activity_id','=','activity_data.id')
                                    ->leftJoin('activity_instrument','activity_instrument.activity_id','=','activity_data.id')
                                    ->orderBy('id','desc')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);

        $instrumentResult = DB::table('instrument_data')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        $this->view->with('instrumentResult', $instrumentResult);
        return $this->view;
    }

    public function add() {
        $instrument_typeResult = DB::table('instrument_type')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        $activity_typeResult = DB::table('activity_type')
                                    ->select('id','name')
                                    ->orderBy('id','desc')
                                    ->get();
        $this->view->with('instrument_typeResult', $instrument_typeResult);
        $this->view->with('activity_typeResult', $activity_typeResult);
        $this->view->with('level', Config::get('data.level'));
        $this->view->with('pass_type', Config::get('data.pass_type'));
        $this->view->with('permission', Config::get('data.permission'));
        return $this->view;
    }

    public function edit() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('system_tc_data')
                            ->select('system_tc_data.id','system_tc_data.created_at','system_tc_data.name','system_tc_data.content','system_tc_data.enable','member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','system_tc_data.create_admin_id','=','member_admin.id')
                            ->where('system_tc_data.id',$id)
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('system_tc_data')
                            ->select('system_tc_data.id','system_tc_data.created_at','system_tc_data.name','system_tc_data.content','system_tc_data.enable','member_admin.name as created_admin_name')
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
                    'activity_id' => 'string|required|max:64',
                    'start_dt' => 'date|required',
                    'activity_name' => 'string|required|max:12',
                    'activity_type_id' => 'integer|required',
                    'relative_plateform' => 'array|required',
                    'level' => 'integer|required',
                    'time' => 'integer|required',
                    'score' => 'integer|required',
                    'pass_type' => 'integer|required',
                    'pass_condition' => 'string|required|max:64',
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

        $content = FileUpload::moveEditor(Request::input('content'));
        try {
            DB::transaction(function()use($content){
                $id = DB::table('activity_data')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'activity_id'=>Request::input('activity_id'),
                                    'start_dt'=>Request::input('start_dt'),
                                    'end_dt'=>Request::input('end_dt'),
                                    'activity_name'=>Request::input('activity_name'),
                                    'activity_type_id'=>Request::input('activity_type_id'),
                                    'relative_plateform'=>json_encode(Request::input('relative_plateform')),
                                    'level'=>Request::input('level'),
                                    'time'=>Request::input('time'),
                                    'score'=>Request::input('score'),
                                    'pass_type'=>Request::input('pass_type'),
                                    'pass_condition'=>Request::input('pass_condition'),
                                    'content'=>$content,
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                $result_after = DB::table('activity_data')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'activity_data',
                    'operator' => DBOperator::OP_INSERT,
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'admin_id' => User::id()
                ]);
                $instrument = Request::input('instrument');
                $instrument_permission = Request::input('instrument_permission');
                foreach($instrument as $k=>$v)
                {
                    $activity_instrument = DB::table('activity_instrument')
                            ->select('activity_instrument_id')
                            ->where('activity_id',$id)
                            ->orderBy('activity_instrument_id','desc')
                            ->first();
                    $activity_instrument = intval($activity_instrument["activity_instrument_id"]) +1;
                    DB::table('activity_instrument')
                            ->insert(
                                array('activity_id'=>$id,
                                        'activity_instrument_id'=>$activity_instrument,
                                        'instrument_id'=>$v,
                                        'permission_id'=>$instrument_permission[$k]
                                )
                            );
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

    public function ajax_get_instrument() {

        $id = Request::input('id');
        $listResult = DB::table('instrument_data');
        $listResult = $listResult->select('id','name')
                                    ->whereIn('instrument_platform_id',$id)
                                    ->get();
        
        return $listResult;
    }
}
