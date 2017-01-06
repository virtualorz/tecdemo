<?php

namespace App\Http\Controllers\Official;

//
use DB;
use DBOperator;
use DBProcedure;
use Request;
use Route;
use Config;
use FileUpload;
use Log;
use User;
use Validator;

class MemberActivityController extends Controller {

    public function index() {
        $activityResult = DB::table('activity_reservation_data')
                            ->select('activity_data.id',
                                        'activity_data.uid',
                                        'activity_data.salt',
                                        'activity_data.activity_id',
                                        'activity_reservation_data.created_at',
                                        DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y.%m.%d") as start_dt'),
                                        DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y.%m.%d") as end_dt'),
                                        'activity_data.activity_name',
                                        'activity_data.level',
                                        'activity_data.time',
                                        'activity_reg.reason')
                            ->leftJoin('activity_data','activity_reservation_data.activity_id','=','activity_data.id')
                            ->leftJoin('activity_reg',function($join){
                                $join->on('activity_data.id','=','activity_reg.activity_id')
                                    ->where('activity_reg.member_id','=',User::id());
                            })
                            ->where('activity_reservation_data.member_id','=',User::Id())
                            ->where('activity_reservation_data.reservation_status','=',1)
                            ->where('activity_reservation_data.attend_status','=',0)
                            ->orderBy('activity_data.start_dt','desc')
                            ->get();

        $historyResult = DB::table('activity_reservation_data')
                            ->select('activity_data.uid',
                                        'activity_data.salt',
                                        'activity_data.activity_id',
                                        DB::raw('DATE_FORMAT(activity_data.start_dt, "%Y.%m.%d") as start_dt'),
                                        DB::raw('DATE_FORMAT(activity_data.end_dt, "%Y.%m.%d") as end_dt'),
                                        'activity_data.activity_name',
                                        'activity_data.level',
                                        'activity_data.time',
                                        'activity_data.pass_type',
                                        'activity_reservation_data.reservation_status',
                                        'activity_reservation_data.attend_status',
                                        'activity_reservation_data.pass_status')
                            ->leftJoin('activity_data','activity_reservation_data.activity_id','=','activity_data.id')
                            ->where('activity_reservation_data.member_id','=',User::Id())
                            ->orderBy('activity_data.start_dt','desc')
                             ->paginate(Config::get('pagination.items'));
        $pagination = $this->getPagination(json_decode($historyResult->toJson(),true)['total']);

        $this->view->with('activityResult', $activityResult);
        $this->view->with('historyResult', $historyResult);
        $this->view->with('pagination', $pagination);
        
        return $this->view;
    }

    public function reg() {
        $id = explode('-',Route::input('id'));
        $dataResult = DB::table('activity_data')
                            ->select('id','activity_name')
                            ->where('uid',$id[0])
                            ->where('salt',$id[1])
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);
        
        return $this->view;
    }

    public function detail() {
        $id = Route::input('id');
        $dataResult = DB::table('member_journal')
                            ->select('member_journal.*',
                                        DB::raw('DATE_FORMAT(member_journal.created_at, "%Y.%m.%d") as created_at'),
                                        DB::raw('DATE_FORMAT(member_journal.release_dt, "%Y.%m.%d") as release_dt'))
                            ->where('member_data_id',User::id())
                            ->where('member_journal_id',$id)
                            ->get();

        $this->view->with('dataResult', $dataResult[0]);
        $this->view->with('journal', Config::get('data.journal'));
        
        return $this->view;
    }

    ##

    public function ajax_reg() {
        $validator = Validator::make(Request::all(), [
                    'reason' => 'string|required',
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        
        try {
            DB::transaction(function(){

                DB::table('activity_reg')
                        ->insert(array(
                            'created_at'=>date('Y-m-d H:i:s'),
                            'activity_id'=>Request::input('id'),
                            'member_id'=>User::id(),
                            'reason'=>Request::input('reason')
                        ));

            });

        } catch (\PDOException $ex) {
            DB::rollBack();

            \Log::error($ex->getMessage());
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.database');
            return $this->view;
        }

        $this->view['msg'] = trans('message.success.reg');
        return $this->view;
    }

    public function ajax_cancel() {
        $validator = Validator::make(Request::all(), [
                    'id' => 'string|required'
        ]);
        if ($validator->fails()) {
            $this->view['result'] = 'no';
            $this->view['msg'] = trans('message.error.validation');
            $this->view['detail'] = $validator->errors();

            return $this->view;
        }
        
        try {
            $id = explode('_',Request::input('id', '0_0'));

                $result_before = DB::table('activity_reservation_data')
                                    ->where('activity_id',$id[0])
                                    ->where('member_id',User::id())
                                    ->where('created_at',$id[1])
                                    ->get();
                DB::table('activity_reservation_data')
                    ->where('activity_id',$id[0])
                    ->where('member_id',User::id())
                    ->where('created_at',$id[1])
                    ->update(['reservation_status'=>'0']);
                
                $result_after = DB::table('activity_reservation_data')
                                    ->where('activity_id',$id[0])
                                    ->where('member_id',User::id())
                                    ->where('created_at',$id[1])
                                    ->get();
                DBProcedure::writeLog([
                    'table' => 'activity_reservation_data',
                    'operator' => DBOperator::OP_UPDATE,
                    'data_before' => isset($result_before[0]) ? $result_before[0] : [],
                    'data_after' => isset($result_after[0]) ? $result_after[0] : [],
                    'member_id' => User::id()
                ]);
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

        
        $this->view['msg'] = trans('message.success.cancel');
        return $this->view;
    }
}
