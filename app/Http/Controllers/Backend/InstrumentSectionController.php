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

class InstrumentSectionController extends Controller {

    public function index() {

        $listResult = DB::table('instrument_section');

        $listResult = $listResult->select('id','section_type',
                                        DB::raw('DATE_FORMAT(start_time, "%H:%i") as start_time'),
                                        DB::raw('DATE_FORMAT(end_time, "%H:%i") as end_time'),
                                        DB::raw('DATE_FORMAT(created_at, "%Y/%m/%d") as created_at'),
                                        'enable')
                                    ->orderBy('start_time','asc')
                                    ->orderBy('end_time','asc')
                                    ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($listResult->toJson(),true)['total']);
        
        $this->view->with('listResult', $listResult);
        $this->view->with('pagination', $pagination);
        $this->view->with('section_type', Config::get('data.section_type'));
        return $this->view;
    }

    public function add() {

        $this->view->with('section_type', Config::get('data.section_type'));
        return $this->view;
    }

    public function edit() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('instrument_section')
                            ->select('instrument_section.*',
                                    DB::raw('DATE_FORMAT(instrument_section.created_at, "%Y/%m/%d %H:%i:%s") as created_at'),
                                    'member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','instrument_section.create_admin_id','=','member_admin.id')
                            ->where('instrument_section.id',$id)
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('section_type', Config::get('data.section_type'));

        return $this->view;
    }

    public function detail() {
        $id = Route::input('id', 0);
        $dataResult = DB::table('instrument_section')
                            ->select('instrument_section.*',
                                    DB::raw('DATE_FORMAT(instrument_section.created_at, "%Y/%m/%d %H:%i:%s") as created_at'),
                                    'member_admin.name as created_admin_name')
                            ->leftJoin('member_admin','instrument_section.create_admin_id','=','member_admin.id')
                            ->where('instrument_section.id',$id)
                            ->get();
                            
        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('section_type', Config::get('data.section_type'));
        
        return $this->view;
    }

    ##

    public function ajax_add() {
        $invalid = [];
        $validator = Validator::make(Request::all(), [
                    'section_type' => 'integer|required',
                    'start_time' => 'string|required|before:end_time',
                    'end_time' => 'string|required',
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

        try {
            DB::transaction(function(){
                $id = DB::table('instrument_section')
                        ->insertGetId(
                            array('created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s'),
                                    'section_type'=>Request::input('section_type'),
                                    'start_time'=>Request::input('start_time'),
                                    'end_time'=>Request::input('end_time'),
                                    'enable'=>Request::input('enable'),
                                    'create_admin_id'=>User::id(),
                                    'update_admin_id'=>User::id()
                            )
                        );
                $result_after = DB::table('instrument_section')
                                ->where('id',$id)
                                ->get();
                DBProcedure::writeLog([
                    'table' => 'instrument_section',
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
                    'section_type' => 'integer|required',
                    'start_time' => 'string|required|before:end_time',
                    'end_time' => 'string|required',
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
                $result_before = DB::table('instrument_section')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DB::table('instrument_section')
                    ->where('id',Request::input('id'))
                    ->update(['section_type'=>Request::input('section_type'),
                                'start_time'=>Request::input('start_time'),
                                'end_time'=>Request::input('end_time'),
                                'enable'=>Request::input('enable'),
                                'update_admin_id'=>User::id()
                    ]);
                $result_after = DB::table('instrument_section')
                                    ->where('id',Request::input('id'))
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'instrument_section',
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

                $result_before = DB::table('instrument_section')
                                    ->where('id',$id)
                                    ->get();
                //先檢查是否有儀器，有活動則無法刪除
                $pass_count = DB::table('instrument_section_set')
                    ->where('instrument_section_id',$id)
                    ->count();
                if($pass_count != 0)
                {
                    $this->view['result'] = 'no';
                    $this->view['msg'] = trans('message.error.validation');
                    $this->view['detail'] = array($result_before[0]['start_time'].'~'.$result_before[0]['end_time']."已有儀器設定無法刪除");
                    return $this->view;
                }
                
                DB::table('instrument_section')
                    ->where('id',$id)
                    ->delete();
                DBProcedure::writeLog([
                    'table' => 'instrument_section',
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
